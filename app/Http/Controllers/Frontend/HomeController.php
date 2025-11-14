<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // produk unggulan
        $featuredProducts = Product::with('category')
                                  ->active()
                                  ->featured()
                                  ->inStock()
                                  ->latest()
                                  ->limit(8)
                                  ->get();

        // produk terbaru
        $latestProducts = Product::with('category')
                                ->active()
                                ->inStock()
                                ->latest()
                                ->limit(12)
                                ->get();

        // Categories dengan jumlah produk
        $categories = Category::active()
                             ->withCount(['activeProducts' => function($query) {
                                 $query->inStock();
                             }])
                             ->having('active_products_count', '>', 0)
                             ->get();

        // produk diskon
        $saleProducts = Product::with('category')
                              ->active()
                              ->onSale()
                              ->inStock()
                              ->latest()
                              ->limit(6)
                              ->get();

        return view('frontend.home', compact(
            'featuredProducts',
            'latestProducts', 
            'categories',
            'saleProducts'
        ));
    }
}