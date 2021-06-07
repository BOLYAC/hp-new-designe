<script src="{{asset('assets/js/jquery-3.2.1.min.js')}}"></script>
<!-- Bootstrap js-->
<script src="{{asset('assets/js/bootstrap/popper.min.js')}}"></script>
<script src="{{asset('assets/js/bootstrap/bootstrap.js')}}"></script>
<!-- feather icon js-->
<script src="{{asset('assets/js/icons/feather-icon/feather.min.js')}}"></script>
<script src="{{asset('assets/js/icons/feather-icon/feather-icon.js')}}"></script>
<!-- Sidebar jquery-->

<script src="{{asset('assets/js/vertical-sidebar-menu.js')}}"></script>
<script src="{{asset('assets/js/config.js')}}"></script>
<!-- Plugins JS start-->
@yield('script')
<script src="{{asset('assets/js/tooltip-init.js')}}"></script>
<!-- Plugins JS Ends-->
<!-- Theme js-->
<script src="{{asset('assets/js/script.js')}}"></script>
<script src="{{ asset('assets/js/theme-customizer/customizer.js') }}"></script>
<script src="{{ asset('assets/js/notify/bootstrap-notify.min.js') }}"></script>
<script src="https://cdn.rawgit.com/robcowie/jquery-stopwatch/master/jquery.stopwatch.js"></script>
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
                url: "{{ route('click2call') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function (response) {
                    $('#demo1').stopwatch().stopwatch('start');
                    notify('Task transferred', 'success');
                },
                error: function (response) {
                    notify('Something wrong', 'danger');
                }
            });
        });

        $('#endCall').on('click', function (e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('click2hang') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function (response) {
                    $('#demo1').stopwatch().click(function(){ 
                        $(this).stopwatch('reset');
                    });
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
@yield('script_after')
@include('sweetalert::alert')
