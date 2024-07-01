<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaisesController extends Controller
{
    public function buscarProvincia($id)
    {
        $consulta = DB::select("select * from provincias where idDepa = $id");
        if (count($consulta) > 0) {
            return response()->json([
                'success' => true,
                'datos' => $consulta
            ], 200);
        } else {
            return response()->json([
                'error' => true,
                'message' => 'No se encontraron resultados para la consulta'
            ], 400);
        }
    }

    public function buscarDistrito($id)
    {
        $consulta = DB::select("select * from distritos where idProv = $id");
        if (count($consulta) > 0) {
            return response()->json([
                'success' => true,
                'datos' => $consulta
            ], 200);
        } else {
            return response()->json([
                'error' => true,
                'message' => 'No se encontraron resultados para la consulta'
            ], 400);
        }
    }
}
