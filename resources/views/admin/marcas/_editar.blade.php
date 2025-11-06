    <!--FORMULARIO EDITAR-->


    <form action="{{route('admin.marcas.update', ['id'=>$marca->id])}}" class="formStore" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">

            <div class="col-sm-6">
                <label class="titulo">Nome: *</label>
                <input type="text" name="nome" id="nome" value="{{ $marca->nome ?? '' }}" class="form-control" required>
            </div>
            <div class="col-sm-6">
                <label for="media_id" class="titulo" id="media_id">Foto: *</label>
                <x-upload-file target="logo" collum="media_id" :media="$marca" type="single" />
                @error('media_id')
                <div class="text-danger mt-2 text-sm">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12">
                <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Atualizar</button>
            </div>
        </div>

    </form>