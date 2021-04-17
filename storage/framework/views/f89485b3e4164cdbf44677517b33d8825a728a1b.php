<!DOCTYPE html>
<html lang="en" dir="ltr">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="description" content="Xolo admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
      <meta name="keywords" content="admin template, Xolo admin template, dashboard template, flat admin template, responsive admin template, web app">
      <meta name="author" content="pixelstrap">
      <link rel="icon" href="<?php echo e(asset('assets/images/favicon.png')); ?>" type="image/x-icon">
      <link rel="shortcut icon" href="<?php echo e(asset('assets/images/favicon.png')); ?>" type="image/x-icon">
      <title>Xolo <?php echo $__env->yieldContent('title'); ?></title>
      <?php echo $__env->make('layouts.vertical.css', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
      <?php echo $__env->yieldContent('style'); ?>
   </head>
   <body class="light-only" main-theme-layout="ltr">
      <!-- Loader starts-->
      <div class="loader-wrapper">
         <div class="theme-loader"></div>
      </div>
      <!-- Loader ends-->
      <!-- page-wrapper Start-->
      <div class="page-wrapper horizontal-wrapper" id="pageWrapper">
         <!-- Page Header Start-->
         <?php echo $__env->make('layouts.vertical.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
         <!-- Page Header Ends -->
         <!-- Page Body Start-->
         <div class="page-body-wrapper horizontal-menu">
            <nav-menus></nav-menus>
            <?php echo $__env->make('layouts.vertical.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <!-- Page Sidebar Ends-->
            <div class="page-body">
               <div class="container-fluid">
                  <div class="page-header">
                     <div class="row">
                        <div class="col-lg-6">
                           <?php echo $__env->yieldContent('breadcrumb-title'); ?>
                           <ol class="breadcrumb">
                              <li class="breadcrumb-item"><a href="<?php echo e(route('/')); ?>"><i class="f-16 fa fa-home"></i></a></li>
                              <?php echo $__env->yieldContent('breadcrumb-items'); ?>
                           </ol>
                        </div>
                        <div class="col-lg-6">
                           <!-- Bookmark Start-->
                           <div class="bookmark pull-right">
                              <ul>
                                 <li><a href="#" data-container="body" data-toggle="popover" data-placement="top" title="" data-original-title="Tables"><i data-feather="inbox"></i></a></li>
                                 <li><a href="#" data-container="body" data-toggle="popover" data-placement="top" title="" data-original-title="Chat"><i data-feather="message-square"></i></a></li>
                                 <li><a href="#" data-container="body" data-toggle="popover" data-placement="top" title="" data-original-title="Icons"><i data-feather="command"></i></a></li>
                                 <li><a href="#" data-container="body" data-toggle="popover" data-placement="top" title="" data-original-title="Learning"><i data-feather="layers"></i></a></li>
                                 <li>
                                    <a href="#"><i class="bookmark-search" data-feather="star"></i></a>
                                    <form class="form-inline search-form">
                                       <div class="form-group form-control-search">
                                          <input type="text" placeholder="Search..">
                                       </div>
                                    </form>
                                 </li>
                              </ul>
                           </div>
                           <!-- Bookmark Ends-->
                        </div>
                     </div>
                  </div>
               </div>
               <!-- Container-fluid starts-->
               <?php echo $__env->yieldContent('content'); ?>
               <!-- Container-fluid Ends-->
            </div>
            <!-- footer start-->
            <?php echo $__env->make('layouts.vertical.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
         </div>
      </div>
      <?php echo $__env->make('layouts.vertical.script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
   </body>
</html><?php /**PATH C:\wamp64\www\hp\resources\views/layouts/vertical/master.blade.php ENDPATH**/ ?>