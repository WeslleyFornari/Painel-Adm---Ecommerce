<div class="table-responsive">
    <table class="table display nowrap" id="Clientes" style="width:100%">

        <thead class="bg-dark arial14-font text-light text-bold">
            <tr class="">
                @foreach($dados as $dado)
                @if ($dado == 'cpf/cnpj')
                <td>CPF/CNPJ</td>
                @elseif ($dado == 'name')
                <td>Nome</td>
                @elseif ($dado == 'data_nascimento')
                <td>Data Nascimento</td>
                @else
                <td>{{ucwords(strtolower($dado))}}</td>
                @endif
                @endforeach
            </tr>
        </thead>

        <tbody class="arial12-font-normal">
            @foreach($clientes as $k => $cli)

            <tr>
                @foreach($dados as $dado)
                @if ($dado == 'cpf/cnpj')
                <td>{{ $cli->dados?->cpf ?? $cli->dados?->cnpj ?? '' }}</td>
                @elseif ($dado == 'name' || $dado == 'email')
                <td>{{$cli->$dado}}</td>
                @elseif ($dado == 'enderecos')
                <td>
                    @foreach ($cli->enderecos as $endereco)
                    {{$endereco->enderecoCompleto()}}<br>
                    {{$endereco->enderecoCompleto2()}}<br>
                    @endforeach
                </td>
                @else
                <td>{{$cli->dados?->$dado}}</td>
                @endif
                @endforeach
            </tr>
            @endforeach
            @for($x = 0; $x < count($dados); $x++)
                @if($x==count($dados) - 2)
                <td>Total de Clientes</td>
                @elseif($x == count($dados) - 1)
                <td>{{ $clientes->count() }}</td>
                @else
                <td></td>
                @endif
                @endfor

        </tbody>
    </table>

</div>
<div class="col-12 justify-content-center mt-4">
    {{ $clientes->links() }}
</div>