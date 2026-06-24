<?php

namespace Tests\Feature;

use App\Models\Medicine;
use App\Models\MedicineRequest;
use App\Models\User;
use App\Notifications\MedicineRequestApproved;
use App\Notifications\MedicineRequestRejected;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class MedicineRequestWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_procurement_approval_increases_pharmacy_stock_and_sends_notification(): void
    {
        Notification::fake();

        $procurement = User::factory()->create([
            'role' => 'procurement',
        ]);

        $pharmacist = User::factory()->create([
            'role' => 'pharmacist',
        ]);

        $medicine = Medicine::create([
            'medical_id' => 'MED-1001',
            'name' => 'Diclofenac Sodium',
            'category' => 'Analgesic',
            'formulation_strength' => '50mg tablets',
            'batch_number' => 'BATCH-001',
            'quantity' => 600,
            'pharmacy_quantity' => 10,
            'stored_date' => now()->toDateString(),
            'expiry_date' => now()->addYear()->toDateString(),
            'unit_price' => 1500,
        ]);

        $request = MedicineRequest::create([
            'user_id' => $pharmacist->id,
            'medicine_id' => $medicine->id,
            'requested_quantity' => 300,
            'status' => 'pending',
            'remarks' => 'Emergency stock refill',
        ]);

        $this->actingAs($procurement)
            ->post(route('requests.approve', $request->id))
            ->assertRedirect();

        $medicine->refresh();
        $request->refresh();

        $this->assertSame(300, $medicine->quantity);
        $this->assertSame(310, $medicine->pharmacy_quantity);
        $this->assertSame('approved', $request->status);
        $this->assertNotNull($request->approved_at);
        $this->assertSame($procurement->id, $request->approved_by);

        Notification::assertSentTo(
            $pharmacist,
            MedicineRequestApproved::class,
            fn (MedicineRequestApproved $notification) => $notification->medicineRequest->id === $request->id
        );
    }

    public function test_procurement_rejection_stores_reason_and_sends_notification(): void
    {
        Notification::fake();

        $procurement = User::factory()->create([
            'role' => 'procurement',
        ]);

        $pharmacist = User::factory()->create([
            'role' => 'pharmacist',
        ]);

        $medicine = Medicine::create([
            'medical_id' => 'MED-2001',
            'name' => 'Cefuroxime',
            'category' => 'Antibiotic',
            'formulation_strength' => '500mg tablets',
            'batch_number' => 'BATCH-002',
            'quantity' => 1000,
            'pharmacy_quantity' => 0,
            'stored_date' => now()->toDateString(),
            'expiry_date' => now()->addYear()->toDateString(),
            'unit_price' => 2500,
        ]);

        $request = MedicineRequest::create([
            'user_id' => $pharmacist->id,
            'medicine_id' => $medicine->id,
            'requested_quantity' => 100,
            'status' => 'pending',
            'remarks' => 'Emergency stock refill',
        ]);

        $this->actingAs($procurement)
            ->post(route('requests.reject', $request->id), [
                'rejection_reason' => 'Procurement stock is reserved for a higher priority allocation.',
            ])
            ->assertRedirect();

        $request->refresh();

        $this->assertSame('rejected', $request->status);
        $this->assertSame('Procurement stock is reserved for a higher priority allocation.', $request->rejection_reason);
        $this->assertNotNull($request->rejected_at);
        $this->assertSame($procurement->id, $request->rejected_by);

        Notification::assertSentTo(
            $pharmacist,
            MedicineRequestRejected::class,
            fn (MedicineRequestRejected $notification) => $notification->medicineRequest->id === $request->id
                && $notification->rejectionReason === 'Procurement stock is reserved for a higher priority allocation.'
        );
    }
}
