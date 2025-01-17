@extends('layouts.admin')

@section('title', 'Edit Store')

@section('content')
<div class="bg-white rounded-lg shadow-lg p-6">
    <h2 class="text-2xl font-bold mb-6">Edit Store</h2>

    <form action="{{ route('admin.stores.update', $store) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                Name
            </label>
            <input type="text" name="name" id="name" value="{{ $store->name }}"
                   class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="location">
                Location
            </label>
            <input type="text" name="location" id="location" value="{{ $store->location }}"
                   class="shadow border rounded w-full py-2 px-3 text-gray-700">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                Description
            </label>
            <textarea name="description" id="description" 
                      class="shadow border rounded w-full py-2 px-3 text-gray-700" 
                      rows="3">{{ $store->description }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="category">
                Category
            </label>
            <select name="category" id="category" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                @foreach(['clothing', 'electronics', 'grocery', 'restaurant', 'beauty', 'furniture'] as $category)
                    <option value="{{ $category }}" {{ $store->category == $category ? 'selected' : '' }}>
                        {{ ucfirst($category) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
                Status
            </label>
            <select name="status" id="status" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                <option value="1" {{ $store->status ? 'selected' : '' }}>Active</option>
                <option value="0" {{ !$store->status ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Update Store
        </button>
    </form>
</div>
@endsection