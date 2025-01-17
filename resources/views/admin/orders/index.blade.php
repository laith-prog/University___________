@extends('layouts.admin')

@section('title', 'Order Management')

@section('content')
<div class="bg-white rounded-lg shadow-lg p-6">
    <h1 class="text-xl font-semibold mb-4">Order Management</h1>

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full table-auto">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">ID</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">First Name</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Total Amount</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Status</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2 text-sm text-gray-700">{{ $order->id }}</td>
                        <td class="px-4 py-2 text-sm text-gray-700">{{ $order->user->first_name }}</td>
                        <td class="px-4 py-2 text-sm text-gray-700">${{ $order->total_amount }}</td>
                        <td class="px-4 py-2 text-sm">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if ($order->status === 'pending') bg-yellow-100 text-yellow-800 
                                @elseif ($order->status === 'accepted') bg-blue-100 text-blue-800
                                @elseif ($order->status === 'delivering') bg-orange-100 text-orange-800
                                @elseif ($order->status === 'delivered') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-2">
                            <a href="{{ route('admin.orders.edit', $order) }}"
                                class="text-blue-600 hover:text-blue-800 text-sm font-medium">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $orders->links('pagination::tailwind') }} <!-- Use TailwindCSS pagination -->
    </div>
</div>
@endsection
