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
                <div class="mb-4" id="subtipo-container">
                    <!-- Opções de subtipo serão adicionadas dinamicamente aqui -->
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
                console.log('Tipo selecionado:', tipo);
                var subtipos = getSubtipos(tipo);
                console.log('Subtipos:', subtipos);
                $('#subtipo-container').empty();
                if (subtipos.length > 0) {
                    var selectHtml = '<label for="subtipo" class="text-white block mb-2">Subtipo</label>';
                    selectHtml += '<select name="subtipo" id="subtipo" class="bg-gray-700 border border-gray-600 rounded-lg p-2 w-full focus:outline-none focus:border-blue-400" required>';
                    $.each(subtipos, function(index, subtipo) {
                        selectHtml += '<option value="' + subtipo + '">' + subtipo + '</option>';
                    });
                    selectHtml += '</select>';
                    $('#subtipo-container').append(selectHtml);
                }
            });

            function getSubtipos(tipo) {
                switch (tipo) {
                    case 'salario':
                        return ['Salário Mensal', 'Bônus', 'Participação nos Lucros', 'Horas Extras'];
                    case 'investimentos':
                        return ['Dividendos', 'Juros', 'Ganhos de Capital'];
                    case 'renda_extra':
                        return ['Trabalho Freelance', 'Vendas Online', 'Aluguel de Imóveis'];
                    case 'presentes':
                        return ['Dinheiro', 'Bens de Valor'];
                    case 'outros':
                        return ['Restituição de Imposto', 'Prêmios', 'Heranças'];
                    default:
                        return [];
                }
            }
        });
    </script>
</x-app-layout>
