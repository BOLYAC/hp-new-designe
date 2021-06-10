w@extends('layouts.vertical.master')
@section('style')

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mx-auto">
                <!-- Zero config.table start -->
                @include('partials.flash-message')
                <div class="card">
                    <div class="card-header">
                        @can('role-create')
                            <a href="{{ route('roles.create') }}" class="btn btn-sm btn-outline-primary">New
                                role <i class="ti-plus"></i></a>
                        @endcan
                    </div>
                    <div class="card-block">
                        <div class="table-responsive">
                            <div class="dt-responsive table-responsive">
                                <table id="res-config" class="table">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">Model</th>
                                        <th scope="col">Action</th>
                                        <th scope="col">User</th>
                                        <th scope="col">Time</th>
                                        <th scope="col">Old Values</th>
                                        <th scope="col">New Values</th>
                                    </tr>
                                    </thead>
                                    <tbody id="audits">
                                    @foreach($audits as $audit)
                                        <tr>
                                            <td>{{ $audit->auditable_type }} (id: {{ $audit->auditable_id }})</td>
                                            <td>{{ $audit->event }}</td>
                                            <td>{{ $audit->user->name }}</td>
                                            <td>{{ $audit->created_at }}</td>
                                            <td>
                                                <table class="table">
                                                    @foreach($audit->old_values as $attribute => $value)
                                                        <tr>
                                                            <td><b>{{ $attribute }}</b></td>
                                                            <td>{{ $value }}</td>
                                                        </tr>
                                                    @endforeach
                                                </table>
                                            </td>
                                            <td>
                                                <table class="table">
                                                    @foreach($audit->new_values as $attribute => $value)
                                                        <tr>
                                                            <td><b>{{ $attribute }}</b></td>
                                                            <td>{{ $value }}</td>
                                                        </tr>
                                                    @endforeach
                                                </table>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Zero config.table end -->
        </div>
    </div>
@endsection

@section('script')

    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function () {
            // Start Edit record
            let table = $('#res-config').DataTable();
            table.on('click', '.delete', function () {
                $tr = $(this).closest('tr');
                if ($($tr).hasClass('child')) {
                    $tr = $tr.prev('.parent');
                }
                var data = table.row($tr).data();
                console.log(data)
                $('#deleteForm').attr('action', 'roles/' + data[0]);
                $('#deleteModal').modal('show');
            })
        });
    </script>

@endsection
