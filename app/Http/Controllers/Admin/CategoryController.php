<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Exports\CategoriesExport;
use Maatwebsite\Excel\Facades\Excel;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::withCount('products');

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $isActive = $request->status === 'active' ? 1 : 0;
            $query->where('is_active', $isActive);
        }

        // Sort
        $query->latest();

        // Handle Export
        if ($request->has('export') && $request->export === 'excel') {
            $categories = $query->get();
            return Excel::download(new CategoriesExport($categories), 'categories-' . date('Y-m-d') . '.xlsx');
        }

        $categories = $query->paginate(10)->withQueryString();

        // Statistics
        $stats = [
            'total_categories' => Category::count(),
            'active_categories' => Category::where('is_active', true)->count(),
            'total_products' => \App\Models\Product::count(),
            'empty_categories' => Category::doesntHave('products')->count(),
        ];

        return view('admin.categories.index', compact('categories', 'stats'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active' => 'boolean'
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->description = $request->description;
        $category->is_active = $request->has('is_active');

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            
            // Resize and save image with v3 syntax
            $manager = new ImageManager(new Driver());
            $resizedImage = $manager->read($image->getPathname());
            $resizedImage->scale(width: 400, height: 400);
            
            $imagePath = 'categories/' . $imageName;
            Storage::disk('public')->put($imagePath, $resizedImage->encode());
            $category->image = $imagePath;
        }

        $category->save();

        return redirect()->route('admin.categories.index')
                        ->with('success', 'Category successfully created.');
    }

    public function show(Category $category)
    {
        $category->load(['products' => function($query) {
            $query->latest()->limit(10);
        }]);

        return view('admin.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active' => 'boolean'
        ]);

        $category->name = $request->name;
        $category->description = $request->description;
        $category->is_active = $request->has('is_active');

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }

            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            
            // Resize and save image with v3 syntax
            $manager = new ImageManager(new Driver());
            $resizedImage = $manager->read($image->getPathname());
            $resizedImage->scale(width: 400, height: 400);
            
            $imagePath = 'categories/' . $imageName;
            Storage::disk('public')->put($imagePath, $resizedImage->encode());
            $category->image = $imagePath;
        }

        $category->save();

        return redirect()->route('admin.categories.index')
                        ->with('success', 'Category successfully updated.');
    }

    public function destroy(Category $category)
    {
        // Check if category has products
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')
                           ->with('error', 'Cannot delete category that has products.');
        }

        // Delete image
        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
                        ->with('success', 'Category successfully deleted.');
    }
}