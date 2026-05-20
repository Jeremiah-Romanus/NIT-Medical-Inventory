<?php

namespace Database\Seeders;

use App\Models\Medicine;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedicineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $storedDate = now()->toDateString();

        $medicines = [
            ['medical_id' => 'MED-001', 'name' => 'Paracetamol', 'category' => '', 'formulation_strength' => 'Tablet 500mg', 'batch_number' => 'BATCH-A101', 'quantity' => 5000, 'stored_date' => $storedDate, 'expiry_date' => '2028-04-12', 'unit_price' => 130.00],
            ['medical_id' => 'MED-002', 'name' => 'Amoxicillin', 'category' => '', 'formulation_strength' => 'Capsule 500mg', 'batch_number' => 'BATCH-B204', 'quantity' => 2400, 'stored_date' => $storedDate, 'expiry_date' => '2027-09-18', 'unit_price' => 390.00],
            ['medical_id' => 'MED-003', 'name' => 'Ibuprofen', 'category' => '', 'formulation_strength' => 'Tablet 400mg', 'batch_number' => 'BATCH-C112', 'quantity' => 3000, 'stored_date' => $storedDate, 'expiry_date' => '2028-01-20', 'unit_price' => 208.00],
            ['medical_id' => 'MED-004', 'name' => 'Metformin', 'category' => '', 'formulation_strength' => 'Tablet 850mg', 'batch_number' => 'BATCH-D441', 'quantity' => 4500, 'stored_date' => $storedDate, 'expiry_date' => '2027-11-05', 'unit_price' => 312.00],
            ['medical_id' => 'MED-005', 'name' => 'Atorvastatin', 'category' => '', 'formulation_strength' => 'Tablet 20mg', 'batch_number' => 'BATCH-E098', 'quantity' => 1500, 'stored_date' => $storedDate, 'expiry_date' => '2028-03-30', 'unit_price' => 1170.00],
            ['medical_id' => 'MED-006', 'name' => 'Amlodipine', 'category' => '', 'formulation_strength' => 'Tablet 5mg', 'batch_number' => 'BATCH-F712', 'quantity' => 2000, 'stored_date' => $storedDate, 'expiry_date' => '2027-08-14', 'unit_price' => 260.00],
            ['medical_id' => 'MED-007', 'name' => 'Omeprazole', 'category' => '', 'formulation_strength' => 'Capsule 20mg', 'batch_number' => 'BATCH-G303', 'quantity' => 3500, 'stored_date' => $storedDate, 'expiry_date' => '2027-12-25', 'unit_price' => 468.00],
            ['medical_id' => 'MED-008', 'name' => 'Azithromycin', 'category' => '', 'formulation_strength' => 'Tablet 500mg', 'batch_number' => 'BATCH-H511', 'quantity' => 1200, 'stored_date' => $storedDate, 'expiry_date' => '2027-06-11', 'unit_price' => 1560.00],
            ['medical_id' => 'MED-009', 'name' => 'Losartan', 'category' => '', 'formulation_strength' => 'Tablet 50mg', 'batch_number' => 'BATCH-H890', 'quantity' => 1800, 'stored_date' => $storedDate, 'expiry_date' => '2028-02-15', 'unit_price' => 650.00],
            ['medical_id' => 'MED-010', 'name' => 'Salbutamol', 'category' => '', 'formulation_strength' => 'Inhaler 100mcg/dose', 'batch_number' => 'BATCH-J121', 'quantity' => 400, 'stored_date' => $storedDate, 'expiry_date' => '2027-10-10', 'unit_price' => 11700.00],
            ['medical_id' => 'MED-011', 'name' => 'Ciprofloxacin', 'category' => '', 'formulation_strength' => 'Tablet 500mg', 'batch_number' => 'BATCH-K442', 'quantity' => 1500, 'stored_date' => $storedDate, 'expiry_date' => '2027-07-19', 'unit_price' => 910.00],
            ['medical_id' => 'MED-012', 'name' => 'Cetirizine', 'category' => '', 'formulation_strength' => 'Tablet 10mg', 'batch_number' => 'BATCH-L901', 'quantity' => 4000, 'stored_date' => $storedDate, 'expiry_date' => '2028-05-01', 'unit_price' => 182.00],
            ['medical_id' => 'MED-013', 'name' => 'Metronidazole', 'category' => '', 'formulation_strength' => 'Tablet 400mg', 'batch_number' => 'BATCH-M156', 'quantity' => 2800, 'stored_date' => $storedDate, 'expiry_date' => '2027-11-22', 'unit_price' => 286.00],
            ['medical_id' => 'MED-014', 'name' => 'Diclofenac Sodium', 'category' => '', 'formulation_strength' => 'Gel 1% (50g)', 'batch_number' => 'BATCH-N873', 'quantity' => 600, 'stored_date' => $storedDate, 'expiry_date' => '2027-09-05', 'unit_price' => 4680.00],
            ['medical_id' => 'MED-015', 'name' => 'Prednisolone', 'category' => '', 'formulation_strength' => 'Tablet 5mg', 'batch_number' => 'BATCH-O221', 'quantity' => 2200, 'stored_date' => $storedDate, 'expiry_date' => '2027-05-14', 'unit_price' => 234.00],
            ['medical_id' => 'MED-016', 'name' => 'Ceftriaxone', 'category' => '', 'formulation_strength' => 'Injection 1g', 'batch_number' => 'BATCH-P607', 'quantity' => 500, 'stored_date' => $storedDate, 'expiry_date' => '2026-12-18', 'unit_price' => 6500.00],
            ['medical_id' => 'MED-017', 'name' => 'Ranitidine', 'category' => '', 'formulation_strength' => 'Tablet 150mg', 'batch_number' => 'BATCH-Q334', 'quantity' => 1900, 'stored_date' => $storedDate, 'expiry_date' => '2027-03-11', 'unit_price' => 364.00],
            ['medical_id' => 'MED-018', 'name' => 'Clopidogrel', 'category' => '', 'formulation_strength' => 'Tablet 75mg', 'batch_number' => 'BATCH-R109', 'quantity' => 1300, 'stored_date' => $storedDate, 'expiry_date' => '2028-01-08', 'unit_price' => 1300.00],
            ['medical_id' => 'MED-019', 'name' => 'Pantoprazole', 'category' => '', 'formulation_strength' => 'Tablet 40mg', 'batch_number' => 'BATCH-S551', 'quantity' => 2500, 'stored_date' => $storedDate, 'expiry_date' => '2027-10-31', 'unit_price' => 572.00],
            ['medical_id' => 'MED-020', 'name' => 'Tramadol', 'category' => '', 'formulation_strength' => 'Capsule 50mg', 'batch_number' => 'BATCH-T982', 'quantity' => 1100, 'stored_date' => $storedDate, 'expiry_date' => '2027-08-27', 'unit_price' => 780.00],
            ['medical_id' => 'MED-021', 'name' => 'Furosemide', 'category' => '', 'formulation_strength' => 'Tablet 40mg', 'batch_number' => 'BATCH-U404', 'quantity' => 3200, 'stored_date' => $storedDate, 'expiry_date' => '2028-04-19', 'unit_price' => 156.00],
            ['medical_id' => 'MED-022', 'name' => 'Meloxicam', 'category' => '', 'formulation_strength' => 'Tablet 15mg', 'batch_number' => 'BATCH-V112', 'quantity' => 1400, 'stored_date' => $storedDate, 'expiry_date' => '2027-07-02', 'unit_price' => 728.00],
            ['medical_id' => 'MED-023', 'name' => 'Glibenclamide', 'category' => '', 'formulation_strength' => 'Tablet 5mg', 'batch_number' => 'BATCH-W731', 'quantity' => 2100, 'stored_date' => $storedDate, 'expiry_date' => '2027-11-14', 'unit_price' => 208.00],
            ['medical_id' => 'MED-024', 'name' => 'Doxycycline', 'category' => '', 'formulation_strength' => 'Capsule 100mg', 'batch_number' => 'BATCH-X802', 'quantity' => 1700, 'stored_date' => $storedDate, 'expiry_date' => '2027-06-23', 'unit_price' => 520.00],
            ['medical_id' => 'MED-025', 'name' => 'Enalapril', 'category' => '', 'formulation_strength' => 'Tablet 10mg', 'batch_number' => 'BATCH-Y319', 'quantity' => 1600, 'stored_date' => $storedDate, 'expiry_date' => '2028-03-12', 'unit_price' => 390.00],
            ['medical_id' => 'MED-026', 'name' => 'Alprazolam', 'category' => '', 'formulation_strength' => 'Tablet 0.5mg', 'batch_number' => 'BATCH-Z005', 'quantity' => 900, 'stored_date' => $storedDate, 'expiry_date' => '2027-09-09', 'unit_price' => 1040.00],
            ['medical_id' => 'MED-027', 'name' => 'Loratadine', 'category' => '', 'formulation_strength' => 'Tablet 10mg', 'batch_number' => 'BATCH-AA12', 'quantity' => 3800, 'stored_date' => $storedDate, 'expiry_date' => '2028-05-15', 'unit_price' => 182.00],
            ['medical_id' => 'MED-028', 'name' => 'Fluconazole', 'category' => '', 'formulation_strength' => 'Capsule 150mg', 'batch_number' => 'BATCH-BB45', 'quantity' => 800, 'stored_date' => $storedDate, 'expiry_date' => '2027-12-04', 'unit_price' => 1950.00],
            ['medical_id' => 'MED-029', 'name' => 'Hydrochlorothiazide', 'category' => '', 'formulation_strength' => 'Tablet 25mg', 'batch_number' => 'BATCH-CC78', 'quantity' => 2400, 'stored_date' => $storedDate, 'expiry_date' => '2028-02-28', 'unit_price' => 234.00],
            ['medical_id' => 'MED-030', 'name' => 'Bisoprolol', 'category' => '', 'formulation_strength' => 'Tablet 5mg', 'batch_number' => 'BATCH-DD91', 'quantity' => 1500, 'stored_date' => $storedDate, 'expiry_date' => '2027-10-14', 'unit_price' => 858.00],
            ['medical_id' => 'MED-031', 'name' => 'Spironolactone', 'category' => '', 'formulation_strength' => 'Tablet 25mg', 'batch_number' => 'BATCH-EE23', 'quantity' => 1100, 'stored_date' => $storedDate, 'expiry_date' => '2027-08-11', 'unit_price' => 624.00],
            ['medical_id' => 'MED-032', 'name' => 'Gabapentin', 'category' => '', 'formulation_strength' => 'Capsule 300mg', 'batch_number' => 'BATCH-FF56', 'quantity' => 1300, 'stored_date' => $storedDate, 'expiry_date' => '2027-11-19', 'unit_price' => 988.00],
            ['medical_id' => 'MED-033', 'name' => 'Cefuroxime', 'category' => '', 'formulation_strength' => 'Tablet 250mg', 'batch_number' => 'BATCH-GG89', 'quantity' => 1000, 'stored_date' => $storedDate, 'expiry_date' => '2027-07-25', 'unit_price' => 1690.00],
            ['medical_id' => 'MED-034', 'name' => 'Levofloxacin', 'category' => '', 'formulation_strength' => 'Tablet 500mg', 'batch_number' => 'BATCH-HH12', 'quantity' => 1200, 'stored_date' => $storedDate, 'expiry_date' => '2027-05-30', 'unit_price' => 1430.00],
            ['medical_id' => 'MED-035', 'name' => 'Simvastatin', 'category' => '', 'formulation_strength' => 'Tablet 20mg', 'batch_number' => 'BATCH-II43', 'quantity' => 2000, 'stored_date' => $storedDate, 'expiry_date' => '2028-01-14', 'unit_price' => 468.00],
            ['medical_id' => 'MED-036', 'name' => 'Erythromycin', 'category' => '', 'formulation_strength' => 'Tablet 250mg', 'batch_number' => 'BATCH-JJ76', 'quantity' => 1400, 'stored_date' => $storedDate, 'expiry_date' => '2027-04-18', 'unit_price' => 676.00],
            ['medical_id' => 'MED-037', 'name' => 'Insulin Glargine', 'category' => '', 'formulation_strength' => 'Injection 100 U/mL', 'batch_number' => 'BATCH-KK09', 'quantity' => 250, 'stored_date' => $storedDate, 'expiry_date' => '2026-11-05', 'unit_price' => 48100.00],
            ['medical_id' => 'MED-038', 'name' => 'Azathioprine', 'category' => '', 'formulation_strength' => 'Tablet 50mg', 'batch_number' => 'BATCH-LL32', 'quantity' => 600, 'stored_date' => $storedDate, 'expiry_date' => '2027-10-22', 'unit_price' => 2860.00],
            ['medical_id' => 'MED-039', 'name' => 'Carbamazepine', 'category' => '', 'formulation_strength' => 'Tablet 200mg', 'batch_number' => 'BATCH-MM65', 'quantity' => 1800, 'stored_date' => $storedDate, 'expiry_date' => '2027-12-15', 'unit_price' => 416.00],
            ['medical_id' => 'MED-040', 'name' => 'Phenobarbital', 'category' => '', 'formulation_strength' => 'Tablet 30mg', 'batch_number' => 'BATCH-NN98', 'quantity' => 1500, 'stored_date' => $storedDate, 'expiry_date' => '2028-03-01', 'unit_price' => 338.00],
            ['medical_id' => 'MED-041', 'name' => 'Vitamin B-Complex', 'category' => '', 'formulation_strength' => 'Tablet', 'batch_number' => 'BATCH-OO21', 'quantity' => 6000, 'stored_date' => $storedDate, 'expiry_date' => '2028-06-10', 'unit_price' => 104.00],
            ['medical_id' => 'MED-042', 'name' => 'Folic Acid', 'category' => '', 'formulation_strength' => 'Tablet 5mg', 'batch_number' => 'BATCH-PP54', 'quantity' => 7000, 'stored_date' => $storedDate, 'expiry_date' => '2028-05-20', 'unit_price' => 78.00],
            ['medical_id' => 'MED-043', 'name' => 'Ferrous Sulfate', 'category' => '', 'formulation_strength' => 'Tablet 200mg', 'batch_number' => 'BATCH-QQ87', 'quantity' => 5500, 'stored_date' => $storedDate, 'expiry_date' => '2028-04-05', 'unit_price' => 104.00],
            ['medical_id' => 'MED-044', 'name' => 'Hydrocortisone', 'category' => '', 'formulation_strength' => 'Cream 1% (30g)', 'batch_number' => 'BATCH-RR10', 'quantity' => 700, 'stored_date' => $storedDate, 'expiry_date' => '2027-08-30', 'unit_price' => 3510.00],
            ['medical_id' => 'MED-045', 'name' => 'Clotrimazole', 'category' => '', 'formulation_strength' => 'Cream 1% (20g)', 'batch_number' => 'BATCH-SS43', 'quantity' => 900, 'stored_date' => $storedDate, 'expiry_date' => '2027-09-12', 'unit_price' => 2990.00],
            ['medical_id' => 'MED-046', 'name' => 'Oral Rehydration Salts', 'category' => '', 'formulation_strength' => 'Sachet 20.5g', 'batch_number' => 'BATCH-TT76', 'quantity' => 3000, 'stored_date' => $storedDate, 'expiry_date' => '2028-06-01', 'unit_price' => 520.00],
            ['medical_id' => 'MED-047', 'name' => 'Zinc Sulfate', 'category' => '', 'formulation_strength' => 'Tablet 20mg', 'batch_number' => 'BATCH-UU09', 'quantity' => 4000, 'stored_date' => $storedDate, 'expiry_date' => '2028-02-10', 'unit_price' => 156.00],
            ['medical_id' => 'MED-048', 'name' => 'Albendazole', 'category' => '', 'formulation_strength' => 'Tablet 400mg', 'batch_number' => 'BATCH-VV42', 'quantity' => 2500, 'stored_date' => $storedDate, 'expiry_date' => '2028-03-15', 'unit_price' => 780.00],
            ['medical_id' => 'MED-049', 'name' => 'Artemether/Lumefantrine', 'category' => '', 'formulation_strength' => 'Tablet 20/120mg', 'batch_number' => 'BATCH-WW75', 'quantity' => 3500, 'stored_date' => $storedDate, 'expiry_date' => '2027-10-05', 'unit_price' => 2210.00],
            ['medical_id' => 'MED-050', 'name' => 'Paracetamol', 'category' => '', 'formulation_strength' => 'Suspension 120mg/5ml', 'batch_number' => 'BATCH-XX08', 'quantity' => 1200, 'stored_date' => $storedDate, 'expiry_date' => '2027-07-14', 'unit_price' => 2470.00],
        ];

        DB::table('distributions')->delete();
        DB::table('requests')->delete();
        DB::table('medicines')->delete();

        foreach ($medicines as $medicine) {
            Medicine::create($medicine);
        }
    }
}

