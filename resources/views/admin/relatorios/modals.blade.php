<!-- TODOS OS PEDIDOS -->
<div class="modal fade modalFormulario" id="modalPedidos" tabindex="-1" aria-labelledby="modalPedidosLabel" aria-hidden="true">
  <div class="modal-dialog" style="max-width: 800px">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalPedidosLabel">Relátorio de Pedidos</h5>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
      </div>
      <div class="modal-body">
        <form action="{{route('admin.relatorios.pedidosFiltro')}}" class="filterForm" method="GET" enctype="multipart/form-data">
          @csrf
          <div class="row">
            <div class="col-6">
              <strong>Por Data</strong>
              <div class="d-flex" id="data_especifica_pedido">
                <input type="date" class="form-control" name="data_inicio" id="data_inicio_1" required placeholder="DD/MM/AAAA" style="border-radius: .5rem .0rem .0rem .5rem !important;">
                <span class="form-control" style="border-radius: 0 !important; width: 100px">até</span>
                <input type="date" class="form-control" name="data_fim" id="data_fim_1" required placeholder="DD/MM/AAAA" style="border-radius: .0rem .5rem .5rem .0rem !important;">
              </div>
              <div class="row mt-3" id="por_periodo_pedido" style="opacity: 0.5;">
                <strong>Por Período</strong>
                <div class="col-8">
                  <select class="form-select" name="meses" id="meses" disabled>
                    @php
                    $months = [
                    1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
                    5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
                    9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
                    ];
                    @endphp
                    @foreach($months as $number => $name)
                    <option value="{{ $number }}">{{ $name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-4">
                  <select class="form-select" name="ano" id="ano" disabled>
                    @for($year = date('Y'); $year >= 1950; $year--)
                    <option value="{{ $year }}">{{ $year }}</option>
                    @endfor
                  </select>
                </div>
              </div>
            </div>
            <div class="col-6">
              <div class="form-check">
                <input class="form-check-input recente" type="radio" name="recente" id="hoje" value="hoje" style="width: 20px; height: 20px;border-radius: 5px;" value="hoje" data-nome="pedido">
                <label class="form-check-label" for="hoje">
                  Hoje
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input recente" type="radio" name="recente" id="esta_semana" value="esta_semana" style="width: 20px; height: 20px;border-radius: 5px;" data-nome="pedido">
                <label class="form-check-label" for="esta_semana">
                  Esta Semana
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input recente" type="radio" name="recente" id="data_especifica_1" value="data_especifica" checked style="width: 20px; height: 20px;border-radius: 5px;" data-nome="pedido">
                <label class="form-check-label" for="data_especifica">
                  Data Específica
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input recente" type="radio" name="recente" id="por_periodo" value="por_periodo" style="width: 20px; height: 20px;border-radius: 5px;" data-nome="pedido">
                <label class="form-check-label" for="por_periodo">
                  Por Periodo
                </label>
              </div>
            </div>
          </div>

          <div class="row mt-4">
            <div class="col-6">
            @if (Auth::user()->role != 'franqueado')
                <strong>Franquia</strong>
                <select name="franquia" class="form-select">
                  <option value="todas">Todas</option>
                  <option value="toy">Toy</option>
                  <option value="trip">Trip</option>
                </select>
            @endif
            </div>
            <div class="col-6">
              <strong>Situação</strong>
              <div class="col-12">
                @foreach($status as $sta)
                <div class="form-check ps-0">
                  <input class="situacao" type="checkbox" name="situacao[]" id="{{$sta->nome}}" value="{{$sta->id}}">
                  <label class="form-check-label2 me-3" for="{{$sta->nome}}">{{$sta->nome}}</label>
                </div>
                @endforeach
              </div>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-6">
              <strong>Cliente</strong>
              <select class="form-select" name="cliente">
                <option value="todos">Todos</option>
                @foreach($clientes as $k => $cliente)
                <option value="{{$cliente->id}}">{{$cliente->name}}</option>
                @endforeach
              </select>
            </div>
            <div class="col-6">
              <strong>Produto</strong>
              <select class="form-select" name="produto">
                <option value="todos">Todos</option>
                @foreach($produtos as $k => $prod)
                <option value="{{$prod->id}}">{{$prod->nome}}</option>
                @endforeach
              </select>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Fechar</button>
        <button type="submit" class="btn btn-primary">Gerar</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- PEDIDOS POR CLIENTES -->
<div class="modal fade modalFormulario" id="modalClientes" tabindex="-1" aria-labelledby="modalClientesLabel" aria-hidden="true">
  <div class="modal-dialog" style="max-width: 800px">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalClientesLabel">Relátorio de Pedidos por Cliente</h5>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
      </div>
      <div class="modal-body">
        <form action="{{route('admin.relatorios.pedidosCliente')}}" class="filterForm" method="GET" enctype="multipart/form-data">
          @csrf
          <div class="row">
            <strong>Cliente</strong>
            <div class="col-9">
              <select name="cliente" required id="" class="form-select">
                <option value="">Selecione</option>
                @foreach($clientes as $k => $cliente)
                <option value="{{$cliente->id}}">{{$cliente->name}}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="row my-4">
            <div class="col-6">
              <input type="radio" name="pedidos" value="ativos"><strong class="mx-2">Pedidos Ativos</strong>
            </div>
            <div class="col-6">
              <input type="radio" name="pedidos" value="todos" checked><strong class="mx-2">Todos Pedidos</strong>
            </div>
          </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Fechar</button>
        <button type="submit" class="btn btn-primary">Gerar</button>
      </div>
      </form>
    </div>
  </div>
</div>


<!-- PEDIDOS SEM PAGAMENTOS -->
<div class="modal fade modalFormulario" id="modalSemPagto" tabindex="-1" aria-labelledby="modalSemPagtoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalSemPagtoLabel">Relátorio de Pedidos Confirmados Sem Pagamento</h5>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
      </div>


      <div class="modal-body">

        <form action="{{route('admin.relatorios.pedidosSemPagamento')}}" class="filterForm" method="GET" enctype="multipart/form-data">
          @csrf
          <div class="col-12">
            @if (Auth::user()->role != 'franqueado')
            <label for="">Franquia</label>
            <select name="franquia" class="form-select">
              <option value="todas">Todas</option>
              <option value="toy">Toy</option>
              <option value="trip">Trip</option>
            </select>
            @endif
          </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Fechar</button>
        <button type="submit" class="btn btn-primary">Gerar</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- ITENS PARA LOGÍSTICA  -->
<div class="modal fade modalFormulario" id="modalItensLogistica" tabindex="-1" aria-labelledby="modalItensLogisticaLabel" aria-hidden="true">
  <div class="modal-dialog" style="max-width: 800px">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalItensLogisticaLabel">Relátorio de Separação de Itens para Logística</h5>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
      </div>

      <div class="modal-body">

        <form action="{{route('admin.relatorios.itemLogistica')}}" class="filterForm" method="GET" enctype="multipart/form-data">
          @csrf

          <div class="row">
            <div class="col-6">
              <strong>Por Data</strong>
              <div class="d-flex" id="data_especifica_itens_logistica">
                <input type="date" class="form-control" name="data_inicio" id="data_inicio_1" required placeholder="DD/MM/AAAA" style="border-radius: .5rem .0rem .0rem .5rem !important;">
                <span class="form-control" style="border-radius: 0 !important; width: 100px">até</span>
                <input type="date" class="form-control" name="data_fim" id="data_fim_1" required placeholder="DD/MM/AAAA" style="border-radius: .0rem .5rem .5rem .0rem !important;">
              </div>
              <div class="row mt-3" id="por_periodo_itens_logistica" style="opacity: 0.5;">
                <strong>Por Período</strong>
                <div class="col-8">
                  <select class="form-select" name="meses" id="meses" disabled>
                    @php
                    $months = [
                    1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
                    5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
                    9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
                    ];
                    @endphp
                    @foreach($months as $number => $name)
                    <option value="{{ $number }}">{{ $name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-4">
                  <select class="form-select" name="ano" id="ano" disabled>
                    @for($year = date('Y'); $year >= 1950; $year--)
                    <option value="{{ $year }}">{{ $year }}</option>
                    @endfor
                  </select>
                </div>
              </div>
            </div>
            <div class="col-6">
              <div class="form-check">
                <input class="form-check-input recente" type="radio" name="recente" id="hoje" value="hoje" style="width: 20px; height: 20px;border-radius: 5px;" value="hoje" data-nome="itens_logistica">
                <label class="form-check-label" for="hoje">
                  Hoje
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input recente" type="radio" name="recente" id="esta_semana" value="esta_semana" style="width: 20px; height: 20px;border-radius: 5px;" data-nome="itens_logistica">
                <label class="form-check-label" for="esta_semana">
                  Esta Semana
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input recente" type="radio" name="recente" id="data_especifica_1" value="data_especifica" checked style="width: 20px; height: 20px;border-radius: 5px;" data-nome="itens_logistica">
                <label class="form-check-label" for="data_especifica">
                  Data Específica
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input recente" type="radio" name="recente" id="por_periodo" value="por_periodo" style="width: 20px; height: 20px;border-radius: 5px;" data-nome="itens_logistica">
                <label class="form-check-label" for="por_periodo">
                  Por Periodo
                </label>
              </div>
            </div>
          </div>

          <div class="row mt-4">
            <div class="col-6">
            @if (Auth::user()->role != 'franqueado')
                <strong>Franquia</strong>
                <select name="franquia" class="form-select">
                  <option value="todas">Todas</option>
                  <option value="toy">Toy</option>
                  <option value="trip">Trip</option>
                </select>
            @endif
            </div>
          </div>
          <hr>

          <div class="row">
            <div class="col-6">
              <strong>Tipo de Logística</strong>
              <select class="form-select" name="tipo_logistica">
                <option value="todos">Todos</option>
                <option value="frete">Frete</option>
                <option value="retirar_loja">Retirar na Loja</option>
              </select>
            </div>
            <div class="col-6">
              <strong>Tipo de Movimentação</strong>
              <select class="form-select" name="tipo_movimentacao">
                <option value="todos">Todos</option>
                <option value="entrega">Entrega</option>
                <option value="devolucao">Devolução</option>
              </select>
            </div>
          </div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Fechar</button>
        <button type="submit" class="btn btn-primary">Gerar</button>
      </div>
      </form>
    </div>
  </div>
</div>


<!-- LOGÍSTICA  -->
<div class="modal fade modalFormulario" id="modalLogistica" tabindex="-1" aria-labelledby="modalLogisticaLabel" aria-hidden="true">
  <div class="modal-dialog" style="max-width: 800px">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLogisticaLabel">Relátorio de Logística</h5>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
      </div>

      <div class="modal-body">

        <form action="{{route('admin.relatorios.logistica')}}" class="filterForm" method="GET" enctype="multipart/form-data">
          @csrf

          <div class="row">
            <div class="col-6">
              <strong>Por Data</strong>
              <div class="d-flex" id="data_especifica_logistica">
                <input type="date" class="form-control" name="data_inicio" id="data_inicio_1" required placeholder="DD/MM/AAAA" style="border-radius: .5rem .0rem .0rem .5rem !important;">
                <span class="form-control" style="border-radius: 0 !important; width: 100px">até</span>
                <input type="date" class="form-control" name="data_fim" id="data_fim_1" required placeholder="DD/MM/AAAA" style="border-radius: .0rem .5rem .5rem .0rem !important;">
              </div>
              <div class="row mt-3" id="por_periodo_logistica" style="opacity: 0.5;">
                <strong>Por Período</strong>
                <div class="col-8">
                  <select class="form-select" name="meses" id="meses" disabled>
                    @php
                    $months = [
                    1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
                    5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
                    9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
                    ];
                    @endphp
                    @foreach($months as $number => $name)
                    <option value="{{ $number }}">{{ $name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-4">
                  <select class="form-select" name="ano" id="ano" disabled>
                    @for($year = date('Y'); $year >= 1950; $year--)
                    <option value="{{ $year }}">{{ $year }}</option>
                    @endfor
                  </select>
                </div>
              </div>
            </div>
            <div class="col-6">
              <div class="form-check">
                <input class="form-check-input recente" type="radio" name="recente" id="hoje" value="hoje" style="width: 20px; height: 20px;border-radius: 5px;" value="hoje" data-nome="logistica">
                <label class="form-check-label" for="hoje">
                  Hoje
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input recente" type="radio" name="recente" id="esta_semana" value="esta_semana" style="width: 20px; height: 20px;border-radius: 5px;" data-nome="logistica">
                <label class="form-check-label" for="esta_semana">
                  Esta Semana
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input recente" type="radio" name="recente" id="data_especifica_1" value="data_especifica" checked style="width: 20px; height: 20px;border-radius: 5px;" data-nome="logistica">
                <label class="form-check-label" for="data_especifica">
                  Data Específica
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input recente" type="radio" name="recente" id="por_periodo" value="por_periodo" style="width: 20px; height: 20px;border-radius: 5px;" data-nome="logistica">
                <label class="form-check-label" for="por_periodo">
                  Por Periodo
                </label>
              </div>
            </div>
          </div>

          <div class="row mt-4">
            <div class="col-6">
              <strong>Franquia</strong>
              @if(Auth::user()->role != 'franqueado')
                  <select name="franquia" class="form-select">
                    <option value="todas">Todas</option>
                    <option value="toy">Toy</option>
                    <option value="trip">Trip</option>
                  </select>
              @endif
            </div>
          </div>
          <hr>


          <div class="row">
            <div class="col-6">
              <strong>Tipo de Logística</strong>
              <select class="form-select" name="tipo_logistica">
                <option value="todos">Todos</option>
                <option value="frete">Frete</option>
                <option value="retirar_loja">Retirar na Loja</option>
              </select>
            </div>
            <div class="col-6">
              <strong>Tipo de Movimentação</strong>
              <select class="form-select" name="tipo_movimentacao">
                <option value="todos">Todos</option>
                <option value="entrega">Entrega</option>
                <option value="devolucao">Devolução</option>
              </select>
            </div>
          </div>
          <div class="row">
            <strong>Local de Entrega</strong>
            <div class="col-12">
              <select class="form-select" name="local">
                <option value="" selected>Selecione Local</option>
                @foreach($regioes as $regiao)
                <option value="{{$regiao->id}}">{{ $regiao->bairro}}, {{ $regiao->cidade}} - {{ $regiao->estado}}</option>
                @endforeach
              </select>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Fechar</button>
        <button type="submit" class="btn btn-primary">Gerar</button>
      </div>
      </form>
    </div>
  </div>
</div>


<!-- TRANSAÇÕES financeiroS -->

<div class="modal fade modalFormulario" id="modalfinanceiro" tabindex="-1" aria-labelledby="modalfinanceiroLabel" aria-hidden="true">
  <div class="modal-dialog" style="max-width: 800px">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalfinanceiroLabel">Relátorio de Transações Financeiras</h5>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
      </div>

      <div class="modal-body">

        <form action="{{route('admin.relatorios.financeiro')}}" class="filterForm" method="GET" enctype="multipart/form-data">
          @csrf

          <div class="row">

            <div class="col-6">
              <strong>Por Data</strong>

              <div class="d-flex" id="data_especifica_financeiro">
                <input type="date" class="form-control" name="data_inicio" placeholder="DD/MM/AAAA" style="border-radius: .5rem .0rem .0rem .5rem !important;">
                <span class="form-control" style="border-radius: 0 !important; width: 100px">até</span>
                <input type="date" class="form-control" name="data_fim" placeholder="DD/MM/AAAA" style="border-radius: .0rem .5rem .5rem .0rem !important;">
              </div>

              <div class="row mt-3" id="por_periodo_financeiro" style="opacity: 0.5;">
                <strong>Por Período</strong>
                <div class="col-8">
                  <select class="form-select" name="meses" disabled>
                    @php
                    $months = [
                    1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
                    5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
                    9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
                    ];
                    @endphp
                    @foreach($months as $number => $name)
                    <option value="{{ $number }}">{{ $name }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="col-4">
                  <select class="form-select" name="ano" disabled>
                    @for($year = date('Y'); $year >= 1950; $year--)
                    <option value="{{ $year }}">{{ $year }}</option>
                    @endfor
                  </select>
                </div>
              </div>
            </div>


            <div class="col-6">
              <div class="form-check">
                <input class="form-check-input recente" type="radio" name="recente" id="hoje" style="width: 20px; height: 20px;border-radius: 5px;" value="hoje" data-nome="financeiro">
                <label class="form-check-label" for="hoje">
                  Hoje
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input recente" type="radio" name="recente" id="esta_semana" value="esta_semana" style="width: 20px; height: 20px;border-radius: 5px;" data-nome="financeiro">
                <label class="form-check-label" for="esta_semana">
                  Esta Semana
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input recente" type="radio" name="recente" id="data_especifica" value="data_especifica" checked style="width: 20px; height: 20px;border-radius: 5px;" data-nome="financeiro">
                <label class="form-check-label" for="data_especifica">
                  Data Específica
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input recente" type="radio" name="recente" id="por_periodo" value="por_periodo" style="width: 20px; height: 20px;border-radius: 5px;" data-nome="financeiro">
                <label class="form-check-label" for="por_periodo">
                  Por Periodo
                </label>
              </div>
            </div>
          </div>

          <hr>

          <div class="row">
            <div class="col-6">
              <strong>Situação da Transação</strong>
              <select class="form-select" name="situacao_financeiro">
                <option value="todos">Todos</option>
                <option value="pendentes">Pendentes</option>
                <option value="pagos">Pagos</option>
              </select>
            </div>
            <div class="col-6">
              <strong>Formas de Pagamentos</strong>
              <select class="form-select" name="forma_pagamento">
                <option value="todos">Todos</option>
                @foreach ($formas_pagamento as $forma_pagamento)
                <option value="{{$forma_pagamento->id}}">{{$forma_pagamento->nome}}</option>
                @endforeach
              </select>
            </div>
          </div>
          <hr>

          <div class="row">
            <div class="col-7">
              <strong>Clientes</strong>
              <select class="form-select" name="clientes">
                <option value="todos">Todos</option>
                @foreach ($clientes as $cliente)
                <option value="{{$cliente->id}}">{{$cliente->name}}</option>
                @endforeach
              </select>
            </div>
            <div class="col-5">
            @if(Auth::user()->role != 'franqueado')
                <strong>Franquia</strong>
                <select name="franquia" class="form-select">
                  <option value="todas">Todas</option>
                  <option value="toy">Toy</option>
                  <option value="trip">Trip</option>
                </select>
              @endif
            </div>
          </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Fechar</button>
        <button type="submit" class="btn btn-primary">Gerar</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- PAGAMENTOS DOS PEDIDOS -->

<div class="modal fade modalFormulario" id="modalpagamentos" tabindex="-1" aria-labelledby="modalpagamentosLabel" aria-hidden="true">
  <div class="modal-dialog" style="max-width: 800px">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalpagamentosLabel">Relátorio de Pagamentos dos Pedidos</h5>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
      </div>

      <div class="modal-body">

        <form action="{{route('admin.relatorios.pagamentos')}}" class="filterForm" method="GET" enctype="multipart/form-data">
          @csrf

          <div class="row mb-3">
            <div class="col-6">
            @if(Auth::user()->role != 'franqueado')
                <strong>Franquia</strong>
                <select name="franquia" class="form-select">
                  <option value="todas">Todas</option>
                  <option value="toy">Toy</option>
                  <option value="trip">Trip</option>
                </select>
            @endif
            </div>
          </div>

          <div class="row">

            <div class="col-12">
              <div class="d-flex">
                <input type="date" class="form-control" name="data_inicio" placeholder="DD/MM/AAAA" required style="border-radius: .5rem .0rem .0rem .5rem !important;">
                <span class="form-control" style="border-radius: 0 !important; width: 100px">até</span>
                <input type="date" class="form-control" name="data_fim" placeholder="DD/MM/AAAA" required style="border-radius: .0rem .5rem .5rem .0rem !important;">
              </div>
            </div>
          </div>

          <hr>

          <div class="row">
            <div class="col-6">
              <strong>Situação da Transação</strong>
              <select class="form-select" name="situacao_pagamentos">
                <option value="todos">Todos</option>
                <option value="pendentes">Pendentes</option>
                <option value="pagos">Pagos</option>
              </select>
            </div>
            <div class="col-6">
              <strong>Formas de Pagamentos</strong>
              <select class="form-select" name="forma_pagamento">
                <option value="todos">Todos</option>
                @foreach ($formas_pagamento as $forma_pagamento)
                <option value="{{$forma_pagamento->id}}">{{$forma_pagamento->nome}}</option>
                @endforeach
              </select>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Fechar</button>
        <button type="submit" class="btn btn-primary">Gerar</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- Clientes -->

<div class="modal fade modalFormulario" id="modalclientes" tabindex="-1" aria-labelledby="modalclientesLabel" aria-hidden="true">
  <div class="modal-dialog" style="max-width: 800px">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalclientesLabel">Relátorio de clientes dos Pedidos</h5>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
      </div>

      <div class="modal-body">

        <form action="{{route('admin.relatorios.clientes')}}" class="filterForm" method="GET" enctype="multipart/form-data">
          @csrf

          <div class="row">

            <div class="col-6">
              <div class="row">
                <div class="col-12">
                  <strong>Palavra</strong>
                  <input type="text" class="form-control" name="palavra">
                </div>
                <div class="col-12 mt-2">
                  <strong>Tipo do Cliente</strong>
                  <select class="form-select" name="pessoa">
                    <option value="todos">Todos</option>
                    <option value="juridica">Pessoa Jurídica</option>
                    <option value="fisica">Pessoal Física</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="col-6">
              <strong>Campos do Relatório</strong>
              <div class="col-12">
                <input class="dados" type="checkbox" name="dados[]" id="nome" value="name" checked>
                <label class="form-check-label2" for="nome">Nome</label>
              </div>
              <div class="col-12">
                <input class="dados" type="checkbox" name="dados[]" id="email" value="email" checked>
                <label class="form-check-label2" for="email">Email</label>
              </div>
              <div class="col-12">

                <input class="dados" type="checkbox" name="dados[]" id="cpf/cnpj" value="cpf/cnpj" checked>
                <label class="form-check-label2" for="cpf/cnpj">CPF/CNPJ</label>
              </div>
              <div class="col-12">
                <input class="dados" type="checkbox" name="dados[]" id="data_nascimento" value="data_nascimento">
                <label class="form-check-label2" for="data_nascimento">Data Nascimento</label>
              </div>
              <div class="col-12">
                <input class="dados" type="checkbox" name="dados[]" id="celular" value="celular" checked>
                <label class="form-check-label2" for="celular">Celular</label> <br>
              </div>
              <div class="col-12">
                <input class="dados" type="checkbox" name="dados[]" id="telefone" value="telefone" checked>
                <label class="form-check-label2" for="telefone">Telefone</label>
              </div>
              <div class="col-12">
                <input class="dados" type="checkbox" name="dados[]" id="enderecos" value="enderecos">
                <label class="form-check-label2" for="enderecos">Enderecos</label>
              </div>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Fechar</button>
        <button type="submit" class="btn btn-primary">Gerar</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- Clientes que mais realizam pedidos -->

<div class="modal fade modalFormulario" id="modalClientesMaisPedidos" tabindex="-1" aria-labelledby="modalClientesMaisPedidosLabel" aria-hidden="true">
  <div class="modal-dialog" style="max-width: 800px">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalClientesMaisPedidosLabel">Relátorio de Clientes que Mais Realizam Pedidos</h5>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
      </div>

      <div class="modal-body">

        <form action="{{route('admin.relatorios.ClientesMaisPedidos')}}" class="filterForm" method="GET" enctype="multipart/form-data">
          @csrf

          <div class="row">

            <div class="col-12">
              <div class="row mt-3" id="por_periodo_ClientesMaisPedidos">
                <strong>Por Período</strong>
                <div class="col-8">
                  <select class="form-select" name="meses">
                    @php
                    $months = [
                    1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
                    5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
                    9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
                    ];
                    @endphp
                    <option value="todos">Todos</option>
                    @foreach($months as $number => $name)
                    <option value="{{ $number }}">{{ $name }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="col-4">
                  <select class="form-select" name="ano">
                    @for($year = date('Y'); $year >= 1950; $year--)
                    <option value="{{ $year }}">{{ $year }}</option>
                    @endfor
                  </select>
                </div>
              </div>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Fechar</button>
        <button type="submit" class="btn btn-primary">Gerar</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- Cliente que mais gastam -->

<div class="modal fade modalFormulario" id="modalClientesMaisGastam" tabindex="-1" aria-labelledby="modalClientesMaisGastamLabel" aria-hidden="true">
  <div class="modal-dialog" style="max-width: 800px">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalClientesMaisGastamLabel">Relátorio de Clientes que Mais Gastam</h5>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
      </div>

      <div class="modal-body">

        <form action="{{route('admin.relatorios.ClientesMaisGastam')}}" class="filterForm" method="GET" enctype="multipart/form-data">
          @csrf

          <div class="row">
            <div class="col-12">
              <div class="row mt-3" id="por_periodo_ClientesMaisGastam">
                <strong>Por Período</strong>
                <div class="col-8">
                  <select class="form-select" name="meses">
                    @php
                    $months = [
                    1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
                    5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
                    9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
                    ];
                    @endphp
                    <option value="todos">Todos</option>
                    @foreach($months as $number => $name)
                    <option value="{{ $number }}">{{ $name }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="col-4">
                  <select class="form-select" name="ano">
                    @for($year = date('Y'); $year >= 1950; $year--)
                    <option value="{{ $year }}">{{ $year }}</option>
                    @endfor
                  </select>
                </div>
              </div>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Fechar</button>
        <button type="submit" class="btn btn-primary">Gerar</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- CurvaABC-->

<div class="modal fade modalFormulario" id="modalCurvaABC" tabindex="-1" aria-labelledby="modalCurvaABCLabel" aria-hidden="true">
  <div class="modal-dialog" style="max-width: 800px">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalCurvaABCLabel">Relátorio Curva ABC</h5>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
      </div>

      <div class="modal-body">

        <form action="{{route('admin.relatorios.curvaABC')}}" class="filterForm" method="GET" enctype="multipart/form-data">
          @csrf

          <div class="row">

            <div class="col-12">
              <div class="row mt-3">
                <div class="col-6 mb-3">
                @if (Auth::user()->role != 'franqueado')
                      <strong>Franquia</strong>
                      <select name="franquia" class="form-select">
                        <option value="todas">Todas</option>
                        <option value="toy">Toy</option>
                        <option value="trip">Trip</option>
                      </select>
                  @endif
                </div>

                <strong>Por Período</strong>
                <div class="col-8">
                  <select class="form-select" name="meses">
                    @php
                    $months = [
                    1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
                    5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
                    9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
                    ];
                    @endphp
                    <option value="todos">Todos</option>
                    @foreach($months as $number => $name)
                    <option value="{{ $number }}">{{ $name }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="col-4">
                  <select class="form-select" name="ano">
                    @for($year = date('Y'); $year >= 1950; $year--)
                    <option value="{{ $year }}">{{ $year }}</option>
                    @endfor
                  </select>
                </div>
              </div>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Fechar</button>
        <button type="submit" class="btn btn-primary">Gerar</button>
      </div>
      </form>
    </div>
  </div>
</div>


<!-- ESTOQUE -->
 
<div class="modal fade modalFormulario" id="modalEstoque" tabindex="-1" aria-labelledby="modalEstoqueLabel" aria-hidden="true">
  <div class="modal-dialog" style="max-width: 800px">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEstoqueLabel">Relátorio de estoque</h5>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
      </div>

      <div class="modal-body">

        <form action="{{route('admin.relatorios.itensEstoque')}}" class="filterForm" method="GET" enctype="multipart/form-data">
          @csrf

          <div class="row">

            <div class="col-12">
              <div class="row mt-3">
                <div class="col-6 mb-3">
                @if (Auth::user()->role != 'franqueado')
                      
                      <strong>Franquia</strong>
                      <select name="franquia" class="form-select select2">
                        <option value="todas">Todas</option>
                        @foreach($franquias as $franquia)
                        <option value="{{$franquia->id}}">{{$franquia->nome_franquia}}</option>
                        @endforeach
                      </select>
                  @endif
                </div>

                <strong>Produtos</strong>
                <select name="produto" class="form-select" id="">
                  <option value="todos">Todos</option>
                @foreach($produtos as $produto)
                    <option value="{{$produto->id}}">{{$produto->nome}}</option>
                @endforeach
              </select>
              </div>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Fechar</button>
        <button type="submit" class="btn btn-primary">Gerar</button>
      </div>
      </form>
    </div>
  </div>
</div>