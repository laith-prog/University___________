<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(10);
        return response()->json($products, 200);
    }

    public function search(Request $request)
    {
    $query = Product::query();
    if ($request->has('query')) {
        $query->where('name', 'LIKE', '%' . $request->input('query') . '%');
    }

    $products = $query->paginate(10)->appends($request->query());

    return response()->json($products, 200); // Return the search results as a JSON response
    }
    public function show($id)
    {
        // Find the product by ID
        $product = Product::find($id);
    
        // If the product is not found, return a 404 response
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
    
        // Increment the 'Trending' field
        $product->Trending += 1;
        $product->save();
    
        // Return the product as a JSON response
        return response()->json($product);
    }
    

    public function getProductsByCategory(Request $request)
    {
    // Validate the input category
    $request->validate([
        'category' => 'required|in:clothes,electronics,food,cosmetics,furniture,accessories'
    ]);

    // Fetch products based on the category
    $products = Product::where('category', $request->category)->paginate(10);
    $products->appends(['category' => $request->category]);

    // Return the response
    return response()->json([
        'message' => 'Products retrieved successfully',
        'data' => $products
    ]);
    }

    public function getMostSellingProducts()
    {
        // Fetch products with pagination (10 products per page)
        $products = Product::orderBy('best_Selling', 'desc')->paginate(10); 
    
        return response()->json($products);
    }
    
    public function getTrendingProducts()
    {
        // Fetch products with pagination (10 products per page)
        $products = Product::orderBy('Trending', 'desc')->paginate(10);
    
        return response()->json($products);
    }
    

}
