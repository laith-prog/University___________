@extends('layouts.admin')

@section('title', 'Edit Order')

@section('content')
<div class="bg-white rounded-lg shadow-lg p-6">
    <h1 class="text-xl font-semibold mb-4">Edit Order #{{ $order->id }}</h1>

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-lg">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- User Information -->
        <div>
            <label for="user" class="block text-sm font-medium text-gray-700">User</label>
            <input
                type="text"
                id="user"
                class="mt-1 block w-full bg-gray-100 border border-gray-300 rounded-md shadow-sm text-sm text-gray-700 cursor-not-allowed"
                value="{{ $order->user->name }}"
                readonly>
        </div>

        <!-- Total Amount -->
        <div>
            <label for="total_amount" class="block text-sm font-medium text-gray-700">Total Amount</label>
            <input
                type="text"
                id="total_amount"
                class="mt-1 block w-full bg-gray-100 border border-gray-300 rounded-md shadow-sm text-sm text-gray-700 cursor-not-allowed"
                value="${{ $order->total_amount }}"
                readonly>
        </div>

        <!-- Delivery Location -->
        <div>
            <label for="delivery_location" class="block text-sm font-medium text-gray-700">Delivery Location</label>
            <input
                id="delivery_location"
                name="delivery_location"
                rows="3"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm"
                value="{{ $order->delivery_location }}"

                readonly>
        </div>

        <!-- Status -->
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
            <select
                id="status"
                name="status"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm"
                required>
                @foreach (['pending', 'accepted', 'delivering', 'delivered', 'cancelled'] as $status)
                    <option value="{{ $status }}" @if ($order->status === $status) selected @endif>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Payment Method -->
        <div>
            <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method</label>
            <input
                type="text"
                id="payment_method"
                class="mt-1 block w-full bg-gray-100 border border-gray-300 rounded-md shadow-sm text-sm text-gray-700 cursor-not-allowed"
                value="{{ ucfirst($order->payment_method) ?? 'N/A' }}"
                readonly>
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" class="w-full bg-blue-600 text-white text-sm font-medium py-2 px-4 rounded-md shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Update Order
            </button>
        </div>
    </form>
</div>
@endsection
