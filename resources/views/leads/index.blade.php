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
                ajax: {
                    url: '{{ route('leads.data') }}',
                    data: function (d) {
                        d.stage = $('select[name=status_filter]').val();
                        d.user = $('select[name=user_filter]').val();
                    }
                },
                columns: [
                    {data: 'full_name', name: 'full_name'},
                    {data: 'stage', name: 'stage'},
                    {data: 'user', name: 'user'},
                    {data: 'sells', name: 'sells'},
                    {data: 'stat', name: 'stat'},
                    {data: 'action', name: 'action'},
                ],
                order: [],
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]]
            });
            // Assigne user
            $('#refresh').click(function () {
                $('select[name=status_filter]').val('');
                $('select[name=user_filter]').val('');
                table.DataTable().destroy();
            });
            // Search form
            $('#search-form').on('submit', function (e) {
                e.preventDefault();
                table.draw();
            });
            table.on('click', '.delete', function () {
                $tr = $(this).closest('tr');
                if ($($tr).hasClass('child')) {
                    $tr = $tr.prev('.parent');
                }
                let data = table.row($tr).data();
                $('#deleteForm').attr('action', 'leads/' + data[0]);
                $('#deleteModal').modal('show');
            })
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
                                <select class="form-control form-control-sm digits" id="status_filter"
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
                    <div class="card-header p-3 b-t-primary">
                        @can('lead-create')
                            <a href="{{ route('leads.create') }}"
                               class="btn btn-sm btn-outline-primary">{{__('New deal')}}<i class="icon-plus"></i></a>
                        @endcan
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
                                    <th></th>
                                    <th data-priority="2">{{ __('Action') }}</th>
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
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete deal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="/leads" method="POST" id="deleteForm">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body p-b-0">
                        <p>Are sur you want to delete this deal?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Delete <i class="ti-trash-alt"></i></button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

