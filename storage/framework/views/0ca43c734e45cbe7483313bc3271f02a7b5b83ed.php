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
                        <a class="nav-link menu-title <?php echo e(Request::is('/') ? 'active' : ''); ?>"
                           href="<?php echo e(route('home')); ?>"><i data-feather="home"></i><span>Dashboard</span>
                        </a>
                    </li>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('client-list')): ?>
                        <li class="dropdown <?php echo e(Request::is('clients') ? 'active' : ''); ?>">
                            <a class="nav-link menu-title"
                               href="<?php echo e(route('clients.index')); ?>"><i data-feather="users"></i><span>Leads</span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('lead-list')): ?>
                        <li class="dropdown <?php echo e(Request::is('leads') ? 'active' : ''); ?>">
                            <a class="nav-link menu-title" href="<?php echo e(route('leads.index')); ?>">
                                <i class="icon-layout-cta-right"></i>
                                <span><?php echo e(__('Deals')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('invoice-list')): ?>
                        <li class="nav-link menu-title <?php echo e(Request::is('invoices') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('invoices.index')); ?>">
                                <i class="icon-layout-cta-right"></i>
                                <span> <?php echo e(__('Invoices')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('task-list')): ?>
                        <li class="nav-link menu-title <?php echo e(Request::is('tasks') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('tasks.index')); ?>">
                                <i class="fa fa-tasks"></i>
                                <span> <?php echo e(__('Tasks')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('event-list')): ?>
                        <li class="nav-link menu-title <?php echo e(Request::is('events') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('events.index')); ?>">
                                <i class="fa fa-calendar"></i>
                                <span> <?php echo e(__('Events')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('calender-show')): ?>
                        <li class="nav-link menu-title <?php echo e(Request::is('calender') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('calender.index')); ?>">
                                <i class="fa fa-calendar"></i>
                                <span> <?php echo e(__('Calender')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-link menu-title <?php echo e(Request::is('projects') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('projects.index')); ?>">
                            <i class="fa fa-product-hunt"></i>
                            <span> <?php echo e(__('Projects')); ?></span>
                        </a>
                    </li>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('source-list')): ?>
                        <li class="nav-link menu-title <?php echo e(Request::is('sources') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('sources.index')); ?>">
                                <i class="ti-direction"></i>
                                <span> <?php echo e(__('Sources')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('agency-list')): ?>
                        <li class="nav-link menu-title <?php echo e(Request::is('agencies') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('agencies.index')); ?>">
                                <i class="ti-magnet"></i>
                                <span> <?php echo e(__('Agencies')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('stats-list')): ?>
                        <li class="nav-link menu-title <?php echo e(Request::is('stats') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('static.index')); ?>">
                                <i class="ti-bar-chart"></i>
                                <span> <?php echo e(__('Reporting')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user-list')): ?>
                        <li class="nav-link menu-title <?php echo e(Request::is('users') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('users.index')); ?>">
                                <i class="fa fa-users"></i>
                                <span> <?php echo e(__('Users')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('team-list')): ?>
                        <li class="nav-link menu-title <?php echo e(Request::is('teams') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('teams.index')); ?>">
                                <i class="ti-link"></i>
                                <span> <?php echo e(__('Teams')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('role-list')): ?>
                        <li class="nav-link menu-title <?php echo e(Request::is('roles') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('roles.index')); ?>">
                                <i class="fa fa-chain"></i>
                                <span> <?php echo e(__('Roles')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('settings')): ?>
                        <li class="nav-link menu-title <?php echo e(Request::is('settings') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('settings.index')); ?>">
                                <i class="fa fa-gears"></i>
                                <span> <?php echo e(__('Settings')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </div>
    </nav>
</header>
<?php /**PATH C:\wamp64\www\hp\resources\views/layouts/vertical/sidebar.blade.php ENDPATH**/ ?>