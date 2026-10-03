@extends('Layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto">

        {{-- Page Header --}}
        <div class="mb-6">
            <div class="flex items-center gap-2  text-sm text-gray-500 mb-2">
                <a href="{{ route('categories.index') }}" class="hover:text-indigo-600 font-bold">
                    Categories
                </a>

                <i data-lucide="chevron-right" class="w-4 h-4"></i>

                <span>Edit Category</span>
            </div>

            <h1 class="text-2xl font-bold text-gray-900">
                Edit Category
            </h1>

            <p class="text-sm text-gray-500 mt-1">
                Update the details of this inventory category.
            </p>
        </div>


        {{-- Main Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- ========================================= --}}
            {{-- LEFT : FORM --}}
            {{-- ========================================= --}}

            <div class="lg:col-span-2">

                <div class="bg-white border border-gray-200 rounded-xl shadow-sm">

                    {{-- Form Header --}}
                    <div class="px-5 py-5 border-b border-gray-100">

                        <h2 class="text-base font-semibold text-gray-900">
                            Category Information
                        </h2>

                        <p class="text-sm text-gray-500 mt-1">
                            Define the foundational details for organizing inventory items.
                        </p>

                    </div>


                    {{-- Form --}}
                    <form action="{{ route('categories.update', $category) }}" method="POST" class="p-5">

                        @csrf
                        @method('PUT')

                        {{-- Category Name --}}
                        <div class="mb-6">

                            <div class="flex items-center justify-between mb-2">

                                <label class="text-sm font-medium text-gray-800">
                                    Category Name
                                    <span class="text-red-500">*</span>
                                </label>

                            </div>

                            <input type="text" id="categoryName" name="name"
                                value="{{ old('name', $category->name) }}" placeholder="e.g. Electronics, Hardware..."
                                class="w-full px-3 py-2.5 rounded-lg border
                                   border-gray-300 text-sm text-gray-800
                                   placeholder:text-gray-400
                                   outline-none transition
                                   focus:border-indigo-500
                                   focus:ring-2 focus:ring-indigo-100
                                   @error('name')
                                       border-red-400 focus:border-red-500 focus:ring-red-100
                                   @enderror">

                            @error('name')
                                <p class="flex items-center gap-1 mt-2 text-xs text-red-600">
                                    <i data-lucide="circle-alert" class="w-3.5 h-3.5"></i>
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- Description --}}
                        <div class="mb-6">

                            <div class="flex items-center justify-between mb-2">

                                <label class="text-sm font-medium text-gray-800">
                                    Description
                                </label>

                                <span class="text-xs text-gray-500">
                                    Optional
                                </span>

                            </div>

                            <textarea id="categoryDescription" name="description" rows="4"
                                placeholder="Briefly describe what belongs in this category..."
                                class="w-full px-3 py-2.5 rounded-lg border
                                   border-gray-300 text-sm text-gray-800
                                   placeholder:text-gray-400
                                   outline-none resize-none transition
                                   focus:border-indigo-500
                                   focus:ring-2 focus:ring-indigo-100">{{ old('description', $category->description) }}</textarea>

                            <p class="mt-2 text-xs text-gray-500">
                                Optional — shown wherever this category is displayed.
                            </p>

                            @error('description')
                                <p class="mt-2 text-xs text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- Divider --}}
                        <div class="border-t border-gray-100 mb-5"></div>


                        {{-- Status --}}
                        <div class="flex items-center justify-between gap-5">

                            <div>

                                <h3 class="text-sm font-semibold text-gray-900">
                                    Status
                                </h3>

                                <p class="text-xs text-gray-500 mt-1">
                                    Determine if this category is available for new inventory.
                                </p>

                            </div>


                            {{-- Status Toggle --}}
                            <div class="flex items-center p-1 bg-gray-100 rounded-lg shrink-0">

                                <button type="button" id="activeBtn"
                                    class="px-4 py-2 text-xs font-semibold rounded-md
                                    {{ old('is_active', $category->is_active) == 1 ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-600' }}
                                    transition">
                                    Active
                                </button>

                                <button type="button" id="inactiveBtn"
                                    class="px-4 py-2 text-xs font-semibold rounded-md
                                    {{ old('is_active', $category->is_active) == 0 ? 'bg-white text-red-600 shadow-sm' : 'text-gray-600' }}
                                    transition">
                                    Inactive
                                </button>

                            </div>

                            {{-- Actual value sent to Laravel --}}
                            <input type="hidden" name="is_active" id="statusInput"
                                value="{{ old('is_active', $category->is_active) }}">

                        </div>


                        {{-- Buttons --}}
                        <div class="flex justify-end gap-3 mt-8 pt-5 border-t border-gray-100">

                            <a href="{{ route('categories.index') }}"
                                class="px-5 py-2.5 rounded-lg border border-gray-300
                                   text-sm font-medium text-gray-700
                                   hover:bg-gray-50 transition">
                                Cancel
                            </a>

                            <button type="submit"
                                class="inline-flex items-center gap-2
                                   px-5 py-2.5 rounded-lg
                                   bg-indigo-600 text-white
                                   text-sm font-semibold
                                   hover:bg-indigo-700 transition">
                                <i data-lucide="save" class="w-4 h-4"></i>
                                Update Category
                            </button>

                        </div>

                    </form>

                </div>

            </div>


            {{-- ========================================= --}}
            {{-- RIGHT : LIVE PREVIEW --}}
            {{-- ========================================= --}}

            <div class="lg:col-span-1">

                <div class="bg-indigo-50/50 border border-indigo-100
                        rounded-xl p-4 sticky top-6">

                    {{-- Preview Header --}}
                    <div class="flex items-center gap-2 pb-3 border-b border-indigo-100">

                        <i data-lucide="eye" class="w-4 h-4 text-indigo-600"></i>

                        <h3 class="text-xs font-semibold text-indigo-700">
                            Live Preview
                        </h3>

                    </div>


                    {{-- Preview Card --}}
                    <div class="mt-4 bg-white border border-gray-200
                            rounded-xl p-4 shadow-sm">

                        {{-- Icon + Status --}}
                        <div class="flex items-center justify-between">

                            <div
                                class="w-9 h-9 rounded-lg bg-indigo-50
                                    flex items-center justify-center">

                                <i data-lucide="boxes" class="w-5 h-5 text-indigo-600"></i>

                            </div>


                            <span id="previewStatus"
                                class="inline-flex items-center px-2 py-0.5
           rounded-full text-[10px] font-medium
           {{ $category->is_active
               ? 'bg-green-50 text-green-600 border border-green-100'
               : 'bg-red-50 text-red-600 border border-red-100' }}">
                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                            </span>

                        </div>


                        {{-- Name --}}
                        <h3 id="previewName" class="mt-5 text-base font-semibold italic text-gray-800">
                            {{ old('name', $category->name) }}
                        </h3>


                        {{-- Description --}}
                        <p id="previewDescription">
                            {{ $category->description ?: 'Description will appear here when added.' }}
                        </p>


                        {{-- Divider --}}
                        <div class="border-t border-gray-100 mt-5 pt-3">

                            <div class="flex justify-between">

                                <span class="text-[11px] text-gray-400">
                                    0 Items
                                </span>

                                <span class="text-[11px] text-gray-400">
                                    Created just now
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================= --}}
    {{-- LIVE PREVIEW SCRIPT --}}
    {{-- ========================================= --}}

    <script>
        const nameInput = document.getElementById('categoryName');
        const descriptionInput = document.getElementById('categoryDescription');

        const previewName = document.getElementById('previewName');
        const previewDescription = document.getElementById('previewDescription');

        const activeBtn = document.getElementById('activeBtn');
        const inactiveBtn = document.getElementById('inactiveBtn');

        const statusInput = document.getElementById('statusInput');
        const previewStatus = document.getElementById('previewStatus');


        // Category Name Preview
        nameInput.addEventListener('input', function() {

            previewName.textContent =
                this.value.trim() || 'New Category Name';

        });


        // Description Preview
        descriptionInput.addEventListener('input', function() {

            previewDescription.textContent =
                this.value.trim() ||
                'Description will appear here when added.';

        });


        // Active
        activeBtn.addEventListener('click', function() {

            statusInput.value = '1';

            activeBtn.className =
                'px-4 py-2 text-xs font-semibold rounded-md bg-white text-indigo-600 shadow-sm transition';

            inactiveBtn.className =
                'px-4 py-2 text-xs font-medium text-gray-600 rounded-md transition';

            previewStatus.textContent = 'Active';

            previewStatus.className =
                'inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-green-50 text-green-600 border border-green-100';

        });


        // Inactive
        inactiveBtn.addEventListener('click', function() {

            statusInput.value = '0';

            inactiveBtn.className =
                'px-4 py-2 text-xs font-semibold rounded-md bg-white text-red-600 shadow-sm transition';

            activeBtn.className =
                'px-4 py-2 text-xs font-medium text-gray-600 rounded-md transition';

            previewStatus.textContent = 'Inactive';

            previewStatus.className =
                'inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-red-50 text-red-600 border border-red-100';

        });
    </script>
@endsection
