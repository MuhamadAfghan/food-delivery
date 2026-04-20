<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Cart - Rise Bowl</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

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

        @include('partials.navbar', ['isHome' => false, 'active' => null, 'showOrderNow' => false])

        <main class="relative pt-24">
            <section class="mx-auto max-w-6xl px-6 pb-14 pt-10">
                <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                    <div>
                        <div class="text-xs font-semibold tracking-[0.25em] text-slate-500">CART</div>
                        <h1 class="mt-2 text-3xl font-extrabold tracking-tight md:text-4xl">
                            Your <span class="text-orange-600">Order</span>
                        </h1>
                        <p class="mt-3 max-w-prose text-sm leading-relaxed text-slate-600">
                            Checkout akan redirect ke WhatsApp.
                        </p>
                    </div>
                    <a href="{{ url('/menu') }}"
                        class="inline-flex items-center justify-center rounded-full px-5 py-2 text-sm font-semibold text-slate-700 ring-1 ring-slate-200 hover:bg-slate-50">
                        Back to Menu
                    </a>
                </div>

                <div id="waConfigWarning"
                    class="mt-6 hidden rounded-2xl bg-red-50 p-4 text-sm text-red-700 ring-1 ring-red-200">
                    RISEBOWL_WHATSAPP_NUMBER belum di-set di .env
                </div>

                <div id="cartEmptyState"
                    class="mt-10 rounded-3xl bg-white/70 p-10 text-center ring-1 ring-slate-200 backdrop-blur">
                    <div class="text-sm text-slate-600">Cart masih kosong.</div>
                    <a href="{{ url('/menu') }}"
                        class="mt-6 inline-flex items-center justify-center rounded-full bg-orange-600 px-6 py-3 text-sm font-semibold text-white hover:bg-orange-700">
                        Browse Menu
                    </a>
                </div>

                <div id="cartFilled" class="mt-10 hidden gap-6 lg:grid-cols-3">
                    <div class="space-y-4 lg:col-span-2" id="cartItems"></div>

                    <div class="rounded-3xl bg-white/70 p-6 ring-1 ring-slate-200 backdrop-blur">
                        <div class="text-sm font-semibold text-slate-900">Summary</div>
                        <div class="mt-3 flex items-center justify-between text-sm">
                            <div class="text-slate-600">Total</div>
                            <div class="font-semibold text-slate-900" id="cartTotal">Rp 0</div>
                        </div>

                        <form id="checkoutForm" class="mt-6 space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700">Nama</label>
                                <input name="name" required
                                    class="mt-2 w-full rounded-2xl bg-white px-4 py-3 text-sm text-slate-900 ring-1 ring-slate-200 focus:outline-none focus:ring-2 focus:ring-orange-600" />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700">Alamat</label>
                                <textarea name="address" rows="3" required
                                    class="mt-2 w-full rounded-2xl bg-white px-4 py-3 text-sm text-slate-900 ring-1 ring-slate-200 focus:outline-none focus:ring-2 focus:ring-orange-600"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700">Catatan (opsional)</label>
                                <textarea name="note" rows="3"
                                    class="mt-2 w-full rounded-2xl bg-white px-4 py-3 text-sm text-slate-900 ring-1 ring-slate-200 focus:outline-none focus:ring-2 focus:ring-orange-600"></textarea>
                            </div>

                            <button id="checkoutBtn" type="submit"
                                class="w-full rounded-full bg-orange-600 px-6 py-3 text-sm font-semibold text-white hover:bg-orange-700">
                                Checkout via WhatsApp
                            </button>

                            <p class="text-xs text-slate-500">
                                Set nomor WA di <span class="font-semibold">.env</span> dengan key
                                <span class="font-semibold">RISEBOWL_WHATSAPP_NUMBER</span>.
                            </p>
                        </form>
                    </div>
                </div>
            </section>
        </main>

        <footer class="border-t border-slate-200 py-8 text-center text-sm text-slate-500">
            © {{ date('Y') }} Rise Bowl. All rights reserved.
        </footer>
    </div>

    <script>
        (function() {
            var CART_KEY = 'risebowl_cart';
            var PRODUCTS = Array.isArray(@json($products ?? [])) ? @json($products ?? []) : [];
            var WA_NUMBER = String(@json($waNumber ?? ''));

            var productMap = {};
            PRODUCTS.forEach(function(p) {
                if (p && p.id != null) productMap[String(p.id)] = p;
            });

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

            function formatRupiah(amount) {
                try {
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount || 0);
                } catch (e) {
                    return 'Rp ' + String(amount || 0);
                }
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

            function setQty(productId, qty) {
                var cart = normalizeCart(readCart());
                var key = String(productId);
                qty = parseInt(qty, 10);
                if (isNaN(qty) || qty <= 0) {
                    delete cart[key];
                } else {
                    cart[key] = Math.max(1, qty);
                }
                writeCart(cart);
                render();
            }

            function removeItem(productId) {
                setQty(productId, 0);
            }

            function render() {
                var cart = normalizeCart(readCart());
                writeCart(cart);

                var emptyState = document.getElementById('cartEmptyState');
                var filled = document.getElementById('cartFilled');
                var itemsEl = document.getElementById('cartItems');
                var totalEl = document.getElementById('cartTotal');

                var ids = Object.keys(cart);
                if (ids.length === 0) {
                    if (emptyState) emptyState.classList.remove('hidden');
                    if (filled) {
                        filled.classList.add('hidden');
                        filled.classList.remove('grid');
                    }
                    if (itemsEl) itemsEl.innerHTML = '';
                    if (totalEl) totalEl.textContent = formatRupiah(0);
                    refreshBadge();
                    return;
                }

                if (emptyState) emptyState.classList.add('hidden');
                if (filled) {
                    filled.classList.remove('hidden');
                    filled.classList.add('grid');
                }

                var html = '';
                var total = 0;

                ids.forEach(function(productId) {
                    var qty = parseInt(cart[productId], 10);
                    if (isNaN(qty) || qty <= 0) return;

                    var p = productMap[String(productId)];
                    if (!p) return;

                    var price = parseInt(p.price || 0, 10);
                    if (isNaN(price) || price < 0) price = 0;

                    var line = price * qty;
                    total += line;

                    html += (
                        '<div class="flex gap-4 p-6 rounded-3xl bg-white/70 ring-1 ring-slate-200 backdrop-blur">' +
                        '  <div class="w-24 h-24 overflow-hidden rounded-2xl bg-slate-100 ring-1 ring-slate-200">' +
                        (p.image_url ? ('    <img src="' + p.image_url + '" alt="' + (p.name || '') +
                            '" class="object-cover w-full h-full" />') : '') +
                        '  </div>' +
                        '  <div class="flex-1">' +
                        '    <div class="flex items-start justify-between gap-4">' +
                        '      <div>' +
                        '        <div class="text-sm font-semibold text-slate-900">' + (p.name || '') +
                        '</div>' +
                        '        <div class="mt-1 text-xs text-slate-500">' + formatRupiah(price) +
                        '</div>' +
                        '      </div>' +
                        '      <button type="button" data-remove-item data-product-id="' + productId +
                        '" class="text-sm font-semibold text-red-700 hover:underline">Remove</button>' +
                        '    </div>' +
                        '    <div class="flex flex-wrap items-center gap-4 mt-4">' +
                        '      <label class="text-sm text-slate-600">Qty</label>' +
                        '      <input type="number" min="1" data-qty-input data-product-id="' + productId +
                        '" value="' + qty +
                        '" class="w-24 px-4 py-2 text-sm bg-white rounded-2xl text-slate-900 ring-1 ring-slate-200 focus:outline-none focus:ring-2 focus:ring-orange-600" />' +
                        '      <div class="text-sm font-semibold text-slate-900">Line: ' + formatRupiah(
                            line) + '</div>' +
                        '    </div>' +
                        '  </div>' +
                        '</div>'
                    );
                });

                if (itemsEl) itemsEl.innerHTML = html;
                if (totalEl) totalEl.textContent = formatRupiah(total);

                refreshBadge();
            }

            function buildWhatsAppMessage(fields) {
                var cart = normalizeCart(readCart());
                var ids = Object.keys(cart);

                var lines = [];
                var total = 0;
                ids.forEach(function(productId) {
                    var qty = parseInt(cart[productId], 10);
                    if (isNaN(qty) || qty <= 0) return;
                    var p = productMap[String(productId)];
                    if (!p) return;
                    var price = parseInt(p.price || 0, 10);
                    if (isNaN(price) || price < 0) price = 0;

                    var lineTotal = price * qty;
                    total += lineTotal;
                    lines.push('- ' + (p.name || '') + ' x' + qty + ' (' + formatRupiah(lineTotal) + ')');
                });

                if (lines.length === 0) return null;

                var msg = 'Halo Rise Bowl! Saya mau pesan:\n\n';
                msg += lines.join('\n');
                msg += '\n\nTotal: ' + formatRupiah(total);
                msg += '\n\nNama: ' + fields.name;
                msg += '\nAlamat: ' + fields.address;
                if (fields.note) msg += '\nCatatan: ' + fields.note;

                return msg;
            }

            function init() {
                var warning = document.getElementById('waConfigWarning');
                if (!WA_NUMBER) {
                    if (warning) warning.classList.remove('hidden');
                    var btn = document.getElementById('checkoutBtn');
                    if (btn) btn.disabled = true;
                }

                document.addEventListener('click', function(e) {
                    var removeBtn = e.target && e.target.closest ? e.target.closest('[data-remove-item]') :
                        null;
                    if (!removeBtn) return;
                    var productId = removeBtn.getAttribute('data-product-id');
                    if (!productId) return;
                    removeItem(productId);
                });

                document.addEventListener('input', function(e) {
                    var input = e.target && e.target.closest ? e.target.closest('[data-qty-input]') : null;
                    if (!input) return;
                    var productId = input.getAttribute('data-product-id');
                    if (!productId) return;
                    setQty(productId, input.value);
                });

                var checkoutForm = document.getElementById('checkoutForm');
                if (checkoutForm) {
                    checkoutForm.addEventListener('submit', function(e) {
                        e.preventDefault();

                        var cart = normalizeCart(readCart());
                        if (Object.keys(cart).length === 0) {
                            render();
                            return;
                        }

                        if (!WA_NUMBER) {
                            var warningEl = document.getElementById('waConfigWarning');
                            if (warningEl) warningEl.classList.remove('hidden');
                            return;
                        }

                        var fd = new FormData(checkoutForm);
                        var name = String(fd.get('name') || '').trim();
                        var address = String(fd.get('address') || '').trim();
                        var note = String(fd.get('note') || '').trim();

                        if (!name || !address) return;

                        var message = buildWhatsAppMessage({
                            name: name,
                            address: address,
                            note: note
                        });

                        if (!message) return;

                        localStorage.removeItem(CART_KEY);
                        window.location.href = 'https://wa.me/' + WA_NUMBER + '?text=' + encodeURIComponent(
                            message);
                    });
                }

                window.addEventListener('storage', function(ev) {
                    if (ev && ev.key === CART_KEY) render();
                });

                render();
            }

            init();
        })();
    </script>
</body>

</html>
