

                <div class="table-responsive">
                    <table class="table display nowrap" id="Clientes" style="width:100%">

                        <thead class="bg-dark arial14-font text-light text-bold">
                            <tr class="">
                                <th scope="col">Pedido</th>
                                <th scope="col">Cliente</th>
                                <th scope="col">Data do Pedido</th>
                                <th scope="col">Descontos</th>
                                <th scope="col">Frete</th>
                                <th scope="col">Total</th>

                            </tr>
                        </thead>

                        <tbody class="arial12-font-normal">
                            @foreach($pedidos as $k => $ped)
                            <tr>
                                <td> <a href="{{route('admin.pedidos.preview', $ped->id)}}" target="_blank">{{ $ped->numero }}</a></td>
                                <td>{{$ped->cliente?->name}}</td>
                                <td> {{$ped->created_at->format('d/m/Y H:i') ?? ''}}</td>
                                <td> {{$ped->valor_desconto ?? ''}}</td>
                                <td> {{$ped->valor_frete ?? ''}}</td>
                                <td> R$ {{getMoney($ped->valor_total) ?? ''}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
                <div class="col-12 mx-auto justify-content-center mt-4">
                     {{ $pedidos->links() }}
                </div>
      