@extends('layouts.admin')

@section('content')

<h1 class="text-2xl font-bold mb-6 text-gray-800">Usuarios</h1>

<div class="bg-white rounded-xl shadow p-6">

<table class="w-full text-sm table-auto">

    <thead class="text-gray-500 border-b">
        <tr>
            <th class="text-left py-3">Nombre</th>
            <th class="text-left">Email</th>
            <th class="text-left w-32">Rol</th>
            <th class="text-left w-40">Acciones</th>
        </tr>
    </thead>

    <tbody>
        @forelse($usuarios as $usuario)
        <tr class="border-t hover:bg-gray-50">
            <td class="py-3">{{ $usuario->nombres }} {{ $usuario->apellidos }}</td>
            <td>{{ $usuario->email }}</td>
            <td>{{ $usuario->rol ?? 'cliente' }}</td>
            <td class="space-x-3">
                <button class="text-blue-500">Editar</button>
                <button class="text-red-500">Eliminar</button>
            </td>
        </tr>
        @empty
        <tr class="border-t">
            <td colspan="4" class="py-3 text-center text-gray-500">No hay usuarios registrados</td>
        </tr>
        @endforelse
    </tbody>

</table>

</div>

@endsection