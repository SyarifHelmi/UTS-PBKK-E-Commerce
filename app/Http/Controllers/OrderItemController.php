<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;

class OrderItemController extends Controller
{
    public function index()
    {
        $items = OrderItem::with('order', 'product')->get();
        return response()->json($items, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id'   => 'required|exists:orders,order_id',
            'product_id' => 'required|exists:products,product_id',
            'quantity'   => 'required|integer|min:1',
            'price'      => 'required|integer|min:0',
        ]);

        $item = OrderItem::create([
            'id'         => Str::uuid(),
            'order_id'   => $validated['order_id'],
            'product_id' => $validated['product_id'],
            'quantity'   => $validated['quantity'],
            'price'      => $validated['price'],
        ]);

        return response()->json($item, 201);
    }

    public function show($id)
    {
        $item = OrderItem::with('order', 'product')->where('id', $id)->first();
        if (!$item) return response()->json(['message' => 'Order item not found'], 404);

        return response()->json($item, 200);
    }

    public function update(Request $request, $id)
    {
        $item = OrderItem::find($id);
        if (!$item) return response()->json(['message' => 'Order item not found'], 404);

        $validated = $request->validate([
            'quantity' => 'sometimes|integer|min:1',
            'price'    => 'sometimes|integer|min:0',
        ]);

        $item->update($validated);
        return response()->json($item, 200);
    }

    public function destroy($id)
    {
        $item = OrderItem::find($id);
        if (!$item) return response()->json(['message' => 'Order item not found'], 404);

        $item->delete();
        return response()->json(['message' => 'Order item deleted'], 200);
    }
}
