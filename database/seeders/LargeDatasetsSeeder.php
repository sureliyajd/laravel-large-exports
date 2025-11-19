<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LargeDatasetsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $tables = [
            'dataset_five_thousand_records' => 5_000,
            'dataset_three_thousand_records' => 3_000,
            'dataset_ten_thousand_records' => 10_000,
            'dataset_eight_thousand_records' => 8_000,
        ];

        foreach ($tables as $tableName => $targetRows) {
            DB::table($tableName)->truncate();
            $this->seedTable($faker, $tableName, $targetRows);
        }
    }

    protected function seedTable(\Faker\Generator $faker, string $tableName, int $targetRows): void
    {
        $batchSize = 100;
        $rows = [];

        for ($i = 0; $i < $targetRows; $i++) {
            $rows[] = $this->generateRow($faker);

            if (count($rows) === $batchSize) {
                DB::table($tableName)->insert($rows);
                $rows = [];
            }
        }

        if (!empty($rows)) {
            DB::table($tableName)->insert($rows);
        }
    }

    protected function generateRow(\Faker\Generator $faker): array
    {
        $row = [];
        $generators = [
            fn () => $faker->sentence(),
            fn () => $faker->company(),
            fn () => $faker->phoneNumber(),
            fn () => $faker->address(),
            fn () => $faker->email(),
            fn () => $faker->url(),
            fn () => $faker->uuid(),
        ];

        for ($col = 1; $col <= 70; $col++) {
            $generator = $generators[$col % count($generators)];
            $row["col_{$col}"] = Str::limit($generator(), 200, '');
        }

        $row['created_at'] = now();
        $row['updated_at'] = now();

        return $row;
    }
}
