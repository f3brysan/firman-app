<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\BillingTransaction;

class MsPelangganController extends Controller
{
    public function index()
    {
        $customers = Customer::all();  
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pelanggan' => 'required|string|max:255|unique:customers,id_pelanggan',
            'nama' => 'required|string|max:255',
            'layanan' => 'required|string|max:255',
            'region' => 'required|string|max:255',
        ]);

        Customer::create($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer)
    {
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'id_pelanggan' => 'required|string|max:255|unique:customers,id_pelanggan,' . $customer->id,
            'nama' => 'required|string|max:255',
            'layanan' => 'required|string|max:255',
            'region' => 'required|string|max:255',
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }
}
