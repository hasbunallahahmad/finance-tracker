<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Pemasukan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                <form action="{{ route('incomes.update', $income) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-label for="title" value="{{ __('Judul') }}" />
                            <x-input id="title" name="title" type="text" class="mt-1 block w-full"
                                value="{{ old('title', $income->title) }}" required autofocus />
                            @error('title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-label for="amount" value="{{ __('Jumlah (Rp)') }}" />
                            <x-input id="amount" name="amount" type="number" step="0.01" min="0"
                                class="mt-1 block w-full" value="{{ old('amount', $income->amount) }}" required />
                            @error('amount')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-label for="category_id" value="{{ __('Kategori') }}" />
                            <select id="category_id" name="category_id"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $income->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-label for="date" value="{{ __('Tanggal') }}" />
                            <x-input id="date" name="date" type="date" class="mt-1 block w-full"
                                value="{{ old('date', $income->date->format('Y-m-d')) }}" required />
                            @error('date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-1 md:col-span-2">
                            <x-label for="description" value="{{ __('Deskripsi') }}" />
                            <textarea id="description" name="description" rows="3"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">{{ old('description', $income->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-1 md:col-span-2">
                            <x-label for="attachment" value="{{ __('Lampiran (PDF/Gambar)') }}" />

                            @if ($income->attachment)
                                <div class="mb-2 flex items-center">
                                    <span class="text-sm text-gray-600 mr-2">File saat ini:</span>
                                    <a href="{{ $income->attachment_url }}" target="_blank"
                                        class="text-blue-600 hover:text-blue-800 text-sm">
                                        Lihat lampiran
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                </div>
                            @endif

                            <input id="attachment" name="attachment" type="file" class="mt-1 block w-full" />
                            <p class="text-gray-500 text-xs mt-1">Format yang didukung: JPEG, PNG, JPG, PDF (Maks. 2MB)
                            </p>
                            <p class="text-gray-500 text-xs">Kosongkan jika tidak ingin mengubah file.</p>
                            @error('attachment')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-6">
                        <a href="{{ route('incomes.index') }}" class="text-gray-600 hover:text-gray-900">
                            {{ __('Kembali') }}
                        </a>
                        <x-button>
                            {{ __('Update') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
