<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mail\ContactoMailable;
use App\Mail\RecuperarClaveMailable;
use Illuminate\Support\Facades\Mail;

class RecuperarClaveController extends Controller
{
    public function index()
    {
        return view("auth/loginRecuperar");
    }

    public function enviarCorreo(Request $request)
    {
        $request->validate([
            "usuario" => "required"
        ]);
        $usuario = $request->usuario;
        $buscarUsuario = DB::select(" select * from usuario where usuario='$usuario' ");
        if (count($buscarUsuario) <= 0) {
            return back()->with("INCORRECTO", "El usuario '$usuario' no existe");
        } else {
            $mailUsuario = $buscarUsuario[0]->correo;
            $idUsuario = $buscarUsuario[0]->id_usuario;
            $codigo = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            try {
                $ponerCodigo = DB::update("update usuario set codigo='$codigo' where id_usuario=$idUsuario");
            } catch (\Throwable $th) {
                return back()->with("INCORRECTO", "Hubo un error. Por favor, inténtelo de nuevo más tarde ");
            }

            try {
                $correo = new RecuperarClaveMailable($idUsuario, $codigo);
                Mail::to($mailUsuario)->send($correo);
                return redirect()->back()->with("CORRECTO", "Te hemos enviado un enlace al siguiente correo $mailUsuario");
            } catch (\Exception $e) {
                return redirect()->back()->with("INCORRECTO", "No se pudo enviar el correo. Por favor, inténtelo de nuevo más tarde.");
            }
        }
    }

    public function formulario()
    {
        return view("vistas/recuperarClave/vistaForm");
    }

    public function enviarUpdate(Request $request)
    {
        $request->validate([
            "txtid" => "required",
            "txtcodigo" => "required",
            "nueva_contraseña" => "required",
            "confirmar_contraseña" => "required"
        ]);

        $clave = md5($request->confirmar_contraseña);

        if ($request->nueva_contraseña != $request->confirmar_contraseña) {
            return back()->with("INCORRECTO", "Las contraseñas no coinciden");
        }

        $buscar = DB::select(" select * from usuario where id_usuario=$request->txtid and codigo='$request->txtcodigo' ");
        if (count($buscar) <= 0) {
            return back()->with("INCORRECTO", "ERROR, hubo un error al validar los datos");
        }

        try {
            $ponerCodigo = DB::update("update usuario set codigo='', password='$clave' where id_usuario=$request->txtid");
        } catch (\Throwable $th) {
            return back()->with("INCORRECTO", "Hubo un error. Por favor, inténtelo de nuevo más tarde ");
        }

        if ($ponerCodigo == 1) {
            return redirect()->route("login")->with("CORRECTO", "La contraseña fué actualizado exitosamente");
        } else {
            return back()->with("INCORRECTO", "Hubo un error. Por favor, inténtelo de nuevo más tarde ");
        }


        return $request;
    }
}
