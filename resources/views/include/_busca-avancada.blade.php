<section id="pesquisa-avancada">
    <form action="{{ route('site.produtos.PesquisarProdutos') }}" id="formPesquisar" method="POST" autocomplete="off">
    @csrf
        <div class="container">
            <div class="row hide-fixed">
                <div class="col">
                <h4>Alugue tudo que seu bebê precisa 👶🏼</h4>
                </div>
            </div>
            <div class="row hide-fixed">
                <div class="col">
                   <label for="">Faça sua busca</label>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3 col-12 position-relative">
                <div class="custom-floating">
                    <input type="text" autocomplete="off"  id="searchInput2"  name="local" placeholder="" required>
                    <label for="searchInput2">Selecione o local de retirada *</label>
                    </div>
                   
                    <!-- <input type="text" id="searchInput2" class="form-input form-control" name="local" required> -->
                    <input type="hidden" name="local_id">
                    <div class="autocomplete containerAutoCompleteLocal">
                        <div id="autocompleteLocal"></div>
                    </div>
                </div>
                <div class="col-sm-3 col-12 position-relative">
                    <div class="custom-floating">
                        <input type="text" id="searchInput3" class="form-input form-control" placeholder="" required>
                        <label for="searchInput3">Produto*</label>
                    </div>
                    <input type="hidden" name="produto_id">
                    <div class="autocomplete containerAutoCompleteProduto" >
                        <div id="autocompleteProduto"></div>
                    </div>
                </div>
                <div class="col-sm-2 col-6">
                <div class="custom-floating">
                  
                    <input class="form-input flatPicker form-control input active" placeholder="00/00/0000" tabindex="0" type="text" readonly="readonly" name="data_inicio">
                    <label for="">Data Entrega</label>
                </div>
                </div>
                <div class="col-sm-2 col-6">
                <div class="custom-floating">
                    <input class="form-input flatPicker form-control input active" placeholder="00/00/00" tabindex="0" type="text" readonly="readonly" name="data_termino">
                    <label for="">Data Devolução</label>
                </div>
                </div>
                <div class="col-sm-2 col-12">
                    <button type="submit" class="btnBuscar">Buscar <img src="{{asset('assets/img/icons/search-lg.svg')}}" alt=""></button>
                </div>
            </div>
        </div>
    </form>
</section>