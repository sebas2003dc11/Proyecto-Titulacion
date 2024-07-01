@extends('layouts/app')
@section('titulo', 'empresa')
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

    <h4 class="text-center text-secondary">DATOS DE LA EMPRESA</h4>

    <div class="mb-0 col-12 bg-light p-3">
        @foreach ($sql as $item)
            <div class="d-flex justify-content-around align-items-center flex-wrap gap-5 pb-3 mb-3 img">
                <div class="text-center">
                    @if ($item->foto == null)
                        <p class="logo" style="font-size: 100px">
                            <i class="far fa-frown"></i>
                        </p>
                    @else
                        <img width="200px" class="logo" src="{{ asset("foto/empresa/$item->foto") }}" alt="">
                    @endif
                </div>
                <div>
                    <h6 class="text-dark font-weight-bold">Modificar imagen</h6>
                    <form action="{{ route('empresa.updateEmpresa') }}" method="POST" id="miForm"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="alert alert-secondary">Selecciona una imagen no muy <b>pesado</b> y en un
                            formato
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
                            <form action="{{ route('empresa.deleteEmpresa') }}" method="get"
                                class="d-inline formulario-eliminar">
                            </form>

                            <a class="btn btn-rounded btn-danger eliminar"><i class="fas fa-trash"></i>&nbsp;&nbsp; Eliminar
                                foto</a>
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('empresa.update', $item->id_empresa) }}" method="POST">
                @csrf
                <div class="row">
                    <div class="fl-flex-label mb-4 col-12 col-lg-6">
                        <input required type="text" name="nombre" class="input input__text" id="nombre"
                            placeholder="Nombre" value="{{ $item->nombre }}">
                        @error('nombre')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="fl-flex-label mb-4 col-12 col-lg-6">
                        <input type="text" name="ubicacion" class="input input__text" placeholder="ubicacion *"
                            value="{{ old('ubicacion', $item->ubicacion) }}">
                    </div>
                    <div class="fl-flex-label mb-4 col-12 col-lg-6">
                        <input type="text" name="telefono" class="input input__text" id="telefono"
                            placeholder="telefono" value="{{ $item->telefono }}">
                    </div>

                    <div class="fl-flex-label mb-4 col-12 col-lg-6">
                        <input type="text" name="correo" class="input input__text" placeholder="Correo *"
                            value="{{ old('correo', $item->correo) }}">
                    </div>
                    <div class="fl-flex-label mb-4 col-12 col-lg-12">
                        <textarea name="descripcion" rows="3" class="input input__text" placeholder="Descripción">{{ $item->descripcion }}</textarea>
                    </div>

                    <div class="text-right mt-0">
                        <button type="submit" class="btn btn-rounded btn-primary">Guardar</button>
                    </div>
                </div>

            </form>
        @endforeach
    </div>

@endsection
