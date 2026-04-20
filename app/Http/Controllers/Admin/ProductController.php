<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::query()
            ->orderByDesc('id')
            ->get();

        return view('admin.products.index', [
            'products' => $products,
        ]);
    }

    public function create(): View
    {
        return view('admin.products.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'string', 'max:32'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);

        $priceDigits = preg_replace('/\D+/', '', (string) $validated['price']);
        if ($priceDigits === '') {
            throw ValidationException::withMessages([
                'price' => 'Harga tidak valid.',
            ]);
        }

        $price = (int) $priceDigits;

        $imagePath = null;
        if ($request->hasFile('image')) {
            $uploaded = $request->file('image');
            $filename = Str::uuid()->toString() . '.' . $uploaded->getClientOriginalExtension();
            $uploaded->move(public_path('images/products'), $filename);
            $imagePath = 'images/products/' . $filename;
        }

        Product::create([
            'name' => $validated['name'],
            'price' => $price,
            'description' => $validated['description'] ?? null,
            'image_path' => $imagePath,
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ]);

        return redirect()->route('admin.products.index');
    }

    public function edit(Product $product): View
    {
        return view('admin.products.edit', [
            'product' => $product,
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'string', 'max:32'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);

        $priceDigits = preg_replace('/\D+/', '', (string) $validated['price']);
        if ($priceDigits === '') {
            throw ValidationException::withMessages([
                'price' => 'Harga tidak valid.',
            ]);
        }

        $price = (int) $priceDigits;

        if ($request->hasFile('image')) {
            $uploaded = $request->file('image');
            $filename = Str::uuid()->toString() . '.' . $uploaded->getClientOriginalExtension();
            $uploaded->move(public_path('images/products'), $filename);
            $product->image_path = 'images/products/' . $filename;
        }

        $product->name = $validated['name'];
        $product->price = $price;
        $product->description = $validated['description'] ?? null;
        $product->is_active = (bool) ($validated['is_active'] ?? false);
        $product->save();

        return redirect()->route('admin.products.index');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('admin.products.index');
    }
}
