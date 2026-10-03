@extends('Layouts.app')
@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Edit Product</h1> <br>
            <p class="text-md text-slate-500">Update details for
                <span class="text-slate-800 font-bold">{{ $product->name }}</span>
            </p>
        </div>
        <form action="{{ route('product.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

                <!-- Left Column: Basic Information (Spans 2 Columns) -->
                <div class="space-y-6 lg:col-span-2">
                    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-base font-semibold text-slate-800">Basic Information</h2>
                        <hr class="my-4 border-slate-100">

                        <div class="space-y-4">
                            <!-- Product Name -->
                            <div class="space-y-1.5">
                                <label class="text-sm font-medium text-slate-700">
                                    Product Name <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="name" value="{{ old('name', $product->name) }}"
                                    placeholder="e.g. Leather Crossbody Bag"
                                    class="w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm text-slate-800 
                                placeholder-slate-400 shadow-sm outline-none transition
                                 focus:border-indigo-500  focus:ring-2 focus:ring-indigo-500/20
                                 @error('name')
                                      !border-red-400 focus:border-red-500 focus:ring-red-100
                                 @enderror
                                 ">
                                @error('name')
                                    <p class="flex items-center gap-1 mt-2 text-xs text-red-600">
                                        <i data-lucide="circle-alert" class="w-3.5 h-3.5"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- SKU & Category Row -->
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <!-- SKU -->
                                <div class="space-y-1.5">
                                    <label class="text-sm font-medium text-slate-700">SKU</label>
                                    <input type="text" name="sku" placeholder="e.g. ACC-BAG-001"
                                        value="{{ old('sku', $product->sku) }}"
                                        class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 shadow-sm transition-colors focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20
                                            @error('sku')
                                            !border-red-400 focus:border-red-500 focus:ring-red-100
                                            @enderror">
                                    @error('sku')
                                        <p class="flex items-center gap-1 mt-2 text-xs text-red-600">
                                            <i data-lucide="circle-alert" class="w-3.5 h-3.5"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Category -->
                                <div class="space-y-1.5">
                                    <label class="text-sm font-medium text-slate-700">Category</label>
                                    <div class="relative">
                                        <select name="category_id"
                                            class="w-full appearance-none rounded-lg border @error('category_id') border-red-500 @else border-slate-200 @enderror bg-white pl-3.5 pr-10 py-2.5 text-sm text-slate-800 shadow-sm transition-colors focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">

                                            {{-- លុប selected ពី default option ចេញ --}}
                                            <option value="" disabled
                                                {{ old('category_id', $product->category_id ?? '') == '' ? 'selected' : '' }}>
                                                Select a category
                                            </option>

                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach

                                        </select>

                                        <i data-lucide="chevron-down"
                                            class="pointer-events-none absolute right-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                                    </div>

                                    {{-- កែប្រែទៅជា category_id ឲ្យត្រូវជាមួយ name --}}
                                    @error('category_id')
                                        <p class="flex items-center gap-1 mt-2 text-xs text-red-600">
                                            <i data-lucide="circle-alert" class="w-3.5 h-3.5"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="space-y-1.5">
                                <label class="text-sm font-medium text-slate-700">Description</label>
                                <textarea name="description" {{ old('description', $product->description) }} rows="4"
                                    placeholder="Enter product details, materials, and dimensions..."
                                    class="w-full rounded-lg border border-slate-200 bg-white p-3.5 text-sm text-slate-800 placeholder-slate-400 shadow-sm transition-colors focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Inventory Details & Product Image (1 Column) -->
                <div class="space-y-6">

                    <!-- Inventory Details Card -->
                    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-base font-semibold text-slate-800">Inventory Details</h2>
                        <hr class="my-4 border-slate-100">

                        <div class="space-y-4">
                            <!-- Price -->
                            <div class="space-y-1.5">
                                <label class="text-sm font-medium text-slate-700">Price ($)</label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-slate-400">$</span>
                                    <input type="number" step="0.01" value="{{ old('price', $product->price) }}"
                                        name="price" placeholder="0.00"
                                        class="w-full rounded-lg border border-slate-200 bg-white py-2.5 pl-8 pr-3.5 text-right text-sm text-slate-800 placeholder-slate-400 shadow-sm transition-colors focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20
                                        @error('price')
                                            !border-red-400 focus:border-red-500 focus:ring-red-100
                                        @enderror">

                                </div>
                                @error('price')
                                    <p class="flex items-center gap-1 mt-2 text-xs text-red-600">
                                        <i data-lucide="circle-alert" class="w-3.5 h-3.5"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Initial Stock & Min Stock -->
                            <div class="grid grid-cols-2 gap-3">
                                <div class="space-y-1.5">
                                    <label class="text-sm font-medium text-slate-700">Initial Stock</label>
                                    <input type="number" name="quantity" value="{{ old('quantity', $product->quantity) }}"
                                        min="0" disabled
                                        class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-center text-sm text-slate-800 shadow-sm transition-colors">
                                    <small class="text-muted">* ចំនួនស្តុកអាចកែប្រែបានតែតាមរយៈ Stock In / Stock Out
                                        ប៉ុណ្ណោះ</small>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-sm font-medium text-slate-700">Min Stock</label>
                                    <input type="number" name="minimum_stock"
                                        value="{{ old('minimum_stock', $product->minimum_stock) }}"
                                        class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-center text-sm text-slate-800 shadow-sm transition-colors focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20
                                        @error('minimum_stock')
                                            !border-red-400 focus:border-red-500 focus:ring-red-100
                                        @enderror
                                        ">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Image Card -->
                    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-base font-semibold text-slate-800">Product Image</h2>
                        <hr class="my-4 border-slate-100">

                        <div
                            class="relative flex flex-col items-center justify-center rounded-lg border-2 border-dashed border-indigo-200 bg-indigo-50/30 p-6 text-center transition-colors hover:bg-indigo-50/60">

                            <img id="imagePreview" class="mb-3 hidden max-h-36 rounded-lg object-contain shadow-sm"
                                alt="Preview">

                            <div id="uploadPrompt" class="flex flex-col items-center">
                                <div
                                    class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-indigo-100 text-indigo-600">
                                    <i data-lucide="image" class="h-6 w-6"></i>
                                </div>
                                <p class="text-sm font-medium text-slate-700">
                                    <span class="text-indigo-600 font-semibold">Upload a file</span> or drag and drop
                                </p>
                                <p class="mt-1 text-xs text-slate-400">PNG, JPG, GIF up to 10MB</p>
                            </div>

                            <input type="file" name="image" id="imageInput" accept="image/*"
                                class="absolute inset-0 cursor-pointer opacity-0" onchange="previewImage(event)">
                        </div>
                    </div>
                    @error('image')
                        <p class="text-sm text-red-500 mt-1">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

            </div>

            <!-- Form Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('product.index') }}"
                    class="rounded-lg border border-slate-300 bg-white px-6 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition-colors hover:bg-slate-50">
                    Cancel
                </a>
                <button type="submit"
                    class="cursor-pointer rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
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

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    prompt.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
