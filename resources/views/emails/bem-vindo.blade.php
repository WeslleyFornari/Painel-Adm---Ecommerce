@extends('layouts.emails')

@section('content')
<div class="email-container">
        <div class="email-header">
            <h4>Bem-vindo(a) ao Facilitrip</h4>
        </div>
        <div class="email-content">
            <p>Olá, {{ $user->name }}!</p>
            <p>Seja bem-vindo(a) à nossa plataforma! Estamos muito felizes em tê-lo(a) conosco.</p>
            <p>A partir de agora, você tem acesso a todos os recursos disponíveis no <a href="{{route('site.index')}}">Facilitrip</a>.</p>
            <p>Se precisar de ajuda, fique à vontade para entrar em contato com a nossa equipe de suporte. Estamos aqui para ajudar você!</p>
            <p>Explore e aproveite ao máximo a sua experiência conosco!</p>
            <p>Atenciosamente,</p>
            <p>Equipe Facilitrip</p>
        </div>
    </div>
@endsection