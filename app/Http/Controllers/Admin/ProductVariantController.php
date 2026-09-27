<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'color_name' => 'required|string|max:100',
            'color_hex'  => 'required|string|max:7',
            'size'       => 'required|string|max:20',
            'stock'      => 'required|integer|min:0',
            'image_url'  => 'required|url',
        ]);

        $variant = $product->variants()->create($validated);

        return back()->with('success', "Varian warna '{$variant->color_name}' (Ukuran {$variant->size}) berhasil ditambahkan dengan stok {$variant->stock}.");
    }

    public function update(Request $request, ProductVariant $variant)
    {
        $validated = $request->validate([
            'color_name' => 'required|string|max:100',
            'color_hex'  => 'required|string|max:7',
            'size'       => 'required|string|max:20',
            'stock'      => 'required|integer|min:0',
            'image_url'  => 'required|url',
        ]);

        $variant->update($validated);

        return back()->with('success', "Varian #{$variant->id} ({$variant->color_name} - {$variant->size}) berhasil diperbarui.");
    }

    public function destroy(ProductVariant $variant)
    {
        $name = "{$variant->color_name} (Size {$variant->size})";
        $variant->delete();

        return back()->with('success', "Varian {$name} berhasil dihapus.");
    }
}
