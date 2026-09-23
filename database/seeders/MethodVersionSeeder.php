<?php

namespace Database\Seeders;

use App\Models\Laboratory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MethodVersionSeeder extends Seeder
{
    public function run(): void
    {
        $lab = Laboratory::firstOrCreate(
            ['code' => 'LAB-DEFAULT'],
            [
                'name' => env('LAB_NAME', 'Default Laboratory'),
                'active' => true,
            ]
        );

        DB::table('method_versions')->upsert(
            [
                [
                    'id' => (string) Str::uuid(),
                    'laboratory_id' => $lab->id,
                    'code' => 'DO-SNI-0698914-2004',
                    'name' => 'Dissolved Oxygen — Titration',
                    'standard' => 'SNI 06-6989.14-2004',
                    'version' => '2004',
                    'active' => true,
                    'notes' => 'Metode titrasi/yodometri modifikasi azida.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => (string) Str::uuid(),
                    'laboratory_id' => $lab->id,
                    'code' => 'BOD-SNI-0698972-2009',
                    'name' => 'Biochemical Oxygen Demand 5 days',
                    'standard' => 'SNI 6989.72:2009',
                    'version' => '2009',
                    'active' => true,
                    'notes' => 'Metode BOD5.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ],
            ['laboratory_id', 'code'],
            [
                'name',
                'standard',
                'version',
                'active',
                'notes',
                'updated_at',
            ]
        );
    }
}
