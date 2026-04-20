<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Rise Bowl</title>

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

        @include('partials.navbar', ['isHome' => true, 'active' => null, 'showOrderNow' => true])

        <main class="relative pt-24" id="home">
            <section class="mx-auto max-w-6xl px-6 pb-16 pt-8 md:pt-12">
                <div class="grid items-center gap-10 md:grid-cols-2">
                    <div>
                        <h1 class="text-4xl font-extrabold leading-tight tracking-tight text-slate-900 md:text-5xl">
                            Where Fresh
                            <span class="text-orange-600">Meets</span>
                            Bowl
                        </h1>
                        <p class="mt-4 max-w-prose text-base leading-relaxed text-slate-600">
                            Experience a clean, filling bowl built for busy days — protein, grains, and veggies in one
                            fast delivery.
                        </p>
                        <div class="mt-7 flex flex-wrap items-center gap-4">
                            <a href="{{ url('/menu') }}"
                                class="inline-flex items-center justify-center rounded-full bg-orange-600 px-6 py-3 text-sm font-semibold text-white hover:bg-orange-700">
                                Order Now
                            </a>
                            <a href="#about"
                                class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700 hover:text-slate-900">
                                Learn More
                            </a>
                        </div>
                    </div>

                    <div class="relative mx-auto w-full max-w-md">
                        <div class="aspect-square w-full rounded-full bg-orange-50 ring-1 ring-orange-100"></div>
                        <div class="absolute inset-0 flex items-center justify-center p-6">
                            <img src="{{ asset('images/chicken-teriyaki.png') }}" alt="Chicken Teriyaki Bowl"
                                class="h-full w-full rounded-full object-cover ring-1 ring-slate-200"
                                fetchpriority="high" />
                        </div>
                    </div>
                </div>
            </section>

            <section id="menu" class="mx-auto max-w-6xl px-6 pb-20">
                <div class="text-center">
                    <div class="text-xs font-semibold tracking-[0.25em] text-slate-500">CHECK OUT</div>
                    <h2 class="mt-2 text-3xl font-extrabold tracking-tight">
                        Our <span class="text-orange-600">Best Sellers</span>
                    </h2>
                </div>

                <div class="mt-10 grid gap-6 md:grid-cols-3">
                    @if (isset($bestSellers) && $bestSellers->count())
                        @foreach ($bestSellers as $product)
                            <article class="rounded-2xl bg-slate-50 p-6 ring-1 ring-slate-200">
                                <div
                                    class="aspect-square w-full overflow-hidden rounded-xl bg-white ring-1 ring-slate-200">
                                    @if ($product->image_path)
                                        <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}"
                                            class="h-full w-full object-cover" loading="lazy" />
                                    @endif
                                </div>
                                <h3 class="mt-5 text-lg font-bold text-slate-900">{{ $product->name }}</h3>
                                @if ($product->description)
                                    <p class="mt-2 text-sm leading-relaxed text-slate-600">
                                        {{ $product->description }}
                                    </p>
                                @endif
                                <button type="button"
                                    class="mt-6 w-full rounded-full bg-orange-600 px-5 py-3 text-sm font-semibold text-white hover:bg-orange-700">
                                    Add to cart Rp {{ number_format($product->price, 0, ',', '.') }}
                                </button>
                            </article>
                        @endforeach
                    @else
                        <article class="rounded-2xl bg-slate-50 p-6 ring-1 ring-slate-200">
                            <img src="{{ asset('images/chicken-teriyaki.png') }}" alt="Chicken Teriyaki Bowl"
                                class="aspect-square w-full rounded-xl object-cover ring-1 ring-slate-200"
                                loading="lazy" />
                            <h3 class="mt-5 text-lg font-bold text-slate-900">Chicken Teriyaki Bowl</h3>
                            <p class="mt-2 text-sm leading-relaxed text-slate-600">
                                Grilled chicken, teriyaki glaze, crunchy greens, and warm rice.
                            </p>
                            <a href="{{ url('/menu') }}"
                                class="mt-6 inline-flex w-full items-center justify-center rounded-full bg-orange-600 px-5 py-3 text-sm font-semibold text-white hover:bg-orange-700">
                                Order from Menu
                            </a>
                        </article>

                        <article class="rounded-2xl bg-slate-50 p-6 ring-1 ring-slate-200">
                            <img src="{{ asset('images/spicy-tuna.png') }}" alt="Spicy Tuna Poke Bowl"
                                class="aspect-square w-full rounded-xl object-cover ring-1 ring-slate-200"
                                loading="lazy" />
                            <h3 class="mt-5 text-lg font-bold text-slate-900">Spicy Tuna Poke Bowl</h3>
                            <p class="mt-2 text-sm leading-relaxed text-slate-600">
                                Fresh tuna, spicy mayo, cucumber, edamame, and sesame.
                            </p>
                            <a href="{{ url('/menu') }}"
                                class="mt-6 inline-flex w-full items-center justify-center rounded-full bg-orange-600 px-5 py-3 text-sm font-semibold text-white hover:bg-orange-700">
                                Order from Menu
                            </a>
                        </article>

                        <article class="rounded-2xl bg-slate-50 p-6 ring-1 ring-slate-200">
                            <img src="{{ asset('images/veggie-crunch.png') }}" alt="Veggie Crunch Bowl"
                                class="aspect-square w-full rounded-xl object-cover ring-1 ring-slate-200"
                                loading="lazy" />
                            <h3 class="mt-5 text-lg font-bold text-slate-900">Veggie Crunch Bowl</h3>
                            <p class="mt-2 text-sm leading-relaxed text-slate-600">
                                Colorful veggies, roasted corn, beans, and citrus dressing.
                            </p>
                            <a href="{{ url('/menu') }}"
                                class="mt-6 inline-flex w-full items-center justify-center rounded-full bg-orange-600 px-5 py-3 text-sm font-semibold text-white hover:bg-orange-700">
                                Order from Menu
                            </a>
                        </article>
                    @endif
                </div>
            </section>

            <section id="about" class="mx-auto max-w-3xl scroll-mt-24 px-6 pb-20 text-center">
                <div class="text-xs font-semibold tracking-[0.25em] text-slate-500">OUR STORY</div>
                <h2 class="mt-2 text-4xl font-extrabold italic tracking-tight text-orange-600">About us</h2>
                <div class="mt-6 space-y-5 text-sm leading-relaxed text-slate-600">
                    <p>
                        Rise Bowl was built for people who want food that feels light but keeps you full. We keep the
                        menu simple: balanced bowls, consistent portions, and flavors that work every day.
                    </p>
                    <p>
                        From warm grains to fresh toppings, each bowl is assembled to travel well — so what arrives at
                        your door still tastes like it was just made.
                    </p>
                    <p>
                        Choose your favorite best-seller and we’ll handle the rest.
                    </p>
                </div>
            </section>

            <section id="contact" class="mx-auto max-w-3xl scroll-mt-24 px-6 pb-16 text-center">
                <div class="text-xs font-semibold tracking-[0.25em] text-slate-500">DON'T HESITATE</div>
                <h2 class="mt-2 text-4xl font-extrabold italic tracking-tight text-orange-600">Contact</h2>
                <a href="tel:{{ config('services.risebowl.contact_phone_tel') }}"
                    class="mt-8 inline-block text-3xl font-semibold text-slate-700 underline decoration-slate-300 underline-offset-8 hover:text-slate-900">
                    {{ config('services.risebowl.contact_phone_display') }}
                </a>
            </section>
        </main>

        <footer class="border-t border-slate-200 py-8 text-center text-sm text-slate-500">
            © {{ date('Y') }} Rise Bowl. All rights reserved.
        </footer>
    </div>

</body>

</html>
