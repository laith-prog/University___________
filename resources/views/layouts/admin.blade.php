<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 text-white">
            <div class="p-4">
                <h2 class="text-2xl font-bold">Admin Panel</h2>
            </div>
            <nav class="mt-4">
    <a href="{{ route('admin.dashboard') }}" class="block py-2 px-4 hover:bg-gray-700">Dashboard</a>
    <a href="{{ route('admin.products.create') }}" class="block py-2 px-4 hover:bg-gray-700">Add Product</a>
    <a href="{{ route('admin.products.index') }}" class="block py-2 px-4 hover:bg-gray-700">Products List</a>
    <a href="{{ route('admin.stores.create') }}" class="block py-2 px-4 hover:bg-gray-700">Add Store</a>
    <a href="{{ route('admin.stores.index') }}" class="block py-2 px-4 hover:bg-gray-700">Stores List</a>
    <a href="{{ route('admin.orders.index') }}" class="block py-2 px-4 hover:bg-gray-700">Orders List</a>
    {{-- <!-- <a href="{{ route('admin.orders.create') }}" class="block py-2 px-4 hover:bg-gray-700">Add Order</a> --> --}}
    <form method="POST" action="{{ route('logout') }}" class="block py-2 px-4">
        @csrf
        <button type="submit" class="text-white hover:text-gray-300">Logout</button>
    </form>
</nav>

        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            @yield('content')
        </div>
    </div>
</body>
</html>