<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportesController extends Controller
{
    public function ticketRegistro($id)
    {
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
        where id_envio=$id ");

        $empresa = DB::select("select * from empresa limit 1");
        

        $pdf = \App::make('dompdf.wrapper');
        $pdf->setPaper([0, 0, 550, 226.77], 'landscape'); //FORMATO HORIZONTAL
        $pdf->loadView('vistas/reportes.ticketEnvio', compact('datos', "empresa"));
        return $pdf->stream("Ticket N° $id");
    }
}
