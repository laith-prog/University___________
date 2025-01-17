@extends('layouts.admin')

@section('title', 'Create Product')

@section('content')
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-bold mb-6">Create New Product</h2>

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="store_id">
                    Store
                </label>
                <select name="store_id" id="store_id" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                    @foreach($stores as $store)
                        <option value="{{ $store->id }}">{{ $store->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                    Name
                </label>
                <input type="text" name="name" id="name" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                    Description
                </label>
                <textarea name="description" id="description" class="shadow border rounded w-full py-2 px-3 text-gray-700" rows="3"></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="price">
                    Price
                </label>
                <input type="number" step="0.01" name="price" id="price" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="quantity">
                    Quantity
                </label>
                <input type="number" name="quantity" id="quantity" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="category">
                    Category
                </label>
                <select name="category" id="category" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                    <option value="clothes">Clothes</option>
                    <option value="electronics">Electronics</option>
                    <option value="food">Food</option>
                    <option value="cosmetics">Cosmetics</option>
                    <option value="furniture">Furniture</option>
                    <option value="accessories">Accessories</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="image">
                    Image
                </label>
                <input type="file" name="image" id="image" class="shadow border rounded w-full py-2 px-3 text-gray-700">
            </div>

            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Create Product
            </button>
        </form>
    </div>
@endsection