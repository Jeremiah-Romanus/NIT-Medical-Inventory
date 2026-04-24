<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\MedicineRequest;
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

        MedicineRequest::create([
            'user_id' => Auth::id(),
            'medicine_id' => $medicine->id,
            'requested_quantity' => $validated['requested_quantity'],
            'remarks' => $validated['remarks'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('pharmacist.request')
            ->with('success', 'Request submitted successfully.');
    }

    public function myRequests()
    {
        $medicines = Medicine::orderBy('name')->get();

        $myRequests = MedicineRequest::with('medicine')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('pharmacist.request', compact('medicines', 'myRequests'));
    }

    public function pendingRequests()
    {
        $requests = MedicineRequest::with(['medicine', 'user'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('procurement.requests', compact('requests'));
    }

    public function approve(MedicineRequest $medicineRequest)
    {
        if ($medicineRequest->status !== 'pending') {
            return back()->with('error', 'Only pending requests can be approved.');
        }

        DB::transaction(function () use ($medicineRequest) {
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

            $medicine->quantity -= $request->requested_quantity;
            $medicine->save();

            $request->status = 'approved';
            $request->save();
        });

        return back()->with('success', 'Request approved and stock updated.');
    }

    public function reject(Request $request, MedicineRequest $medicineRequest)
    {
        $validated = $request->validate([
            'remarks' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($medicineRequest->status !== 'pending') {
            return back()->with('error', 'Only pending requests can be rejected.');
        }

        $medicineRequest->update([
            'status' => 'rejected',
            'remarks' => $validated['remarks'] ?? $medicineRequest->remarks,
        ]);

        return back()->with('success', 'Request rejected.');
    }
}
