

<a href="#" class="tooglegeCollapse float-end" data-target="#collapse-Cupom"><i class="fas fa-times"></i></a> <!-- X -->

<!--FORMULARIO CADASTRO-->
<form action="{{route('admin.cupons.store')}}" id="formStore" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id">
    <div class="row mt-2 arial12-font">

        <div class="form-group col-sm-4">
            <span class="titulo"> Código: *</span>
            <input type="text" name="codigo" class="form-control text-uppercase" required>
        </div>
        <div class="form-group col-sm-4">
            <span class="titulo"> Modalidade: *</span>
            <select name="modalidade" required id="" class="form-select">
                <option value="">Selecione</option>
                <option value="frete">Frete</option>
                <option value="produtos">Produtos</option>
            </select>
        </div>
        <div class="form-group col-sm-4">
            <span class="titulo"> Tipo: *</span>
            <select name="tipo" required id="" class="form-select">
                <option value="">Selecione</option>
                <option value="porcentagem">Porcentagem</option>
                <option value="real">Real</option>
            </select>
        </div>

        @if(Auth::user()->role == 'franqueado')
            <input type="hidden" name="tipo_franqueado" id="tipo_franqueado" value="{{Auth::user()->franquia->tipo_franqueado}}">
        @elseif(Auth::user()->role != 'franqueado')
        <div class="form-group col-sm-3">
            <span class="titulo"> Tipo Franqueado: *</span>
            <select class="form-select" name="tipo_franqueado" id="tipo_franqueado" required>
                <option value="toy">TOY</option>
                <option value="trip">TRIP</option>
            </select>
        </div>
        @endif

        <div class="form-group col-sm-3">
            <span class="titulo"> Quantidade: *</span>
            <input type="number" name="qtd" class="form-control" required min="1">
        </div>
        <div class="form-group col-sm-3">
            <span class="titulo"> Valor: *</span>
            <input type="text" name="valor" class="form-control moneyMask" required>
        </div>
        <div class="form-group col-sm-3">
            <span class="titulo"> Valor Minimo: *</span>
            <input type="text" name="valor_minimo" class="form-control moneyMask" required>
        </div>
       

        @if(Auth::user()->role != 'franqueado')
        <div class="form-group col-sm-6" id="card_franquia">
            <span class="titulo"> Nome da Franquia: *</span>
            <select class="form-select" name="id_franquia" id="id_franquia" required>
                <option value="">Selecione</option>
                @foreach($franquias as $k => $value)
                <option value="{{ $value->id }}">{{ $value->nome_franquia }}</option>
                @endforeach
            </select>
        </div>
        @elseif(Auth::user()->role == 'franqueado' && Auth::user()->franquia->tipo_franqueado == 'toy')
            <input type="hidden" value="{{Auth::user()->franquia->id}}" name="id_franquia" id="id_franquia">
        @endif

        <div class="col-sm-6 mt-3 pe-3 text-end">
            <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i> Salvar</button>
        </div>

    </div>

</form>