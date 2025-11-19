<?php

namespace Database\Seeders;

use App\Models\Registration;
use Illuminate\Database\Seeder;

class RegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $total = 20_000;
        $batchSize = 1_000;
        $batches = (int) ceil($total / $batchSize);

        foreach (range(1, $batches) as $batch) {
            $currentBatchSize = min($batchSize, $total - (($batch - 1) * $batchSize));

            Registration::factory()
                ->count($currentBatchSize)
                ->create();
        }
    }
}

