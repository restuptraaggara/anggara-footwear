<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['variants', 'category']);

        // Filter: category slug
        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        // Filter: material type
        if ($request->filled('material')) {
            $query->where('material_type', 'like', '%' . $request->material . '%');
        }

        // Filter: size (via variants)
        if ($request->filled('size')) {
            $query->whereHas('variants', fn($q) => $q->where('size', $request->size)->where('stock', '>', 0));
        }

        // Live search: name or material
        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('material_type', 'like', "%{$term}%")
                  ->orWhere('description', 'like', "%{$term}%");
            });
        }

        // AJAX request: return JSON for live-filter
        if ($request->expectsJson() || $request->ajax()) {
            $products = $query->get();
            return response()->json([
                'products' => $products->map(function ($p) {
                    $first = $p->variants->first();
                    $colors = $p->variants->unique('color_name')->values();
                    return [
                        'id'            => $p->id,
                        'name'          => $p->name,
                        'slug'          => $p->slug,
                        'category'      => $p->category->name,
                        'material_type' => $p->material_type,
                        'price'         => $p->price,
                        'price_formatted' => $p->formatted_price,
                        'is_featured'   => $p->is_featured,
                        'image_url'     => $first?->image_url,
                        'colors'        => $colors->map(fn($v) => [
                            'name'      => $v->color_name,
                            'hex'       => $v->color_hex,
                            'image_url' => $v->image_url,
                        ])->values(),
                    ];
                }),
                'count' => $products->count(),
            ]);
        }

        // Normal page load
        $products    = $query->paginate(12)->withQueryString();
        $categories  = Category::all();
        $materials   = Product::select('material_type')->distinct()->orderBy('material_type')->pluck('material_type');
        $sizes       = ProductVariant::select('size')->distinct()->get()
                        ->sortBy(fn($v) => is_numeric($v->size) ? (int)$v->size : 999)
                        ->pluck('size')->unique()->values();

        return view('products.index', compact('products', 'categories', 'materials', 'sizes'));
    }

    public function show(string $slug)
    {
        $product = Product::with(['variants', 'category'])
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedProducts = Product::with('variants')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    // Quick search API for navbar search bar
    public function search(Request $request)
    {
        $term = $request->get('q', '');
        if (strlen($term) < 2) return response()->json(['results' => []]);

        $products = Product::with(['variants'])
            ->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('material_type', 'like', "%{$term}%");
            })
            ->limit(6)
            ->get();

        return response()->json([
            'results' => $products->map(fn($p) => [
                'name'            => $p->name,
                'slug'            => $p->slug,
                'price_formatted' => $p->formatted_price,
                'material_type'   => $p->material_type,
                'image_url'       => $p->variants->first()?->image_url,
                'url'             => route('products.show', $p->slug),
            ]),
        ]);
    }
}
