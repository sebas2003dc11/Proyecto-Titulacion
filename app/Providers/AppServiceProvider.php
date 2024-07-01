<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $envioHoy = DB::select(" SELECT count(*) as total FROM envio WHERE fecha_salida = CURDATE() ");
        View::share('envioHoy', $envioHoy[0]->total);

        $envioHoyRecepcionado = DB::select(" SELECT count(*) as total FROM envio WHERE fecha_salida = CURDATE() and envio_estado=3; ");
        View::share('envioHoyRecepcionado', $envioHoyRecepcionado[0]->total);

        $envioHoyNoRecepcionado = DB::select(" SELECT count(*) as total FROM envio WHERE fecha_salida = CURDATE() and envio_estado!=3; ");
        View::share('envioHoyNoRecepcionado', $envioHoyNoRecepcionado[0]->total);

        $usuarios = DB::select(" SELECT count(*) as total FROM usuario ");
        View::share('usuarios', $usuarios[0]->total);

        $envios = DB::select(" SELECT count(*) as total FROM envio ");
        View::share('envios', $envios[0]->total);

        $empresa = DB::select(" SELECT * FROM empresa ");
        View::share('empresa', $empresa);

        View::share('foto', $empresa[0]->foto);
        
    }
}
