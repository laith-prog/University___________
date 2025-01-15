<?php
namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class StoreController extends Controller
{
    public function index()
{
    $stores = Store::paginate(10);
        return response()->json($stores, 200); // Return paginated result as JSON response.
}
    public function search(Request $request)
    {
        
        $query = Store::query();

        // Filter by name
        if ($request->has('query')) {
            $query->where('name', 'LIKE', '%' . $request->input('query') . '%');
        }
        $stores = $query->paginate(10)->appends($request->query());

        return response()->json($stores,200);

        
    }

    public function show($id)
    {
        $store = Store::with('products')->find($id);

        if (!$store) {
            return response()->json(['message' => 'Store not found'], 404);
        }
        $store->Trending += 1;
        $store->save();

        return response()->json($store);
    }
    public function getTrendingStores()
    {
        // Fetch products with pagination (10 products per page)
        $store = Store::orderBy('Trending', 'desc')->paginate(10);
    
        return response()->json($store);
    }
   

public function getStoresByCategory(Request $request)
{
    // Validate the input category
    $request->validate([
        'category' => 'required|in:clothing,electronics,grocery,restaurant,beauty,furniture'
    ]);

    // Fetch stores by category with pagination
    $stores = Store::where('category', $request->category)->paginate(10);

    // Append the 'category' parameter to the pagination links
    $stores->appends(['category' => $request->category]);

    // Return the paginated data
    return response()->json([
        'message' => 'Stores retrieved successfully',
        'data' => $stores
    ]);
}

       



    
}
