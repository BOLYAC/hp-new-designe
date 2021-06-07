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
    <div class="customizer-contain">
        <div class="customizer-links">
            <div class="row">
                <div class="col call-chat-body">
                    <div class="card shadow-0 border">
                        <div class="card-body p-0">
                            <div class="chat-box">
                                <!-- Chat right side start-->
                                <div class="chat-right-aside" style="max-width:100%!important;">
                                    <!-- chat start-->
                                    <div class="chat">
                                        <!-- chat-header start-->
                                        <div class="chat-header clearfix">
                                            <div class="about">

                                            </div>
                                            <ul class="list-inline float-left float-sm-right chat-menu-icons">
                                                <li class="list-inline-item"><a id="click2call" href="#"><i
                                                            class="icon-headphone-alt"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="chat-history">
                                            <div class="text-center pr-0 call-content">
                                                <div>
                                                    <div class="total-time">
                                                        <h2 class="digits">36 : 56</h2>
                                                    </div>
                                                    <div class="call-icons">
                                                    </div>
                                                    <button class="btn btn-danger-gradien btn-block btn-lg">END CALL
                                                    </button>
                                                    <div class="receiver-img"><img
                                                            src="../assets/images/other-images/receiver-img.jpg"
                                                            alt=""></div>
                                                </div>
                                            </div>
                                            <div class="col-sm-7 pl-0 caller-img">
                                                <img class="img-fluid"
                                                     src="../assets/images/other-images/caller.jpg"
                                                     alt=""></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo \Livewire\Livewire::scripts(); ?>

<?php echo $__env->make('layouts.vertical.script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</body>
</html>
<?php /**PATH C:\wamp64\www\hp\resources\views/layouts/vertical/master.blade.php ENDPATH**/ ?>