<div class="table-responsive">
    <table class="table display nowrap" id="Financeiro" style="width:100%">

        <thead class="bg-dark arial14-font text-light text-bold">
            <tr class="">
                <th scope="col">Produto</th>
                <th scope="col">Nº de Pedidos</th>
                <th scope="col">Qtd. Itens Faturados</th>
                <th scope="col">Preço Unit. Médio</th>
                <th scope="col">Receita Total Bruta</th>
                <th scope="col">Receita Total Líquida</th>
                <th scope="col">Participação (%)</th>
                <th scope="col">Classe</th>
            </tr>
        </thead>

        <tbody class="arial12-font-normal">
            @foreach ($produtosClassificados as $produto)
            <tr>
                <td>{{ $produto->produto->nome }}</td>
                <td>{{ $produto->num_pedidos }}</td>
                <td>{{ $produto->qtd_faturada }}</td>
                <td>{{ number_format($produto->preco_unitario_medio, 2, ',', '.') }}</td>
                <td>{{ number_format($produto->receita_total_bruta, 2, ',', '.') }}</td>
                <td>{{ number_format($produto->receita_total_liquida, 2, ',', '.') }}</td>
                <td>{{ number_format($produto->participacao, 2, ',', '.') }}%</td>
                <td>{{ $produto->classificacao }}</td>
            </tr>
            @endforeach
    </table>

</div>