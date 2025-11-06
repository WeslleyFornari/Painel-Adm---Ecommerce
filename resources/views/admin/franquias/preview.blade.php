
<form action="">
                     <div class="row mt-1">
                            <div class="col-12 col-sm-2">

                                <label for="">Franquia * </label>
                                <input type="text" disabled name="nome_franquia" required class="form-control" value="{{$franquia->tipo_franqueado}} ">                              
                            </div>
                            <div class="col-12 col-sm-4">
                                <label for="">Razão Social *</label>
                                <input type="text" disabled name="nome_franquia" required class="form-control" value="{{$franquia->nome_franquia}} ">
                            </div>
                            <div class="col-12 col-sm-3">
                                <label for="">CNPJ *</label>
                                <input type="text" disabled name="cnpj" required class="form-control cnpjMask" value="{{$franquia->cnpj}}">
                            </div>
                            <div class="col-12 col-sm-3">
                                <label for="">Responsável *</label>
                                <input type="text"  disabled name="nome_responsavel" required class="form-control" value="{{$franquia->nome_responsavel}}">
                            </div>
                            
                            

                     </div>

                     <div class="row mt-3">
                            <div class="col-12 col-sm-3">
                                <label for="">CPF *</label>
                                <input type="text" disabled name="cpf" required class="form-control cpfMask"  value="{{$franquia->cpf}}">
                            </div>
                            <div class="col-12 col-sm-3">
                                <label for="">Telefone 1 *</label>
                                <input type="text" disabled name="celular" required class="form-control phoneMask"  value="{{$franquia->celular}}">
                            </div>
                            <div class="col-12 col-sm-3">
                                <label for="">Telefone 2 *</label>
                                <input type="text" disabled name="telefone" class="form-control phoneMask"  value="{{$franquia->telefone}}">
                            </div>
                            
                        <div class="col-12 col-sm-3">
                                <label for="">CEP *</label>
                                <div class="input-group ">
                                <input type="text" disabled class="form-control cepMask border-radius-bottom-end-0" name="cep" id="buscaCep" value="{{$franquia->cep}}" required>
                               
                                </div>  
                            </div>
       
                    </div>

                    <div class="row mt-3">
                             <div class="col-12 col-sm-4">
                                <label for="">Endereço *</label>
                                <input type="text" disabled name="endereco" required class="form-control" value="{{$franquia->endereco}}">
                            </div>

                            <div class="col-6 col-sm-2">
                                <label for="">Número *</label>
                                <input type="text" disabled name="numero" required class="form-control" value="{{$franquia->numero}}">
                            </div>
                            <div class="col-3">
                                <label for="">Complemento</label>
                                <input type="text" disabled name="complemento" class="form-control" value="{{$franquia->complemento}}">
                            </div>
                            <div class="col-3">
                                <label for="">Bairro * </label>
                                <input type="text" disabled name="bairro" required class="form-control" value="{{$franquia->bairro}}">
                            </div>
                            
                            
                    </div>

                    <div class="row mt-3 mb-3">
                             <div class="col-3">
                                <label for="">Cidade *</label>
                                <input type="text" disabled name="cidade" required class="form-control"  value="{{$franquia->cidade}}">
                            </div>
                            <div class="col-2">
                                <label for="">Estado * </label>
                                <input type="text" disabled name="estado" required class="form-control" value="{{$franquia->estado}}">
                            </div>
                            <div class="col-3">
                                <label for="">País * </label>
                                <input type="text" disabled name="pais" required c value="{{$franquia->pais}}">
                            </div>

                    </div>
                    <div class="row mt-3 px-3">
                        <label for="">Retirada no Balcão</label>
                        <input type="text" class="form-control" disabled value="{{$franquia->retirada_balcao}}">
                    </div>

  </form>