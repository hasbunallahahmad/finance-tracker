<!-- resources/views/dashboard.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Dashboard Summary Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <!-- Total Income Card -->
                        <div class="bg-green-100 rounded-lg p-6 shadow">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-sm font-medium text-green-800">Total Income</h3>
                                    <p class="text-2xl font-bold text-green-600">Rp 5.000.000</p>
                                </div>
                                <div class="bg-green-200 rounded-full p-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-2">
                                <p class="text-sm text-green-700">+12% dari bulan lalu</p>
                            </div>
                        </div>

                        <!-- Total Expense Card -->
                        <div class="bg-red-100 rounded-lg p-6 shadow">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-sm font-medium text-red-800">Total Expense</h3>
                                    <p class="text-2xl font-bold text-red-600">Rp 3.500.000</p>
                                </div>
                                <div class="bg-red-200 rounded-full p-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-2">
                                <p class="text-sm text-red-700">+5% dari bulan lalu</p>
                            </div>
                        </div>

                        <!-- Balance Card -->
                        <div class="bg-blue-100 rounded-lg p-6 shadow">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-sm font-medium text-blue-800">Balance</h3>
                                    <p class="text-2xl font-bold text-blue-600">Rp 1.500.000</p>
                                </div>
                                <div class="bg-blue-200 rounded-full p-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-2">
                                <p class="text-sm text-blue-700">Selisih pendapatan dan pengeluaran</p>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Section -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Income vs Expense Chart -->
                        <div class="bg-white rounded-lg p-6 shadow border">
                            <h3 class="text-lg font-medium text-gray-800 mb-4">Income vs Expense</h3>
                            <div class="h-64">
                                <!-- Income vs Expense Chart akan ditampilkan di sini -->
                                <p class="text-gray-500 text-center mt-20">Chart area - Income vs Expense</p>
                            </div>
                        </div>

                        <!-- Expense Categories Chart -->
                        <div class="bg-white rounded-lg p-6 shadow border">
                            <h3 class="text-lg font-medium text-gray-800 mb-4">Expense by Category</h3>
                            <div class="h-64">
                                <!-- Expense Categories Chart akan ditampilkan di sini -->
                                <p class="text-gray-500 text-center mt-20">Chart area - Expense Categories</p>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Transactions -->
                    <div class="bg-white rounded-lg p-6 shadow border">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-800">Recent Transactions</h3>
                            <a href="#" class="text-blue-600 hover:text-blue-800 text-sm">View All</a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Description
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Category
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Amount
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Type
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">Gaji Bulanan</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">Salary</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">01/05/2023</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">Rp 5.000.000</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Income
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">Belanja Bulanan</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">Groceries</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">05/05/2023</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">Rp 1.200.000</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Expense
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">Bayar Internet</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">Utilities</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">10/05/2023</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">Rp 350.000</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Expense
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
