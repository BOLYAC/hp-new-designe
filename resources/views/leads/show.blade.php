@extends('layouts.vertical.master')
@section('title', '| Deal Edit')
@section('style_before')
    <!-- Select 2 css -->
    <link rel="stylesheet" href="{{ asset('assets/css/select2.css') }}"/>
    <!-- Plugins css start-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/summernote.css') }}">
    <!-- Datatables.css -->
    <link rel="stylesheet" href="{{ asset('assets/css/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/datatable-extension.css') }}">



    <style>
        .select2-container {
            width: 100% !important;
            padding: 0;
        }
    </style>

@endsection

@section('script')
    <!-- Plugins JS start-->
    <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <!-- Plugins JS start-->
    <script src="{{ asset('assets/js/editor/summernote/summernote.js') }}"></script>
    <script src="{{ asset('assets/js/editor/summernote/summernote.custom.js') }}"></script>
    <!-- Plugins JS start-->
    <script src="{{ asset('assets/js/notify/bootstrap-notify.min.js') }}"></script>
    <!-- Plugins JS start-->

    <!-- Datatables.js -->
    <script src="{{asset('assets/js/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables/datatable-extension/dataTables.bootstrap4.min.js')}}"></script>
    <script>

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
        })

        $(".js-select2-sales").select2();
        $('.js-event-sells').select2();
        // Start Edit record
        let table = $('#res-config').DataTable({
            order: [[1, 'desc']],
        });

        // Init notification
        function notify(title, type) {
            $.notify({
                    title: title
                },
                {
                    type: type,
                    allow_dismiss: true,
                    newest_on_top: true,
                    mouse_over: true,
                    showProgressbar: true,
                    spacing: 10,
                    timer: 2000,
                    placement: {
                        from: 'top',
                        align: 'right'
                    },
                    offset: {
                        x: 30,
                        y: 30
                    },
                    delay: 1000,
                    z_index: 10000,
                    animate: {
                        enter: 'animated bounce',
                        exit: 'animated bounce'
                    }
                });
        }

        $("#stage_id").on('change', function (e) {
            e.preventDefault();
            let level = $(this).val();
            let lead_id = '{{ $lead->id }}';
            if (level) {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('stage.change') }}',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        stage_id: level,
                        lead_id: lead_id
                    },
                    success: function (r) {
                        notify('Stage changed', 'success');
                    }
                    , error: function (error) {
                        notify('Ops!! Something wrong', 'danger');
                    }
                });
            }
        });
        $('#trans-to-sales').on('submit', function (e) {
            e.preventDefault();
            user_id = $('#inCharge').val();
            lead_id = '{{ $lead->id }}';

            $.ajax({
                url: "{{route('deal.change.owner')}}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    user_id: user_id,
                    lead_id: lead_id,
                },
                success: function () {
                    notify('Deal transferred successfully', 'success');
                },
                error: function (response) {
                    notify('Ops something is wrong!', 'danger');
                },
            });
        });
        $('.js-language-all').select2({
            ajax: {
                url: "{{ route('language.name') }}",
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.name,
                                id: item.name
                            }
                        })
                    };
                },
                cache: true
            }
        });
    </script>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('leads.index') }}">{{ __('Deals') }}</a></li>
    <li class="breadcrumb-item">{{ __('Lead name') }}: {{ $lead->full_name ?? $lead->client->full_name }}</li>
@endsection

@section('content')
    <!-- Main-body start -->
    <div class="container-fluid">
        @include('partials.flash-message')
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header b-t-primary b-b-primary row">
                        <div class="col-md-4 col-lg-4">
                            @if($lead->invoice_id <> 0)
                                <span class="badge badge-success">{{ __('Deal Won') }}</span>
                            @else
                                @can('deal-stage')
                                    <div>
                                        <select name="stage_id" id="stage_id"
                                                class="form-control form-control-sm">
                                            <option
                                                value="1" {{ old('stage_id', $lead->stage_id) == 1 ? 'selected' : '' }}>
                                                {{ __('In contact') }}
                                            </option>
                                            <option
                                                value="2" {{ old('stage_id', $lead->stage_id) == 2 ? 'selected' : '' }}>
                                                {{ __('Appointment Set') }}
                                            </option>
                                            <option
                                                value="3" {{ old('stage_id', $lead->stage_id) == 3 ? 'selected' : '' }}>
                                                {{ __('Follow up') }}
                                            </option>
                                            <option
                                                value="4" {{ old('stage_id', $lead->stage_id) == 4 ? 'selected' : '' }}>
                                                {{ __('Reservation') }}
                                            </option>
                                            <option
                                                value="5" {{ old('stage_id', $lead->stage_id) == 5 ? 'selected' : '' }}>
                                                {{ __('contract signed') }}
                                            </option>
                                            <option
                                                value="6" {{ old('stage_id', $lead->stage_id) == 6 ? 'selected' : '' }}>
                                                {{ __('Down payment') }}
                                            </option>
                                            <option
                                                value="7" {{ old('stage_id', $lead->stage_id) == 7 ? 'selected' : '' }}>
                                                {{ __('Developer invoice') }}
                                            </option>
                                            <option
                                                value="8" {{ old('stage_id', $lead->stage_id) == 8 ? 'selected' : '' }}>
                                                {{ __('Won Deal') }}
                                            </option>
                                            <option
                                                value="9" {{ old('stage_id', $lead->stage_id) == 9 ? 'selected' : '' }}>
                                                {{ __('Lost') }}
                                            </option>
                                        </select>
                                    </div>
                                @endcan
                            @endif
                        </div>
                        <div class="col-md-12 col-lg-8">
                            @if($lead->invoice_id <> 0)
                            @else
                                @can('transfer-deal-to-invoice')
                                    <div>
                                        <form action="{{ route('lead.convert.order', $lead) }}"
                                              onSubmit="return confirm('Are you sure?');"
                                              method="post" class="pull-right">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-success btn-sm"
                                                    href="">{{ __('Convert to invoice') }} <i class="ti-money"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endcan
                                @can('client-appointment')
                                    <div>
                                        <button
                                            class="btn btn-primary btn-sm pull-right mr-2"
                                            data-toggle="modal"
                                            data-target="#sign-in-modal">{{ __('Make appointment') }} <i
                                                class="ti-alarm-clock"></i></button>
                                    </div>
                                @endcan
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <h4>{{ __('Description') }}</h4>
                        <p>
                            {!! $lead->description !!}
                        </p>
                    </div>
                    <div class="card-footer">
                        {{ __('Created at:') }}
                        {{ Carbon\Carbon::parse($lead->created_at)->format('Y-m-d H:i') }}
                    </div>
                </div>
                @include('partials.comments', ['subject' => $lead])
                @include('partials.events', ['subject' => $lead])
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header b-b-info">
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
                                    <h6 class="font-primary">{{ $lead->user->name }}</h6>
                                    <p>{{ $lead->user->roles->first()->name }}</p>
                                </div>
                            </div>
                        </div>

                        <br>
                        @can('share-client')
                            <form id="trans-to-sales">
                                @csrf
                                <div class="form-group form-group-sm">
                                    <select class="form-control form-control-sm" name="inCharge" id="inCharge">
                                        @foreach($users as $user)
                                            <option
                                                value="{{ $user->id }}" {{ $lead->user_id == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit"
                                        class="btn btn-sm btn-outline-primary form-control form-control-sm">
                                    {{ __('Change') }} <i
                                        class="icon-share-alt"></i></button>
                            </form>
                        @endcan
                    </div>
                </div>
                <div class="card">
                    <div class="card-header b-b-primary">
                        <h6 class="text-muted">
                            @if($lead->client)
                                <a href="{{ route('clients.edit', $lead->client) }}">{{ $lead->client->full_name ?? '' }}</a>
                            @else
                                {{ $lead->lead_name ?? '' }}
                            @endif
                        </h6>
                    </div>
                    <div class="card-body">
                        @if(is_null($lead->client))
                            <p><b>{{ __('Phone number:') }}</b><br>{{ $lead->lead_phone }}</p>
                            <p><b>{{ __('Email:') }}</b><br>{{ $lead->lead_email }}</p>
                        @else
                            <p><b>{{ __('Phone number:') }}</b><br>{{ $lead->client->client_number ?? '' }}</p>
                            <p><b>{{ __('Email:') }}</b><br>{{ $lead->client->client_email ?? '' }}</p>
                        @endif
                    </div>
                </div>
                @can('share-deal')
                    <div class="card">
                        <div class="card-header b-b-primary">
                            <h6 class="text-muted">{{ __('sale(s) representative') }}</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{route('sales.shareLead')}}" method="POST" id="share_lead_with">
                                @csrf
                                <div class="form-group">
                                    <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                                    <select class="js-select2-sales form-control form-control-sm"
                                            name="share_with[]" id="share_with"
                                            multiple>
                                        @foreach($users as $user)
                                            @if($lead->ShareWithSelles->contains($user))
                                                <option value="{{ $user->id }}"
                                                        selected>{{ $user->name ?? '' }}</option>
                                            @else
                                                <option value="{{ $user->id }}">{{ $user->name ?? ''}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit"
                                        class="btn btn-sm btn-outline-primary form-control form-control-sm">{{ __('Share') }}
                                    <i
                                        class="icon-share"></i></button>
                            </form>
                        </div>
                    </div>
                @endcan
                <div class="card card-with-border">
                    <div class="card-header">
                        <h5 class="d-inline-block">{{ __('Stage activity') }}</h5>
                    </div>
                    <div class="card-body activity-social">
                        <ul>
                            @foreach($stage_logs as $log)
                                <li class="border-recent-success">
                                    <small>{{ $log->created_at->format('Y-m-d H:i') }}</small>
                                    <p class="mb-0">{{ __('Stage change to') }}: <span
                                            class="f-w-800 text-primary">{{ $log->stage_name }}</span></p>
                                    <P>by <a href="#">{{ $log->user_name }}</a></P>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Start Appointment Modal -->
    <div class="modal fade" id="sign-in-modal" tabindex="-1">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('New appointment') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('events.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-b-0">
                        <div class="row">
                            <div class="form-group input-group-sm col-6">
                                <label for="name">{{ __('Title') }}</label>
                                <input class="form-control sm" type="text" name="name" id="name">
                            </div>
                            <div class="form-group input-group-sm col-6">
                                <label for="event_date">{{ __('Date of appointment') }}</label>
                                <input id="event_date" class="form-control" name="event_date" type="datetime-local"
                                       required/>
                            </div>
                            <input type="hidden" name="client_id" value="{{ $lead->client_id}}">
                            <input type="hidden" name="lead_id" value="{{ $lead->id}}">
                            <input type="hidden" name="user_id" value="{{ auth()->id()}}">
                            <div class="form-group input-group-sm col-12">
                                <label for="color">{{ __('Colors') }}</label>
                                <div>
                                    <input id="color" name="color" type="color" value="#0B8043" list="presetColors">
                                    <datalist id="presetColors">
                                        <option>#0B8043</option>
                                        <option>#D50000</option>
                                        <option>#F4511E</option>
                                        <option>#8E24AA</option>
                                        <option>#3F51B5</option>
                                        <option>#039BE5</option>
                                    </datalist>
                                </div>
                            </div>
                            <div class="form-group input-group-sm col-2">
                                <label for="currency">{{__('Currency')}}</label>
                                <select class="form-control form-control-sm" id="currency" name="currency">
                                    <option value="try">TRY</option>
                                    <option value="usd">USD</option>
                                    <option value="euro">EURO</option>
                                </select>
                            </div>
                            <div class="form-group col-10">
                                <div class="col-form-label pt-0">{{ __('Budget') }}</div>
                                <select name="budget[]"
                                        class="form-control form-control-sm digits js-budgets-all col"
                                        multiple></select>
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="col-form-label">{{ __('Languages') }}</div>
                            <select class="js-language-all custom-select col-12" multiple name="lang[]" id="lang">
                            </select>
                        </div>
                        @can('share-appointment')
                            <div class="mb-2">
                                <div class="col-form-label">{{ __('Share with') }}</div>
                                <select class="js-event-sells custom-select" name="share_with[]" multiple>
                                    @foreach($users as $user)
                                        @if($lead->ShareWithSelles->contains($user))
                                            <option value="{{ $user->id }}"
                                                    selected>{{ $user->name ?? '' }}</option>
                                        @else
                                            <option value="{{ $user->id }}">{{ $user->name ?? ''}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        @endcan
                        <div class="form-group input-group-sm">
                            <label for="description">{{ __('Description') }}</label>
                            <textarea class="form-control sm" type="text" name="description"
                                      id="description"></textarea>
                        </div>
                        <div class="form-group input-group-sm">
                            <label for="place">{{ __('Place') }}</label>
                            <input class="form-control sm" type="text" name="place" id="place">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary"
                                onClick="this.form.submit(); this.disabled=true; this.value='Sending…';">{{ __('Save') }}
                            <i
                                class="ti-save-alt"></i></button>
                        <button type="button" class="btn btn-warning" data-dismiss="modal">{{ __('Cancel') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Appointment Modal -->

@endsection

