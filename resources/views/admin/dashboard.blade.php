@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-500 bg-opacity-75">
                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="mb-2 text-sm font-medium text-gray-600">Total Products</p>
                <p class="text-lg font-semibold text-gray-700">{{ $totalProducts }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-500 bg-opacity-75">
                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="mb-2 text-sm font-medium text-gray-600">Total Stores</p>
                <p class="text-lg font-semibold text-gray-700">{{ $totalStores }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-500 bg-opacity-75">
                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="mb-2 text-sm font-medium text-gray-600">Trending Products</p>
                <p class="text-lg font-semibold text-gray-700">{{ $trendingProducts }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-red-500 bg-opacity-75">
                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="mb-2 text-sm font-medium text-gray-600">Best Selling</p>
                <p class="text-lg font-semibold text-gray-700">{{ $bestSelling }}</p>
            </div>
        </div>
    </div>
</div>

<div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-xl font-semibold mb-4">Recent Products</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 text-left">Name</th>
                        <th class="px-4 py-2 text-left">Store</th>
                        <th class="px-4 py-2 text-left">Price</th>
                        <th class="px-4 py-2 text-left">Category</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentProducts as $product)
                    <tr>
                        <td class="border px-4 py-2">{{ $product->name }}</td>
                        <td class="border px-4 py-2">{{ $product->store->name }}</td>
                        <td class="border px-4 py-2">${{ $product->price }}</td>
                        <td class="border px-4 py-2">{{ ucfirst($product->category) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-xl font-semibold mb-4">Recent Stores</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 text-left">Name</th>
                        <th class="px-4 py-2 text-left">Category</th>
                        <th class="px-4 py-2 text-left">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentStores as $store)
                    <tr>
                        <td class="border px-4 py-2">{{ $store->name }}</td>
                        <td class="border px-4 py-2">{{ ucfirst($store->category) }}</td>
                        <td class="border px-4 py-2">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $store->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $store->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection