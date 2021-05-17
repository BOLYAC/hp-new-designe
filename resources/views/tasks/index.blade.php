@extends('layouts.vertical.master')
@section('title', '| Tasks')
@section('style_before')
    <!-- Notification.css -->
    <link rel="stylesheet" href="{{ asset('assets/css/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/datatable-extension.css') }}">
@endsection

@section('script')
    <script src="{{ asset('assets/js/notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('assets/js/datatables/datatable-extension/dataTables.buttons.min.js')}}"></script>
    <script src="{{ asset('assets/js/datatables/datatable-extension/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatables/datatable-extension/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatables/datatable-extension/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatables/datatable-extension/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/js/datatables/datatable-extension/dataTables.autoFill.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatables/datatable-extension/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatables/datatable-extension/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatables/datatable-extension/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatables/datatable-extension/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatables/datatable-extension/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('assets/js/datatables/datatable-extension/dataTables.responsive.min.js')}}"></script>
    <script src="{{ asset('assets/js/datatables/datatable-extension/responsive.bootstrap4.min.js')}}"></script>

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

            function fetch_data(val) {
                let table = $('#datatable').DataTable({
                    destroy: true,
                    order: [[0, 'asc']],
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{!! route('tasks.data') !!}',
                        data: {
                            name: val,
                        }
                    },
                    @can('can-generate-report')
                    dom: 'lfrtBip',
                    buttons: [
                        {
                            extend: 'excel',
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            title: '',
                        },
                        {
                            extend: 'pdf',
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            title: '',
                        },
                        {
                            extend: 'print',
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            title: '',
                        }
                    ],
                    @endcan
                    columns: [
                        {
                            data: 'id',
                            name: 'id',
                        },
                        {
                            data: 'date',
                            name: 'date'
                        },
                        {
                            data: 'title',
                            name: 'title'
                        },
                        {
                            data: 'client_id',
                            name: 'client_id',
                        },
                        {
                            data: 'country',
                            name: 'country',
                        },
                        {
                            data: 'nationality',
                            name: 'nationality',
                        },
                        {
                            data: 'user_id',
                            name: 'user_id',
                            orderable: false,
                            searchable: false,
                        },
                        {
                            data: 'archive',
                            name: 'archive',
                            orderable: false,
                            searchable: false,
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                        },
                    ],

                });
                @can('change-task')
                table.on('click', '.assign', function () {
                    let $tr = $(this).closest('tr');
                    if ($($tr).hasClass('child')) {
                        $tr = $tr.prev('.parent');
                    }
                    let data = table.row($tr).id()
                    console.log(data)
                    $('#task_assigned_id').val(data);
                    $('#assignModal').modal('show');
                });
                @endcan
                // Submit Assignment
                $('#assignForm').on('submit', function (e) {
                    e.preventDefault();
                    let assigned_user = $('#assigned_user').val();
                    let task_assigned_id = $('#task_assigned_id').val();
                    $.ajax({
                        url: "{{ route('tasks.assigne') }}",
                        type: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            user_id: assigned_user,
                            task_assigned_id: task_assigned_id,
                        },
                        success: function (response) {
                            table.ajax.reload(null, false);
                            $('.modal').modal('hide');
                            notify('Task transferred', 'success');
                        },
                        error: function (response) {
                            notify('Something wrong', 'danger');
                        }
                    });
                });
            }

            $("#radioForm input[type='radio']").change(function () {
                const val = document.querySelector("input[name=radio]:checked").value;
                fetch_data(val);
            })
            fetch_data();
            window.setInterval(fetch_data, 1800000);


            // Select ajax

            $('.js-client-all').each(function () {
                $(this).select2({
                    dropdownParent: $(this).parent(),
                    ajax: {
                        url: "{{ route('event.client.filter') }}",
                        dataType: 'json',
                        delay: 250,
                        processResults: function (data) {
                            return {
                                results: $.map(data, function (item) {
                                    return {
                                        text: item.full_name,
                                        id: item.id
                                    }
                                })
                            };
                        },
                        cache: true
                    }
                });
            });
        });
    </script>

@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item">{{ __('Tasks list') }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <!-- Zero config.table start -->
                @include('partials.flash-message')
                <div class="card">
                    <div class="card-header p-2 b-b-primary b-t-primary row">
                        @can('task-create')
                            <div class="col-2">
                                <button class="btn btn-outline-success btn-sm" data-toggle="modal"
                                        data-target="#sign-in-modal">{{ __('New Task') }}
                                    <i class="icon-plus"></i></button>
                            </div>
                        @endcan
                        <div class="form-radio m-2 col text-right">
                            <form id="radioForm">
                                <div class="form-group m-checkbox-inline mb-0 custom-radio-ml">
                                    <div class="radio radio-primary">
                                        <input type="radio" id="radioinline1" name="radio" checked="checked"
                                               value="all">
                                        <label class="mb-0" for="radioinline1"><i class="helper"></i>{{ __('All') }}
                                        </label>
                                    </div>
                                    <div class="radio radio-primary">
                                        <input type="radio" id="radioinline2" name="radio"
                                               value="today-tasks">
                                        <label class="mb-0" for="radioinline2"><i
                                                class="helper"></i>{{ __('Today tasks') }}
                                        </label>
                                    </div>
                                    <div class="radio radio-primary">
                                        <input type="radio" id="radioinline3" name="radio"
                                               value="future-tasks">
                                        <label class="mb-0" for="radioinline3"><i
                                                class="helper"></i>{{ __('Future tasks') }}
                                        </label>
                                    </div>
                                    <div class="radio radio-primary">
                                        <input type="radio" id="radioinline4" name="radio"
                                               value="older-tasks">
                                        <label class="mb-0" for="radioinline4"><i
                                                class="helper"></i>{{ __('Older tasks') }}
                                        </label>
                                    </div>
                                    <div class="radio radio-primary">
                                        <input type="radio" id="radioinline5" name="radio"
                                               value="pending-tasks">
                                        <label class="mb-0" for="radioinline5"><i
                                                class="helper"></i>{{ __('Pending tasks') }}
                                        </label>
                                    </div>
                                    <div class="radio radio-primary">
                                        <input type="radio" id="radioinline6" name="radio"
                                               value="completed-tasks">
                                        <label class="mb-0" for="radioinline6"><i
                                                class="helper"></i>{{ __('Completed tasks') }}
                                        </label>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="order-history dt-ext table-responsive">
                            <table id="datatable"
                                   class="table task-list-table table-striped table-bordered nowrap"
                                   style="width:100%">
                                <thead>
                                <tr>
                                    <th width="10%">N°</th>
                                    <th width="20%">{{ __('Date') }}</th>
                                    <th width="40%">{{ __('Title') }}</th>
                                    <th width="20%">{{ __('Client') }}</th>
                                    <th width="20%">{{ __('Country') }}</th>
                                    <th width="20%">{{ __('Nationality') }}</th>
                                    <th width="20%">{{ __('Assigned') }}</th>
                                    <th width="20%">{{ __('Process') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                                </thead>

                            </table>
                        </div>
                    </div>
                    <div class="card-footer">

                    </div>
                </div>
            </div>
            <!-- Zero config.table end -->
        </div>
    </div>
    <!-- Edit modal start -->
    <div class="modal" id="assignModal" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Assign user') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="assignForm">
                    @csrf
                    <div class="modal-body p-b-0">
                        <input type="hidden" name="task_assigned_id" id="task_assigned_id">
                        <div class="form-group">
                            <select class="form-control" name="assigned_user" id="assigned_user">
                                <option value="" selected>-- {{ __('Select user') }} --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{ __('Save') }} <i class="ti-save-alt"></i>
                        </button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Edit modal end -->

    <!-- Create modal start -->
    <div class="modal" id="sign-in-modal" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('New task') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('tasks.store') }}" method="POST" id="createForm">
                    @csrf
                    <div class="modal-body p-b-0">
                        <div class="row">
                            <div class="form-group col-6">
                                <label for="title">
                                    {{ __('Title') }}
                                </label>
                                <input class="form-control" type="text" name="title"
                                       placeholder="{{ __('Task title') }}">
                            </div>
                            <div class="form-group col-6">
                                <label for="date">{{ __('Date') }}</label>
                                <input name="date" class="form-control" type="date"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <select class="form-control js-client-all" name="client_id">
                                <option selected="selected">{{ __('Search for the client') }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" name="body" cols="10" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{ __('Save') }} <i class="ti-save-alt"></i>
                        </button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
