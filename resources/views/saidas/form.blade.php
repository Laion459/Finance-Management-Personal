<x-app-layout>
    <x-slot name="header">
        <h1 class="text-white text-2xl mb-4">Registrar Saída</h1>
    </x-slot>

    <div class="flex justify-center items-center h-screen bg-gray-900">
        <div class="bg-gray-800 rounded-lg p-8 w-96 text-center">
            <form action="{{ route('saidas.store') }}" method="POST">
                @csrf

                <!-- Adicionando o campo oculto para tipo_despesa -->
                <input type="hidden" name="tipo_despesa" id="tipo_despesa_hidden" value="">

                <div class="mb-4">
                    <label for="tipo_despesa" class="text-white block mb-2">Tipo de Despesa</label>
                    <select name="tipo_despesa" id="tipo_despesa" class="bg-gray-700 border border-gray-600 rounded-lg p-2 w-full focus:outline-none focus:border-blue-400">
                        <option value="moradia">Moradia</option>
                        <option value="transporte">Transporte</option>
                        <option value="alimentacao">Alimentação</option>
                        <option value="saude">Saúde</option>
                        <option value="educacao">Educação</option>
                        <option value="lazer">Lazer</option>
                        <option value="outros">Outros</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="categoria" class="text-white block mb-2">Categoria</label>
                    <select name="categoria" id="categoria" class="bg-gray-700 border border-gray-600 rounded-lg p-2 w-full focus:outline-none focus:border-blue-400">
                        <!-- Opções de categoria serão adicionadas dinamicamente aqui -->
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
                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 w-full">Registrar</button>

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
            $('#tipo_despesa').change(function() {
                var tipoDespesa = $(this).val();
                console.log('Tipo de despesa selecionado:', tipoDespesa);
                var categorias = categoriasPorTipo(tipoDespesa);
                console.log('Categorias:', categorias);
                $('#categoria').empty();
                $.each(categorias, function(index, categoria) {
                    $('#categoria').append('<option value="' + categoria + '">' + categoria + '</option>');
                });
                // Atualizar o campo oculto 'tipo_despesa_hidden' com o valor selecionado
                $('#tipo_despesa_hidden').val(tipoDespesa);
            });

            // Atualizar o subtipo_hidden quando a categoria for selecionada
            $('#categoria').change(function() {
                $('#categoria_hidden').val($(this).val());
            });

            function categoriasPorTipo(tipo) {
                switch (tipo) {
                    case 'moradia':
                        return ['Aluguel', 'Condomínio', 'IPTU', 'Água', 'Luz', 'Gás', 'Internet', 'Telefone', 'TV a cabo', 'Manutenção', 'Outros'];
                    case 'transporte':
                        return ['Combustível', 'Estacionamento', 'Pedágio', 'Transporte público', 'Parcela do veículo', 'Manutenção do veículo', 'Seguro do veículo', 'Outros'];
                    case 'alimentacao':
                        return ['Mercado', 'Restaurante', 'Lanches', 'Padaria', 'Bebidas'];
                    case 'saude':
                        return ['Plano de saúde', 'Consultas médicas', 'Medicamentos', 'Exames', 'Tratamentos'];
                    case 'educacao':
                        return ['Mensalidade escolar', 'Material escolar', 'Cursos', 'Livros'];
                    case 'lazer':
                        return ['Cinema', 'Teatro', 'Shows', 'Viagens', 'Restaurantes', 'Bares'];
                    case 'outros':
                        return ['Roupas', 'Calçados', 'Presentes', 'Doações', 'Assinaturas', 'Serviços', 'Parcelas de compras', 'Outros'];
                    default:
                        return [];
                }
            }
        });
    </script>
</x-app-layout>
