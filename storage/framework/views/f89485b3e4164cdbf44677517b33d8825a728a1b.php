<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?php echo e(asset('assets/images/favicon.png')); ?>" type="image/x-icon">
    <link rel="shortcut icon" href="<?php echo e(asset('assets/images/favicon.png')); ?>" type="image/x-icon">
    <title>Hashim Group CRM <?php echo $__env->yieldContent('title'); ?></title>
    <?php echo \Livewire\Livewire::styles(); ?>

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


        <?php echo $__env->make('layouts.vertical.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>f
        <!-- Page Sidebar Ends-->
        <div class="page-body">
            <div class="container-fluid">
                <div class="page-header">
                    <div class="row">
                        <div class="col-lg-6">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><i class="f-16 fa fa-home"></i></a>
                                </li>
                                <?php echo $__env->yieldContent('breadcrumb-items'); ?>
                            </ol>
                            <?php echo $__env->yieldContent('breadcrumb-title'); ?>
                        </div>
                        <?php echo $__env->yieldContent('bookmarks-start'); ?>
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
<?php echo \Livewire\Livewire::scripts(); ?>

<?php echo $__env->make('layouts.vertical.script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</body>
</html>
<?php /**PATH C:\wamp64\www\hp\resources\views/layouts/vertical/master.blade.php ENDPATH**/ ?>