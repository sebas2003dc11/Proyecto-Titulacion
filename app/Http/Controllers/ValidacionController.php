<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ValidacionController extends Controller
{
    public function buscarNumero($numero)
    {
        $verificar = DB::select(" select count(*) as total from envio where numero_reg='$numero' ");

        if ($verificar[0]->total >= 1) {
            return response()->json([
                'success' => true,
                'respuesta' => "El numero de registro $numero ya existe"
            ], 200);
        } else {
            return response()->json([
                'error' => true,
                'datos' => 'No se encontraron resultados para la consulta'
            ], 400);
        }
    }


    public function buscarRemitente($dni)
    {
        // $verificar = DB::select(" select * from remitente where dni='$dni' ");

        // if (count($verificar) >= 1) {
        //     return response()->json([
        //         'success' => true,
        //         'respuesta' => "El numero de registro $numero ya existe"
        //     ], 200);
        // } else {
        //     return response()->json([
        //         'error' => true,
        //         'datos' => 'No se encontraron resultados para la consulta'
        //     ], 400);
        // }
    }
}
