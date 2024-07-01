<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{

    public function index()
    {
        $sql = DB::select(" SELECT usuario.*,sucursal.nombre,sucursal.direccion,distritos.Distrito,
        provincias.Provincia,departamentos.Departamento FROM usuario
        LEFT JOIN sucursal ON usuario.id_sucursal = sucursal.id_sucursal
        LEFT JOIN distritos ON sucursal.distrito = distritos.idDist
        LEFT JOIN provincias ON distritos.idProv = provincias.idProv
        LEFT JOIN departamentos ON provincias.idDepa = departamentos.idDepa
         ");
        $sucursal = DB::select(" SELECT sucursal.id_sucursal,sucursal.nombre,sucursal.direccion,sucursal.telefono,
        distritos.Distrito,provincias.Provincia,departamentos.Departamento FROM sucursal
        INNER JOIN distritos ON sucursal.distrito = distritos.idDist
        INNER JOIN provincias ON distritos.idProv = provincias.idProv
        INNER JOIN departamentos ON provincias.idDepa = departamentos.idDepa
         ");
        return view("vistas/usuario/usuario", compact("sql","sucursal"));
    }
    public function create(Request $request)
    {
        $request->validate([
            "txtnombre" => "required",
            "sucursal" => "required",
            "txtusuario" => "required",
            "txtclave" => "required",
            "txttelefono" => "required",
            "txtcorreo" => "required",
            "txtfoto" => "mimes:jpeg,png,jpg"
        ]);

        $nombre = $request->txtnombre;
        $sucursal = $request->sucursal;
        $telefono = $request->txttelefono;
        $usuario = $request->txtusuario;
        $clave = md5($request->txtclave);
        $correo = $request->txtcorreo;

        $verificarDuplicado = DB::select("select count(*) as 'total' from usuario where usuario='$usuario' or correo='$correo' ");
        if ($verificarDuplicado[0]->total >= 1) {
            return back()->with("INCORRECTO", "El Usuario o Correo ya existe");
        }

        try {
            $sql = DB::table('usuario')->insertGetId([
                'id_sucursal' => $sucursal,
                'nombres' => $nombre,
                'usuario' => $usuario,
                'password' => $clave,
                'telefono' => $telefono,
                'correo' => $correo,
            ]);
        } catch (\Throwable $th) {
            $sql = 0;
        }

        //guardando la foto en servidor
        try {
            $file = $request->file("txtfoto");
            $nombreFile = $sql . "." . $file->guessExtension();
            $ruta = public_path("foto/usuario/" . $nombreFile);
            copy($file, $ruta);
        } catch (\Throwable $th) {
            $nombreFile = "";
        }


        //actualizamos en campo foto con la url de la foto
        try {
            $actualizar = DB::update(" update usuario set foto = '$nombreFile' where id_usuario=$sql ");
            if ($actualizar == 0) {
                $actualizar = 1;
            }
        } catch (\Throwable $th) {
            $actualizar = 0;
        }


        if ($sql >= 1 and $actualizar == 1) {
            return back()->with("CORRECTO", "Usuario registrado correctamente");
        } else {
            return back()->with("INCORRECTO", "Error al registrar");
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            "txtid" => "required",
            "sucursal" => "required",
            "txtnombre" => "required",
            "txttelefono" => "required",
            "txtusuario" => "required",
        ]);

        $id = $request->txtid;
        $sucursal = $request->sucursal;
        $nombre = $request->txtnombre;
        $telefono = $request->txttelefono;
        $usuario = $request->txtusuario;
        $correo = $request->txtcorreo;

        $verificarDuplicado = DB::select("select count(*) as 'total' from usuario where (usuario='$usuario' and id_usuario!=$id) or (correo='$correo' and id_usuario!=$id) ");
        if ($verificarDuplicado[0]->total >= 1) {
            return back()->with("INCORRECTO", "El Usuario o Correo ya existe");
        }

        try {
            $sql = DB::update(" update usuario set id_sucursal=$sucursal , nombres='$nombre', telefono='$telefono', usuario='$usuario', correo='$correo' where id_usuario=$id ");
            if ($sql == 0) {
                $sql = 1;
            }
        } catch (\Throwable $th) {
            $sql = 0;
        }
        if ($sql == 1) {
            return back()->with("CORRECTO", "Usuario modificado correctamente");
        } else {
            return back()->with("INCORRECTO", "Error al modificar");
        }
    }

    public function delete($id)
    {

        //eliminamos la img anterior
        try {
            $buscarNombre = DB::select(" select foto from usuario where id_usuario=$id ");
            $nomFoto = $buscarNombre[0]->foto;
            $rutaAn = public_path("foto/usuario/" . $nomFoto);
            unlink("$rutaAn");
        } catch (\Throwable $th) {
        }


        try {
            $sql = DB::delete(" delete from usuario where id_usuario=$id ");
        } catch (\Throwable $th) {
            $sql = 0;
        }


        if ($sql == 1) {
            return back()->with("CORRECTO", "Usuario eliminado correctamente");
        } else {
            return back()->with("INCORRECTO", "Error al eliminar");
        }
    }

    public function deleteFoto($id)
    {
        $traerRuta = DB::select("select foto from usuario where id_usuario=$id");
        $nombre = $traerRuta[0]->foto;

        $rutaAn = public_path("foto/usuario/" . $nombre);
        try {
            $eliminar = unlink("$rutaAn");
        } catch (\Throwable $th) {
        }

        $actualizar = DB::update("update usuario set foto='' where id_usuario=$id ");

        if ($actualizar == 1) {
            return back()->with("CORRECTO", "Foto eliminado correctamente");
        } else {
            return back()->with("INCORRECTO", "Error al eliminar");
        }
    }

    public function actualizarFoto(Request $request)
    {
        $request->validate([
            "txtfoto2" => "mimes:jpeg,png,jpg"
        ]);
        $id = $request->txtid;

        //eliminamos la img anterior
        try {
            $buscarNombre = DB::select(" select foto from usuario where id_usuario=$id ");
            $nomFoto = $buscarNombre[0]->foto;
            $rutaAn = public_path("foto/usuario/" . $nomFoto);
            unlink("$rutaAn");
        } catch (\Throwable $th) {
        }

        //guardando la foto en servidor
        try {
            $file = $request->file("txtfoto2");
            $nombreFile = $id . "." . $file->guessExtension();
            $ruta = public_path("foto/usuario/" . $nombreFile);
            copy($file, $ruta);
        } catch (\Throwable $th) {
            $nombreFile = "";
        }


        //actualizamos en campo foto con la url de la foto
        try {
            $actualizar = DB::update(" update usuario set foto = '$nombreFile' where id_usuario=$id ");
            if ($actualizar == 0) {
                $actualizar = 1;
            }
        } catch (\Throwable $th) {
            $actualizar = 0;
        }


        if ($actualizar == 1) {
            return back()->with("CORRECTO", "Usuario modificado correctamente");
        } else {
            return back()->with("INCORRECTO", "Error al modificar");
        }
    }

    public function perfilIndex()
    {
        $id = Auth::user()->id_usuario;
        $sql = DB::select(" select * from usuario where id_usuario=$id ");
        return view("vistas/perfil", compact("sql"));
    }
    public function perfilUpdate(Request $request)
    {
        $request->validate([
            "id" => "required",
            "txtusuario" => "required",
            "txtnombre" => "required",
            "txtcorreo" => "required",
        ]);

        try {
            $sql = DB::update(" update usuario set usuario=?, nombres=?, telefono=?, correo=? where id_usuario=? ", [
                $request->txtusuario,
                $request->txtnombre,
                $request->txttelefono,
                $request->txtcorreo,
                $request->id
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

    public function perfilUpdatePerfil(Request $request)
    {

        $request->validate([
            "id" => "required",
            "foto" => "required|mimes:jpeg,png,jpg",
        ]);

        $id = $request->id;
        //eliminamos la img anterior
        try {
            $buscarNombre = DB::select(" select foto from usuario where id_usuario=$id ");
            $nomFoto = $buscarNombre[0]->foto;
            $rutaAn = public_path("foto/usuario/" . $nomFoto);
            unlink("$rutaAn");
        } catch (\Throwable $th) {
        }


        //guardando la foto en servidor
        try {
            $file = $request->file("foto");
            $nombreFile = $id . "." . $file->guessExtension();
            $ruta = public_path("foto/usuario/" . $nombreFile);
            copy($file, $ruta);
        } catch (\Throwable $th) {
            $nombreFile = "";
        }

        try {
            $sql = DB::update(" update usuario set foto=? where id_usuario=? ", [
                $nombreFile,
                $request->id
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

    public function perfilDeletePerfil($id)
    {


        //eliminamos la img anterior
        try {
            $buscarNombre = DB::select(" select foto from usuario where id_usuario=$id ");
            $nomFoto = $buscarNombre[0]->foto;
            $rutaAn = public_path("foto/usuario/" . $nomFoto);
            unlink("$rutaAn");
        } catch (\Throwable $th) {
        }

        try {
            $sql = DB::update(" update usuario set foto='' where id_usuario=? ", [
                $id
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

    //clave
    public function claveIndex()
    {
        $id = Auth::user()->id_usuario;
        return view("vistas/cambiarClave", compact("id"));
    }

    public function claveUpdate(Request $request)
    {

        $request->validate([
            "claveActual" => "required",
            "claveNuevo" => "required",
        ]);

        $id = Auth::user()->id_usuario;
        $verClaveAn = DB::select(" select password from usuario where id_usuario=$id ");
        $pass = md5($request->claveNuevo);

        if ($verClaveAn[0]->password != md5($request->claveActual)) {
            return back()->with("AVISO", "La contraseña actual es INCORRECTA");
        }
        try {
            $sql = DB::update(" update usuario set password=? where id_usuario=? ", [
                $pass,
                $id
            ]);
            if ($sql == 0) {
                $sql = 1;
            }
        } catch (\Throwable $th) {
            $sql = 0;
        }

        if ($sql == 1) {
            return back()->with("CORRECTO", "Contraseña modificado correctamente");
        } else {
            return back()->with("INCORRECTO", "Error al modificar");
        }
    }
}
