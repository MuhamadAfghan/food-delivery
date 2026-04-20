<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login - Rise Bowl</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles / Scripts -->
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

        <header class="relative z-10">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-6">
                <a href="{{ url('/') }}" class="flex items-center gap-2 font-semibold tracking-tight">
                    <span class="text-orange-600">RISE</span>
                    <span class="text-slate-900">BOWL</span>
                </a>

                <a href="{{ url('/') }}"
                    class="inline-flex items-center justify-center rounded-full px-5 py-2 text-sm font-semibold text-slate-700 ring-1 ring-slate-200 hover:bg-slate-50">
                    Back to Home
                </a>
            </div>
        </header>

        <main class="relative z-10">
            <div class="mx-auto max-w-6xl px-6 pb-16 pt-8">
                <div class="mx-auto max-w-md rounded-3xl bg-white/70 p-8 ring-1 ring-slate-200 backdrop-blur">
                    <div class="text-xs font-semibold tracking-[0.25em] text-slate-500">ADMIN</div>
                    <h1 class="mt-2 text-2xl font-extrabold tracking-tight">Login</h1>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600">
                        Masuk untuk akses admin.
                    </p>

                    <form class="mt-8 space-y-5" method="POST" action="{{ route('login.store') }}">
                        @csrf

                        <div>
                            <label for="email" class="block text-sm font-semibold text-slate-700">Email</label>
                            <input id="email" name="email" type="email" autocomplete="username" required
                                value="{{ old('email') }}"
                                class="mt-2 w-full rounded-2xl bg-white px-4 py-3 text-sm text-slate-900 ring-1 ring-slate-200 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-600" />
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
                            <input id="password" name="password" type="password" autocomplete="current-password"
                                required
                                class="mt-2 w-full rounded-2xl bg-white px-4 py-3 text-sm text-slate-900 ring-1 ring-slate-200 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-600" />
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <label class="flex items-center gap-3 text-sm text-slate-600">
                            <input name="remember" type="checkbox" value="1"
                                class="h-4 w-4 rounded border-slate-300 text-orange-600 focus:ring-orange-600" />
                            Remember me
                        </label>

                        <button
                            class="w-full rounded-full bg-orange-600 px-5 py-3 text-sm font-semibold text-white hover:bg-orange-700">
                            Login
                        </button>
                    </form>
                </div>
            </div>
        </main>

        <footer class="relative z-10 border-t border-slate-200 py-8 text-center text-sm text-slate-500">
            © {{ date('Y') }} Rise Bowl. All rights reserved.
        </footer>
    </div>
</body>

</html>
