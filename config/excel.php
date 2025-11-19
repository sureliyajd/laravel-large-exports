<?php

use Maatwebsite\Excel\Excel;
use PhpOffice\PhpSpreadsheet\Reader\Csv;

return [
    'exports' => [
        /*
        |--------------------------------------------------------------------------
        | Chunk size
        |--------------------------------------------------------------------------
        |
        | When using FromQuery, the query is automatically chunked.
        | Override globally via EXCEL_EXPORT_CHUNK_SIZE.
        |
        */
        'chunk_size'             => (int) env('EXCEL_EXPORT_CHUNK_SIZE', 1000),

        /*
        |--------------------------------------------------------------------------
        | Excel-specific memory limit
        |--------------------------------------------------------------------------
        |
        | Allows overriding PHP's memory limit just for Excel jobs.
        | Set with EXCEL_MEMORY_LIMIT (e.g. 512M, 1G). Leave null to skip.
        |
        */
        'memory_limit'           => env('EXCEL_MEMORY_LIMIT'),

        /*
        |--------------------------------------------------------------------------
        | Pre-calculate formulas during export
        |--------------------------------------------------------------------------
        */
        'pre_calculate_formulas' => false,

        /*
        |--------------------------------------------------------------------------
        | Enable strict null comparison
        |--------------------------------------------------------------------------
        |
        | When enabling strict null comparison empty cells ('') will
        | be added to the sheet.
        */
        'strict_null_comparison' => false,

        /*
        |--------------------------------------------------------------------------
        | CSV Settings
        |--------------------------------------------------------------------------
        |
        | Configure e.g. delimiter, enclosure and line ending for CSV exports.
        |
        */
        'csv'                    => [
            'delimiter'              => ',',
            'enclosure'              => '"',
            'line_ending'            => PHP_EOL,
            'use_bom'                => false,
            'include_separator_line' => false,
            'excel_compatibility'    => false,
            'output_encoding'        => '',
            'test_auto_detect'       => true,
        ],

        /*
        |--------------------------------------------------------------------------
        | Worksheet properties
        |--------------------------------------------------------------------------
        |
        | Configure e.g. default title, creator, subject,...
        |
        */
        'properties'             => [
            'creator'        => '',
            'lastModifiedBy' => '',
            'title'          => '',
            'description'    => '',
            'subject'        => '',
            'keywords'       => '',
            'category'       => '',
            'manager'        => '',
            'company'        => '',
        ],
    ],

    'imports'            => [
        'read_only'    => true,
        'ignore_empty' => false,
        'heading_row'  => [
            'formatter' => 'slug',
        ],
        'csv'          => [
            'delimiter'        => null,
            'enclosure'        => '"',
            'escape_character' => '\\',
            'contiguous'       => false,
            'input_encoding'   => Csv::GUESS_ENCODING,
        ],
        'properties'   => [
            'creator'        => '',
            'lastModifiedBy' => '',
            'title'          => '',
            'description'    => '',
            'subject'        => '',
            'keywords'       => '',
            'category'       => '',
            'manager'        => '',
            'company'        => '',
        ],
        'cells'        => [
            'middleware' => [
                //\Maatwebsite\Excel\Middleware\TrimCellValue::class,
                //\Maatwebsite\Excel\Middleware\ConvertEmptyCellValuesToNull::class,
            ],
        ],
    ],

    'extension_detector' => [
        'xlsx'     => Excel::XLSX,
        'xlsm'     => Excel::XLSX,
        'xltx'     => Excel::XLSX,
        'xltm'     => Excel::XLSX,
        'xls'      => Excel::XLS,
        'xlt'      => Excel::XLS,
        'ods'      => Excel::ODS,
        'ots'      => Excel::ODS,
        'slk'      => Excel::SLK,
        'xml'      => Excel::XML,
        'gnumeric' => Excel::GNUMERIC,
        'htm'      => Excel::HTML,
        'html'     => Excel::HTML,
        'csv'      => Excel::CSV,
        'tsv'      => Excel::TSV,
        'pdf'      => Excel::DOMPDF,
    ],

    'value_binder'       => [
        'default' => Maatwebsite\Excel\DefaultValueBinder::class,
    ],

    'cache'        => [
        'driver'      => 'memory',
        'batch'       => [
            'memory_limit' => 60000,
        ],
        'illuminate'  => [
            'store' => null,
        ],
        'default_ttl' => 10800,
    ],

    'transactions' => [
        'handler' => 'db',
        'db'      => [
            'connection' => null,
        ],
    ],

    'temporary_files' => [
        'local_path'          => storage_path('framework/cache/laravel-excel'),
        'local_permissions'   => [
            // 'dir'  => 0755,
            // 'file' => 0644,
        ],
        'remote_disk'         => null,
        'remote_prefix'       => null,
        'force_resync_remote' => null,
    ],
];

