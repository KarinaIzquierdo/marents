<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function indexApi()
    {
        try {
            $users = User::all();
            return response()->json($users, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener usuarios',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroyApi($id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'error' => 'Usuario no encontrado'
                ], 404);
            }

            // Evitar que se elimine el admin principal
            if ($user->rol === 'admin' && $user->id === 1) {
                return response()->json([
                    'error' => 'No se puede eliminar el administrador principal'
                ], 403);
            }

            $user->delete();

            return response()->json([
                'message' => 'Usuario eliminado correctamente'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al eliminar usuario',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
