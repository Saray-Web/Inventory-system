@extends('Layouts.app')
@section('content')
    @if (session('success'))
        <div
            class="mb-5 flex items-center gap-3
                rounded-lg border border-green-200
                bg-green-50 px-4 py-3 text-sm text-green-700">

            <i data-lucide="circle-check" class="w-5 h-5"></i>

            <span>
                {{ session('success') }}
            </span>

        </div>
    @endif
    @if (session('error'))
        <div
            class="mb-5 flex items-center gap-3
                rounded-lg border border-green-200
                bg-green-50 px-4 py-3 text-sm text-red-700">

            <i data-lucide="circle-x" class="w-5 h-5"></i>

            <span>
                {{ session('error') }}
            </span>

        </div>
    @endif
    <div class="space-y-8">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    Categories
                </h1>
                <p class="text-sm text-gray-500">
                    Management Categories List
                </p>
            </div>
            <div class="text-sm text-gray-500">
                {{ now()->format('d M Y') }}
            </div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 sm:p-6">

            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                {{-- Add Category --}}
                <div>
                    <a href="{{ route('categories.create') }}"
                        class="inline-flex items-center
                       px-3 py-2 lg:px-4
                       bg-blue-700 text-white rounded-lg
                       hover:bg-blue-800 transition
                       gap-x-2">

                        <i data-lucide="SquarePlus" class="w-5 h-5 shrink-0"></i>

                        <span class="font-bold">
                            Add Category
                        </span>

                    </a>
                </div>


                {{-- Filter + Search --}}
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">

                    {{-- Filter --}}
                    <div class="relative w-full sm:w-44">

                        <button type="button" id="statusBtn"
                            class="w-full h-11 flex items-center justify-between
                           bg-white border border-gray-200
                           rounded-lg px-4 text-sm text-gray-600
                           shadow-sm hover:border-gray-300
                           focus:outline-none focus:ring-2
                           focus:ring-blue-100 focus:border-blue-400
                           transition">

                            <span id="statusText">
                                Filter by status
                            </span>

                            <i data-lucide="chevrons-up-down" class="w-4 h-4 text-gray-400">
                            </i>

                        </button>


                        {{-- Dropdown --}}
                        <div id="statusDropdown"
                            class="hidden absolute z-50 mt-2 w-full
                           bg-white border border-gray-100
                           rounded-lg shadow-lg p-1">

                            <button type="button"
                                class="status-option w-full text-left
                               px-3 py-2 text-sm text-gray-600
                               rounded-md hover:bg-gray-50 transition"
                                data-value="">
                                All Status
                            </button>

                            <button type="button"
                                class="status-option w-full flex items-center gap-2
                               text-left px-3 py-2 text-sm text-gray-600
                               rounded-md hover:bg-green-50 transition"
                                data-value="active">

                                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                Active
                            </button>

                            <button type="button"
                                class="status-option w-full flex items-center gap-2
                               text-left px-3 py-2 text-sm text-gray-600
                               rounded-md hover:bg-red-50 transition"
                                data-value="inactive">

                                <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                Inactive
                            </button>

                        </div>
                    </div>


                    {{-- Search --}}
                    <label class="relative w-full sm:w-64">

                        <i data-lucide="search" class="w-4 h-4 absolute inset-y-0 left-3 my-auto text-slate-400">
                        </i>

                        <input type="text" placeholder="Search by name or SKU..."
                            class="w-full pl-9 pr-3 py-2.5 text-sm
                           rounded-lg bg-slate-100
                           border border-transparent
                           focus:bg-white
                           focus:border-blue-500
                           focus:ring-2 focus:ring-blue-500/20
                           outline-none transition">

                    </label>

                </div>

            </div>

        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">


            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[700px] ">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-sm tracking-wide border-b border-slate-200">
                        <tr ">
                            <th class="py-3 px-4 text-green-700">#</th>
                            <th class="py-3 px-4">Category</th>
                            <th class="py-3 px-4">Description</th>
                            <th class="py-3 px-4">Products</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Created Date</th>
                            <th class="py-3 px-4 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($categories as $category)
                            <tr class="transition-colors duration-200 lg:hover:bg-green-300/10">
                                <td class="py-4 px-4 font-medium text-green-700">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="py-4 px-4 font-semibold text-gray-800">
                                    {{ $category->name }}
                                </td>
                                <td class="py-4 px-4 text-gray-500 max-w-xs truncate">
                                    {{ $category->description ?? 'No Description' }}
                                </td>
                                <td class="py-4 px-4 text-gray-700 text-lg">
                                    {{ $category->products_count }}
                                </td>
                                @if ($category->is_active)
                                    <td class="py-4 px-4">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Active</span>
                                    </td>
                                @else
                                    <td class="py-4 px-4">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Inctive</span>
                                    </td>
                                @endif

                                <td class="py-4 px-4 text-gray-500 whitespace-nowrap">
                                    {{ $category->created_at->format('d/m/Y') }}
                                </td>

                                <td class="py-4 px-4 text-center">
                                    <div class="flex items-center justify-center gap-2 shrink-0">
                                        <a href="{{ route('categories.edit', $category) }}"
                                            class="inline-flex items-center justify-center w-9 h-9 border border-gray-200 text-blue-700 rounded-md
                                                hover:bg-blue-100 transition">
                                            <i data-lucide="SquarePen" class="w-4 h-4"></i>
                                        </a>
                                        <form action="{{ route('categories.destroy', $category) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this category?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center justify-center w-9 h-9 border border-red-100 text-red-600 rounded-md
                                                    hover:bg-red-50 transition">
                                                <i data-lucide="Trash2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-10 text-center text-gray-500">
                                    No categories found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>




    <script>
        const statusBtn = document.getElementById('statusBtn');
        const statusDropdown = document.getElementById('statusDropdown');
        const statusText = document.getElementById('statusText');
        const statusOptions = document.querySelectorAll('.status-option');

        // Open / Close dropdown
        statusBtn.addEventListener('click', () => {
            statusDropdown.classList.toggle('hidden');
        });

        // Select option
        statusOptions.forEach(option => {
            option.addEventListener('click', () => {

                statusText.textContent = option.textContent.trim();

                statusDropdown.classList.add('hidden');
            });
        });

        // Close when click outside
        document.addEventListener('click', (e) => {

            if (!statusBtn.contains(e.target) &&
                !statusDropdown.contains(e.target)) {

                statusDropdown.classList.add('hidden');
            }
        });
    </script>
@endsection
