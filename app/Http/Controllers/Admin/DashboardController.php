<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Store;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'totalProducts' => Product::count(),
            'totalStores' => Store::count(),
            'trendingProducts' => Product::where('Trending', '>', 0)->count(),
            'bestSelling' => Product::where('best_Selling', '>', 0)->count(),
            'recentProducts' => Product::with('store')->latest()->take(5)->get(),
            'recentStores' => Store::latest()->take(5)->get(),
        ];

        return view('admin.dashboard', $data);
    }
}