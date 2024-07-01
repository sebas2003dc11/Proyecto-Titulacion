@extends('layouts/app')
@section('titulo', 'Sucursal')
@section('content')
    {{-- notificaciones --}}

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

    <script>
        function confimar_eliminar(params) {
            var res = confirm("estas seguro que deseas eliminar");
            return res;
        }
    </script>

    <h4 class="text-center text-secondary">SUCURSALES</h4>

    @error('nombre')
        <p class="error-message text-danger">{{ $message }}</p>
    @enderror
    @error('departamento')
        <p class="error-message text-danger">{{ $message }}</p>
    @enderror
    @error('provincia')
        <p class="error-message text-danger">{{ $message }}</p>
    @enderror
    @error('distrito')
        <p class="error-message text-danger">{{ $message }}</p>
    @enderror
    @error('direccion')
        <p class="error-message text-danger">{{ $message }}</p>
    @enderror

    @error('telefonp')
        <p class="error-message text-danger">{{ $message }}</p>
    @enderror


    <div class="pb-1 pt-2">
        <a href="" data-toggle="modal" data-target="#registrar" class="btn btn-rounded btn-primary"><i
                class="fas fa-plus"></i>&nbsp;
            Registrar</a>
    </div>

    <!-- Modal registrar datos -->
    <div class="modal fade" id="registrar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between">
                    <h5 class="modal-title w-100" id="exampleModalLabel">Registrar Nuevo Sucursal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('sucursal.store') }}" method="POST">
                        @csrf
                        <div class="fl-flex-label mb-4 px-2 col-12">
                            <input required type="text" placeholder="Nombre" class="input input__text" name="nombre"
                                value="{{ old('nombre') }}">
                        </div>

                        <div class="fl-flex-label mb-4 px-2 col-12 ">
                            <label class="title">Departamento</label>
                            <select required class="input input__select" name="departamento" id="departamento">
                                <option value="">Seleccionar...</option>
                                @foreach ($departamento as $item)
                                    <option value="{{ $item->idDepa }}"
                                        {{ old('departamento') == $item->idDepa ? 'selected' : '' }}>
                                        {{ $item->Departamento }}</option>
                                @endforeach
                            </select>
                            @error('departamento')
                                <p class="error-message text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="fl-flex-label mb-4 px-2 col-12 ">
                            <label class="title">Provincia</label>
                            <select required class="input input__select" name="provincia" id="provincia">
                                {{-- <option value="">Seleccionar...</option> --}}
                            </select>
                            @error('provincia')
                                <p class="error-message text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="fl-flex-label mb-4 px-2 col-12 ">
                            <label class="title">Distrito</label>
                            <select required class="input input__select" name="distrito" id="distrito">
                                {{-- <option value="">Seleccionar...</option> --}}
                            </select>
                            @error('distrito')
                                <p class="error-message text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="fl-flex-label mb-4 px-2 col-12">
                            <input required type="text" placeholder="Dirección" class="input input__text"
                                name="direccion" value="{{ old('direccion') }}">
                        </div>

                        <div class="fl-flex-label mb-4 px-2 col-12">
                            <input required type="text" placeholder="Teléfono" class="input input__text" name="telefono"
                                value="{{ old('telefono') }}">
                        </div>




                        <div class="text-right p-2">
                            <a data-dismiss="modal" class="btn btn-secondary btn-rounded">Atras</a>
                            <button type="submit" value="ok" name="btnmodificar"
                                class="btn btn-primary btn-rounded">Registrar</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>


    <section class="card">
        <div class="card-block">
            <table id="example" class="display table table-striped" cellspacing="0" width="100%">
                <thead class="table-primary">
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Ubicación D-P-D</th>
                        <th>Dirección</th>
                        <th>Teléfono</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($sql as $key => $item)
                        <tr>
                            <td>{{ $item->id_sucursal }}</td>
                            <td>{{ $item->nombre }}</td>
                            <td>{{ $item->nomDistrito . '-' . $item->Provincia . '-' . $item->Departamento }}</td>
                            <td>{{ $item->direccion }}</td>
                            <td>{{ $item->telefono }}</td>

                            <td>
                                <a style="top: 0" href="" data-toggle="modal"
                                    data-target="#modificar{{ $item->id_sucursal }}" class="btn btn-sm btn-warning m-1"><i
                                        class="fas fa-edit"></i></a>

                                <form action="{{ route('sucursal.destroy', $item->id_sucursal) }}" method="POST"
                                    class="d-inline formulario-eliminar">
                                    @csrf
                                    @method('delete')
                                </form>
                                <a href="#" class="btn btn-sm btn-danger eliminar"
                                    data-id="{{ $item->id_sucursal }}">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>

                            <!-- Modal modificar datos-->
                            <div class="modal fade" id="modificar{{ $item->id_sucursal }}" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header d-flex justify-content-between">
                                            <h5 class="modal-title w-100" id="exampleModalLabel">Modificar Sucursal</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('sucursal.update', $item->id_sucursal) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="fl-flex-label mb-4 px-2 col-12">
                                                    <input required type="text" placeholder="Nombre"
                                                        class="input input__text" name="nombre"
                                                        value="{{ old('nombre', $item->nombre) }}">
                                                </div>

                                                <div class="fl-flex-label mb-4 px-2 col-12 ">
                                                    <label class="title">Departamento</label>
                                                    <select required class="input input__select" name="departamento"
                                                        data-id="depa-{{ $key }}">
                                                        <option value="">Seleccionar...</option>
                                                        @foreach ($departamento as $datosDepa)
                                                            <option value="{{ $datosDepa->idDepa }}"
                                                                {{ $item->idDepa == $datosDepa->idDepa ? 'selected' : '' }}>
                                                                {{ $datosDepa->Departamento }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('departamento')
                                                        <p class="error-message text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div class="fl-flex-label mb-4 px-2 col-12 ">
                                                    <label class="title">Provincia</label>
                                                    <select required class="input input__select" name="provincia"
                                                        data-id="prov-{{ $key }}">
                                                        <option value="">Seleccionar...</option>
                                                        @foreach ($provincia as $datosProv)
                                                            <option value="{{ $datosProv->idProv }}"
                                                                {{ $item->idProv == $datosProv->idProv ? 'selected' : '' }}>
                                                                {{ $datosProv->Provincia }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('provincia')
                                                        <p class="error-message text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div class="fl-flex-label mb-4 px-2 col-12 ">
                                                    <label class="title">Distrito</label>
                                                    <select required class="input input__select" name="distrito"
                                                        data-id="dist-{{ $key }}">
                                                        @foreach ($distrito as $datosDist)
                                                            <option value="{{ $datosDist->idDist }}"
                                                                {{ $item->distrito == $datosDist->idDist ? 'selected' : '' }}>
                                                                {{ $datosDist->Distrito }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('distrito')
                                                        <p class="error-message text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div class="fl-flex-label mb-4 px-2 col-12">
                                                    <input required type="text" placeholder="Dirección"
                                                        class="input input__text" name="direccion"
                                                        value="{{ old('direccion', $item->direccion) }}">
                                                </div>

                                                <div class="fl-flex-label mb-4 px-2 col-12">
                                                    <input required type="text" placeholder="Teléfono"
                                                        class="input input__text" name="telefono"
                                                        value="{{ old('telefono', $item->telefono) }}">
                                                </div>

                                                <div class="text-right p-2">
                                                    <a data-dismiss="modal"
                                                        class="btn btn-secondary btn-rounded">Atras</a>
                                                    <button type="submit" value="ok" name="btnmodificar"
                                                        class="btn btn-primary btn-rounded">Modificar</button>
                                                </div>
                                            </form>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>


    <script>
        //buscar distrito por ajax
        let departamento = document.getElementById("departamento");
        let provincia = document.getElementById("provincia");
        let distrito = document.getElementById("distrito");

        departamento.addEventListener("change", buscarProvinciaPartida)
        provincia.addEventListener("change", buscarDistritoPartida)



        function buscarProvinciaPartida() {

            let departamento = document.getElementById("departamento").value;
            var ruta = "{{ url('buscar-provincia') }}-" + departamento + "";

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
                    provincia.innerHTML = nuevoOption;
                },
                error: function(data) {
                    let option = `<option value="">Seleccionar...</option>`;
                    provincia.innerHTML = option;
                }
            })
            buscarDistritoPartida()
        }

        function buscarDistritoPartida() {
            let provincia = document.getElementById("provincia").value;

            var ruta = "{{ url('buscar-distrito') }}-" + provincia + "";

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
                    distrito.innerHTML = nuevoOption;
                },
                error: function(data) {
                    let option = `<option value="">Seleccionar...</option>`;
                    distrito.innerHTML = option;
                }
            })
        }


        //agregar buscar distrito en la parte de modal modificar
        window.onload = function() {

            //buscarprovincias
            const todosDepartamentos = document.querySelectorAll('[data-id^="depa-"]');
            todosDepartamentos.forEach(function(ele, index) {
                console.log(ele)
                ele.addEventListener("change", function(elemento) {
                    let provincia = document.querySelector(`[data-id^="prov-${index}"]`)
                    console.log(provincia)
                    let valorDepartamento = elemento.target.value;
                    var ruta = "{{ url('buscar-provincia') }}-" + valorDepartamento + "";

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
                            provincia.innerHTML = nuevoOption;
                        },
                        error: function(data) {
                            let option = `<option value="">Seleccionar...</option>`;
                            provincia.innerHTML = option;
                        }
                    })
                })
            })

            //buscarDistritor
            const todosProvincias = document.querySelectorAll('[data-id^="prov-"]');
            todosProvincias.forEach(function(ele, index) {
                console.log(ele)
                ele.addEventListener("change", function(elemento) {
                    let distrito = document.querySelector(`[data-id^="dist-${index}"]`)
                    console.log(distrito)
                    let valorDepartamento = elemento.target.value;
                    var ruta = "{{ url('buscar-distrito') }}-" + valorDepartamento + "";

                    $.ajax({
                        url: ruta,
                        type: "get",
                        success: function(data) {
                            let option = '';
                            let defecto = `<option value="">Seleccionar...</option>`;
                            data.datos.forEach(function(ele) {
                                option +=
                                    `<option value="${ele.idDist}">${ele.Distrito}</option>`
                            });
                            let nuevoOption = `${defecto}${option}`;
                            distrito.innerHTML = nuevoOption;
                        },
                        error: function(data) {
                            let option = `<option value="">Seleccionar...</option>`;
                            distrito.innerHTML = option;
                        }
                    })
                })
            })
        }
    </script>

@endsection
