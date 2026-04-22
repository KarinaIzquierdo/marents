<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Producto;
use App\Models\Modelo;
use App\Models\Categoria;
use App\Models\Color;
use App\Models\Talla;
use App\Models\ProductoVariacion;
use App\Models\ProductoImagen;

class ProductoController extends Controller
{
    //  LISTADO
    public function index()
    {
        $productos = Producto::with([
            'modelo.categoria',
            'imagen',
            'variaciones.talla',
            'variaciones.colorPrimario',
            'variaciones.colorSecundario'
        ])->get(); // 👉 luego puedes usar paginate()

        $categorias = Categoria::all();
        $tallas = Talla::all();

        return view('admin.productos.index', compact(
            'productos',
            'categorias',
            'tallas'
        ));
    }

    //  CREAR PRODUCTO
    public function store(Request $request)
    {
        // ✅ VALIDACIÓN
        $request->validate([
            'modelo_nombre' => 'required|string',
            'categoria_id' => 'required',
            'color_primario' => 'required|string',
            'talla_numero' => 'required|numeric',
            'precio' => 'required|numeric|min:0',
            'costo' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0'
        ]);

        //  MODELO
        $modelo = Modelo::create([
            'nombre' => $request->modelo_nombre,
            'categoria_id' => $request->categoria_id
        ]);

        //  PRODUCTO
        $producto = Producto::create([
            'modelo_id' => $modelo->id
        ]);

        //  COLORES
        $colorPrimario = Color::firstOrCreate([
            'nombre' => $request->color_primario
        ]);

        $colorSecundario = null;

        if ($request->color_secundario) {
            $colorSecundario = Color::firstOrCreate([
                'nombre' => $request->color_secundario
            ]);
        }

        //  TALLA
        $talla = Talla::firstOrCreate([
            'numero' => $request->talla_numero
        ]);

        //  VARIACIÓN (SIN DUPLICADOS)
        ProductoVariacion::updateOrCreate(
            [
                'producto_id' => $producto->id,
                'talla_id' => $talla->id
            ],
            [
                'color_id' => $colorPrimario->id,
                'color_secundario_id' => $colorSecundario?->id,
                'precio' => $request->precio,
                'costo' => $request->costo,
                'stock' => $request->stock,
                'tiene_descuento' => $request->valor_descuento ? true : false,
                'valor_descuento' => $request->valor_descuento
            ]
        );

        //  IMAGEN
        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store('productos', 'public');

            ProductoImagen::create([
                'producto_id' => $producto->id,
                'url' => 'storage/' . $path
            ]);
        }

        return back()->with('success', 'Producto creado');
    }

    //  AGREGAR STOCK A PRODUCTO
    public function agregarStockProducto(Request $request)
    {
        // ✅ VALIDACIÓN
        $request->validate([
            'producto_id' => 'required',
            'talla_id' => 'required',
            'cantidad' => 'required|numeric|min:1'
        ]);

        $variacion = ProductoVariacion::where('producto_id', $request->producto_id)
            ->where('talla_id', $request->talla_id)
            ->first();

        if ($variacion) {

            // ✔ SUMAR STOCK
            $variacion->increment('stock', $request->cantidad);

        } else {

            //  SI NO EXISTE → CREAR

            $color = Color::first();

            if (!$color) {
                $color = Color::create(['nombre' => 'Default']);
            }

            ProductoVariacion::create([
                'producto_id' => $request->producto_id,
                'talla_id' => $request->talla_id,
                'color_id' => $color->id,
                'color_secundario_id' => null,
                'precio' => 0,
                'costo' => 0,
                'stock' => $request->cantidad,
                'tiene_descuento' => 0,
                'valor_descuento' => null
            ]);
        }

        return back()->with('success', 'Stock actualizado');
    }

    //  OBTENER MODELOS POR CATEGORIA
public function getModelos($categoriaId)
{
    return Modelo::where('categoria_id', $categoriaId)->get();
}

//  OBTENER TALLAS POR MODELO
public function getTallas($modeloId)
{
    return Talla::select('id', 'numero')
        ->orderBy('numero')
        ->get();
}
public function getProductoInfo($modeloId)
{
    $producto = Producto::where('modelo_id', $modeloId)
        ->with([
            'imagen',
            'variaciones.talla', // 👈 FALTABA ESTO
            'variaciones.colorPrimario',
            'variaciones.colorSecundario'
        ])
        ->first();

    return response()->json($producto);
}
// OBTENER COLORES POR MODELO
public function getColores($modeloId)
{
    $variaciones = ProductoVariacion::whereHas('producto', function ($q) use ($modeloId) {
        $q->where('modelo_id', $modeloId);
    })
    ->with(['colorPrimario', 'colorSecundario'])
    ->get();

    // 🔥 PRIMARIOS
    $primarios = $variaciones
        ->pluck('colorPrimario')
        ->filter()
        ->unique('id')
        ->values();

    // 🔥 SECUNDARIOS
    $secundarios = $variaciones
        ->pluck('colorSecundario')
        ->filter()
        ->unique('id')
        ->values();

    return response()->json([
        'primarios' => $primarios,
        'secundarios' => $secundarios
    ]);
}

public function agregarStockGlobal(Request $request)
{
    $request->validate([
        'variacion_id' => 'required',
        'cantidad' => 'required|numeric|min:1'
    ]);

    $variacion = ProductoVariacion::find($request->variacion_id);

    if (!$variacion) {
        return back()->with('error', 'Variación no encontrada');
    }

    $variacion->increment('stock', $request->cantidad);

    return back()->with('success', 'Stock actualizado correctamente');
}

public function show($id)
{
    $producto = Producto::with([
        'modelo.categoria',
        'imagen',
        'variaciones.talla',
        'variaciones.colorPrimario'
    ])->findOrFail($id);

    $variaciones = $producto->variaciones;

    // 🔥 COLORES ÚNICOS
    $colores = $variaciones
        ->pluck('colorPrimario')
        ->filter()
        ->unique('id')
        ->values();

    // 🔥 TALLAS (TODAS)
    $tallas = $variaciones
        ->pluck('talla.numero')
        ->unique()
        ->sort()
        ->values();

    // 🔥 PRECIO (puedes mejorar luego)
    $precio = $variaciones->avg('precio');

    return view('producto.show', compact(
        'producto',
        'colores',
        'tallas',
        'precio'
    ));
}















}



