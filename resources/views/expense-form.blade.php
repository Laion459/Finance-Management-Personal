<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário de Registro de Despesas</title>
    <!-- link para o arquivo CSS do Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-800 p-6 flex justify-center items-center h-screen">

    <div class="w-full max-w-lg">
        <!-- Botão para voltar à página inicial -->
        <div class="flex items-center justify-center mt-16">
            <a href="{{ route('home') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Home</a>
        </div>
        <br><hr><br>
        <h1 class="text-2xl font-bold mb-4 text-gray-200 text-center">Formulário de Registro de Despesas</h1>
        <hr><br>
        <form action="{{ route('expenses.store') }}" method="post" class="bg-gray-900 shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-300 text-sm font-bold mb-2" for="date">Data:</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-800 bg-gray-200 leading-tight focus:outline-none focus:shadow-outline" type="date" name="date" id="date" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-300 text-sm font-bold mb-2" for="amount">Valor R$:</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-800 bg-gray-200 leading-tight focus:outline-none focus:shadow-outline" type="number" name="amount" id="amount" required placeholder="Digite o valor" onfocus="this.placeholder = ''">
            </div>

            <div class="mb-4">
                <label class="block text-gray-300 text-sm font-bold mb-2" for="category_id">Tipo de Despesa:</label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-800 bg-gray-200 leading-tight focus:outline-none focus:shadow-outline" name="category_id" id="category_id" required>
                    <option value="">Selecione o tipo de despesa</option>
                    @foreach($expenseCategories as $id => $category)
                    <option value="{{ $id }}">{{ $category }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-300 text-sm font-bold mb-2" for="payment_method">Método de Pagamento:</label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-800 bg-gray-200 leading-tight focus:outline-none focus:shadow-outline" name="payment_method" id="payment_method" required>
                    <option value="">Selecione o método de pagamento</option>
                    @foreach($paymentCategories as $id => $category)
                    <option value="{{ $id }}">{{ $category }}</option>
                    @endforeach
                </select>
            </div>


            <div class="mb-4">
                <label class="block text-gray-300 text-sm font-bold mb-2" for="description">Descrição (opcional):</label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-800 bg-gray-200 leading-tight focus:outline-none focus:shadow-outline" name="description" id="description" rows="4"></textarea>
            </div>



            <div class="flex items-center justify-center">
                <button class="bg-blue-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">Registrar Despesa</button>
            </div>



        </form>
    </div>
</body>

</html>
