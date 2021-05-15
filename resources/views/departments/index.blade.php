@extends('layouts.vertical.master')
@section('title', '| Departments')

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

        $(function () {
            let table = $('#departments-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: '{!! route('departments.indexDataTable') !!}',
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'description', name: 'description', orderable: false, searchable: false},
                    @can('department-delete')
                        {
                            data: 'delete',
                            name: 'delete',
                            orderable: false,
                            searchable: false,
                            class: 'fit-action-delete-th table-actions'
                        },
                    @endcan
                ]
            });

            table.on('click', '.edit', function () {
                $tr = $(this).closest('tr');
                if ($($tr).hasClass('child')) {
                    $tr = $tr.prev('.parent');
                }
                var data = table.row($tr).data();
                console.log(data);
                $('#name').val(data[1]);
                $('#description').val(data[2]);
                $('#editForm').attr('action', 'departments/' + data[0]);
                $('#editModal').modal('show');
            })
            table.on('click', '.delete', function () {
                $tr = $(this).closest('tr');
                if ($($tr).hasClass('child')) {
                    $tr = $tr.prev('.parent');
                }
                var data = table.row($tr).data();
                $('#deleteForm').attr('action', 'departments/' + data[0]);
                $('#deleteModal').modal('show');
            })

        });
    </script>

@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item">{{ __('Departments list') }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <!-- Zero config.table start -->
                @include('partials.flash-message')
                <div class="card">
                    <div class="card-header b-t-primary b-b-primary">
                        <button class="btn btn-outline-success btn-sm" data-toggle="modal"
                                data-target="#sign-in-modal">{{ __('New department') }} <i class="icon-plus"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="departments-table"
                                   class="display table-hover"
                                   style="width:100%">
                                <thead>
                                <tr>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Description') }}</th>
                                    <th></th>
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

    <!-- Create modal start -->
    <div class="modal fade" id="sign-in-modal" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('New source') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('departments.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-b-0">
                        <div class="form-group">
                            <label for="title">
                                {{ __('Title') }}
                            </label>
                            <input class="form-control" type="text" name="name"
                                   placeholder="Source title">
                        </div>

                        <div class="form-group">
                            <textarea class="form-control" name="description" cols="10" rows="3"></textarea>
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
    <!-- Create modal end -->
    <!-- Edit modal start -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Edit department') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="/departments" method="POST" id="editForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-b-0">
                        <div class="form-group">
                            <label for="title">
                                {{ __('Name') }}
                            </label>
                            <input class="form-control" type="text" name="name" id="name"
                                   placeholder="Department name">
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" name="description" id="description" cols="10"
                                      rows="3"></textarea>
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
    <!-- Edit modal end -->
    <!-- Edit modal start -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Delete department')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="/departments" method="POST" id="deleteForm">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body p-b-0">
                        <p>{{ __('Are sur you want to delete this department?') }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{ __('Delete') }} <i class="icon-trash"></i>
                        </button>
                        <button type="button" class="btn btn-warning" data-dismiss="modal">{{ __('Cancel') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Edit modal end -->

@endsection
