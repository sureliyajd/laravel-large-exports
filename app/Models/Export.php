<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Export extends Model
{
    protected $fillable = [
        'table_name',
        'columns',
        'format',
        'file_path',
        'status',
        'error',
        'meta',
    ];

    protected $casts = [
        'columns' => 'array',
        'meta' => 'array',
    ];
}
