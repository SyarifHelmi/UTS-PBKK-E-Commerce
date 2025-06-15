<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    public function index()
    {
        return response()->json(Customer::all(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:50',
            'email'    => 'required|email|unique:customers,email',
            'password' => 'required|string|min:6',
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string|max:255',
        ]);

        $customer = Customer::create([
            'customer_id' => Str::uuid(),
            'name'        => $validated['name'],
            'email'       => $validated['email'],
            'password'    => bcrypt($validated['password']),
            'phone'       => $validated['phone'] ?? null,
            'address'     => $validated['address'] ?? null,
        ]);

        return response()->json($customer, 201);
    }

    public function show($id)
    {
        $customer = Customer::where('customer_id', $id)->first();
        if (!$customer) return response()->json(['message' => 'Customer not found'], 404);

        return response()->json($customer, 200);
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::where('customer_id', $id)->first();
        if (!$customer) return response()->json(['message' => 'Customer not found'], 404);

        $validated = $request->validate([
            'name'     => 'sometimes|required|string|max:50',
            'email'    => 'sometimes|required|email|unique:customers,email,' . $customer->customer_id . ',customer_id',
            'password' => 'nullable|string|min:6',
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string|max:255',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }

        $customer->update($validated);
        return response()->json($customer, 200);
    }

    public function destroy($id)
    {
        $customer = Customer::where('customer_id', $id)->first();
        if (!$customer) return response()->json(['message' => 'Customer not found'], 404);

        $customer->delete();
        return response()->json(['message' => 'Customer deleted'], 200);
    }
}