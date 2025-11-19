<?php

namespace App\Http\Controllers;

use App\Exports\GenericTableExport;
use App\Models\Export;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    /**
     * Show the export UI and history
     */
    public function index()
    {
        $exports = Export::orderBy('created_at', 'desc')->take(20)->get();
        return view('home', compact('exports'));
    }

    /**
     * Get list of all tables in the database
     */
    public function tables()
    {
        try {
            $connection = DB::connection();
            $database = $connection->getDatabaseName();
            
            // Get tables based on database driver
            $driver = $connection->getDriverName();
            
            if ($driver === 'sqlite') {
                $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%' ORDER BY name");
                $tableNames = array_map(fn($table) => $table->name, $tables);
            } elseif ($driver === 'mysql') {
                $tables = DB::select("SELECT TABLE_NAME as name FROM information_schema.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_TYPE = 'BASE TABLE'", [$database]);
                $tableNames = array_map(fn($table) => $table->name, $tables);
            } elseif ($driver === 'pgsql') {
                $tables = DB::select("SELECT tablename as name FROM pg_tables WHERE schemaname = 'public' ORDER BY tablename");
                $tableNames = array_map(fn($table) => $table->name, $tables);
            } else {
                $tableNames = [];
            }
            
            return response()->json(['tables' => $tableNames]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get columns for a specific table
     */
    public function columns(string $table)
    {
        try {
            $columns = DB::getSchemaBuilder()->getColumnListing($table);
            $rowCount = DB::table($table)->count();

            return response()->json([
                'columns' => $columns,
                'row_count' => $rowCount,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Trigger a queued export
     */
    public function export(Request $request)
    {
        $request->validate([
            'table' => 'required|string',
            'columns' => 'required|array|min:1',
            'format' => 'required|in:csv,xlsx',
        ]);

        try {
            // Verify table exists by trying to get columns
            // This will throw an exception if table doesn't exist
            try {
                $availableColumns = DB::getSchemaBuilder()->getColumnListing($request->table);
                if (empty($availableColumns)) {
                    return response()->json(['error' => 'Table not found or has no columns'], 404);
                }
            } catch (\Exception $e) {
                return response()->json(['error' => 'Table not found: ' . $e->getMessage()], 404);
            }

            // Verify columns exist
            $invalidColumns = array_diff($request->columns, $availableColumns);
            if (!empty($invalidColumns)) {
                return response()->json(['error' => 'Invalid columns: ' . implode(', ', $invalidColumns)], 400);
            }

            $totalRows = DB::table($request->table)->count();

            if ($totalRows === 0) {
                return response()->json(['error' => 'Selected table does not have any rows to export'], 400);
            }

            // Create export record
            $export = Export::create([
                'table_name' => $request->table,
                'columns' => $request->columns,
                'format' => $request->format,
                'status' => 'pending',
                'meta' => [
                    'total_rows' => $totalRows,
                ],
            ]);

            // Generate file path
            $fileName = 'export_' . $export->id . '_' . time() . '.' . $request->format;
            $filePath = 'exports/' . $fileName;

            // Update export with file path
            $export->update(['file_path' => $filePath]);

            // Dispatch queued export
            $exportClass = new GenericTableExport(
                $request->table,
                $request->columns,
                $export->id
            );

            try {
                if ($request->format === 'csv') {
                    Excel::queue($exportClass, $filePath, 'local', \Maatwebsite\Excel\Excel::CSV);
                } else {
                    Excel::queue($exportClass, $filePath, 'local', \Maatwebsite\Excel\Excel::XLSX);
                }
            } catch (\Exception $e) {
                $export->update([
                    'status' => 'failed',
                    'error' => $e->getMessage(),
                ]);
                throw $e;
            }

            return response()->json([
                'success' => true,
                'export_id' => $export->id,
                'message' => 'Export queued successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Download exported file
     */
    public function download($id)
    {
        $export = Export::findOrFail($id);

        if ($export->status !== 'done' || !$export->file_path) {
            return response()->json(['error' => 'Export not ready or file not found'], 404);
        }

        if (!Storage::exists($export->file_path)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        return Storage::download($export->file_path);
    }
}

