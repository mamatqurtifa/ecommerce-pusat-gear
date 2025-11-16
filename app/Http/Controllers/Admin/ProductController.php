<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status);
        }

        // Filter by stock status
        if ($request->has('stock_status')) {
            switch ($request->stock_status) {
                case 'low_stock':
                    $query->where('manage_stock', true)->whereColumn('stock', '<=', 'min_stock')->where('stock', '>', 0);
                    break;
                case 'out_of_stock':
                    $query->where('manage_stock', true)->where('stock', 0);
                    break;
                case 'in_stock':
                    $query->where(function($q) {
                        $q->where('manage_stock', false)
                          ->orWhere(function($q2) {
                              $q2->where('manage_stock', true)->where('stock', '>', 0);
                          });
                    });
                    break;
            }
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('sku', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDir = $request->get('sort_dir', 'desc');
        $query->orderBy($sortBy, $sortDir);

        // Handle Export
        if ($request->has('export') && $request->export === 'excel') {
            $products = $query->get();
            return Excel::download(new ProductsExport($products), 'products-' . date('Y-m-d') . '.xlsx');
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::active()->get();

        // Statistics
        $stats = [
            'total_products' => Product::count(),
            'active_products' => Product::where('is_active', true)->count(),
            'low_stock' => Product::where('manage_stock', true)
                                 ->whereColumn('stock', '<=', 'min_stock')
                                 ->where('stock', '>', 0)
                                 ->count(),
            'featured_products' => Product::where('is_featured', true)->count(),
        ];

        return view('admin.products.index', compact('products', 'categories', 'request', 'stats'));
    }

    public function create()
    {
        $categories = Category::active()->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|min:10',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'sku' => 'required|string|max:100|unique:products,sku',
            'stock' => 'required_if:manage_stock,true|integer|min:0',
            'min_stock' => 'required_if:manage_stock,true|integer|min:0',
            'manage_stock' => 'boolean',
            'weight' => 'nullable|numeric|min:0',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active' => 'boolean',
            'is_featured' => 'boolean'
        ]);

        $product = new Product();
        $product->fill($request->except(['images']));
        $product->manage_stock = $request->has('manage_stock');
        $product->is_active = $request->has('is_active');
        $product->is_featured = $request->has('is_featured');

        // Handle images upload
        if ($request->hasFile('images')) {
            $imagePaths = [];
            $manager = new ImageManager(new Driver());
            
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                
                // Resize and save image with v3 syntax
                $resizedImage = $manager->read($image->getPathname());
                $resizedImage->scale(width: 800, height: 800);
                
                $imagePath = 'products/' . $imageName;
                Storage::disk('public')->put($imagePath, $resizedImage->encode());
                $imagePaths[] = $imagePath;
            }
            $product->images = $imagePaths;
        }

        $product->save();

        return redirect()->route('admin.products.index')
                        ->with('success', 'Product successfully created.');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'orderItems.order']);
        
        // Get recent orders for this product
        $recentOrders = $product->orderItems()
                               ->with('order.user')
                               ->latest()
                               ->limit(10)
                               ->get();

        return view('admin.products.show', compact('product', 'recentOrders'));
    }

    public function edit(Product $product)
    {
        $categories = Category::active()->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|min:10',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'sku' => 'required|string|max:100|unique:products,sku,' . $product->id,
            'stock' => 'required_if:manage_stock,true|integer|min:0',
            'min_stock' => 'required_if:manage_stock,true|integer|min:0',
            'manage_stock' => 'boolean',
            'weight' => 'nullable|numeric|min:0',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active' => 'boolean',
            'is_featured' => 'boolean'
        ]);

        $product->fill($request->except(['images']));
        $product->manage_stock = $request->has('manage_stock');
        $product->is_active = $request->has('is_active');
        $product->is_featured = $request->has('is_featured');

        // Handle images upload
        if ($request->hasFile('images')) {
            // Delete old images
            if ($product->images) {
                foreach ($product->images as $oldImage) {
                    if (Storage::disk('public')->exists($oldImage)) {
                        Storage::disk('public')->delete($oldImage);
                    }
                }
            }

            $imagePaths = [];
            $manager = new ImageManager(new Driver());
            
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                
                // Resize and save image with v3 syntax
                $resizedImage = $manager->read($image->getPathname());
                $resizedImage->scale(width: 800, height: 800);
                
                $imagePath = 'products/' . $imageName;
                Storage::disk('public')->put($imagePath, $resizedImage->encode());
                $imagePaths[] = $imagePath;
            }
            $product->images = $imagePaths;
        }

        $product->save();

        return redirect()->route('admin.products.index')
                        ->with('success', 'Product successfully updated.');
    }

    public function destroy(Product $product)
    {
        // Check if product has orders
        if ($product->orderItems()->count() > 0) {
            return redirect()->route('admin.products.index')
                           ->with('error', 'Cannot delete product that has order history.');
        }

        // Delete images
        if ($product->images) {
            foreach ($product->images as $image) {
                if (Storage::disk('public')->exists($image)) {
                    Storage::disk('public')->delete($image);
                }
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')
                        ->with('success', 'Product successfully deleted.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,feature,unfeature,delete',
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id'
        ]);

        $products = Product::whereIn('id', $request->product_ids);

        switch ($request->action) {
            case 'activate':
                $products->update(['is_active' => true]);
                $message = 'Products successfully activated.';
                break;
            case 'deactivate':
                $products->update(['is_active' => false]);
                $message = 'Products successfully deactivated.';
                break;
            case 'feature':
                $products->update(['is_featured' => true]);
                $message = 'Products successfully set as featured.';
                break;
            case 'unfeature':
                $products->update(['is_featured' => false]);
                $message = 'Products successfully removed from featured.';
                break;
            case 'delete':
                // Check if any product has orders
                $hasOrders = $products->whereHas('orderItems')->exists();
                if ($hasOrders) {
                    return redirect()->route('admin.products.index')
                                   ->with('error', 'Some products cannot be deleted because they have order history.');
                }
                
                // Delete images and products
                $productsToDelete = $products->get();
                foreach ($productsToDelete as $product) {
                    if ($product->images) {
                        foreach ($product->images as $image) {
                            if (Storage::disk('public')->exists($image)) {
                                Storage::disk('public')->delete($image);
                            }
                        }
                    }
                }
                $products->delete();
                $message = 'Products successfully deleted.';
                break;
        }

        return redirect()->route('admin.products.index')->with('success', $message);
    }
}