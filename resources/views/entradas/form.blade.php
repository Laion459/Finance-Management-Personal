<x-app-layout>
    <x-slot name="header">
        <h1 class="text-white text-2xl mb-4 flex justify-center items-center">Registrar Entrada</h1>
    </x-slot>
    <div class="flex justify-center items-center h-screen bg-gray-900">
        <div class="bg-gray-800 rounded-lg p-8 text-center">
            <form action="{{ route('entradas.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="tipo" class="text-white block mb-2">Tipo</label>
                    <select name="tipo" id="tipo" class="bg-gray-700 border border-gray-600 rounded-lg p-2 w-full focus:outline-none focus:border-blue-400" required>
                        <option value="salario">Salário</option>
                        <option value="investimentos">Investimentos</option>
                        <option value="renda_extra">Renda Extra</option>
                        <option value="presentes">Presentes</option>
                        <option value="outros">Outros</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="subtipo" class="text-white block mb-2">Subtipo</label>
                    <select name="subtipo" id="subtipo" class="bg-gray-700 border border-gray-600 rounded-lg p-2 w-full focus:outline-none focus:border-blue-400" required>
                        <optgroup label="Salário" id="salario-subtipos" style="display: none;">
                            <option value="Salário Mensal">Salário Mensal</option>
                            <option value="Bônus">Bônus</option>
                            <option value="Participação nos Lucros">Participação nos Lucros</option>
                            <option value="Horas Extras">Horas Extras</option>
                        </optgroup>
                        <optgroup label="Investimentos" id="investimentos-subtipos" style="display: none;">
                            <option value="Dividendos">Dividendos</option>
                            <option value="Juros">Juros</option>
                            <option value="Ganhos de Capital">Ganhos de Capital</option>
                        </optgroup>
                        <optgroup label="Renda Extra" id="renda_extra-subtipos" style="display: none;">
                            <option value="Trabalho Freelance">Trabalho Freelance</option>
                            <option value="Vendas Online">Vendas Online</option>
                            <option value="Aluguel de Imóveis">Aluguel de Imóveis</option>
                        </optgroup>
                        <optgroup label="Presentes" id="presentes-subtipos" style="display: none;">
                            <option value="Dinheiro">Dinheiro</option>
                            <option value="Bens de Valor">Bens de Valor</option>
                        </optgroup>
                        <optgroup label="Outros" id="outros-subtipos" style="display: none;">
                            <option value="Restituição de Imposto">Restituição de Imposto</option>
                            <option value="Prêmios">Prêmios</option>
                            <option value="Heranças">Heranças</option>
                        </optgroup>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="descricao" class="text-white block mb-2">Descrição</label>
                    <input type="text" name="descricao" id="descricao" class="bg-gray-700 border border-gray-600 rounded-lg p-2 w-full focus:outline-none focus:border-blue-400">
                </div>
                <div class="mb-4">
                    <label for="valor" class="text-white block mb-2">Valor</label>
                    <input type="text" name="valor" id="valor" class="bg-gray-700 border border-gray-600 rounded-lg p-2 w-full focus:outline-none focus:border-blue-400" required>
                </div>
                <button type="submit" class="bg-blue-500 text-white py-2 px-2 rounded-lg hover:bg-blue-600 ">Registrar</button>
            </form>
            <!-- Botão "Home" -->
            <div class="flex justify-center mt-8">
                <a href="{{ route('home') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Home</a>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tipo').change(function() {
                var tipo = $(this).val();
                // Esconde todos os optgroup
                $('#subtipo optgroup').hide();
                // Mostra o optgroup correspondente ao tipo selecionado
                $('#' + tipo + '-subtipos').show();
            });
        });
    </script>
</x-app-layout>
