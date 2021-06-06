@extends('layouts.vertical.master')
@section('title', 'Dashboard')

@section('style_before')
    <!-- Plugins css start-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/summernote.css') }}">
    <!-- Notification.css -->
    <link rel="stylesheet" href="{{ asset('assets/css/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/datatable-extension.css') }}">
    <!-- Select 2 css -->
    <link rel="stylesheet" href="{{ asset('assets/css/select2.css') }}"/>
@endsection

@section('style')
    <style type="text/css">
        input[type="datetime-local"]::-webkit-calendar-picker-indicator {
            background: transparent;
            bottom: 0;
            color: transparent;
            cursor: pointer;
            height: auto;
            left: 0;
            position: absolute;
            right: 0;
            top: 0;
            width: auto;
        }
    </style>
@endsection

@section('script')
    <!-- Select 2 js -->
    <script type="text/javascript" src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>
    <!-- Plugins JS start-->
    <script src="{{ asset('assets/js/editor/summernote/summernote.js') }}"></script>
    <script src="{{ asset('assets/js/editor/summernote/summernote.custom.js') }}"></script>
    <script>
        $(function () {
            let budgetData = [
                {
                    id: 1,
                    text: 'Less then 50K'
                },
                {
                    id: 2,
                    text: '50K <> 100K'
                },
                {
                    id: 3,
                    text: '100K <> 150K'
                },
                {
                    id: 4,
                    text: '150K <> 200K'
                },
                {
                    id: 5,
                    text: '200K <> 300K'
                },
                {
                    id: 6,
                    text: '300K <> 400k'
                },
                {
                    id: 7,
                    text: '400k <> 500K'
                },
                {
                    id: 8,
                    text: '500K <> 600k'
                },
                {
                    id: 9,
                    text: '600K <> 1M'
                },
                {
                    id: 10,
                    text: '1M <> 2M'
                },
                {
                    id: 11,
                    text: 'More then 2M'
                }
            ]

            $('.js-budgets-all').select2({
                data: budgetData,
                theme: 'classic',
            })
            $(".js-budgets-all").select2({
                theme: 'classic'
            }).val({!! json_encode($event->lead_budget) !!}).trigger('change.select2');
        });
        $('.js-client-all').select2({
            ajax: {
                url: "{{ route('event.client.filter') }}",
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.full_name,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        });
        $(function () {
            if ($('#results').val() === '3') {
                $('#negative-form').show();
                console.log($('#results').val())
            } else {
                $('#negative-form').hide();
            }
            $('#results').change(function () {
                if ($('#results').val() === '3') {
                    $('#negative-form').show();
                } else {
                    $('#negative-form').hide();
                }
            })
        });
    </script>

@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('events.index') }}">{{ __('Events') }}</a></li>
    <li class="breadcrumb-item">{{ __('Show event') }}</li>
@endsection

@section('breadcrumb-title')

@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9">
                <!-- Zero config.table start -->
                @include('partials.flash-message')
                <div class="card">
                    <form action="{{ route('events.update', $event) }}" method="post"
                          enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card-body b-t-primary">
                            @include('events.partial.owner')
                            @can('share-lead')
                                <div class="form-group input-group-sm">
                                    <label for="role">{{ __('Assigned') }}</label>
                                    <select class="form-control" name="user_id" id="user_id">
                                        @if(auth()->user()->hasRole('Admin'))
                                            @foreach($users as $user)
                                                <option
                                                    value="{{ $user->id }}" {{ $event->user_id === $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }}</option>
                                            @endforeach
                                        @elseif(auth()->user()->hasRole('Manager'))
                                            @foreach(auth()->user()->currentTeam->allUsers() as $user)
                                                <option
                                                    value="{{ $user->id }}" {{ $event->user_id === $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            @endcan

                            @can('share-appointment')
                                <div class="form-group form-group-sm">
                                    <label for="share_with">{{ __('Sell representative') }}</label>
                                    <select class="js-example-basic-multiple form-control" name="share_with[]"
                                            id="share_with" multiple>
                                        @foreach($users as $user)
                                            @if($event->SharedEvents->contains($user))
                                                <option value="{{ $user->id }}"
                                                        selected>{{ $user->name }}</option>
                                            @else
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            @endcan

                            @include('events.partial.sells')
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary btn-sm waves-effect waves-light">
                                save
                                <i class="icon-save"></i></button>
                            <a href="{{ redirect()->route('events.index') }}" id="edit-cancel"
                               class="btn btn-warning btn-sm">{{ __('Cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header b-b-primary">
                        <h5 class="text-muted">{{ __('Owned by') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="inbox">
                            <div class="media active">
                                <div class="media-size-email">
                                    <img class="mr-3 rounded-circle"
                                         src="{{ asset('/assets/images/user/user.png') }}"
                                         alt="">
                                </div>
                                <div class="media-body">
                                    <h6 class="font-primary">{{ $event->user->name }}</h6>
                                    <p>{{ $event->user->roles->first()->name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header b-b-primary">
                        <h6 class="text-muted">
                            @if($event->client->exists())
                                <a href="{{ route('clients.edit', $event->client) }}">{{ $event->client->full_name ?? '' }}</a>
                            @else
                                {{ $event->lead_name ?? '' }}
                            @endif
                        </h6>
                    </div>
                    <div class="card-body">
                        @if($event->client->exists())
                            <p><b>{{ __('Phone number:') }}</b><br>{{ $event->lead_phone ?? '' }}</p>
                            <p><b>{{ __('Email:') }}</b><br>{{ $event->lead_email ?? '' }}</p>
                        @else
                            <p><b>{{ __('Phone number:') }}</b><br>{{ $event->client->client_number ?? '' }}</p>
                            <p><b>{{ __('Email:') }}</b><br>{{ $event->client->client_email ?? '' }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
