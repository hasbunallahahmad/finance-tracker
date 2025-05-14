<!-- resources/views/transactions/create.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Transaction') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Form for adding a new transaction -->
                    <form action="{{ route('transactions.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Transaction Type -->
                            <div>
                                <x-label for="type" value="{{ __('Transaction Type') }}" />
                                <div class="mt-2 space-y-2">
                                    <div class="flex items-center">
                                        <input id="type_income" name="type" type="radio" value="income"
                                            class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300" checked>
                                        <label for="type_income" class="ml-3 block text-sm font-medium text-gray-700">
                                            Income
                                        </label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="type_expense" name="type" type="radio" value="expense"
                                            class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                        <label for="type_expense" class="ml-3 block text-sm font-medium text-gray-700">
                                            Expense
                                        </label>
                                    </div>
                                </div>
                                <x-input-error for="type" class="mt-2" />
                            </div>

                            <!-- Amount -->
                            <div>
                                <x-label for="amount" value="{{ __('Amount (Rp)') }}" />
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">Rp</span>
                                    </div>
                                    <x-input id="amount" type="number" name="amount" class="pl-12 block w-full"
                                        min="0" step="0.01" required />
                                </div>
                                <x-input-error for="amount" class="mt-2" />
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <x-label for="description" value="{{ __('Description') }}" />
                                <x-input id="description" type="text" name="description" class="mt-1 block w-full"
                                    required />
                                <x-input-error for="description" class="mt-2" />
                            </div>

                            <!-- Category -->
                            <div>
                                <x-label for="category_id" value="{{ __('Category') }}" />
                                <div class="mt-1">
                                    <select id="category_id" name="category_id"
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                        <!-- Income Categories -->
                                        <optgroup label="Income Categories" id="income_categories">
                                            <option value="1">Salary</option>
                                            <option value="2">Freelance</option>
                                            <option value="3">Investments</option>
                                            <option value="4">Gifts</option>
                                            <option value="5">Other Income</option>
                                        </optgroup>
                                        <!-- Expense Categories -->
                                        <optgroup label="Expense Categories" id="expense_categories"
                                            style="display:none;">
                                            <option value="6">Food & Drinks</option>
                                            <option value="7">Shopping</option>
                                            <option value="8">Housing</option>
                                            <option value="9">Transportation</option>
                                            <option value="10">Vehicle</option>
                                            <option value="11">Entertainment</option>
                                            <option value="12">Communication</option>
                                            <option value="13">Financial Expenses</option>
                                            <option value="14">Investments</option>
                                            <option value="15">Other Expense</option>
                                        </optgroup>
                                    </select>
                                </div>
                                <x-input-error for="category_id" class="mt-2" />
                            </div>

                            <!-- Date -->
                            <div>
                                <x-label for="date" value="{{ __('Date') }}" />
                                <x-input id="date" type="date" name="date" class="mt-1 block w-full"
                                    required />
                                <x-input-error for="date" class="mt-2" />
                            </div>

                            <!-- Notes -->
                            <div class="md:col-span-2">
                                <x-label for="notes" value="{{ __('Notes (Optional)') }}" />
                                <textarea id="notes" name="notes" rows="3"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border-gray-300 rounded-md"></textarea>
                                <x-input-error for="notes" class="mt-2" />
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end mt-6 space-x-4">
                            <a href="{{ route('transactions.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:border-gray-300 focus:ring focus:ring-gray-200 disabled:opacity-25 transition">
                                Cancel
                            </a>
                            <x-button>
                                {{ __('Save Transaction') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Category Toggling -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeIncome = document.getElementById('type_income');
            const typeExpense = document.getElementById('type_expense');
            const incomeCategories = document.getElementById('income_categories');
            const expenseCategories = document.getElementById('expense_categories');

            // Function to toggle categories based on transaction type
            function toggleCategories() {
                if (typeIncome.checked) {
                    incomeCategories.style.display = 'block';
                    expenseCategories.style.display = 'none';
                    // Select the first income category
                    if (incomeCategories.querySelector('option')) {
                        incomeCategories.querySelector('option').selected = true;
                    }
                } else {
                    incomeCategories.style.display = 'none';
                    expenseCategories.style.display = 'block';
                    // Select the first expense category
                    if (expenseCategories.querySelector('option')) {
                        expenseCategories.querySelector('option').selected = true;
                    }
                }
            }

            // Initial setup
            toggleCategories();

            // Add event listeners
            typeIncome.addEventListener('change', toggleCategories);
            typeExpense.addEventListener('change', toggleCategories);
        });
    </script>
</x-app-layout>
