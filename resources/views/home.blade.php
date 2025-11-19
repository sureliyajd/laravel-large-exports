<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Large Data Export System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            padding: 30px;
            margin-bottom: 30px;
        }
        
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 2rem;
        }
        
        .subtitle {
            color: #666;
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }
        
        select, input[type="text"], input[type="number"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        
        select:focus, input[type="text"]:focus, input[type="number"]:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .columns-container {
            max-height: 300px;
            overflow-y: auto;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px;
            margin-top: 10px;
        }
        
        .column-item {
            display: flex;
            align-items: center;
            padding: 8px 0;
        }
        
        .column-item input[type="checkbox"] {
            margin-right: 10px;
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        
        .column-item label {
            margin: 0;
            cursor: pointer;
            font-weight: normal;
        }
        
        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        
        .exports-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .exports-table th,
        .exports-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .exports-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-processing {
            background: #cfe2ff;
            color: #084298;
        }
        
        .status-done {
            background: #d1e7dd;
            color: #0f5132;
        }
        
        .status-failed {
            background: #f8d7da;
            color: #842029;
        }
        
        .loading {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-left: 10px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .info-box {
            padding: 12px;
            border: 2px dashed #e0e0e0;
            border-radius: 8px;
            background: #f8f9ff;
            color: #333;
            font-weight: 500;
        }

        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>📊 Large Data Export System</h1>
            <p class="subtitle">Export your database tables to CSV or XLSX format with queued processing</p>
            
            <div id="alert-container"></div>
            
            <form id="export-form">
                <div class="form-group">
                    <label for="table-select">Select Table:</label>
                    <select id="table-select" name="table" required>
                        <option value="">Loading tables...</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Select Columns:</label>
                    <div id="columns-container" class="columns-container hidden">
                        <p style="color: #999; text-align: center; padding: 20px;">
                            Select a table to load columns
                        </p>
                    </div>
                </div>
                
                <div class="form-group hidden" id="row-info">
                    <label>Table Rows:</label>
                    <div class="info-box" id="row-count-text">
                        Select a table to view the available rows.
                    </div>
                </div>

                <div class="form-group">
                    <label for="format-select">Export Format:</label>
                    <select id="format-select" name="format" required>
                        <option value="csv">CSV</option>
                        <option value="xlsx">XLSX (Excel)</option>
                    </select>
                </div>
                
                <button type="submit" class="btn" id="export-btn">
                    Start Export
                </button>
            </form>
        </div>
        
        <div class="card">
            <h2>Export History</h2>
            <table class="exports-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Table</th>
                        <th>Columns</th>
                        <th>Format</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="exports-tbody">
                    @forelse($exports as $export)
                    <tr>
                        <td>{{ $export->id }}</td>
                        <td>{{ $export->table_name }}</td>
                        <td>{{ count($export->columns) }} columns</td>
                        <td>{{ strtoupper($export->format) }}</td>
                        <td>
                            <span class="status-badge status-{{ $export->status }}">
                                {{ $export->status }}
                            </span>
                        </td>
                        <td>{{ $export->created_at->format('Y-m-d H:i:s') }}</td>
                        <td>
                            @if($export->status === 'done')
                                <a href="{{ route('export.download', $export->id) }}" class="btn" style="padding: 6px 12px; font-size: 14px; text-decoration: none; display: inline-block;">
                                    Download
                                </a>
                            @elseif($export->status === 'processing')
                                <span class="loading"></span>
                            @elseif($export->status === 'failed')
                                <span style="color: #dc3545; font-size: 12px;">{{ \Illuminate\Support\Str::limit($export->error ?? 'Export failed', 30) }}</span>
                            @else
                                <span style="color: #999;">Pending...</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: #999; padding: 40px;">
                            No exports yet. Create your first export above!
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <script>
        let currentRowCount = 0;

        // Load tables on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadTables();
            setInterval(refreshExports, 5000); // Refresh every 5 seconds
        });
        
        // Load tables
        async function loadTables() {
            try {
                const response = await fetch('/tables');
                const data = await response.json();
                
                const select = document.getElementById('table-select');
                select.innerHTML = '<option value="">Select a table...</option>';
                
                data.tables.forEach(table => {
                    const option = document.createElement('option');
                    option.value = table;
                    option.textContent = table;
                    select.appendChild(option);
                });
            } catch (error) {
                showAlert('Error loading tables: ' + error.message, 'error');
            }
        }
        
        // Load columns when table is selected
        const rowInfo = document.getElementById('row-info');
        const rowCountText = document.getElementById('row-count-text');
        const exportBtn = document.getElementById('export-btn');

        function resetRowInfo() {
            currentRowCount = 0;
            rowInfo.classList.add('hidden');
            rowCountText.textContent = 'Select a table to view the available rows.';
            exportBtn.disabled = false;
        }

        function updateRowInfo(rowCount) {
            currentRowCount = rowCount;
            rowInfo.classList.remove('hidden');

            if (rowCount === 0) {
                rowCountText.textContent = 'No rows found for this table.';
                exportBtn.disabled = true;
                return;
            }

            const formattedCount = rowCount.toLocaleString();
            rowCountText.textContent = `${formattedCount} total rows available.`;
            exportBtn.disabled = false;
        }

        resetRowInfo();

        document.getElementById('table-select').addEventListener('change', async function() {
            const table = this.value;
            const container = document.getElementById('columns-container');
            
            if (!table) {
                container.classList.add('hidden');
                resetRowInfo();
                return;
            }
            
            container.classList.remove('hidden');
            container.innerHTML = '<p style="text-align: center; padding: 20px; color: #999;">Loading columns...</p>';
            
            try {
                const response = await fetch(`/columns/${encodeURIComponent(table)}`);
                const data = await response.json();
                
                if (data.error) {
                    container.innerHTML = `<p style="color: #dc3545; text-align: center; padding: 20px;">${data.error}</p>`;
                    resetRowInfo();
                    return;
                }
                
                container.innerHTML = '';
                data.columns.forEach(column => {
                    const div = document.createElement('div');
                    div.className = 'column-item';
                    div.innerHTML = `
                        <input type="checkbox" id="col-${column}" name="columns[]" value="${column}" checked>
                        <label for="col-${column}">${column}</label>
                    `;
                    container.appendChild(div);
                });
                
                // Add select all/none buttons
                const controls = document.createElement('div');
                controls.style.marginBottom = '10px';
                controls.innerHTML = `
                    <button type="button" onclick="selectAllColumns(true)" style="padding: 6px 12px; margin-right: 10px; border: 1px solid #ddd; background: #f8f9fa; border-radius: 4px; cursor: pointer;">Select All</button>
                    <button type="button" onclick="selectAllColumns(false)" style="padding: 6px 12px; border: 1px solid #ddd; background: #f8f9fa; border-radius: 4px; cursor: pointer;">Deselect All</button>
                `;
                container.insertBefore(controls, container.firstChild);

                updateRowInfo(data.row_count ?? 0);
            } catch (error) {
                container.innerHTML = `<p style="color: #dc3545; text-align: center; padding: 20px;">Error loading columns: ${error.message}</p>`;
                resetRowInfo();
            }
        });
        
        // Select all/none columns
        function selectAllColumns(select) {
            const checkboxes = document.querySelectorAll('#columns-container input[type="checkbox"]');
            checkboxes.forEach(cb => cb.checked = select);
        }
        
        // Handle form submission
        document.getElementById('export-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const table = document.getElementById('table-select').value;
            const format = document.getElementById('format-select').value;
            const checkboxes = document.querySelectorAll('#columns-container input[type="checkbox"]:checked');
            const columns = Array.from(checkboxes).map(cb => cb.value);
            
            if (!table) {
                showAlert('Please select a table', 'error');
                return;
            }
            
            if (columns.length === 0) {
                showAlert('Please select at least one column', 'error');
                return;
            }

            const btn = document.getElementById('export-btn');
            btn.disabled = true;
            btn.textContent = 'Processing...';
            
            try {
                const response = await fetch('/export', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({
                        table: table,
                        columns: columns,
                        format: format
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showAlert('Export queued successfully! It will appear in the history below.', 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showAlert(data.error || 'Export failed', 'error');
                }
            } catch (error) {
                showAlert('Error: ' + error.message, 'error');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Start Export';
            }
        });
        
        // Show alert
        function showAlert(message, type) {
            const container = document.getElementById('alert-container');
            const alert = document.createElement('div');
            alert.className = `alert alert-${type}`;
            alert.textContent = message;
            container.innerHTML = '';
            container.appendChild(alert);
            
            setTimeout(() => {
                alert.remove();
            }, 5000);
        }
        
        // Refresh exports table
        async function refreshExports() {
            // Simple page reload for now - could be improved with AJAX
            // window.location.reload();
        }
    </script>
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
</body>
</html>

