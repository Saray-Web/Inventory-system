<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Stockflow · Inventory')</title>
    <script>
        // Apply the saved theme before the page paints to prevent a light-mode flash on navigation.
        if (localStorage.getItem('stockflow-theme') === 'dark') {
            document.documentElement.classList.add('dark');
        }
    </script>
    <script src="https://unpkg.com/lucide@latest"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 font-sans text-slate-800 antialiased">
    <script>
        // The dark-mode CSS targets body.dark too; set it before the app markup is parsed.
        if (document.documentElement.classList.contains('dark')) {
            document.body.classList.add('dark');
        }
    </script>
    <div class="min-h-screen">
        <aside id="sidebar"
            class="fixed inset-y-0 left-0 z-50 flex w-[17.5rem] -translate-x-full flex-col overflow-y-auto bg-slate-950 px-4 py-5 text-slate-300 shadow-2xl transition-transform duration-300 lg:translate-x-0">
            <a href="{{ route('dashboard.index') }}" class="mb-9 flex items-center gap-3 px-2 text-white">
                <span
                    class="grid h-10 w-10 place-items-center rounded-xl bg-gradient-to-br from-cyan-400 to-blue-600 shadow-lg shadow-blue-500/25"><i
                        data-lucide="boxes" class="h-5 w-5"></i></span>
                <span><strong class="block text-base tracking-tight">Stockflow</strong><small
                        class="text-xs text-slate-500">Inventory control</small></span>
            </a>
            <nav class="space-y-1.5">
                <p class="mb-3 px-3 text-[11px] font-bold uppercase tracking-[0.16em] text-slate-600">Workspace</p>
                @php($nav = [['dashboard.index', 'layout-dashboard', 'Dashboard'], ['categories.index', 'tags', 'Categories'], ['product.index', 'package', 'Products'], ['stockIn.index', 'circle-arrow-down', 'Stock In'], ['stockOut.index', 'circle-arrow-up', 'Stock Out'], ['history.index', 'RotateCcwClock', 'History']])
                @foreach ($nav as [$route, $icon, $label])
                    <a href="{{ route($route) }}"
                        class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs(str_replace('.index', '.*', $route)) ? 'bg-white/10 text-white shadow-sm ring-1 ring-white/10' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <i data-lucide="{{ $icon }}"
                            class="h-[18px] w-[18px] {{ request()->routeIs(str_replace('.index', '.*', $route)) ? 'text-cyan-400' : 'text-slate-500 group-hover:text-cyan-300' }}"></i>{{ $label }}
                    </a>
                @endforeach
            </nav>
            <div class="mt-auto border-t border-white/10 pt-4">
                <div class="flex items-center gap-3 rounded-xl bg-white/5 px-3 py-3"><span
                        class="grid h-9 w-9 place-items-center rounded-full bg-gradient-to-br from-violet-500 to-fuchsia-500 text-sm font-bold text-white">A</span><span
                        class="min-w-0"><strong class="block truncate text-sm text-white">Administrator</strong><small
                            class="block truncate text-xs text-slate-500">System owner</small></span></div>
            </div>
        </aside>
        <div id="overlay" class="fixed inset-0 z-40 hidden bg-slate-950/60 backdrop-blur-sm lg:hidden"></div>
        <div class="lg:ml-[17.5rem]">
            <header
                class="sticky top-0 z-30 flex h-[4.5rem] items-center justify-between border-b border-slate-200/80 bg-slate-50/85 px-4 backdrop-blur-xl sm:px-7">
                <div class="flex items-center gap-3"><button id="menuBtn"
                        class="grid h-10 w-10 place-items-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm lg:hidden"><i
                            data-lucide="menu" class="h-5 w-5"></i></button>
                    <div>
                        <p class="text-xs font-medium text-slate-400">Inventory workspace</p>
                        <h2 class="text-lg font-bold tracking-tight text-slate-900">@yield('page-title', 'Overview')</h2>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button id="themeBtn" type="button" aria-label="Toggle theme"
                        class="grid h-10 w-10 place-items-center rounded-xl border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:text-blue-600"><i
                            data-lucide="moon" class="h-4 w-4"></i></button>
                    <button id="notifBtn"
                        class="relative grid h-10 w-10 place-items-center rounded-xl border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:text-blue-600"><i
                            data-lucide="bell" class="h-4 w-4"></i><span
                            class="absolute right-2.5 top-2.5 h-1.5 w-1.5 rounded-full bg-rose-500 ring-2 ring-white"></span></button>
                    <div id="notifPanel"
                        class="absolute right-4 top-[4.25rem] hidden w-[min(22rem,calc(100vw-2rem))] overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl">
                        <div class="border-b border-slate-100 px-4 py-3"><strong class="text-sm">Notifications</strong>
                            <p class="mt-0.5 text-xs text-slate-500">Your inventory is up to date.</p>
                        </div>
                        <div class="px-4 py-5 text-center text-sm text-slate-400">No new notifications</div>
                    </div>
                    <div class="hidden h-7 w-px bg-slate-200 sm:block"></div>
                    <div class="relative">
                        <button id="profileMenuBtn" type="button" aria-expanded="false" aria-controls="profileMenu"
                            class="flex items-center gap-2 rounded-xl p-1.5 text-left transition hover:bg-slate-200/70">
                            <span class="hidden text-right sm:block"><strong
                                    class="block text-sm text-slate-700">Admin</strong><small
                                    class="text-xs text-emerald-600">Online</small></span>
                            {{-- Replace this fallback with an <img> later; data-profile-avatar is ready for the dynamic image URL. --}}
                            <span data-profile-avatar
                                class="grid h-9 w-9 place-items-center overflow-hidden rounded-full bg-gradient-to-br from-violet-500 to-fuchsia-500 text-sm font-bold text-white">A</span>
                            <i data-lucide="chevron-down" class="hidden h-4 w-4 text-slate-400 sm:block"></i>
                        </button>
                        <div id="profileMenu"
                            class="absolute right-0 top-[calc(100%+0.6rem)] hidden w-64 overflow-hidden rounded-2xl border border-slate-200 bg-white p-1.5 shadow-xl"
                            role="menu">
                            <div class="flex items-center gap-3 px-3 py-3"><span data-profile-avatar
                                    class="grid h-10 w-10 place-items-center overflow-hidden rounded-xl bg-gradient-to-br from-violet-500 to-fuchsia-500 text-sm font-bold text-white">A</span>
                                <div>
                                    <strong class="block text-sm text-slate-800">Administrator</strong>
                                    <small class="block text-xs text-slate-500">admin@stockflow.local</small>
                                </div>
                            </div>
                            <div class="my-1 border-t border-slate-100"></div>
                            <a href="#" role="menuitem"
                                class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-100"><i
                                    data-lucide="settings-2" class="h-4 w-4 text-slate-400"></i>Settings</a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" role="menuitem"
                                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-rose-600 transition hover:bg-rose-50">
                                    <i data-lucide="log-out" class="h-4 w-4 text-rose-500"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>
            <main class="mx-auto max-w-[1600px] p-4 sm:p-7">
                @yield('content')
            </main>
        </div>
    </div>
    <script>
        lucide.createIcons();
    </script>
</body>

</html>
