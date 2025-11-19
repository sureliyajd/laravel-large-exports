<?php

namespace Database\Seeders;

use App\Models\Registration;
use Illuminate\Database\Seeder;

class AdditionalRegistrationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $additional = 50_000;
        $batchSize = 1_000;
        $batches = (int) ceil($additional / $batchSize);

        foreach (range(1, $batches) as $batch) {
            $currentBatchSize = min($batchSize, $additional - (($batch - 1) * $batchSize));

            Registration::factory()
                ->count($currentBatchSize)
                ->create();
        }
    }
}

