@extends('layouts.vertical.master')
@section('title', '| Deals')

@section('style_before')
    <!-- Notification.css -->
    <link rel="stylesheet" href="{{ asset('assets/css/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/datatable-extension.css') }}">
@endsection

@section('script')

    <script src="{{asset('assets/js/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables/datatable-extension/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables/datatable-extension/buttons.bootstrap4.min.js')}}"></script>

    <script src="{{asset('assets/js/datatables/datatable-extension/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables/datatable-extension/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables/datatable-extension/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables/datatable-extension/dataTables.colReorder.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables/datatable-extension/dataTables.rowReorder.min.js')}}"></script>

    <script>
        $(document).ready(function () {
            let table = $('#res-config').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: '{{ route('leads.data') }}',
                    data: function (d) {
                        d.stage = $('select[name=status_filter]').val();
                        d.user = $('select[name=user_filter]').val();
                        d.department = $('select[name=department_filter]').val();
                        d.country = $('select[name=country_filter]').val();
                        d.team = $('select[name=team_filter]').val();
                    }
                },
                columns: [
                    {data: 'lead_name', name: 'lead_name'},
                    {data: 'stage', name: 'stage', 'searchable': false, 'orderable': false},
                    {data: 'user', name: 'user', 'searchable': false, 'orderable': false},
                    {data: 'sells', name: 'sells', 'searchable': false, 'orderable': false},
                    {data: 'stat', name: 'stat', 'searchable': false, 'orderable': false},
                    {data: 'action', name: 'action', 'searchable': false, 'orderable': false},
                ],
                order: [],
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]]
            });
            // Assigne user
            $('#refresh').click(function () {
                $('select[name=status_filter]').val('');
                $('select[name=user_filter]').val('');
                $('select[name=department_filter]').val('');
                $('select[name=team_filter]').val('');
                table.DataTable().destroy();
            });
            // Search form
            $('#search-form').on('submit', function (e) {
                e.preventDefault();
                table.draw();
            });
            @can('lead-delete')
            table.on('click', '.delete', function () {
                $tr = $(this).closest('tr');
                if ($($tr).hasClass('child')) {
                    $tr = $tr.prev('.parent');
                }
                let data = table.row($tr).data();
                $('#deleteForm').attr('action', 'leads/' + data[0]);
                $('#deleteModal').modal('show');
            })
            @endcan
        });
    </script>

@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item">{{ __('Deals') }}</li>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-2">
                <div class="card p-1">
                    <div class="card-header b-l-primary p-2">
                        <h6 class="m-0">{{ __('Filter deal by:') }}</h6>
                    </div>
                    <form id="search-form">
                        <div class="card-body p-2">
                            <div class="form-group mb-2">
                                <select class="custom-select custom-select-sm" id="status_filter"
                                        name="status_filter">
                                    <option value="">{{ __('Select stage') }}</option>
                                    <option value="1">{{ __('In contact') }}</option>
                                    <option value="2">{{ __('Appointment Set') }}</option>
                                    <option value="3">{{ __('Follow up') }}</option>
                                    <option value="4">{{ __('Reservation') }}</option>
                                    <option value="5">{{ __('contract signed') }}</option>
                                    <option value="6">{{ __('Down payment') }}</option>
                                    <option value="7">{{ __('Developer invoice') }}</option>
                                    <option value="8">{{ __('Won Deal') }}</option>
                                    <option value="9">{{ __('Lost') }}</option>
                                </select>
                            </div>
                            @if(auth()->user()->hasRole('Admin') || auth()->user()->hasPermissionTo('team-manager'))
                                <div class="form-group mb-2">
                                    <select name="user_filter" id="user_filter"
                                            class="custom-select custom-select-sm">
                                        <option value="">{{ __('Assigned') }}</option>
                                        @foreach($users as $row)
                                            <option value="{{ $row->id }}">{{ $row->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if(isset($departments))
                                    <div class="form-group mb-2">
                                        <select name="department_filter" id="department_filter"
                                                class="custom-select custom-select-sm">
                                            <option value="">{{ __('Department') }}</option>
                                            @foreach($departments as $row)
                                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                @if(isset($teams))
                                    <div class="form-group mb-2">
                                        <select name="team_filter" id="team_filter"
                                                class="custom-select custom-select-sm">
                                            <option value="">{{ __('Team') }}</option>
                                            @foreach($teams as $row)
                                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            @endif
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
                @include('partials.flash-message')
                <div class="card">
                    <div class="card-header p-3 b-t-primary d-flex justify-content-between">
                        @can('lead-create')
                            <div>
                                <a href="{{ route('leads.create') }}"
                                   class="btn btn-sm btn-outline-primary">{{__('New deal')}}<i
                                        class="icon-plus"></i></a>
                            </div>
                        @endcan
                        <div class="col-md-10">
                            <form action="{{ route('generate.deal.report') }}" method="post" role="form">
                                @csrf
                                <div class="row">
                                    <div class="col-3 pr-1 pl-0">
                                        <input type="date" name="from_date" id="from_date"
                                               class="form-control form-control-sm"
                                               placeholder="From Date" value="{{ now()->format('Y-m-d') }}" required>
                                    </div>
                                    <div class="col-3 pr-1 pl-1">
                                        <input type="date" name="to_date" id="to_date"
                                               class="form-control form-control-sm"
                                               placeholder="To Date" value="{{ now()->format('Y-m-d') }}" required>
                                    </div>
                                    <div class="col pr-1 pl-1">
                                        <button type="submit"
                                                class="btn btn-primary btn-sm">{{ __('Generate') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="order-history dt-ext table-responsive">
                            <table id="res-config"
                                   class="table task-list-table table-striped table-bordered nowrap"
                                   style="width:100%">
                                <thead>
                                <tr>
                                    <th data-priority="1">{{ __('Client') }}</th>
                                    <th data-priority="4">{{ __('Stage') }}</th>
                                    <th>{{ __('Assigned') }}</th>
                                    <th>{{ __('Sell representative') }}</th>
                                    <th>{{ __('Stat') }}</th>
                                    <th data-priority="2">{{ __('Action') }}</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Zero config.table end -->
        </div>
    </div>
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Delete deal') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="/leads" method="POST" id="deleteForm">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body p-b-0">
                        <p>{{ __('Are sur you want to delete this deal?') }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{ __('Delete') }} <i class="ti-trash-alt"></i>
                        </button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

