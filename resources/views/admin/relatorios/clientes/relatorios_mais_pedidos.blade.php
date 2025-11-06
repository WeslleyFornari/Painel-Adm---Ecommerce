              
<div class="table-responsive">
    <table class="table display nowrap" id="Clientes" style="width:100%">

        <thead class="bg-dark arial14-font text-light text-bold">
            <tr class="">
                <th scope="col">Nome</th>
                <th scope="col">CPF/CNPJ</th>
                <th scope="col">Quantidade de Pedidos</th>

            </tr>
        </thead>

        <tbody class="arial12-font-normal">

            @foreach($clientes as $k => $cli)
            @if($cli->cliente?->name != 'usuário deletado' )
            <tr>
                <td>{{$cli->cliente?->name}}</td>
                <td>{{ $cli->cliente?->dados->cpf ?? $ped->cliente?->dados->cnpj ?? '' }}</td>
                <td> {{$cli->qtd_pedidos}}</td>
            </tr>
            @endif
            @endforeach

        </tbody>
    </table>

</div>
<div class="col-12 justify-content-center mt-4">
    {{ $clientes->links() }}
</div>