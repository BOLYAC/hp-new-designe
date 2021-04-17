@extends('layouts.light.master')
@section('title', 'Sample Page')

@section('css')

@endsection

@section('style')
    <!-- Notification.css -->
    <link rel="stylesheet" href="{{ asset('assets/css/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/datatable-extension.css') }}">
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Dashboard</li>
@endsection

@section('breadcrumb-title')

@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-9 xl-100 box-col-12">
                <div class="row">
                    <div class="col-12">
                        <div class="project-overview">
                            <div class="row">
                                <div class="col-xl-2 col-sm-4 col-6">
                                    <h2 class="f-w-600 counter font-primary">18</h2>
                                    <p class="mb-0">Total leads</p>
                                </div>
                                <div class="col-xl-2 col-sm-4 col-6">
                                    <h2 class="f-w-600 counter font-secondary">143</h2>
                                    <p class="mb-0">Past tasks</p>
                                </div>
                                <div class="col-xl-2 col-sm-4 col-6">
                                    <h2 class="f-w-600 counter font-success">574</h2>
                                    <p class="mb-0">Completed tasks</p>
                                </div>
                                <div class="col-xl-2 col-sm-4 col-6">
                                    <h2 class="f-w-600 counter font-info">15</h2>
                                    <p class="mb-0">Today tasks</p>
                                </div>
                                <div class="col-xl-2 col-sm-4 col-6">
                                    <h2 class="f-w-600 counter font-warning">20</h2>
                                    <p class="mb-0">Today events</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-xl-6 xl-100">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs border-tab" id="top-tab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" id="top-lead-tab" data-toggle="tab"
                                                href="#top-lead" role="tab" aria-controls="top-lead"
                                                aria-selected="true">New lead</a>
                        </li>
                        <li class="nav-item"><a class="nav-link" id="today-top-tab" data-toggle="tab"
                                                href="#top-today" role="tab" aria-controls="top-today"
                                                aria-selected="false">Today tasks</a></li>
                        <li class="nav-item"><a class="nav-link" id="tomorrow-top-tab" data-toggle="tab"
                                                href="#top-tomorrow" role="tab" aria-controls="top-tomorrow"
                                                aria-selected="false">Tomorrow tasks</a></li>
                        <li class="nav-item"><a class="nav-link" id="pending-top-tab" data-toggle="tab"
                                                href="#top-pending" role="tab" aria-controls="top-pending"
                                                aria-selected="false">Pending tasks</a>
                        </li>
                        <li class="nav-item"><a class="nav-link" id="completed-top-tab" data-toggle="tab"
                                                href="#top-completed" role="tab" aria-controls="top-completed"
                                                aria-selected="false">Completed Tasks</a></li>
                    </ul>
                    <div class="tab-content" id="top-tabContent">
                        <div class="tab-pane fade show active" id="top-lead" role="tabpanel"
                             aria-labelledby="top-lead-tab">
                            <div class="dt-ext table-responsive">
                                <table class="display" id="lead-table">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Position</th>
                                        <th>Office</th>
                                        <th>Age</th>
                                        <th>Start date</th>
                                        <th>Salary</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="top-today" role="tabpanel" aria-labelledby="profile-top-tab">
                            <div class="dt-ext table-responsive">
                                <table class="display" id="today-table">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Position</th>
                                        <th>Office</th>
                                        <th>Age</th>
                                        <th>Start date</th>
                                        <th>Salary</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="top-tomorrow" role="tabpanel" aria-labelledby="tomorrow-top-tab">
                            <div class="dt-ext table-responsive">
                                <table class="display" id="tomorrow-table">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Position</th>
                                        <th>Office</th>
                                        <th>Age</th>
                                        <th>Start date</th>
                                        <th>Salary</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="top-pending" role="tabpanel" aria-labelledby="tomorrow-top-tab">
                            <div class="dt-ext table-responsive">
                                <table class="display" id="pending-table">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Position</th>
                                        <th>Office</th>
                                        <th>Age</th>
                                        <th>Start date</th>
                                        <th>Salary</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="top-completed" role="tabpanel" aria-labelledby="completed-top-tab">
                            <div class="dt-ext table-responsive">
                                <table class="display" id="completed-table">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Position</th>
                                        <th>Office</th>
                                        <th>Age</th>
                                        <th>Start date</th>
                                        <th>Salary</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

    <script src="{{asset('assets/js/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables/datatable-extension/responsive.bootstrap4.min.js')}}"></script>
    <script>
        $('#lead-table').DataTable();
        $('#today-table').DataTable();
        $('#tomorrow-table').DataTable();
        $('#pending-table').DataTable();
        $('#completed-table').DataTable();
    </script>

@endsection
