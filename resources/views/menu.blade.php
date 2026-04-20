<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Menu - Rise Bowl</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body class="bg-white font-sans text-slate-900 antialiased">
    <div class="relative overflow-hidden">
        <div aria-hidden="true" class="pointer-events-none absolute inset-0">
            <div class="absolute -left-24 -top-24 h-80 w-80 rounded-full bg-orange-100 blur-3xl"></div>
            <div class="absolute -bottom-24 -right-24 h-80 w-80 rounded-full bg-orange-100 blur-3xl"></div>
        </div>

        @include('partials.navbar', ['isHome' => false, 'active' => 'menu', 'showOrderNow' => false])

        <main class="relative pt-24">
            <section class="mx-auto max-w-6xl px-6 pb-14 pt-10">
                <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                    <div>
                        <div class="text-xs font-semibold tracking-[0.25em] text-slate-500">MENU</div>
                        <h1 class="mt-2 text-3xl font-extrabold tracking-tight md:text-4xl">
                            Pick your <span class="text-orange-600">Rise Bowl</span>
                        </h1>
                        <p class="mt-3 max-w-prose text-sm leading-relaxed text-slate-600">
                            Simple menu, fast delivery.
                        </p>
                    </div>
                    <a href="{{ url('/') }}"
                        class="inline-flex items-center justify-center rounded-full px-5 py-2 text-sm font-semibold text-slate-700 ring-1 ring-slate-200 hover:bg-slate-50">
                        Back to Home
                    </a>
                </div>

                <div class="mt-10 grid gap-6 md:grid-cols-3">
                    @forelse ($products as $product)
                        <article class="rounded-2xl bg-slate-50 p-6 ring-1 ring-slate-200">
                            <div class="aspect-square w-full overflow-hidden rounded-xl bg-white ring-1 ring-slate-200">
                                @if ($product->image_path)
                                    <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}"
                                        class="h-full w-full object-cover" loading="lazy" />
                                @endif
                            </div>
                            <div class="mt-5 flex items-start justify-between gap-4">
                                <h2 class="text-lg font-bold text-slate-900">{{ $product->name }}</h2>
                                <div class="text-sm font-semibold text-slate-900">Rp
                                    {{ number_format($product->price, 0, ',', '.') }}</div>
                            </div>
                            @if ($product->description)
                                <p class="mt-2 text-sm leading-relaxed text-slate-600">
                                    {{ $product->description }}
                                </p>
                            @endif
                            <button type="button" data-add-to-cart data-product-id="{{ $product->id }}"
                                class="mt-6 w-full rounded-full bg-orange-600 px-5 py-3 text-sm font-semibold text-white hover:bg-orange-700">
                                Add to cart
                            </button>
                        </article>
                    @empty
                        <div
                            class="rounded-3xl bg-white/70 p-10 text-center ring-1 ring-slate-200 backdrop-blur md:col-span-3">
                            <div class="text-sm text-slate-600">Menu belum ada produk.</div>
                        </div>
                    @endforelse
                </div>
            </section>
        </main>

        <footer class="border-t border-slate-200 py-8 text-center text-sm text-slate-500">
            © {{ date('Y') }} Rise Bowl. All rights reserved.
        </footer>
    </div>

    <div id="toast"
        class="pointer-events-none fixed bottom-6 left-1/2 z-[60] hidden -translate-x-1/2 rounded-full bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-lg"
        role="status" aria-live="polite"></div>

    <script>
        (function() {
            var CART_KEY = 'risebowl_cart';
            var toastTimer = null;

            function showToast(message) {
                var el = document.getElementById('toast');
                if (!el) return;
                el.textContent = message;
                el.classList.remove('hidden');
                if (toastTimer) window.clearTimeout(toastTimer);
                toastTimer = window.setTimeout(function() {
                    el.classList.add('hidden');
                }, 2000);
            }

            function readCart() {
                try {
                    var raw = localStorage.getItem(CART_KEY);
                    var parsed = raw ? JSON.parse(raw) : {};
                    return parsed;
                } catch (e) {
                    return {};
                }
            }

            function writeCart(cart) {
                localStorage.setItem(CART_KEY, JSON.stringify(cart));
            }

            function parseQty(entry) {
                if (entry == null) return 0;
                if (typeof entry === 'number') return entry;
                if (typeof entry === 'string') return parseInt(entry, 10);
                if (typeof entry === 'object') {
                    var q = entry.quantity ?? entry.qty ?? entry.count ?? 0;
                    return parseInt(q, 10);
                }
                return 0;
            }

            function normalizeCart(cart) {
                var normalized = {};

                if (Array.isArray(cart)) {
                    cart.forEach(function(row) {
                        if (!row || typeof row !== 'object') return;
                        var productId = row.product_id ?? row.productId ?? row.id;
                        if (productId == null) return;
                        var qty = parseQty(row.quantity ?? row.qty ?? row.count);
                        if (!isNaN(qty) && qty > 0) normalized[String(productId)] = qty;
                    });
                    return normalized;
                }

                if (cart && typeof cart === 'object' && cart.items && (Array.isArray(cart.items) || typeof cart
                        .items === 'object')) {
                    return normalizeCart(cart.items);
                }

                if (!cart || typeof cart !== 'object') return normalized;

                Object.keys(cart).forEach(function(productId) {
                    var qty = parseQty(cart[productId]);
                    if (!isNaN(qty) && qty > 0) normalized[String(productId)] = qty;
                });

                return normalized;
            }

            function countItems(cart) {
                var normalized = normalizeCart(cart);
                return Object.keys(normalized).length;
            }

            function refreshBadge() {
                var badge = document.getElementById('cartCount');
                if (!badge) return;
                badge.textContent = String(countItems(readCart()));
            }

            function addToCart(productId) {
                var cart = normalizeCart(readCart());
                var key = String(productId);
                var current = parseInt(cart[key] || 0, 10);
                if (isNaN(current) || current < 0) current = 0;
                cart[key] = current + 1;
                writeCart(cart);
                refreshBadge();

                showToast('Berhasil ditambahkan ke cart');
            }

            document.addEventListener('click', function(e) {
                var btn = e.target && e.target.closest ? e.target.closest('[data-add-to-cart]') : null;
                if (!btn) return;
                var productId = btn.getAttribute('data-product-id');
                if (!productId) return;
                addToCart(productId);
            });

            window.addEventListener('storage', function(ev) {
                if (ev && ev.key === CART_KEY) refreshBadge();
            });

            refreshBadge();
        })();
    </script>
</body>

</html>
