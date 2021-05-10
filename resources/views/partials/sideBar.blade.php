<div class="main-menu-content">
  <ul class="main-navigation">
    <li class="nav-item single-item {{ Request::is('/') ? 'has-class' : ''}}">
      <a href="{{ route('home') }}">
        <i class="ti-home"></i>
        <span> {{ __('Dashboard') }}</span>
      </a>
    </li>
    @can('client-list')
    <li class="nav-item single-item {{ Request::is('clients') ? 'has-class' : ''}}">
      <a href="{{ route('clients.index') }}">
        <i class="ti-user"></i>
        <span> {{ __('Leads') }}</span>
      </a>
    </li>
    @endcan
    @can('lead-list')
    <li class="nav-item single-item {{ Request::is('leads') ? 'has-class' : ''}}">
      <a href="{{ route('leads.index') }}">
        <i class="ti-layout-cta-right"></i>
        <span> {{ __('Deals') }}</span>
      </a>
    </li>
    @endcan
    @can('invoice-list')
    <li class="nav-item single-item {{ Request::is('invoices') ? 'has-class' : ''}}">
      <a href="{{ route('invoices.index') }}">
        <i class="ti-layout-cta-right"></i>
        <span> {{ __('Invoices') }}</span>
      </a>
    </li>
    @endcan

    @can('task-list')
    <li class="nav-item single-item {{ Request::is('tasks') ? 'has-class' : ''}}">
      <a href="{{ route('tasks.index') }}">
        <i class="fa fa-tasks"></i>
        <span> {{ __('Tasks') }}</span>
      </a>
    </li>
    @endcan
    @can('event-list')
    <li class="nav-item single-item {{ Request::is('events') ? 'has-class' : ''}}">
      <a href="{{ route('events.index') }}">
        <i class="fa fa-calendar"></i>
        <span> {{ __('Events') }}</span>
      </a>
    </li>
    @endcan
    @can('calender-show')
    <li class="nav-item single-item {{ Request::is('calender') ? 'has-class' : ''}}">
      <a href="{{ route('calender.index') }}">
        <i class="fa fa-calendar"></i>
        <span> {{ __('Calender') }}</span>
      </a>
    </li>
    @endcan
    <li class="nav-item single-item {{ Request::is('projects') ? 'has-class' : ''}}">
      <a href="{{ route('projects.index') }}">
        <i class="fa fa-product-hunt"></i>
        <span> {{ __('Projects') }}</span>
      </a>
    </li>
    @can('source-list')
    <li class="nav-item single-item {{ Request::is('sources') ? 'has-class' : ''}}">
      <a href="{{ route('sources.index') }}">
        <i class="ti-direction"></i>
        <span> {{ __('Sources') }}</span>
      </a>
    </li>
    @endcan
    @can('agency-list')
    <li class="nav-item single-item {{ Request::is('agencies') ? 'has-class' : ''}}">
      <a href="{{ route('agencies.index') }}">
        <i class="ti-magnet"></i>
        <span> {{ __('Agencies') }}</span>
      </a>
    </li>
    @endcan
    @can('stats-list')
    <li class="nav-item single-item {{ Request::is('stats') ? 'has-class' : ''}}">
      <a href="{{ route('static.index') }}">
        <i class="ti-bar-chart"></i>
        <span> {{ __('Reporting') }}</span>
      </a>
    </li>
    @endcan
    @can('user-list')
    <li class="nav-item single-item {{ Request::is('users') ? 'has-class' : ''}}">
      <a href="{{ route('users.index') }}">
        <i class="fa fa-users"></i>
        <span> {{ __('Users') }}</span>
      </a>
    </li>
    @endcan
    @can('team-list')
    <li class="nav-item single-item {{ Request::is('teams') ? 'has-class' : ''}}">
      <a href="{{ route('teams.index') }}">
        <i class="ti-link"></i>
        <span> {{ __('Teams') }}</span>
      </a>
    </li>
    @endcan
    @can('role-list')
    <li class="nav-item single-item {{ Request::is('roles') ? 'has-class' : ''}}">
      <a href="{{ route('roles.index') }}">
        <i class="fa fa-chain"></i>
        <span> {{ __('Roles') }}</span>
      </a>
    </li>
    @endcan
    @can('settings')
    <li class="nav-item single-item {{ Request::is('settings') ? 'has-class' : ''}}">
      <a href="{{ route('settings.index') }}">
        <i class="fa fa-gears"></i>
        <span> {{ __('Settings') }}</span>
      </a>
    </li>
    @endcan
  </ul>
</div>
