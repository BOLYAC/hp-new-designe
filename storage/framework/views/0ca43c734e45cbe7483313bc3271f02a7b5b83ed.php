<header class="main-nav">
    <nav>
        <div class="main-navbar">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="mainnav">
                <ul class="nav-menu custom-scrollbar">
                    <li class="back-btn">
                        <div class="mobile-back text-right"><span><?php echo e(__('Back')); ?></span><i
                                class="fa fa-angle-right pl-2"
                                aria-hidden="true"></i></div>
                    </li>

                    <li class="dropdown">
                        <a class="nav-link menu-title <?php echo e(Route::currentRouteName() === 'home' ? 'active' : ''); ?>"
                           href="<?php echo e(route('home')); ?>"><i data-feather="home"></i><span><?php echo e(__('Dashboard')); ?></span>
                        </a>
                    </li>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('client-list')): ?>
                        <li class="dropdown">
                            <a class="nav-link menu-title <?php echo e(Route::currentRouteName() === 'clients.index' ? 'active' : ''); ?>"
                               href="<?php echo e(route('clients.index')); ?>"><i
                                    data-feather="users"></i><span><?php echo e(__('Leads')); ?>

                                </span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('lead-list')): ?>
                        <li class="dropdown">
                            <a class="nav-link menu-title  <?php echo e(Route::currentRouteName() === 'leads.index' ? 'active' : ''); ?>"
                               href="<?php echo e(route('leads.index')); ?>">
                                <i class="icon-layout-cta-right"></i>
                                <span><?php echo e(__('Deals')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('invoice-list')): ?>
                        <li class="dropdown">
                            <a href="<?php echo e(route('invoices.index')); ?>"
                               class="nav-link menu-title <?php echo e(Route::currentRouteName() === 'invoices.index' ? 'active' : ''); ?>">
                                <i class="icon-layout-cta-right"></i>
                                <span> <?php echo e(__('Invoices')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('task-list')): ?>
                        <li class="dropdown">
                            <a href="<?php echo e(route('tasks.index')); ?>"
                               class="nav-link menu-title <?php echo e(Route::currentRouteName() === 'tasks.index' ? 'active' : ''); ?>">
                                <i class="fa fa-tasks"></i>
                                <span> <?php echo e(__('Tasks')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('event-list')): ?>
                        <li class="dropdown">
                            <a href="<?php echo e(route('events.index')); ?>"
                               class="nav-link menu-title <?php echo e(Route::currentRouteName() === 'events.index' ? 'active' : ''); ?>">
                                <i class="fa fa-calendar"></i>
                                <span> <?php echo e(__('Events')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('calender-show')): ?>
                        <li class="dropdown">
                            <a href="<?php echo e(route('calender.index')); ?>"
                               class="nav-link menu-title <?php echo e(Route::currentRouteName() === 'calender.index' ? 'active' : ''); ?>">
                                <i class="fa fa-calendar"></i>
                                <span> <?php echo e(__('Calender')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <li class="dropdown">
                        <a href="<?php echo e(route('projects.index')); ?>"
                           class="nav-link menu-title <?php echo e(Route::currentRouteName() === 'projects.index' ? 'active' : ''); ?>">
                            <i class="fa fa-product-hunt"></i>
                            <span> <?php echo e(__('Projects')); ?></span>
                        </a>
                    </li>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('source-list')): ?>
                        <li class="dropdown">
                            <a href="<?php echo e(route('sources.index')); ?>"
                               class="nav-link menu-title <?php echo e(Route::currentRouteName() === 'sources.index' ? 'active' : ''); ?>">
                                <i class="icon-direction"></i>
                                <span> <?php echo e(__('Sources')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('agency-list')): ?>
                        <li class="dropdown">
                            <a href="<?php echo e(route('agencies.index')); ?>"
                               class="nav-link menu-title <?php echo e(Route::currentRouteName() === 'agencies.index' ? 'active' : ''); ?>">
                                <i class="icon-magnet"></i>
                                <span> <?php echo e(__('Agencies')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('stats-list')): ?>
                        <?php if(auth()->user()->department_id === 2): ?>
                            <li class="dropdown">
                                <a href="<?php echo e(route('calls.index')); ?>"
                                   class="nav-link menu-title <?php echo e(Route::currentRouteName() === 'calls.index' ? 'active' : ''); ?>">
                                    <i class="icon-bar-chart"></i>
                                    <span> <?php echo e(__('Reporting')); ?></span>
                                </a>
                            </li>
                        <?php else: ?>
                            <li class="dropdown">
                                <a href="<?php echo e(route('static.index')); ?>"
                                   class="nav-link menu-title <?php echo e(Route::currentRouteName() === 'static.index' ? 'active' : ''); ?>">
                                    <i class="icon-bar-chart"></i>
                                    <span> <?php echo e(__('Reporting')); ?></span>
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user-list')): ?>
                        <li class="dropdown">
                            <a href="<?php echo e(route('users.index')); ?>"
                               class="nav-link menu-title <?php echo e(Route::currentRouteName() === 'users.index' ? 'active' : ''); ?>">
                                <i class="fa fa-users"></i>
                                <span> <?php echo e(__('Users')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('team-list')): ?>
                        <li class="dropdown">
                            <a href="<?php echo e(route('teams.index')); ?>"
                               class="nav-link menu-title <?php echo e(Route::currentRouteName() === 'teams.index' ? 'active' : ''); ?>">
                                <i class="icon-link"></i>
                                <span> <?php echo e(__('Teams')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('department-list')): ?>
                        <li class="dropdown">
                            <a href="<?php echo e(route('departments.index')); ?>"
                               class="nav-link menu-title <?php echo e(Route::currentRouteName() === 'departments.index' ? 'active' : ''); ?>">
                                <i class="fa fa-building"></i>
                                <span> <?php echo e(__('Departments')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('role-list')): ?>
                        <li class="dropdown">
                            <a href="<?php echo e(route('roles.index')); ?>"
                               class="nav-link menu-title <?php echo e(Route::currentRouteName() === 'roles.index' ? 'active' : ''); ?>">
                                <i class="fa fa-chain"></i>
                                <span> <?php echo e(__('Roles')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('settings')): ?>
                        <li class="dropdown">
                            <a href="<?php echo e(route('settings.index')); ?>"
                               class="nav-link menu-title <?php echo e(Route::currentRouteName() === 'settings.index' ? 'active' : ''); ?>">
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