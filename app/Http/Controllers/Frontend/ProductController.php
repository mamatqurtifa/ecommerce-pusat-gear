<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->active()->inStock();
        
        // filter berdasarkan kategori
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }
        
        // filter berdasarkan rentang harga
        if ($request->has('min_price') && $request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }
        
        if ($request->has('max_price') && $request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }
        
        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }
        
        // Sort
        $sortBy = $request->get('sort_by', 'latest');
        switch ($sortBy) {
            case 'price_low':
                $query->orderByRaw('COALESCE(sale_price, price) ASC');
                break;
            case 'price_high':
                $query->orderByRaw('COALESCE(sale_price, price) DESC');
                break;
            case 'name':
                $query->orderBy('name', 'ASC');
                break;
            case 'featured':
                $query->orderBy('is_featured', 'DESC')->orderBy('created_at', 'DESC');
                break;
            default:
                $query->latest();
        }
        
        $products = $query->paginate(12)->withQueryString();
        $categories = Category::active()->withCount('activeProducts')->get();
        
        $priceRange = Product::active()->inStock()->selectRaw('MIN(COALESCE(sale_price, price)) as min_price, MAX(COALESCE(sale_price, price)) as max_price')->first();
        
        return view('frontend.products.index', compact('products', 'categories', 'priceRange', 'request'));
    }
    
    public function show(Product $product)
    {
        // cek apakah produk aktif
        if (!$product->is_active) {
            abort(404);
        }
        
        $product->load('category');
        
        // produk yang terkait dari kategori yang sama
        $relatedProducts = Product::with('category')
                                 ->active()
                                 ->inStock()
                                 ->where('category_id', $product->category_id)
                                 ->where('id', '!=', $product->id)
                                 ->limit(4)
                                 ->get();
        
        return view('frontend.products.show', compact('product', 'relatedProducts'));
    }
    
    public function category(Category $category)
    {
        // cek apakah kategori aktif
        if (!$category->is_active) {
            abort(404);
        }
        
        $products = Product::with('category')
                          ->active()
                          ->inStock()
                          ->where('category_id', $category->id)
                          ->latest()
                          ->paginate(12);
        
        $categories = Category::active()->withCount('activeProducts')->get();
        
        return view('frontend.products.category', compact('category', 'products', 'categories'));
    }
}