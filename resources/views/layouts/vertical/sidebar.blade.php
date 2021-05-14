<header class="main-nav">
    <nav>
        <div class="main-navbar">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="mainnav">
                <ul class="nav-menu custom-scrollbar">
                    <li class="back-btn">
                        <div class="mobile-back text-right"><span>{{ __('Back') }}</span><i
                                class="fa fa-angle-right pl-2"
                                aria-hidden="true"></i></div>
                    </li>

                    <li class="dropdown {{ Request::is('/') ? 'active' : ''}}">
                        <a class="nav-link menu-title"
                           href="{{route('home')}}"><i data-feather="home"></i><span>{{ __('Dashboard') }}</span>
                        </a>
                    </li>
                    @can('client-list')
                        <li class="dropdown {{ Request::is('clients') ? 'active' : ''}}">
                            <a class="nav-link menu-title"
                               href="{{route('clients.index')}}"><i
                                    data-feather="users"></i><span>{{ __('Leads') }}</span>
                            </a>
                        </li>
                    @endcan
                    @can('lead-list')
                        <li class="dropdown {{ Request::is('leads') ? 'active' : ''}}">
                            <a class="nav-link menu-title" href="{{route('leads.index')}}">
                                <i class="icon-layout-cta-right"></i>
                                <span>{{ __('Deals') }}</span>
                            </a>
                        </li>
                    @endcan
                    @can('invoice-list')
                        <li class="nav-link menu-title {{ Request::is('invoices') ? 'active' : ''}}">
                            <a href="{{ route('invoices.index') }}">
                                <i class="icon-layout-cta-right"></i>
                                <span> {{ __('Invoices') }}</span>
                            </a>
                        </li>
                    @endcan
                    @can('task-list')
                        <li class="nav-link menu-title {{ Request::is('tasks') ? 'active' : ''}}">
                            <a href="{{ route('tasks.index') }}">
                                <i class="fa fa-tasks"></i>
                                <span> {{ __('Tasks') }}</span>
                            </a>
                        </li>
                    @endcan
                    @can('event-list')
                        <li class="nav-link menu-title {{ Request::is('events') ? 'active' : ''}}">
                            <a href="{{ route('events.index') }}">
                                <i class="fa fa-calendar"></i>
                                <span> {{ __('Events') }}</span>
                            </a>
                        </li>
                    @endcan
                    @can('calender-show')
                        <li class="nav-link menu-title {{ Request::is('calender') ? 'active' : ''}}">
                            <a href="{{ route('calender.index') }}">
                                <i class="fa fa-calendar"></i>
                                <span> {{ __('Calender') }}</span>
                            </a>
                        </li>
                    @endcan
                    <li class="nav-link menu-title {{ Request::is('projects') ? 'active' : ''}}">
                        <a href="{{ route('projects.index') }}">
                            <i class="fa fa-product-hunt"></i>
                            <span> {{ __('Projects') }}</span>
                        </a>
                    </li>
                    @can('source-list')
                        <li class="nav-link menu-title {{ Request::is('sources') ? 'active' : ''}}">
                            <a href="{{ route('sources.index') }}">
                                <i class="icon-direction"></i>
                                <span> {{ __('Sources') }}</span>
                            </a>
                        </li>
                    @endcan
                    @can('agency-list')
                        <li class="nav-link menu-title {{ Request::is('agencies') ? 'active' : ''}}">
                            <a href="{{ route('agencies.index') }}">
                                <i class="icon-magnet"></i>
                                <span> {{ __('Agencies') }}</span>
                            </a>
                        </li>
                    @endcan
                    @can('stats-list')
                        <li class="nav-link menu-title {{ Request::is('stats') ? 'active' : ''}}">
                            <a href="{{ route('static.index') }}">
                                <i class="icon-bar-chart"></i>
                                <span> {{ __('Reporting') }}</span>
                            </a>
                        </li>
                    @endcan
                    @can('user-list')
                        <li class="nav-link menu-title {{ Request::is('users') ? 'active' : ''}}">
                            <a href="{{ route('users.index') }}">
                                <i class="fa fa-users"></i>
                                <span> {{ __('Users') }}</span>
                            </a>
                        </li>
                    @endcan
                    @can('team-list')
                        <li class="nav-link menu-title {{ Request::is('teams') ? 'active' : ''}}">
                            <a href="{{ route('teams.index') }}">
                                <i class="icon-link"></i>
                                <span> {{ __('Teams') }}</span>
                            </a>
                        </li>
                    @endcan
                    @can('role-list')
                        <li class="nav-link menu-title {{ Request::is('roles') ? 'active' : ''}}">
                            <a href="{{ route('roles.index') }}">
                                <i class="fa fa-chain"></i>
                                <span> {{ __('Roles') }}</span>
                            </a>
                        </li>
                    @endcan
                    @can('settings')
                        <li class="nav-link menu-title {{ Request::is('settings') ? 'active' : ''}}">
                            <a href="{{ route('settings.index') }}">
                                <i class="fa fa-gears"></i>
                                <span> {{ __('Settings') }}</span>
                            </a>
                        </li>
                    @endcan
                </ul>
            </div>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </div>
    </nav>
</header>
