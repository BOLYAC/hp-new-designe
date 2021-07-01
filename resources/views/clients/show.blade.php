@extends('layouts.vertical.master')
@section('title', '| Show client')
@section('style_before')
    <!-- Plugins css start-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/summernote.css') }}">
    <!-- ToDo css -->
    <link rel="stylesheet" href="{{ asset('assets/css/todo.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css">
@endsection
@section('script')
    <!-- Plugins JS start-->
    <script src="{{ asset('assets/js/editor/summernote/summernote.js') }}"></script>
    <script src="{{ asset('assets/js/editor/summernote/summernote.custom.js') }}"></script>
    <!-- Plugins JS start-->
    <script src="{{ asset('assets/js/notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('assets/js/notify/notify-script.js') }}"></script>
@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">{{ __('Leads') }}</a></li>
    <li class="breadcrumb-item">{{ __('Show:') }} {{ $client->complete_name ?? $client->full_name ?? '' }}</li>
@endsection

@section('content')
    @php
        $requirements_request = [
                        ['id' => 1,'text' => 'Investments'],
                        ['id' => 2,'text' => 'Life style'],
                        ['id' => 3,'text' => 'Investments + Life style'],
                        ['id' => 4,'text' => 'Citizenship'],
                    ];
        $budget_request = [
                        ['id' => 1,'text' => 'Less then 50K'],
                        ['id' => 2,'text' => '50K-100K'],
                        ['id' => 3,'text' => '100K-150K'],
                        ['id' => 4,'text' => '150K200K'],
                        ['id' => 5,'text' => '200K-300K'],
                        ['id' => 6,'text' => '300K-400k'],
                        ['id' => 7,'text' => '400k-500K'],
                        ['id' => 8,'text' => '500K-600k'],
                        ['id' => 9,'text' => '600K-1M'],
                        ['id' => 10,'text' => '1M-2M'],
                        ['id' => 11,'text' => 'More then 2M'],
                    ];
        $rooms_request = [
                        ['id' => 1,'text' => '0 + 1'],
                        ['id' => 2,'text' => '1 + 1'],
                        ['id' => 3,'text' => '2 + 1'],
                        ['id' => 4,'text' => '3 + 1'],
                        ['id' => 5,'text' => '4 + 1'],
                        ['id' => 6,'text' => '5 + 1'],
                        ['id' => 7,'text' => '6 + 1'],
                    ];
    @endphp
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9 col-lg-10">
                <!-- Zero config.table start -->
                <div class="card b-t-primary">
                    <div class="card-header b-t-primary b-b-primary p-2 d-flex justify-content-between">
                        <h5 class="mr-auto mt-2">{{ __('Lead') }}
                            : {{ $client->complete_name ?? $client->full_name ?? '' }}</h5>
                        @can('client-edit')
                            <a class="btn btn-sm btn-warning mr-2" href="{{ route('clients.index') }}"><i
                                    class="icon-arrow-left"></i> {{ __('Back') }}</a>
                            <a class="btn btn-sm btn-primary"
                               href="{{ route('clients.edit', $client) }}">{{ __('Edit') }}</a>

                        @endcan
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <table class="table m-0">
                                    <tbody>
                                    <tr>
                                        <th scope="row">Id</th>
                                        <td>{{ $client->public_id ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">{{ __('Full Name') }}</th>
                                        <td>{{ $client->complete_name ?? $client->full_name ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">{{ __('Phone(s)') }}</th>
                                        <td>
                                            {{ $client->client_number }}
                                            <a href="javascript:void(0)"
                                               class="btn btn-xs btn-outline-primary float-right theme-setting ph1"><i
                                                    class="fa fa-phone"></i></a>
                                            <a href="https://wa.me/{{$client->client_number}}" target="_blank"
                                               class="btn btn-xs btn-outline-success float-right mr-2"><i
                                                    class="fa fa-whatsapp"></i></a>
                                            <br>
                                            {{ $client->client_number_2 }}
                                            <a href="javascript:void(0)"
                                               class="btn btn-xs btn-outline-primary float-right theme-setting ph1"><i
                                                    class="fa fa-phone"></i></a>
                                            <a href="https://wa.me/{{$client->client_number_2}}" target="_blank"
                                               class="btn btn-xs btn-outline-success float-right mr-2"><i
                                                    class="fa fa-whatsapp"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">{{ __('Email(s)') }}</th>
                                        <td>
                                            <a href="{{ route('clients.compose.email', ['client' => $client]) }}"
                                               class="btn btn-xs btn-outline-primary"><i
                                                    class="icon-email"></i> {{ __('Send email') }}
                                            </a>
                                    </tr>
                                    <tr>
                                        <th scope="row">{{ __('Country') }}</th>
                                        <td>
                                            @php
                                                if (is_null($client->country)){
                                                    echo $client->getRawOriginal('country') ?? '';
                                                } else  {
                                                    $cou = '';
                                                    $countries = collect($client->country)->toArray();
                                                foreach( $countries as $name) {
                                                    $cou .=  '<span class="badge badge-dark">' .  $name . '</span>';
                                                }
                                                    echo $cou;
                                                }
                                            @endphp
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">{{ __('Nationality') }}</th>
                                        <td>
                                            @php
                                                if (is_null($client->nationality)){
                                                echo $client->getRawOriginal('nationality') ?? '';
                                                } else  {
                                                $cou = '';
                                                $countries = collect($client->nationality)->toArray();
                                                foreach( $countries as $name) {
                                                $cou .=  '<span class="badge badge-dark">' .  $name . '</span>';
                                                }
                                                echo $cou;
                                                }
                                            @endphp
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">{{ __('Languages') }}</th>
                                        <td>
                                            @php
                                                if (is_null($client->lang)){
                                                    echo $client->getRawOriginal('lang') ?? '';
                                                } else  {
                                                $cou = '';
                                                $countries = collect($client->lang)->toArray();
                                                foreach( $countries as $name) {
                                                $cou .=  '<span class="badge badge-dark">' .  $name . '</span>';
                                                }
                                                echo $cou;
                                                }
                                            @endphp
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Desciption') }}</th>
                                        <td>
                                            {!! $client->description ?? '' !!}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- end of table col-lg-6 -->
                            <div class="col-md-6">
                                <table class="table">
                                    <tbody>
                                    <tr>
                                        <th scope="row">{{__('Status')}}</th>
                                        <td>
                                            @php
                                                $i = $client->status;
                                                switch ($i) {
                                                case 1:
                                                echo '<span class="badge badge-light-primary">'.__('New Lead').'</span>';
                                                break;
                                                case 8:
                                                echo '<span class="badge badge-light-primary">'.__('No Answer').'</span>';
                                                break;
                                                case 12:
                                                echo '<span class="badge badge-light-primary">'.__('In progress').'</span>';
                                                break;
                                                case 3:
                                                echo '<span class="badge badge-light-primary">'.__('Potential appointment').'</span>';
                                                break;
                                                case 4:
                                                echo '<span class="badge badge-light-primary">'.__('Appointment set').'</span>';
                                                break;
                                                case 10:
                                                echo '<span class="badge badge-light-primary">'.__('Appointment follow up').'</span>';
                                                break;
                                                case 5:
                                                echo '<span class="badge badge-light-success">'.__('Sold').'</span>';
                                                break;
                                                case 13:
                                                echo '<span class="badge badge-light-danger">'.__('Unreachable').'</span>';
                                                break;
                                                case 7:
                                                echo '<span class="badge badge-light-danger">'.__('Not interested').'</span>';
                                                break;
                                                case 11:
                                                echo '<span class="badge badge-light-danger">'.__('Low budget').'</span>';
                                                break;
                                                case 9:
                                                echo '<span class="badge badge-light-danger">'.__('Wrong Number').'</span>';
                                                break;
                                                case 14:
                                                echo '<span class="badge badge-light-danger">'.__('Unqualified').'</span>';
                                                break;
                                                case 15:
                                                echo '<span class="badge badge-light-danger">'.__('Lost').'</span>';
                                                break;
                                                }
                                            @endphp
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">{{ __('Propriety') }}</th>
                                        <td>
                                            @php
                                                $i = $client->priority;
                                                switch ($i) {
                                                case 1:
                                                echo '<span class="txt-success f-w-600">'.__('Low').'</span>';
                                                break;
                                                case 2:
                                                echo '<span class="txt-warning f-w-600">'.__('Medium').'</span>';
                                                break;
                                                case 3:
                                                echo '<span class="txt-danger f-w-600">'.__('High').'</span>';
                                                break;
                                                }
                                            @endphp
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">{{ __('Budget') }}</th>
                                        <td>
                                            @php
                                                if (is_null($client->budget_request)) {
                                                    echo $client->getRawOriginal('budget_request') ?? '';
                                                } else {
                                                    $cou = '';
                                                    $countries = collect($client->budget_request)->toArray();
                                                    $arr = $budget_request;
                                                    $allowed = $countries;
                                                    $result = array_intersect_key($arr, array_flip($allowed));
                                                    foreach ($result as $val) {
                                                            $cou .= '<span class="badge badge-light-primary">' . $val['text'] . '</span><br>';
                                                }
                                                    echo $cou;
                                                }
                                            @endphp
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">{{ __('Rooms Request') }}</th>
                                        <td>
                                            @php
                                                if (is_null($client->rooms_request)) {
                                                    echo $client->getRawOriginal('rooms_request') ?? '';
                                                } else {
                                                    $cou = '';
                                                    $countries = collect($client->rooms_request)->toArray();
                                                    //$result = array_intersect_key($countries, $requirements_request);
                                                    $arr = $rooms_request;
                                                    $allowed = $countries;
                                                    $result = array_intersect_key($arr, array_flip($allowed));
                                                    foreach ($result as $val) {
                                                            $cou .= '<span class="badge badge-light-primary">' . $val['text'] . '</span><br>';
                                                }
                                                    echo $cou;
                                                }
                                            @endphp
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">{{ __('Requirement') }}</th>
                                        <td>
                                            @php
                                                if (is_null($client->requirements_request)) {
                                                    echo $client->getRawOriginal('requirements_request') ?? '';
                                                } else {
                                                    $cou = '';
                                                    $countries = collect($client->requirements_request)->toArray();
                                                    //$result = array_intersect_key($countries, $requirements_request);
                                                    $arr = $requirements_request;
                                                    $allowed = $countries;
                                                    $result = array_intersect_key($arr, array_flip($allowed));
                                                    foreach ($result as $val) {
                                                            $cou .= '<span class="badge badge-light-primary">' . $val['text'] . '</span><br>';
                                                }
                                                    echo $cou;
                                                }
                                            @endphp
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">{{ __('Source') }}</th>
                                        <td>
                                            {{ optional($client->source)->name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">{{ __('Companies name') }}</th>
                                        <td>
                                            {{ optional($client)->campaigne_name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">{{ __('Agency') }}</th>
                                        <td>
                                            {{ optional($client->agency)->name }}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- end of table col-lg-6 -->
                        </div>
                    </div>
                </div>
                @include('clients.task-note')
            </div>
            <div class="col-md-3 col-lg-2">
                <div class="card card-with-border">
                    <div class="card-header b-b-info">
                        <h5 class="text-muted">{{ __('Owned by') }}</h5>
                    </div>
                    <div class="card-body pl-2 pr-2 pt-4">
                        <div class="inbox">
                            <div class="media active">
                                <div class="media-size-email">
                                    <img class="mr-3 rounded-circle img-50"
                                         style="width: 50px;height:50px;"
                                         src="{{ asset('storage/' . $client->user->image_path) }}"
                                         alt="">
                                </div>
                                <div class="media-body">
                                    <h6 class="font-primary">{{ $client->user->name }}</h6>
                                    <p>{{ $client->user->roles->first()->name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transfer to Deal -->
                @can('transfer-lead-to-deal')
                    <div class="card card-with-border">
                        <div class="card-header b-b-info">
                            <h5 class="text-muted">{{ __('Transfer to deal') }}</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('sales.transfer') }}" method="POST">
                                @csrf
                                <input type="hidden" name="clientId" value="{{ $client->id }}">
                                <button class="btn btn-outline-success btn-sm form-control"
                                        id="tran-to">{{ __('Done') }} <i
                                        class="icon-arrow-right"></i></button>
                            </form>
                        </div>
                    </div>
                @endcan
                @if($client->leads()->exists())
                    <div class="card card-with-border">
                        <div class="card-header">
                            <h5 class="d-inline-block">{{ __('Deals history') }}</h5>
                        </div>
                        <div class="card-body activity-social">
                            <ul>
                                @foreach($client->leads as $lead)
                                    <li class="border-recent-success">
                                        <small>{{ $lead->created_at->format('Y-m-d') }}</small>
                                        <p class="mb-0">{{ __('Stage') }}: <span
                                                class="f-w-800 text-primary">
                                                @php
                                                    $i = $lead->stage_id;
                                                switch ($i) {
                                                    case 1:
                                                        echo '<span class="badge badge-light-primary f-w-600">' . __('In contact') . '</span>';
                                                        break;
                                                    case 2:
                                                        echo '<span class="badge badge-light-primary f-w-600">' . __('Appointment Set') . '</span>';
                                                        break;
                                                    case 3:
                                                        echo '<span class="badge badge-light-primary f-w-600">' . __('Follow up') . '</span>';
                                                        break;
                                                    case 4:
                                                        echo '<span class="badge badge-light-primary f-w-600">' . __('Reservation') . '</span>';
                                                        break;
                                                    case 5:
                                                        echo '<span class="badge badge-light-primary f-w-600">' . __('Contract signed') . '</span>';
                                                        break;
                                                    case 6:
                                                        echo '<span class="badge badge-light-primary f-w-600">' . __('Down payment') . '</span>';
                                                        break;
                                                    case 7:
                                                        echo '<span class="badge badge-light-primary f-w-600">' . __('Developer invoice') . '</span>';
                                                        break;
                                                    case 8:
                                                        echo '<span class="badge badge-light-success f-w-600">' . __('Won Deal') . '</span>';
                                                        break;
                                                    case 9:
                                                        echo '<span class="badge badge-light-danger f-w-600">' . __('Lost') . '</span>';
                                                        break;
                                                }
                                                @endphp
                                        </span></p>
                                        <p>{{ __('Name') }} <a
                                                href="{{ route('leads.show', $lead) }}">{{ $lead->lead_name }}</a></p>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                @if($client->StatusLog()->exists())
                    <div class="card card-with-border">
                        <div class="card-header">
                            <h5 class="d-inline-block">{{ __('Status activity') }}</h5>
                        </div>
                        <div class="card-body activity-social">
                            <ul>
                                @foreach($client->StatusLog as $log)
                                    <li class="border-recent-warning">
                                        <small>{{ $log->created_at->format('Y-m-d H:i') }}</small>
                                        <p class="mb-0">{{ __('Status change to') }}: <span
                                                class="f-w-800 text-primary">{{ $log->status_name }}</span></p>
                                        <P>by <a href="#">{{ $log->user_name }}</a></P>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                <div class="card card-with-border">
                    <div class="card-header b-b-info">
                        <h5 class="text-muted">{{ __('History') }}</h5>
                    </div>
                    <div class="card-body">
                        <h6>{{ __('Modified By:') }} </h6>
                        <p>{{ $client->updateBy->name }}</p>
                        <h6>{{ __('Created time:') }} </h6>
                        <p>
                            {{ Carbon\Carbon::parse($client->created_at)->format('Y-m-d H:m') }}
                        </p>
                        <h6>{{ __('Modified time:') }} </h6>
                        <p>
                            {{ Carbon\Carbon::parse($client->updated_at)->format('Y-m-d H:m') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
