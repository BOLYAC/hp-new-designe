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
    <script>

        // Main data table
        let table = $('#leads-table').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ajax: {
                url: '{!! route('clients.data') !!}',
                data: function (d) {
                    d.user = $('select[name=user_filter1]').val();
                    d.source = $('select[name=source_filter]').val();
                    d.status = $('select[name=status_filter]').val();
                    d.priority = $('select[name=priority_filter1]').val();
                    d.assigned = $('select[name=assigned_user_id]').val();
                    d.country = $('input[name=country_filter]').val();
                    d.phone = $('input[name=phone_filter]').val();
                    d.cName = $('input[name=name_filter]').val();
                    d.next_call = $('input[name=next_call_filter]').val();
                    d.public_id = $('input[name=client_public_id]').val();
                    d.email = $('input[name=email_filter]').val();
                    d.filter_var = $("input[type='radio'][name=options]:checked").val();
                    d.filter_order = $("input[type='radio'][name=orders]:checked").val();
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
                {data: 'client_number', name: 'client_number'},
                {data: 'client_email', name: 'client_email'},
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
                    targets: [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]
                }
            ],
            order: [[1, 'asc']]
        });

        // Filtration sidebar
        $("#cts_select").hide();
        $("#pts_select").hide();

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
                    <div class="card-body p-2">
                        <div class="form-group mb-2">
                            <select class="form-control form-control-sm digits" id="status_filter">
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
                            <select class="form-control form-control-sm digits" id="source_filter">
                                <option value="">{{ __('Source') }}</option>

                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <select class="form-control form-control-sm digits" id="priority_filter">
                                <option value="">{{ __('Priority') }}</option>
                                <option value="1">{{ __('Low') }}</option>
                                <option value="2">{{ __('Medium') }}</option>
                                <option value="3">{{ __('High') }}</option>
                            </select>
                        </div>
                        <div class="checkbox checkbox-primary">
                            <input id="country_check" type="checkbox"
                                   onclick="valueChanged()">
                            <label for="country_check">{{ __('Country') }}</label>
                        </div>
                        <div class="form-group mb-2 ml-2" id="cts_select">
                            <select class="form-control form-control-sm digits mb-1" id="country_type">
                                <option value="">{{ __('is') }}</option>
                                <option value="1">{{ __('isn\'t') }}</option>
                                <option value="2">{{ __('contains') }}</option>
                                <option value="3">{{ __('dosen\'t contain') }}</option>
                                <option value="4">{{ __('start with') }}</option>
                                <option value="5">{{ __('ends with') }}</option>
                                <option value="6">{{ __('is empty') }}</option>
                                <option value="7">{{ __('is note empty') }}</option>
                            </select>
                            <input type="text" class="form-control form-control-sm" placeholder="{{ __('Type here') }}"
                                   id="country_field">
                        </div>
                        <div class="checkbox checkbox-primary">
                            <input id="phone_check" type="checkbox"
                                   onclick="valuePhoneChanged()">
                            <label for="phone_check">{{ __('Phone') }}</label>
                        </div>
                        <div class="form-group mb-2 ml-2" id="pts_select">
                            <select class="form-control form-control-sm digits mb-1" id="phone_type">
                                <option value="">{{ __('is') }}</option>
                                <option value="1">{{ __('isn\'t') }}</option>
                                <option value="2">{{ __('contains') }}</option>
                                <option value="3">{{ __('dosen\'t contain') }}</option>
                                <option value="4">{{ __('start with') }}</option>
                                <option value="5">{{ __('ends with') }}</option>
                                <option value="6">{{ __('is empty') }}</option>
                                <option value="7">{{ __('is note empty') }}</option>
                            </select>
                            <input type="text" class="form-control form-control-sm" placeholder="{{ __('Type here') }}"
                                   id="phone_field">
                        </div>
                        <div class="form-group mb-2">
                            <select class="form-control form-control-sm digits" id="assigned_filter">
                                <option value="">{{ __('Assigned') }}</option>

                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <input type="text" class="form-control form-control-sm"
                                   placeholder="{{ __('Last active') }}"
                                   id="last_active">
                        </div>
                        <div class="checkbox checkbox-primary">
                            <input id="no_tasks" type="checkbox">
                            <label for="no_tasks">{{ __('No tasks') }}</label>
                        </div>
                        <div class="theme-form mb-2">
                            <input class="form-control form-control-sm digits" type="text" name="daterange"
                                   value="">
                        </div>
                        <div class="form-group">
                            <label class="d-block" for="edo-ani">
                                <input class="radio_animated" id="edo-ani" type="radio" name="rdo-ani"
                                       checked=""
                                       data-original-title="" title=""> {{ __('Creation') }}
                            </label>
                            <label class="d-block" for="edo-ani1">
                                <input class="radio_animated" id="edo-ani1" type="radio" name="rdo-ani"
                                       data-original-title="" title=""> {{ __('Modification') }}
                            </label>
                            <label class="d-block" for="edo-ani2">
                                <input class="radio_animated" id="edo-ani2" type="radio" name="rdo-ani"
                                       checked=""
                                       data-original-title="" title=""> {{ __('Arrival') }}
                            </label>
                            <label class="d-block" for="edo-ani13">
                                <input class="radio_animated" id="edo-ani13" type="radio" name="rdo-ani"
                                       data-original-title="" title=""> {{ __('None') }}
                            </label>
                        </div>
                    </div>
                    <div class="card-footer p-2">
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <button class="btn btn-primary" type="button">{{ __('Filter') }}</button>
                            <button class="btn btn-light" type="button">{{ __('Clear') }}</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-10">
                <div class="card p-1">
                    <div class="card-header card-no-border p-1">
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
                    <div class="card-body p-1">
                        <div class="order-history dt-ext table-responsive">
                            <table id="leads-table" class="table table-striped display table-bordered nowrap"
                                   width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th></th>
                                    <th>N°</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>E-mail</th>
                                    <th>Country</th>
                                    <th>
                                        <select name="status_filter" id="status_filter"
                                                class="custom-select custom-select-sm">
                                            <option value=""> Status
                                            </option>
                                            <option value="1">
                                                New Lead
                                            </option>
                                            <option value="8">
                                                No Answer
                                            </option>
                                            <option value="12">
                                                In progress
                                            </option>
                                            <option value="3">
                                                Potential
                                                appointment
                                            </option>
                                            <option value="4">
                                                Appointment
                                                set
                                            </option>
                                            <option value="10">
                                                Appointment
                                                follow up
                                            </option>
                                            <option value="5">
                                                Sold
                                            </option>
                                            <option value="13">
                                                Unreachable
                                            </option>
                                            <option value="7">
                                                Not interested
                                            </option>
                                            <option value="11">
                                                Low budget
                                            </option>
                                            <option value="9">
                                                Wrong Number
                                            </option>
                                            <option value="14">
                                                Unqualified
                                            </option>
                                        </select>
                                    </th>
                                    <th>
                                        <select name="source_filter" id="source_filter"
                                                class="custom-select custom-select-sm">
                                            <option value="">Source</option>
                                            @foreach($sources as $row)
                                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th>
                                        <select name="agency_filter" id="agency_filter"
                                                class="custom-select custom-select-sm">
                                            <option value="">Agency</option>
                                            @foreach($agencies as $row)
                                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th>
                                        <select name="priority_filter" id="priority_filter"
                                                class="custom-select custom-select-sm">
                                            <option value="">Priority
                                            </option>
                                            <option value="1">
                                                Low
                                            </option>
                                            <option value="2">
                                                Medium
                                            </option>
                                            <option value="3">
                                                High
                                            </option>
                                        </select>
                                    </th>
                                    <th>
                                        @if(auth()->user()->hasRole('Admin'))
                                            <select name="user_filter" id="user_filter"
                                                    class="custom-select custom-select-sm">
                                                <option value="">Assigned</option>
                                                @foreach($users as $row)
                                                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                                                @endforeach
                                            </select>
                                        @elseif(auth()->user()->hasRole('Manager'))
                                            @if(auth()->user()->ownedTeams()->count() > 0)
                                                <select name="user_filter" id="user_filter"
                                                        class="custom-select custom-select-sm">
                                                    <option value="">Assigned</option>
                                                    @foreach(auth()->user()->currentTeam->allUsers() as $user)
                                                        <option
                                                            value="{{ $user->id }}">{{ $user->name }}</option>
                                                    @endforeach
                                                </select>
                                            @else
                                                Assigned
                                            @endif
                                        @else
                                            Assigned
                                        @endif
                                    </th>
                                    <th>Creation date</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody class="task-page">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
