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
                            <button type="button"
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

</body>

</html>
