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


     public function getCart(Request $request)
     {
         // Get the authenticated user
         $user = Auth::user();
     
         // Check if the user is authenticated
         if (!$user) {
             return response()->json(['message' => 'Unauthorized'], 401);
         }
     
         // Get the user's cart with the associated cart items and product details
         $cart = Cart::with('Items.product')->where('user_id', $user->id)->first();
     
         // If the cart doesn't exist, return a 404 response
         if (!$cart) {
             return response()->json(['message' => 'Cart not found'], 404);
         }
     
         // Calculate the total price of the cart
         $totalPrice = 0;
         foreach ($cart->Items as $cartItem) {
             $totalPrice += $cartItem->quantity * $cartItem->product->price;
         }
     
         // Return the cart with its items and total price
         return response()->json([
             'cart' => $cart,  // cart object will include 'cartItems' and related 'product'
             'total' => number_format($totalPrice, 2)  // total price formatted to 2 decimal places
         ]);
     }
     

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
public function cancelCart(Request $request)
{
    // Get the authenticated user
    $user = Auth::user();

    // Check if the user is authenticated
    if (!$user) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    // Retrieve the user's cart
    $cart = Cart::where('user_id', $user->id)->first();

    // If the cart doesn't exist, return a 404 response
    if (!$cart) {
        return response()->json(['message' => 'Cart not found'], 404);
    }

    // Retrieve all the cart items
    $cartItems = CartItem::where('cart_id', $cart->id)->get();

    // Check if there are cart items to cancel
    if ($cartItems->isEmpty()) {
        return response()->json(['message' => 'No items in the cart to cancel'], 404);
    }

    // Prepare an array to hold the details of canceled products
    $canceledProducts = [];

    // Loop through each cart item and restore the product quantity
    foreach ($cartItems as $cartItem) {
        $product = Product::find($cartItem->product_id);

        if ($product) {
            // Restore the product quantity to stock
            $product->quantity += $cartItem->quantity;
            $product->save();

            // Add product details to the canceled products array
            $canceledProducts[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'canceled_quantity' => $cartItem->quantity,
                'remaining_stock' => $product->quantity
            ];
        }
    }

    // Delete all cart items
    CartItem::where('cart_id', $cart->id)->delete();

    // Return success message with the canceled products details
    return response()->json([
        'message' => 'Cart canceled and items removed successfully',
        'canceled_products' => $canceledProducts
    ]);
}
public function deleteCartItem(Request $request)
{
    // Validate the input data
    $validated = $request->validate([
        'cart_item_id' => 'required|exists:cart_items,id',
    ]);

    // Get the authenticated user
    $user = Auth::user();

    // Check if the user is authenticated
    if (!$user) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    // Retrieve the user's cart
    $cart = Cart::where('user_id', $user->id)->first();

    // If the cart doesn't exist, return a 404 response
    if (!$cart) {
        return response()->json(['message' => 'Cart not found'], 404);
    }

    // Retrieve the cart item
    $cartItem = CartItem::where('id', $request->cart_item_id)
        ->where('cart_id', $cart->id)
        ->first();

    // If the cart item doesn't exist or doesn't belong to the user, return an error
    if (!$cartItem) {
        return response()->json(['message' => 'Cart item not found or does not belong to this user'], 404);
    }

    // Get the product associated with the cart item
    $product = Product::find($cartItem->product_id);

    // Restore the quantity of the product
    if ($product) {
        $product->quantity += $cartItem->quantity;
        $product->save();
    }

    // Delete the cart item
    $cartItem->delete();

    // Return a success response with the canceled product details
    return response()->json([
        'message' => 'Cart item deleted successfully',
        'product_id' => $product->id,
        'product_name' => $product->name,
        'canceled_quantity' => $cartItem->quantity,
        'remaining_stock' => $product->quantity
    ]);
}




}
