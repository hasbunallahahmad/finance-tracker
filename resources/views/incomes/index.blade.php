<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pemasukan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                <!-- Alert Message -->
                @if (session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                        role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Form Tambah Pemasukan -->
                <div class="mb-6 p-6 bg-gray-50 border border-gray-200 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Tambah Pemasukan Baru</h3>

                    <form action="{{ route('incomes.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-label for="title" value="{{ __('Judul') }}" />
                                <x-input id="title" name="title" type="text" class="mt-1 block w-full"
                                    value="{{ old('title') }}" required autofocus />
                                @error('title')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <x-label for="amount" value="{{ __('Jumlah (Rp)') }}" />
                                <x-input id="amount" name="amount" type="number" step="0.01" min="0"
                                    class="mt-1 block w-full" value="{{ old('amount') }}" required />
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
                                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                                    value="{{ old('date', date('Y-m-d')) }}" required />
                                @error('date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-1 md:col-span-2">
                                <x-label for="description" value="{{ __('Deskripsi') }}" />
                                <textarea id="description" name="description" rows="3"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <x-label for="attachment" value="{{ __('Lampiran (PDF/Gambar)') }}" />
                                <input id="attachment" name="attachment" type="file" class="mt-1 block w-full" />
                                <p class="text-gray-500 text-xs mt-1">Format yang didukung: JPEG, PNG, JPG, PDF (Maks.
                                    2MB)</p>
                                @error('attachment')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-button>
                                {{ __('Simpan') }}
                            </x-button>
                        </div>
                    </form>
                </div>

                <!-- Daftar Pemasukan -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Riwayat Pemasukan</h3>

                    @if ($incomes->isEmpty())
                        <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 p-4 rounded-lg">
                            Belum ada data pemasukan.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="py-2 px-4 border-b text-left">Tanggal</th>
                                        <th class="py-2 px-4 border-b text-left">Judul</th>
                                        <th class="py-2 px-4 border-b text-left">Kategori</th>
                                        <th class="py-2 px-4 border-b text-right">Jumlah</th>
                                        <th class="py-2 px-4 border-b text-center">Lampiran</th>
                                        <th class="py-2 px-4 border-b text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($incomes as $income)
                                        <tr>
                                            <td class="py-2 px-4 border-b">{{ $income->date->format('d-m-Y') }}</td>
                                            <td class="py-2 px-4 border-b">
                                                <div>{{ $income->title }}</div>
                                                @if ($income->description)
                                                    <div class="text-xs text-gray-500">
                                                        {{ Str::limit($income->description, 50) }}</div>
                                                @endif
                                            </td>
                                            <td class="py-2 px-4 border-b">
                                                {{ $income->category ? $income->category->name : 'Tanpa Kategori' }}
                                            </td>
                                            <td class="py-2 px-4 border-b text-right">Rp
                                                {{ number_format($income->amount, 2, ',', '.') }}</td>
                                            <td class="py-2 px-4 border-b text-center">
                                                @if ($income->attachment)
                                                    <a href="{{ $income->attachment_url }}" target="_blank"
                                                        class="text-blue-600 hover:text-blue-800">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                        </svg>
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="py-2 px-4 border-b text-center">
                                                <div class="flex justify-center space-x-2">
                                                    <a href="{{ route('incomes.edit', $income) }}"
                                                        class="text-yellow-600 hover:text-yellow-800">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </a>
                                                    <form action="{{ route('incomes.destroy', $income) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="text-red-600 hover:text-red-800">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                                fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $incomes->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
