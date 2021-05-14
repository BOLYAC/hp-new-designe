@extends('layouts.vertical.master')
@section('title', 'Agencies list')

@section('style_before')
    <!-- Notification.css -->
    <link rel="stylesheet" href="{{ asset('assets/css/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/datatable-extension.css') }}">
@endsection

@section('style')

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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.1.2/handlebars.min.js"></script>

    <script id="details-template" type="text/x-handlebars-template">
        @verbatim
        <div class="badge badge-light-primary">Leads list</div>
        <div class="dt-responsive table-responsive">
            <table class="table table-striped table-bordered nowrap display compact" id="client-{{id}}"
                   style="width: 100%">
                <thead>
                <tr>
                    <th>N°</th>
                    <th>New</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Assigned</th>
                    <th>Source</th>
                </tr>
                </thead>
            </table>
        </div>
        @endverbatim
    </script>
    <script>
        var template = Handlebars.compile($("#details-template").html());
        var table = $('#res-config').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('agencies.index') }}',
            },
            "drawCallback": function (settings) {
                var api = this.api();
                // Output the data for the visible rows to the browser's console
            },
            columns: [
                {
                    "className": 'details-control',
                    "orderable": false,
                    "searchable": false,
                    "data": null,
                    "defaultContent": ''
                },
                {data: 'id', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'action', name: 'action'},
            ],
            order: [[1, 'asc']]
        });
        // Add event listener for opening and closing details
        $('#res-config tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = table.row(tr);
            var tableId = 'client-' + row.data().id;
            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            } else {
                // Open this row
                row.child(template(row.data())).show();
                initTable(tableId, row.data());
                console.log(row.data());
                tr.addClass('shown');
                tr.next().find('td').addClass('no-padding bg-gray');
            }
        });

        function initTable(tableId, data) {
            $('#' + tableId).DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                paging: false,
                ordering: false,
                info: false,
                searching: false,
                ajax:
                    {
                        url: data.details_url,
                    },
                columns: [
                    {data: 'public_id', name: 'public_id'},
                    {data: 'type', name: 'type'},
                    {data: 'full_name', name: 'full_name'},
                    {data: 'status', name: 'status'},
                    {data: 'assigned', name: 'assigned'},
                    {data: 'source_id', name: 'source_id'},
                ]
            })
        }

        table.on('click', '.delete', function () {
            $tr = $(this).closest('tr');
            if ($($tr).hasClass('child')) {
                $tr = $tr.prev('.parent');
            }
            var data = table.row($tr).data();
            $('#deleteForm').attr('action', 'agencies/' + data[0]);
            $('#deleteModal').modal('show');
        })
    </script>

@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item">{{ __('Agencies') }}</li>
@endsection

@section('breadcrumb-title')

@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <!-- Zero config.table start -->
                @include('partials.flash-message')
                <div class="card">
                    <div class="card-header b-b-primary b-t-primary">
                        <a href="{{ route('agencies.create') }}"
                           class="btn btn-sm btn-outline-primary">{{ __('New Agency') }} <i class="icon-plus"></i></a>
                    </div>
                    <div class="card-body">
                        <div class="order-history dt-ext table-responsive">
                            <table id="res-config"
                                   class="table task-list-table table-striped table-bordered nowrap"
                                   style="width:100%">
                                <thead>
                                <tr>
                                    <th width="5%"></th>
                                    <th width="5%">ID</th>
                                    <th>{{ __('Name') }}</th>
                                    <th width="10%"></th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Zero config.table end -->
    </div>

    <!-- Delete modal start -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Delete agency')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="/agencies" method="POST" id="deleteForm">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body p-b-0">
                        <p>{{ __('Are sur you want to delete this agency?') }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{ __('Delete') }} <i class="icon-trash"></i>
                        </button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Delete modal end -->

@endsection
