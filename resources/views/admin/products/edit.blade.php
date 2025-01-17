@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
<div class="bg-white rounded-lg shadow-lg p-6">
    <h2 class="text-2xl font-bold mb-6">Edit Product</h2>

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="store_id">
                Store
            </label>
            <select name="store_id" id="store_id" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                @foreach($stores as $store)
                    <option value="{{ $store->id }}" {{ $product->store_id == $store->id ? 'selected' : '' }}>
                        {{ $store->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                Name
            </label>
            <input type="text" name="name" id="name" value="{{ $product->name }}" 
                   class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                Description
            </label>
            <textarea name="description" id="description" 
                      class="shadow border rounded w-full py-2 px-3 text-gray-700" 
                      rows="3">{{ $product->description }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="price">
                Price
            </label>
            <input type="number" step="0.01" name="price" id="price" value="{{ $product->price }}"
                   class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="quantity">
                Quantity
            </label>
            <input type="number" name="quantity" id="quantity" value="{{ $product->quantity }}"
                   class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="category">
                Category
            </label>
            <select name="category" id="category" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                @foreach(['clothes', 'electronics', 'food', 'cosmetics', 'furniture', 'accessories'] as $category)
                    <option value="{{ $category }}" {{ $product->category == $category ? 'selected' : '' }}>
                        {{ ucfirst($category) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="image">
                Image
            </label>
            @if($product->image)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $product->image) }}" 
                         alt="{{ $product->name }}" 
                         class="w-32 h-32 object-cover">
                </div>
            @endif
            <input type="file" name="image" id="image" 
                   class="shadow border rounded w-full py-2 px-3 text-gray-700">
        </div>

        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Update Product
        </button>
    </form>
</div>
@endsection