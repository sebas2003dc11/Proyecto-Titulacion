@extends('layouts/app')
@section('titulo', 'Registro de envios')
@section('content')




    <h4 class="text-center text-secondary">DATOS DEL CONSIGNATARIO</h4>
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
            <form action="{{ route('receptor.update', $datos->id_receptor) }}" method="POST" id="formActualizar">
                @csrf
                @method('PUT')
                <div class="fl-flex-label mb-4 px-2 col-12">
                    <input required type="text" placeholder="DNI" class="input input__text" name="dni"
                        value="{{ old('dni', $datos->dni) }}">
                    @error('dni')
                        <span class="text-danger font-weight-bold">{{ $message }}</span>
                    @enderror
                </div>
                <div class="fl-flex-label mb-4 px-2 col-12">
                    <input required type="text" placeholder="Nombre o Razon Social" class="input input__text"
                        name="nombre" value="{{ old('nombre', $datos->nombre_razon_social) }}">
                    @error('nombre')
                        <span class="text-danger font-weight-bold">{{ $message }}</span>
                    @enderror
                </div>


                <div class="fl-flex-label mb-4 px-2 col-12">
                    <input type="text" placeholder="Dirección" class="input input__text" name="direccion"
                        value="{{ old('direccion', $datos->direccion) }}">
                </div>
                <div class="fl-flex-label mb-4 px-2 col-12">
                    <input type="text" placeholder="Teléfono" class="input input__text" name="telefono"
                        value="{{ old('telefono', $datos->telefono) }}">
                </div>

            </form>

            <div class="text-right p-2 d-flex justify-content-end flex-wrap gap-2 col-12">
                <a href="{{ route('receptor.index') }}" class="btn btn-secondary"><i class="fas fa-caret-left"></i>
                    Atras</a>


                <form action="{{ route('receptor.destroy', $datos->id_receptor) }}" method="POST"
                    class="d-inline formulario-eliminar">
                    @csrf
                    @method('DELETE')
                </form>
                <a class="btn btn-danger eliminar" data-id="{{ $datos->id_receptor }}"><i class="fas fa-trash-alt"></i>
                    Eliminar Registro</a>

                <button form="formActualizar" type="submit" value="ok" name="btnmodificar" class="btn bg-primary"><i
                        class="fas fa-save"></i> Modificar</button>
            </div>
        </div>
    @endforeach





@endsection
