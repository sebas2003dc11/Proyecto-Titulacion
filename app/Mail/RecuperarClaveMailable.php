<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RecuperarClaveMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = "RESTABLECER CONTRASEÑA"; //ESTO ES EL ASUNTO DEL CORREO
    public $id_usuario; //solo creamos para pasar variables a la vista y ENVIARSELOS
    public $codigo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($id_usuario, $codigo)
    {
        $this->id_usuario = $id_usuario; //ALMACENAMOS LOS DATOS QUE SE RECIBEN PARA MOSTRAR EN LA VISTA
        $this->codigo = $codigo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('vistas/recuperarClave/vistaEnvioCorreo')->with("id_usuario", $this->id_usuario)->with("codigo", $this->codigo);
    }
}
