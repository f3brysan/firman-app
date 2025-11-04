<?php

namespace App\Http\Controllers;

use App\Models\BillingTransaction;
use App\Models\Customer;
use Illuminate\Http\Request;

class BillingTransactionController extends Controller
{
    public function index(Request $request)
    {
        $billingTransactions = BillingTransaction::with('customer')->latest()->get();
        if ($request->has('periode')) {
            $billingTransactions = $billingTransactions->where('periode', $request->periode);
        }else{
            $billingTransactions = $billingTransactions->where('periode', date('Ym'));
        }
        return view('billing-transactions.index', compact('billingTransactions'));
    }

    public function create()
    {
        $customers = Customer::all();
        return view('billing-transactions.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'periode' => 'required|string|max:255',
            'bandwith' => 'nullable|string|max:255',
            'pemakaian' => 'nullable|numeric',
            'total' => 'nullable|numeric',
            'harga_satuan' => 'nullable|numeric',
        ]);

        BillingTransaction::create($validated);

        return redirect()->route('billing-transactions.index')
            ->with('success', 'Billing transaction created successfully.');
    }

    public function show(BillingTransaction $billingTransaction)
    {
        $billingTransaction->load('customer');
        return view('billing-transactions.show', compact('billingTransaction'));
    }

    public function edit(BillingTransaction $billingTransaction)
    {
        $customers = Customer::all();
        return view('billing-transactions.edit', compact('billingTransaction', 'customers'));
    }

    public function update(Request $request, BillingTransaction $billingTransaction)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'periode' => 'required|string|max:255',
            'bandwith' => 'nullable|string|max:255',
            'pemakaian' => 'nullable|numeric',
            'total' => 'nullable|numeric',
            'harga_satuan' => 'nullable|numeric',
        ]);

        $billingTransaction->update($validated);

        return redirect()->route('billing-transactions.index')
            ->with('success', 'Billing transaction updated successfully.');
    }

    public function destroy(BillingTransaction $billingTransaction)
    {
        $billingTransaction->delete();

        return redirect()->route('billing-transactions.index')
            ->with('success', 'Billing transaction deleted successfully.');
    }
}
