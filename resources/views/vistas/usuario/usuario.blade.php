@extends('layouts/app')
@section('titulo', 'Usuario')
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

    <h4 class="text-center text-secondary">LISTA DE USUARIOS</h4>

    @error('txtnombre')
        <p class="error-message text-danger">El nombre del usuario es obligatorio</p>
    @enderror
    @error('txtapellidopaterno')
        <p class="error-message text-danger">El apellido paterno del usuario es obligatorio</p>
    @enderror
    @error('txtapellidomaterno')
        <p class="error-message text-danger">El apellido paterno del usuario es obligatorio</p>
    @enderror
    @error('txtusuario')
        <p class="error-message text-danger">El usuario es obligatorio</p>
    @enderror
    @error('txtclave')
        <p class="error-message text-danger">El campo clave es obligatorio</p>
    @enderror
    @error('txtcorreo')
        <p class="error-message text-danger">El campo correo es obligatorio</p>
    @enderror
    @error('txtfoto')
        <p class="error-message text-danger">El formato de la foto solo puede ser: jpeg,png,jpg</p>
    @enderror
    @error('txtfoto2')
        <p class="error-message text-danger">El formato de la foto solo puede ser: jpeg,png,jpg</p>
    @enderror
    @error('sucursal')
        <p class="error-message text-danger">{{ $message }}</p>
    @enderror


    <div class="pb-1 pt-2">
        <a href="" data-toggle="modal" data-target="#registrar" class="btn btn-rounded btn-primary"><i
                class="fas fa-plus"></i>&nbsp;
            Registrar</a>
    </div>

    <!-- Modal registrar datos usuario-->
    <div class="modal fade" id="registrar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between">
                    <h5 class="modal-title w-100" id="exampleModalLabel">Registrar Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('usuario.create') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="fl-flex-label mb-4 px-2 col-12">
                            <input required type="text" placeholder="Nombre" class="input input__text" name="txtnombre"
                                value="{{ old('txtnombre') }}">
                        </div>

                        <div class="fl-flex-label mb-4 px-2 col-12">
                            <input required type="text" placeholder="Usuario" class="input input__text" name="txtusuario"
                                value="{{ old('txtusuario') }}">
                        </div>
                        <div class="fl-flex-label mb-4 px-2 col-12">
                            <input required type="password" placeholder="Contraseña" class="input input__text"
                                name="txtclave" value="{{ old('txtclave') }}">
                        </div>
                        <div class="fl-flex-label mb-4 px-2 col-12">
                            <label>Seleccione el Sucursal...</label>
                            <select required class="input input__select" name="sucursal" id="">
                                <option value="">Seleccionar...</option>
                                @foreach ($sucursal as $suc)
                                    <option value="{{ $suc->id_sucursal }}">
                                        {{ $suc->nombre . ' - ' . $suc->Distrito . ' - ' . $suc->Provincia . ' - ' . $suc->Departamento }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="fl-flex-label mb-4 px-2 col-12">
                            <input required type="text" placeholder="Teléfono" class="input input__text"
                                name="txttelefono" value="{{ old('txttelefono') }}">
                        </div>
                        <div class="fl-flex-label mb-4 px-2 col-12">
                            <input required type="email" placeholder="Correo" class="input input__text" name="txtcorreo"
                                value="{{ old('txtcorreo') }}">
                        </div>


                        <div class="fl-flex-label mb-4 px-2 col-12">
                            <label>Seleccionar foto del usuario</label>
                            <input type="file" class="input input__text" name="txtfoto" value="{{ old('txtfoto') }}">
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
                        <th>Usuario</th>
                        <th>Sucursal</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Foto</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($sql as $item)
                        <tr>
                            <td>{{ $item->id_usuario }}</td>
                            <td>{{ $item->nombres }}</td>
                            <td>{{ $item->usuario }}</td>
                            <td>{{ $item->nombre . ' - ' . $item->Distrito . ' - ' . $item->Provincia . ' - ' . $item->Departamento }}
                            </td>
                            <td>{{ $item->telefono }}</td>
                            <td>{{ $item->correo }}</td>
                            <td>
                                @if ($item->foto != '')
                                    <a data-toggle="modal" data-target="#modificarfoto{{ $item->id_usuario }}"><img
                                            src="{{ asset("foto/usuario/$item->foto") }}" width="50px"
                                            alt=""></a>
                                @else
                                    <a data-toggle="modal" data-target="#modificarfoto{{ $item->id_usuario }}">Agregar
                                        foto</a>
                                @endif
                            </td>

                            <td>

                                <a style="top: 0" href="" data-toggle="modal"
                                    data-target="#modificar{{ $item->id_usuario }}" class="btn btn-sm btn-warning m-1"><i
                                        class="fas fa-edit"></i></a>

                                <form action="{{ route('usuario.delete', $item->id_usuario) }}" method="get"
                                    class="d-inline formulario-eliminar">
                                </form>
                                <a href="#" class="btn btn-sm btn-danger eliminar"
                                    data-id="{{ $item->id_usuario }}">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>


                            <!-- Modal modificar datos usuario-->
                            <div class="modal fade" id="modificar{{ $item->id_usuario }}" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header d-flex justify-content-between">
                                            <h5 class="modal-title w-100" id="exampleModalLabel">Modificar Usuario</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('usuario.update') }}" method="POST">
                                                @csrf
                                                <div hidden class="fl-flex-label mb-4 px-2 col-12">
                                                    <input type="text" placeholder="ID" class="input input__text"
                                                        name="txtid" value="{{ $item->id_usuario }}">
                                                </div>
                                                <div class="fl-flex-label mb-4 px-2 col-12">
                                                    <input required type="text" placeholder="Nombre"
                                                        class="input input__text" name="txtnombre"
                                                        value="{{ $item->nombres }}">
                                                </div>

                                                <div class="fl-flex-label mb-4 px-2 col-12">
                                                    <input required type="text" placeholder="Usuario"
                                                        class="input input__text" name="txtusuario"
                                                        value="{{ $item->usuario }}">
                                                </div>

                                                <div class="fl-flex-label mb-4 px-2 col-12">
                                                    <label>Seleccione el Sucursal...</label>
                                                    <select required class="input input__select" name="sucursal"
                                                        id="">
                                                        <option value="">Seleccionar...</option>
                                                        @foreach ($sucursal as $suc)
                                                            <option
                                                                {{ $item->id_sucursal == $suc->id_sucursal ? 'selected' : '' }}
                                                                value="{{ $suc->id_sucursal }}">
                                                                {{ $suc->nombre . ' - ' . $suc->Distrito . ' - ' . $suc->Provincia . ' - ' . $suc->Departamento }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="fl-flex-label mb-4 px-2 col-12">
                                                    <input required type="text" placeholder="Telefono"
                                                        class="input input__text" name="txttelefono"
                                                        value="{{ $item->telefono }}">
                                                </div>

                                                <div class="fl-flex-label mb-4 px-2 col-12">
                                                    <input type="text" placeholder="Correo" class="input input__text"
                                                        name="txtcorreo" value="{{ $item->correo }}">
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

                            <!-- Modal modificar foto usuario -->
                            <div class="modal fade" id="modificarfoto{{ $item->id_usuario }}" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header d-flex justify-content-between">
                                            <h5 class="modal-title w-100" id="exampleModalLabel">Modificar Foto</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="text-center">
                                                <img width="200px" src="{{ asset("foto/usuario/$item->foto") }}"
                                                    alt="">
                                            </div>
                                            <form action="{{ route('usuario.actualizarFoto') }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <div hidden class="fl-flex-label mb-4 px-2 col-12">
                                                    <input type="text" placeholder="ID" class="input input__text"
                                                        name="txtid" value="{{ $item->id_usuario }}">
                                                </div>
                                                <div class="fl-flex-label mb-4 px-2 col-12">
                                                    <input required type="file" class="input input__text"
                                                        name="txtfoto2">
                                                </div>

                                                <div class="text-right p-2">
                                                    <a onclick="return confimar_eliminar()"
                                                        href="{{ route('usuario.deleteFoto', $item->id_usuario) }}"
                                                        class="btn btn-danger btn-rounded">Eliminar Foto</a>
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

@endsection
