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
    <li class="breadcrumb-item">{{ __('Show:') }} {{ $client->full_name }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9 col-lg-10">
                <!-- Zero config.table start -->
                <div class="card b-t-primary">
                    <div class="card-header b-t-primary b-b-primary p-2 d-flex justify-content-between">
                        <h5 class="mr-auto mt-2">{{ __('Lead') }}: {{ $client->full_name ?? '' }}</h5>
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
                                        <td>{{ $client->full_name ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">{{ __('Phone(s)') }}</th>
                                        <td>{{ $client->client_number }}
                                            <br> {{ $client->client_number_2 }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">{{ __('Email(s)') }}</th>
                                        <td>{{ $client->client_email }}
                                            <br> {{ $client->client_email_2 }}</td>
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
                                                }
                                            @endphp
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">{{ __('Budget') }}</th>
                                        <td>
                                            @php
                                                $i = $client->status;
                                                switch ($i) {
                                                case 1:
                                                echo 'Less then 50K';
                                                break;
                                                case 2:
                                                echo '50K-100K';
                                                break;
                                                case 3:
                                                echo '100K-150K';
                                                break;
                                                case 4:
                                                echo '150K-200K';
                                                break;
                                                case 5:
                                                echo ' 200K-300K';
                                                break;
                                                case 6:
                                                echo '300K-400k';
                                                break;
                                                case 7:
                                                echo '400k-500K';
                                                break;
                                                case 8:
                                                echo '500K-600k';
                                                break;
                                                case 9:
                                                echo '600K-1M';
                                                break;
                                                case 10:
                                                echo '1M-2M';
                                                break;
                                                case 11:
                                                echo 'More then 2M';
                                                break;
                                                }
                                            @endphp
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">{{ __('Request') }}</th>
                                        <td>
                                            @php
                                                $i = $client->rooms;
                                                switch ($i) {
                                                case 1:
                                                echo '0 + 1';
                                                break;
                                                case 2:
                                                echo '1 + 1';
                                                break;
                                                case 3:
                                                echo '2 + 1';
                                                break;
                                                case 4:
                                                echo '3 + 1';
                                                break;
                                                case 5:
                                                echo '4 + 1';
                                                break;
                                                case 6:
                                                echo '5 + 1';
                                                break;
                                                case 7:
                                                echo '6 + 1';
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
                                        <th scope="row">{{ __('Requirement') }}</th>
                                        <td>
                                            @php
                                                $i = $client->requirements;
                                                switch ($i) {
                                                case 1:
                                                echo __('Investments');
                                                break;
                                                case 2:
                                                echo __('Life style');
                                                break;
                                                case 3:
                                                echo __('Investments + Life style');
                                                break;
                                                case 4:
                                                echo __('Citizenship');
                                                break;
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
                                         src="{{ asset('storage/' . $lead->user->image_path) }}"
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
                                        <p>{{ __('Name') }} <a href="{{ route('leads.show', $lead) }}">{{ $lead->lead_name }}</a></p>
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
