<header class="main-nav">
   <nav>
      <div class="main-navbar">
         <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
         <div id="mainnav">
            <ul class="nav-menu custom-scrollbar">
               <li class="back-btn">
                  <div class="mobile-back text-right"><span>Back</span><i class="fa fa-angle-right pl-2" aria-hidden="true"></i></div>
               </li>
               <li class="dropdown">
                  <a class="nav-link menu-title <?php echo e(Route::currentRouteName()=='index' ? 'active' : ''); ?>" href="<?php echo e(route('index')); ?>"><i data-feather="home"></i><span>Dashboard</span>
                  </a>
               </li>
               <li class="dropdown">
                  <a class="nav-link menu-title <?php echo e(request()->route()->getPrefix() == '/starter-kit' ? 'active' : ''); ?>" href="#"><i data-feather="anchor"></i><span>Sarter Kit</span>
                     <div class="according-menu"><i class="fa fa-angle-double-<?php echo e(request()->route()->getPrefix() == '/starter-kit' ? 'down' : 'right'); ?>"></i></div>
                  </a>
                  <ul class="nav-submenu menu-content" style="display: <?php echo e(request()->route()->getPrefix() == '/starter-kit' ? 'block;' : 'none'); ?>">
                     <li>
                        <a class="submenu-title <?php echo e(in_array(Route::currentRouteName(), ['layout-light', 'layout-dark']) ? 'active' : ''); ?>" href="#">Color Version
                           <div class="according-menu"><i class="fa fa-angle-double-<?php echo e(in_array(Route::currentRouteName(), ['layout-light', 'layout-dark']) ? 'down' : 'right'); ?>"></i></div>
                        </a>
                        <ul class="nav-sub-childmenu submenu-content" style="display: <?php echo e(in_array(Route::currentRouteName(), ['layout-light', 'layout-dark']) ? 'block' : 'none'); ?>;">
                           <li><a href="<?php echo e(route('layout-light')); ?>" class="<?php echo e(Route::currentRouteName()=='layout-light' ? 'active' : ''); ?>">Layout Light</a></li>
                           <li><a href="<?php echo e(route('layout-dark')); ?>" class="<?php echo e(Route::currentRouteName()=='layout-dark' ? 'active' : ''); ?>">Layout Dark</a></li>
                        </ul>
                     </li>
                     <li>
                        <a class="submenu-title <?php echo e(in_array(Route::currentRouteName(), ['layout-box', 'layout-rtl', 'compact-layout', 'vertical-layout', 'dark-rtl-layout', 'vertical-rtl-layout', 'compact-rtl-layout', 'dark-box-layout', 'vertical-box-layout', 'compact-dark-layout']) ? 'active' : ''); ?>" href="#">Page layout
                           <div class="according-menu"><i class="fa fa-angle-double-<?php echo e(in_array(Route::currentRouteName(), ['layout-box', 'layout-rtl', 'compact-layout', 'vertical-layout', 'dark-rtl-layout','vertical-rtl-layout', 'compact-rtl-layout', 'dark-box-layout', 'vertical-box-layout', 'compact-dark-layout']) ? 'down' : 'right'); ?>"></i></div>
                        </a>
                        <ul class="nav-sub-childmenu submenu-content" style="display: <?php echo e(in_array(Route::currentRouteName(), ['layout-box', 'layout-rtl', 'compact-layout', 'vertical-layout', 'dark-rtl-layout', 'vertical-rtl-layout', 'compact-rtl-layout', 'dark-box-layout', 'vertical-box-layout', 'compact-dark-layout']) ? 'block' : 'none'); ?>;">
                           <li><a href="<?php echo e(route('layout-box')); ?>" class="<?php echo e(Route::currentRouteName()=='layout-box' ? 'active' : ''); ?>">Layout-box</a></li>
                           <li><a href="<?php echo e(route('layout-rtl')); ?>" class="<?php echo e(Route::currentRouteName()=='layout-rtl' ? 'active' : ''); ?>">Layout RTL</a></li>
                           <li><a href="<?php echo e(route('compact-layout')); ?>" class="<?php echo e(Route::currentRouteName()=='compact-layout' ? 'active' : ''); ?>">Compact Layout</a></li>  
                           <li><a href="<?php echo e(route('vertical-layout')); ?>" class="<?php echo e(Route::currentRouteName()=='vertical-layout' ? 'active' : ''); ?>">Vertical Layout</a></li>
                           <li><a href="<?php echo e(route('dark-rtl-layout')); ?>" class="<?php echo e(Route::currentRouteName()=='dark-rtl-layout' ? 'active' : ''); ?>">Dark & RTL Layout</a></li>
                           <li><a href="<?php echo e(route('vertical-rtl-layout')); ?>" class="<?php echo e(Route::currentRouteName()=='vertical-rtl-layout' ? 'active' : ''); ?>">Vertical & RTL Layout</a></li>
                           <li><a href="<?php echo e(route('compact-rtl-layout')); ?>" class="<?php echo e(Route::currentRouteName()=='compact-rtl-layout' ? 'active' : ''); ?>">Compact & RTL Layout</a></li>
                           <li><a href="<?php echo e(route('dark-box-layout')); ?>" class="<?php echo e(Route::currentRouteName()=='dark-box-layout' ? 'active' : ''); ?>">Dark & Box Layout</a></li>
                           <li><a href="<?php echo e(route('vertical-box-layout')); ?>" class="<?php echo e(Route::currentRouteName()=='vertical-box-layout' ? 'active' : ''); ?>">Vetical Box Layout</a></li>
                           <li><a href="<?php echo e(route('compact-dark-layout')); ?>" class="<?php echo e(Route::currentRouteName()=='compact-dark-layout' ? 'active' : ''); ?>">Compact & Dark Layout</a></li>
                        </ul>
                     </li>
                     <li>
                        <a class="submenu-title <?php echo e(in_array(Route::currentRouteName(), ['footer-light', 'footer-dark', 'footer-fixed']) ? 'active' : ''); ?>" href="#">Footers
                           <div class="according-menu"><i class="fa fa-angle-double-<?php echo e(in_array(Route::currentRouteName(), ['footer-light', 'footer-dark', 'footer-fixed']) ? 'down' : 'right'); ?>"></i></div>
                        </a>
                        <ul class="nav-sub-childmenu submenu-content" style="display: <?php echo e(in_array(Route::currentRouteName(), ['footer-light', 'footer-dark', 'footer-fixed']) ? 'block' : 'none'); ?>;">
                           <li><a href="<?php echo e(route('footer-light')); ?>" class="<?php echo e(Route::currentRouteName()=='footer-light' ? 'active' : ''); ?>">Footer Light</a></li>
                           <li><a href="<?php echo e(route('footer-dark')); ?>" class="<?php echo e(Route::currentRouteName()=='footer-dark' ? 'active' : ''); ?>">Footer Dark</a></li>
                           <li><a href="<?php echo e(route('footer-fixed')); ?>" class="<?php echo e(Route::currentRouteName()=='footer-fixed' ? 'active' : ''); ?>">Footer Fixed</a></li>
                        </ul>
                     </li>
                  </ul>
               </li>
            </ul>
         </div>
         <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
      </div>
   </nav>
</header><?php /**PATH /home/ekta/Documents/0 - Themeforest - Themes/Xolo Theme/Xolo_Starter_Kit/resources/views/layouts/box/sidebar.blade.php ENDPATH**/ ?>