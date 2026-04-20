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

@php
    $cartCount = collect(session('cart', []))->sum();
@endphp

<body class="bg-white font-sans text-slate-900 antialiased">
    <div class="relative overflow-hidden">
        <div aria-hidden="true" class="pointer-events-none absolute inset-0">
            <div class="absolute -left-24 -top-24 h-80 w-80 rounded-full bg-orange-100 blur-3xl"></div>
            <div class="absolute -bottom-24 -right-24 h-80 w-80 rounded-full bg-orange-100 blur-3xl"></div>
        </div>

        <header class="fixed inset-x-0 top-0 z-50 border-b border-slate-200/70 bg-white/70 backdrop-blur">
            <div class="mx-auto max-w-6xl px-6">
                <div class="flex items-center justify-between py-6">
                    <a href="{{ url('/') }}" class="flex items-center gap-2 font-semibold tracking-tight">
                        <span class="text-orange-600">RISE</span>
                        <span class="text-slate-900">BOWL</span>
                    </a>

                    <nav class="hidden items-center gap-8 text-sm font-medium text-slate-600 md:flex">
                        <a href="{{ url('/') }}" class="hover:text-slate-900">Home</a>
                        <a href="{{ url('/menu') }}" class="hover:text-slate-900">Menu</a>
                        <a href="{{ url('/') }}#about" class="hover:text-slate-900">About</a>
                        <a href="{{ url('/') }}#contact" class="hover:text-slate-900">Contact</a>
                        <a href="{{ url('/admin') }}" class="hover:text-slate-900">Admin</a>
                    </nav>

                    <a href="{{ url('/cart') }}"
                        class="relative inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-semibold text-slate-700 ring-1 ring-slate-200 hover:bg-slate-50"
                        aria-label="Cart">
                        Cart
                        <span
                            class="inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-orange-600 px-1 text-xs font-semibold text-white">{{ $cartCount }}</span>
                    </a>
                </div>
            </div>
        </header>

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

                @if ($errors->any())
                    <div class="mt-6 rounded-2xl bg-red-50 p-4 text-sm text-red-700 ring-1 ring-red-200">
                        <ul class="list-disc space-y-1 pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (count($items) === 0)
                    <div class="mt-10 rounded-3xl bg-white/70 p-10 text-center ring-1 ring-slate-200 backdrop-blur">
                        <div class="text-sm text-slate-600">Cart masih kosong.</div>
                        <a href="{{ url('/menu') }}"
                            class="mt-6 inline-flex items-center justify-center rounded-full bg-orange-600 px-6 py-3 text-sm font-semibold text-white hover:bg-orange-700">
                            Browse Menu
                        </a>
                    </div>
                @else
                    <div class="mt-10 grid gap-6 lg:grid-cols-3">
                        <div class="space-y-4 lg:col-span-2">
                            <form method="POST" action="{{ url('/cart/update') }}" class="space-y-4">
                                @csrf

                                @foreach ($items as $row)
                                    @php($product = $row['product'])
                                    <div
                                        class="flex gap-4 rounded-3xl bg-white/70 p-6 ring-1 ring-slate-200 backdrop-blur">
                                        <div
                                            class="h-24 w-24 overflow-hidden rounded-2xl bg-slate-100 ring-1 ring-slate-200">
                                            @if ($product->image_path)
                                                <img src="{{ asset($product->image_path) }}"
                                                    alt="{{ $product->name }}" class="h-full w-full object-cover" />
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-start justify-between gap-4">
                                                <div>
                                                    <div class="text-sm font-semibold text-slate-900">
                                                        {{ $product->name }}</div>
                                                    <div class="mt-1 text-xs text-slate-500">Rp
                                                        {{ number_format($product->price, 0, ',', '.') }}</div>
                                                </div>
                                                <button type="submit" form="remove-{{ $product->id }}"
                                                    class="text-sm font-semibold text-red-700 hover:underline">
                                                    Remove
                                                </button>
                                            </div>

                                            <div class="mt-4 flex flex-wrap items-center gap-4">
                                                <label class="text-sm text-slate-600">Qty</label>
                                                <input type="number" min="0"
                                                    name="quantities[{{ $product->id }}]"
                                                    value="{{ $row['quantity'] }}"
                                                    class="w-24 rounded-2xl bg-white px-4 py-2 text-sm text-slate-900 ring-1 ring-slate-200 focus:outline-none focus:ring-2 focus:ring-orange-600" />
                                                <div class="text-sm font-semibold text-slate-900">Line: Rp
                                                    {{ number_format($row['line_total'], 0, ',', '.') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                {{-- <button
                                    class="w-full px-6 py-3 text-sm font-semibold rounded-full text-slate-700 ring-1 ring-slate-200 hover:bg-slate-50">
                                    Update Cart
                                </button> --}}
                            </form>

                            @foreach ($items as $row)
                                @php($product = $row['product'])
                                <form id="remove-{{ $product->id }}" method="POST"
                                    action="{{ url('/cart/remove/' . $product->id) }}" class="hidden">
                                    @csrf
                                </form>
                            @endforeach
                        </div>

                        <div class="rounded-3xl bg-white/70 p-6 ring-1 ring-slate-200 backdrop-blur">
                            <div class="text-sm font-semibold text-slate-900">Summary</div>
                            <div class="mt-3 flex items-center justify-between text-sm">
                                <div class="text-slate-600">Total</div>
                                <div class="font-semibold text-slate-900">Rp {{ number_format($total, 0, ',', '.') }}
                                </div>
                            </div>

                            <div class="mt-6 space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700">Nama</label>
                                    <input name="name" form="checkoutForm" required value="{{ old('name') }}"
                                        class="mt-2 w-full rounded-2xl bg-white px-4 py-3 text-sm text-slate-900 ring-1 ring-slate-200 focus:outline-none focus:ring-2 focus:ring-orange-600" />
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700">Alamat</label>
                                    <textarea name="address" form="checkoutForm" rows="3" required
                                        class="mt-2 w-full rounded-2xl bg-white px-4 py-3 text-sm text-slate-900 ring-1 ring-slate-200 focus:outline-none focus:ring-2 focus:ring-orange-600">{{ old('address') }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700">Catatan (opsional)</label>
                                    <textarea name="note" form="checkoutForm" rows="3"
                                        class="mt-2 w-full rounded-2xl bg-white px-4 py-3 text-sm text-slate-900 ring-1 ring-slate-200 focus:outline-none focus:ring-2 focus:ring-orange-600">{{ old('note') }}</textarea>
                                </div>

                                <form id="checkoutForm" method="POST" action="{{ url('/checkout') }}">
                                    @csrf
                                    <button
                                        class="w-full rounded-full bg-orange-600 px-6 py-3 text-sm font-semibold text-white hover:bg-orange-700">
                                        Checkout via WhatsApp
                                    </button>
                                </form>

                                <p class="text-xs text-slate-500">
                                    Set nomor WA di <span class="font-semibold">.env</span> dengan key
                                    <span class="font-semibold">RISEBOWL_WHATSAPP_NUMBER</span>.
                                </p>
                            </div>
                        </div>
                    </div>
    </div>
    @endif
    </section>
    </main>

    <footer class="border-t border-slate-200 py-8 text-center text-sm text-slate-500">
        © {{ date('Y') }} Rise Bowl. All rights reserved.
    </footer>
    </div>
</body>

</html>
