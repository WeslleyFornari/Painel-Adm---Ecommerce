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
            <span class="nav-link-text ms-1">Relátorios</span>
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
          <a class="nav-link  {{ request()->routeIs('admin.estoque.*') ? 'active' : '' }}" href="{{route('admin.estoque.index')}}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="fas fa-boxes"></i>
            </div>
            <span class="nav-link-text ms-1">Estoque</span>
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
          <a class="nav-link  {{ request()->routeIs('admin.cep_bloqueado.*') ? 'active' : '' }}" href="{{route('admin.cep_bloqueados.index')}}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="fas fa-map-marker-alt"></i>
            </div>
            <span class="nav-link-text ms-1">CEP Bloqueados</span>
          </a>
        </li>
        
      
      
       