@extends('layouts.vertical.master')
@section('title', 'Lead Edit')
@section('style_before')
    <!-- Plugins css start-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/summernote.css') }}">
    <!-- Notification.css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/pages/notification/notification.css') }}">
    <!-- Animate.css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/animate.css') }}">
    <!-- Date-Dropper css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datedropper.min.css') }}"/>
    <!-- Mini-color css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/jquery.minicolors.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/lightbox.min.css') }}">
    <!-- Select 2 css -->
    <link rel="stylesheet" href="{{ asset('assets/css/select2.css') }}"/>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css">
    <style>
        .mce-menu {
            z-index: 999999999999999 !important;
        }

        .mce-popover {
            z-index: 999999999999999 !important;
        }

        #note-in-modal {
            position: fixed;
            top: 10px;
            right: auto;
            left: 5px;
            bottom: 0;
        }

        .select2-container--open {
            z-index: 999999999999999 !important;
        }
    </style>
@endsection

@section('style')

@endsection

@section('script')
    <!-- Plugins JS start-->
    <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>
    <!-- Plugins JS start-->
    <script src="{{ asset('assets/js/editor/summernote/summernote.js') }}"></script>
    <script src="{{ asset('assets/js/editor/summernote/summernote.custom.js') }}"></script>
    <!-- Plugins JS start-->
    <script src="{{ asset('assets/js/notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('assets/js/notify/notify-script.js') }}"></script>
    <!-- Plugins JS start-->
    <script type="{{ asset('assets/js/lightbox.min.js') }}"></script>
    <script>
        let requirmentData = [
            {
                id: 1,
                text: 'Investments'
            },
            {
                id: 2,
                text: 'Life style'
            },
            {
                id: 3,
                text: 'Investments + Life style'
            },
            {
                id: 4,
                text: 'Citizenship'
            },
        ]
        let roomsData = [
            {
                id: 1,
                text: '0 + 1'
            },
            {
                id: 2,
                text: '1 + 1'
            },
            {
                id: 3,
                text: '2 + 1'
            },
            {
                id: 4,
                text: '3 + 1'
            },
            {
                id: 5,
                text: '4 + 1'
            },
            {
                id: 6,
                text: '5 + 1'
            },
            {
                id: 7,
                text: '6 + 1'
            },
        ]
        let budgetData = [
            {
                id: 1,
                text: 'Less then 50K'
            },
            {
                id: 2,
                text: '50K-100K'
            },
            {
                id: 3,
                text: '100K-150K'
            },
            {
                id: 4,
                text: '150K200K'
            },
            {
                id: 5,
                text: '200K-300K'
            },
            {
                id: 6,
                text: '300K-400k'
            },
            {
                id: 7,
                text: '400k-500K'
            },
            {
                id: 8,
                text: '500K-600k'
            },
            {
                id: 9,
                text: '600K-1M'
            },
            {
                id: 10,
                text: '1M-2M'
            },
            {
                id: 11,
                text: 'More then 2M'
            }
        ]
        //Welcome Message (not for login page)
        function notify(message, type) {
            $.growl({
                message: message
            }, {
                type: type,
                allow_dismiss: false,
                label: 'Cancel',
                className: 'btn-xs btn-inverse',
                placement: {
                    from: 'bottom',
                    align: 'right'
                },
                delay: 2500,
                animate: {
                    enter: 'animated fadeInRight',
                    exit: 'animated fadeOutRight'
                },
                offset: {
                    x: 30,
                    y: 30
                }
            });
        }

        window.livewire.on('alert', param => {
            notify(param['message'], param['type'])
        })


        $('#trans-to-sales').on('submit', function (e) {
            e.preventDefault();
            user_id = $('#inCharge').val();
            client_id = '{{ $client->id }}';

            $.ajax({
                url: "{{route('sales.share')}}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    user_id: user_id,
                    client_id: client_id,
                },
                success: function () {
                    notify('Client transferred successfully', 'success');
                },
                error: function (response) {
                    notify('already have been assigned to this user', 'danger');
                },
            });
        });

        $('#share_lead_with').on('submit', function (e) {
            e.preventDefault();
            user_id = $('#share_with').val();
            client_id = '{{ $client->id }}';

            $.ajax({
                url: "{{route('sales.shareLead')}}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    user_id: user_id,
                    client_id: client_id,
                },
                success: function () {
                    notify('Client transferred successfully', 'success');
                },
                error: function (response) {
                    console.log(response);
                    notify('already have been assigned to this user', 'danger');
                },
            });
        });

        $(document).ready(function () {
            // Start Edit record
            var table = $('#res-config').DataTable();
            table.on('click', '.delete', function () {
                var $this = $(this);
                var row = $this.closest("tr");
                var id = row.data("id");

                $('#deleteForm').attr('action', '/documents/' + id);
                $('#deleteModal').modal('show');
            })
        });
        @can('change-task')
        $('.task-new #assign_task').click(function (e) {
            e.preventDefault();
            var idTask = $(this).data('id');
            $('#task_assigned_id').val(idTask);
            $('#assignModal').modal('show');
        });
        @endcan
        // Submit Assignment
        $('#assignForm').on('submit', function (e) {
            e.preventDefault();
            let task_assigned_id = $('#task_assigned_id').val();
            let user_id = $('#assigned_user').val();
            $.ajax({
                url: "{{route('tasks.assigne')}}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    user_id: user_id,
                    task_assigned_id: task_assigned_id,
                },
                success: function (response) {
                    $('#assignModal').modal('hide');
                    notify('Task assigned', 'success');
                },
                error: function (response) {
                    notify('Something wrong', 'danger');
                }
            });
        });
        $('.js-rooms-all').select2({
            data: roomsData,
            theme: 'classic',
        })
        $('.js-requirements-all').select2({
            data: requirmentData,
            theme: 'classic',
        })
        $('.js-budgets-all').select2({
            data: budgetData,
            theme: 'classic',
        })

        $(function () {
            $(".js-rooms-all").select2({
                theme: 'classic'
            }).val({!! json_encode($client->rooms_request) !!}).trigger('change.select2');
            $(".js-requirements-all").select2({
                theme: 'classic'
            }).val({!! json_encode($client->requirements_request) !!}).trigger('change.select2');
            $(".js-budgets-all").select2({
                theme: 'classic'
            }).val({!! json_encode($client->budget_request) !!}).trigger('change.select2');
        });
        $('.js-country-all').select2({
            placeholder: "Select a country",
            theme: 'classic',
            ajax: {
                url: "{{ route('country.name') }}",
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
        $('.js-nationality-all').select2({
            placeholder: "Select a nationality",
            theme: 'classic',
            ajax: {
                url: "{{ route('nationality.name') }}",
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
        $('.js-language-all').select2({
            placeholder: "Select a language",
            theme: 'classic',
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
    <li class="breadcrumb-item">Leads</li>
@endsection

@section('breadcrumb-title')

@endsection

@section('content')

    <!-- Main-body start -->
    <div class="main-body" id="app">
        <div class="page-wrapper">
            <!-- Page header start -->
            <div class="page-header">
                <div class="page-header-title">
                    <h4>Lead: {{ $client->full_name }}</h4>
                </div>


                <div class="page-header-breadcrumb">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="index.html">
                                <i class="icofont icofont-home"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">Leads</a>
                        </li>
                    </ul>
                </div>
                <div class="page-header">
                    <div class="row mt-4">
                        <div class="col-2">
                            @if (isset($previous_record))
                                <a href="{{ route('clients.edit', $previous_record->id) }}" class="btn btn-primary">
                                    <i class="ti-arrow-left"></i>
                                    Previous
                                    Lead
                                </a>
                            @endif
                        </div>
                        <div class="col-2">
                            @if (isset($next_record))
                                <a href="{{ route('clients.edit', $next_record->id) }}" class="btn btn-primary">
                                    Next Lead <i class="ti-arrow-right"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- Page header end -->
            <!-- Page body start -->
            <div class="page-body">
                @include('partials.flash-message')
                <div class="row">
                    <div class="col-md-9 col-lg-10">
                        <!-- tab header start -->
                        <div class="tab-header">
                            <ul class="nav nav-tabs md-tabs tab-timeline" role="tablist" id="mytab">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#personal" role="tab">Personal
                                        Info</a>
                                    <div class="slide"></div>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#bnote" role="tab">Notes & Tasks</a>
                                    <div class="slide"></div>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#bdocs" role="tab">Documents</a>
                                    <div class="slide"></div>
                                </li>
                            </ul>
                        </div>
                        <!-- tab header end -->
                        <!-- tab content start -->
                        <div class="tab-content">
                            <!-- tab panel personal start -->
                            <div class="tab-pane active" id="personal" role="tabpanel">
                                <!-- personal card start -->
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-header-text">Contact information</h5>
                                    </div>
                                    <div class="card-block">
                                        <form action="{{ route('clients.update', $client) }}" method="POST"
                                              enctype="multipart/form-data" role="form">
                                            @csrf
                                            @method('PUT')
                                            <div class="form-group">
                                                <label for="">Id</label>
                                                <input type="text" class="form-control form-control-sm"
                                                       value="{{ $client->public_id }}" readonly>
                                            </div>
                                            <div class="row">
                                                <div class="col-md">
                                                    <div class="row">
                                                        <div class="form-group col-md-12 col-lg-6">
                                                            <label for="first_name">First
                                                                name</label>
                                                            <input type="text" name="first_name" id="first_name"
                                                                   class="form-control form-control-sm @error('first_name') form-control-danger @enderror"
                                                                   value="{{ old('first_name', $client->first_name) }}">
                                                            @error('first_name')
                                                            <span class="invalid-feedback" role="alert">
                                  <strong class="text-danger">{{ $message }}</strong>
                                </span>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group col-md-12 col-lg-6">
                                                            <label for="last_name">Last
                                                                name</label>
                                                            <input type="text" name="last_name" id="last_name"
                                                                   class="form-control form-control-sm @error('last_name') form-control-danger @enderror"
                                                                   value="{{ old('last_name', $client->last_name) }}">
                                                            @error('last_name')
                                                            <span class="invalid-feedback" role="alert">
                                  <strong class="text-danger">{{ $message }}</strong>
                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    @if($client)
                                                        <div class="form-group">
                                                            <label for="full_name">Full
                                                                name</label>
                                                            <input type="text" name="full_name" id="full_name"
                                                                   class="form-control form-control-sm"
                                                                   value="{{ old('full_name', $client->full_name) }}">
                                                        </div>
                                                    @endif
                                                    <div class="row">
                                                        <div class="col-md-12 col-lg-6">
                                                            <div class="form-group">
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <label for="client_number_2">Phone
                                                                            number</label>
                                                                    </div>
                                                                    <div class="col-2">
                                                                        <a href="https://wa.me/{{$client->client_number}}?text={{ __('Hello, how can we help you?') }}"
                                                                           target="_blank"
                                                                           class="btn btn-sm btn-outline-success float-right btn-mini"><i
                                                                                class="icofont icofont-social-whatsapp"></i></a>
                                                                    </div>
                                                                </div>
                                                                <input type="text" name="client_number"
                                                                       id="client_number"
                                                                       class="form-control form-control-sm @error('client_number') form-control-danger @enderror"
                                                                       value="{{ old('client_number', $client->client_number) }}"
                                                                       @if($client->client_number) @can('cant-update-field') readonly @endcan
                                                                    @endif
                                                                >
                                                                @error('client_number')
                                                                <span class="invalid-feedback" role="alert">
                                    <strong class="text-danger">{{ $message }}</strong>
                                  </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 col-lg-6">
                                                            <div class="form-group">
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <label for="client_number_2">Phone
                                                                            number 2</label>
                                                                    </div>
                                                                    <div class="col-2">
                                                                        <a href="https://wa.me/{{$client->client_number_2}}?text={{ __('Hello, how can we help you?') }}"
                                                                           target="_blank"
                                                                           class="btn btn-sm btn-outline-success float-right btn-mini"><i
                                                                                class="icofont icofont-social-whatsapp"></i></a>
                                                                    </div>
                                                                </div>
                                                                <input type="text" name="client_number_2"
                                                                       id="client_number_2"
                                                                       class="form-control form-control-sm @error('client_number_2') form-control-danger @enderror"
                                                                       value="{{ old('client_number_2', $client->client_number_2) }}"
                                                                       @if($client->client_number_2) @can('cant-update-field') readonly @endcan
                                                                    @endif
                                                                >
                                                                @error('client_number_2')
                                                                <span class="invalid-feedback" role="alert">
                                    <strong class="text-danger">{{ $message }}</strong>
                                  </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-12 col-lg-6">
                                                            <div class="row">
                                                                <div class="col">
                                                                    <label for="client_email">E-mail</label>
                                                                </div>
                                                                <div class="col">
                                                                    <a href="mailto:{{ $client->client_email }}"
                                                                       class="btn btn-sm btn-outline-info float-right btn-mini"><i
                                                                            class="ti-email"></i></a>
                                                                </div>
                                                            </div>
                                                            <input type="email" name="client_email"
                                                                   id="client_email"
                                                                   class="form-control form-control-sm @error('client_email') form-control-danger @enderror"
                                                                   value="{{ old('client_email', $client->client_email) }}">
                                                            @error('client_email')
                                                            <span class="invalid-feedback" role="alert">
                                    <strong class="text-danger">{{ $message }}</strong>
                                  </span>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group col-md-12 col-lg-6">
                                                            <label for="client_email_2">E-mail
                                                                2</label>
                                                            <a href="mailto:{{ $client->client_email_2 }}"
                                                               class="btn btn-sm btn-outline-info float-right btn-mini"><i
                                                                    class="ti-email"></i></a>
                                                            <input type="email" name="client_email_2"
                                                                   id="client_email_2"
                                                                   class="form-control form-control-sm @error('client_email_2') form-control-danger @enderror"
                                                                   value="{{ old('client_email_2', $client->client_email_2) }}">
                                                            @error('client_email_2')
                                                            <span class="invalid-feedback" role="alert">
                                    <strong class="text-danger">{{ $message }}</strong>
                                  </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group input-group-sm col-md-12 col-lg-6">
                                                            <label for="country">Country</label>
                                                            <select class="js-country-all form-control form-control-sm"
                                                                    multiple="multiple" name="country[]" id="country">
                                                                <option></option>
                                                                @php $clientCountry = collect($client->country)->toArray() @endphp
                                                                @foreach($clientCountry as $lang)
                                                                    <option value="{{ $lang }}" selected>
                                                                        {{ $lang }}</option>
                                                                @endforeach
                                                            </select>
                                                            @if(is_null($client->country))
                                                                <div class="col-form-label">
                                                                    Old: {{ $client->getRawOriginal('country') ?? '' }}</div>
                                                            @endif
                                                        </div>
                                                        <div class="form-group input-group-sm col-md-12 col-lg-6">
                                                            <label for="nationality">Nationality</label>
                                                            <select
                                                                class="js-nationality-all form-control form-control-sm"
                                                                multiple="multiple" name="nationality[]"
                                                                id="nationality">
                                                                <option></option>
                                                                @php $clientNationality = collect($client->nationality)->toArray() @endphp
                                                                @foreach($clientNationality as $nat)
                                                                    <option value="{{ $nat }}" selected>
                                                                        {{ $nat }}</option>
                                                                @endforeach
                                                            </select>
                                                            @if(is_null($client->country))
                                                                <div class="col-form-label">
                                                                    Old: {{ $client->getRawOriginal('nationality') ?? '' }}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="form-group input-group-sm">
                                                        <label for="lang">Languages</label>
                                                        <select class="js-language-all form-control form-control-sm"
                                                                multiple="multiple" name="lang[]" id="lang">
                                                            <option></option>
                                                            @php $clientLang = collect($client->lang)->toArray() @endphp
                                                            @foreach( $clientLang as $lang)
                                                                <option vlaue="{{ $lang }}"
                                                                        selected> {{ $lang }} </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md">
                                                    <div class="row">
                                                        <div class="form-group col-md-12 col-lg-6">
                                                            <label for="status">Status</label>
                                                            <select name="status" id="status"
                                                                    class="form-control form-control-sm">
                                                                <option value="0" selected disabled> -- Client status
                                                                    --
                                                                </option>
                                                                <option
                                                                    value="1" {{ old('status', $client->status) == 1 ? 'selected' : '' }}>
                                                                    New Lead
                                                                </option>
                                                                <option
                                                                    value="8" {{ old('status', $client->status) == 8 ? 'selected' : '' }}>
                                                                    No Answer
                                                                </option>
                                                                <option
                                                                    value="12" {{ old('status', $client->status) == 12 ? 'selected' : '' }}>
                                                                    In progress
                                                                </option>
                                                                <option
                                                                    value="3" {{ old('status', $client->status) == 3 ? 'selected' : '' }}>
                                                                    Potential
                                                                    appointment
                                                                </option>
                                                                <option
                                                                    value="4" {{ old('status', $client->status) == 4 ? 'selected' : '' }}>
                                                                    Appointment
                                                                    set
                                                                </option>
                                                                <option
                                                                    value="10" {{ old('status', $client->status) == 10 ? 'selected' : '' }}>
                                                                    Appointment
                                                                    follow up
                                                                </option>
                                                                <option
                                                                    value="5" {{ old('status', $client->status) == 5 ? 'selected' : '' }}>
                                                                    Sold
                                                                </option>
                                                                <option
                                                                    value="13" {{ old('status', $client->status) == 13 ? 'selected' : '' }}>
                                                                    Unreachable
                                                                </option>
                                                                <option
                                                                    value="7" {{ old('status', $client->status) == 7 ? 'selected' : '' }}>
                                                                    Not interested
                                                                </option>
                                                                <option
                                                                    value="11" {{ old('status', $client->status) == 11 ? 'selected' : '' }}>
                                                                    Low budget
                                                                </option>
                                                                <option
                                                                    value="9" {{ old('status', $client->status) == 9 ? 'selected' : '' }}>
                                                                    Wrong Number
                                                                </option>
                                                                <option
                                                                    value="14" {{ old('status', $client->status) == 14 ? 'selected' : '' }}>
                                                                    Unqualified
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-12 col-lg-6">
                                                            <label for="priority">Priority</label>
                                                            <select name="priority" id="priority"
                                                                    class="form-control form-control-sm">
                                                                <option value="0" selected disabled> Priority
                                                                </option>
                                                                <option
                                                                    value="1" {{ $client->priority == '1' ? 'selected' : '' }}>
                                                                    Low
                                                                </option>
                                                                <option
                                                                    value="2" {{ $client->priority == '2' ? 'selected' : '' }}>
                                                                    Medium
                                                                </option>
                                                                <option
                                                                    value="3" {{ $client->priority == '3' ? 'selected' : '' }}>
                                                                    High
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-12 col-lg-6">
                                                            <label for="budget_request">Budget</label>
                                                            <select name="budget_request[]" id="budget_request"
                                                                    class="js-budgets-all form-control form-control-sm"
                                                                    multiple>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-12 col-lg-6">
                                                            <label for="rooms_request">Request</label>
                                                            <select class="js-rooms-all form-control form-control-sm"
                                                                    name="rooms_request[]" id="rooms_request" multiple>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="requirements_request">Requirement</label>
                                                        <select name="requirements_request[]" id="requirements_request"
                                                                class="js-requirements-all form-control form-control-sm"
                                                                multiple>
                                                        </select>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-12 col-lg-6 col-xl-4">
                                                            <label for="source">Source</label>
                                                            <select name="source_id" id="source"
                                                                    class="form-control form-control-sm @error('source_id') form-control-danger @enderror">
                                                                <option value="" selected disabled> -- Select source --
                                                                </option>
                                                                @foreach($sources as $source)
                                                                    <option value="{{ $source->id }}"
                                                                        {{ $client->source_id == $source->id ? 'selected' : '' }}>
                                                                        {{ $source->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('source_id')
                                                            <span class="invalid-feedback" role="alert">
                                  <strong class="text-danger">{{ $message }}</strong>
                                </span>
                                                            @enderror
                                                        </div>

                                                        <div class="form-group col-md-12 col-lg-6 col-xl-4">
                                                            <label for="">Campaign name</label>
                                                            <input type="text" class="form-control form-control-sm"
                                                                   value="{{ $client->campaigne_name }}">
                                                        </div>

                                                        <div class="form-group col-md-12 col-lg-6 col-xl-4">
                                                            <label for="source">Agency</label>
                                                            <select name="agency_id" id="agency"
                                                                    class="form-control form-control-sm @error('agency_id') form-control-danger @enderror">
                                                                <option value="" selected disabled> -- Select agency --
                                                                </option>
                                                                @foreach($agencies as $agency)
                                                                    <option value="{{ $agency->id }}"
                                                                        {{ $client->agency_id == $agency->id ? 'selected' : '' }}>
                                                                        {{ $agency->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('agency_id')
                                                            <span class="invalid-feedback" role="alert">
                                  <strong class="text-danger">{{ $message }}</strong>
                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md">
                                                            <label for="appointment_date">Date
                                                                of
                                                                coming</label>
                                                            <input name="appointment_date" id="appointment_date"
                                                                   class="form-control form-control-sm"
                                                                   value="{{ old('appointment_date', optional($client->appointment_date)->format('Y-m-d') ) }}"
                                                                   type="date"/>
                                                        </div>
                                                        <div class="form-group col-md">
                                                            <label for="duration_stay">Duration of Stay</label>
                                                            <select name="duration_stay" id="duration_stay"
                                                                    class="form-control form-control-sm">
                                                                <option selected> -- Select duration of Stay --</option>
                                                                <option
                                                                    value="1" {{ old('duration_stay', $client->duration_stay) == 1 ? 'selected' : '' }}>
                                                                    1 Day
                                                                </option>
                                                                <option
                                                                    value="2" {{ old('duration_stay', $client->duration_stay) == 2 ? 'selected' : '' }}>
                                                                    2 Days
                                                                </option>
                                                                <option
                                                                    value="3" {{ old('duration_stay', $client->duration_stay) == 3 ? 'selected' : '' }}>
                                                                    3 Days
                                                                </option>
                                                                <option
                                                                    value="4" {{ old('duration_stay', $client->duration_stay) == 4 ? 'selected' : '' }}>
                                                                    4 Days
                                                                </option>
                                                                <option
                                                                    value="5" {{ old('duration_stay', $client->duration_stay) == 5 ? 'selected' : '' }}>
                                                                    5 Days
                                                                </option>
                                                                <option
                                                                    value="6" {{ old('duration_stay', $client->duration_stay) == 6 ? 'selected' : '' }}>
                                                                    6 Days
                                                                </option>
                                                                <option
                                                                    value="7" {{ old('duration_stay', $client->duration_stay) == 7 ? 'selected' : '' }}>
                                                                    7 Days
                                                                </option>
                                                                <option
                                                                    value="8" {{ old('duration_stay', $client->duration_stay) == 8 ? 'selected' : '' }}>
                                                                    8 Days
                                                                </option>
                                                                <option
                                                                    value="9" {{ old('duration_stay', $client->duration_stay) == 9 ? 'selected' : '' }}>
                                                                    9 Days
                                                                </option>
                                                                <option
                                                                    value="10" {{ old('duration_stay', $client->duration_stay) == 10 ? 'selected' : '' }}>
                                                                    10 Days
                                                                </option>
                                                                <option
                                                                    value="11" {{ old('duration_stay', $client->duration_stay) == 11 ? 'selected' : '' }}>
                                                                    11 Days
                                                                </option>
                                                                <option
                                                                    value="12" {{ old('duration_stay', $client->duration_stay) == 12 ? 'selected' : '' }}>
                                                                    12 Days
                                                                </option>
                                                                <option
                                                                    value="13" {{ old('duration_stay', $client->duration_stay) == 13 ? 'selected' : '' }}>
                                                                    13 Days
                                                                </option>
                                                                <option
                                                                    value="14" {{ old('duration_stay', $client->duration_stay) == 14 ? 'selected' : '' }}>
                                                                    14 Days
                                                                </option>
                                                                <option
                                                                    value="15" {{ old('duration_stay', $client->duration_stay) == 15 ? 'selected' : '' }}>
                                                                    16 Days
                                                                </option>
                                                                <option
                                                                    value="30" {{ old('duration_stay', $client->duration_stay) == 30 ? 'selected' : '' }}>
                                                                    1 Month
                                                                </option>
                                                                <option
                                                                    value="60" {{ old('duration_stay', $client->duration_stay) == 60 ? 'selected' : '' }}>
                                                                    2 Months
                                                                </option>
                                                                <option
                                                                    value="90" {{ old('duration_stay', $client->duration_stay) == 90 ? 'selected' : '' }}>
                                                                    3 Months
                                                                </option>
                                                                <option
                                                                    value="99" {{ old('duration_stay', $client->duration_stay) == 99 ? 'selected' : '' }}>
                                                                    Unspecified
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="description">Note:</label>
                                                <textarea name="description"
                                                          id="description">{{ old('description', $client->description) }}</textarea>
                                            </div>
                                            <!-- end of table col-lg-6 -->
                                    </div>
                                    <div class="card-footer">
                                        <div>
                                            <button type="submit" class="btn btn-primary waves-effect waves-light">
                                                Save <i class="ti-save"></i></button>
                                            <a href="{{ redirect()->route('clients.index') }}" id="edit-cancel"
                                               class="btn btn-danger waves-effect">Cancel</a>
                                        </div>
                                    </div>
                                    </form>
                                    <!-- personal card end-->
                                </div>
                                <!-- tab pane personal end -->
                            </div>
                            <!-- tab panel personal end -->
                            <!-- tab pane Note start -->
                            <div class="tab-pane" id="bnote" role="tabpanel">
                                @include('clients.task-note')
                            </div>
                            <!-- tab pane Note end -->
                            <!-- tab pane Task start -->
                            <div class="tab-pane" id="bdocs" role="tabpanel">
                                @include('clients.documents.index')
                            </div>
                            <!-- tab pane Task end -->
                            <!-- tab content end -->
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-2">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-header-text"> Ownded by</h5>
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
                                <br>
                                @can('share-client')
                                    <form id="trans-to-sales">
                                        @csrf
                                        <div class="form-group form-group-sm
                ">
                                            <select class="form-control form-control-sm" name="inCharge" id="inCharge">
                                                @foreach($users as $user)
                                                    <option
                                                        value="{{ $user->id }}" {{ $client->user_id == $user->id ? 'selected' : '' }}>
                                                        {{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="submit"
                                                class="btn btn-sm btn-outline-primary form-control form-control-sm">
                                            Change <i
                                                class="ti-share-alt"></i></button>
                                    </form>
                                @endcan
                            </div>
                        </div>

                        <!-- Transfer to invoice -->
                        @can('transfer-lead-to-deal')
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-header-text">Transfer to deal</h5>
                                </div>
                                <div class="card-block">
                                    <form action="{{ route('sales.transfer') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="clientId" value="{{ $client->id }}">
                                        <button class="btn btn-outline-success btn-sm form-control" id="tran-to">Done <i
                                                class="ti-arrow-right"></i></button>
                                    </form>
                                </div>
                            </div>
                        @endcan
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-header-text">History</h5>
                            </div>
                            <div class="card-block">

                                <p><b>Modified By: </b>{{ $client->updateBy->name }}</p>
                                <p>
                                    <b>Created
                                        time: </b>{{ Carbon\Carbon::parse($client->created_at)->format('Y-m-d H:m') }}
                                </p>
                                <p><b>Modified
                                        time: </b>{{ Carbon\Carbon::parse($client->updated_at)->format('Y-m-d H:m') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Page body end -->
            </div>
        </div>
        <!-- Create modal end -->
        <!-- Delete file modal start -->
        <div class="modal fade" id="deleteModal" tabindex="-1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete file</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="/documents" method="POST" id="deleteForm">
                        @csrf
                        @method('DELETE')
                        <div class="modal-body p-b-0">
                            <p>Are sur you want to delete?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Delete <i class="ti-trash-alt"></i></button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Delete file modal end -->
        <!-- assigne modal start -->
        <div class="modal fade" id="assignModal" tabindex="-1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Assigne to a user</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="assignForm">
                        @csrf
                        <div class="modal-body p-b-0">
                            <input type="hidden" name="task_assigned_id" id="task_assigned_id">
                            <div class="form-group">
                                <select class="form-control" name="assigned_user" id="assigned_user">
                                    <option value="" selected>-- Select user --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Save <i class="ti-save-alt"></i></button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Edit modal end -->
    @endsection
