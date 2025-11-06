<!-- Formulario Não encontrado -->
<form action="" class="form form-nao-encontrado">
@csrf
<div class="loading">
								<div class="sk-cube-grid">
								  <div class="sk-cube sk-cube1"></div>
								  <div class="sk-cube sk-cube2"></div>
								  <div class="sk-cube sk-cube3"></div>
								  <div class="sk-cube sk-cube4"></div>
								  <div class="sk-cube sk-cube5"></div>
								  <div class="sk-cube sk-cube6"></div>
								  <div class="sk-cube sk-cube7"></div>
								  <div class="sk-cube sk-cube8"></div>
								  <div class="sk-cube sk-cube9"></div>
								</div>
							</div>
<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-sm-3 col-12 mb-3 mb-sm-0">
                <div class="input-custom">
                    <input type="text" name="modelo" required id="placeholder-text" placeholder="Modelo desejado*">
                </div>
            </div>
            <div class="col-sm-3 col-12 mb-3 mb-sm-0">
                <div class="input-custom">
                    <input type="text" name="nome" required id="placeholder-text" placeholder="Nome*">
                </div>
            </div>
            <div class="col-sm-3 col-12 mb-3 mb-sm-0">
                <div class="input-custom">
                    <input type="email" name="email" id="placeholder-text" placeholder="E-mail">
                </div>
            </div>
            <div class="col-sm-3 col-12 mb-3 mb-sm-0">
                <div class="input-custom">
                    <input type="text" name="telefone" required id="placeholder-text" placeholder="Telefone/WhatsApp*" class="phoneMask">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="concordo- mt-sm-4 mt-0">

    <div class="row mt-sm-0 mt-4 align-items-end">
     
        <div class="col-sm-10 col-11">
        <input type="checkbox" name="" id="">Eu concordo
            <span style="font-size: 0.8em;">De acordo com a Lei Geral de Proteção de Dados, concordo em fornecer os dados
                acima para que a Avantgarde entre em contato comigo para apresentar produtos e
                serviços. Seu nome, e-mail e telefone serão usados com a finalidade de ofertar
                uma
                oportunidade, de acordo com a nossa
                <a href="{{route('paginas',['slug'=>'politica-de-privacidade'])}}" target="_blank">Política de Privacidade.</a>
            <span>
        </div>

        <div class="col-sm-2 col-12">
          
                    <button type="submit" class="btn btn-brand btn-lg">Enviar</button>
        
        </div>
    </div>
</div>
</form>

      