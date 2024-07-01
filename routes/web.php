<?php

use App\Http\Controllers\CitaController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\EnvioController;
use App\Http\Controllers\EspecialidadController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\PaisesController;
use App\Http\Controllers\ReceptorController;
use App\Http\Controllers\RecuperarClaveController;
use App\Http\Controllers\RemitenteController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ValidacionController;
use App\Models\Remitente;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
    //return redirect()->route("home");
})->name("welcome");

Auth::routes(['verify' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


/* mis rutas */

//ENVIO
Route::resource("envio", EnvioController::class)->middleware("verified");
Route::get("buscar-envios-{id}", [EnvioController::class, "buscarEnvio"])->name("buscar.envio")->middleware('verified');
Route::get("buscar-remitente-{id}", [EnvioController::class, "buscarRemitente"])->name("buscar.remitente")->middleware('verified');
Route::get("buscar-receptor-{id}", [EnvioController::class, "buscarReceptor"])->name("buscar.receptor")->middleware('verified');
Route::get("eliminarTodo-envios", [EnvioController::class, "eliminarTodo"])->name("envio.eliminarTodo")->middleware('verified');
Route::get('reporteEnvio', [EnvioController::class, 'reporteEnvio'])->name('envio.reporteEnvio');


//REMITENTE
Route::resource("remitente", RemitenteController::class)->middleware("verified");
Route::get("buscar-remitente-{id}", [RemitenteController::class, "buscarRemitente"])->name("buscar.remitente")->middleware('verified');
Route::get("eliminarTodo-remitente", [RemitenteController::class, "eliminarTodo"])->name("remitente.eliminarTodo")->middleware('verified');
Route::get('reporteRemitente', [RemitenteController::class, 'reporteRemitente'])->name('remitente.reporteRemitente');


//receptor
Route::resource("receptor", ReceptorController::class)->middleware("verified");
Route::get("buscar-receptor-{id}", [ReceptorController::class, "buscarReceptor"])->name("buscar.receptor")->middleware('verified');
Route::get("eliminarTodo-receptor", [ReceptorController::class, "eliminarTodo"])->name("receptor.eliminarTodo")->middleware('verified');
Route::get('reporteReceptor', [ReceptorController::class, 'reporteReceptor'])->name('receptor.reporteReceptor');

//pagos
Route::get("realizar-pago-{id}", [EnvioController::class, "pagar"])->name("realizar.pago")->middleware('verified');
Route::get("eliminar-pago-{id}", [EnvioController::class, "noPagar"])->name("eliminar.pago")->middleware('verified');
//estado de envios
Route::get("estado-recepcionado-{id}", [EnvioController::class, "ponerRecepcionado"])->name("estado.recepcionado")->middleware('verified');
Route::get("estado-enTransito-{id}", [EnvioController::class, "ponerEnTransito"])->name("estado.enTransito")->middleware('verified');
Route::get("estado-destino-{id}", [EnvioController::class, "ponerDestino"])->name("estado.enDestino")->middleware('verified');
Route::get("estado-entregado-{id}", [EnvioController::class, "ponerEntregado"])->name("estado.entregado")->middleware('verified');




//BUSCAR-DISTRITO-PROVINCIAS-DEPARTAMENTOS-OTROS
Route::get("buscar-provincia-{id}", [PaisesController::class, "buscarProvincia"])->name("buscar.provincia")->middleware('verified');
Route::get("buscar-distrito-{id}", [PaisesController::class, "buscarDistrito"])->name("buscar.distrito")->middleware('verified');

//BUSCAR-DUPLICADOS
Route::get("buscar-numero-{numeroReg}", [ValidacionController::class, "buscarNumero"])->name("buscar.numero")->middleware('verified');

//USUARIO
Route::get('usuario-index', [UsuarioController::class, 'index'])->name('usuario.index')->middleware('verified');
Route::post('usuario-create', [UsuarioController::class, 'create'])->name('usuario.create')->middleware('verified');
Route::post('usuario-update', [UsuarioController::class, 'update'])->name('usuario.update')->middleware('verified');
Route::post('usuario-mod-foto', [UsuarioController::class, 'actualizarFoto'])->name('usuario.actualizarFoto')->middleware('verified');
Route::get('usuario-delete-{id}', [UsuarioController::class, 'delete'])->name('usuario.delete')->middleware('verified');
Route::get('usuario-eliminar-foto-{id}', [UsuarioController::class, 'deleteFoto'])->name('usuario.deleteFoto')->middleware('verified');

//SUCURSALES
Route::resource('sucursal', SucursalController::class)->middleware('verified');



//recuperar contraseña
Route::get("recuperar-clave", [RecuperarClaveController::class, "index"])->name("recuperar.index");
Route::post("recuperar-clave", [RecuperarClaveController::class, "enviarCorreo"])->name("recuperar.enviarCorreo");
Route::get("recuperar-clave-form-{id}-{codigo}", [RecuperarClaveController::class, "formulario"])->name("recuperar.form");
Route::post("recuperar-update", [RecuperarClaveController::class, "enviarUpdate"])->name("recuperar.update");

//empresa
Route::get('empresa-index', [EmpresaController::class, 'index'])->name('empresa.index')->middleware('verified');
Route::post('empresa-update-{id}', [EmpresaController::class, 'update'])->name('empresa.update')->middleware('verified');
Route::post('empresa-cambiar-empresa', [EmpresaController::class, 'empresaUpdateEmpresa'])->name('empresa.updateEmpresa')->middleware('verified');
Route::get('empresa-delete-empresa', [EmpresaController::class, 'empresaDeleteEmpresa'])->name('empresa.deleteEmpresa')->middleware('verified');
Route::post('empresa-cambiar-empresaUgel', [EmpresaController::class, 'empresaUpdateEmpresaUgel'])->name('empresa.updateEmpresaUgel')->middleware('verified');
Route::get('empresa-delete-empresaUgel', [EmpresaController::class, 'empresaDeleteEmpresaUgel'])->name('empresa.deleteEmpresaUgel')->middleware('verified');

//mi perfil
Route::get('perfil-index', [UsuarioController::class, 'perfilIndex'])->name('perfil.index')->middleware('verified');
Route::post('perfil-update', [UsuarioController::class, 'perfilUpdate'])->name('perfil.update')->middleware('verified');
Route::post('perfil-update-perfil', [UsuarioController::class, 'perfilUpdatePerfil'])->name('perfil.updatePerfil')->middleware('verified');
Route::get('perfil-delete-perfil-{id}', [UsuarioController::class, 'perfilDeletePerfil'])->name('perfil.deletePerfil')->middleware('verified');
//mi perfil
Route::get('clave-index', [UsuarioController::class, 'claveIndex'])->name('clave.index')->middleware('verified');
Route::post('clave-update', [UsuarioController::class, 'claveUpdate'])->name('clave.update')->middleware('verified');


//reportes
Route::get('ticket-registroEnvio-{id}', [ReportesController::class, 'ticketRegistro'])->name('pdf.ticketRegistro');


//buscar-envios-clientes
Route::get('buscar-envio', [EnvioController::class, 'buscarEnvioCliente'])->name('buscar.buscarEnvioCliente');