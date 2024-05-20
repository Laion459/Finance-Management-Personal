<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 text-white font-bold">
            <!-- Container for registration buttons -->
            <div class="flex justify-between mb-6">

                <!-- Input registration button -->
                <div class="flex items-center">
                    <a href="{{ route('entradas.form') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-6 rounded transition duration-300 transform hover:scale-110">
                        <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor" class="bi bi-arrow-down-circle" viewBox="0 0 16 16" style="color: white;">
                            <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-7.5 3.5a.5.5 0 0 0-1 0V5.707L5.354 7.854a.5.5 0 0 0-.708-.708l3-3a.5.5 0 0 0 .708 0l3 3a.5.5 0 0 0-.708.708L8.5 5.707z" />
                        </svg>
                        <span class="ml-2" style="font-size: 1.2em; color: white;">Entradas</span>
                    </a>
                </div>

                <div class="flex items-center text-white font-bold">
                    <span class="ml-2" style="font-size: 2.5em; color: orange;">MENU</span>
                </div>

                <!-- Output registration button -->
                <div class="flex items-center">
                    <a href="{{ route('saidas.form') }}" class="bg-red-500 hover:bg-red-700 text-white font-bold py-3 px-6 rounded transition duration-300 transform hover:scale-110">
                        <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor" class="bi bi-arrow-up-circle" viewBox="0 0 16 16" style="color: white;">
                            <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-7.5 3.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707z" />
                        </svg>
                        <span class="ml-2" style="font-size: 1.2em; color: white;">Saídas</span>
                    </a>
                </div>
            </div>

            <!-- Container for existing buttons -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between">
                    <!-- Button and image for expense registration form -->
                    <div class="flex flex-col items-center">
                        <a href="{{ route('expenses.form') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded transition duration-300 transform hover:scale-110">
                            <img class="w-32 h-32 transition duration-300 transform hover:scale-110" src="{{ asset('img/registro-despesa.jpg') }}" alt="Registro de Despesas">
                        </a>
                        <span class="mt-2 text-sm text-center">Saídas Diárias</span>
                    </div>

                    <!-- Button and image for budget tracking -->
                    <div class="flex flex-col items-center">
                        <a href="{{ route('budget-tracking') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded transition duration-300 transform hover:scale-110">
                            <img class="w-32 h-32 transition duration-300 transform hover:scale-110" src="{{ asset('img/capa.jpg') }}" alt="Acompanhamento de Orçamento">
                        </a>
                        <span class="mt-2 text-sm text-center">Acompanhar Orçamento</span>
                    </div>

                    <!-- Button and image for Monthly Reports -->
                    <div class="flex flex-col items-center">
                        <a href="{{ route('reports.monthly') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-6 px-8 rounded transition duration-300 transform hover:scale-110">
                            <img class="w-36 h-36 transition duration-300 transform hover:scale-110" src="{{ asset('img/grafi.jpg') }}" alt="Relatório Mensal">
                        </a>
                        <span class="mt-2 text-sm text-center">Relatório Mensal</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
