<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Admin - Edit Product</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body class="bg-white font-sans text-slate-900 antialiased">
    <div class="relative min-h-screen overflow-hidden">
        <div aria-hidden="true" class="pointer-events-none absolute inset-0">
            <div class="absolute -left-24 -top-24 h-80 w-80 rounded-full bg-orange-100 blur-3xl"></div>
            <div class="absolute -bottom-24 -right-24 h-80 w-80 rounded-full bg-orange-100 blur-3xl"></div>
        </div>

        <header class="relative z-10 border-b border-slate-200/70 bg-white/70 backdrop-blur">
            <div class="mx-auto max-w-4xl px-6">
                <div class="flex items-center justify-between py-6">
                    <div class="flex items-center gap-2 font-semibold tracking-tight">
                        <span class="text-orange-600">RISE</span>
                        <span class="text-slate-900">BOWL</span>
                        <span
                            class="ml-2 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">ADMIN</span>
                    </div>

                    <a href="{{ route('admin.products.index') }}"
                        class="inline-flex items-center justify-center rounded-full px-5 py-2 text-sm font-semibold text-slate-700 ring-1 ring-slate-200 hover:bg-slate-50">
                        Back
                    </a>
                </div>
            </div>
        </header>

        <main class="relative z-10">
            <section class="mx-auto max-w-4xl px-6 py-10">
                <div class="text-xs font-semibold tracking-[0.25em] text-slate-500">CMS</div>
                <h1 class="mt-2 text-3xl font-extrabold tracking-tight">Edit Product</h1>

                <form class="mt-8 space-y-6 rounded-3xl bg-white/70 p-8 ring-1 ring-slate-200 backdrop-blur"
                    method="POST" action="{{ route('admin.products.update', $product) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Name</label>
                        <input name="name" required value="{{ old('name', $product->name) }}"
                            class="mt-2 w-full rounded-2xl bg-white px-4 py-3 text-sm text-slate-900 ring-1 ring-slate-200 focus:outline-none focus:ring-2 focus:ring-orange-600" />
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Price (Rupiah)</label>
                        <div class="relative mt-2">
                            <div
                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-sm font-semibold text-slate-500">
                                Rp
                            </div>
                            <input id="priceInput" name="price" type="text" inputmode="numeric" autocomplete="off"
                                required value="{{ old('price', $product->price) }}" placeholder="0"
                                class="w-full rounded-2xl bg-white py-3 pl-12 pr-4 text-sm text-slate-900 ring-1 ring-slate-200 focus:outline-none focus:ring-2 focus:ring-orange-600" />
                        </div>
                        @error('price')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Description</label>
                        <textarea name="description" rows="4"
                            class="mt-2 w-full rounded-2xl bg-white px-4 py-3 text-sm text-slate-900 ring-1 ring-slate-200 focus:outline-none focus:ring-2 focus:ring-orange-600">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Photo</label>
                        @if ($product->image_path)
                            <div class="mt-3 flex items-center gap-4">
                                <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}"
                                    class="h-16 w-16 rounded-2xl object-cover ring-1 ring-slate-200" />
                                <div class="text-xs text-slate-500">Current photo</div>
                            </div>
                        @endif
                        <input name="image" type="file" accept="image/*"
                            class="mt-3 w-full rounded-2xl bg-white px-4 py-3 text-sm text-slate-700 ring-1 ring-slate-200 file:mr-4 file:rounded-full file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-slate-700 hover:file:bg-slate-200" />
                        @error('image')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <label class="flex items-center gap-3 text-sm text-slate-700">
                        <input name="is_active" type="checkbox" value="1"
                            {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                            class="h-4 w-4 rounded border-slate-300 text-orange-600 focus:ring-orange-600" />
                        Active (tampil di menu)
                    </label>

                    <button
                        class="w-full rounded-full bg-orange-600 px-5 py-3 text-sm font-semibold text-white hover:bg-orange-700">
                        Update
                    </button>
                </form>
            </section>
        </main>

        <script>
            (function() {
                const input = document.getElementById('priceInput');
                if (!input) return;

                const format = (value) => {
                    const digits = String(value || '').replace(/\D+/g, '');
                    if (!digits) return '';
                    const n = Number(digits);
                    if (!Number.isFinite(n)) return '';
                    try {
                        return new Intl.NumberFormat('id-ID').format(n);
                    } catch (e) {
                        return digits.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                    }
                };

                const normalize = () => {
                    input.value = format(input.value);
                };

                input.addEventListener('input', normalize);
                normalize();
            })();
        </script>

        <footer class="relative z-10 border-t border-slate-200 py-8 text-center text-sm text-slate-500">
            © {{ date('Y') }} Rise Bowl. All rights reserved.
        </footer>
    </div>
</body>

</html>
