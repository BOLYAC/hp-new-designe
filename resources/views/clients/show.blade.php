@extends('layouts.app')

@section('content')

    <!-- Main-body start -->
    <div class="main-body">
        <div class="page-wrapper">
            <!-- Page header start -->
            <div class="page-header">
                <div class="page-header-title">
                    <h4>Lead: {{ $client->full_name ?? '' }}</h4>
                </div>
                <div class="page-header-breadcrumb">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">
                                <i class="icofont icofont-home"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">List</a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- Page header end -->
            <!-- Page body start -->
            <div class="page-body">
                <div class="row">
                    <div class="col-md-9 col-lg-10">
                        <!-- Zero config.table start -->
                        <div class="card">
                            <div class="card-header">
                            </div>
                            <div class="card-block">
                                <div class="view-info">
                                    <div class="general-info">
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
                                                                echo '<span class="label label-inverse-info">New Lead</span>';
                                                                break;
                                                                case 8:
                                                                echo '<span class="label label-inverse-info">No Answer</span>';
                                                                break;
                                                                case 12:
                                                                echo '<span class="label label-inverse-info">In progress</span>';
                                                                break;
                                                                case 3:
                                                                echo '<span class="label label-inverse-info">Potential appointment</span>';
                                                                break;
                                                                case 4:
                                                                echo '<span class="label label-inverse-info">Appointment set</span>';
                                                                break;
                                                                case 10:
                                                                echo '<span class="label label-inverse-info">Appointment follow up</span>';
                                                                break;
                                                                case 5:
                                                                echo '<span class="label label-inverse-info">Sold</span>';
                                                                break;
                                                                case 13:
                                                                echo '<span class="label label-inverse-info">Unreachable</span>';
                                                                break;
                                                                case 7:
                                                                echo '<span class="label label-inverse-info">Not interested</span>';
                                                                break;
                                                                case 11:
                                                                echo '<span class="label label-inverse-info">Low budget</span>';
                                                                break;
                                                                case 9:
                                                                echo '<span class="label label-inverse-info">Wrong Number</span>';
                                                                break;
                                                                case 14:
                                                                echo '<span class="label label-inverse-info">Unqualified</span>';
                                                                break;
                                                                }
                                                            @endphp
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Budget</th>
                                                        <td>
                                                            @php
                                                                $i = $client->status;
                                                                switch ($i) {
                                                                case 1:
                                                                echo 'Less then 50K';
                                                                break;
                                                                case 2:
                                                                echo '50K <> 100K';
                                                                break;
                                                                case 3:
                                                                echo '100K <> 150K';
                                                                break;
                                                                case 4:
                                                                echo '150K <> 200K';
                                                                break;
                                                                case 5:
                                                                echo ' 200K <> 300K';
                                                                break;
                                                                case 6:
                                                                echo '300K <> 400k';
                                                                break;
                                                                case 7:
                                                                echo '400k <> 500K';
                                                                break;
                                                                case 8:
                                                                echo '500K <> 600k';
                                                                break;
                                                                case 9:
                                                                echo '600K <> 1M';
                                                                break;
                                                                case 10:
                                                                echo '1M <> 2M';
                                                                break;
                                                                case 11:
                                                                echo 'More then 2M';
                                                                break;
                                                                }
                                                            @endphp
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Request</th>
                                                        <td>
                                                            @php
                                                                $i = $client->rooms;
                                                                switch ($i) {
                                                                case 1:
                                                                echo '1 + 0';
                                                                break;
                                                                case 2:
                                                                echo '1 + 1';
                                                                break;
                                                                case 3:
                                                                echo '1 + 2';
                                                                break;
                                                                case 4:
                                                                echo '1 + 3';
                                                                break;
                                                                case 5:
                                                                echo '1 + 4';
                                                                break;
                                                                case 6:
                                                                echo '1 + 5';
                                                                break;
                                                                case 7:
                                                                echo '1 + 6';
                                                                break;
                                                                }
                                                            @endphp
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Propriety</th>
                                                        <td>
                                                            @php
                                                                $i = $client->priority;
                                                                switch ($i) {
                                                                case 1:
                                                                echo '<span class="label label-warning">Low</span>';
                                                                break;
                                                                case 2:
                                                                echo '<span class="label label-warning">Medium</span>';
                                                                break;
                                                                case 3:
                                                                echo '<span class="label label-warning">High</span>';
                                                                break;
                                                                }
                                                            @endphp
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Requirement</th>
                                                        <td>
                                                            @php
                                                                $i = $client->requirements;
                                                                switch ($i) {
                                                                case 1:
                                                                echo 'Investments';
                                                                break;
                                                                case 2:
                                                                echo 'Life style';
                                                                break;
                                                                case 3:
                                                                echo 'Investments + Life style';
                                                                break;
                                                                case 4:
                                                                echo 'Citizenship';
                                                                break;
                                                                }
                                                            @endphp
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Source</th>
                                                        <td>
                                                            {{ optional($client->source)->name }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Companies name</th>
                                                        <td>
                                                            {{ optional($client)->campaigne_name }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Agency</th>
                                                        <td>
                                                            {{ optional($client->agency)->name }}
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!-- end of table col-lg-6 -->
                                        </div>
                                        <!-- end of row -->
                                    </div>
                                    <!-- end of general info -->
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ url()->previous() }}" class="btn btn-danger btn-sm"><i
                                        class="fa fa-arrow-left"></i> Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-2">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-header-text"> Owned by</h5>
                            </div>
                            <div class="card-block user-box text-center">
                                <a class="media-center" href="#!">
                                    <img class="media-object img-circle" src="{{ asset('assets/images/avatar-1.png') }}"
                                         alt="Generic placeholder image">
                                    <div class="live-status bg-danger"></div>
                                </a>
                                <div class="media-body">
                                    <div class="chat-header">{{ $client->user->name }}</div>
                                    <div
                                        class="text-muted social-designation">{{ $client->user->roles->first()->name }}</div>
                                </div>

                            </div>
                        </div>
                    </div>                    <!-- Zero config.table end -->
                </div>
            </div>
        </div>
        <!-- Page body end -->
    </div>

@endsection
