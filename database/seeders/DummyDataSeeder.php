<?php

namespace Database\Seeders;

use App\Models\AnalysisAudit;
use App\Models\AnalysisRun;
use App\Models\BodControl;
use App\Models\BodDilution;
use App\Models\Client;
use App\Models\ClientUser;
use App\Models\Instrument;
use App\Models\LabUser;
use App\Models\Laboratory;
use App\Models\Reagent;
use App\Models\Sample;
use App\Models\UncertaintyComponent;
use App\Models\UncertaintyModel;
use App\Services\BodEvaluationService;
use App\Services\LaboratoryCalculationService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DummyDataSeeder extends Seeder
{
    private LaboratoryCalculationService $calc;
    private BodEvaluationService $bodQc;

    public function run(): void
    {
        $this->calc = app(LaboratoryCalculationService::class);
        $this->bodQc = app(BodEvaluationService::class);
        $this->migrateLegacyDoRuns();

        $labs = [
            ['code' => 'LAB-DEFAULT', 'name' => 'Default Laboratory', 'address' => 'Jl. Laboratorium No. 1, Bandung', 'phone' => '022-7201111', 'email' => 'lab@default.local',
             'users' => [['name' => 'Nita Rahmi', 'email' => 'nita@lab.local', 'role' => 'analyst'], ['name' => 'Budi Santoso', 'email' => 'budi@lab.local', 'role' => 'analyst'], ['name' => 'Dewi Anggraini', 'email' => 'dewi@lab.local', 'role' => 'supervisor'], ['name' => 'Arif Hidayat', 'email' => 'arif@lab.local', 'role' => 'viewer']],
             'clients' => [['code' => 'PT-TIRTA', 'name' => 'PT Tirta Jaya Sukses', 'email' => 'tirta@example.id'], ['code' => 'CV-AQUA', 'name' => 'CV Aqua Pur Indonesia', 'email' => 'aquapur@example.id'], ['code' => 'PDAM-BGR', 'name' => 'PDAM Tirta Kahuripan', 'email' => 'pdam@example.id']],
             'samples' => [
                ['code' => 'SW-001', 'name' => 'Sampel Air Sungai Cikapundung', 'matrix' => 'Air sungai', 'customer' => 'PDAM Tirta Kahuripan'],
                ['code' => 'SW-002', 'name' => 'Sampel Air Hulu Sungai', 'matrix' => 'Air sungai', 'customer' => ''],
                ['code' => 'LW-001', 'name' => 'Sampel Air Danau', 'matrix' => 'Air danau', 'customer' => ''],
                ['code' => 'WW-001', 'name' => 'Efluen IPAL Domestik', 'matrix' => 'Air limbah domestik', 'customer' => 'CV Aqua Pur Indonesia'],
                ['code' => 'WW-002', 'name' => 'Air Limbah Industri Tekstil', 'matrix' => 'Air limbah industri', 'customer' => 'PT Tirta Jaya Sukses'],
                ['code' => 'WW-003', 'name' => 'Air Limbah Rumah Sakit', 'matrix' => 'Air limbah domestik', 'customer' => ''],
                ['code' => 'GW-001', 'name' => 'Sampel Air Tanah Sumur', 'matrix' => 'Air tanah', 'customer' => 'PDAM Tirta Kahuripan'],
                ['code' => 'TW-001', 'name' => 'Sampel Air Waduk', 'matrix' => 'Air danau', 'customer' => ''],
             ],
             'runs' => [
                ['sample' => 'SW-001', 'param' => 'DO', 'client' => 'PDAM-TIRTA', 'status' => 'APPROVED', 'days' => 1, 'tio' => 1.68, 'dup' => 1.71, 'analyst' => 'Nita Rahmi'],
                ['sample' => 'SW-002', 'param' => 'DO', 'client' => '', 'status' => 'APPROVED', 'days' => 2, 'tio' => 1.52, 'dup' => 1.55, 'analyst' => 'Budi Santoso'],
                ['sample' => 'LW-001', 'param' => 'DO', 'client' => '', 'status' => 'READY_FOR_REVIEW', 'days' => 3, 'tio' => 1.74, 'dup' => 1.76, 'analyst' => 'Nita Rahmi'],
                ['sample' => 'WW-001', 'param' => 'DO', 'client' => 'CV-AQUA', 'status' => 'DRAFT', 'days' => 4, 'tio' => 0.98, 'dup' => null, 'analyst' => 'Budi Santoso'],
                ['sample' => 'WW-002', 'param' => 'DO', 'client' => 'PT-TIRTA', 'status' => 'REVISION_REQUIRED', 'days' => 5, 'tio' => 2.22, 'dup' => 2.42, 'analyst' => 'Nita Rahmi'],
                ['sample' => 'SW-001', 'param' => 'BOD5', 'client' => 'PDAM-TIRTA', 'status' => 'APPROVED', 'days' => 1, 'dep' => 3.4, 'b' => 0.08, 'p' => 0.1, 'ph' => 7.2, 'gga' => 203.0, 'ggaOk' => true, 'dils' => [1, 2], 'analyst' => 'Budi Santoso'],
                ['sample' => 'WW-003', 'param' => 'BOD5', 'client' => '', 'status' => 'APPROVED', 'days' => 2, 'dep' => 3.1, 'b' => 0.12, 'p' => 0.05, 'ph' => 7.0, 'gga' => 196.5, 'ggaOk' => true, 'dils' => [1, 2], 'analyst' => 'Nita Rahmi'],
                ['sample' => 'WW-002', 'param' => 'BOD5', 'client' => 'PT-TIRTA', 'status' => 'READY_FOR_REVIEW', 'days' => 3, 'dep' => 2.8, 'b' => 0.05, 'p' => 0.02, 'ph' => 6.8, 'gga' => null, 'ggaOk' => false, 'dils' => [1, 3], 'analyst' => 'Budi Santoso'],
                ['sample' => 'GW-001', 'param' => 'BOD5', 'client' => '', 'status' => 'DRAFT', 'days' => 4, 'dep' => 2.5, 'b' => 0.18, 'p' => 0.15, 'ph' => 7.4, 'gga' => null, 'ggaOk' => false, 'dils' => [2], 'analyst' => 'Nita Rahmi'],
                ['sample' => 'TW-001', 'param' => 'BOD5', 'client' => '', 'status' => 'REVISION_REQUIRED', 'days' => 6, 'dep' => 4.1, 'b' => 0.10, 'p' => 0.04, 'ph' => 7.1, 'gga' => 210.0, 'ggaOk' => false, 'dils' => [1, 2], 'analyst' => 'Budi Santoso'],
             ]],
            ['code' => 'LAB-ENVIR', 'name' => 'EnviroLab Nusantara', 'address' => 'Jl. Dipatiukur No. 45, Bandung', 'phone' => '022-7204581', 'email' => 'enviro@envirolab.co.id',
             'users' => [['name' => 'Rahmat Wijaya', 'email' => 'rahmat@envirolab.co.id', 'role' => 'admin'], ['name' => 'Sinta Marlina', 'email' => 'sinta@envirolab.co.id', 'role' => 'analyst'], ['name' => 'Bagas Pratama', 'email' => 'bagas@envirolab.co.id', 'role' => 'analyst'], ['name' => 'Tania Sari', 'email' => 'tania@envirolab.co.id', 'role' => 'supervisor'], ['name' => 'Erik Sanjaya', 'email' => 'erik@envirolab.co.id', 'role' => 'viewer']],
             'clients' => [['code' => 'IND-KARYA', 'name' => 'PT Indah Karya Tekstil', 'email' => 'indahkarya@example.id'], ['code' => 'HTL-GRAND', 'name' => 'Hotel Grand Indonesia', 'email' => 'hotel@example.id']],
             'samples' => [
                ['code' => 'EW-001', 'name' => 'Efluen Industri Tekstil', 'matrix' => 'Air limbah industri', 'customer' => 'PT Indah Karya Tekstil'],
                ['code' => 'EW-002', 'name' => 'Efluen IPAL Hotel', 'matrix' => 'Air limbah domestik', 'customer' => 'Hotel Grand Indonesia'],
                ['code' => 'EW-003', 'name' => 'Air Sungai di Hilir Industri', 'matrix' => 'Air sungai', 'customer' => ''],
                ['code' => 'EW-004', 'name' => 'Air Limbah Laundry', 'matrix' => 'Air limbah domestik', 'customer' => 'Hotel Grand Indonesia'],
                ['code' => 'EW-005', 'name' => 'Air Baku Unit Pengolahan', 'matrix' => 'Air sungai', 'customer' => ''],
             ],
             'runs' => [
                ['sample' => 'EW-001', 'param' => 'DO', 'client' => 'IND-KARYA', 'status' => 'APPROVED', 'days' => 1, 'tio' => 1.35, 'dup' => 1.38, 'analyst' => 'Sinta Marlina'],
                ['sample' => 'EW-003', 'param' => 'DO', 'client' => '', 'status' => 'DRAFT', 'days' => 3, 'tio' => 1.71, 'dup' => null, 'analyst' => 'Bagas Pratama'],
                ['sample' => 'EW-002', 'param' => 'BOD5', 'client' => 'HTL-GRAND', 'status' => 'APPROVED', 'days' => 2, 'dep' => 3.8, 'b' => 0.15, 'p' => 0.03, 'ph' => 7.3, 'gga' => 199.0, 'ggaOk' => true, 'dils' => [1, 2], 'analyst' => 'Sinta Marlina'],
                ['sample' => 'EW-004', 'param' => 'BOD5', 'client' => 'HTL-GRAND', 'status' => 'READY_FOR_REVIEW', 'days' => 4, 'dep' => 3.3, 'b' => 0.07, 'p' => 0.05, 'ph' => 7.0, 'gga' => null, 'ggaOk' => false, 'dils' => [1], 'analyst' => 'Bagas Pratama'],
             ]],
            ['code' => 'LAB-WATER', 'name' => 'WaterCare Laboratory', 'address' => 'Jl. M.H. Thamrin Kav 12, Jakarta Pusat', 'phone' => '021-39870122', 'email' => 'info@watercarelab.id',
             'users' => [['name' => 'Maya Putri', 'email' => 'maya@watercarelab.id', 'role' => 'admin'], ['name' => 'Joko Susilo', 'email' => 'joko@watercarelab.id', 'role' => 'analyst'], ['name' => 'Sri Wahyuni', 'email' => 'sri@watercarelab.id', 'role' => 'analyst'], ['name' => 'Fajar Nugroho', 'email' => 'fajar@watercarelab.id', 'role' => 'supervisor']],
             'clients' => [['code' => 'PDAM-MDN', 'name' => 'PDAM Tirta Mandiri', 'email' => 'pdam@example.id'], ['code' => 'PT-MSDH', 'name' => 'PT Makanan Sehat', 'email' => 'makanan@example.id']],
             'samples' => [
                ['code' => 'WJ-001', 'name' => 'Air Baku Sungai Ciliwung', 'matrix' => 'Air sungai', 'customer' => 'PDAM Tirta Mandiri'],
                ['code' => 'WJ-002', 'name' => 'Efluen Industri Makanan', 'matrix' => 'Air limbah industri', 'customer' => 'PT Makanan Sehat'],
                ['code' => 'WJ-003', 'name' => 'Air Danau Rawa Pening', 'matrix' => 'Air danau', 'customer' => ''],
                ['code' => 'WJ-004', 'name' => 'Air Limbah Restoran', 'matrix' => 'Air limbah domestik', 'customer' => 'PT Makanan Sehat'],
             ],
             'runs' => [
                ['sample' => 'WJ-001', 'param' => 'DO', 'client' => 'PDAM-MDN', 'status' => 'APPROVED', 'days' => 1, 'tio' => 1.83, 'dup' => 1.86, 'analyst' => 'Joko Susilo'],
                ['sample' => 'WJ-003', 'param' => 'DO', 'client' => '', 'status' => 'READY_FOR_REVIEW', 'days' => 2, 'tio' => 1.60, 'dup' => 1.62, 'analyst' => 'Sri Wahyuni'],
                ['sample' => 'WJ-002', 'param' => 'BOD5', 'client' => 'PT-MSDH', 'status' => 'APPROVED', 'days' => 3, 'dep' => 2.9, 'b' => 0.09, 'p' => 0.05, 'ph' => 6.9, 'gga' => 197.0, 'ggaOk' => true, 'dils' => [1, 2], 'analyst' => 'Joko Susilo'],
                ['sample' => 'WJ-004', 'param' => 'BOD5', 'client' => 'PT-MSDH', 'status' => 'DRAFT', 'days' => 5, 'dep' => 3.6, 'b' => 0.11, 'p' => 0.03, 'ph' => 7.2, 'gga' => null, 'ggaOk' => false, 'dils' => [1], 'analyst' => 'Sri Wahyuni'],
             ]],
        ];

        foreach ($labs as $labSpec) {
            $lab = $this->seedLab($labSpec);
            $this->seedUsers($lab, $labSpec['users']);
            $this->seedMethods($lab);
            $clients = $this->seedClients($lab, $labSpec['clients']);
            $this->seedInstruments($lab);
            $this->seedReagents($lab);
            $this->seedSamples($lab, $labSpec['samples']);
            $this->seedRuns($lab, $labSpec['runs'], $clients);
            $this->seedUncertainty($lab);
        }
    }

    private function seedLab(array $spec): Laboratory
    {
        return Laboratory::updateOrCreate(['code' => $spec['code']], [
            'name' => $spec['name'],
            'address' => $spec['address'],
            'phone' => $spec['phone'],
            'email' => $spec['email'],
            'active' => true,
        ]);
    }

    private function seedUsers(Laboratory $lab, array $users): void
    {
        foreach ($users as $u) {
            LabUser::updateOrCreate(['laboratory_id' => $lab->id, 'email' => $u['email']], [
                'name' => $u['name'],
                'role' => $u['role'],
                'active' => true,
            ]);
        }
    }

    private function seedMethods(Laboratory $lab): void
    {
        $methods = [
            ['code' => 'DO-SNI-0698914-2004', 'name' => 'Dissolved Oxygen — Titration', 'standard' => 'SNI 06-6989.14-2004', 'version' => '2004', 'notes' => 'Metode titrasi/yodometri modifikasi azida.'],
            ['code' => 'BOD-SNI-0698972-2009', 'name' => 'Biochemical Oxygen Demand 5 days', 'standard' => 'SNI 6989.72:2009', 'version' => '2009', 'notes' => 'Metode BOD5.'],
        ];
        foreach ($methods as $m) {
            \Illuminate\Support\Facades\DB::table('method_versions')->updateOrInsert(
                ['laboratory_id' => $lab->id, 'code' => $m['code']],
                ['name' => $m['name'], 'standard' => $m['standard'], 'version' => $m['version'], 'notes' => $m['notes'], 'active' => true, 'updated_at' => now(), 'created_at' => now(), 'id' => (string) \Illuminate\Support\Str::uuid()]
            );
        }
    }

    private function seedClients(Laboratory $lab, array $clients): array
    {
        $out = [];
        foreach ($clients as $i => $c) {
            $client = Client::updateOrCreate(['laboratory_id' => $lab->id, 'code' => $c['code']], [
                'name' => $c['name'],
                'address' => $lab->address,
                'phone' => $lab->phone,
                'email' => $c['email'],
                'active' => true,
            ]);
            $userEmail = 'portal-'.strtolower($c['code']).'@local.test';
            $password = 'Portal@12345';
            ClientUser::updateOrCreate(['laboratory_id' => $lab->id, 'email' => $userEmail], [
                'client_id' => $client->id,
                'name' => 'Portal '.$c['name'],
                'password_hash' => Hash::make($password),
                'role' => 'client_admin',
                'active' => true,
            ]);
            $out[$c['code']] = $client->id;
        }
        return $out;
    }

    private function seedInstruments(Laboratory $lab): void
    {
        $instruments = [
            ['code' => 'DO-TITR', 'name' => 'Digital Burette DO', 'serial_number' => 'DB-2201-0'.substr($lab->id, 0, 4), 'calibration_due' => now()->addYear(), 'status' => 'active'],
            ['code' => 'DO-MTR', 'name' => 'DO Meter Portabel', 'serial_number' => 'DM-2103-'.substr($lab->id, 0, 4), 'calibration_due' => now()->addMonths(6), 'status' => 'active'],
            ['code' => 'BOD-INC', 'name' => 'Inkubator BOD 20 C', 'serial_number' => 'BI-2007-'.substr($lab->id, 0, 4), 'calibration_due' => now()->addMonths(9), 'status' => 'active'],
            ['code' => 'PH-MTR', 'name' => 'pH Meter', 'serial_number' => 'PM-2205-'.substr($lab->id, 0, 4), 'calibration_due' => now()->addMonths(3), 'status' => 'maintenance'],
        ];
        foreach ($instruments as $i) {
            Instrument::updateOrCreate(['laboratory_id' => $lab->id, 'code' => $i['code']], $i);
        }
    }

    private function seedReagents(Laboratory $lab): void
    {
        $reagents = [
            ['code' => 'RGT-NAS', 'name' => 'Natrium tiosulfat 0,025 N', 'lot_number' => 'NS-26'.'(09)', 'concentration' => 0.025, 'unit' => 'N', 'expiry_date' => now()->addMonths(6)],
            ['code' => 'RGT-MNS', 'name' => 'Mangan sulfat (MnSO4)', 'lot_number' => 'MN-26'.'(05)', 'concentration' => null, 'unit' => null, 'expiry_date' => now()->addMonths(12)],
            ['code' => 'RGT-AKI', 'name' => 'Alkali iodida-azida', 'lot_number' => 'AK-26'.'(07)', 'concentration' => null, 'unit' => null, 'expiry_date' => now()->addMonths(4)],
            ['code' => 'RGT-AML', 'name' => 'Amilum 1%', 'lot_number' => 'AM-26'.'(01)', 'concentration' => 1.0, 'unit' => '%', 'expiry_date' => now()->addWeeks(4)],
            ['code' => 'RGT-GGA', 'name' => 'Larutan GGA (skim milk + glukosa)', 'lot_number' => 'GG-26'.'(10)', 'concentration' => 198.0, 'unit' => 'mg/L', 'expiry_date' => now()->addMonths(2)],
            ['code' => 'RGT-SD', 'name' => 'Air bebas oksigen (seed dilution)', 'lot_number' => 'SD-26'.'(05)', 'concentration' => null, 'unit' => null, 'expiry_date' => now()->addDays(7)],
        ];
        foreach ($reagents as $r) {
            Reagent::updateOrCreate(['laboratory_id' => $lab->id, 'code' => $r['code']], $r);
        }
    }

    private function seedSamples(Laboratory $lab, array $samples): void
    {
        foreach ($samples as $i => $s) {
            Sample::updateOrCreate(['laboratory_id' => $lab->id, 'sample_code' => $s['code']], [
                'sample_name' => $s['name'],
                'matrix' => $s['matrix'],
                'received_at' => now()->subDays(count($samples) - $i),
                'customer' => $s['customer'] ?: null,
                'notes' => 'Contoh data dummy.',
            ]);
        }
    }

    private function seedRuns(Laboratory $lab, array $runs, array $clients): void
    {
        $dilutionTemplates = [
            1 => ['sample_volume_ml' => 20, 'final_volume_ml' => 300, 'do_initial' => 7.6, 'do_final' => 5.2, 'fail' => false],
            2 => ['sample_volume_ml' => 50, 'final_volume_ml' => 300, 'do_initial' => 7.5, 'do_final' => 3.9, 'fail' => false],
            3 => ['sample_volume_ml' => 30, 'final_volume_ml' => 300, 'do_initial' => 7.4, 'do_final' => 6.9, 'fail' => true],
        ];
        $analysts = LabUser::where('laboratory_id', $lab->id)->where('role', 'analyst')->pluck('name')->toArray();
        $defaultAnalyst = $analysts[0] ?? 'Analyst';
        $batchBase = 'BT-'.now()->format('ym').'-';
        foreach ($runs as $idx => $spec) {
            $sampleCode = $spec['sample'];
            $param = $spec['param'];
            $status = $spec['status'];
            $exists = AnalysisRun::where('laboratory_id', $lab->id)->where('sample_code', $sampleCode)->where('parameter', $param)->exists();
            if ($exists) {
                continue;
            }
            $analyst = $spec['analyst'] ?? $defaultAnalyst;
            $sample = Sample::where('laboratory_id', $lab->id)->where('sample_code', $sampleCode)->first();
            $analysedAt = now()->subDays($spec['days'])->setTime(9 + $idx % 8, 15 + $idx * 7 % 40);

            if ($param === 'DO') {
                $dupTio = $spec['dup'] ?? $spec['tio'];
                $inputs = [
                    'client_id' => $spec['client'] ? ($clients[$spec['client']] ?? null) : null,
                    'sample_code' => $sampleCode,
                    'simplo_titrasi_1' => round($spec['tio'] - 0.01, 3),
                    'simplo_titrasi_2' => round($spec['tio'] + 0.01, 3),
                    'duplo_titrasi_1' => round($dupTio - 0.01, 3),
                    'duplo_titrasi_2' => round($dupTio + 0.01, 3),
                    'normality' => 0.025,
                    'winkler_volume_ml' => 300,
                    'reagent_mnso4_ml' => 1.0,
                    'reagent_alkali_ml' => 1.0,
                    'aliquot_ml' => 50,
                ];
                $calc = $this->calc->dissolvedOxygenDuo($inputs['simplo_titrasi_1'], $inputs['simplo_titrasi_2'], $inputs['duplo_titrasi_1'], $inputs['duplo_titrasi_2'], $inputs['normality'], $inputs['winkler_volume_ml'], $inputs['reagent_mnso4_ml'], $inputs['reagent_alkali_ml'], $inputs['aliquot_ml']);
                $sampleMatrix = $sample?->matrix;
                $method = 'SNI 06-6989.14-2004';
            } else {
                $depletion = $spec['dep'];
                $a1 = 7.8;
                $a2 = round($a1 - $depletion, 1);
                $b1 = 8.0;
                $b2 = round($b1 - $spec['b'], 3);
                $vb = 3;
                $vc = 3;
                $p = $spec['p'];
                $start = $analysedAt->copy()->subDay();
                $end = $start->copy()->addHours(120);
                $inputs = [
                    'client_id' => $spec['client'] ? ($clients[$spec['client']] ?? null) : null,
                    'sample_code' => $sampleCode,
                    'a1' => $a1, 'a2' => $a2, 'b1' => $b1, 'b2' => $b2, 'vb' => $vb, 'vc' => $vc, 'p' => $p,
                    'sample_ph' => $spec['ph'], 'sample_temperature' => 21.5,
                    'sampling_at' => $start->toDateTimeString(), 'bod_incubation_start' => $start->toDateTimeString(), 'bod_incubation_end' => $end->toDateTimeString(),
                    'storage_temperature' => 4.0, 'storage_hours' => 2.0, 'interference_treatment' => '',
                    'gga_bod' => $spec['gga'],
                ];
                $calc = $this->calc->bod5($a1, $a2, $b1, $b2, $vb, $vc, $p);
                $calc['qc'] = $this->bodQc->evaluate($a1, $a2, $b1, $b2, $p);
                if ($spec['gga'] !== null) {
                    $calc['gga'] = $this->bodQc->evaluateGga($spec['gga']);
                }
                $calc['storage_status'] = $this->bodQc->storageStatus(2.0);
                $sampleMatrix = $sample?->matrix;
                $method = 'SNI 6989.72:2009';
            }

            $reportNumber = null;
            $approvedBy = null;
            $approvedAt = null;
            $submittedAt = null;
            if (in_array($status, ['APPROVED', 'READY_FOR_REVIEW', 'REVISION_REQUIRED'], true)) {
                $submittedAt = $analysedAt->copy()->addHours(3);
            }
            if ($status === 'APPROVED') {
                $approvedBy = $lab->users()->where('role', 'supervisor')->value('name') ?? 'Supervisor';
                $approvedAt = $submittedAt->copy()->addHours(26);
                $reportNumber = 'LAP-'.now()->format('Ymd').'-'.strtoupper(substr(md5($lab->id.$sampleCode.$param), 0, 8));
            }

            $run = AnalysisRun::create([
                'laboratory_id' => $lab->id,
                'client_id' => $inputs['client_id'],
                'sample_code' => $sampleCode,
                'parameter' => $param,
                'method_version' => $method,
                'analyst' => $analyst,
                'analysed_at' => $analysedAt,
                'inputs' => $inputs,
                'calculation' => $calc,
                'notes' => 'Data dummy untuk demo.',
                'status' => $status,
                'submitted_at' => $submittedAt,
                'approved_by' => $approvedBy,
                'approved_at' => $approvedAt,
                'report_number' => $reportNumber,
                'sample_matrix' => $sampleMatrix,
                'batch_code' => $batchBase.str_pad((string) ($idx + 1), 3, '0', STR_PAD_LEFT),
                'instrument_code' => 'DO-TITR',
                'reagent_lot' => 'NS-26'.'(09)',
            ]);

            if ($param === 'BOD5') {
                $incubTemp = 20.0;
                $incubHours = 120.0;
                foreach ($spec['dils'] as $dIdx => $dCode) {
                    $tpl = $dilutionTemplates[$dCode];
                    $dilutionAt = $analysedAt->copy()->subDay();
                    $final = $tpl['final_volume_ml'];
                    $sampleVol = $tpl['sample_volume_ml'];
                    $doInit = $tpl['do_initial'];
                    $doFinal = $tpl['do_final'];
                    if ($tpl['fail']) {
                        $doFinal = $tpl['do_initial'] - 0.7;
                    }
                    $p = $final > 0 ? $sampleVol / $final : 0;
                    $eval = $this->bodQc->evaluateDilution($doInit, $doFinal, $incubTemp, $incubHours);
                    BodDilution::create([
                        'laboratory_id' => $lab->id,
                        'analysis_run_id' => $run->id,
                        'sample_volume_ml' => $sampleVol,
                        'final_volume_ml' => $final,
                        'p' => round($p, 10),
                        'do_initial' => $doInit,
                        'do_final' => $doFinal,
                        'do_depletion' => round($doInit - $doFinal, 6),
                        'dilution_code' => 'D'.($dIdx + 1),
                        'dilution_at' => $dilutionAt,
                        'do_initial_at' => $dilutionAt,
                        'do_final_at' => $analysedAt->copy()->addHours(120),
                        'diluent_volume_ml' => $final - $sampleVol,
                        'incubation_temperature' => $incubTemp,
                        'incubation_hours' => $incubHours,
                        'selection_status' => $eval['status'],
                    ]);
                }
                if ($spec['gga'] !== null) {
                    $g = $this->bodQc->evaluateGga($spec['gga']);
                    BodControl::create([
                        'laboratory_id' => $lab->id,
                        'analysis_run_id' => $run->id,
                        'control_type' => 'GGA',
                        'control_code' => 'GGA',
                        'bod_result' => $spec['gga'],
                        'expected_min' => $g['lower'],
                        'expected_max' => $g['upper'],
                        'acceptance_percent' => 30.5 / 198 * 100,
                        'status' => $g['status'],
                    ]);
                }
            }

            $this->audit($run, 'CREATE', null, 'DRAFT', 'Impordata dummy', $analyst);
            if (in_array($status, ['APPROVED', 'READY_FOR_REVIEW', 'REVISION_REQUIRED'], true)) {
                $this->audit($run, 'SUBMIT', 'DRAFT', 'READY_FOR_REVIEW', null, $analyst);
                if ($status === 'APPROVED') {
                    $this->audit($run, 'REVIEW', 'READY_FOR_REVIEW', 'APPROVED', null, $approvedBy);
                } elseif ($status === 'REVISION_REQUIRED') {
                    $this->audit($run, 'REVIEW', 'READY_FOR_REVIEW', 'REVISION_REQUIRED', 'Perlu perbaikan QC.', $lab->users()->where('role', 'supervisor')->value('name') ?? 'Supervisor');
                }
            }
        }
    }

    private function migrateLegacyDoRuns(): void
    {
        $legacy = AnalysisRun::withoutGlobalScopes()->where('parameter', 'DO')->get();
        foreach ($legacy as $run) {
            $in = $run->inputs ?? [];
            if (isset($in['simplo_titrasi_1']) || !isset($in['thiosulfate_ml'])) {
                continue;
            }
            $simplo = $in['thiosulfate_ml'];
            $duplo = $in['thiosulfate_duplo_ml'] ?? $simplo;
            $in['simplo_titrasi_1'] = $simplo;
            $in['simplo_titrasi_2'] = $simplo;
            $in['duplo_titrasi_1'] = $duplo;
            $in['duplo_titrasi_2'] = $duplo;
            unset($in['thiosulfate_ml'], $in['thiosulfate_duplo_ml']);
            $calc = $this->calc->dissolvedOxygenDuo(
                $in['simplo_titrasi_1'], $in['simplo_titrasi_2'],
                $in['duplo_titrasi_1'], $in['duplo_titrasi_2'],
                $in['normality'], $in['winkler_volume_ml'],
                $in['reagent_mnso4_ml'], $in['reagent_alkali_ml'], $in['aliquot_ml']
            );
            $run->timestamps = false;
            $run->update(['inputs' => $in, 'calculation' => $calc]);
        }
    }

    private function seedUncertainty(Laboratory $lab): void
    {
        $kits = [
            ['code' => 'UNC-DO', 'parameter' => 'DO', 'precision_sd' => 0.12, 'precision_n' => 10, 'bias_sd' => 0.35, 'bias_n' => 8],
            ['code' => 'UNC-BOD5', 'parameter' => 'BOD5', 'precision_sd' => 1.4, 'precision_n' => 12, 'bias_sd' => 2.1, 'bias_n' => 6],
        ];
        foreach ($kits as $k) {
            $model = UncertaintyModel::updateOrCreate(['laboratory_id' => $lab->id, 'code' => $k['code']], [
                'parameter' => $k['parameter'],
                'model_name' => 'Top-down uncertainty '.$k['parameter'],
                'coverage_factor' => 2.0,
                'unit' => 'mg/L',
                'active' => true,
                'notes' => 'Estimasi dari data QC historis (dummy).',
            ]);
            $result = $this->calc->topDownUncertainty($k['precision_sd'], $k['precision_n'], $k['bias_sd'], $k['bias_n']);
            $total = ($result['u_precision'] ** 2) + ($result['u_bias'] ** 2);
            $parts = [
                ['code' => 'PREC', 'name' => 'Precision (repeatability)', 'source_type' => 'PRECISION', 'sd' => $result['u_precision'], 'n' => $k['precision_n']],
                ['code' => 'BIAS', 'name' => 'Bias (CRM/control)', 'source_type' => 'BIAS', 'sd' => $result['u_bias'], 'n' => $k['bias_n']],
            ];
            foreach ($parts as $p) {
                UncertaintyComponent::updateOrCreate(['uncertainty_model_id' => $model->id, 'code' => $p['code']], [
                    'name' => $p['name'],
                    'source_type' => $p['source_type'],
                    'distribution' => 'normal',
                    'standard_uncertainty' => $p['sd'],
                    'sensitivity_coefficient' => 1,
                    'contribution' => $p['sd'],
                    'degrees_of_freedom' => $p['n'] - 1,
                    'relative_contribution_percent' => $total > 0 ? round(($p['sd'] ** 2) / $total * 100, 4) : 0,
                ]);
            }
        }
    }

    private function audit(AnalysisRun $run, string $action, ?string $from, ?string $to, ?string $notes, string $actor): void
    {
        if (AnalysisAudit::where('analysis_run_id', $run->id)->where('action', $action)->where('actor', $actor)->exists()) {
            return;
        }
        AnalysisAudit::create([
            'laboratory_id' => $run->laboratory_id,
            'analysis_run_id' => $run->id,
            'actor' => $actor,
            'action' => $action,
            'from_status' => $from,
            'to_status' => $to,
            'notes' => $notes,
        ]);
    }
}