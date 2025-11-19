<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = [
            'dataset_five_thousand_records',
            'dataset_three_thousand_records',
            'dataset_ten_thousand_records',
            'dataset_eight_thousand_records',
        ];

        foreach ($tables as $tableName) {
            Schema::create($tableName, function (Blueprint $table) {
                $table->id();

                for ($i = 1; $i <= 70; $i++) {
                    $table->text("col_{$i}")->nullable();
                }

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'dataset_five_thousand_records',
            'dataset_three_thousand_records',
            'dataset_ten_thousand_records',
            'dataset_eight_thousand_records',
        ];

        foreach ($tables as $tableName) {
            Schema::dropIfExists($tableName);
        }
    }
};
