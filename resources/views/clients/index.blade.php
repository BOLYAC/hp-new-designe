@extends('layouts.vertical.master')
@section('title', 'Leads')
@section('style_before')
    <!-- Datatables.css -->
    <link rel="stylesheet" href="{{ asset('assets/css/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/datatable-extension.css') }}">
    <!-- Notification.css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/daterange-picker.css') }}">
@endsection


@section('style')
    <style>
        .dt-head-center {
            text-align: center;
            vertical-align: middle !important;
        }

        table.dataTable thead .sorting::after,
        table.dataTable thead .sorting_asc::after,
        table.dataTable thead .sorting_desc::after {
            display: none;
        }

        table.dataTable thead .sorting::before,
        table.dataTable thead .sorting_asc::before,
        table.dataTable thead .sorting_desc::before {
            display: none;
        }

        table.dataTable thead .sorting {
            background-image: url(https://datatables.net/media/images/sort_both.png);
            background-repeat: no-repeat;
            background-position: center right;
        }

        table.dataTable thead .sorting_asc {
            background-image: url(https://datatables.net/media/images/sort_asc.png);
            background-repeat: no-repeat;
            background-position: center right;
        }

        table.dataTable thead .sorting_desc {
            background-image: url(https://datatables.net/media/images/sort_desc.png);
            background-repeat: no-repeat;
            background-position: center right;
        }
    </style>
@endsection

@section('script')
    <!-- Datatables.js -->
    <script src="{{asset('assets/js/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables/datatable-extension/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables/datatable-extension/buttons.bootstrap4.min.js')}}"></script>

    <script src="{{asset('assets/js/datatables/datatable-extension/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables/datatable-extension/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables/datatable-extension/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables/datatable-extension/dataTables.colReorder.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables/datatable-extension/dataTables.rowReorder.min.js')}}"></script>
    <!-- Plugins JS start-->
    <script src="{{ asset('assets/js/datepicker/daterange-picker/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker/daterange-picker/daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker/daterange-picker/daterange-picker.custom.js') }}"></script>
    <!-- Notify -->
    <script src="{{ asset('assets/js/notify/bootstrap-notify.min.js') }}"></script>
    <script>
        function notify(title, type) {
            $.notify({
                    title: title
                },
                {
                    type: type,
                    allow_dismiss: true,
                    newest_on_top: true,
                    mouse_over: true,
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

        // Main data table
        let table = $('#leads-table').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            responsive: true,
            ajax: {
                url: '{!! route('clients.data') !!}',
                data: function (d) {
                    d.status = $('select[name=status_filter]').val();
                    d.source = $('select[name=source_filter]').val();
                    d.priority = $('select[name=priority_filter]').val();
                    d.agency = $('select[name=agency_filter]').val();
                    d.country_check = $('#country_check').is(':checked');
                    d.country_type = $('select[name=country_type]').val();
                    d.country = $('input[name=country_field]').val();
                    d.phone_check = $('#phone_check').is(':checked');
                    d.phone_type = $('select[name=phone_type]').val();
                    d.phone = $('input[name=phone_field]').val();
                    d.user = $('select[name=user_filter]').val();
                    d.daysActif = $('#last_active').val();
                    d.lastUpdate = $('#no_tasks').is(':checked');
                    d.daterange = $('input[name=daterange]').val()
                    d.filterDateBase = $('input[type="radio"]:checked').val();
                }
            },
            "drawCallback": function (settings) {
                var api = this.api();
            },
            columns: [
                {data: 'id', name: 'id', visible: false},
                {data: 'check', name: 'check', orderable: false, searchable: false,},
                {data: 'public_id', name: 'public_id'},
                {data: 'full_name', name: 'full_name'},
                {data: 'country', name: 'country'},
                {data: 'status', name: 'status', orderable: false, searchable: false,},
                {data: 'source_id', name: 'source_id', orderable: false, searchable: false,},
                {data: 'agency_id', name: 'agency_id', orderable: false, searchable: false,},
                {data: 'priority', name: 'priority', orderable: false, searchable: false,},
                {data: 'user_id', name: 'user_id', orderable: false, searchable: false},
                {data: 'created_at', name: 'created_at', searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            columnDefs: [
                // Center align the header content of column 1
                {
                    className: "dt-head-center",
                    targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                }
            ],
            order: [[1, 'asc']]
        });
        // Assigne user
        $('#refresh').click(function () {
            $('select[name=status_filter]').val('');
            $('select[name=source_filter]').val('');
            $('select[name=priority_filter]').val('');
            $('select[name=agency_filter]').val('');
            $('#country_check').prop('checked', false);
            $('input[name=country_field]').val('');
            $('#phone_check').prop('checked', false);
            $('input[name=phone_field]').val('');
            $('select[name=user_filter]').val('');
            $('#last_active').val('');
            $('#no_tasks').prop('checked', false);
            $('input[name=daterange]').val('')
            $('input[type="radio"]').filter('[value=none]').prop('checked', true);
            valueChanged()
            valuePhoneChanged()
            table.DataTable().destroy();
        });
        // Search form
        $('#search-form').on('submit', function (e) {
            table.draw();
            e.preventDefault();
        });
        // Check/Uncheck ALl
        $('#checkAll').change(function () {
            if ($(this).is(':checked')) {
                $('.checkbox-circle').prop('checked', true);
            } else {
                $('.checkbox-circle').each(function () {
                    $(this).prop('checked', false);
                });
            }
        });
        @can('share-client')
        // Select all, trigger modal
        $('#row-select-btn').on('click', function (e) {
            e.preventDefault();
            let filter = [];
            $('.checkbox-circle:checked').each(function () {
                filter.push($(this).val());
            });
            if (filter.length > 0) {
                $('#massAssignModal').modal('show');
            } else {
                notify('At least select one lead!', 'danger');
            }
        });
        @endcan
        // Mass assign modal
        $('#massAssignForm').on('submit', function (e) {
            e.preventDefault();
            let ids = [];
            $('.checkbox-circle:checked').each(function () {
                ids.push($(this).val());
            });
            $.ajax({
                url: "{{ route('sales.share.mass') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    user_id: $('#assigned_user_mass').val(),
                    clients: ids,
                },
                success: function (response) {
                    table.ajax.reload(null, false);
                    $('#massAssignModal').modal('hide');
                    notify('Lead transferred', 'success');
                },
            });
        });

        // Filtration sidebar
        $("#cts_select").hide();
        $("#pts_select").hide();


        $(document).ready(function () {
            $('input[name=daterange]').val('')
        });


        function valueChanged() {
            if ($('#country_check').is(":checked"))
                $("#cts_select").show();
            else
                $("#cts_select").hide();
        }

        function valuePhoneChanged() {
            if ($('#phone_check').is(":checked"))
                $("#pts_select").show();
            else
                $("#pts_select").hide();
        }

    </script>

@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">{{ __('Leads') }}</li>
@endsection

@section('breadcrumb-title')
    <h3>{{ __('Leads list') }}</h3>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-2">
                <div class="card p-1">
                    <div class="card-header b-l-primary p-2">
                        <h6 class="m-0">{{ __('Filter leads by:') }}</h6>
                    </div>
                    <form id="search-form">
                        <div class="card-body p-2">
                            <div class="form-group mb-2">
                                <select class="form-control form-control-sm digits" id="status_filter"
                                        name="status_filter">
                                    <option value="">{{ __('Status') }}</option>
                                    <option value="1">{{ __('New Lead') }}</option>
                                    <option value="8">{{ __('No Answer') }}</option>
                                    <option value="12">{{ __('In progress') }}</option>
                                    <option value="3">{{ __('Potential appointment') }}</option>
                                    <option value="4">{{ __('Appointment set') }}</option>
                                    <option value="10">{{ __('Appointment follow up') }}</option>
                                    <option value="5">{{ __('Sold') }}</option>
                                    <option value="13">{{ __('Unreachable') }}</option>
                                    <option value="7">{{ __('Not interested') }}</option>
                                    <option value="11">{{ __('Low budget') }}</option>
                                    <option value="9">{{ __('Wrong Number') }}</option>
                                    <option value="14">{{ __('Unqualified') }}</option>
                                </select>
                            </div>
                            <div class="form-group mb-2">
                                <select class="form-control form-control-sm digits" id="source_filter"
                                        name="source_filter">
                                    <option value="">{{ __('Source') }}</option>
                                    @foreach($sources as $row)
                                        <option value="{{ $row->id }}">{{ $row->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-2">
                                <select class="form-control form-control-sm digits" id="priority_filter"
                                        name="priority_filter">
                                    <option value="">{{ __('Priority') }}</option>
                                    <option value="1">{{ __('Low') }}</option>
                                    <option value="2">{{ __('Medium') }}</option>
                                    <option value="3">{{ __('High') }}</option>
                                </select>
                            </div>
                            <div class="form-group mb-2">
                                <select class="form-control form-control-sm digits" id="agency_filter"
                                        name="agency_filter">
                                    <option value="">{{ __('Agency') }}</option>
                                    @foreach($agencies as $row)
                                        <option value="{{ $row->id }}">{{ $row->name }}</option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="checkbox checkbox-primary">
                                <input id="country_check" type="checkbox"
                                       onclick="valueChanged()">
                                <label for="country_check">{{ __('Country') }}</label>
                            </div>
                            <div class="form-group mb-2 ml-2" id="cts_select">
                                <select class="form-control form-control-sm digits mb-1" id="country_type"
                                        name="country_type">
                                    <option value="1">{{ __('is') }}</option>
                                    <option value="2">{{ __('isn\'t') }}</option>
                                    <option value="3">{{ __('contains') }}</option>
                                    <option value="4">{{ __('dosen\'t contain') }}</option>
                                    <option value="5">{{ __('start with') }}</option>
                                    <option value="6">{{ __('ends with') }}</option>
                                    <option value="7">{{ __('is empty') }}</option>
                                    <option value="8">{{ __('is note empty') }}</option>
                                </select>
                                <input type="text" class="form-control form-control-sm"
                                       placeholder="{{ __('Type here') }}"
                                       id="country_field" name="country_field">
                            </div>
                            <div class="checkbox checkbox-primary">
                                <input id="phone_check" type="checkbox"
                                       onclick="valuePhoneChanged()">
                                <label for="phone_check">{{ __('Phone') }}</label>
                            </div>
                            <div class="form-group mb-2 ml-2" id="pts_select">
                                <select class="form-control form-control-sm digits mb-1" id="phone_type"
                                        name="phone_type">
                                    <option value="1">{{ __('is') }}</option>
                                    <option value="2">{{ __('isn\'t') }}</option>
                                    <option value="3">{{ __('contains') }}</option>
                                    <option value="4">{{ __('dosen\'t contain') }}</option>
                                    <option value="5">{{ __('start with') }}</option>
                                    <option value="6">{{ __('ends with') }}</option>
                                    <option value="7">{{ __('is empty') }}</option>
                                    <option value="8">{{ __('is note empty') }}</option>
                                </select>
                                <input type="text" class="form-control form-control-sm"
                                       placeholder="{{ __('Type here') }}"
                                       id="phone_field" name="phone_field">
                            </div>
                            @if(auth()->user()->hasRole('Admin'))
                                <div class="form-group mb-2">
                                    <option value="">{{ __('Assigned') }}</option>
                                    <select name="user_filter" id="user_filter"
                                            class="custom-select custom-select-sm">
                                        <option value="">{{ __('Assigned') }}</option>
                                        @foreach($users as $row)
                                            <option value="{{ $row->id }}">{{ $row->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @elseif(auth()->user()->hasRole('Manager'))
                                @if(auth()->user()->ownedTeams()->count() > 0)
                                    <div class="form-group mb-2">
                                        <select name="user_filter" id="user_filter"
                                                class="custom-select custom-select-sm">
                                            <option value="">{{ __('Assigned') }}</option>
                                            @foreach(auth()->user()->currentTeam->allUsers() as $user)
                                                <option
                                                    value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            @endif
                            <div class="form-group mb-2">
                                <input type="text" class="form-control form-control-sm"
                                       placeholder="{{ __('Last active') }}"
                                       id="last_active" name="last_active">
                            </div>
                            <div class="checkbox checkbox-primary">
                                <input id="no_tasks" type="checkbox">
                                <label for="no_tasks" nonce="no_tasks">{{ __('No tasks') }}</label>
                            </div>
                            <div class="theme-form mb-2">
                                <input class="form-control form-control-sm digits" type="text" name="daterange"
                                       value="">
                            </div>
                            <div class="form-group">
                                <label class="d-block" for="edo-ani">
                                    <input class="radio_animated" id="edo-ani" type="radio" name="rdo-ani"
                                           value="creation"> {{ __('Creation') }}
                                </label>
                                <label class="d-block" for="edo-ani1">
                                    <input class="radio_animated" id="edo-ani1" type="radio" name="rdo-ani"
                                           value="modification"> {{ __('Modification') }}
                                </label>
                                <label class="d-block" for="edo-ani2">
                                    <input class="radio_animated" id="edo-ani2" type="radio" name="rdo-ani"
                                           value="arrival"> {{ __('Arrival') }}
                                </label>
                                <label class="d-block" for="edo-ani13">
                                    <input class="radio_animated" id="edo-ani13" type="radio" name="rdo-ani" checked
                                           value="none"> {{ __('None') }}
                                </label>
                            </div>
                        </div>
                        <div class="card-footer p-2">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <button class="btn btn-primary" type="submit">{{ __('Filter') }}</button>
                                <button class="btn btn-light" type="button" id="refresh">{{ __('Clear') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-sm-10">
                <div class="card p-1">
                    <div class="card-header card-no-border p-2  b-t-primary">
                        @can('share-client')
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-primary btn-sm">
                                    <input id="checkAll" type="checkbox" checked
                                           autocomplete="off">{{ __('Select/Unselect') }}
                                </label>
                            </div>
                            <button type="button" id="row-select-btn" class="btn btn-primary btn-sm">
                                {{ __('Assign Lead') }}
                            </button>
                            <button type="button" id="row-send-btn" class="btn btn-primary btn-sm">
                                {{ __('Send project') }}
                            </button>
                            <button type="button" id="row-delete-btn" class="btn btn-danger btn-sm">
                                {{ __('Delete') }}
                            </button>
                        @endcan
                        @if(auth()->user()->hasRole('Admin'))
                            <a class="btn btn-sm btn-outline-success m-b-20 float-right ml-2"
                               href="{{ route('importExportZoho') }}">{{ __('Zoho
                                Import') }}<i class="icofont icofont-upload"></i></a>
                        @endif
                        @can('client-import')
                            <a class="btn btn-sm btn-outline-success m-b-20 float-right ml-2"
                               href="{{ route('importExport') }}">{{ __('Import
                                data') }} <i class="icofont icofont-upload"></i></a>
                        @endcan

                        @can('client-create')
                            <a href="{{ route('clients.create') }}"
                               class="btn btn-sm btn-outline-primary m-b-20 float-right">{{ __('New
                                lead') }} <i class="icofont icofont-plus"></i></a>
                        @endcan
                    </div>
                    <div class="card-body p-1 b-t-primary">
                        <div class="order-history dt-ext table-responsive m-2">
                            <table id="leads-table" class="display">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th></th>
                                    <th data-priority="1">N°</th>
                                    <th data-priority="2">{{ __('Name') }}</th>
                                    <th>{{ __('Country') }}</th>
                                    <th>
                                        {{ __('Status') }}
                                    </th>
                                    <th>
                                        {{ __('Source') }}
                                    </th>
                                    <th>
                                        {{ __('Agency') }}
                                    </th>
                                    <th>
                                        {{ __('Priority') }}
                                    </th>
                                    <th>
                                        {{ __('Assigned') }}
                                    </th>
                                    <th>{{ __('Creation date') }}</th>
                                    <th data-priority="3">{{ __('Action') }}</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Mass Assigne -->
    <div class="modal fade" id="massAssignModal" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Assign to user') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="massAssignForm">
                    @csrf
                    <div class="modal-body p-b-0">
                        <div class="form-group">
                            <select class="form-control" name="assigned_user_mass" id="assigned_user_mass">
                                <option value="" selected>-- {{ __('Select user') }} --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{ __('Save') }} <i class="icon-save"></i>
                        </button>
                        <button type="button" class="btn btn-warning" data-dismiss="modal">{{ __('Cancel') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
