<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();
        return response()->json($products, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|integer|min:0',
            'stock'       => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,category_id',
        ]);

        $product = Product::create([
            'product_id'  => Str::uuid(),
            'name'        => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price'       => $validated['price'],
            'stock'       => $validated['stock'],
            'category_id' => $validated['category_id'],
        ]);

        return response()->json($product, 201);
    }

    public function show($id)
    {
        $product = Product::with('category')->where('product_id', $id)->first();
        if (!$product) return response()->json(['message' => 'Product not found'], 404);

        return response()->json($product, 200);
    }

    public function update(Request $request, $id)
    {
        $product = Product::where('product_id', $id)->first();
        if (!$product) return response()->json(['message' => 'Product not found'], 404);

        $validated = $request->validate([
            'name'        => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'sometimes|integer|min:0',
            'stock'       => 'sometimes|integer|min:0',
            'category_id' => 'sometimes|exists:categories,id',
        ]);

        $product->update($validated);
        return response()->json($product, 200);
    }

    public function destroy($id)
    {
        $product = Product::where('product_id', $id)->first();
        if (!$product) return response()->json(['message' => 'Product not found'], 404);

        $product->delete();
        return response()->json(['message' => 'Product deleted'], 200);
    }
}