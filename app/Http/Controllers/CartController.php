<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Add an item to the cart.
     */
    public function addToCart(Request $request)
{
    // Validate the request
    $validated = $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
    ]);

    $user = Auth::user();
    $cart = Cart::firstOrCreate(['user_id' => $user->id]);

    // Fetch the product and check available quantity
    $product = Product::find($request->product_id);

    if ($product->quantity < $request->quantity) {
        return response()->json(['message' => 'Not enough product quantity available'], 400);
    }

    // Check if the product already exists in the cart
    $cartItem = CartItem::where('cart_id', $cart->id)
        ->where('product_id', $request->product_id)
        ->first();

    if ($cartItem) {
        $cartItem->quantity += $request->quantity;

        if ($cartItem->quantity > $product->quantity) {
            return response()->json(['message' => 'Not enough product quantity available'], 400);
        }

        $cartItem->save();
    } else {
        // Create a new cart item
        $cartItem = CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
        ]);
    }

    // Deduct the quantity from the product
    $product->quantity -= $request->quantity;
    $product->save();

    // Schedule deletion of the cart item after 10 minutes if not ordered
    $cartItem->deleteAfter(10);

    return response()->json(['message' => 'Product added to cart successfully']);
}


    /**
     * Edit an item in the cart.
     */
    public function editCartItem(Request $request)
{
    // Validate request input
    $validated = $request->validate([
        'cart_item_id' => 'required|exists:cart_items,id',
        'quantity' => 'required|integer|min:1',
    ]);

    $user = Auth::user();
    $cart = Cart::where('user_id', $user->id)->first();
    if (!$cart) {
        return response()->json(['message' => 'Cart not found'], 404);
    }

    $cartItem = CartItem::where('id', $request->cart_item_id)
        ->where('cart_id', $cart->id)
        ->first();

    if (!$cartItem) {
        return response()->json(['message' => 'Cart item not found or does not belong to this user'], 404);
    }

    $product = Product::find($cartItem->product_id);

    // Restore the quantity from the previous cart item
    $product->quantity += $cartItem->quantity;

    if ($request->quantity > $product->quantity) {
        return response()->json(['message' => 'Not enough product quantity available'], 400);
    }

    // Update the product's quantity and the cart item's quantity
    $product->quantity -= $request->quantity;
    $product->save();

    $cartItem->quantity = $request->quantity;
    $cartItem->save();

    // Reschedule deletion of the cart item after 10 minutes
    $cartItem->deleteAfter(10);

    return response()->json(['message' => 'Cart item updated successfully']);
}

}
