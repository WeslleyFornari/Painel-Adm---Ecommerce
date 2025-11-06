
<form action="#">
 
                <div class="row mt-4">
                                                                                                           
                            <div class="col-12 col-sm-3">
                                <label for="">CPF *</label>
                                <input type="text" name="cpf" disabled class="form-control cpfMask" id="cpf" value="{{$usuario->dados->cpf ?? ''}}">
                            </div>
                            
                            <div class="col-12 col-sm-3">
                                <label for="">CNPJ *</label>
                                <input type="text" name="cnpj" disabled class="form-control cnpjMask" value="{{$usuario->dados->cnpj ?? ''}}">
                            </div>       
                            <div class="col-12 col-sm-3">
                                <label for="">Telefone 1 *</label>
                                <input type="text" name="celular" disabled required class="form-control phoneMask" value="{{$usuario->dados->celular ?? ''}}">
                            </div>
                            <div class="col-12 col-sm-3">
                                <label for="">Telefone 2 *</label>
                                <input type="text" name="telefone" disabled class="form-control phoneMask" value="{{$usuario->dados->telefone ?? ''}}">
                            </div>
                </div>

                    <div class="row mt-4">

                             <div class="col-12 col-sm-3">
                                <label for="">CEP *</label>
                                <div class="input-group ">
                                <input type="text" disabled class="form-control cepMask border-radius-bottom-end-0" name="cep" id="buscaCep" value="{{$usuario->dados->cep ?? ''}}" required>
                                
                                </div>  
                            </div>
                             <div class="col-12 col-sm-6">
                                <label for="">Endereço *</label>
                                <input type="text" name="endereco" disabled required class="form-control" value="{{$usuario->dados->endereco ?? ''}}">
                            </div>

                            <div class="col-6 col-sm-2">
                                <label for="">Número *</label>
                                <input type="text" name="numero" disabled required class="form-control" value="{{$usuario->dados->numero ?? ''}}">
                            </div>     
                            
                    </div>

                    <div class="row mt-3">

                            <div class="col-4">
                                <label for="">Complemento</label>
                                <input type="text" name="complemento" disabled class="form-control" value="{{$usuario->dados->complemento ?? ''}}">
                            </div>
                            <div class="col-3">
                                <label for="">Bairro * </label>
                                <input type="text" name="bairro" disabled required class="form-control" value="{{$usuario->dados->bairro ?? ''}}">
                            </div>
                             <div class="col-3">
                                <label for="">Cidade *</label>
                                <input type="text" name="cidade" disabled required class="form-control" value="{{$usuario->dados->cidade ?? ''}}">
                            </div>
                            

                    </div>

                    <div class="row mt-3">

                    <div class="col-2 mb-3">
                                <label for="">Estado * </label>
                                <input type="text" name="estado" disabled required class="form-control" value="{{$usuario->dados->estado ?? ''}}">
                            </div>
                            <div class="col-3">
                                <label for="">País * </label>
                                <input type="text" name="pais" disabled required class="form-control" value="{{$usuario->dados->pais ?? ''}}">
                            </div>
                    </div>

                  
</form>
