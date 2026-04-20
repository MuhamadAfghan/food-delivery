<header class="fixed inset-x-0 top-0 z-50 border-b border-slate-200/70 bg-white/70 backdrop-blur">
    @php
        $isHome = (bool) ($isHome ?? false);
        $active = $active ?? null;
        $showOrderNow = (bool) ($showOrderNow ?? false);

        $homeHref = $isHome ? '#home' : url('/');
        $aboutHref = $isHome ? '#about' : url('/') . '#about';
        $contactHref = $isHome ? '#contact' : url('/') . '#contact';

        $homeClass = $active === 'home' ? 'text-slate-900' : 'hover:text-slate-900';
        $menuClass = $active === 'menu' ? 'text-slate-900' : 'hover:text-slate-900';
        $aboutClass = $active === 'about' ? 'text-slate-900' : 'hover:text-slate-900';
        $contactClass = $active === 'contact' ? 'text-slate-900' : 'hover:text-slate-900';
        $adminClass = $active === 'admin' ? 'text-slate-900' : 'hover:text-slate-900';
    @endphp

    <div class="mx-auto max-w-6xl px-6">
        <div class="flex items-center justify-between py-6">
            <a href="{{ $homeHref }}" class="flex items-center gap-2 font-semibold tracking-tight">
                <span class="text-orange-600">RISE</span>
                <span class="text-slate-900">BOWL</span>
            </a>

            <nav class="hidden items-center gap-8 text-sm font-medium text-slate-600 md:flex">
                <a href="{{ $homeHref }}" class="{{ $homeClass }}">Home</a>
                <a href="{{ url('/menu') }}" class="{{ $menuClass }}">Menu</a>
                <a href="{{ $aboutHref }}" class="{{ $aboutClass }}">About</a>
                <a href="{{ $contactHref }}" class="{{ $contactClass }}">Contact</a>
                <a href="{{ url('/admin') }}" class="{{ $adminClass }}">Admin</a>
            </nav>

            <div class="flex items-center gap-4">
                @if ($showOrderNow)
                    <a href="{{ url('/menu') }}"
                        class="hidden rounded-full bg-orange-600 px-5 py-2 text-sm font-semibold text-white hover:bg-orange-700 md:inline-flex">
                        Order Now
                    </a>
                @endif

                <a href="{{ url('/cart') }}"
                    class="relative inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-semibold text-slate-700 ring-1 ring-slate-200 hover:bg-slate-50"
                    aria-label="Cart">
                    Cart
                    <span id="cartCount"
                        class="inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-orange-600 px-1 text-xs font-semibold text-white">0</span>
                </a>
            </div>
        </div>
    </div>
</header>
