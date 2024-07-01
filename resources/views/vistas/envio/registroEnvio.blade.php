@extends('layouts/app')
@section('titulo', 'Registro de envios')
@section('content')

    {{-- estilos --}}

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

        .error-message {
            position: absolute;
            bottom: -20px;
        }
    </style>


    {{-- scripts --}}


    <h4 class="text-center text-secondary">REGISTRAR NUEVO ENVIO</h4>
    @if (session('INCORRECTO'))
        <div class="alert alert-danger">{{ session('INCORRECTO') }}</div>
    @endif

    @if (session('CORRECTO'))
        <div class="alert alert-success">{{ session('CORRECTO') }}</div>
    @endif

    @if (session('REGISTRADO'))
        <script>
            $(function notificacion() {
                new PNotify({
                    title: "REGISTRADO",
                    type: "success",
                    text: "En envio fue registrado exitosamente",
                    styling: "bootstrap3"
                });
            });

            setTimeout(() => {
                var url = "{{ route('pdf.ticketRegistro', ['id' => session('REGISTRADO')]) }}";
                window.window.open(url, '_blank');

            }, 1000);
        </script>
    @endif

    <div id="incorrecto"></div>
    <div id="correcto"></div>


    <div class="modal-body bg-light">
        <form action="{{ route('envio.store') }}" method="POST" id="miForm">
            @csrf

            <div class="form-group">
                <div class="input-group input-group-lg col-12">
                    <span class="input-group-btn">
                        <button id="numero" class="btn bootstrap-touchspin-down" type="button">Número [Ult. Registro
                            {{ $ultimoRegistro }}]</button>
                    </span>
                    <input required id="cod" type="number" placeholder="N° de orden de envío" name="numero_envio"
                        class="form-control input-lg" style="display: block;"
                        value="{{ old('numero_envio', $ultimoRegistro + 1) }}">
                </div>
                @error('numero_envio')
                    <p class="error-message text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <legend>Datos del Remitente</legend>
                <div class="fl-flex-label mb-4 px-2 col-12 col-md-4">
                    <label class="title">DNI / RUC</label>
                    <input required type="number" id="dniRemitente" placeholder="" class="input input__text"
                        name="dni_del_remitente" value="{{ old('dni_del_remitente') }}">
                    @error('dni_del_remitente')
                        <p class="error-message text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="fl-flex-label mb-4 px-2 col-12 col-md-8">
                    <label class="title">Nombre o Razon Social</label>
                    <input required type="text" id="nombreRemitente" placeholder="" class="input input__text"
                        name="nombre_del_remitente" value="{{ old('nombre_del_remitente') }}">
                    @error('nombre_del_remitente')
                        <p class="error-message text-danger">{{ $message }}</p>
                    @enderror
                </div>


                <div class="fl-flex-label mb-4 px-2 col-12 col-md-4">
                    <label class="title">Teléfono</label>
                    <input type="number" id="telefonoRemitente" placeholder="" class="input input__text"
                        name="telefono_del_remitente" value="{{ old('telefono_del_remitente') }}">
                    @error('telefono_del_remitente')
                        <p class="error-message text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="fl-flex-label mb-4 px-2 col-12 col-md-8">
                    <label class="title">Dirección</label>
                    <input type="text" id="direccionRemitente" placeholder="" class="input input__text"
                        name="direccion_del_remitente" value="{{ old('direccion_del_remitente') }}">
                    @error('direccion_del_remitente')
                        <p class="error-message text-danger">{{ $message }}</p>
                    @enderror
                </div>


            </div>

            <div>
                <legend>Datos del Consignatario</legend>
                <div class="fl-flex-label mb-4 px-2 col-12 col-md-4">
                    <label class="title">DNI / RUC</label>
                    <input required id="dniReceptor" type="number" class="input input__text" name="dni_del_consignatario"
                        value="{{ old('dni_del_consignatario') }}">
                    @error('dni_del_consignatario')
                        <p class="error-message text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="fl-flex-label mb-4 px-2 col-12 col-md-8">
                    <label class="title">Nombre o Razon Social</label>
                    <input required id="nombreReceptor" type="text" placeholder="" class="input input__text"
                        name="nombre_del_consignatario" value="{{ old('nombre_del_consignatario') }}">
                    @error('nombre_del_consignatario')
                        <p class="error-message text-danger">{{ $message }}</p>
                    @enderror
                </div>


                <div class="fl-flex-label mb-4 px-2 col-12 col-md-4">
                    <label class="title">Teléfono</label>
                    <input type="number" id="telefonoReceptor" placeholder="" class="input input__text"
                        name="telefono_del_consignatario" value="{{ old('telefono_del_consignatario') }}">
                    @error('telefono_del_consignatario')
                        <p class="error-message text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="fl-flex-label mb-4 px-2 col-12 col-md-8">
                    <label class="title">Dirección</label>
                    <input type="text" id="direccionReceptor" placeholder="" class="input input__text"
                        name="direccion_del_consignatario" value="{{ old('direccion_del_consignatario') }}">
                    @error('direccion_del_consignatario')
                        <p class="error-message text-danger">{{ $message }}</p>
                    @enderror
                </div>


            </div>

            <div>
                <legend>Lugar de Salida - Llegada</legend>


                @if (count($traerDistritoUsuario) <= 0)
                    <div>
                        <legend class="etiqueta">Lugar de partida</legend>
                        <div class="fl-flex-label mb-4 px-2 col-12 col-md-4">
                            <label class="title">Departamento</label>
                            <select required class="input input__select" name="departamento_partida"
                                id="departamento_partida">
                                <option value="">Seleccionar...</option>
                                @foreach ($departamento as $item)
                                    <option value="{{ $item->idDepa }}"
                                        {{ old('departamento_partida') == $item->idDepa ? 'selected' : '' }}>
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
                                <option value="">
                                </option>
                            </select>
                            @error('provincia_partida')
                                <p class="error-message text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="fl-flex-label mb-4 px-2 col-12 col-md-4">
                            <label class="title">Distrito</label>
                            <select required class="input input__select" name="distrito_partida" id="distrito_partida">
                                <option value="">
                                </option>
                            </select>
                            @error('distrito_partida')
                                <p class="error-message text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="fl-flex-label mb-4 px-2 col-12 col-md-12">
                            <input type="text" class="input input__select block" name="direccion_partida"
                                placeholder="Dirección" value="{{ old('direccion_partida') }}">
                            @error('direccion_partida')
                                <p class="error-message text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                @else
                    @foreach ($traerDistritoUsuario as $usuarioUbicacion)
                        <div>
                            <legend class="etiqueta">Lugar de partida</legend>
                            <div class="fl-flex-label mb-4 px-2 col-12 col-md-4">
                                <label class="title">Departamento</label>
                                <select required class="input input__select" name="departamento_partida"
                                    id="departamento_partida">
                                    <option value="">Seleccionar...</option>
                                    @foreach ($departamento as $item)
                                        <option {{ $usuarioUbicacion->idDepa == $item->idDepa ? 'selected' : '' }}
                                            value="{{ $item->idDepa }}"
                                            {{ old('departamento_partida') == $item->idDepa ? 'selected' : '' }}>
                                            {{ $item->Departamento }}</option>
                                    @endforeach
                                </select>
                                @error('departamento_partida')
                                    <p class="error-message text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="fl-flex-label mb-4 px-2 col-12 col-md-4">
                                <label class="title">Provincia</label>
                                <select required class="input input__select" name="provincia_partida"
                                    id="provincia_partida">
                                    <option value="{{ $usuarioUbicacion->idProv }}">{{ $usuarioUbicacion->Provincia }}
                                    </option>
                                </select>
                                @error('provincia_partida')
                                    <p class="error-message text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="fl-flex-label mb-4 px-2 col-12 col-md-4">
                                <label class="title">Distrito</label>
                                <select required class="input input__select" name="distrito_partida"
                                    id="distrito_partida">
                                    <option value="{{ $usuarioUbicacion->idDist }}">{{ $usuarioUbicacion->Distrito }}
                                    </option>
                                </select>
                                @error('distrito_partida')
                                    <p class="error-message text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="fl-flex-label mb-4 px-2 col-12 col-md-12">
                                <input type="text" class="input input__select block" name="direccion_partida"
                                    placeholder="Dirección" value="{{ $usuarioUbicacion->direccion }}">
                                @error('direccion_partida')
                                    <p class="error-message text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    @endforeach
                @endif

                <div>
                    <legend class="etiqueta">Lugar de llegada</legend>
                    <div class="fl-flex-label mb-4 px-2 col-12 col-md-4">
                        <label class="title">Departamento</label>
                        <select required class="input input__select" name="departamento_llegada"
                            id="departamento_llegada">
                            <option value="">Seleccionar...</option>
                            @foreach ($departamento as $item)
                                <option value="{{ $item->idDepa }}"
                                    {{ old('departamento_llegada') == $item->idDepa ? 'selected' : '' }}>
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
                            {{-- <option value="">Seleccionar...</option> --}}
                        </select>
                        @error('provincia_llegada')
                            <p class="error-message text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="fl-flex-label mb-4 px-2 col-12 col-md-4">
                        <label class="title">Distrito</label>
                        <select required class="input input__select" name="distrito_llegada" id="distrito_llegada">
                            {{-- <option value="">Seleccionar...</option> --}}
                        </select>
                        @error('distrito_llegada')
                            <p class="error-message text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="fl-flex-label mb-4 px-2 col-12 col-md-12">
                        <input required type="text" class="input input__select" name="direccion_llegada"
                            placeholder="Dirección" value="{{ old('direccion_llegada') }}">
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
                            value="{{ old('cantidad') }}">
                        @error('cantidad')
                            <p class="error-message text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="fl-flex-label mb-4 px-2 col-12 col-sm-6 col-md-3">
                        <label class="title">Precio</label>
                        <input required class="input input__select" type="text" name="precio"
                            value="{{ old('precio') }}">
                        @error('precio')
                            <p class="error-message text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="fl-flex-label mb-4 px-2 col-12 col-sm-6 col-md-3">
                        <label class="title">Estado de pago</label>
                        <div class="checkbox-toggle">
                            <input name="estado_pago" type="checkbox" id="check-toggle-2">
                            <label id="miLabel" for="check-toggle-2"></label>
                        </div>
                        @error('estado_pago')
                            <p class="error-message text-danger">{{ $message }}</p>
                        @enderror
                    </div>


                    <div class="fl-flex-label mb-4 px-2 col-12 col-sm-6 col-md-3">
                        <label class="title">Fecha de salida</label>
                        <input required class="input input__select" type="date"
                            value="{{ date('Y-m-d', strtotime('+1 day')) }}" name="fecha_salida"
                            min="{{ date('Y-m-d') }}">
                        @error('fecha_salida')
                            <p class="error-message text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="fl-flex-label mb-4 px-2 col-12 col-md-12">
                        <textarea required name="descripcion" class="input input__select" rows="2" placeholder="Descripcion">{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <p class="error-message text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>



            <div class="text-right p-2">
                <a href="{{ route('envio.index') }}" class="btn btn-secondary btn-rounded">Atras</a>
                <button type="submit" value="ok" name="btnmodificar"
                    class="btn btn-primary btn-rounded">Registrar</button>
            </div>
        </form>
    </div>


    <script>
        var checkbox = document.getElementById("check-toggle-2");
        var label = document.getElementById("miLabel");

        checkbox.addEventListener("click", function() {
            if (checkbox.checked) {
                label.innerText = "Pagado";
            } else {
                label.innerText = "No pagado";
            }
        });



        //buscar distrito por ajax
        let departamento_partida = document.getElementById("departamento_partida");
        let provincia_partida = document.getElementById("provincia_partida");
        let distrito_partida = document.getElementById("distrito_partida");


        let departamento_llegada = document.getElementById("departamento_llegada");
        let provincia_llegada = document.getElementById("provincia_llegada");
        let distrito_llegada = document.getElementById("distrito_llegada");

        try {
            departamento_partida.addEventListener("change", buscarProvinciaPartida)
            provincia_partida.addEventListener("change", buscarDistritoPartida)

        } catch (error) {

        }

        try {
            departamento_llegada.addEventListener("change", buscarProvinciaLlegada)
            provincia_llegada.addEventListener("change", buscarDistritoLlegada)
        } catch (error) {

        }


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
        let numero = document.getElementById("cod");
        numero.addEventListener("input", buscarNumero)
        numero.addEventListener("keyup", buscarNumero)
        numero.addEventListener("blur", buscarNumero)

        function buscarNumero() {
            let numero = document.getElementById("cod").value;
            let incorrecto = document.getElementById("incorrecto");
            var ruta = "{{ url('buscar-numero') }}-" + numero + "";

            $.ajax({
                url: ruta,
                type: "get",
                success: function(data) {
                    incorrecto.innerHTML = `<div class="alert alert-danger">${data.respuesta}</div>`;
                },
                error: function(data) {
                    incorrecto.innerHTML = "";
                }
            })
        }
    </script>

    <script>
        let dniRemitente = document.getElementById("dniRemitente")
        let dniReceptor = document.getElementById("dniReceptor")

        dniRemitente.addEventListener("input", buscarRemitente)
        dniRemitente.addEventListener("keyup", buscarRemitente)
        dniRemitente.addEventListener("blur", buscarRemitente)

        dniReceptor.addEventListener("input", buscarReceptor)
        dniReceptor.addEventListener("keyup", buscarReceptor)
        dniReceptor.addEventListener("blur", buscarReceptor)

        function buscarRemitente() {
            let dniRemitente = document.getElementById("dniRemitente").value
            if (dniRemitente.length >= 8 && dniRemitente.length <= 20) {
                let nombreRemitente = document.getElementById("nombreRemitente")
                let telefonoRemitente = document.getElementById("telefonoRemitente")
                let direccionRemitente = document.getElementById("direccionRemitente")

                var ruta = "{{ url('buscar-remitente') }}-" + dniRemitente + "";

                $.ajax({
                    url: ruta,
                    type: "get",
                    success: function(data) {
                        if (data.success) {
                            data.datos.forEach(function(ele) {
                                nombreRemitente.value = ele.nombre_razon_social
                                telefonoRemitente.value = ele.telefono
                                direccionRemitente.value = ele.direccion
                            });
                        } else {
                            nombreRemitente.value = ""
                            telefonoRemitente.value = ""
                            direccionRemitente.value = ""
                        }

                    },
                    error: function() {
                        nombreRemitente.value = ""
                        telefonoRemitente.value = ""
                        direccionRemitente.value = ""
                    }
                })
            }

        }

        function buscarReceptor() {
            let dniReceptor = document.getElementById("dniReceptor").value
            if (dniReceptor.length >= 8 && dniReceptor.length <= 20) {
                let nombreReceptor = document.getElementById("nombreReceptor")
                let telefonoReceptor = document.getElementById("telefonoReceptor")
                let direccionReceptor = document.getElementById("direccionReceptor")

                var ruta = "{{ url('buscar-receptor') }}-" + dniReceptor + "";

                $.ajax({
                    url: ruta,
                    type: "get",
                    success: function(data) {
                        if (data.success) {
                            data.datos.forEach(function(ele) {
                                nombreReceptor.value = ele.nombre_razon_social
                                telefonoReceptor.value = ele.telefono
                                direccionReceptor.value = ele.direccion
                            });
                        } else {
                            nombreReceptor.value = ""
                            telefonoReceptor.value = ""
                            direccionReceptor.value = ""
                        }

                    },
                    error: function() {
                        nombreReceptor.value = ""
                        telefonoReceptor.value = ""
                        direccionReceptor.value = ""
                    }
                })
            }
        }
    </script>


@endsection
