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
        <tr class="border-t hover:bg-gray-50">

            <td class="py-3">Admin</td>
            <td>admin@marents.com</td>
            <td>admin</td>

            <td class="space-x-3">
                <button class="text-blue-500">Editar</button>
                <button class="text-red-500">Eliminar</button>
            </td>

        </tr>
    </tbody>

</table>

</div>

@endsection