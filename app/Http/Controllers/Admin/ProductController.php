<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'variants'])->withCount('variants');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('material_type', 'like', '%' . $request->search . '%');
        }

        $products = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id'   => 'required|exists:categories,id',
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'material_type' => 'required|string|max:255',
            'price'         => 'required|numeric|min:0',
            'is_featured'   => 'boolean',
            // Initial variant (optional)
            'color_name'    => 'nullable|string|max:100',
            'color_hex'     => 'nullable|string|max:7',
            'size'          => 'nullable|string|max:20',
            'stock'         => 'nullable|integer|min:0',
            'image_url'     => 'nullable|url',
        ]);

        $slug = Str::slug($validated['name']);
        // Ensure unique slug
        $count = Product::where('slug', 'like', "{$slug}%")->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        $product = Product::create([
            'category_id'   => $validated['category_id'],
            'name'          => $validated['name'],
            'slug'          => $slug,
            'description'   => $validated['description'],
            'material_type' => $validated['material_type'],
            'price'         => $validated['price'],
            'is_featured'   => $request->boolean('is_featured'),
        ]);

        // If variant fields supplied, create first variant
        if (!empty($validated['color_name']) && !empty($validated['size'])) {
            ProductVariant::create([
                'product_id' => $product->id,
                'color_name' => $validated['color_name'],
                'color_hex'  => $validated['color_hex'] ?? '#3D3D3D',
                'size'       => $validated['size'],
                'stock'      => $validated['stock'] ?? 10,
                'image_url'  => $validated['image_url'] ?? 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&q=80',
            ]);
        }

        return redirect()->route('admin.products.edit', $product->id)
                         ->with('success', "Produk '{$product->name}' berhasil dibuat! Anda dapat menambahkan varian lainnya di bawah.");
    }

    public function edit(Product $product)
    {
        $product->load(['category', 'variants']);
        $categories = Category::all();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id'   => 'required|exists:categories,id',
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'material_type' => 'required|string|max:255',
            'price'         => 'required|numeric|min:0',
            'is_featured'   => 'boolean',
        ]);

        // Update slug if name changed
        if ($product->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['is_featured'] = $request->boolean('is_featured');
        $product->update($validated);

        return back()->with('success', "Data produk '{$product->name}' berhasil diperbarui.");
    }

    public function destroy(Product $product)
    {
        $name = $product->name;
        $product->delete();

        return redirect()->route('admin.products.index')
                         ->with('success', "Produk '{$name}' dan seluruh variannya telah dihapus.");
    }
}
