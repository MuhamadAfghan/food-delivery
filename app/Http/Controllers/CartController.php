<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class CartController extends Controller
{
    public function show(Request $request): View
    {
        $products = collect();

        if (Schema::hasTable('products')) {
            $products = Product::query()
                ->where('is_active', true)
                ->orderByDesc('id')
                ->get()
                ->map(fn(Product $product) => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => (int) $product->price,
                    'image_url' => $product->image_path ? asset($product->image_path) : null,
                ])
                ->values();
        }

        $number = (string) config('services.risebowl.whatsapp_number');
        $waNumber = preg_replace('/\D+/', '', $number ?? '');

        return view('cart', [
            'products' => $products,
            'waNumber' => $waNumber,
        ]);
    }
}
