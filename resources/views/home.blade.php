<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Finance Management Personal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Container for registration buttons -->
            <div class="flex justify-center space-x-16 mb-6">
                <!-- Input registration button -->
                <div class="flex items-center">
                    <a href="{{ route('entradas.form') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-300 transform hover:scale-110">
                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-arrow-down-circle" viewBox="0 0 16 16" style=" color: green;">
                            <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293z" />
                        </svg>
                        <span class="ml-2" style="font-size: 1em; color: green;">INCOME</span>
                    </a>
                </div>
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 text-white font-bold">
                    <span class="ml-2" style="font-size: 2.5em; color: orange;">REGISTER</span>
                </div>

                <!-- Output registration button -->
                <div class="flex items-center">
                    <a href="{{ route('saidas.form') }}" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition duration-300 transform hover:scale-110">
                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-arrow-up-circle" viewBox="0 0 16 16" style=" color: red;">
                            <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-7.5 3.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707z" />
                        </svg>
                        <span class="ml-2" style="font-size: 0.9em; color: red;">EXPENSE</span>
                    </a>
                </div>
            </div>

            <!-- Container for existing buttons -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg flex justify-between">
                <div class="p-6 text-gray-900 dark:text-gray-100 flex space-x-6">

                    <!-- Button and image for expense registration form -->
                    <div class="flex flex-col items-center">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" style="-webkit-animation: slide-fwd-center 0.45s cubic-bezier(0.250, 0.460, 0.450, 0.940) both; animation: slide-fwd-center 0.45s cubic-bezier(0.250, 0.460, 0.450, 0.940) both;">
                            <a href="{{ route('expenses.form') }}">
                                <img class="w-24 transition duration-300 transform hover:scale-110" src="{{ asset('img/registro-despesa.jpg') }}" alt="Registro de Despesas">
                            </a>
                        </button>
                        <span class="mt-2 text-sm">Expense Registration</span>
                    </div>

                    <!-- Button and image for budget tracking -->
                    <div class="flex flex-col items-center">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" style="-webkit-animation: slide-fwd-center 0.45s cubic-bezier(0.250, 0.460, 0.450, 0.940) both; animation: slide-fwd-center 0.45s cubic-bezier(0.250, 0.460, 0.450, 0.940) both;">
                            <a href="{{ route('budget-tracking') }}">
                                <img class="w-24 transition duration-300 transform hover:scale-110" src="{{ asset('img/capa.jpg') }}" alt="Acompanhamento de Orçamento">
                            </a>
                        </button>
                        <span class="mt-2 text-sm">Budget Tracking</span>
                    </div>

                    <!-- Button and image for Monthly Reports -->
                    <div class="flex flex-col items-center">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" style="-webkit-animation: slide-fwd-center 0.45s cubic-bezier(0.250, 0.460, 0.450, 0.940) both; animation: slide-fwd-center 0.45s cubic-bezier(0.250, 0.460, 0.450, 0.940) both;">
                            <a href="{{ route('reports.monthly') }}">
                                <img class="w-24 transition duration-300 transform hover:scale-110" src="{{ asset('img/grafi.jpg') }}" alt="Acompanhamento de Orçamento">
                            </a>
                        </button>
                        <span class="mt-2 text-sm">Monthly Reports</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
