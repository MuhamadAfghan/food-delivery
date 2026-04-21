<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(Request $request): View
    {
        $products = collect();

        if (Schema::hasTable('products')) {
            $searchQuery = trim((string) $request->query('q', ''));
            $sort = (string) $request->query('sort', '');

            $query = Product::query()->where('is_active', true);

            if ($searchQuery !== '') {
                $query->where(function ($q) use ($searchQuery) {
                    $q->where('name', 'like', "%{$searchQuery}%")
                        ->orWhere('description', 'like', "%{$searchQuery}%");
                });
            }

            if ($sort === 'price_asc') {
                $query->orderBy('price', 'asc');
            } elseif ($sort === 'price_desc') {
                $query->orderBy('price', 'desc');
            } else {
                $query->orderByDesc('id');
            }

            $products = $query->get();
        }

        return view('menu', [
            'products' => $products,
        ]);
    }
}
