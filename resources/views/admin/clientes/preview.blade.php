
    <ul class="nav nav-tabs" id="myTab" role="tablist">

    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Home</button>
    </li>
    <li class="nav-item" role="presentation">
    @if(isset($usuario))
        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Dados Complementares</button>
    @else 
        <button class="nav-link disabled" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Dados Complementares</button>
    @endif
    </li>

    </ul>

    <div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab"> @include('admin.clientes._inicio-preview')</div>
    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">@include('admin.clientes._dados_complementares-preview')</div>