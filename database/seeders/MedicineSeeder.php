<?php

namespace Database\Seeders;

use App\Models\Medicine;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
                'batch_number' => 'BATCH-001',
                'quantity' => 25,
                'unit_price' => 50,
                'expiry_date' => '2024-01-15',
            ],
            [
                'name' => 'Amoxicillin 500mg',
                'category' => 'Antibiotic',
                'batch_number' => 'BATCH-045',
                'quantity' => 150,
                'unit_price' => 120,
                'expiry_date' => '2026-07-20',
            ],
            [
                'name' => 'Ibuprofen 400mg',
                'category' => 'Analgesic',
                'batch_number' => 'BATCH-089',
                'quantity' => 320,
                'unit_price' => 75,
                'expiry_date' => '2028-03-10',
            ],
            [
                'name' => 'Metformin 500mg',
                'category' => 'Diabetes',
                'batch_number' => 'BATCH-120',
                'quantity' => 500,
                'unit_price' => 85,
                'expiry_date' => '2027-11-30',
            ],
            [
                'name' => 'Chloroquine 250mg',
                'category' => 'Antimalarial',
                'batch_number' => 'BATCH-067',
                'quantity' => 200,
                'unit_price' => 60,
                'expiry_date' => '2028-06-15',
            ],
            [
                'name' => 'Aspirin 75mg',
                'category' => 'Antiplatelet',
                'batch_number' => 'BATCH-078',
                'quantity' => 75,
                'unit_price' => 40,
                'expiry_date' => '2025-06-30',
            ],
            [
                'name' => 'Ciprofloxacin 500mg',
                'category' => 'Antibiotic',
                'batch_number' => 'BATCH-102',
                'quantity' => 180,
                'unit_price' => 150,
                'expiry_date' => '2027-02-14',
            ],
            [
                'name' => 'Omeprazole 20mg',
                'category' => 'Gastrointestinal',
                'batch_number' => 'BATCH-091',
                'quantity' => 250,
                'unit_price' => 95,
                'expiry_date' => '2028-08-22',
            ],
            [
                'name' => 'Insulin Glargine',
                'category' => 'Diabetes',
                'batch_number' => 'BATCH-156',
                'quantity' => 40,
                'unit_price' => 280,
                'expiry_date' => '2026-10-11',
            ],
            [
                'name' => 'Atorvastatin 20mg',
                'category' => 'Cardiovascular',
                'batch_number' => 'BATCH-145',
                'quantity' => 400,
                'unit_price' => 120,
                'expiry_date' => '2029-01-05',
            ],
        ];

        foreach ($medicines as $medicine) {
            Medicine::create($medicine);
        }
    }
}
