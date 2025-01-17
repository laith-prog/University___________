@extends('layouts.admin')

@section('title', 'Create Store')

@section('content')
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-bold mb-6">Create New Store</h2>

        <form action="{{ route('admin.stores.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                    Name
                </label>
                <input type="text" name="name" id="name" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="location">
                    Location
                </label>
                <input type="text" name="location" id="location" class="shadow border rounded w-full py-2 px-3 text-gray-700">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                    Description
                </label>
                <textarea name="description" id="description" class="shadow border rounded w-full py-2 px-3 text-gray-700" rows="3"></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="category">
                    Category
                </label>
                <select name="category" id="category" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                    <option value="clothing">Clothing</option>
                    <option value="electronics">Electronics</option>
                    <option value="grocery">Grocery</option>
                    <option value="restaurant">Restaurant</option>
                    <option value="beauty">Beauty</option>
                    <option value="furniture">Furniture</option>
                </select>
            </div>

            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Create Store
            </button>
        </form>
    </div>
@endsection