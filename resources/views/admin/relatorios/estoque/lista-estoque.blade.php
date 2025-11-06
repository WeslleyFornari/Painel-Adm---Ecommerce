<div class="card-body">
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th width="80">Foto</th>
                    <th width="100">Código</th>
                    <th>Nome do Produto</th>
                    <th width="100">Estoque</th>
                    <th width="150">Valor de Compra</th>
                    <th width="150">Data de Compra</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($estoque as $estoque)
                <tr>
                    <td class="text-center">
                        @if($estoque->produto && $estoque->produto->fotoprincipal && $estoque->produto->fotoprincipal->imagem)
                            <img src="{{ asset($estoque->produto->fotoprincipal->imagem->fullpatch()) }}" style="height:60px; width:60px;">
                        @else
                            <img src="{{ asset('images/no-image.png') }}" style="height:60px; width:60px;">
                        @endif
                    </td>
                    <td>{{$estoque->codigo}}</td>
                    <td>{{$estoque->produto->nome}}</td>
                    <td class="text-center">{{$estoque->qtd ?? '1'}}</td>
                    <td class="text-end">R$ {{getMoney($estoque->valor_compra, 'R$') ?? 'Sem histórico'}}</td>
                    <td>{{ $estoque->data_compra ? date('d/m/Y', strtotime($estoque->data_compra)) : 'Sem histórico' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>