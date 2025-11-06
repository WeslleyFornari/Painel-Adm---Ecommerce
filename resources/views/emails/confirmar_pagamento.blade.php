@extends('layouts.emails')

@section('content')
<div class="email-container">
    <div class="email-header">
        <h2>Confirmação de Pagamento</h2>
    </div>
    <div class="email-body">
        <h1>Olá, {{ $pedido->cliente->name ?? 'Cliente' }}</h1>
        <p>Obrigado por sua compra! Estamos felizes em informar que seu pagamento foi confirmado com sucesso.</p>
        <p>Segue abaixo o resumo do seu pedido:</p>
        <table border="1">
            <tr>
                <th>Produto</th>
                <th>Quantidade</th>
                <th>Preço Unitário</th>
                <th>Preço Total</th>
            </tr>
            @foreach ($pedido->itens as $item)
            <tr>
                <td>{{ $item->produto->nome }}</td>
                <td>{{ $item->qtd }}</td>
                <td>R$ {{ number_format($item->valor_unitario, 2, ',', '.') }}</td>
                <td>R$ {{ number_format($item->valor_total, 2, ',', '.') }}</td>
            </tr>
            @endforeach
        </table>
        <table class="order-summary">
            <tr>
                <th></th>
                <th></th>
                <th colspan="3">Subtotal</th>
                <th>R$ {{ number_format($pedido->valor_total_produtos, 2, ',', '.') }}</th>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th colspan="3">Frete</th>
                <th>R$ {{ number_format($pedido->valor_frete, 2, ',', '.') }}</th>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th colspan="3">Desconto</th>
                <th>R$ {{ number_format($pedido->valor_desconto, 2, ',', '.') }}</th>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th colspan="3">Total</th>
                <th>R$ {{ number_format($pedido->valor_total_produtos + $pedido->valor_frete - $valor->valor_desconto, 2, ',', '.') }}</th>
            </tr>
        </table>
        <a href="" class="button">Ver Pedido</a>
        <p>Se você tiver alguma dúvida, entre em contato com nossa equipe de suporte.</p>
    </div>
    <div class="email-footer">
        <p>&copy; 2024 Facilitrip. Todos os direitos reservados.</p>
    </div>
</div>
@endsection
