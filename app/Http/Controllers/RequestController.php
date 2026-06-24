<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\MedicineRequest;
use App\Notifications\MedicineRequestApproved;
use App\Notifications\MedicineRequestRejected;
use App\Support\AuditTrail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RequestController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'medicine_id' => ['required', 'exists:medicines,id'],
            'requested_quantity' => ['required', 'integer', 'min:1'],
            'remarks' => ['nullable', 'string', 'max:1000'],
        ]);

        $medicine = Medicine::findOrFail($validated['medicine_id']);

        if ($validated['requested_quantity'] > $medicine->quantity) {
            throw ValidationException::withMessages([
                'requested_quantity' => 'Requested quantity exceeds the available stock.',
            ]);
        }

        $medicineRequest = MedicineRequest::create([
            'user_id' => Auth::id(),
            'medicine_id' => $medicine->id,
            'requested_quantity' => $validated['requested_quantity'],
            'remarks' => $validated['remarks'] ?? null,
            'status' => 'pending',
        ]);

        AuditTrail::record(
            'request.created',
            $medicineRequest,
            $medicine->name,
            null,
            [
                'medicine' => $medicine->name,
                'requested_quantity' => $medicineRequest->requested_quantity,
                'status' => $medicineRequest->status,
                'remarks' => $medicineRequest->remarks,
            ]
        );

        return redirect()
            ->route('pharmacist.request')
            ->with('success', 'Request submitted successfully.');
    }

    public function myRequests()
    {
        $medicines = Medicine::where('quantity', '>', 0)
            ->orderBy('name')
            ->get();

        $myRequests = MedicineRequest::with('medicine')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('pharmacist.request', compact('medicines', 'myRequests'));
    }

    public function pendingRequests()
    {
        $totalRequestsCount = MedicineRequest::count();
        $pendingRequestsCount = MedicineRequest::where('status', 'pending')->count();
        $approvedRequestsCount = MedicineRequest::where('status', 'approved')->count();
        $rejectedRequestsCount = MedicineRequest::where('status', 'rejected')->count();

        $requests = \Illuminate\Support\Facades\DB::table('requests')
            ->join('users', 'requests.user_id', '=', 'users.id')
            ->join('medicines', 'requests.medicine_id', '=', 'medicines.id')
            ->leftJoin('users as approvers', 'requests.approved_by', '=', 'approvers.id')
            ->leftJoin('users as rejectors', 'requests.rejected_by', '=', 'rejectors.id')
            ->select(
                'requests.id',
                'users.name as requester',
                'medicines.id as medicine_id',
                'medicines.batch_number',
                'medicines.name as medicine',
                'medicines.quantity as procurement_quantity',
                'requests.requested_quantity as quantity',
                'requests.status',
                'requests.remarks',
                'requests.approval_note',
                'requests.rejection_reason',
                'requests.approved_at',
                'requests.rejected_at',
                'approvers.name as approved_by_name',
                'rejectors.name as rejected_by_name',
                'requests.created_at'
            )
            ->where('requests.status', 'pending')
            ->orderByDesc('requests.created_at')
            ->get();

        return view('procurement.requests', compact(
            'requests',
            'totalRequestsCount',
            'pendingRequestsCount',
            'approvedRequestsCount',
            'rejectedRequestsCount'
        ));
    }

    public function approve(MedicineRequest $medicineRequest)
    {
        if ($medicineRequest->status !== 'pending') {
            return back()->with('error', 'Only pending requests can be approved.');
        }

        try {
            $approvedRequest = null;

            DB::transaction(function () use ($medicineRequest, &$approvedRequest) {
                $request = MedicineRequest::query()
                    ->whereKey($medicineRequest->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($request->status !== 'pending') {
                    throw ValidationException::withMessages([
                        'status' => 'This request is no longer pending.',
                    ]);
                }

                $medicine = Medicine::query()
                    ->whereKey($request->medicine_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($request->requested_quantity > $medicine->quantity) {
                    throw ValidationException::withMessages([
                        'requested_quantity' => 'Not enough stock available to approve this request.',
                    ]);
                }

                $oldRequestValues = $request->only(['status', 'remarks']);
                $oldMedicineQuantity = $medicine->quantity;
                $oldPharmacyQuantity = $medicine->pharmacy_quantity;

                $medicine->quantity -= $request->requested_quantity;
                $medicine->pharmacy_quantity += $request->requested_quantity;
                $medicine->save();

                $request->status = 'approved';
                $request->approval_note = 'Approved and transferred to pharmacy stock.';
                $request->approved_at = now();
                $request->approved_by = Auth::id();
                $request->save();

                AuditTrail::record(
                    'request.approved',
                    $request,
                    $medicine->name,
                    [
                        'request' => $oldRequestValues,
                        'medicine_quantity' => $oldMedicineQuantity,
                        'pharmacy_quantity' => $oldPharmacyQuantity,
                    ],
                    [
                        'request' => $request->only(['status', 'remarks', 'approval_note', 'approved_at', 'approved_by']),
                        'medicine_quantity' => $medicine->quantity,
                        'pharmacy_quantity' => $medicine->pharmacy_quantity,
                    ],
                    [
                        'requested_quantity' => $request->requested_quantity,
                        'medicine_id' => $medicine->id,
                    ]
                );

                $approvedRequest = $request->fresh()->loadMissing(['medicine', 'user', 'approver']);
            });

            $approvedRequest?->user?->notify(new MedicineRequestApproved($approvedRequest));

            return back()->with('success', 'Request approved and stock updated successfully.');
        } catch (ValidationException $e) {
            $message = collect($e->errors())->flatten()->first() ?: 'Validation error while approving request.';
            return back()->with('error', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to approve request: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, MedicineRequest $medicineRequest)
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ]);

        if ($medicineRequest->status !== 'pending') {
            return back()->with('error', 'Only pending requests can be rejected.');
        }

        try {
            $rejectedRequest = null;

            DB::transaction(function () use ($medicineRequest, $validated, &$rejectedRequest) {
                $request = MedicineRequest::query()
                    ->whereKey($medicineRequest->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($request->status !== 'pending') {
                    throw ValidationException::withMessages([
                        'status' => 'This request is no longer pending.',
                    ]);
                }

                $oldValues = $request->only(['status', 'remarks', 'rejection_reason']);

                $request->update([
                    'status' => 'rejected',
                    'rejection_reason' => $validated['rejection_reason'],
                    'rejected_at' => now(),
                    'rejected_by' => Auth::id(),
                ]);

                $rejectedRequest = $request->fresh()->loadMissing(['medicine', 'user', 'rejector']);

                AuditTrail::record(
                    'request.rejected',
                    $rejectedRequest,
                    $rejectedRequest->medicine?->name,
                    $oldValues,
                    $rejectedRequest->only(['status', 'rejection_reason', 'rejected_at', 'rejected_by']),
                    [
                        'rejection_reason' => $validated['rejection_reason'],
                        'rejected_by' => Auth::user()->name,
                    ]
                );
            });

            $rejectedRequest?->user?->notify(new MedicineRequestRejected(
                $rejectedRequest,
                $validated['rejection_reason']
            ));

            return back()->with('success', 'Request rejected and notification sent to pharmacist.');
        } catch (ValidationException $e) {
            $message = collect($e->errors())->flatten()->first() ?: 'Validation error while rejecting request.';
            return back()->with('error', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to reject request: ' . $e->getMessage());
        }
    }
}
