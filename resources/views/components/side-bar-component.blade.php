<aside class="main-sidebar sidebar-light-teal elevation-4">
  <!-- Brand Logo -->
  <a href="#" class="brand-link navbar-teal">
    <img src="{{ asset('dist/img/ic_logo_and_title_foreground.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-1" style="opacity: .8">
    <div class="brand-text font-weight-light">
      <strong>Plucky</strong>
    </div>
  </a>

  <div class="sidebar">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="{{ asset('dist/img/avatar5.png') }}" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block text-wrap">{{ \Illuminate\Support\Facades\Auth::user()->username }}</a>
      </div>
    </div>

    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
          <a href="{{ route('home') }}" class="nav-link {{ request()->is('home') ? 'active' : '' }}">
            <i class="nav-icon fas fa-heartbeat"></i>
            <p>
              Home
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('user.index') }}" class="nav-link {{ request()->is(['user', 'user/*']) ? 'active' : '' }}">
            <i class="nav-icon fas fa-users"></i>
            <p>
              User
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('lot.index') }}" class="nav-link {{ request()->is(['lot', 'lot/*']) ? 'active' : '' }}">
            <i class="nav-icon fas fa-trophy"></i>
            <p>
              LOT
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('level.index') }}" class="nav-link {{ request()->is(['level', 'level/*']) ? 'active' : '' }}">
            <i class="nav-icon fas fa-stream"></i>
            <p>
              Level
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('setting.index') }}" class="nav-link {{ request()->is(['setting', 'setting/*']) ? 'active' : '' }}">
            <i class="nav-icon fa fa-cogs"></i>
            <p>
              Setting
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="nav-link">
            <i class="nav-icon fas fa-power-off"></i>
            <p>
              Logout
            </p>
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
          </form>
        </li>
      </ul>
    </nav>
  </div>
</aside>