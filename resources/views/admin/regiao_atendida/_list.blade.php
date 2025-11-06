<div class="card">
    <div class="card-body">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="bairro-tab" data-bs-toggle="tab" data-bs-target="#bairro" type="button" role="tab" aria-controls="bairro" aria-selected="false">Localização</button>
            </li>
            <!-- <li class="nav-item" role="presentation">
                <button class="nav-link" id="cidade-tab" data-bs-toggle="tab" data-bs-target="#cidade" type="button" role="tab" aria-controls="cidade" aria-selected="true">Cidade</button>
            </li> -->
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="bairro" role="tabpanel" aria-labelledby="bairro-tab"> @include('admin.regiao_atendida._list_bairro')</div>
            <!-- <div class="tab-pane fade" id="cidade" role="tabpanel" aria-labelledby="cidade-tab"></div> -->
        </div>
    </div>
</div>