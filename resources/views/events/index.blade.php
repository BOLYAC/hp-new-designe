@extends('layouts.vertical.master')
@section('title', '| Events')

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
            // Start Edit record
            let table = $('#res-config').DataTable({
                order: []
            });
            table.on('click', '.delete', function () {
                $tr = $(this).closest('tr');
                if ($($tr).hasClass('child')) {
                    $tr = $tr.prev('.parent');
                }
                var data = table.row($tr).data();
                console.log(data)
                $('#deleteForm').attr('action', 'events/' + data[0]);
                $('#deleteModal').modal('show');
            })
        });
    </script>

@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item">{{ __('Events') }}</li>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                @include('partials.flash-message')
                <div class="card p-1">
                    <div class="card-header card-no-border p-2  b-t-primary b-b-primary">
                        @can('event-create')
                            <a href="{{ route('events.create') }}" class="btn btn-sm btn-outline-primary">
                                {{ __('New Event') }} <i class="icon-plus"></i></a>
                        @endcan
                        @can('can-generate-report')
                            <a href="{{ route('view.report', 'today') }}" class="btn btn-sm btn-outline-success">
                                {{ __('Generate report for today') }}
                                <i class="icon-calendar"></i>
                            </a>
                            <a href="{{ route('view.report', 'tomorrow') }}" class="btn btn-sm btn-outline-success">
                                {{ __('Generate report for the next day') }}
                                <i class="icon-calendar"></i>
                            </a>
                        @endcan
                        <a href="{{ route('calender.index') }}" class="btn btn-sm btn-outline-success pull-right">
                            {{ __('Calendar') }}
                            <i class="icon-calendar"></i></a>
                        <div class="col-12 mt-2">
                            <form action="{{ route('generate.custom.report') }}" method="post" role="form">
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
                                    <th width="5%">#</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Client') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Place') }}</th>
                                    <th>{{ __('Assigned') }}</th>
                                    <th width="5%"></th>
                                </tr>
                                </thead>
                                <tbody class="task-page">
                                @foreach($events as $key => $event)
                                    <tr>
                                        <td>
                                            {{ $event->id }}
                                        </td>
                                        <td>
                                            <a href="{{ route('events.show', $event) }}">{{ $event->name ?? '' }}</a>
                                        </td>
                                        <td>
                                            {{ $event->lead_name ?? $event->client->full_name }}
                                        </td>

                                        <td>
                                            {{ Carbon\Carbon::parse($event->event_date)->format('Y-m-d') }}
                                        </td>

                                        <td>
                                            {{ $event->place ?? '' }}
                                        </td>
                                        <td><span class="badge badge-success">
                                                {{ $event->user->name ?? '' }}</span>
                                        </td>
                                        <td class="action-icon">
                                            <a class="dropdown-toggle addon-btn" data-toggle="dropdown"
                                               aria-expanded="true">
                                                <i class="icofont icofont-ui-settings"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="{{ route('events.show', $event) }}"
                                                   class="dropdown-item m-r-15 text-muted f-18"><i
                                                        class="icofont icofont-eye-alt"></i>{{ __('Show') }}</a>
                                                @can('event-edit')
                                                    <a href="{{ route('events.edit', $event) }}"
                                                       class="dropdown-item m-r-15 text-muted f-18"><i
                                                            class="icofont icofont-ui-edit"></i>{{ __('Edit') }}</a>
                                                @endcan
                                                @can('event-replicate')
                                                    <a href="{{ route('replicate.event', $event) }}"
                                                       class="dropdown-item m-r-15 text-muted f-18"><i
                                                            class="fa fa-clone"></i>{{ __('Duplicate') }}</a>
                                                @endcan
                                                @can('event-delete')
                                                    <a class="dropdown-item m-r-15 text-muted f-18 delete"><i
                                                            class="icofont icofont-trash"></i>{{ __('Delete') }}</a>
                                                @endcan
                                            </div>
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
    </div>

    <!-- Delete modal start -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Delete appointment') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="/events" method="POST" id="deleteForm">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body p-b-0">
                        <p>{{ __('Are sur you want to delete this appointment?') }}</p>
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
    <!-- Delete modal end -->

@endsection
