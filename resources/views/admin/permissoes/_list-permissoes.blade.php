
<div class="col-12">
    <div class="row bg-dark arial16-font text-light text-bold m-0 py-2">
        <div class="col-4">Nome</div>
        <div class="col">Permissoes</div>
    </div>
</div>
<div class="col-12 mt-4">
@foreach($usuarios as $k => $user)
    <div class="row m-0 py-2 border-bottom arial14-font-normal ">

        <div class="col-4">
            {{$user->name ?? ''}}
        </div>
        <div class="col">
            <a href="{{route('admin.permissoes.permissoes', $user->id)}}" target="_blank" class="btn btn-sm btn-primary">Ver</a>
        </div>
    </div>
@endforeach
</div>
            
    
          


