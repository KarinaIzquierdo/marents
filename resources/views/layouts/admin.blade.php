<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin - Marents</title>

    {{-- 🔥 VITE --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- 🔥 DATATABLES CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css">

</head>

<body class="bg-gray-100">

<div class="flex min-h-screen">

    {{-- 🔥 SIDEBAR --}}
    <aside class="w-64 bg-[#1f1f1f] text-white flex flex-col">

        <div class="p-6 text-xl font-bold border-b border-white/10">
            Marents
        </div>

        <nav class="flex-1 p-4 space-y-2 text-sm">

            <a href="/admin/dashboard" class="sidebar-item hover:text-white/80">
                Dashboard
            </a>

            <a href="/admin/usuarios" class="sidebar-item hover:text-white/80">
                Usuarios
            </a>

            <a href="/admin/productos" class="sidebar-item hover:text-white/80">
                Productos
            </a>

        </nav>

    </aside>

    {{-- 🔥 MAIN --}}
    <div class="flex-1 flex flex-col">

        {{-- 🔥 TOPBAR --}}
        <header class="bg-white shadow px-6 py-3 flex justify-between items-center">

            <div class="font-semibold text-gray-700">
                Panel administrativo
            </div>

            <div class="flex items-center gap-4">

                <span class="text-sm text-gray-600">
                    {{ auth()->user()->nombres }}
                </span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-red-500 text-sm hover:underline">
                        Salir
                    </button>
                </form>

            </div>

        </header>

        {{-- 🔥 CONTENIDO --}}
        <main class="p-6">
            @yield('content')
        </main>

    </div>

</div>

{{-- 🔥 STACK GLOBAL DE MODALES (CLAVE 💣) --}}
@stack('modals')

</body>
</html>