@extends('layouts.site')

@section('title', __('Not Found'))


@section('busca-avancada')
@include('include._busca-avancada')
@endsection

@section('content')
<div class="row">
    <div class="col text-center">
<img src="{{asset('assets/img_site/404.svg')}}" class="img-fluid" alt="">
</div>
</div>
@endsection

