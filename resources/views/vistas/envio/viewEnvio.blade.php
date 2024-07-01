@extends('layouts/app')
@section('titulo', 'Registro de envios')
@section('content')

    <style>
        legend {
            background-color: rgb(79, 227, 136);
            color: rgb(0, 0, 0);
            padding: 7px 12px;
            font-weight: 500;
        }

        legend.etiqueta {
            color: rgb(0, 0, 0);
            padding: 4px 9px;
            width: max-content;
            font-size: 15px;
        }

        .title {
            display: block;
            z-index: 999;
        }

        .checkbox-toggle input:checked+label:before {
            background-color: #00a20e;
            padding: 12px;
            font-size: 40px;
        }

        .checkbox-toggle input+label:before {
            background-color: #f90000;
            padding: 12px;
            font-size: 40px;
        }

        #miLabel {
            font-weight: bold;
        }

        .title {
            position: relative;
            z-index: 999 !important;
        }

        #numero {
            background: rgb(0, 0, 0);
            color: white;
            cursor: default;
            border: none;
        }

        #numero:hover {
            background: black;
            color: white;
        }

        .pagar,
        .noPagar {
            height: max-content !important;
            padding: 10px 20px;
        }
        .error-message {
            position: absolute;
            bottom: -20px;
        }
    </style>



    <h4 class="text-center text-secondary">DATOS DEL ENVIO</h4>
    @if (session('INCORRECTO'))
        <div class="alert alert-danger">{{ session('INCORRECTO') }}</div>
    @endif

    @if (session('CORRECTO'))
        <div class="alert alert-success">{{ session('CORRECTO') }}</div>
    @endif

    @if (session('CORRECTO'))
        <script>
            $(function notificacion() {
                new PNotify({
                    title: "CORRECTO",
                    type: "success",
                    text: "{{ session('CORRECTO') }}",
                    styling: "bootstrap3"
                });
            });
        </script>
    @endif

    @if (session('INCORRECTO'))
        <script>
            $(function notificacion() {
                new PNotify({
                    title: "INCORRECTO",
                    type: "error",
                    text: "{{ session('INCORRECTO') }}",
                    styling: "bootstrap3"
                });
            });
        </script>
    @endif

    <div id="incorrecto"></div>
    <div id="correcto"></div>


    @foreach ($sql as $datos)
        <div class="modal-body bg-light">
            <form action="{{ route('envio.update', $datos->id_envio) }}" id="formMod" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <div class="input-group input-group-lg col-12">
                        <span class="input-group-btn">
                            <button id="numero" class="btn bootstrap-touchspin-down" type="button">Número [Ult. Registro
                                {{ $ultimoRegistro }}]</button>
                        </span>
                        <input readonly required id="cod" type="number" placeholder="N° de orden de envío"
                            name="numero_envio" class="form-control input-lg" style="display: block;"
                            value="{{ old('numero_envio', $datos->numero_reg) }}">
                    </div>
                    @error('numero_envio')
                        <p class="error-message text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <legend>Datos del Remitente</legend>
                    <input type="hidden" name="idRemitente" value="{{ $datos->id_remitente }}">
                    <div class="fl-flex-label mb-4 px-2 col-12 col-md-4">
                        <input required type="number" placeholder="DNI / RUC" class="input input__text"
                            name="dni_del_remitente" value="{{ old('dni_del_remitente', $datos->dniRemitente) }}">
                        @error('dni_del_remitente')
                            <p class="error-message text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="fl-flex-label mb-4 px-2 col-12 col-md-8">
                        <input required type="text" placeholder="Nombre o Razon Social" class="input input__text"
                            name="nombre_del_remitente" value="{{ old('nombre_del_remitente', $datos->nomRemitente) }}">
                        @error('nombre_del_remitente')
                            <p class="error-message text-danger">{{ $message }}</p>
                        @enderror
                    </div>


                    <div class="fl-flex-label mb-4 px-2 col-12 col-md-4">
                        <input type="number" placeholder="Teléfono" class="input input__text" name="telefono_del_remitente"
                            value="{{ old('telefono_del_remitente', $datos->remitenteTelefono) }}">
                        @error('telefono_del_remitente')
                            <p class="error-message text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="fl-flex-label mb-4 px-2 col-12 col-md-8">
                        <input type="text" placeholder="Dirección" class="input input__text"
                            name="direccion_del_remitente"
                            value="{{ old('direccion_del_remitente', $datos->remitenteDireccion) }}">
                        @error('direccion_del_remitente')
                            <p class="error-message text-danger">{{ $message }}</p>
                        @enderror
                    </div>


                </div>

                <div>
                    <legend>Datos del Consignatario</legend>
                    <input type="hidden" name="idReceptor" value="{{ $datos->id_receptor }}">
                    <div class="fl-flex-label mb-4 px-2 col-12 col-md-4">
                        <input required type="number" placeholder="DNI / RUC" class="input input__text"
                            name="dni_del_consignatario" value="{{ old('dni_del_consignatario', $datos->dniReceptor) }}">
                        @error('dni_del_consignatario')
                            <p class="error-message text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="fl-flex-label mb-4 px-2 col-12 col-md-8">
                        <input required type="text" placeholder="Nombre o Razon Social" class="input input__text"
                            name="nombre_del_consignatario"
                            value="{{ old('nombre_del_consignatario', $datos->nomReceptor) }}">
                        @error('nombre_del_consignatario')
                            <p class="error-message text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="fl-flex-label mb-4 px-2 col-12 col-md-4">
                        <input type="number" placeholder="Teléfono" class="input input__text"
                            name="telefono_del_consignatario"
                            value="{{ old('telefono_del_consignatario', $datos->receptorTelefono) }}">
                        @error('telefono_del_consignatario')
                            <p class="error-message text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="fl-flex-label mb-4 px-2 col-12 col-md-8">
                        <input type="text" placeholder="Dirección" class="input input__text"
                            name="direccion_del_consignatario"
                            value="{{ old('direccion_del_consignatario', $datos->receptorDireccion) }}">
                        @error('direccion_del_consignatario')
                            <p class="error-message text-danger">{{ $message }}</p>
                        @enderror
                    </div>


                </div>

                <div>
                    <legend>Lugar de Salida - Llegada</legend>
                    <div>
                        <legend class="etiqueta">Lugar de partida</legend>
                        <div class="fl-flex-label mb-4 px-2 col-12 col-md-4">
                            <label class="title">Departamento</label>
                            <select required class="input input__select" name="departamento_partida"
                                id="departamento_partida">
                                <option value="">Seleccionar...</option>
                                @foreach ($departamento as $item)
                                    <option value="{{ $item->idDepa }}"
                                        {{ $datos->desdeIdDepa == $item->idDepa ? 'selected' : '' }}>
                                        {{ $item->Departamento }}</option>
                                @endforeach
                            </select>
                            @error('departamento_partida')
                                <p class="error-message text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="fl-flex-label mb-4 px-2 col-12 col-md-4">
                            <label class="title">Provincia</label>
                            <select required class="input input__select" name="provincia_partida" id="provincia_partida">
                                <option value="">Seleccionar...</option>
                                @foreach ($provincia as $item)
                                    <option value="{{ $item->idProv }}"
                                        {{ $datos->desdeIdProv == $item->idProv ? 'selected' : '' }}>
                                        {{ $item->Provincia }}</option>
                                @endforeach
                            </select>
                            @error('provincia_partida')
                                <p class="error-message text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="fl-flex-label mb-4 px-2 col-12 col-md-4">
                            <label class="title">Distrito</label>
                            <select required class="input input__select" name="distrito_partida" id="distrito_partida">
                                <option value="">Seleccionar...</option>
                                @foreach ($distrito as $item)
                                    <option value="{{ $item->idDist }}"
                                        {{ $datos->desdeIdDist == $item->idDist ? 'selected' : '' }}>
                                        {{ $item->Distrito }}</option>
                                @endforeach
                            </select>
                            @error('distrito_partida')
                                <p class="error-message text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="fl-flex-label mb-4 px-2 col-12 col-md-12">
                            <input type="text" class="input input__select" name="direccion_partida"
                                placeholder="Dirección" value="{{ old('direccion_partida', $datos->desde_direccion) }}">
                            @error('direccion_partida')
                                <p class="error-message text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <legend class="etiqueta">Lugar de llegada</legend>
                        <div class="fl-flex-label mb-4 px-2 col-12 col-md-4">
                            <label class="title">Departamento</label>
                            <select required class="input input__select" name="departamento_llegada"
                                id="departamento_llegada">
                                <option value="">Seleccionar...</option>
                                @foreach ($departamento as $item)
                                    <option value="{{ $item->idDepa }}"
                                        {{ $datos->hastaIdDepa == $item->idDepa ? 'selected' : '' }}>
                                        {{ $item->Departamento }}</option>
                                @endforeach
                            </select>
                            @error('departamento_llegada')
                                <p class="error-message text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="fl-flex-label mb-4 px-2 col-12 col-md-4">
                            <label class="title">Provincia</label>
                            <select required class="input input__select" name="provincia_llegada" id="provincia_llegada">
                                <option value="">Seleccionar...</option>
                                @foreach ($provincia2 as $item)
                                    <option value="{{ $item->idProv }}"
                                        {{ $datos->hastaIdProv == $item->idProv ? 'selected' : '' }}>
                                        {{ $item->Provincia }}</option>
                                @endforeach
                            </select>
                            @error('provincia_llegada')
                                <p class="error-message text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="fl-flex-label mb-4 px-2 col-12 col-md-4">
                            <label class="title">Distrito</label>
                            <select required class="input input__select" name="distrito_llegada" id="distrito_llegada">
                                <option value="">Seleccionar...</option>
                                @foreach ($distrito2 as $item)
                                    <option value="{{ $item->idDist }}"
                                        {{ $datos->hastaIdDist == $item->idDist ? 'selected' : '' }}>
                                        {{ $item->Distrito }}</option>
                                @endforeach
                            </select>
                            @error('distrito_llegada')
                                <p class="error-message text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="fl-flex-label mb-4 px-2 col-12 col-md-12">
                            <input required type="text" class="input input__select" name="direccion_llegada"
                                placeholder="Dirección" value="{{ old('direccion_llegada', $datos->hasta_direccion) }}">
                            @error('direccion_llegada')
                                <p class="error-message text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                </div>


                <div>
                    <legend>Datos del Envio</legend>

                    <div>
                        <div class="fl-flex-label mb-4 px-2 col-12 col-sm-6 col-md-3">
                            <label class="title">Cantidad</label>
                            <input required class="input input__select" type="number" name="cantidad"
                                value="{{ old('cantidad', $datos->cantidad) }}">
                            @error('cantidad')
                                <p class="error-message text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="fl-flex-label mb-4 px-2 col-12 col-sm-6 col-md-3">
                            <label class="title">Precio</label>
                            <input required class="input input__select" type="text" name="precio"
                                value="{{ old('precio', $datos->precio) }}">
                            @error('precio')
                                <p class="error-message text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="fl-flex-label mb-4 px-2 col-12 col-sm-6 col-md-3">
                            <label class="title">Estado de pago</label>
                            @if ($datos->pago_estado == '1')
                                <span style="color: rgb(21, 181, 0);font-size: 38px;"><i
                                        class="fas fa-toggle-on"></i></span>
                            @else
                                <span style="color: red;font-size: 38px;"><i class="fas fa-toggle-on"></i></span>
                            @endif

                            @error('estado_pago')
                                <p class="error-message text-danger">{{ $message }}</p>
                            @enderror
                        </div>


                        <div class="fl-flex-label mb-4 px-2 col-12 col-sm-6 col-md-3">
                            <label class="title">Fecha de envío</label>
                            <input required class="input input__select" type="date"
                                value="{{ old('fecha_salida', $datos->fecha_salida) }}" name="fecha_salida">
                            @error('fecha_salida')
                                <p class="error-message text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="fl-flex-label mb-4 px-2 col-12 col-md-9">
                            <textarea required name="descripcion" class="input input__select" rows="2" placeholder="Descripcion">{{ old('descripcion', $datos->descripcion) }}</textarea>
                            @error('descripcion')
                                <p class="error-message text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="fl-flex-label mb-4 px-2 col-12 col-sm-6 col-md-3">
                            <label class="title">Fecha de recojo <button type="button" id="hoy"
                                    class="btn btn-secondary btn-sm">HOY</button></label>
                            <input class="input input__select" type="text"
                                value="{{ old('fecha_recojo', $datos->fecha_recojo) }}" name="fecha_recojo"
                                id="fechaRecojo">
                            @error('fecha_recojo')
                                <p class="error-message text-danger">{{ $message }}</p>
                            @enderror
                        </div>


                    </div>
                </div>

            </form>
            <div class="text-right p-2 d-flex justify-content-end flex-wrap gap-2 col-12">
                <a href="{{ route('envio.index') }}" class="btn btn-secondary"><i class="fas fa-caret-left"></i>
                    Atras</a>

                @if ($datos->pago_estado == '1')
                    <form action="{{ route('eliminar.pago', $datos->id_envio) }}" method="get"
                        class="d-inline formulario-noPagar">
                    </form>
                    <a class="btn btn-sm bg-success noPagar"data-id="{{ $datos->id_envio }}"><i
                            class="fas fa-check"></i> PAGADO</a>
                @else
                    <form action="{{ route('realizar.pago', $datos->id_envio) }}" method="get"
                        class="d-inline formulario-pagar">
                    </form>
                    <a class="btn btn-sm bg-danger pagar"data-id="{{ $datos->id_envio }}"><i class="fas fa-times"></i>
                        DEBE</a>
                @endif

                @if ($datos->envio_estado == '1')
                    <a data-toggle="modal" data-target="#estadoEnvio" class="btn bg-primary"><i
                            class="fas fa-people-carry"></i> {{ strtoupper($datos->nombre) }}</a>
                @else
                    @if ($datos->envio_estado == '2')
                        <a data-toggle="modal" data-target="#estadoEnvio" class="btn bg-warning"><i
                                class="fas fa-car-side"></i> {{ strtoupper($datos->nombre) }}</a>
                    @else
                        @if ($datos->envio_estado == '3')
                            <a data-toggle="modal" data-target="#estadoEnvio" class="btn bg-danger"><i
                                    class="fas fa-times"></i> {{ strtoupper($datos->nombre) }}</a>
                        @else
                            @if ($datos->envio_estado == '4')
                                <a data-toggle="modal" data-target="#estadoEnvio" class="btn bg-success"><i
                                        class="fas fa-check"></i> {{ strtoupper($datos->nombre) }}</a>
                            @endif
                        @endif
                    @endif
                @endif


                <form action="{{ route('envio.destroy', $datos->id_envio) }}" method="POST"
                    class="d-inline formulario-eliminar">
                    @csrf
                    @method('DELETE')
                </form>
                <a class="btn btn-danger eliminar" data-id="{{ $datos->id_envio }}"><i class="fas fa-trash-alt"></i>
                    Eliminar Registro</a>

                <a href="{{ route('pdf.ticketRegistro', $datos->id_envio) }}" target="_blank" class="btn btn-primary"><i
                        class="fas fa-ticket-alt"></i>
                    Ticket</a>

                <button form="formMod" type="submit" value="ok" name="btnmodificar" class="btn bg-primary"><i
                        class="fas fa-save"></i> Guardar cambios</button>
            </div>
        </div>


        <!-- Modal para cambiar estado de envio-->
        <div class="modal fade" id="estadoEnvio" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header d-flex justify-content-between">
                        <h5 class="modal-title w-100" id="exampleModalLabel">CAMBIAR ESTADO DE ENVIO</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <a href="{{ route('estado.recepcionado', $datos->id_envio) }}"
                                    class="btn bg-primary">RECEPCIONADO</a>
                            </div>
                            <div class="col-sm-3">
                                <a href="{{ route('estado.enTransito', $datos->id_envio) }}" class="btn bg-warning">EN
                                    TRANSITO</a>
                            </div>
                            <div class="col-sm-3">
                                <a href="{{ route('estado.enDestino', $datos->id_envio) }}" class="btn bg-danger">EN
                                    DESTINO</a>
                            </div>
                            <div class="col-sm-3">
                                <a href="{{ route('estado.entregado', $datos->id_envio) }}"
                                    class="btn bg-success">ENTREGADO</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endforeach


    <script>
        //buscar distrito por ajax
        let departamento_partida = document.getElementById("departamento_partida");
        let provincia_partida = document.getElementById("provincia_partida");
        let distrito_partida = document.getElementById("distrito_partida");


        let departamento_llegada = document.getElementById("departamento_llegada");
        let provincia_llegada = document.getElementById("provincia_llegada");
        let distrito_llegada = document.getElementById("distrito_llegada");

        departamento_partida.addEventListener("change", buscarProvinciaPartida)
        provincia_partida.addEventListener("change", buscarDistritoPartida)

        departamento_llegada.addEventListener("change", buscarProvinciaLlegada)
        provincia_llegada.addEventListener("change", buscarDistritoLlegada)


        function buscarProvinciaPartida() {

            let departamento_partida = document.getElementById("departamento_partida").value;
            var ruta = "{{ url('buscar-provincia') }}-" + departamento_partida + "";

            $.ajax({
                url: ruta,
                type: "get",
                success: function(data) {
                    let option = '';
                    let defecto = `<option value="">Seleccionar...</option>`;
                    data.datos.forEach(function(ele) {
                        option +=
                            `<option value="${ele.idProv}">${ele.Provincia}</option>`
                    });
                    let nuevoOption = `${defecto}${option}`;
                    provincia_partida.innerHTML = nuevoOption;
                },
                error: function(data) {
                    let option = `<option value="">Seleccionar...</option>`;
                    provincia_partida.innerHTML = option;
                }
            })
            buscarDistritoPartida()
        }

        function buscarDistritoPartida() {
            let provincia_partida = document.getElementById("provincia_partida").value;

            var ruta = "{{ url('buscar-distrito') }}-" + provincia_partida + "";

            $.ajax({
                url: ruta,
                type: "get",
                success: function(data) {
                    let option;
                    let defecto = `<option value="">Seleccionar...</option>`;
                    data.datos.forEach(function(ele) {
                        option += `<option value="${ele.idDist}">${ele.Distrito}</option>`
                    });
                    let nuevoOption = `${defecto}${option}`;
                    distrito_partida.innerHTML = nuevoOption;
                },
                error: function(data) {
                    let option = `<option value="">Seleccionar...</option>`;
                    distrito_partida.innerHTML = option;
                }
            })
        }



        function buscarProvinciaLlegada() {

            let departamento_llegada = document.getElementById("departamento_llegada").value;
            var ruta = "{{ url('buscar-provincia') }}-" + departamento_llegada + "";

            $.ajax({
                url: ruta,
                type: "get",
                success: function(data) {
                    let option;
                    let defecto = `<option value="">Seleccionar...</option>`;
                    data.datos.forEach(function(ele) {
                        option += `<option value="${ele.idProv}">${ele.Provincia}</option>`
                    });
                    let nuevoOption = `${defecto}${option}`;
                    provincia_llegada.innerHTML = nuevoOption;
                },
                error: function(data) {
                    let option = `<option value="">Seleccionar...</option>`;
                    provincia_llegada.innerHTML = option;
                }
            })
            buscarDistritoLlegada()
        }

        function buscarDistritoLlegada() {
            let provincia_llegada = document.getElementById("provincia_llegada").value;

            var ruta = "{{ url('buscar-distrito') }}-" + provincia_llegada + "";

            $.ajax({
                url: ruta,
                type: "get",
                success: function(data) {
                    let option;
                    let defecto = `<option value="">Seleccionar...</option>`;
                    data.datos.forEach(function(ele) {
                        option += `<option value="${ele.idDist}">${ele.Distrito}</option>`
                    });
                    let nuevoOption = `${defecto}${option}`;
                    distrito_llegada.innerHTML = nuevoOption;
                },
                error: function(data) {
                    let option = `<option value="">Seleccionar...</option>`;
                    distrito_llegada.innerHTML = option;
                }
            })
        }
    </script>

    <script>
        let hoy = document.getElementById("hoy")
        hoy.addEventListener("click", fechaHoy)

        function fechaHoy() {
            //poner la fecha y hora actual en el input con id fechaRecojo
            let fechaRecojo = document.getElementById("fechaRecojo")
            let fecha = new Date()
            let dia = fecha.getDate()
            let mes = fecha.getMonth() + 1
            let anio = fecha.getFullYear()
            let hora = fecha.getHours()
            let minutos = fecha.getMinutes()
            let segundos = fecha.getSeconds()
            fechaRecojo.value = `${anio}-${mes}-${dia} ${hora}:${minutos}:${segundos}`
        }
    </script>


@endsection
