@extends('Layouts.app')

@section('content')
    <div class="mx-auto max-w-6xl space-y-6">
        <!-- Header -->
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Add New Product</h1>
                <p class="text-sm text-slate-500">Enter details to add a new item to your inventory.</p>
            </div>
            <a href="{{ route('product.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                Back to Products
            </a>
        </div>

        <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

                <!-- Left Column: Basic Information (Spans 2 Columns) -->
                <div class="space-y-6 lg:col-span-2">
                    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-base font-semibold text-slate-900">Basic Information</h2>
                        <p class="text-xs text-slate-500">Essential product details for cataloging.</p>
                        <hr class="my-4 border-slate-100">

                        <div class="space-y-4">
                            <!-- 1. Product Name -->
                            <div class="space-y-1.5">
                                <label for="name" class="text-sm font-medium text-slate-700">
                                    Product Name <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="e.g. Leather Crossbody Bag"
                                    class="w-full rounded-lg border px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 shadow-sm outline-none transition
                                    @error('name') border-rose-400 focus:border-rose-500 focus:ring-2 focus:ring-rose-100 @else border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 @enderror">
                                @error('name')
                                    <p class="mt-1 flex items-center gap-1.5 text-xs text-rose-600">
                                        <i data-lucide="circle-alert" class="h-3.5 w-3.5"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- SKU & Category Row -->
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <!-- 2. SKU -->
                                <div class="space-y-1.5">
                                    <label for="skuPreview" class="text-sm font-medium text-slate-700">
                                        SKU
                                    </label>
                                    <input type="text" id="skuPreview" value="Auto-generated when saved" readonly
                                        class="w-full cursor-not-allowed rounded-lg border border-slate-200 bg-slate-100 px-3.5 py-2.5 font-mono text-sm text-slate-600 shadow-sm outline-none">
                                    <p class="text-xs text-slate-500">Generated from the first two letters of the product name.</p>
                                </div>

                                <!-- 3. Category -->
                                <div class="space-y-1.5">
                                    <label for="category_id" class="text-sm font-medium text-slate-700">
                                        Category <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <select id="category_id" name="category_id"
                                            class="w-full appearance-none rounded-lg border px-3.5 py-2.5 pr-10 text-sm text-slate-800 shadow-sm outline-none transition
                                            @error('category_id') border-rose-400 focus:border-rose-500 focus:ring-2 focus:ring-rose-100 @else border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 @enderror">
                                            <option value="" disabled {{ old('category_id') ? '' : 'selected' }}>Select a category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <i data-lucide="chevron-down" class="pointer-events-none absolute right-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                                    </div>
                                    @error('category_id')
                                        <p class="mt-1 flex items-center gap-1.5 text-xs text-rose-600">
                                            <i data-lucide="circle-alert" class="h-3.5 w-3.5"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <!-- 6. Description -->
                            <div class="space-y-1.5">
                                <label for="description" class="text-sm font-medium text-slate-700">Description</label>
                                <textarea id="description" name="description" rows="5" placeholder="Enter product details, materials, dimensions..."
                                    class="w-full rounded-lg border p-3.5 text-sm text-slate-800 placeholder-slate-400 shadow-sm outline-none transition
                                    @error('description') border-rose-400 focus:border-rose-500 focus:ring-2 focus:ring-rose-100 @else border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 @enderror">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-1 flex items-center gap-1.5 text-xs text-rose-600">
                                        <i data-lucide="circle-alert" class="h-3.5 w-3.5"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Price, Min Stock & Image (1 Column) -->
                <div class="space-y-6">

                    <!-- Pricing & Min Stock Card -->
                    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-base font-semibold text-slate-900">Inventory & Pricing</h2>
                        <p class="text-xs text-slate-500">Manage pricing and threshold limits.</p>
                        <hr class="my-4 border-slate-100">

                        <div class="space-y-4">
                            <!-- 4. Price -->
                            <div class="space-y-1.5">
                                <label for="price" class="text-sm font-medium text-slate-700">
                                    Price ($) <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-slate-400">$</span>
                                    <input type="number" id="price" min="0" step="0.01" name="price" value="{{ old('price') }}" placeholder="0.00"
                                        class="w-full rounded-lg border py-2.5 pl-8 pr-3.5 text-right text-sm text-slate-800 placeholder-slate-400 shadow-sm outline-none transition
                                        @error('price') border-rose-400 focus:border-rose-500 focus:ring-2 focus:ring-rose-100 @else border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 @enderror">
                                </div>
                                @error('price')
                                    <p class="mt-1 flex items-center gap-1.5 text-xs text-rose-600">
                                        <i data-lucide="circle-alert" class="h-3.5 w-3.5"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- 5. Minimum Stock -->
                            <div class="space-y-1.5">
                                <label for="minimum_stock" class="text-sm font-medium text-slate-700">Min Stock</label>
                                <input type="number" id="minimum_stock" name="minimum_stock" value="{{ old('minimum_stock', 5) }}" min="0"
                                    class="w-full rounded-lg border px-3.5 py-2.5 text-center text-sm text-slate-800 shadow-sm outline-none transition
                                    @error('minimum_stock') border-rose-400 focus:border-rose-500 focus:ring-2 focus:ring-rose-100 @else border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 @enderror">
                                @error('minimum_stock')
                                    <p class="mt-1 flex items-center gap-1.5 text-xs text-rose-600">
                                        <i data-lucide="circle-alert" class="h-3.5 w-3.5"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- 7. Image Upload Card -->
                    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-base font-semibold text-slate-900">Product Image</h2>
                        <p class="text-xs text-slate-500">Upload high quality media files.</p>
                        <hr class="my-4 border-slate-100">

                        <div class="relative flex flex-col items-center justify-center rounded-lg border-2 border-dashed p-6 text-center transition
                            @error('image') border-rose-300 bg-rose-50/20 hover:bg-rose-50/40 @else border-indigo-200 bg-indigo-50/30 hover:bg-indigo-50/60 @enderror">

                            <img id="imagePreview" class="mb-3 hidden max-h-36 rounded-lg object-contain shadow-sm" alt="Preview">

                            <div id="uploadPrompt" class="flex flex-col items-center">
                                <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-indigo-100 text-indigo-600">
                                    <i data-lucide="image" class="h-6 w-6"></i>
                                </div>
                                <p class="text-sm font-medium text-slate-700">
                                    <span class="font-semibold text-indigo-600">Upload a file</span> or drag and drop
                                </p>
                            </div>

                            <div class="relative z-10 mt-3 space-y-1 text-xs text-slate-500 pointer-events-none">
                                <p>JPG, PNG, or WEBP up to 5MB</p>
                                <p>Recommended: 1200 × 1200px (minimum 600 × 600px)</p>
                                <p id="imageFileNote" class="font-medium">Optional: no file selected.</p>
                            </div>

                            <input type="file" id="imageInput" name="image" accept=".jpg,.jpeg,.png,.webp" class="absolute inset-0 cursor-pointer opacity-0" onchange="previewImage(event)">
                        </div>

                        @error('image')
                            <p class="mt-2 flex items-center gap-1.5 text-xs text-rose-600">
                                <i data-lucide="circle-alert" class="h-3.5 w-3.5"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <a href="{{ route('product.index') }}"
                    class="rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-200">
                    Cancel
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                    <i data-lucide="save" class="h-4 w-4"></i>
                    Save Product
                </button>
            </div>
        </form>
    </div>

    <!-- Image Preview Script -->
    <script>
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('imagePreview');
            const prompt = document.getElementById('uploadPrompt');
            const fileNote = document.getElementById('imageFileNote');

            if (input.files && input.files[0]) {
                fileNote.textContent = `Selected: ${input.files[0].name}`;
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    prompt.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        document.getElementById('name').addEventListener('input', function(event) {
            const letters = event.target.value.replace(/[^a-z0-9]/gi, '').toUpperCase();
            const prefix = letters.length >= 2 ? letters.slice(0, 2) : 'PR';

            document.getElementById('skuPreview').value = `${prefix}-XXXX (generated when saved)`;
        });
    </script>
@endsection
