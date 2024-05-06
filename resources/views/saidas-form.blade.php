@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center h-screen bg-gray-900">
    <div class="bg-gray-800 rounded-lg p-8">
        <h1 class="text-white text-2xl mb-4">Registrar Saída</h1>
        <form action="{{ route('saidas.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="tipo" class="text-white block mb-2">Tipo</label>
                <select name="tipo" id="tipo" class="bg-gray-700 border border-gray-600 rounded-lg p-2 w-full focus:outline-none focus:border-blue-400">
                    <option value="aluguel">Aluguel</option>
                    <option value="energia">Energia</option>
                    <!-- Adicione outras opções aqui -->
                </select>
            </div>
            <div class="mb-4">
                <label for="descricao" class="text-white block mb-2">Descrição</label>
                <input type="text" name="descricao" id="descricao" class="bg-gray-700 border border-gray-600 rounded-lg p-2 w-full focus:outline-none focus:border-blue-400">
            </div>
            <div class="mb-4">
                <label for="valor" class="text-white block mb-2">Valor</label>
                <input type="text" name="valor" id="valor" class="bg-gray-700 border border-gray-600 rounded-lg p-2 w-full focus:outline-none focus:border-blue-400">
            </div>
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600">Registrar</button>
        </form>
    </div>
</div>
@endsection
