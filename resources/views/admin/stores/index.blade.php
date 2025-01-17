@extends('layouts.admin')

@section('title', 'Stores')

@section('content')
<div class="bg-white rounded-lg shadow-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Stores List</h2>
        <a href="{{ route('admin.stores.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Add New Store
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full table-auto">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-left">Name</th>
                    <th class="px-4 py-2 text-left">Category</th>
                    <th class="px-4 py-2 text-left">Location</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Trending</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stores as $store)
                <tr>
                    <td class="border px-4 py-2">{{ $store->name }}</td>
                    <td class="border px-4 py-2">{{ ucfirst($store->category) }}</td>
                    <td class="border px-4 py-2">{{ $store->location }}</td>
                    <td class="border px-4 py-2">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $store->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $store->status ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="border px-4 py-2">{{ $store->Trending }}</td>
                    <td class="border px-4 py-2">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.stores.edit', $store) }}" class="text-blue-500 hover:text-blue-700">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <form action="{{ route('admin.stores.destroy', $store) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Are you sure you want to delete this store?')">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $stores->links() }}
    </div>
</div>
@endsection