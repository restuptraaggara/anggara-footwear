<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Categories
        $men = Category::create(['name' => 'Men', 'slug' => 'men', 'description' => 'Footwear for men']);
        $women = Category::create(['name' => 'Women', 'slug' => 'women', 'description' => 'Footwear for women']);
        $acc = Category::create(['name' => 'Accessories', 'slug' => 'accessories', 'description' => 'Sustainable accessories']);

        // Products data
        $products = [
            [
                'category' => $men,
                'name' => 'Tree Runner',
                'slug' => 'tree-runner',
                'description' => 'Our lightest, breeziest shoe yet. Made from a blend of eucalyptus tree fiber and a touch of recycled nylon, the Tree Runner keeps feet cool and comfortable all day.',
                'material_type' => 'Eucalyptus Tree Fiber',
                'price' => 1650000,
                'is_featured' => true,
                'variants' => [
                    ['color_name' => 'Natural White', 'color_hex' => '#F5F0E8', 'sizes' => ['39','40','41','42','43','44'],
                     'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&q=80'],
                    ['color_name' => 'Charcoal', 'color_hex' => '#3D3D3D', 'sizes' => ['39','40','41','42','43','44'],
                     'image' => 'https://images.unsplash.com/photo-1608231387042-66d1773070a5?w=800&q=80'],
                    ['color_name' => 'Sage Green', 'color_hex' => '#8FAF8A', 'sizes' => ['39','40','41','42','43','44'],
                     'image' => 'https://images.unsplash.com/photo-1539185441755-769473a23570?w=800&q=80'],
                ],
            ],
            [
                'category' => $men,
                'name' => 'Wool Runner',
                'slug' => 'wool-runner',
                'description' => 'Crafted from ZQ Merino Wool, this all-weather shoe regulates temperature and wicks moisture. Versatile, comfortable, and naturally odor-resistant.',
                'material_type' => 'ZQ Merino Wool',
                'price' => 1850000,
                'is_featured' => true,
                'variants' => [
                    ['color_name' => 'Moonrise Grey', 'color_hex' => '#B0A9A0', 'sizes' => ['39','40','41','42','43','44','45'],
                     'image' => 'https://images.unsplash.com/photo-1600269452121-4f2416e55c28?w=800&q=80'],
                    ['color_name' => 'Navy Blue', 'color_hex' => '#2C3E6B', 'sizes' => ['39','40','41','42','43','44','45'],
                     'image' => 'https://images.unsplash.com/photo-1520256862855-398228c41684?w=800&q=80'],
                ],
            ],
            [
                'category' => $men,
                'name' => 'Tree Dasher',
                'slug' => 'tree-dasher',
                'description' => 'Built for performance, made from eucalyptus fiber. The Tree Dasher delivers superior cushioning and breathability for runners who care about the planet.',
                'material_type' => 'Eucalyptus Tree Fiber + SweetFoam®',
                'price' => 1950000,
                'is_featured' => true,
                'variants' => [
                    ['color_name' => 'Blizzard', 'color_hex' => '#ECEAE4', 'sizes' => ['40','41','42','43','44'],
                     'image' => 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=800&q=80'],
                    ['color_name' => 'Tropic Teal', 'color_hex' => '#4A9E8A', 'sizes' => ['40','41','42','43','44'],
                     'image' => 'https://images.unsplash.com/photo-1460353581641-37baddab0fa2?w=800&q=80'],
                ],
            ],
            [
                'category' => $women,
                'name' => 'Tree Breezers',
                'slug' => 'tree-breezers',
                'description' => 'Slip on and go. Made from airy eucalyptus fiber, these lightweight sneakers are perfect for those warm-weather days when you need easy, breezy comfort.',
                'material_type' => 'Eucalyptus Tree Fiber',
                'price' => 1450000,
                'is_featured' => true,
                'variants' => [
                    ['color_name' => 'Rose Blush', 'color_hex' => '#E8C4B8', 'sizes' => ['36','37','38','39','40','41'],
                     'image' => 'https://images.unsplash.com/photo-1604671801908-6f0c6a092c05?w=800&q=80'],
                    ['color_name' => 'Cream', 'color_hex' => '#F0EBE3', 'sizes' => ['36','37','38','39','40','41'],
                     'image' => 'https://images.unsplash.com/photo-1582588678413-dbf45f4823e9?w=800&q=80'],
                    ['color_name' => 'Dusty Lavender', 'color_hex' => '#A89BC0', 'sizes' => ['36','37','38','39','40','41'],
                     'image' => 'https://images.unsplash.com/photo-1543163521-1bf539c55dd2?w=800&q=80'],
                ],
            ],
            [
                'category' => $women,
                'name' => 'Wool Runner Go',
                'slug' => 'wool-runner-go',
                'description' => 'All-terrain. All-season. Made with ZQ Merino Wool and a water-resistant finish, the Wool Runner Go takes you from trail to town without skipping a beat.',
                'material_type' => 'ZQ Merino Wool + Water Repellent',
                'price' => 2050000,
                'is_featured' => false,
                'variants' => [
                    ['color_name' => 'Pebble', 'color_hex' => '#C4B9A8', 'sizes' => ['36','37','38','39','40'],
                     'image' => 'https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?w=800&q=80'],
                    ['color_name' => 'Forest', 'color_hex' => '#4A6741', 'sizes' => ['36','37','38','39','40'],
                     'image' => 'https://images.unsplash.com/photo-1571945153237-4929e783af4a?w=800&q=80'],
                ],
            ],
            [
                'category' => $women,
                'name' => 'Mizzle Runner',
                'slug' => 'mizzle-runner',
                'description' => 'Drizzle? No problem. The Mizzle Runner features a bio-based water repellent treatment and a eucalyptus fiber upper that keeps you dry and comfortable.',
                'material_type' => 'Eucalyptus Fiber + Plant-based DWR',
                'price' => 1750000,
                'is_featured' => false,
                'variants' => [
                    ['color_name' => 'Storm Grey', 'color_hex' => '#7A8896', 'sizes' => ['36','37','38','39','40','41'],
                     'image' => 'https://images.unsplash.com/photo-1556906781-9a412961a28c?w=800&q=80'],
                    ['color_name' => 'Clay', 'color_hex' => '#C4956A', 'sizes' => ['36','37','38','39','40','41'],
                     'image' => 'https://images.unsplash.com/photo-1533681904393-9ab6eee7e408?w=800&q=80'],
                ],
            ],
            [
                'category' => $acc,
                'name' => 'Merino Wool Socks',
                'slug' => 'merino-wool-socks',
                'description' => 'Soft, temperature-regulating, and naturally odor-resistant. Made from 100% ZQ Merino Wool for all-day comfort.',
                'material_type' => 'ZQ Merino Wool',
                'price' => 225000,
                'is_featured' => false,
                'variants' => [
                    ['color_name' => 'Natural', 'color_hex' => '#F0EBE3', 'sizes' => ['S/M','M/L'],
                     'image' => 'https://images.unsplash.com/photo-1586350977771-b3b0abd50c82?w=800&q=80'],
                    ['color_name' => 'Charcoal', 'color_hex' => '#3D3D3D', 'sizes' => ['S/M','M/L'],
                     'image' => 'https://images.unsplash.com/photo-1434389677669-e08b4cac3105?w=800&q=80'],
                ],
            ],
            [
                'category' => $acc,
                'name' => 'Shoelace Pack',
                'slug' => 'shoelace-pack',
                'description' => 'Made from recycled plastic bottles. A set of 3 pairs in earthy tones to customize your look while reducing waste.',
                'material_type' => 'Recycled PET',
                'price' => 95000,
                'is_featured' => false,
                'variants' => [
                    ['color_name' => 'Earth Pack', 'color_hex' => '#C4956A', 'sizes' => ['One Size'],
                     'image' => 'https://images.unsplash.com/photo-1491553895911-0055eca6402d?w=800&q=80'],
                ],
            ],
        ];

        foreach ($products as $data) {
            $product = Product::create([
                'category_id' => $data['category']->id,
                'name' => $data['name'],
                'slug' => $data['slug'],
                'description' => $data['description'],
                'material_type' => $data['material_type'],
                'price' => $data['price'],
                'is_featured' => $data['is_featured'],
            ]);

            foreach ($data['variants'] as $variantData) {
                foreach ($variantData['sizes'] as $size) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'color_name' => $variantData['color_name'],
                        'color_hex' => $variantData['color_hex'],
                        'size' => $size,
                        'stock' => rand(5, 30),
                        'image_url' => $variantData['image'],
                    ]);
                }
            }
        }
    }
}
