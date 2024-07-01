@extends('layouts/app')
@section('titulo', 'Registro de envios')
@section('content')

    <script>
        function confirmar(params) {
            var res = confirm("Estas seguro de cambiar el estador");
            return res;
        }

        function confimar_eliminar(params) {
            var res = confirm(
                "Por tu seguridad te preguntamos una vez más: ¿ Estas seguro de de eliminar todo el registro?");
            return res;
            confimar_eliminar
        }
    </script>

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

    <h4 class="text-center text-secondary">LISTA DE ENVIOS</h4>


    <div class="form-group row col-12 px-4">
        <div class="col-12 col-sm-9">
            <input type="text" id="dni" class="form-control p-3"
                placeholder="Numero de Registro / DNI / Nombre del Remitente o Consignatario" autofocus>
        </div>
        <button id="buscar" class="btn btn-success col-12 col-sm-3 mt-2 mt-sm-0" type="submit">Buscar</button>
    </div>
    <div class="card-block table-responsive">
        <table id="" class="display table table-striped" cellspacing="0" width="100%">
            <thead class="table-primary">
                <tr>
                    <th>Id</th>
                    <th>Numero Reg.</th>
                    <th>Consignatario</th>
                    <th>Lugar Destino</th>
                    <th>Precio</th>
                    <th>Descripcion</th>
                    <th>Estado Pago</th>
                    <th>Estado Envio</th>
                    <th></th>
                </tr>
            </thead>

            <tbody id="tbody">

            </tbody>
        </table>
    </div>


    <div class="pb-1 pt-2 d-flex flex-wrap gap-2">
        
        <a href="{{ route('envio.reporteEnvio') }}" target="_blank" class="btn btn-rounded bg-primary"><i
                class="fas fa-file-pdf"></i>&nbsp;
            Reporte</a>

        <form onsubmit="return confimar_eliminar()" action="{{ route('envio.eliminarTodo') }}" method="get"
            class="d-inline formulario-eliminar">
        </form>
        <a class="btn btn-rounded btn-danger eliminar"><i class="fas fa-trash-alt"></i>&nbsp;
            Eliminar todos los registros</a>

    </div>

    @error('txtfile')
        <p class="alert alert-danger p-2">{{ $message }}</p>
    @enderror



    <section class="card">
        <div class="card-block">
            <table id="example2" class="display table table-striped" cellspacing="0" width="100%">
                <thead class="table-primary">
                    <tr>
                        <th>Id</th>
                        <th>Numero Reg.</th>
                        {{-- <th>Remitente</th> --}}
                        <th>Consignatario</th>
                        {{-- <th>Fecha de envio</th> --}}
                        {{-- <th>Fecha de recojo</th> --}}
                        {{-- <th>Lugar Salida</th> --}}
                        <th>Lugar Destino</th>
                        {{-- <th>Cant.</th> --}}
                        <th>Precio</th>
                        <th>Descripcion</th>
                        <th>Estado Pago</th>
                        <th>Estado Envio</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($envio as $item)
                        <tr>
                            <td>{{ $item->id_envio }}</td>
                            <td>{{ $item->numero_reg }}</td>
                            {{-- <td>{{ $item->nomRemitente }}</td> --}}
                            <td>{{ $item->nomReceptor }}</td>
                            {{-- <td>{{ $item->fecha_salida }}</td> --}}
                            {{-- <td>{{ $item->fecha_recojo }}</td> --}}
                            {{-- <td>{{ $item->desde_distrito_nombre }} - {{ $item->desde_provincia_nombre }} - --}}
                            {{-- {{ $item->desde_departamento_nombre }}</td> --}}
                            <td>{{ $item->hasta_distrito_nombre }} - {{ $item->hasta_provincia_nombre }} -
                                {{ $item->hasta_departamento_nombre }}</td>
                            {{-- <td>{{ $item->cantidad }}</td> --}}
                            <td><b>S/.{{ $item->precio }}</b></td>
                            <td>{{ $item->descripcion }}</td>
                            <td>
                                @if ($item->pago_estado == '1')
                                    <form action="{{ route('eliminar.pago', $item->id_envio) }}" method="get"
                                        class="d-inline formulario-noPagar">
                                    </form>
                                    <a class="btn btn-sm bg-success noPagar"data-id="{{ $item->id_envio }}">PAGADO</a>
                                @else
                                    <form action="{{ route('realizar.pago', $item->id_envio) }}" method="get"
                                        class="d-inline formulario-pagar">
                                    </form>
                                    <a class="btn btn-sm bg-danger pagar"data-id="{{ $item->id_envio }}">DEBE</a>
                                @endif
                            </td>
                            <td>
                                @if ($item->envio_estado == '1')
                                    <a data-toggle="modal" data-target="#estadoEnvio{{ $item->id_envio }}"
                                        class="btn btn-sm bg-primary">{{ strtoupper($item->nombre) }}</a>
                                @else
                                    @if ($item->envio_estado == '2')
                                        <a data-toggle="modal" data-target="#estadoEnvio{{ $item->id_envio }}"
                                            class="btn btn-sm bg-warning">{{ strtoupper($item->nombre) }}</a>
                                    @else
                                        @if ($item->envio_estado == '3')
                                            <a data-toggle="modal" data-target="#estadoEnvio{{ $item->id_envio }}"
                                                class="btn btn-sm bg-danger">{{ strtoupper($item->nombre) }}</a>
                                        @else
                                            @if ($item->envio_estado == '4')
                                                <a data-toggle="modal" data-target="#estadoEnvio{{ $item->id_envio }}"
                                                    class="btn btn-sm bg-success">{{ strtoupper($item->nombre) }}</a>
                                            @endif
                                        @endif
                                    @endif
                                @endif
                            </td>
                        </tr>


                        <!-- Modal cambiar estado de envio-->
                        <div class="modal fade" id="estadoEnvio{{ $item->id_envio }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header d-flex justify-content-between">
                                        <h5 class="modal-title w-100" id="exampleModalLabel">CAMBIAR ESTADO DE ENVIO</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="col-12">
                                            <div class="col-sm-3">
                                                <a href="{{ route('estado.recepcionado', $item->id_envio) }}"
                                                    class="btn bg-primary">RECEPCIONADO</a>
                                            </div>
                                            <div class="col-sm-3">
                                                <a href="{{ route('estado.enTransito', $item->id_envio) }}"
                                                    class="btn bg-warning">EN TRANSITO</a>
                                            </div>
                                            <div class="col-sm-3">
                                                <a href="{{ route('estado.enDestino', $item->id_envio) }}"
                                                    class="btn bg-danger">EN DESTINO</a>
                                            </div>
                                            <div class="col-sm-3">
                                                <a href="{{ route('estado.entregado', $item->id_envio) }}"
                                                    class="btn bg-success">ENTREGADO</a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                    @endforeach
                </tbody>
            </table>
            <div class="text-right">
                {{ $envio->links('pagination::bootstrap-4') }}
                Mostrando {{ $envio->firstItem() }} - {{ $envio->lastItem() }} de {{ $envio->total() }}
                resultados
            </div>

        </div>
    </section>


    <script>
        let buscar = document.getElementById("buscar");
        let valor = document.getElementById("dni");
        valor.addEventListener("blur", buscarEnvio)
        valor.addEventListener("keyup", buscarEnvio)
        buscar.addEventListener("click", buscarEnvio)


        function buscarEnvio() {
            let valor = document.getElementById("dni").value;
            // if (valor == "") {
            //     alert("Ingrese datos para buscar")
            // }
            var ruta = "{{ url('buscar-envios') }}-" + valor + "";
            $.ajax({
                url: ruta,
                type: "get",
                success: function(data) {
                    let tr = document.createElement("tr");
                    let tbody = document.querySelector("tbody");
                    let fragmento = document.createDocumentFragment();
                    let td = "";
                    data.datos.forEach(function(item, index) {
                        td +=
                            `<tr>
                                <td>${item.id_envio}</td>
                                <td>${item.numero_reg}</td>
                                <td>${item.nomReceptor}</td>
                                <td>${item.hasta_distrito_nombre} - ${item.hasta_provincia_nombre} - ${item.hasta_departamento_nombre}</td>
                                <td><b>S/.${item.precio}</b></td>
                                <td>${item.descripcion}</td>
                                <td>
                                    ${item.pago_estado=='1' ? '<a class="btn btn-sm bg-success">PAGADO</a>' : '<a class="btn btn-sm bg-danger">DEBE</a>'}

                                </td>
                                <td>
                                    ${item.envio_estado=='1' ? '<a class="btn btn-sm bg-primary">RECEPCIONADO</a>' : item.envio_estado=='2' ? '<a class="btn btn-sm bg-warning">EN TRANSITO</a>' : item.envio_estado=='3' ? '<a class="btn btn-sm bg-danger">EN DESTINO</a>': '<a class="btn btn-sm bg-success">ENTREGADO</a>' } 
                                </td>
                            
                                <td>
                                    <a style="top: 0" href="envio/${item.id_envio}" class="btn btn-sm btn-primary m-1"><i class="fas fa-eye"></i></a>
                                </td>


                            </tr>`;
                        tbody.innerHTML = td
                    })

                },
                error: function(data) {
                    let tbody = document.getElementById("tbody");
                    tbody.innerHTML = ""
                }
            })
        }
    </script>


@endsection
