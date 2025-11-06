<div class="table-responsive">
    <table class="table display nowrap" id="Clientes" style="width:100%">

        <thead class="bg-dark arial14-font text-light text-bold">
            <tr class="">
                <th scope="col">Nome</th>
                <th scope="col">CPF/CNPJ</th>
                <th scope="col">Valor em Pedidos</th>

            </tr>
        </thead>

        <tbody class="arial12-font-normal">
            @foreach($clientes as $k => $cli)
            <tr>
                <td>{{$cli->cliente?->name}}</td>
                <td> {{ $cli->cliente?->dados->cpf ?? $ped->cliente?->dados->cnpj ?? '' }}</td>
                <td> R$ {{getMoney($cli->total_gasto)}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
<div class="col-12 justify-content-center mt-4">
    {{ $clientes->links() }}
</div>




