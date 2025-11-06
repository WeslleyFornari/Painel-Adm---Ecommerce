@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-7 ps-4 my-2">
                        <h4>{{$usuario->name}}</h4>          
                    </div>
                </div>
                <form action="{{route('admin.permissoes.store', $usuario->id)}}" id="formStore" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-4">
                                    Página
                                </div>
                                <div class="col-2">
                                    Visualizar
                                </div>
                                <div class="col-2">
                                    Criar
                                </div>
                                <div class="col-2">
                                    Editar
                                </div>
                                <div class="col-2">
                                    Deletar
                                </div>
                            </div>
                            @foreach ($permissoes as $permissao)
                                <div class="row mt-4">
                                    <div class="col-4">
                                        {{$permissao->pagina->titulo}}
                                        <input type="hidden" name="permissoes[id][]" value="{{$permissao->id}}">
                                        <input type="hidden" name="permissoes[pagina][{{$permissao->id}}]" value="{{$permissao->pagina->id}}">
                                    </div>
                                    <div class="col-2">
                                    @if ($permissao->pagina->visualizar == 'sim')
                                        <input type="checkbox" id="{{$permissao->pagina->slug}}_visualizar" name="permissoes[visualizar][{{$permissao->id}}]" value="sim"
                                        @if ($permissao->visualizar == 'sim') checked @endif>
                                    @endif
                                    </div>
                                    <div class="col-2">
                                    @if ($permissao->pagina->criar == 'sim')
                                        <input type="checkbox" id="{{$permissao->pagina->slug}}_criar" name="permissoes[criar][{{$permissao->id}}]" value="sim"
                                        @if ($permissao->criar == 'sim') checked @endif>
                                    @endif
                                    </div>
                                    <div class="col-2">
                                    @if ($permissao->pagina->editar == 'sim')
                                        <input type="checkbox" id="{{$permissao->pagina->slug}}_editar" name="permissoes[editar][{{$permissao->id}}]" value="sim"
                                        @if ($permissao->editar == 'sim') checked @endif>
                                    @endif
                                    </div>
                                    <div class="col-2">
                                    @if ($permissao->pagina->deletar == 'sim')
                                        <input type="checkbox" id="{{$permissao->pagina->slug}}_deletar" name="permissoes[deletar][]" value="sim"
                                        @if ($permissao->deletar == 'sim') checked @endif>
                                    @endif
                                    </div>
                                </div>
                                <hr>
                            @endforeach
                        </div>
                    </div>

                   
                    <div class="row">
                        <div class="col"> 
                            <a class="btn btn-secondary m-0" href="{{route('admin.permissoes.index')}}">Voltar</a>
                        </div>
                        <div class="col text-end">
                            <button type="submit" class="btn btn-success">Salvar</button>
                        </div>
                    </div>
                </form>
           </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$("#formStore").submit(function (e) {
    e.preventDefault();
    $("span.error").remove();
    $.ajax({
        type: "POST",
        url: $(this).attr('action'),
        data: $(this).serialize(),
        success: function (data) {
            swal({
                title: "Parabéns",
                text: "Permissões alterada com sucesso!.",
                icon: "success",
            }).then(function() {
                location.reload();
            });
            $("#formStore")[0].reset();
            $(".disabled").remove();
        },
        error: function (err) {
            console.log(err);
            if (err.status == 422) {
                console.log(err.responseJSON);
                $('#success_message').fadeIn().html(err.responseJSON.message);
                $.each(err.responseJSON.errors, function (i, error) {
                    var el = $(document).find('[name="' + i + '"]');
                    el.after($('<span class="error" style="color: red;">' + error[0] + '</span>'));
                });
            }
        }
    });
});
</script>
@endsection
