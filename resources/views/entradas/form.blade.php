<x-app-layout>
    <x-slot name="header">
        <h1 class="text-white text-2xl mb-4 flex justify-center items-center">Registrar Entrada Mensais</h1>
    </x-slot>
    <hr>
    <div class="flex justify-center items-center h-screen bg-gray-900">
        <div class="bg-gray-800 rounded-lg p-8 text-center">
            <form action="{{ route('entradas.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="date" class="text-white block mb-2">Data</label>
                    <input type="date" name="date" id="date" class="bg-gray-700 border border-gray-600 rounded-lg p-2 w-full focus:outline-none focus:border-blue-400" required>
                </div>
                <div class="mb-4">
                    <label for="type" class="text-white block mb-2">Tipo</label>
                    <select name="type" id="type" class="bg-gray-700 border border-gray-600 rounded-lg p-2 w-full focus:outline-none focus:border-blue-400" required>
                        <option value="" disabled selected>Selecione o tipo</option>
                        @foreach ($categoriesJson as $category)
                            <option value="{{ $category['type'] }}">{{ $category['subtype'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="subtype" class="text-white block mb-2">Subtipo</label>
                    <select name="subtype" id="subtype" class="bg-gray-700 border border-gray-600 rounded-lg p-2 w-full focus:outline-none focus:border-blue-400" required>
                        <!-- Opções de subtipo serão preenchidas dinamicamente com JavaScript -->
                    </select>
                </div>

                <div class="mb-4">
                    <label for="description" class="text-white block mb-2">Descrição</label>
                    <input type="text" name="description" id="description" class="bg-gray-700 border border-gray-600 rounded-lg p-2 w-full focus:outline-none focus:border-blue-400">
                </div>
                <div class="mb-4">
                    <label for="amount" class="text-white block mb-2">Valor</label>
                    <input type="text" name="amount" id="amount" class="bg-gray-700 border border-gray-600 rounded-lg p-2 w-full focus:outline-none focus:border-blue-400" required>
                </div>
                <button type="submit" class="bg-blue-500 text-white rounded-lg p-2 w-full hover:bg-blue-600">Registrar</button>
            </form>
        </div>
    </div>

    <script>
        // Obtém as categorias do PHP em formato JSON
        const categories = JSON.parse('{!! json_encode($categoriesJson) !!}');

        // Função para preencher os subtipos quando um tipo é selecionado
        function fillSubtypes() {
            const selectedType = document.getElementById('type').value;
            const subtypeSelect = document.getElementById('subtype');
            subtypeSelect.innerHTML = '';

            // Encontra as categorias correspondentes ao tipo selecionado
            const selectedCategories = categories.filter(category => category.type === selectedType);

            // Preenche os subtipos no elemento de seleção de subtipos
            selectedCategories.forEach(category => {
                const option = document.createElement('option');
                option.value = category.subtype;
                option.textContent = category.subtype;
                subtypeSelect.appendChild(option);
            });
        }

        // Adiciona um listener de evento para o evento 'change' no tipo
        document.getElementById('type').addEventListener('change', fillSubtypes);

        // Chama a função fillSubtypes para preencher os subtipos iniciais
        fillSubtypes();
    </script>
</x-app-layout>
