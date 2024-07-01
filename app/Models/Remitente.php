<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Remitente extends Model
{
    use HasFactory;
    public $table="remitente";
    public $primaryKey="id_remitente";
    public $fillable=[
        "dni","nombre_razon_social","departamento","provincia","distrito","direccion","telefono"
    ];
}
