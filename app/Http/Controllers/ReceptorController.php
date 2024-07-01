<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReceptorController extends Controller
{
    public function index()
    {
        $datos = DB::table('receptor')->orderByDesc("id_receptor")->paginate(10);

        return view("vistas/receptor/listaReceptor", compact("datos"));
    }

    public function store(Request $request)
    {
        $request->validate([
            "dni" => "required",
            "nombre" => "required",
        ]);

        $dni = $request->dni;
        $nombre = $request->nombre;
        $direccion = $request->direccion;
        $telefono = $request->telefono;

        $verificarDuplicado = DB::select("select count(*) as 'total' from receptor where dni='$dni' ");
        if ($verificarDuplicado[0]->total >= 1) {
            return back()->with("INCORRECTO", "El DNI $dni ya existe");
        }

        try {
            $sql = DB::insert(" insert into receptor(dni,nombre_razon_social,direccion,telefono)values(?,?,?,?) ", [
                $dni, $nombre, $direccion, $telefono
            ]);
            if ($sql == 0) {
                $sql = 1;
            }
        } catch (\Throwable $th) {
            $sql = 0;
        }
        if ($sql == 1) {
            return back()->with("CORRECTO", "Consignatario registrado correctamente");
        } else {
            return back()->with("INCORRECTO", "Error al modificar");
        }
    }

    public function buscarReceptor($id)
    {
        $sql = DB::select(" select * from receptor where dni like'%$id%'  or nombre_razon_social like'%$id%'  limit 10 ");

        if (count($sql) > 0) {
            return response()->json([
                'success' => true,
                'datos' => $sql
            ], 200);
        } else {
            return response()->json([
                'error' => true,
                'message' => 'No se encontraron resultados para la consulta'
            ], 400);
        }
    }


    public function eliminarTodo()
    {
        try {
            $sql = DB::delete(" delete from receptor");
        } catch (\Throwable $th) {
            return back()->with("INCORRECTO", "Error al eliminar, intente nuevamente");
        }


        if ($sql >= 1) {
            return back()->with("CORRECTO", "Registro eliminado existosamente");
        } else {
            return back()->with("INCORRECTO", "Error al eliminar, intente nuevamente");
        }
    }

    public function destroy($id)
    {
        try {
            $sql = DB::delete(" delete from receptor where id_receptor=$id");
        } catch (\Throwable $th) {
            return back()->with("INCORRECTO", "Error al eliminar, intente nuevamente");
        }


        if ($sql == 1) {
            return redirect()->route("receptor.index")->with("CORRECTO", "Registro eliminado existosamente");
        } else {
            return back()->with("INCORRECTO", "Error al eliminar, intente nuevamente");
        }
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            "dni" => "required",
            "nombre" => "required",
        ]);

        $dni = $request->dni;
        $nombre = $request->nombre;
        $direccion = $request->direccion;
        $telefono = $request->telefono;

        $verificarDuplicado = DB::select("select count(*) as 'total' from receptor where dni='$dni' and id_receptor!=$id ");
        if ($verificarDuplicado[0]->total >= 1) {
            return back()->with("INCORRECTO", "El DNI ya existe");
        }

        try {
            $sql = DB::update(" update receptor set dni='$dni', nombre_razon_social='$nombre', direccion='$direccion', telefono='$telefono' where id_receptor=$id ");
            if ($sql == 0) {
                $sql = 1;
            }
        } catch (\Throwable $th) {
            $sql = 0;
        }
        if ($sql == 1) {
            return back()->with("CORRECTO", "Receptor modificado correctamente");
        } else {
            return back()->with("INCORRECTO", "Error al modificar");
        }
    }

    public function show($id)
    {
        $sql = DB::select(" select * from receptor where id_receptor=$id ");

        return view("vistas/receptor/viewReceptor", compact("sql"))
            ->with("sql", $sql);
    }

    public function reporteReceptor()
    {
        $datos = DB::select(" select * from receptor ");
        $empresa = DB::select(" select * from empresa limit 1 ");

        $pdf = \App::make('dompdf.wrapper');
        //$pdf->setPaper('a4', 'landscape');//FORMATO HORIZONTAL
        $pdf->loadView('vistas/receptor/pdfReceptor', compact('datos', "empresa"));
        return $pdf->stream("reporte Receptores");
    }
}
