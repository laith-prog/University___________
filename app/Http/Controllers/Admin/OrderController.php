<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->latest()->paginate(10); // Add pagination
        return view('admin.orders.index', compact('orders'));
    }

    public function edit(Order $order)
    {
        $statuses = [ 'accepted', 'delivering', 'delivered', 'cancelled'];
        return view('admin.orders.edit', compact('order', 'statuses'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:accepted,delivering,delivered,cancelled',
        ]);

        $order->update([
            'status' => $request->status,
        ]);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order status updated successfully.');
    }
}
