<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Store::latest()->paginate(10);
        return view('admin.stores.index', compact('stores'));
    }

    public function create()
    {
        return view('admin.stores.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:100',
            'location' => 'nullable',
            'description' => 'nullable',
            'category' => 'required|in:clothing,electronics,grocery,restaurant,beauty,furniture',
        ]);

        Store::create($validated);

        return redirect()->route('admin.stores.index')->with('success', 'Store created successfully');
    }
    public function edit(Store $store)
{
    return view('admin.stores.edit', compact('store'));
}

public function update(Request $request, Store $store)
{
    $validated = $request->validate([
        'name' => 'required|max:100',
        'location' => 'nullable',
        'description' => 'nullable',
        'category' => 'required|in:clothing,electronics,grocery,restaurant,beauty,furniture',
        'status' => 'boolean'
    ]);

    $store->update($validated);

    return redirect()->route('admin.stores.index')->with('success', 'Store updated successfully');
}

public function destroy(Store $store)
{
    $store->delete();
    return redirect()->route('admin.stores.index')->with('success', 'Store deleted successfully');
}
}