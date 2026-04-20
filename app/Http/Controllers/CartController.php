<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class CartController extends Controller
{
    public function show(Request $request): View
    {
        $items = [];
        $total = 0;

        $cart = $this->getCart($request);

        if (Schema::hasTable('products') && count($cart) > 0) {
            $products = Product::query()->whereIn('id', array_keys($cart))->get()->keyBy('id');

            foreach ($cart as $productId => $qty) {
                $product = $products->get($productId);
                if (! $product) {
                    continue;
                }

                $qty = max(1, (int) $qty);
                $lineTotal = $product->price * $qty;
                $total += $lineTotal;

                $items[] = [
                    'product' => $product,
                    'quantity' => $qty,
                    'line_total' => $lineTotal,
                ];
            }
        }

        return view('cart', [
            'items' => $items,
            'total' => $total,
        ]);
    }

    public function add(Request $request, Product $product): RedirectResponse
    {
        $cart = $this->getCart($request);
        $cart[$product->id] = (int) ($cart[$product->id] ?? 0) + 1;

        $request->session()->put('cart', $cart);

        return back();
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'quantities' => ['required', 'array'],
            'quantities.*' => ['required', 'integer', 'min:0'],
        ]);

        $cart = [];
        foreach ($validated['quantities'] as $productId => $qty) {
            $qty = (int) $qty;
            if ($qty <= 0) {
                continue;
            }
            $cart[(int) $productId] = $qty;
        }

        $request->session()->put('cart', $cart);

        return back();
    }

    public function remove(Request $request, Product $product): RedirectResponse
    {
        $cart = $this->getCart($request);
        unset($cart[$product->id]);

        $request->session()->put('cart', $cart);

        return back();
    }

    public function checkout(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:1000'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $number = (string) config('services.risebowl.whatsapp_number');
        $number = preg_replace('/\D+/', '', $number ?? '');

        if ($number === '') {
            return back()->withErrors([
                'whatsapp' => 'RISEBOWL_WHATSAPP_NUMBER belum di-set di .env',
            ]);
        }

        $cart = $this->getCart($request);
        if (! Schema::hasTable('products') || count($cart) === 0) {
            return back()->withErrors([
                'cart' => 'Cart masih kosong.',
            ]);
        }

        $products = Product::query()->whereIn('id', array_keys($cart))->get()->keyBy('id');

        $lines = [];
        $total = 0;
        foreach ($cart as $productId => $qty) {
            $product = $products->get($productId);
            if (! $product) {
                continue;
            }

            $qty = max(1, (int) $qty);
            $lineTotal = $product->price * $qty;
            $total += $lineTotal;

            $lines[] = sprintf(
                '- %s x%d (Rp %s)',
                $product->name,
                $qty,
                number_format($lineTotal, 0, ',', '.')
            );
        }

        if (count($lines) === 0) {
            return back()->withErrors([
                'cart' => 'Produk di cart tidak valid.',
            ]);
        }

        $message = "Halo Rise Bowl! Saya mau pesan:\n\n";
        $message .= implode("\n", $lines);
        $message .= "\n\nTotal: Rp " . number_format($total, 0, ',', '.');
        $message .= "\n\nNama: " . $validated['name'];
        $message .= "\nAlamat: " . $validated['address'];
        if (! empty($validated['note'])) {
            $message .= "\nCatatan: " . $validated['note'];
        }

        $request->session()->forget('cart');

        $url = 'https://wa.me/' . $number . '?text=' . rawurlencode($message);

        return redirect()->away($url);
    }

    private function getCart(Request $request): array
    {
        $cart = $request->session()->get('cart', []);

        if (! is_array($cart)) {
            return [];
        }

        return $cart;
    }
}
