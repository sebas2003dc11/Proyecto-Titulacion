<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RemitenteController extends Controller
{
    public function index()
    {
        $datos = DB::table('remitente')->orderByDesc("id_remitente")->paginate(10);

        return view("vistas/remitente/listaRemitente", compact("datos"));
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

        $verificarDuplicado = DB::select("select count(*) as 'total' from remitente where dni='$dni' ");
        if ($verificarDuplicado[0]->total >= 1) {
            return back()->with("INCORRECTO", "El DNI $dni ya existe");
        }

        try {
            $sql = DB::insert(" insert into remitente(dni,nombre_razon_social,direccion,telefono)values(?,?,?,?) ",[
                $dni,$nombre,$direccion,$telefono
            ]);
            if ($sql == 0) {
                $sql = 1;
            }
        } catch (\Throwable $th) {
            $sql = 0;
        }
        if ($sql == 1) {
            return back()->with("CORRECTO", "Remitente registrado correctamente");
        } else {
            return back()->with("INCORRECTO", "Error al modificar");
        }
    }

    public function buscarRemitente($id)
    {
        $sql = DB::select(" select * from remitente where dni like'%$id%'  or nombre_razon_social like'%$id%'  limit 10 ");

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
            $sql = DB::delete(" delete from remitente");
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
            $sql = DB::delete(" delete from remitente where id_remitente=$id");
        } catch (\Throwable $th) {
            return back()->with("INCORRECTO", "Error al eliminar, intente nuevamente");
        }


        if ($sql == 1) {
            return redirect()->route("remitente.index")->with("CORRECTO", "Registro eliminado existosamente");
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

        $verificarDuplicado = DB::select("select count(*) as 'total' from remitente where dni='$dni' and id_remitente!=$id ");
        if ($verificarDuplicado[0]->total >= 1) {
            return back()->with("INCORRECTO", "El DNI ya existe");
        }

        try {
            $sql = DB::update(" update remitente set dni='$dni', nombre_razon_social='$nombre', direccion='$direccion', telefono='$telefono' where id_remitente=$id ");
            if ($sql == 0) {
                $sql = 1;
            }
        } catch (\Throwable $th) {
            $sql = 0;
        }
        if ($sql == 1) {
            return back()->with("CORRECTO", "Remitente modificado correctamente");
        } else {
            return back()->with("INCORRECTO", "Error al modificar");
        }
    }

    public function show($id)
    {
        $sql = DB::select(" select * from remitente where id_remitente=$id ");

        return view("vistas/remitente/viewRemitente", compact("sql"))
            ->with("sql", $sql);
    }

    public function reporteRemitente(){
        $datos = DB::select(" select * from remitente ");
        $empresa = DB::select(" select * from empresa limit 1 ");

        $pdf = \App::make('dompdf.wrapper');
        //$pdf->setPaper('a4', 'landscape');//FORMATO HORIZONTAL
        $pdf->loadView('vistas/remitente/pdfRemitente', compact('datos',"empresa"));
        return $pdf->stream("reporte remitentes");
        
    }
}
