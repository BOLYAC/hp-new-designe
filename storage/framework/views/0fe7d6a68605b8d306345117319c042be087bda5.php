<script src="<?php echo e(asset('assets/js/jquery-3.2.1.min.js')); ?>"></script>
<!-- Bootstrap js-->
<script src="<?php echo e(asset('assets/js/bootstrap/popper.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/bootstrap/bootstrap.js')); ?>"></script>
<!-- feather icon js-->
<script src="<?php echo e(asset('assets/js/icons/feather-icon/feather.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/icons/feather-icon/feather-icon.js')); ?>"></script>
<!-- Sidebar jquery-->

<script src="<?php echo e(asset('assets/js/vertical-sidebar-menu.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/config.js')); ?>"></script>
<!-- Plugins JS start-->
<?php echo $__env->yieldContent('script'); ?>
<script src="<?php echo e(asset('assets/js/tooltip-init.js')); ?>"></script>
<!-- Plugins JS Ends-->
<!-- Theme js-->
<script src="<?php echo e(asset('assets/js/script.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/theme-customizer/customizer.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/notify/bootstrap-notify.min.js')); ?>"></script>
<script>
    $(document).ready(function () {
        function notify(title, type) {
            $.notify({
                    title: title
                },
                {
                    type: type,
                    allow_dismiss: true,
                    newest_on_top: true,
                    mouse_over: true,
                    showProgressbar: true,
                    spacing: 10,
                    timer: 2000,
                    placement: {
                        from: 'top',
                        align: 'right'
                    },
                    offset: {
                        x: 30,
                        y: 30
                    },
                    delay: 1000,
                    z_index: 10000,
                    animate: {
                        enter: 'animated bounce',
                        exit: 'animated bounce'
                    }
                });
        }

        $('#click2call').on('click', function (e) {
            e.preventDefault();
            $.ajax({
                url: "<?php echo e(route('click2call')); ?>",
                type: "POST",
                data: {
                    "_token": "<?php echo e(csrf_token()); ?>",
                },
                success: function (response) {

                    notify('Task transferred', 'success');
                },
                error: function (response) {
                    notify('Something wrong', 'danger');
                }
            });
        });

    })
</script>
<!-- Plugin used -->
<?php echo $__env->yieldContent('script_after'); ?>
<?php echo $__env->make('sweetalert::alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH C:\wamp64\www\hp\resources\views/layouts/vertical/script.blade.php ENDPATH**/ ?>