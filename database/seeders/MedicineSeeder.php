<?php

namespace Database\Seeders;

use App\Models\Medicine;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MedicineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $medicines = [
            [
                'name' => 'Paracetamol 500mg',
                'category' => 'Analgesic',
                'batch_number' => 'NIT-2025-10-001',
                'quantity' => 20,
                'unit_price' => 50,
                'expiry_date' => '2025-12-10',
                'created_at' => Carbon::create(2025, 10, 8, 9, 0, 0),
            ],
            [
                'name' => 'Amoxicillin 500mg',
                'category' => 'Antibiotic',
                'batch_number' => 'NIT-2025-10-002',
                'quantity' => 30,
                'unit_price' => 120,
                'expiry_date' => '2026-05-18',
                'created_at' => Carbon::create(2025, 10, 18, 9, 0, 0),
            ],
            [
                'name' => 'Ibuprofen 400mg',
                'category' => 'Analgesic',
                'batch_number' => 'NIT-2025-11-003',
                'quantity' => 40,
                'unit_price' => 75,
                'expiry_date' => '2026-11-20',
                'created_at' => Carbon::create(2025, 11, 12, 9, 0, 0),
            ],
            [
                'name' => 'Metformin 500mg',
                'category' => 'Diabetes',
                'batch_number' => 'NIT-2025-12-004',
                'quantity' => 50,
                'unit_price' => 85,
                'expiry_date' => '2026-03-15',
                'created_at' => Carbon::create(2025, 12, 6, 9, 0, 0),
            ],
            [
                'name' => 'Chloroquine 250mg',
                'category' => 'Antimalarial',
                'batch_number' => 'NIT-2026-01-005',
                'quantity' => 20,
                'unit_price' => 60,
                'expiry_date' => '2026-05-25',
                'created_at' => Carbon::create(2026, 1, 9, 9, 0, 0),
            ],
            [
                'name' => 'Aspirin 75mg',
                'category' => 'Antiplatelet',
                'batch_number' => 'NIT-2026-01-006',
                'quantity' => 30,
                'unit_price' => 40,
                'expiry_date' => '2026-10-30',
                'created_at' => Carbon::create(2026, 1, 24, 9, 0, 0),
            ],
            [
                'name' => 'Ciprofloxacin 500mg',
                'category' => 'Antibiotic',
                'batch_number' => 'NIT-2026-02-007',
                'quantity' => 40,
                'unit_price' => 150,
                'expiry_date' => '2026-02-28',
                'created_at' => Carbon::create(2026, 2, 14, 9, 0, 0),
            ],
            [
                'name' => 'Omeprazole 20mg',
                'category' => 'Gastrointestinal',
                'batch_number' => 'NIT-2026-03-008',
                'quantity' => 50,
                'unit_price' => 95,
                'expiry_date' => '2026-06-30',
                'created_at' => Carbon::create(2026, 3, 11, 9, 0, 0),
            ],
            [
                'name' => 'Insulin Glargine',
                'category' => 'Diabetes',
                'batch_number' => 'NIT-2026-04-009',
                'quantity' => 20,
                'unit_price' => 280,
                'expiry_date' => '2026-08-22',
                'created_at' => Carbon::create(2026, 4, 3, 9, 0, 0),
            ],
            [
                'name' => 'Atorvastatin 20mg',
                'category' => 'Cardiovascular',
                'batch_number' => 'NIT-2026-04-010',
                'quantity' => 30,
                'unit_price' => 120,
                'expiry_date' => '2027-01-05',
                'created_at' => Carbon::create(2026, 4, 17, 9, 0, 0),
            ],
        ];

        foreach ($medicines as $medicine) {
            $createdAt = $medicine['created_at'];
            unset($medicine['created_at']);

            Medicine::updateOrCreate(
                ['batch_number' => $medicine['batch_number']],
                $medicine
            );

            DB::table('medicines')
                ->where('batch_number', $medicine['batch_number'])
                ->update([
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
        }
    }
}
