<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'producto';

    // 🔥 IMPORTANTE (tu tabla usa id manual)
    protected $primaryKey = 'id';

    public $timestamps = false; // ⚠️ tu tabla no tiene created_at / updated_at

    protected $fillable = [
        'producto_id',
        'talla_id',
        'color_id',
        'precio',
        'costo',
        'stock',
        'tiene_descuento',
        'valor_descuento'
    ];

    // 🔗 RELACIONES

    public function modelo()
    {
        return $this->belongsTo(Modelo::class);
    }

    public function imagen()
    {
        return $this->hasOne(ProductoImagen::class);
    }

    public function variaciones()
    {
        return $this->hasMany(ProductoVariacion::class);
    }
}