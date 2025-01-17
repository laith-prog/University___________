<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('store')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $stores = Store::all();
        return view('admin.products.create', compact('stores'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'name' => 'required|max:100',
            'description' => 'nullable',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'category' => 'required|in:clothes,electronics,food,cosmetics,furniture,accessories',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully');
    }
    public function edit(Product $product)
{
    $stores = Store::all();
    return view('admin.products.edit', compact('product', 'stores'));
}

public function update(Request $request, Product $product)
{
    $validated = $request->validate([
        'store_id' => 'required|exists:stores,id',
        'name' => 'required|max:100',
        'description' => 'nullable',
        'price' => 'required|numeric',
        'quantity' => 'required|integer',
        'category' => 'required|in:clothes,electronics,food,cosmetics,furniture,accessories',
        'image' => 'nullable|image|max:2048'
    ]);

    if ($request->hasFile('image')) {
        $validated['image'] = $request->file('image')->store('products', 'public');
    }

    $product->update($validated);

    return redirect()->route('admin.products.index')->with('success', 'Product updated successfully');
}

public function destroy(Product $product)
{
    $product->delete();
    return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully');
}
}