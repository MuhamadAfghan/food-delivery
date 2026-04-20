<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Admin - Products</title>

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

        <header class="relative z-10 border-b border-slate-200/70 bg-white/70 backdrop-blur">
            <div class="mx-auto max-w-6xl px-6">
                <div class="flex flex-wrap items-center justify-between gap-4 py-6">
                    <div class="flex items-center gap-2 font-semibold tracking-tight">
                        <span class="text-orange-600">RISE</span>
                        <span class="text-slate-900">BOWL</span>
                        <span
                            class="ml-2 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">ADMIN</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                class="inline-flex items-center justify-center rounded-full px-5 py-2 text-sm font-semibold text-slate-700 ring-1 ring-slate-200 hover:bg-slate-50">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="relative z-10">
            <section class="mx-auto max-w-6xl px-6 py-10">
                <div class="flex items-end justify-between gap-4">
                    <div>
                        <div class="text-xs font-semibold tracking-[0.25em] text-slate-500">CMS</div>
                        <h1 class="mt-2 text-3xl font-extrabold tracking-tight">Products</h1>
                        <p class="mt-2 text-sm text-slate-600">Kelola data produk yang tampil di menu.</p>
                    </div>
                    <div class="flex flex-wrap items-center justify-end gap-3">
                        <a href="{{ route('admin.products.create') }}"
                            class="inline-flex items-center justify-center rounded-full bg-orange-600 px-5 py-2 text-sm font-semibold text-white hover:bg-orange-700">
                            New Product
                        </a>
                    </div>
                </div>

                <div class="mt-8 overflow-hidden rounded-3xl bg-white/70 ring-1 ring-slate-200 backdrop-blur">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50/70">
                                <tr>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                        Product</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                        Price</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                        Status</th>
                                    <th class="px-6 py-4"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @forelse ($products as $product)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-4">
                                                <div
                                                    class="h-12 w-12 overflow-hidden rounded-xl bg-slate-100 ring-1 ring-slate-200">
                                                    @if ($product->image_path)
                                                        <img src="{{ asset($product->image_path) }}"
                                                            alt="{{ $product->name }}"
                                                            class="h-full w-full object-cover" />
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="text-sm font-semibold text-slate-900">
                                                        {{ $product->name }}</div>
                                                    @if ($product->description)
                                                        <div class="mt-1 line-clamp-1 max-w-md text-xs text-slate-500">
                                                            {{ $product->description }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-semibold text-slate-900">Rp
                                            {{ number_format($product->price, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4">
                                            @if ($product->is_active)
                                                <span
                                                    class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">Active</span>
                                            @else
                                                <span
                                                    class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">Hidden</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('admin.products.edit', $product) }}"
                                                    class="inline-flex items-center justify-center rounded-full px-4 py-2 text-sm font-semibold text-slate-700 ring-1 ring-slate-200 hover:bg-slate-50">
                                                    Edit
                                                </a>
                                                <form method="POST"
                                                    action="{{ route('admin.products.destroy', $product) }}"
                                                    onsubmit="return confirm('Delete this product?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                        class="inline-flex items-center justify-center rounded-full px-4 py-2 text-sm font-semibold text-red-700 ring-1 ring-red-200 hover:bg-red-50">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-10 text-center text-sm text-slate-600">
                                            No products yet. Create your first product.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </main>

        <footer class="relative z-10 border-t border-slate-200 py-8 text-center text-sm text-slate-500">
            © {{ date('Y') }} Rise Bowl. All rights reserved.
        </footer>
    </div>
</body>

</html>
