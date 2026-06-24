<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicine;
use App\Support\AuditTrail;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class MedicineController extends Controller
{
    /**
     * Display medicine listing with pagination, search and filters.
     */
    public function index(Request $request)
    {
        $query = Medicine::query();

        // Global search across name, medical_id and batch_number
        if ($request->filled('q')) {
            $term = $request->input('q');
            $query->where(function ($qb) use ($term) {
                $qb->where('name', 'like', '%' . $term . '%')
                   ->orWhere('medical_id', 'like', '%' . $term . '%')
                   ->orWhere('batch_number', 'like', '%' . $term . '%');
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        // Status filter (expired / expiring_soon / active)
        if ($request->filled('status')) {
            if ($request->input('status') === 'expired') {
                $query->where('expiry_date', '<', now());
            } elseif ($request->input('status') === 'expiring_soon') {
                $query->whereBetween('expiry_date', [now(), now()->addMonths(6)]);
            } elseif ($request->input('status') === 'active') {
                $query->where('expiry_date', '>=', now()->addMonths(6));
            }
        }

        // Counts for alerts (computed over entire dataset)
        $expiredCount = Medicine::where('expiry_date', '<', now())->count();
        $expiringCount = Medicine::whereBetween('expiry_date', [now(), now()->addMonths(6)])->count();

        // Categories for filter dropdown
        $categories = Medicine::select('category')->distinct()->pluck('category')->filter()->values();

        // Stock context is based on route name so role-specific pages show their own stock view
        $stockContext = 'all';
        if ($request->routeIs('pharmacist.stock')) {
            $stockContext = 'pharmacy';
            // Pharmacist should see items that exist in the pharmacy inventory
            $query->where('pharmacy_quantity', '>', 0);
        } elseif ($request->routeIs('procurement.stock')) {
            $stockContext = 'procurement';
        }

        // Paginate results and preserve query string
        $medicines = $query->orderBy('name')->paginate(25)->withQueryString();

        return view('medicines.index', compact(
            'medicines',
            'expiredCount',
            'expiringCount',
            'categories',
            'stockContext'
        ));
    }

    /**
     * Show the form for creating a new medicine
     */
    public function create()
    {
        return view('medicines.create');
    }

    /**
     * Store a newly created medicine in database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'medical_id' => 'required|string|max:20|unique:medicines,medical_id',
            'name' => 'required|string|max:255',
            'formulation_strength' => 'required|string|max:255',
            'batch_number' => 'required|string|max:255|unique:medicines',
            'quantity' => 'required|integer|min:0',
            'pharmacy_quantity' => 'nullable|integer|min:0',
            'stored_date' => 'required|date_format:d/m/Y',
            'expiry_date' => 'required|date_format:d/m/Y',
            'unit_price' => 'required|numeric|min:0',
        ], [
            'stored_date.date_format' => 'Stored date must use DD/MM/YYYY format.',
            'expiry_date.date_format' => 'Expiry date must use DD/MM/YYYY format.',
        ]);

        $validated = $this->prepareMedicineDates($validated);
        $validated['category'] = '';
        $validated['pharmacy_quantity'] = $validated['pharmacy_quantity'] ?? 0;

        $medicine = Medicine::create($validated);

        AuditTrail::record(
            'medicine.created',
            $medicine,
            $medicine->name,
            null,
            $medicine->fresh()->toArray()
        );

        return redirect()->route('medicines.index')
                        ->with('success', 'Medicine added successfully!');
    }

    /**
     * Display the specified medicine
     */
    public function show(Medicine $medicine)
    {
        return view('medicines.show', compact('medicine'));
    }

    /**
     * Show the form for editing the specified medicine
     */
    public function edit(Medicine $medicine)
    {
        return view('medicines.edit', compact('medicine'));
    }

    /**
     * Update the specified medicine in database
     */
    public function update(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'medical_id' => 'required|string|max:20|unique:medicines,medical_id,' . $medicine->id,
            'name' => 'required|string|max:255',
            'formulation_strength' => 'required|string|max:255',
            'batch_number' => 'required|string|max:255|unique:medicines,batch_number,' . $medicine->id,
            'quantity' => 'required|integer|min:0',
            'pharmacy_quantity' => 'nullable|integer|min:0',
            'stored_date' => 'required|date_format:d/m/Y',
            'expiry_date' => 'required|date_format:d/m/Y',
            'unit_price' => 'required|numeric|min:0',
        ], [
            'stored_date.date_format' => 'Stored date must use DD/MM/YYYY format.',
            'expiry_date.date_format' => 'Expiry date must use DD/MM/YYYY format.',
        ]);

        $validated = $this->prepareMedicineDates($validated);
        $validated['category'] = '';
        $validated['pharmacy_quantity'] = $validated['pharmacy_quantity'] ?? 0;

        $oldValues = $medicine->only(array_keys($validated));

        $medicine->update($validated);

        AuditTrail::record(
            'medicine.updated',
            $medicine,
            $medicine->name,
            $oldValues,
            $medicine->fresh()->only(array_keys($validated))
        );

        return redirect()->route('medicines.index')
                        ->with('success', 'Medicine updated successfully!');
    }

    /**
     * Remove the specified medicine from database
     */
    public function destroy(Medicine $medicine)
    {
        $oldValues = $medicine->toArray();
        $subject = $medicine->name;

        $medicine->delete();

        AuditTrail::record(
            'medicine.deleted',
            $medicine,
            $subject,
            $oldValues
        );

        return redirect()->route('medicines.index')
                        ->with('success', 'Medicine deleted successfully!');
    }

    private function prepareMedicineDates(array $validated): array
    {
        $storedDate = Carbon::createFromFormat('d/m/Y', $validated['stored_date'])->startOfDay();
        $expiryDate = Carbon::createFromFormat('d/m/Y', $validated['expiry_date'])->startOfDay();

        if ($expiryDate->lt(now()->startOfDay())) {
            throw ValidationException::withMessages([
                'expiry_date' => 'Expiry date must be today or a future date.',
            ]);
        }

        if ($expiryDate->lt($storedDate)) {
            throw ValidationException::withMessages([
                'expiry_date' => 'Expiry date cannot be earlier than the stored date.',
            ]);
        }

        $validated['stored_date'] = $storedDate->format('Y-m-d');
        $validated['expiry_date'] = $expiryDate->format('Y-m-d');

        return $validated;
    }
}
