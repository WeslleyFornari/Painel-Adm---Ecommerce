<li class="nav-item">
  <a class="nav-link  {{ request()->routeIs('admin.home') ? 'active' : '' }}" href="{{route('admin.home')}}">
    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
      <i class="fas fa-chart-area"></i>
    </div>
    <span class="nav-link-text ms-1">Dashboard</span>
  </a>
</li>

<li class="nav-item">
  <a class="nav-link  {{ request()->routeIs('admin.relatorios.index') ? 'active' : '' }}" href="{{route('admin.relatorios.index')}}">
    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
      <i class="fas fa-table"></i>
    </div>
    <span class="nav-link-text ms-1">Relatórios</span>
  </a>
</li>
<li class="nav-item">
  <a class="nav-link  {{ request()->routeIs('admin.calendarios.index') ? 'active' : '' }}" href="{{route('admin.calendarios.index')}}">
    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
      <i class="fas fa-calendar-minus"></i>
    </div>
    <span class="nav-link-text ms-1">Calendário</span>
  </a>
</li>


<li class="nav-item">
  <a class="nav-link  {{ request()->routeIs('admin.franquias.*') ? 'active' : '' }}" href="{{route('admin.franquias.index')}}">
    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
      <i class="fas fa-building"></i>
    </div>
    <span class="nav-link-text ms-1">Franquias</span>
  </a>
</li>

<li class="nav-item">
  <a class="nav-link  {{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}" href="{{route('admin.usuarios.index')}}">
    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
      <i class="fas fa-user"></i>
    </div>
    <span class="nav-link-text ms-1">Usuários</span>
  </a>
</li>
<li class="nav-item">
  <a class="nav-link  {{ request()->routeIs('admin.clientes.*') ? 'active' : '' }}" href="{{route('admin.clientes.index')}}">
    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
      <i class="fas fa-user"></i>
    </div>
    <span class="nav-link-text ms-1">Clientes</span>
  </a>
</li>
<li class="nav-item">
  <a class="nav-link  {{ request()->routeIs('admin.pedidos.*') ? 'active' : '' }}" href="{{route('admin.pedidos.index')}}">
    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
      <!-- <i class="fas fa-bag-shopping"></i> -->
      <i class="fas fa-shopping-cart"></i>
    </div>
    <span class="nav-link-text ms-1">Pedidos</span>
  </a>
</li>

<li class="nav-item">
  <a class="nav-link  {{ request()->routeIs('admin.produtos.*') ? 'active' : '' }}" href="{{route('admin.produtos.index')}}">
    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
      <!-- <i class="fas fa-comments-dollar"></i> -->
      <i class="fas fa-shopping-bag"></i>
    </div>
    <span class="nav-link-text ms-1">Produtos</span>
  </a>
</li>
<li class="nav-item">
  <a class="nav-link  {{ request()->routeIs('admin.estoque.*') ? 'active' : '' }}" href="{{route('admin.estoque.index')}}">
    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
      <i class="fas fa-boxes"></i>
    </div>
    <span class="nav-link-text ms-1">Estoque</span>
  </a>
</li>
<li class="nav-item">
  <a class="nav-link  {{ request()->routeIs('admin.cupons.*') ? 'active' : '' }}" href="{{route('admin.cupons.index')}}">
    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
      <i class="fas fa-barcode"></i>
    </div>
    <span class="nav-link-text ms-1">Cupons</span>
  </a>
</li>

<!-- <li class="nav-item">
          <a class="nav-link  " href="{{route('admin.base_conhecimento.index')}}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="fas fa-info-circle"></i>
            </div>
            <span class="nav-link-text ms-1">Base Conhecimento</span>
          </a>
        </li> -->

<li class="nav-item">
  <a class="nav-link  {{ request()->routeIs('perguntas_frequentes.*') ? 'active' : '' }} " href="{{route('perguntas_frequentes.index')}}">
    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
      <i class="fas fa-comments"></i>
    </div>
    <span class="nav-link-text ms-1">Perguntas Frequentes</span>
  </a>
</li>

<li class="nav-item">
  <a class="nav-link  {{ request()->routeIs('admin.depoimentos.*') ? 'active' : '' }}" href="{{route('admin.depoimentos.index')}}">
    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
      <i class="fas fa-info-circle"></i>
    </div>
    <span class="nav-link-text ms-1">Depoimentos</span>
  </a>
</li>

<li class="nav-item">
  <a class="nav-link  {{ request()->routeIs('admin.duvidas.*') ? 'active' : '' }}" href="{{route('admin.duvidas.index')}}">
    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
      <i class="fas fa-question"></i>
    </div>
    <span class="nav-link-text ms-1">Dúvidas Frequentes</span>
  </a>
</li>

<li class="nav-item">
  <a class="nav-link  {{ request()->routeIs('admin.regiao_atendida.*') ? 'active' : '' }}" href="{{route('admin.regiao_atendida.index')}}">
    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
      <i class="fas fa-map-marker-alt"></i>
    </div>
    <span class="nav-link-text ms-1">Região Atendida</span>
  </a>
</li>
<li class="nav-item">
  <a class="nav-link  {{ request()->routeIs('admin.cep_bloqueados.*') ? 'active' : '' }}" href="{{route('admin.cep_bloqueados.index')}}">
    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
      <i class="fas fa-map-marker-alt"></i>
    </div>
    <span class="nav-link-text ms-1">CEP Bloqueados</span>
  </a>
</li>
<li class="nav-item">
  <a class="nav-link  {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}" href="{{route('admin.banners.index')}}">
    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
      <i class="fas fa-images"></i>
    </div>
    <span class="nav-link-text ms-1">Banners1</span>
  </a>
</li>
<li class="nav-item">
          <a class="nav-link  {{ request()->routeIs('admin.marcas.*') ? 'active' : '' }}" href="{{route('admin.marcas.index')}}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fas fa-copyright"></i>
            </div>
            <span class="nav-link-text ms-1">Marcas</span>
          </a>
        </li>
<li class="nav-item">
  <a class="nav-link  {{ request()->routeIs('admin.formulario_contato.*') ? 'active' : '' }}" href="{{route('admin.formulario_contato.index')}}">
    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
      <i class="fas fa-envelope"></i>
    </div>
    <span class="nav-link-text ms-1">Formulários1</span>
  </a>
</li>

<li class="nav-item">
  <a class="nav-link  {{ request()->routeIs('admin.configuracoes.*') ? 'active' : '' }}" href="{{route('admin.configuracoes.index')}}">
    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
      <svg width="12px" height="12px" viewBox="0 0 40 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
        <title>settings</title>
        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
          <g transform="translate(-2020.000000, -442.000000)" fill="#FFFFFF" fill-rule="nonzero">
            <g transform="translate(1716.000000, 291.000000)">
              <g transform="translate(304.000000, 151.000000)">
                <polygon class="color-background opacity-6" points="18.0883333 15.7316667 11.1783333 8.82166667 13.3333333 6.66666667 6.66666667 0 0 6.66666667 6.66666667 13.3333333 8.82166667 11.1783333 15.315 17.6716667"></polygon>
                <path class="color-background opacity-6" d="M31.5666667,23.2333333 C31.0516667,23.2933333 30.53,23.3333333 30,23.3333333 C29.4916667,23.3333333 28.9866667,23.3033333 28.48,23.245 L22.4116667,30.7433333 L29.9416667,38.2733333 C32.2433333,40.575 35.9733333,40.575 38.275,38.2733333 L38.275,38.2733333 C40.5766667,35.9716667 40.5766667,32.2416667 38.275,29.94 L31.5666667,23.2333333 Z"></path>
                <path class="color-background" d="M33.785,11.285 L28.715,6.215 L34.0616667,0.868333333 C32.82,0.315 31.4483333,0 30,0 C24.4766667,0 20,4.47666667 20,10 C20,10.99 20.1483333,11.9433333 20.4166667,12.8466667 L2.435,27.3966667 C0.95,28.7083333 0.0633333333,30.595 0.00333333333,32.5733333 C-0.0583333333,34.5533333 0.71,36.4916667 2.11,37.89 C3.47,39.2516667 5.27833333,40 7.20166667,40 C9.26666667,40 11.2366667,39.1133333 12.6033333,37.565 L27.1533333,19.5833333 C28.0566667,19.8516667 29.01,20 30,20 C35.5233333,20 40,15.5233333 40,10 C40,8.55166667 39.685,7.18 39.1316667,5.93666667 L33.785,11.285 Z"></path>
              </g>
            </g>
          </g>
        </g>
      </svg>
    </div>
    <span class="nav-link-text ms-1">Configurações</span>
  </a>
</li>