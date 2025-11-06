<div class="sidenav-header">
  <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
  <a class="navbar-brand m-0" href="{{route('admin.home')}}">
    <img src="{{logoAdmin()}}" class="navbar-brand-img" alt="main_logo">
    <span class="ms-1 font-weight-bold"></span>
  </a>
</div>
<hr class="horizontal dark mt-0">

<div class="collapse navbar-collapse pb-5 w-auto " id="sidenav-collapse-main">
  <ul class="navbar-nav">
    @foreach (permissoes() as $permissao)
    <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('admin.' . $permissao->pagina->slug . '.index') ? 'active' : '' }}" 
       href="{{ route('admin.' . $permissao->pagina->slug . '.index') }}">
        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="{{ $permissao->pagina->icone }}"></i>
        </div>
        <span class="nav-link-text ms-1">{{ $permissao->pagina->titulo }}</span>
    </a>
</li>
    @endforeach
  </ul>

</div>