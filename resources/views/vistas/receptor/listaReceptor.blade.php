@extends('layouts/app')
@section('titulo', 'Registro de envios')
@section('content')

    <script>
        function confirmar(params) {
            var res = confirm("¿Estás seguro de cambiar el estado?");
            return res;
        }

        function confimar_eliminar(params) {
            var res = confirm(
                "Por tu seguridad, te preguntamos una vez más: ¿Estás seguro de eliminar todo el registro?");
            return res;
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

    <h4 class="text-center text-secondary">TODOS LOS CONSIGNATARIOS</h4>

    <div class="form-group row col-12 px-4">
        <div class="col-12 col-sm-9">
            <input type="text" id="dni" class="form-control p-3"
                placeholder="DNI / Nombre / Razon Social del Consignatario">
        </div>
        <button id="buscar" class="btn btn-success col-12 col-sm-3 mt-2 mt-sm-0" type="button">Buscar</button>
    </div>

    <div class="card-block table-responsive">
        <table id="" class="display table table-striped" cellspacing="0" width="100%">
            <thead class="table-primary">
                <tr>
                    <th>Id</th>
                    <th>DNI</th>
                    <th>Nombre / Razon Social</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="tbody">
            </tbody>
        </table>
    </div>

    <div class="pb-1 pt-2 d-flex flex-wrap gap-2">
        <a href="" data-toggle="modal" data-target="#registrar" class="btn btn-rounded btn-primary"><i
                class="fas fa-plus"></i>&nbsp;
            Registrar</a>

        <a href="{{ route('receptor.reporteReceptor') }}" target="_blank" class="btn btn-rounded bg-primary"><i
                class="fas fa-file-pdf"></i>&nbsp;
            Reporte</a>

        <form onsubmit="return confimar_eliminar()" action="{{ route('receptor.eliminarTodo') }}" method="get"
            class="d-inline formulario-eliminar">
        </form>

        <a class="btn btn-rounded btn-danger eliminar"><i class="fas fa-trash-alt"></i>&nbsp;
            Eliminar todos los registros</a>
    </div>

    <!-- Modal registrar datos-->
    <div class="modal fade" id="registrar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between">
                    <h5 class="modal-title w-100" id="exampleModalLabel">Registrar Consignatarios</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formularioReceptor" action="{{ route('receptor.store') }}" method="POST"
                        onsubmit="return validarFormulario()">
                        @csrf

                        <div class="fl-flex-label mb-4 px-2 col-12">
                            <input required type="text" placeholder="DNI" class="input input__text" name="dni"
                                value="{{ old('dni') }}">
                            <span id="errorDni" class="text-danger font-weight-bold">@error('dni'){{ $message }}@enderror</span>
                        </div>

                        <div class="fl-flex-label mb-4 px-2 col-12">
                            <input required type="text" placeholder="Nombre o Razon Social" class="input input__text"
                                name="nombre" value="{{ old('nombre') }}">
                            <span class="text-danger font-weight-bold">@error('nombre'){{ $message }}@enderror</span>
                        </div>

                        <div class="fl-flex-label mb-4 px-2 col-12">
                            <input type="text" placeholder="Dirección" class="input input__text" name="direccion"
                                value="{{ old('direccion') }}">
                        </div>

                        <div class="fl-flex-label mb-4 px-2 col-12">
                            <input required type="text" placeholder="Teléfono" class="input input__text"
                                name="telefono" value="{{ old('telefono') }}">
                            <span id="errorTelefono" class="text-danger font-weight-bold">@error('telefono'){{ $message }}@enderror</span>
                        </div>

                        <div class="text-right p-2">
                            <a data-dismiss="modal" class="btn btn-secondary btn-rounded" onclick="limpiarFormulario()">Atrás</a>
                            <button type="submit" value="ok" name="btnmodificar"
                                class="btn btn-primary btn-rounded">Registrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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
                        <th>DNI</th>
                        <th>Nombre / Razon Social</th>
                        <th>Dirección</th>
                        <th>Teléfono</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($datos as $item)
                        <tr>
                            <td>{{ $item->id_receptor }}</td>
                            <td>{{ $item->dni }}</td>
                            <td>{{ $item->nombre_razon_social }}</td>
                            <td>{{ $item->direccion }}</td>
                            <td>{{ $item->telefono }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="text-right">
                {{ $datos->links('pagination::bootstrap-4') }}
                Mostrando {{ $datos->firstItem() }} - {{ $datos->lastItem() }} de {{ $datos->total() }}
                resultados
            </div>
        </div>
    </section>

    <script>
        let buscar = document.getElementById("buscar");
        let valor = document.getElementById("dni");
        valor.addEventListener("blur", buscarReceptor)
        valor.addEventListener("keyup", buscarReceptor)
        buscar.addEventListener("click", buscarReceptor)

        function buscarReceptor() {
            let valor = document.getElementById("dni").value;
            var ruta = "{{ url('buscar-receptor') }}-" + valor + "";
            $.ajax({
                url: ruta,
                type: "get",
                success: function(data) {
                    let tbody = document.getElementById("tbody");
                    tbody.innerHTML = ''; // Limpiar contenido existente antes de agregar nuevas filas

                    data.datos.forEach(function(item, index) {
                        let tr = document.createElement("tr"); // Crear nueva fila
                        tr.innerHTML = `
                            <td>${item.id_receptor}</td>
                            <td>${item.dni}</td> 
                            <td>${item.nombre_razon_social}</td>
                            <td>${item.direccion}</td>
                            <td>${item.telefono}</td>
                            <td>
                                <a style="top: 0" href="receptor/${item.id_receptor}" class="btn btn-sm btn-primary m-1"><i class="fas fa-eye"></i></a>
                            </td>
                        `;
                        tbody.appendChild(tr); // Agregar la nueva fila a tbody
                    })

                },
                error: function(data) {
                    let tbody = document.getElementById("tbody");
                    tbody.innerHTML = "" // Limpiar contenido en caso de error
                }
            })
        }

        function validarFormulario() {
            let formValido = true;

            // Validación del DNI
            let dni = document.getElementsByName('dni')[0].value;
            let dniError = document.getElementById('errorDni');
            if (dni.length < 8 || dni.length > 20) {
                dniError.textContent = 'El DNI debe tener entre 8 y 20 caracteres.';
                formValido = false;
            } else {
                dniError.textContent = '';
            }

            // Validación del Teléfono
            let telefono = document.getElementsByName('telefono')[0].value;
            let telefonoError = document.getElementById('errorTelefono');
            let telefonoRegex = /^[0-9]{9,}$/;
            if (!telefonoRegex.test(telefono)) {
                telefonoError.textContent = 'El teléfono debe contener 9 dígitos.';
                formValido = false;
            } else {
                telefonoError.textContent = '';
            }

            return formValido;
        }

        function limpiarFormulario() {
            document.getElementById("formularioReceptor").reset();
            document.getElementById("errorDni").textContent = '';
            document.getElementById("errorTelefono").textContent = '';
        }
    </script>

@endsection
