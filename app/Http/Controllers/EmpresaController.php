<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmpresaController extends Controller
{
    public function index()
    {
        try {
            $sql = DB::select('select * from empresa');
        } catch (\Throwable $th) {
            //throw $th;
        }
        return view('vistas/empresa/empresa', compact("sql"));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            "nombre" => "required"
        ]);

        try {
            $sql = DB::update('update empresa set nombre=?, descripcion=? ,ubicacion=?, telefono=?, correo=? where id_empresa=?', [
                $request->nombre,
                $request->descripcion,
                $request->ubicacion,
                $request->telefono,
                $request->correo,
                $id
            ]);
            if ($sql == 0) {
                $sql = 1;
            }
        } catch (\Throwable $th) {
            $sql = 0;
        }
        if ($sql == 1) {
            return back()->with('CORRECTO', 'Datos modificados correctamente');
        } else {
            return back()->with('INCORRECTO', 'Error al modificar');
        }
    }

    public function empresaUpdateEmpresa(Request $request)
    {

        $request->validate([
            "foto" => "required|mimes:jpg,jpeg,png",
        ]);

        $logoNombre = DB::select(" select * from empresa limit 1 ");
        $nombre = $logoNombre[0]->foto;

        $ruta = public_path("foto/empresa/" . $nombre);
        try {
            if (unlink($ruta)) {
            } else {
            }
        } catch (\Throwable $th) {
            //throw $th;
        }

        try {
            $file = $request->file("foto");
            $nombreFile = "logo" . "." . $file->guessExtension();
            $ruta = public_path("foto/empresa/" . $nombreFile);
            copy($file, $ruta);
        } catch (\Throwable $th) {
            $nombreFile = "";
        }

        try {
            $sql = DB::update(" update empresa set foto=? ", [
                $nombreFile,
            ]);
            if ($sql == 0) {
                $sql = 1;
            }
        } catch (\Throwable $th) {
            $sql = 0;
        }
        if ($sql == 1) {
            return back()->with("CORRECTO", "Datos modificado correctamente");
        } else {
            return back()->with("INCORRECTO", "Error al modificar");
        }
    }

    public function empresaDeleteEmpresa()
    {
        $logoNombre = DB::select(" select * from empresa limit 1 ");
        $nombre = $logoNombre[0]->foto;
        $ruta = public_path("foto/empresa/" . $nombre);
        try {
            if (unlink($ruta)) {
            } else {
            }
        } catch (\Throwable $th) {
            //throw $th;
        }

        try {
            $sql = DB::update(" update empresa set foto='' ");
            if ($sql == 0) {
                $sql = 1;
            }
        } catch (\Throwable $th) {
            $sql = 0;
        }
        if ($sql == 1) {
            return back()->with("CORRECTO", "Datos modificado correctamente");
        } else {
            return back()->with("INCORRECTO", "Error al modificar");
        }
    }

}
