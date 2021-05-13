<div class="page-main-header">
    <div class="main-header-right">
        <div class="main-header-left">
            <div class="logo-wrapper"><a href="{{route('home')}}"><img src="{{asset('assets/images/logo/hp.png')}}"
                                                                       alt=""></a>
            </div>
        </div>
        <div class="mobile-sidebar">
            <div class="media-body text-right switch-sm">
                <label class="switch">
                    <input id="sidebar-toggle" type="checkbox" data-toggle=".container" checked="checked"><span
                        class="switch-state"></span>
                </label>
            </div>
        </div>
        <div class="nav-right col pull-right right-menu">
            <ul class="nav-menus">
                <li class="onhover-dropdown px-0"><span class="media user-header"><img
                            class="mr-2 rounded-circle img-35"
                            style="width: 35px;height:35px;"
                            src="{{  asset('storage/' . auth()->user()->image_path) }}"
                            alt=""><span class="media-body"><span class="f-12 f-w-600">{{ auth()->user()->name }}</span><span
                                class="d-block">{{ auth()->user()->roles->first()->name }}</span></span></span>
                    <ul class="profile-dropdown onhover-show-div">
                        <li><i data-feather="log-in"></i>{{ __('Logout') }}</li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="d-lg-none mobile-toggle pull-right"><i data-feather="more-horizontal"></i></div>
    </div>
</div>
