<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário de Registro de Despesas</title>
</head>
<body>
    <h1>Formulário de Registro de Despesas</h1>
    <form action="{{ route('expenses.store') }}" method="post">
        @csrf
        <label for="date">Data:</label>
        <input type="date" name="date" id="date" required><br><br>

        <label for="amount">Valor:</label>
        <input type="number" name="amount" id="amount" required><br><br>

        <label for="category">Categoria:</label>
        <select name="category" id="category" required>
            <option value="">Selecione a categoria</option>
            <option value="1">Alimentação</option>
            <option value="2">Transporte</option>
            <option value="3">Lazer</option>
            <!-- Adicione mais opções de categoria conforme necessário -->
        </select><br><br>

        <!-- Adicione mais campos conforme necessário -->

        <button type="submit">Registrar Despesa</button>
    </form>
</body>
</html>
