<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(): View
    {
        $products = collect();

        if (Schema::hasTable('products')) {
            $products = Product::query()
                ->where('is_active', true)
                ->orderByDesc('id')
                ->get();
        }

        return view('menu', [
            'products' => $products,
        ]);
    }
}
