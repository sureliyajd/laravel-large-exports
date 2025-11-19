<?php

namespace App\Exports;

use App\Models\Export;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeExport;
use Throwable;

class GenericTableExport implements FromQuery, WithHeadings, WithChunkReading, ShouldQueue, WithEvents
{
    protected string $table;
    protected array $columns;
    protected int $exportId;

    public function __construct(string $table, array $columns, int $exportId)
    {
        $this->table = $table;
        $this->columns = $columns;
        $this->exportId = $exportId;
    }

    /**
     * @return Builder
     */
    public function query()
    {
        return DB::table($this->table)->select($this->columns);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return $this->columns;
    }

    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return (int) config('excel.exports.chunk_size', 1000);
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            BeforeExport::class => function (BeforeExport $event) {
                $export = Export::find($this->exportId);
                if ($export && $export->status === 'pending') {
                    $export->update(['status' => 'processing']);
                }
            },
            AfterSheet::class => function (AfterSheet $event) {
                // Only update if still processing (to avoid multiple updates)
                $export = Export::find($this->exportId);
                if ($export && $export->status === 'processing') {
                    $export->update(['status' => 'done']);
                }
            },
        ];
    }

    /**
     * Handle a failed export job.
     */
    public function failed(Throwable $exception): void
    {
        $export = Export::find($this->exportId);
        if ($export) {
            $export->update([
                'status' => 'failed',
                'error' => $exception->getMessage(),
            ]);
        }
    }
}

