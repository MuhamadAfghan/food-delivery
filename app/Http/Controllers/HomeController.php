<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $bestSellers = collect();

        if (Schema::hasTable('products')) {
            $bestSellers = Product::query()
                ->where('is_active', true)
                ->orderByDesc('id')
                ->limit(3)
                ->get();
        }

        return view('welcome', [
            'bestSellers' => $bestSellers,
        ]);
    }
}
