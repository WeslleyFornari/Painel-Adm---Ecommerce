@extends('layouts.emails')

@section('content')
<div class="email-container">
        <h1>Resposta à sua pergunta reverente ao produto {{$pergunta->produto->nome}}!</h1>

        <div class="question-answer">
            <h2>Sua Pergunta</h2>
            <p><em>"{{$pergunta->pergunta}}"</em></p>

            <h2>Nossa Resposta</h2>
            <p>{{$pergunta->resposta}}</p>
        </div>

        <p class="footer">
            Obrigado por entrar em contato! Se você tiver mais dúvidas, não hesite em nos procurar.
        </p>
    </div>
@endsection
