<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('customer')->get();
        return response()->json($orders, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id'  => 'required|exists:customers,customer_id',
            'order_date'   => 'required|date',
            'total_amount' => 'required|integer|min:0',
            'status'       => 'required|string|max:50',
        ]);

        $order = Order::create([
            'order_id'     => Str::uuid(),
            'customer_id'  => $validated['customer_id'],
            'order_date'   => $validated['order_date'],
            'total_amount' => $validated['total_amount'],
            'status'       => $validated['status'],
        ]);

        return response()->json($order, 201);
    }

    public function show($id)
{
    $order = Order::with('customer')->where('order_id', $id)->first();

    if (!$order) {
        return response()->json(['message' => 'Order not found'], 404);
    }

    return response()->json($order, 200);
}

    public function update(Request $request, $id)
    {
        $order = Order::where('order_id', $id)->first();
        if (!$order) return response()->json(['message' => 'Order not found'], 404);

        $validated = $request->validate([
            'order_date'   => 'sometimes|date',
            'total_amount' => 'sometimes|integer|min:0',
            'status'       => 'sometimes|string|max:50',
        ]);

        $order->update($validated);
        return response()->json($order, 200);
    }

    public function destroy($id)
    {
        $order = Order::where('order_id', $id)->first();
        if (!$order) return response()->json(['message' => 'Order not found'], 404);

        $order->delete();
        return response()->json(['message' => 'Order deleted'], 200);
    }
}
