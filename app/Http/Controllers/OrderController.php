<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Create a new order
     */
    public function createOrder(Request $request)
{

    $validated = $request->validate([
        'payment_method' => 'required|string', // E.g., 'credit_card', 'paypal', etc.
        'transaction_id' => 'nullable|string', // Optional transaction ID
    ]);
    // Validate only delivery_location as it will be fetched from User model
    $user = Auth::user();

    if (!$user) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    // Ensure user has a location set
    if (!$user->location) {
        return response()->json(['message' => 'User has no delivery location set'], 400);
    }

    $cart = Cart::where('user_id', $user->id)->first();

    if (!$cart) {
        return response()->json(['message' => 'Cart is empty'], 404);
    }

    $cartItems = CartItem::where('cart_id', $cart->id)->get();

    if ($cartItems->isEmpty()) {
        return response()->json(['message' => 'Cart has no items'], 400);
    }

    $totalAmount = 0;

    foreach ($cartItems as $cartItem) {
        $product = Product::find($cartItem->product_id);

        if (!$product) {
            return response()->json([
                'message' => "Product not found: ID {$cartItem->product_id}",
            ], 404);
        }

        $totalAmount += $product->price * $cartItem->quantity;
    }

    // Get delivery location directly from User model
    $deliveryLocation = $user->location;

    $order = Order::create([
        'user_id' => $user->id,
        'total_amount' => $totalAmount,
        'status' => 'pending',
        'delivery_location' => $deliveryLocation,
        'payment_method'=>$request->payment_method,
        'transaction_id'=>$request->transaction_id
    ]);

    foreach ($cartItems as $cartItem) {
        // Create the order item
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $cartItem->product_id,
            'quantity' => $cartItem->quantity,
            'price_at_time' => $cartItem->product->price,
        ]);

        // Increment the `best_Selling` field of the product
        $product = Product::find($cartItem->product_id);
        $product->best_Selling += $cartItem->quantity; // Increment by the quantity ordered
        $product->save();
    }

    // Clear the cart
    CartItem::where('cart_id', $cart->id)->delete();

    return response()->json(['message' => 'Order created successfully', 'order' => $order]);
}


    /**
     * Display all orders for the authenticated user
     */
    public function getUserOrders()
    {
        $user = Auth::user();

        $orders = Order::where('user_id', $user->id)->with('orderItems.product')->get();

        return response()->json(['orders' => $orders]);
    }

    /**
     * Get the details of a specific order
     */
    public function getOrderDetails($orderId)
    {
        $user = Auth::user();

        $order = Order::where('id', $orderId)
                      ->where('user_id', $user->id)
                      ->with('orderItems.product') // Include product details in the response
                      ->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return response()->json(['order' => $order]);
    }

    /**
     * Edit an existing order (only if status is pending)
     */
    public function editOrder(Request $request, $orderId)
    {
        $validated = $request->validate([
            'delivery_location'=>'nullable|string',
            'order_items' => 'required|array',
            'order_items.*.product_id' => 'required|exists:products,id',
            'order_items.*.quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        $order = Order::where('id', $orderId)->where('user_id', $user->id)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($order->status != 'pending') {
            return response()->json(['message' => 'You cannot edit an order that is not pending'], 400);
        }

        // Delete current order items
        $order->orderItems()->delete();

        // Recalculate total amount and add new items
        $totalAmount = 0;
        foreach ($validated['order_items'] as $item) {
            $product = Product::find($item['product_id']);

            if (!$product) {
                return response()->json([
                    'message' => "Product not found: ID {$item['product_id']}",
                ], 404);
            }

            $totalAmount += $product->price * $item['quantity'];

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price_at_time' => $product->price,
            ]);
        }

        // Update the order
        $order->update([
            'total_amount' => $totalAmount,
        ]);

        return response()->json(['message' => 'Order updated successfully', 'order' => $order]);
    }

    /**
     * Cancel an existing order (only if status is pending)
     */
    public function cancelOrder($orderId)
    {
        $user = Auth::user();
        $order = Order::where('id', $orderId)->where('user_id', $user->id)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($order->status != 'pending') {
            return response()->json(['message' => 'You can only cancel a pending order'], 400);
        }

        // Update order status to canceled
        $order->status = 'cancelled';
        $order->save();

        return response()->json(['message' => 'Order canceled successfully']);
    }
}
