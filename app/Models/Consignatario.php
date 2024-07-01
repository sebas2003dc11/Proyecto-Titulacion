<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consignatario extends Model
{
    use HasFactory;
    public $table="receptor";
    public $primaryKey="id_receptor";
    public $fillable=[
        "dni","nombre_razon_social","departamento","provincia","distrito","direccion","telefono"
    ];
}
