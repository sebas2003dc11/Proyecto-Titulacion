<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Envio extends Model
{
    use HasFactory;
    public $table = "envio";
    public $primaryKey = "id_envio";
    public $fillable=[
        "numero_reg","id_remitente","id_receptor","fecha_salida","fecha_recojo","desde_distrito","desde_direccion","hasta_distrito","hasta_direccion",
        "cantidad","descripcion","precio","pago_estado","envio_estado",
    ];
}
