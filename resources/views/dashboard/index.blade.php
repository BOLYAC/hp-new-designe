@extends('layouts.vertical.master')
@section('title', 'Dashboard')

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

    <script>
        $(document).ready(function () {
            $('#lead-table').DataTable({
                destroy: true,
                stateSave: false,
                order: [[0, 'asc']],
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: '{!! route('dashboard.data') !!}'
                },
                columns: [
                    {
                        data: 'created_at',
                        name: 'created_at',
                    },
                    {
                        data: 'full_name',
                        name: 'full_name',
                    },
                    {
                        data: 'country',
                        name: 'country'
                    },
                    {
                        data: 'nationality',
                        name: 'nationality'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'priority',
                        name: 'priority',
                        orderable: false,
                        searchable: false,
                    }
                ],
            });
            $('#agency-table').DataTable({
                destroy: true,
                stateSave: false,
                order: [[0, 'asc']],
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: '{!! route('dashboard.agencies.data') !!}'
                },
                columns: [
                    {
                        data: 'created_at',
                        name: 'created_at',
                    },
                    {
                        data: 'company_type',
                        name: 'company_type',
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                ],
            });
            $('#today-table').DataTable();
            $('#tomorrow-table').DataTable();
            $('#pending-table').DataTable();
            $('#completed-table').DataTable();
        });
    </script>

@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item">Dashboard</li>
@endsection

@section('breadcrumb-title')

@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12 xl-100 box-col-12">
                <div class="row">
                    <div class="col-12">
                        <div class="project-overview">
                            <div class="row">
                                <div class="col-xl-2 col-sm-4 col-6">
                                    <h2 class="f-w-600 counter font-primary">{{ $allClients }}</h2>
                                    <p class="mb-0">{{ __('Total leads') }}</p>
                                </div>
                                <div class="col-xl-2 col-sm-4 col-6">
                                    <h2 class="f-w-600 counter font-secondary">{{ $olderTask }}</h2>
                                    <p class="mb-0">{{ __('Past tasks') }}</p>
                                </div>
                                <div class="col-xl-2 col-sm-4 col-6">
                                    <h2 class="f-w-600 counter font-success">{{ $completedTasks->count() }}</h2>
                                    <p class="mb-0">{{ __('Completed tasks') }}</p>
                                </div>
                                <div class="col-xl-2 col-sm-4 col-6">
                                    <h2 class="f-w-600 counter font-info">{{ $todayTasks->count() }}</h2>
                                    <p class="mb-0">{{ __('Today tasks') }}</p>
                                </div>
                                <div class="col-xl-2 col-sm-4 col-6">
                                    <h2 class="f-w-600 counter font-warning">{{ $events }}</h2>
                                    <p class="mb-0"><a
                                            href="{{ route('events.index', 'today-event') }}">{{ __('Today Appointment(s)') }}</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-12 xl-100">
            <div class="card b-t-primary">
                <div class="card-body">
                    <ul class="nav nav-tabs border-tab" id="top-tab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" id="top-lead-tab" data-toggle="tab"
                                                href="#top-lead" role="tab" aria-controls="top-lead"
                                                aria-selected="true">{{ __('New lead') }}</a>
                        </li>
                        <li class="nav-item"><a class="nav-link" id="top-agency-tab" data-toggle="tab"
                                                href="#top-agency" role="tab" aria-controls="top-agency"
                                                aria-selected="true">{{ __('Agencies') }}</a>
                        </li>
                        <li class="nav-item"><a class="nav-link" id="today-top-tab" data-toggle="tab"
                                                href="#top-today" role="tab" aria-controls="top-today"
                                                aria-selected="false">{{ __('Today tasks') }}</a></li>
                        <li class="nav-item"><a class="nav-link" id="tomorrow-top-tab" data-toggle="tab"
                                                href="#top-tomorrow" role="tab" aria-controls="top-tomorrow"
                                                aria-selected="false">{{ __('Tomorrow tasks') }}</a></li>
                        <li class="nav-item"><a class="nav-link" id="pending-top-tab" data-toggle="tab"
                                                href="#top-pending" role="tab" aria-controls="top-pending"
                                                aria-selected="false">{{ __('Pending tasks') }}</a>
                        </li>
                        <li class="nav-item"><a class="nav-link" id="completed-top-tab" data-toggle="tab"
                                                href="#top-completed" role="tab" aria-controls="top-completed"
                                                aria-selected="false">{{ __('Completed Tasks') }}</a></li>
                    </ul>
                    <div class="tab-content" id="top-tabContent">
                        <div class="tab-pane fade show active" id="top-lead" role="tabpanel"
                             aria-labelledby="top-lead-tab">
                            <div class="order-history dt-ext table-responsive">
                                <table class="display" id="lead-table">
                                    <thead>
                                    <tr>
                                        <th data-priority="1">{{ __('Created') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Country') }}</th>
                                        <th>{{ __('Nationality') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Priority') }}</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade show" id="top-agency" role="tabpanel"
                             aria-labelledby="top-agency-tab">
                            <div class="order-history dt-ext table-responsive">
                                <table class="display" id="agency-table">
                                    <thead>
                                    <tr>
                                        <th data-priority="1">{{ __('Create at') }}</th>
                                        <th>{{ __('Agency type') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Phone') }}</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="top-today" role="tabpanel" aria-labelledby="profile-top-tab">
                            <div class="dt-ext table-responsive">
                                <table class="display" id="today-table">
                                    <thead>
                                    <tr>
                                        <th>{{ __('Title') }}</th>
                                        <th>{{ __('Client name') }}</th>
                                        <th>{{ __('Country') }}</th>
                                        <th>{{ __('Nationality') }}</th>
                                        <th>{{ __('Date') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($todayTasks as $todayTask)
                                        <tr class="unread">
                                            <td>
                                                @if($todayTask->source_type === 'App\Agency')
                                                    <a href="{{ route('agencies.edit', ['agency' => $todayTask->agency->id]) }}"
                                                       class="email-name">
                                                        {{ $todayTask->title ?? '' }}
                                                    </a>
                                                @else
                                                    <a href="{{ route('clients.edit', ['client' => $todayTask->client->id]) }}"
                                                       class="email-name">
                                                        {{ $todayTask->title ?? '' }}
                                                    </a>
                                                @endif
                                            </td>
                                            <td>
                                                {{ optional($todayTask->client)->full_name }}
                                                {{ optional($todayTask->agency)->title }}
                                            </td>
                                            <td>
                                                @if(is_null($todayTask->client->country))
                                                    <div class="col-form-label">
                                                        {{ $todayTask->client->getRawOriginal('country') ?? '' }}</div>
                                                @else
                                                    @php $countries = collect($todayTask->client->country)->toArray() @endphp
                                                    @foreach( $countries as $name)
                                                        <span class="badge badge-inverse">{{ $name }}</span>
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td>
                                                @if(is_null($todayTask->client->nationality))
                                                    {{ $todayTask->client->getRawOriginal('nationality') ?? '' }}
                                                @else
                                                    @php $countries = collect($todayTask->client->nationality)->toArray() @endphp
                                                    @foreach( $countries as $name)
                                                        <span class="badge badge-inverse">{{ $name }}</span>
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td class="email-time">
                                                {{ Carbon\Carbon::parse($todayTask->date)->format('Y-m-d H:i') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="top-tomorrow" role="tabpanel" aria-labelledby="tomorrow-top-tab">
                            <div class="dt-ext table-responsive">
                                <table class="display" id="tomorrow-table">
                                    <thead>
                                    <tr>
                                        <th>{{ __('Title') }}</th>
                                        <th>{{ __('Client name') }}</th>
                                        <th>{{ __('Country') }}</th>
                                        <th>{{ __('Nationality') }}</th>
                                        <th>{{ __('Date') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($tomorrowTasks as $tomorrowTask)
                                        <tr class="unread">
                                            <td>
                                                @if($tomorrowTask->source_type === 'App\Agency')
                                                    <a href="{{ route('agencies.edit', ['agency' => $tomorrowTask->agency->id]) }}"
                                                       class="email-name">
                                                        {{ $tomorrowTask->title ?? '' }}
                                                    </a>
                                                @else
                                                    <a href="{{ route('clients.edit', ['client' => $tomorrowTask->client->id]) }}"
                                                       class="email-name">
                                                        {{ $tomorrowTask->title ?? '' }}
                                                    </a>
                                                @endif
                                            </td>
                                            <td>
                                                {{ optional($tomorrowTask->client)->full_name }}
                                                {{ optional($tomorrowTask->agency)->title }}
                                            </td>
                                            <td>
                                                @if(is_null($tomorrowTask->client->country))
                                                    {{ $tomorrowTask->client->getRawOriginal('country') ?? '' }}
                                                @else
                                                    @php $countries = collect($tomorrowTask->client->country)->toArray() @endphp
                                                    @foreach( $countries as $name)
                                                        <span class="badge badge-inverse">{{ $name }}</span>
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td>
                                                @if(is_null($tomorrowTask->client->nationality))
                                                    {{ $tomorrowTask->client->getRawOriginal('nationality') ?? '' }}
                                                @else
                                                    @php $nat = collect($tomorrowTask->client->nationality)->toArray() @endphp
                                                    @foreach( $nat as $name)
                                                        <span class="badge badge-inverse">{{ $name }}</span>
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td class="email-time">
                                                {{ Carbon\Carbon::parse($tomorrowTask->date)->format('Y-m-d H:i') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="top-pending" role="tabpanel" aria-labelledby="tomorrow-top-tab">
                            <div class="dt-ext table-responsive">
                                <table class="display" id="pending-table">
                                    <thead>
                                    <tr>
                                        <th>{{ __('Title') }}</th>
                                        <th>{{ __('Client name') }}</th>
                                        <th>{{ __('Country') }}</th>
                                        <th>{{ __('Nationality') }}</th>
                                        <th>{{ __('Date') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($pendingTasks as $pendingTask)
                                        <tr>
                                            <td>
                                                @if($pendingTask->source_type == 'App\Agency')
                                                    <a href="{{ route('agencies.edit', ['agency' => $pendingTask->agency->id]) }}"
                                                       class="email-name">
                                                        {{ $pendingTask->title ?? '' }}
                                                    </a>
                                                @else
                                                    @if(!is_null($pendingTask->client_id))
                                                        <a href="{{ route('clients.edit', ['client' => $pendingTask->client_id]) }}"
                                                           class="email-name">
                                                            {{ $pendingTask->title ?? '' }}
                                                        </a>
                                                    @endif
                                                @endif
                                                {{ $pendingTask->soure_type }}
                                            </td>
                                            <td>
                                                {{ optional($pendingTask->client)->full_name }}
                                                {{ optional($pendingTask->agency)->title }}
                                            </td>
                                            <td>
                                                @if(is_null($pendingTask->client->country))
                                                    {{ $pendingTask->client->getRawOriginal('country') ?? '' }}
                                                @else
                                                    @php $countries = collect($pendingTask->client->country)->toArray() @endphp
                                                    @foreach( $countries as $name)
                                                        <span class="badge badge-light-primary">{{ $name }}</span>
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td>
                                                @if(is_null($pendingTask->client->nationality))
                                                    {{ $pendingTask->client->getRawOriginal('nationality') ?? '' }}
                                                @else
                                                    @php $nat = collect($pendingTask->client->nationality)->toArray() @endphp
                                                    @foreach( $nat as $name)
                                                        <span class="badge badge-light-primary">{{ $name }}</span>
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td class="email-time">
                                                {{ Carbon\Carbon::parse($pendingTask->date)->format('Y-m-d') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="top-completed" role="tabpanel"
                             aria-labelledby="completed-top-tab">
                            <div class="users-total table-responsive">
                                <table class="table" id="completed-table">
                                    <thead>
                                    <tr>
                                        <th width="5%">{{ __('Stat') }}</th>
                                        <th>{{ __('Title') }}</th>
                                        <th>{{ __('Client') }}</th>
                                        <th>{{ __('Country') }}</th>
                                        <th>{{ __('Nationality') }}</th>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Completed at') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($completedTasks as $completedTask)
                                        <tr>
                                            <td>
                                                <div class="round-product"><i
                                                        class="icofont icofont-check"></i></div>
                                            </td>
                                            <td>
                                                @if($completedTask->source_type === 'App\Agency')
                                                    <a href="{{ route('agencies.edit', ['agency' => $completedTask->agency->id]) }}"
                                                       class="email-name">
                                                        {{ $completedTask->title ?? '' }}
                                                    </a>
                                                @else
                                                    <a href="{{ route('clients.edit', ['client' => $completedTask->client->id]) }}"
                                                       class="email-name">
                                                        {{ $completedTask->title ?? '' }}
                                                    </a>
                                                @endif
                                            </td>
                                            <td>
                                                {{ optional($completedTask->client)->full_name }}
                                                {{ optional($completedTask->agency)->title }}
                                            </td>
                                            <td>
                                                @if(is_null($completedTask->client->country))
                                                    {{ $completedTask->client->getRawOriginal('country') ?? '' }}
                                                @else
                                                    @php $countries = collect($completedTask->client->country)->toArray() @endphp
                                                    @foreach( $countries as $name)
                                                        <span class="badge badge-light-primary">{{ $name }}</span>
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td>
                                                @if(is_null($completedTask->client->nationality))
                                                    {{ $completedTask->client->getRawOriginal('nationality') ?? '' }}
                                                @else
                                                    @php $nat = collect($completedTask->client->nationality)->toArray() @endphp
                                                    @foreach( $nat as $name)
                                                        <span class="badge badge-light-primary">{{ $name }}</span>
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td>
                                                {{ Carbon\Carbon::parse($completedTask->date)->format('Y-m-d') }}
                                            </td>
                                            <td>
                                                {{ Carbon\Carbon::parse($completedTask->updated_at)->format('Y-m-d') }}
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
    </div>
@endsection
