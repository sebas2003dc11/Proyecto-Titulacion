<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EnvioController extends Controller
{

    public function index()
    {
        $envio = DB::table('envio')
            ->select('estado_envio.nombre', 'envio.id_envio', 'envio.numero_reg', 'envio.id_remitente', 'envio.id_receptor', 'envio.fecha_salida', 'envio.fecha_recojo', 'envio.desde_distrito', 'envio.desde_direccion', 'envio.hasta_distrito', 'envio.hasta_direccion', 'envio.cantidad', 'envio.descripcion', 'envio.precio', 'envio.pago_estado', 'envio.envio_estado', 'envio.registrado_por', 'envio.recepcionado_por', 'remitente.nombre_razon_social as nomRemitente', 'remitente.dni AS dniRemitente', 'receptor.dni AS dniReceptor', 'receptor.nombre_razon_social as nomReceptor', 'd1.Distrito AS desde_distrito_nombre', 'd2.Distrito AS hasta_distrito_nombre', 'prov1.Provincia AS desde_provincia_nombre', 'prov2.Provincia AS hasta_provincia_nombre', 'depa1.Departamento AS desde_departamento_nombre', 'depa2.Departamento AS hasta_departamento_nombre')
            ->join('remitente', 'envio.id_remitente', '=', 'remitente.id_remitente')
            ->join('receptor', 'envio.id_receptor', '=', 'receptor.id_receptor')
            ->leftJoin('distritos as d1', 'envio.desde_distrito', '=', 'd1.idDist')
            ->leftJoin('distritos as d2', 'envio.hasta_distrito', '=', 'd2.idDist')
            ->leftJoin('provincias as prov1', 'd1.idProv', '=', 'prov1.idProv')
            ->leftJoin('provincias as prov2', 'd2.idProv', '=', 'prov2.idProv')
            ->leftJoin('departamentos as depa1', 'prov1.idDepa', '=', 'depa1.idDepa')
            ->leftJoin('departamentos as depa2', 'prov2.idDepa', '=', 'depa2.idDepa')
            ->join('estado_envio', 'envio.envio_estado', '=', 'estado_envio.id_estado_envio')
            ->orderByDesc("envio.id_envio")
            ->paginate(10);

        return view("vistas/envio/listaEnvio", compact("envio"));
    }


    public function create()
    {
        $departamento = DB::select(" select * from departamentos ");
        $ultimoRegistro = DB::select(" select max(numero_reg) as numero_reg from envio ");

        $id_usuario = Auth::user()->id_usuario;

        $traerDistritoUsuario = DB::select(" SELECT
        usuario.id_usuario, sucursal.nombre, sucursal.direccion,distritos.Distrito,provincias.Provincia,
        departamentos.Departamento,distritos.idDist,provincias.idProv,departamentos.idDepa,
        sucursal.id_sucursal FROM usuario
        INNER JOIN sucursal ON usuario.id_sucursal = sucursal.id_sucursal
        INNER JOIN distritos ON sucursal.distrito = distritos.idDist
        INNER JOIN provincias ON distritos.idProv = provincias.idProv
        INNER JOIN departamentos ON provincias.idDepa = departamentos.idDepa
         where id_usuario=$id_usuario");

        return view("vistas/envio/registroEnvio", compact("departamento", "traerDistritoUsuario"))->with("ultimoRegistro", $ultimoRegistro[0]->numero_reg);
    }


    public function store(Request $request)
    {
        $request->validate(
            [
                "numero_envio" => "required",
                "dni_del_remitente" => ["required", "regex:/^(\d{8}|\d{10}|\d{20})$/"],
                "nombre_del_remitente" => "required",
                "telefono_del_remitente" => ["nullable", "regex:/^\d{9}$/"],
                // "direccion_del_remitente" => "required",
                "dni_del_consignatario" => ["required", "regex:/^(\d{8}|\d{10}|\d{20})$/"],
                "nombre_del_consignatario" => "required",
                "telefono_del_consignatario" => ["nullable", "regex:/^\d{9}$/"],
                // "direccion_del_consignatario" => "required",
                "departamento_partida" => "required",
                "provincia_partida" => "required",
                "distrito_partida" => "required",
                // "direccion_partida" => "required",
                "departamento_llegada" => "required",
                "provincia_llegada" => "required",
                "distrito_llegada" => "required",
                // "direccion_llegada" => "required",
                "cantidad" => "required",
                "precio" => "required",
                // "estado_pago" => "required",
                "fecha_salida" => "required",
                "descripcion" => "required",
            ],
            [
                "numero_envio.required" => "El campo numero de envio es obligatorio",
                "dni_del_remitente.required" => "El campo DNI del remitente es obligatorio",
                "dni_del_remitente.regex" => "Este campo contiene menos de 8 digitos",
                "nombre_del_remitente.required" => "El campo nombre del remitente es obligatorio",
                "telefono_del_remitente.regex" => "El telefono del remitente debe tener 9 digitos",
                "dni_del_consignatario.required" => "El campo DNI del consignatario es obligatorio",
                "dni_del_consignatario.regex" => "Este campo contiene menos de 8 digitos",
                "nombre_del_consignatario.required" => "El campo nombre del consignatario es obligatorio",
                "telefono_del_consignatario.regex" => "El telefono debe tener 9 digitos",
                "departamento_partida.required" => "El campo departamento de partida es obligatorio",
                "provincia_partida.required" => "El campo provincia de partida es obligatorio",
                "distrito_partida.required" => "El campo distrito de partida es obligatorio",
                "departamento_llegada.required" => "El campo departamento de llegada es obligatorio",
                "provincia_llegada.required" => "El campo provincia de llegada es obligatorio",
                "distrito_llegada.required" => "El campo distrito de llegada es obligatorio",
                "cantidad.required" => "El campo cantidad es obligatorio",
                "precio.required" => "El campo precio es obligatorio",
                "fecha_salida.required" => "El campo fecha de salida es obligatorio",
                "descripcion.required" => "El campo descripcion es obligatorio",
            ]
        );
        if ($request->estado_pago == "on") {
            $estadoPago = 1;
        } else {
            $estadoPago = 0;
        }
        $id_usuario = Auth::user()->nombres;

        //verificar si ya existe
        $numero = $request->numero_envio;
        $verificar = DB::select(" select count(*) as total from envio where numero_reg='$numero' ");
        if ($verificar[0]->total >= 1) {
            return back()->with("INCORRECTO", "El numero de registro '$numero' ya existe");
        }

        $consultarRemitente = DB::select("select * from remitente where dni='$request->dni_del_remitente' limit 1");
        if (count($consultarRemitente) >= 1) {
            foreach ($consultarRemitente as $key => $value) {
                $idRemitente = $value->id_remitente;
            }
        } else {
            //registrar remitente
            $insertarRemitente = DB::table('remitente')->insertGetId([
                'dni' => $request->dni_del_remitente,
                'nombre_razon_social' => $request->nombre_del_remitente,
                "direccion" => $request->direccion_del_remitente,
                "telefono" => $request->telefono_del_remitente,
            ]);
            $idRemitente = $insertarRemitente;
        }


        $consultarReceptor = DB::select("select * from receptor where dni='$request->dni_del_consignatario' limit 1");
        if (count($consultarReceptor) >= 1) {
            foreach ($consultarReceptor as $key => $value) {
                $idReceptor = $value->id_receptor;
            }
        } else {
            //registrar receptor
            $insertarReceptor = DB::table('receptor')->insertGetId([
                'dni' => $request->dni_del_consignatario,
                'nombre_razon_social' => $request->nombre_del_consignatario,
                "direccion" => $request->direccion_del_consignatario,
                "telefono" => $request->telefono_del_consignatario,
            ]);
            $idReceptor = $insertarReceptor;
        }



        //registramos envio
        $id_registro = DB::transaction(function () use ($numero, $idRemitente, $idReceptor, $request, $estadoPago, $id_usuario) {
            return DB::table('envio')->insertGetId([
                'numero_reg' => $numero,
                'id_remitente' => $idRemitente,
                'id_receptor' => $idReceptor,
                'fecha_salida' => $request->fecha_salida,
                'fecha_recojo' => '',
                'desde_distrito' => $request->distrito_partida,
                'desde_direccion' => $request->direccion_partida,
                'hasta_distrito' => $request->distrito_llegada,
                'hasta_direccion' => $request->direccion_llegada,
                'cantidad' => $request->cantidad,
                'descripcion' => $request->descripcion,
                'precio' => $request->precio,
                'pago_estado' => $estadoPago,
                'envio_estado' => 1,
                'registrado_por' => $id_usuario,
                'recepcionado_por' => ''
            ]);
        });


        if ($id_registro >= 1) {
            //return redirect()->route("pdf.ticketRegistro", ["id" => $id_registro]);
            return back()->with("REGISTRADO", $id_registro);
        } else {
            return back()->with("INCORRECTO", "Error al registrar, intente nuevamente");
        }
    }


    public function show($id)
    {
        $sql = DB::select(" SELECT
        envio.id_envio,
        envio.numero_reg,
        envio.id_remitente,
        envio.id_receptor,
        date(envio.fecha_salida) as 'fecha_salida',
        envio.fecha_recojo,
        envio.desde_distrito,
        envio.desde_direccion,
        envio.hasta_distrito,
        envio.hasta_direccion,
        envio.cantidad,
        envio.descripcion,
        envio.precio,
        envio.pago_estado,
        envio.envio_estado,
        envio.registrado_por,
        envio.recepcionado_por,
        remitente.nombre_razon_social AS nomRemitente,
        remitente.dni AS dniRemitente,
        remitente.telefono as remitenteTelefono,
        remitente.direccion as remitenteDireccion,
        receptor.telefono as receptorTelefono,
        receptor.direccion as receptorDireccion,
        receptor.dni AS dniReceptor,
        receptor.nombre_razon_social AS nomReceptor,
        d1.Distrito AS desde_distrito_nombre,
        d2.Distrito AS hasta_distrito_nombre,
        prov1.Provincia AS desde_provincia_nombre,
        prov2.Provincia AS hasta_provincia_nombre,
        depa1.Departamento AS desde_departamento_nombre,
        depa2.Departamento AS hasta_departamento_nombre,
        estado_envio.nombre,
        depa2.idDepa as hastaIdDepa,
        depa1.idDepa as desdeIdDepa,
        prov2.idProv as hastaIdProv,
        prov1.idProv as desdeIdProv,
        d2.idDist as hastaIdDist,
        d1.idDist as desdeIdDist
        FROM
        envio
        INNER JOIN remitente ON envio.id_remitente = remitente.id_remitente
        INNER JOIN receptor ON envio.id_receptor = receptor.id_receptor
        LEFT JOIN distritos AS d1 ON envio.desde_distrito = d1.idDist
        LEFT JOIN distritos AS d2 ON envio.hasta_distrito = d2.idDist
        LEFT JOIN provincias AS prov1 ON d1.idProv = prov1.idProv
        LEFT JOIN provincias AS prov2 ON d2.idProv = prov2.idProv
        LEFT JOIN departamentos AS depa1 ON prov1.idDepa = depa1.idDepa
        LEFT JOIN departamentos AS depa2 ON prov2.idDepa = depa2.idDepa
        INNER JOIN estado_envio ON envio.envio_estado = estado_envio.id_estado_envio
        where id_envio=$id ");
        $departamento = DB::select(" select * from departamentos ");
        $provincia = DB::select(" select * from provincias where idDepa=? ", [$sql[0]->desdeIdDepa]);
        $distrito = DB::select(" select * from distritos where idProv=? ", [$sql[0]->desdeIdProv]);

        $provincia2 = DB::select(" select * from provincias where idDepa=? ", [$sql[0]->hastaIdDepa]);
        $distrito2 = DB::select(" select * from distritos where idProv=? ", [$sql[0]->hastaIdProv]);
        $ultimoRegistro = DB::select(" select max(numero_reg) as numero_reg from envio ");

        return view("vistas/envio/viewEnvio", compact("departamento"))
            ->with("sql", $sql)
            ->with("ultimoRegistro", $ultimoRegistro[0]->numero_reg)
            ->with("provincia", $provincia)
            ->with("distrito", $distrito)
            ->with("provincia2", $provincia2)
            ->with("distrito2", $distrito2);
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        $request->validate(
            [
                "idRemitente" => "required",
                "idReceptor" => "required",
                "dni_del_remitente" => ["required", "regex:/^(\d{8}|\d{10}|\d{20})$/"],
                "nombre_del_remitente" => "required",
                "dni_del_consignatario" => ["required", "regex:/^(\d{8}|\d{10}|\d{20})$/"],
                "nombre_del_consignatario" => "required",
                "distrito_partida" => "required",
                "direccion_llegada" => "required",
                "distrito_llegada" => "required",
                "cantidad" => "required",
                "precio" => "required",
                "fecha_salida" => "required",
                "descripcion" => "required",
                "telefono_del_remitente" => ["nullable", "regex:/^\d{9}$/"],
                "telefono_del_consignatario" => ["nullable", "regex:/^\d{9}$/"],
            ],
            [
                "idRemitente.required" => "El campo idRemitente es obligatorio",
                "idReceptor.required" => "El campo idReceptor es obligatorio",
                "dni_del_remitente.required" => "El campo DNI del remitente es obligatorio",
                "dni_del_remitente.regex" => "Este campo solo permite 8, 10 o 20 digitos",
                "nombre_del_remitente.required" => "El campo nombre del remitente es obligatorio",
                "dni_del_consignatario.required" => "El campo DNI del consignatario es obligatorio",
                "dni_del_consignatario.regex" => "Este campo solo permite 8, 10 o 20 digitos",
                "nombre_del_consignatario.required" => "El campo nombre del consignatario es obligatorio",
                "distrito_partida.required" => "El campo distrito de partida es obligatorio",
                "direccion_llegada.required" => "El campo direccion de llegada es obligatorio",
                "distrito_llegada.required" => "El campo distrito de llegada es obligatorio",
                "cantidad.required" => "El campo cantidad es obligatorio",
                "precio.required" => "El campo precio es obligatorio",
                "fecha_salida.required" => "El campo fecha de salida es obligatorio",
                "descripcion.required" => "El campo descripcion es obligatorio",
                "telefono_del_remitente.regex" => "El telefono del remitente debe tener 9 digitos",
                "telefono_del_consignatario.regex" => "El telefono del consignatario debe tener 9 digitos",
            ]
        );

        $idRemitente = $request->idRemitente;
        $idReceptor = $request->idReceptor;

        $consultarRemitente = DB::select("select * from remitente where dni='$request->dni_del_remitente' limit 1");
        if (count($consultarRemitente) <= 0) {
            $insertarRemitente = DB::table('remitente')->insertGetId([
                'dni' => $request->dni_del_remitente,
                'nombre_razon_social' => $request->nombre_del_remitente,
                "direccion" => $request->direccion_del_remitente,
                "telefono" => $request->telefono_del_remitente,
            ]);
            $idRemitente = $insertarRemitente;
        } else {
            //buscando e dni antiguo
            $consultarDNIAntiguoRemitente = DB::select("select dni from remitente where id_remitente=$idRemitente  ");
            if ($consultarRemitente[0]->dni == $consultarDNIAntiguoRemitente[0]->dni) {
                $actualizarRemitente = DB::update("update remitente set nombre_razon_social=?, direccion=?, telefono=? where id_remitente=$idRemitente ", [
                    $request->nombre_del_remitente, $request->direccion_del_remitente, $request->telefono_del_remitente
                ]);
            } else {
                $actualizarRemitente = DB::update("update remitente set nombre_razon_social=?, direccion=?, telefono=? where dni=$request->dni_del_remitente ", [
                    $request->nombre_del_remitente, $request->direccion_del_remitente, $request->telefono_del_remitente
                ]);
                $idRemitente = $consultarRemitente[0]->id_remitente;
            }
        }


        $consultarReceptor = DB::select("select * from receptor where dni='$request->dni_del_consignatario' limit 1");
        if (count($consultarReceptor) <= 0) {
            //registrar receptor
            $insertarReceptor = DB::table('receptor')->insertGetId([
                'dni' => $request->dni_del_consignatario,
                'nombre_razon_social' => $request->nombre_del_consignatario,
                "direccion" => $request->direccion_del_consignatario,
                "telefono" => $request->telefono_del_consignatario,
            ]);
            $idReceptor = $insertarReceptor;
        } else {
            //buscando e dni antiguo
            $consultarDNIAntiguoReceptor = DB::select("select dni from receptor where id_receptor=$idReceptor  ");
            if ($consultarReceptor[0]->dni == $consultarDNIAntiguoReceptor[0]->dni) {
                $actualizarReceptor = DB::update("update receptor set nombre_razon_social=?, direccion=?, telefono=? where id_receptor=$idReceptor ", [
                    $request->nombre_del_consignatario, $request->direccion_del_consignatario, $request->telefono_del_consignatario
                ]);
            } else {
                $actualizarReceptor = DB::update("update receptor set nombre_razon_social=?, direccion=?, telefono=? where dni=$request->dni_del_consignatario ", [
                    $request->nombre_del_consignatario, $request->direccion_del_consignatario, $request->telefono_del_consignatario
                ]);
                $idReceptor = $consultarReceptor[0]->id_receptor;
            }
        }


        try {
            $actualizar = DB::update(" update envio set id_remitente=?, id_receptor=?, fecha_salida=?, fecha_recojo=?, desde_distrito=?, desde_direccion=?, hasta_distrito=?, hasta_direccion=?, cantidad=?, descripcion=?, precio=? where id_envio=$id ", [
                $idRemitente, $idReceptor, $request->fecha_salida, $request->fecha_recojo, $request->distrito_partida,
                $request->direccion_partida, $request->distrito_llegada, $request->direccion_llegada, $request->cantidad,
                $request->descripcion, $request->precio
            ]);
            $actualizar = 1;
        } catch (\Throwable $th) {
            $actualizar = 0;
        }

        if ($actualizar == 1) {
            return back()->with("CORRECTO", "Los datos fueron actualizados exitosamente");
        } else {
            return back()->with("INCORRECTO", "Error al actualizar, intente nuevamente");
        }
    }


    public function destroy($id)
    {
        try {
            $sql = DB::delete(" delete from envio where id_envio=$id ");
        } catch (\Throwable $th) {
            return back()->with("INCORRECTO", "Error al eliminar, intente nuevamente");
        }


        if ($sql >= 1) {
            return redirect()->route("envio.index")->with("CORRECTO", "Registro eliminado existosamente");
        } else {
            return back()->with("INCORRECTO", "Error al eliminar, intente nuevamente");
        }
    }

    public function buscarEnvio($id)
    {
        $sql = DB::select(" SELECT
        envio.*,
        remitente.nombre_razon_social AS nomRemitente,
        remitente.dni AS dniRemitente,
        receptor.dni AS dniReceptor,
        receptor.nombre_razon_social AS nomReceptor,
        d1.Distrito AS desde_distrito_nombre,
        d2.Distrito AS hasta_distrito_nombre,
        prov1.Provincia AS desde_provincia_nombre,
        prov2.Provincia AS hasta_provincia_nombre,
        depa1.Departamento AS desde_departamento_nombre,
        depa2.Departamento AS hasta_departamento_nombre,
        estado_envio.nombre
        FROM
        envio
        INNER JOIN remitente ON envio.id_remitente = remitente.id_remitente
        INNER JOIN receptor ON envio.id_receptor = receptor.id_receptor
        LEFT JOIN distritos AS d1 ON envio.desde_distrito = d1.idDist
        LEFT JOIN distritos AS d2 ON envio.hasta_distrito = d2.idDist
        LEFT JOIN provincias AS prov1 ON d1.idProv = prov1.idProv
        LEFT JOIN provincias AS prov2 ON d2.idProv = prov2.idProv
        LEFT JOIN departamentos AS depa1 ON prov1.idDepa = depa1.idDepa
        LEFT JOIN departamentos AS depa2 ON prov2.idDepa = depa2.idDepa
        INNER JOIN estado_envio ON envio.envio_estado = estado_envio.id_estado_envio
        where numero_reg like'%$id%' or remitente.dni like '%$id%' or receptor.dni like'%$id%' or remitente.nombre_razon_social like'%$id%' or receptor.nombre_razon_social like'%$id%' limit 10 ");

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

    public function pagar($id)
    {
        try {
            $sql = DB::update(" update envio set pago_estado=1 where id_envio=$id ");
        } catch (\Throwable $th) {
            //throw $th;
        }

        if ($sql == 1) {
            return back()->with("CORRECTO", "El pago se realizo con éxito");
        } else {
            return back()->with("INCORRECTO", "Error al realizar pago, intente nuevamente");
        }
    }

    public function noPagar($id)
    {
        try {
            $sql = DB::update(" update envio set pago_estado=0 where id_envio=$id ");
        } catch (\Throwable $th) {
            //throw $th;
        }

        if ($sql == 1) {
            return back()->with("CORRECTO", "El pago se eliminó con éxito");
        } else {
            return back()->with("INCORRECTO", "Error al eliminar pago, intente nuevamente");
        }
    }

    public function ponerRecepcionado($id)
    {
        try {
            $sql = DB::update(" update envio set envio_estado=1 where id_envio=$id ");
        } catch (\Throwable $th) {
            //throw $th;
        }

        if ($sql == 1) {
            return back()->with("CORRECTO", "Cambios realizados exitosamente");
        } else {
            return back()->with("INCORRECTO", "Error al actualizar pago, intente nuevamente");
        }
    }

    public function ponerEnTransito($id)
    {
        try {
            $sql = DB::update(" update envio set envio_estado=2 where id_envio=$id ");
        } catch (\Throwable $th) {
            //throw $th;
        }

        if ($sql == 1) {
            return back()->with("CORRECTO", "Cambios realizados exitosamente");
        } else {
            return back()->with("INCORRECTO", "Error al actualizar pago, intente nuevamente");
        }
    }

    public function ponerDestino($id)
    {
        try {
            $sql = DB::update(" update envio set envio_estado=3 where id_envio=$id ");
        } catch (\Throwable $th) {
            //throw $th;
        }

        if ($sql == 1) {
            return back()->with("CORRECTO", "Cambios realizados exitosamente");
        } else {
            return back()->with("INCORRECTO", "Error al actualizar pago, intente nuevamente");
        }
    }

    public function ponerEntregado($id)
    {
        //verificar que la fecha_recojo no sea null
        $sql = DB::select(" select fecha_recojo from envio where id_envio=$id ");
        if ($sql[0]->fecha_recojo == null) {
            return back()->with("INCORRECTO", "Ingrese la fecha de recojo");
        }

        //verificar que el pago este realizado
        $sql = DB::select(" select pago_estado from envio where id_envio=$id ");
        if ($sql[0]->pago_estado == 0) {
            return back()->with("INCORRECTO", "Aún no se realizo el pago");
        }

        try {
            $fecha = date("Y-m-d h:i:s");
            $sql = DB::update(" update envio set envio_estado=4, fecha_recojo='$fecha' where id_envio=$id ");
        } catch (\Throwable $th) {
            //throw $th;
        }


        if ($sql == 1) {
            return back()->with("CORRECTO", "Cambios realizados exitosamente");
        } else {
            return back()->with("INCORRECTO", "Error al actualizar pago, intente nuevamente");
        }
    }

    public function eliminarTodo()
    {
        try {
            $sql = DB::delete(" delete from envio ");
        } catch (\Throwable $th) {
            return back()->with("INCORRECTO", "Error al eliminar, intente nuevamente");
        }


        if ($sql >= 1) {
            return back()->with("CORRECTO", "Registro eliminado existosamente");
        } else {
            return back()->with("INCORRECTO", "Error al eliminar, intente nuevamente");
        }
    }

    public function buscarRemitente($id)
    {
        $sql = DB::select("select * from remitente where dni='$id' limit 1");
        if (count($sql) >= 1) {
            return response()->json([
                "success" => true,
                "datos" => $sql
            ], 200);
        } else {
            return response()->json([
                "error" => true,
                "incorrecto" => "No se encontraron resultados"
            ]);
        }
    }

    public function buscarReceptor($id)
    {
        $sql = DB::select("select * from receptor where dni='$id' limit 1");
        if (count($sql) >= 1) {
            return response()->json([
                "success" => true,
                "datos" => $sql
            ], 200);
        } else {
            return response()->json([
                "error" => true,
                "incorrecto" => "No se encontraron resultados"
            ]);
        }
    }

    public function reporteEnvio()
    {
        $datos = $sql = DB::select(" SELECT
        envio.id_envio,
        envio.numero_reg,
        envio.id_remitente,
        envio.id_receptor,
        date(envio.fecha_salida) as 'fecha_salida',
        envio.fecha_recojo,
        envio.desde_distrito,
        envio.desde_direccion,
        envio.hasta_distrito,
        envio.hasta_direccion,
        envio.cantidad,
        envio.descripcion,
        envio.precio,
        envio.pago_estado,
        envio.envio_estado,
        envio.registrado_por,
        envio.recepcionado_por,
        remitente.nombre_razon_social AS nomRemitente,
        remitente.dni AS dniRemitente,
        remitente.telefono as remitenteTelefono,
        remitente.direccion as remitenteDireccion,
        receptor.telefono as receptorTelefono,
        receptor.direccion as receptorDireccion,
        receptor.dni AS dniReceptor,
        receptor.nombre_razon_social AS nomReceptor,
        d1.Distrito AS desde_distrito_nombre,
        d2.Distrito AS hasta_distrito_nombre,
        prov1.Provincia AS desde_provincia_nombre,
        prov2.Provincia AS hasta_provincia_nombre,
        depa1.Departamento AS desde_departamento_nombre,
        depa2.Departamento AS hasta_departamento_nombre,
        estado_envio.nombre,
        depa2.idDepa as hastaIdDepa,
        depa1.idDepa as desdeIdDepa,
        prov2.idProv as hastaIdProv,
        prov1.idProv as desdeIdProv,
        d2.idDist as hastaIdDist,
        d1.idDist as desdeIdDist
        FROM
        envio
        INNER JOIN remitente ON envio.id_remitente = remitente.id_remitente
        INNER JOIN receptor ON envio.id_receptor = receptor.id_receptor
        LEFT JOIN distritos AS d1 ON envio.desde_distrito = d1.idDist
        LEFT JOIN distritos AS d2 ON envio.hasta_distrito = d2.idDist
        LEFT JOIN provincias AS prov1 ON d1.idProv = prov1.idProv
        LEFT JOIN provincias AS prov2 ON d2.idProv = prov2.idProv
        LEFT JOIN departamentos AS depa1 ON prov1.idDepa = depa1.idDepa
        LEFT JOIN departamentos AS depa2 ON prov2.idDepa = depa2.idDepa
        INNER JOIN estado_envio ON envio.envio_estado = estado_envio.id_estado_envio ");

        $empresa = DB::select(" select * from empresa limit 1 ");

        $pdf = \App::make('dompdf.wrapper');
        $pdf->setPaper('a4', 'landscape'); //FORMATO HORIZONTAL
        $pdf->loadView('vistas/envio/pdfEnvio', compact('datos', "empresa"));
        return $pdf->stream("reporte de envios");
    }



    //busquedas de envios de clientes
    public function buscarEnvioCliente(Request $request)
    {
        $request->validate([
            "numero" => "required",
        ]);
        $datos = DB::select(" SELECT
        envio.id_envio,
        envio.numero_reg,
        envio.id_remitente,
        envio.id_receptor,
        date(envio.fecha_salida) as 'fecha_salida',
        envio.fecha_recojo,
        envio.desde_distrito,
        envio.desde_direccion,
        envio.hasta_distrito,
        envio.hasta_direccion,
        envio.cantidad,
        envio.descripcion,
        envio.precio,
        envio.pago_estado,
        envio.envio_estado,
        envio.registrado_por,
        envio.recepcionado_por,
        remitente.nombre_razon_social AS nomRemitente,
        remitente.dni AS dniRemitente,
        remitente.telefono as remitenteTelefono,
        remitente.direccion as remitenteDireccion,
        receptor.telefono as receptorTelefono,
        receptor.direccion as receptorDireccion,
        receptor.dni AS dniReceptor,
        receptor.nombre_razon_social AS nomReceptor,
        d1.Distrito AS desde_distrito_nombre,
        d2.Distrito AS hasta_distrito_nombre,
        prov1.Provincia AS desde_provincia_nombre,
        prov2.Provincia AS hasta_provincia_nombre,
        depa1.Departamento AS desde_departamento_nombre,
        depa2.Departamento AS hasta_departamento_nombre,
        estado_envio.nombre,
        depa2.idDepa as hastaIdDepa,
        depa1.idDepa as desdeIdDepa,
        prov2.idProv as hastaIdProv,
        prov1.idProv as desdeIdProv,
        d2.idDist as hastaIdDist,
        d1.idDist as desdeIdDist
        FROM
        envio
        INNER JOIN remitente ON envio.id_remitente = remitente.id_remitente
        INNER JOIN receptor ON envio.id_receptor = receptor.id_receptor
        LEFT JOIN distritos AS d1 ON envio.desde_distrito = d1.idDist
        LEFT JOIN distritos AS d2 ON envio.hasta_distrito = d2.idDist
        LEFT JOIN provincias AS prov1 ON d1.idProv = prov1.idProv
        LEFT JOIN provincias AS prov2 ON d2.idProv = prov2.idProv
        LEFT JOIN departamentos AS depa1 ON prov1.idDepa = depa1.idDepa
        LEFT JOIN departamentos AS depa2 ON prov2.idDepa = depa2.idDepa
        INNER JOIN estado_envio ON envio.envio_estado = estado_envio.id_estado_envio
        where numero_reg=$request->numero ");

        $empresa = DB::select("select * from empresa limit 1");

        if (count($datos) <= 0) {
            return back()->with("INCORRECTO", "No se encontraron resultados");
        } else {
            return view("vistas/resultadoBusqueda")->with("datos", $datos)->with("empresa", $empresa);
        }
    }
}
