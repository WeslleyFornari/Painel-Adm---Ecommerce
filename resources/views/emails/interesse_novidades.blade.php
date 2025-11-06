@extends('layouts.emails')

@section('content')
<div class="email-container">
        <h1>Um usuário possui interesse de receber as novidades da facilitrip!</h1>

        <h2>Email</h2>
        <p>{{$email}}</p>

        <p class="footer">
            Obrigado por entrar em contato! Se você tiver mais dúvidas, não hesite em nos procurar.
        </p>
    </div>
@endsection
