<x-app-layout>
    <x-slot name="header">
        <h1 class="text-white text-2xl mb-4 flex justify-center items-center">Registrar Despesas</h1>
    </x-slot>
    <hr>
    <div class="flex justify-center items-center h-screen bg-gray-900">
        <div class="bg-gray-800 rounded-lg p-8 text-center">
            <form action="{{ route('expenses.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="date" class="text-white block mb-2">Data</label>
                    <input type="date" name="date" id="date" class="bg-gray-700 border border-gray-600 rounded-lg p-2 w-full focus:outline-none focus:border-blue-400" required>
                </div>
                <div class="mb-4">
                    <label for="amount" class="text-white block mb-2">Valor</label>
                    <input type="number" name="amount" id="amount" class="bg-gray-700 border border-gray-600 rounded-lg p-2 w-full focus:outline-none focus:border-blue-400" required>
                </div>
                <div class="mb-4">
                    <label for="category_id" class="text-white block mb-2">Tipo de Despesa</label>
                    <select name="category_id" id="category_id" class="bg-gray-700 border border-gray-600 rounded-lg p-2 w-full focus:outline-none focus:border-blue-400" required>
                        <option value="" disabled selected>Selecione o tipo de despesa</option>
                        @foreach($expenseCategories as $id => $category)
                        <option value="{{ $id }}">{{ $category['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="payment_method" class="text-white block mb-2">Método de Pagamento</label>
                    <select name="payment_method" id="payment_method" class="bg-gray-700 border border-gray-600 rounded-lg p-2 w-full focus:outline-none focus:border-blue-400" required>
                        <option value="" disabled selected>Selecione o método de pagamento</option>
                        @foreach($paymentCategories as $id => $category)
                        <option value="{{ $id }}">{{ $category['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="description" class="text-white block mb-2">Descrição (opcional)</label>
                    <textarea class="bg-gray-700 border border-gray-600 rounded-lg p-2 w-full focus:outline-none focus:border-blue-400" name="description" id="description"></textarea>
                </div>
                <button type="submit" class="bg-blue-500 text-white rounded-lg p-2 w-full hover:bg-blue-600">Registrar Despesa</button>
            </form>
        </div>
    </div>
</x-app-layout>
<script>
    // Obtém as categorias de despesas do PHP em formato JSON
    const expenseCategories = JSON.parse('{!! json_encode($expenseCategories) !!}');
    // Obtém as categorias de métodos de pagamento do PHP em formato JSON
    const paymentCategories = JSON.parse('{!! json_encode($paymentCategories) !!}');
    dd(expenseCategories, paymentCategories);


    console.log('expenseCategories:', expenseCategories);
    console.log('paymentCategories:', paymentCategories);
    // Função para preencher os subtipos quando um tipo de despesa é selecionado
    function fillPaymentMethods() {
        const selectedCategoryId = document.getElementById('category_id').value;
        const paymentMethodSelect = document.getElementById('payment_method');
        paymentMethodSelect.innerHTML = ''; // Limpa as opções existentes

        // Encontra as categorias correspondentes ao tipo de despesa selecionado
        const selectedPaymentMethods = paymentCategories.filter(category => category.id == selectedCategoryId);

        // Preenche os métodos de pagamento no elemento de seleção
        selectedPaymentMethods.forEach(category => {
            const option = document.createElement('option');
            option.value = category.id;
            option.textContent = category.subtype; // Use category.subtype
            paymentMethodSelect.appendChild(option);
        });
    }

    // Adiciona um listener de evento para o evento 'change' no tipo de despesa
    document.getElementById('category_id').addEventListener('change', fillPaymentMethods);

    // Chama a função fillPaymentMethods para preencher os métodos de pagamento iniciais
    fillPaymentMethods();
</script>
