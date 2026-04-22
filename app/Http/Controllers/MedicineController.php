<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicine;

class MedicineController extends Controller
{
    /**
     * Display all medicines
     */
    public function index()
    {
        $medicines = Medicine::all();
        return view('medicines.index', compact('medicines'));
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
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'batch_number' => 'required|string|max:255|unique:medicines',
            'quantity' => 'required|integer|min:0',
            'expiry_date' => 'required|date',
            'unit_price' => 'required|numeric|min:0',
        ]);

        Medicine::create($validated);

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
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'batch_number' => 'required|string|max:255|unique:medicines,batch_number,' . $medicine->id,
            'quantity' => 'required|integer|min:0',
            'expiry_date' => 'required|date',
            'unit_price' => 'required|numeric|min:0',
        ]);

        $medicine->update($validated);

        return redirect()->route('medicines.index')
                        ->with('success', 'Medicine updated successfully!');
    }

    /**
     * Remove the specified medicine from database
     */
    public function destroy(Medicine $medicine)
    {
        $medicine->delete();

        return redirect()->route('medicines.index')
                        ->with('success', 'Medicine deleted successfully!');
    }
}
