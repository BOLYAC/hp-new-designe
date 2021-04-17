<header class="main-nav">
    <nav>
        <div class="main-navbar">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="mainnav">
                <ul class="nav-menu custom-scrollbar">
                    <li class="back-btn">
                        <div class="mobile-back text-right"><span>Back</span><i class="fa fa-angle-right pl-2"
                                                                                aria-hidden="true"></i></div>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title <?php echo e(Route::currentRouteName()=='index' ? 'active' : ''); ?>"
                           href="<?php echo e(route('index')); ?>"><i data-feather="home"></i><span>Dashboard</span>
                        </a>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title <?php echo e(Route::currentRouteName()=='index' ? 'active' : ''); ?>"
                           href="<?php echo e(route('index')); ?>"><i data-feather="users"></i><span>Leads</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </div>
    </nav>
</header>
<?php /**PATH C:\wamp64\www\hp\resources\views/layouts/light/sidebar.blade.php ENDPATH**/ ?>