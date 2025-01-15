<?php
namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Add a product to favorites
     */
    public function addToFavorites(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',  // Ensure product exists
        ]);

        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Check if the product is already in favorites
        $existingFavorite = Favorite::where('user_id', $user->id)
                                    ->where('product_id', $validated['product_id'])
                                    ->first();

        if ($existingFavorite) {
            return response()->json(['message' => 'Product already in favorites'], 400);
        }

        // Add the product to favorites
        $favorite = Favorite::create([
            'user_id' => $user->id,
            'product_id' => $validated['product_id'],
        ]);

        return response()->json(['message' => 'Product added to favorites', 'favorite' => $favorite]);
    }

    /**
     * Remove a product from favorites
     */
    public function removeFromFavorites($productId)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Find the favorite record for the user and product
        $favorite = Favorite::where('user_id', $user->id)
                            ->where('product_id', $productId)
                            ->first();

        if (!$favorite) {
            return response()->json(['message' => 'Product not found in favorites'], 404);
        }

        // Delete the favorite
        $favorite->delete();

        return response()->json(['message' => 'Product removed from favorites']);
    }

    /**
     * Get all favorite products for the authenticated user
     */
    public function getFavorites()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Get all favorite products for the user
        $favorites = Favorite::where('user_id', $user->id)
                             ->with('product') // Include product details in the response
                             ->get();

        return response()->json(['favorites' => $favorites]);
    }
}
