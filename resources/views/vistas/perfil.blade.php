@extends('layouts/app')
@section('titulo', 'sis-municipio-escolar')

@section('content')

    <style>
        img.logo {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            box-shadow: 0px 0px 20px rgb(226, 226, 226);
            margin-top: -20px;
            margin-bottom: 40px;
            object-fit: cover;
        }

        .logo {
            font-size: 130px;
            color: rgb(228, 228, 228);
        }

        .img {
            background: rgb(247, 251, 255);
        }
    </style>

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

    @if (session('AVISO'))
        <script>
            $(function notificacion() {
                new PNotify({
                    title: "AVISO",
                    type: "error",
                    text: "{{ session('AVISO') }}",
                    styling: "bootstrap3"
                });
            });
        </script>
    @endif


    <h4 class="text-center text-secondary">MI PERFIL</h4>

    <div class="mb-0 col-12 bg-light p-5 pt-0">
        @foreach ($sql as $item)
            <div class="d-flex justify-content-around align-items-center flex-wrap gap-5 pt-3 pb-3 mb-3 img">
                <div class="text-center">
                    @if ($item->foto == null)
                        <p class="logo">
                            <i class="far fa-frown"></i>
                        </p>
                    @else
                        <img class="logo" src="{{ asset("foto/usuario/$item->foto") }}" alt="">
                    @endif
                </div>
                <div>
                    <h6 class="text-dark font-weight-bold">Modificar imagen</h6>
                    <form action="{{ route('perfil.updatePerfil') }}" method="POST" id="miForm"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ $item->id_usuario }}">
                        <div class="alert alert-secondary">Selecciona una imagen no muy <b>pesado</b> y en un formato
                            <b>válido</b> ...!
                        </div>
                        <div class="fl-flex-label mb-4 col-12">
                            <input required type="file" name="foto" class="input form-control-file input__text"
                                value="">
                            @error('foto')
                                <small class="error error__text">{{ $message }}</small>
                            @enderror
                        </div>
                    </form>
                    <div class="d-flex justify-content-end gap-4">
                        <div class="text-right mt-0">
                            <button form="miForm" type="submit" class="btn btn-rounded btn-success"><i
                                    class="fas fa-save"></i>&nbsp;&nbsp; Modificar perfil</button>
                        </div>
                        <div class="text-right mt-0">
                            <form action="{{ route('perfil.deletePerfil', $item->id_usuario) }}" method="get"
                                class="d-inline formulario-eliminar">
                            </form>
                            <a class="btn btn-rounded btn-danger eliminar" data-id="{{ $item->id_usuario }}"><i
                                    class="fas fa-trash"></i>&nbsp;&nbsp; Eliminar
                                foto</a>
                        </div>
    
                    </div>
                </div>
            </div>



            <form action="{{ route('perfil.update') }}" method="POST">
                @csrf
                <div class="row">
                    <input type="hidden" name="id" value="{{ $item->id_usuario }}">


                    <div class="fl-flex-label mb-4 col-12 col-lg-6">
                        <input type="text" name="txtusuario" class="input input__text" placeholder="Usuario *"
                            value="{{ old('txtusuario', $item->usuario) }}">
                        @error('txtusuario')
                            <small class="error error__text">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="fl-flex-label mb-4 col-12 col-lg-6">
                        <input type="text" name="txtnombre" class="input input__text" id="nombre" placeholder="Nombre"
                            value="{{ old('txtnombre', $item->nombres) }}">
                        @error('txtnombre')
                            <small class="error error__text">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="fl-flex-label mb-4 col-12 col-lg-6">
                        <input type="text" name="txttelefono" class="input input__text" id="telefono"
                            placeholder="Teléfono" value="{{ old('txttelefono', $item->telefono) }}">
                        @error('txttelefono')
                            <small class="error error__text">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="fl-flex-label mb-4 col-12 col-lg-6">
                        <input type="email" name="txtcorreo" class="input input__text" placeholder="Correo *"
                            value="{{ old('correo', $item->correo) }}">
                    </div>

                    <div class="text-right mt-0">
                        <button type="submit" class="btn btn-rounded btn-primary">Guardar</button>
                    </div>
                </div>

            </form>
        @endforeach
    </div>




@endsection
