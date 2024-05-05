<div id="sidebar-menu">
  <ul class="metismenu list-unstyled" id="side-menu">
    <li class="menu-title text-center fw-bolder"> [ {{ auth()->user()->nickName }} ] </li>
    <li class="menu-title">Principal</li>
    <li>
      <a href="{{ route('dashboard') }}" class="waves-effect">
        <i class="mdi mdi-home"></i>
        <span>Inicio</span>
      </a>
    </li>
    @if (auth()->user()->getAllPermissions()->count())
      <li class="menu-title text-uppercase">Administrar</li>
    @endif
    @can('view banks')
      <li>
        <a href="{{ route('banks') }}" class="waves-effect">
          <i class="fas fa-money-check"></i>
          <span>Bancos</span>
        </a>
      </li>
    @endcan

    @can('view client')
      <li>
        <a href="{{ route('customers') }}" class="waves-effect">
          <i class="fas fa-users"></i>
          <span>Clientes</span>
        </a>
      </li>
    @endcan

    @can('view contacts')
      <li>
        <a href="{{ route('contacts') }}" class="waves-effect">
          <i class="far fa-address-book"></i>
          <span>Contactos</span>
        </a>
      </li>
    @endcan

    @can('view enterprises')
      <li>
        <a href="{{ route('enterprises') }}" class="waves-effect">
          <i class="far fa-building"></i>
          <span>Empresa</span>
        </a>
      </li>
    @endcan

    @can('view pucharses')
      <li>
        <a href="{{ route('shoppingManagement') }}" class="waves-effect">
          <i class="fas fa-cash-register"></i>
          <span>Gestión de compras</span>
        </a>
      </li>
    @endcan

    @can('view sales')
      <li>
        <a href="{{ route('salesManagement') }}" class="waves-effect">
          <i class="fab fa-shopify"></i>
          <span>Gestión de ventas</span>
        </a>
      </li>
    @endcan

    @can('view licenses')
      <li>
        <a href="{{ route('licenses') }}" class="waves-effect">
          <i class="fas fa-compact-disc"></i>
          <span>Seguimiento licencias</span>
        </a>
      </li>
    @endcan

    @can('view suppliers')
      <li>
        <a href="{{ route('suppliers') }}" class="waves-effect">
          <i class="fas fa-people-carry"></i>
          <span>Proveedores</span>
        </a>
      </li>
    @endcan

    @can('view roles')
      <li>
        <a href="javascript: void(0);" class="has-arrow waves-effect" aria-expanded="true">
          <i class="fas fa-users-cog"></i>
          <span>Config. permisos</span>
        </a>
        <ul class="sub-menu" aria-expanded="true">
          <li><a href="{{ route('roles') }}">Roles</a></li>
          @can('update roles')
            <li><a href="{{ route('assing-permission')}}">Asignar permisos</a></li>
          @endcan
        </ul>
      </li>
    @endcan

    @can('view invoice tracking')
      <li>
        <a href="{{ route('trackingOfReceipts') }}" class="waves-effect">
          <i class="fas fa-search-location"></i>
          <span>Seguimiento Fact.</span>
        </a>
      </li>
    @endcan

    @can('view series')
      <li>
        <a href="{{ route('series') }}" class="waves-effect">
          <i class="fas fa-barcode"></i>
          <span>Series</span>
        </a>
      </li>
    @endcan

    @can('view users')
      <li>
        <a href="{{ route('users') }}" class="waves-effect">
          <i class="fas fa-users"></i>
          <span>Usuarios</span>
        </a>
      </li>
    @endcan
    @can('view users')
      <li>
        <a href="{{ route('mycompany') }}" class="waves-effect">
          <i class="fas fa-users"></i>
          <span>MyCompany</span>
        </a>
      </li>
    @endcan

  </ul>
</div>
