<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login · Stockflow</title>
    <script src="https://unpkg.com/lucide@latest"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 font-sans text-slate-800 antialiased">
    <main class="flex min-h-screen items-center justify-center px-4 py-10">
        <section
            class="w-full max-w-md overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl shadow-slate-300/40">
            <div class="border-b border-slate-100 px-7 py-8 text-center sm:px-10">
                <div
                    class="mx-auto grid h-12 w-12 place-items-center rounded-xl bg-gradient-to-br from-cyan-400 to-blue-600 text-white shadow-lg shadow-blue-500/25">
                    <i data-lucide="boxes" class="h-6 w-6"></i>
                </div>
                <h1 class="mt-4 text-2xl font-bold tracking-tight text-slate-900">Welcome back</h1>
                <p class="mt-2 text-sm text-slate-500">Sign in to your Dashboard account.</p>
            </div>
            @if (session('error'))
                <div class="mx-7 mt-5 rounded-xl bg-red-50 p-4 text-sm text-red-600 border border-red-100">
                    {{ session('error') }}
                </div>
            @endif
            <form action="{{ route('form.submit') }}" method="POST" class="space-y-5 px-7 py-7 sm:px-10">
                @csrf
                <div>
                    <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">Email address</label>
                    <div class="relative">
                        <i data-lucide="mail"
                            class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                        <input name="email" id="email" type="email" autocomplete="email" placeholder="you@company.com" value="{{ old('email') }}"
                            class="block w-full rounded-xl border border-slate-200 bg-white py-3 pl-10 pr-4 text-sm outline-none ring-4 ring-transparent transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-blue-500/10">
                    </div>
                    @error('email')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <div class="mb-2 flex items-center justify-between">
                        <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
                        <a href="#"
                            class="text-xs font-semibold text-blue-600 transition hover:text-blue-700">Forgot
                            password?</a>
                    </div>
                    <div class="relative">
                        <i data-lucide="lock-keyhole"
                            class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                        <input name="password" id="password" type="password" autocomplete="current-password"
                            placeholder="Enter your password"
                            class="block w-full rounded-xl border border-slate-200 bg-white py-3 pl-10 pr-12 text-sm outline-none ring-4 ring-transparent transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-blue-500/10">
                        
                        <button type="button" id="passwordToggle" aria-label="Show password"
                            class="absolute right-3 top-1/2 grid h-8 w-8 -translate-y-1/2 place-items-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-600">
                            <i data-lucide="eye" class="h-4 w-4"></i>
                        </button>
                    </div>
                    @error('password')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <label class="flex cursor-pointer items-center gap-2.5 text-sm text-slate-600">
                    <input type="checkbox" class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <span>Remember me</span>
                </label>

                <button type="submit"
                    class="flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-bold text-white shadow-lg shadow-blue-500/20 transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/20">
                    Sign in <i data-lucide="arrow-right" class="h-4 w-4"></i>
                </button>
            </form>

            <div class="border-t border-slate-100 bg-slate-50 px-7 py-4 text-center text-xs text-slate-500">
                Stockflow · Inventory control
            </div>
        </section>
    </main>
    <script>
        lucide.createIcons();
        const toggle = document.getElementById('passwordToggle');
        const password = document.getElementById('password');
        toggle?.addEventListener('click', () => {
            const isVisible = password.type === 'text';
            password.type = isVisible ? 'password' : 'text';
            toggle.setAttribute('aria-label', isVisible ? 'Show password' : 'Hide password');
            toggle.innerHTML = `<i data-lucide="${isVisible ? 'eye' : 'eye-off'}" class="h-4 w-4"></i>`;
            lucide.createIcons({
                nodes: [toggle]
            });
        });
    </script>
</body>

</html>
