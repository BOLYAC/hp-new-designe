@extends('layouts.vertical.master')

@section('content')

    <div class="row">
        <div class="col-md-9 col-lg-10">
            <!-- Zero config.table start -->
            <div class="card b-t-primary">
                <div class="card-body ">
                    <div class="row">
                        <div class="col-lg-6">
                            <table class="table m-0">
                                <tbody>
                                <tr>
                                    <th scope="row">Id</th>
                                    <td>{{ $client->public_id ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Full Name</th>
                                    <td>{{ $client->full_name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Phone(s)</th>
                                    <td>{{ $client->client_number }}
                                        <br> {{ $client->client_number_2 }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Email(s)</th>
                                    <td>{{ $client->client_email }}
                                        <br> {{ $client->client_email_2 }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Country</th>
                                    <td>
                                        @php
                                            if (is_null($client->country)){
                                            echo $client->getRawOriginal('country') ?? '';
                                            } else  {
                                            $cou = '';
                                            $countries = collect($client->country)->toArray();
                                            foreach( $countries as $name) {
                                            $cou .=  '<span class="badge badge-inverse">' .  $name . '</span>';
                                            }
                                            echo $cou;
                                            }
                                        @endphp
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Nationality</th>
                                    <td>
                                        @php
                                            if (is_null($client->nationality)){
                                            echo $client->getRawOriginal('nationality') ?? '';
                                            } else  {
                                            $cou = '';
                                            $countries = collect($client->nationality)->toArray();
                                            foreach( $countries as $name) {
                                            $cou .=  '<span class="badge badge-inverse">' .  $name . '</span>';
                                            }
                                            echo $cou;
                                            }
                                        @endphp
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Languages</th>
                                    <td>
                                        @php
                                            if (is_null($client->lang)){
                                                echo $client->getRawOriginal('lang') ?? '';
                                            } else  {
                                            $cou = '';
                                            $countries = collect($client->lang)->toArray();
                                            foreach( $countries as $name) {
                                            $cou .=  '<span class="badge badge-inverse">' .  $name . '</span>';
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
                                    <th scope="row">Status</th>
                                    <td>
                                        @php
                                            $i = $client->status;
                                            switch ($i) {
                                            case 1:
                                            echo '<span class="label label-inverse-info">'.__('New Lead').'</span>';
                                            break;
                                            case 8:
                                            echo '<span class="label label-inverse-info">'.__('No Answer').'</span>';
                                            break;
                                            case 12:
                                            echo '<span class="label label-inverse-info">'.__('In progress').'</span>';
                                            break;
                                            case 3:
                                            echo '<span class="label label-inverse-info">'.__('Potential appointment').'</span>';
                                            break;
                                            case 4:
                                            echo '<span class="label label-inverse-info">'.__('Appointment set').'</span>';
                                            break;
                                            case 10:
                                            echo '<span class="label label-inverse-info">'.__('Appointment follow up').'</span>';
                                            break;
                                            case 5:
                                            echo '<span class="label label-inverse-info">'.__('Sold').'</span>';
                                            break;
                                            case 13:
                                            echo '<span class="label label-inverse-info">'.__('Unreachable').'</span>';
                                            break;
                                            case 7:
                                            echo '<span class="label label-inverse-info">'.__('Not interested').'</span>';
                                            break;
                                            case 11:
                                            echo '<span class="label label-inverse-info">'.__('Low budget').'</span>';
                                            break;
                                            case 9:
                                            echo '<span class="label label-inverse-info">'.__('Wrong Number').'</span>';
                                            break;
                                            case 14:
                                            echo '<span class="label label-inverse-info">'.__('Unqualified').'</span>';
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
                                            echo '<span class="label label-warning">'.__('Low').'</span>';
                                            break;
                                            case 2:
                                            echo '<span class="label label-warning">'.__('Medium').'</span>';
                                            break;
                                            case 3:
                                            echo '<span class="label label-warning">'.__('High').'</span>';
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
                <div class="card-footer">
                    <a href="{{ url()->previous() }}" class="btn btn-danger btn-sm"><i
                            class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-lg-2">
            <div class="card">
                <div class="card-header b-b-info">
                    <h5 class="text-muted">{{ __('Owned by') }}</h5>
                </div>
                <div class="card-body pl-2 pr-2 pt-4">
                    <div class="inbox">
                        <div class="media active">
                            <div class="media-size-email">
                                <img class="mr-3 rounded-circle"
                                     src="{{ asset('/assets/images/user/user.png') }}"
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
        </div>
    </div>

@endsection
