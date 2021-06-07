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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">

    <style>
        .select2-container {
            width: 100% !important;
            padding: 0;
        }

        .jconfirm.jconfirm-supervan .jconfirm-box div.jconfirm-content {
            overflow: hidden
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
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
            if (level == 4) {
                $.confirm({
                    title: '{{ __('Reservation From') }}',
                    theme: 'supervan',
                    columnClass: 'col-md-8',
                    bootstrapClasses: {
                        container: 'container',
                        containerFluid: 'container-fluid',
                        row: 'row',
                    },
                    content: '' +
                        '<form class="reservationForm" enctype="multipart/form-data">' +
                        '@csrf' +
                        '<div class="row">' +
                        '<div class="form-group col-md-12">' +
                        '<label> {{ __('Project name') }} </label>' +
                        '<select id="project" name="project_id" class="project_id form-control form-control-sm">' +
                        '<option value="">-- {{ __('Select Project') }} --</option>' +
                        '@foreach($projects as $project)' +
                        '<option value="{{ $project->id }}">{{ $project->project_name }}</option>' +
                        '@endforeach' +
                        '</select>' +
                        '<div class="form-group">' +
                        '<label for="property">{{ __('Select Property:') }}</label>' +
                        '<select name="property" class="property form-control form-control-sm">' +
                        '<option value="">-- {{ __('Select Property') }} --</option>' +
                        '</select>' +
                        '</div>' +
                        '</div>' +
                        '<div class="form-group col-md-6">' +
                        '<label> {{ __('Province/Country') }} </label>' +
                        '<input type="text" class="country_province form-control form-control-sm" name="country_province" required/>' +
                        '</div>' +
                        '<div class="form-group col-md-6">' +
                        '<label> {{ __('Flat Num') }} </label>' +
                        '<input type="text" class="flat_num form-control form-control-sm" name="flat_num" required/>' +
                        '</div>' +
                        '<div class="form-group col-md-6">' +
                        '<label> {{ __('Gross M²') }} </label>' +
                        '<input type="text" class="gross_square form-control form-control-sm" name="gross_square" required/>' +
                        '</div>' +
                        '<div class="form-group col-md-6">' +
                        '<label> {{ __('Section/Plot') }} </label>' +
                        '<input type="text" class="section_plot form-control form-control-sm" name="section_plot" required/>' +
                        '</div>' +
                        '<div class="form-group col-md-6">' +
                        '<label> {{ __('Block Num') }} </label>' +
                        '<input type="text" class="block_num form-control form-control-sm" name="block_num" required/>' +
                        '</div>' +
                        '<div class="form-group col-md-6">' +
                        '<label> {{ __('Floor Num') }} </label>' +
                        '<input type="text" class="floor_number form-control form-control-sm" name="floor_number" required/>' +
                        '</div>' +
                        '<div class="form-group col-md-6">' +
                        '<label> {{ __('Num of Rooms') }} </label>' +
                        '<input type="text" class="room_number form-control form-control-sm" name="room_number" required/>' +
                        '</div>' +
                        '<div class="form-group col-md-6">' +
                        '<label> {{ __('Reservation amount') }} </label>' +
                        '<input type="text" class="reservation_amount form-control form-control-sm" name="reservation_amount" required/>' +
                        '</div>' +
                        '<div class="form-group col-md-6">' +
                        '<label> {{ __('Sale Price') }} </label>' +
                        '<input type="text" class="sale_price form-control form-control-sm" name="sale_price" required/>' +
                        '</div>' +
                        '<div class="form-group col-md-6">' +
                        '<label> {{ __('File upload') }} </label>' +
                        '<input type="file" class="file_path form-control form-control-sm" name="file_path" required/>' +
                        '</div>' +
                        '</div>' +
                        '</form>',
                    buttons: {
                        formSubmit: {
                            text: 'Submit',
                            btnClass: 'btn-blue',
                            action: function () {
                                var name = this.$content.find('.project_id').val();
                                if (!name) {
                                    $.alert('provide a valid Project name');
                                    return false;
                                }
                                let project_id = this.$content.find('.project_id').val();
                                let project_name = this.$content.find('.project_id option:selected').val();
                                let country_province = this.$content.find('.country_province').val();
                                let flat_num = this.$content.find('.flat_num').text();
                                let gross_square = this.$content.find('.gross_square').val();
                                let section_plot = this.$content.find('.section_plot').val();
                                let block_num = this.$content.find('.block_num').val();
                                let floor_number = this.$content.find('.floor_number').val();
                                let room_number = this.$content.find('.room_number').val();
                                let reservation_amount = this.$content.find('.reservation_amount').val();
                                let sale_price = this.$content.find('.sale_price').val();
                                let file_path = this.$content.find('.file_path').val();
                                let lead_id = '{{ $lead->id }}'
                                $.ajax({
                                    url: "{{ route('deal.reservation.form') }}",
                                    type: "POST",
                                    data: {
                                        "_token": "{{ csrf_token() }}",
                                        lead_id,
                                        project_id,
                                        project_name,
                                        country_province,
                                        flat_num,
                                        gross_square,
                                        section_plot,
                                        floor_number,
                                        block_num,
                                        room_number,
                                        reservation_amount,
                                        sale_price,
                                        file_path
                                    },
                                    success: function () {
                                        notify('Reservation created, Stage change to reservation', 'success');
                                    },
                                    error: function () {
                                        notify('Ops!! Something wrong', 'danger');
                                    },
                                });

                            }
                        },
                        cancel: function () {
                            $("#stage_id").val({{ $lead->stage_id }});
                        },
                    },
                    onContentReady: function () {
                        // Get properties
                        let unit_type = this.$content.find('.flat_num');
                        let gross_sqm = this.$content.find('.gross_square');
                        let flat_type = this.$content.find('.floor_number');
                        let country_province = this.$content.find('.country_province');
                        let proj = this.$content.find('.project_id')
                        let property = this.$content.find('.property')
                        // List properties
                        proj.on('change', function (e) {
                            e.preventDefault();
                            let projectID = $(this).val();
                            if (projectID) {
                                $.ajax({
                                    url: '/project/single-project/' + projectID,
                                    type: "GET",
                                    dataType: "json",
                                    success: function (data) {
                                        country_province.empty();
                                        country_province.val(data);
                                    }
                                });
                                $.ajax({
                                    url: '/project/get-properties/' + projectID,
                                    type: "GET",
                                    dataType: "json",
                                    success: function (data) {
                                        property.empty();
                                        property.append('<option value="">-- {{ __('Select Property') }} --</option>');
                                        $.each(data, function (key, value) {
                                            property.append('<option value="' + key + '">' + value + '</option>');
                                        });


                                    }
                                });
                            } else {
                                property.empty();
                            }
                        });
                        // get single project
                        property.on('change', function (e) {
                            e.preventDefault();
                            let propertyID = $(this).val();
                            if (propertyID) {
                                $.ajax({
                                    url: '/properties/single-property/' + propertyID,
                                    type: "GET",
                                    dataType: "json",
                                    success: function (data) {

                                        unit_type.empty();
                                        gross_sqm.empty();
                                        flat_type.empty();
                                        unit_type.val(data[0]['unit_type']);
                                        gross_sqm.val(data[0]['gross_sqm']);
                                        flat_type.val(data[0]['flat_type']);
                                    }
                                });
                            } else {
                                property.empty();
                            }
                        });
                        // bind to events
                        var jc = this;
                        this.$content.find('form').on('submit', function (e) {
                            // if the user submits the form by pressing enter in the field.
                            e.preventDefault();
                            jc.$$formSubmit.trigger('click'); // reference the button and click it
                        });
                    }
                });
            } else {

                $.ajax({
                    type: 'POST',
                    url: '{{ route('stage.change') }}',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        stage_id: level,
                        lead_id: lead_id
                    },
                    success: function (r) {
                        if (level >= 7) {
                            $('#stage_id_shown').removeClass("invisible").addClass("visible")
                        } else {
                            $('#stage_id_shown').removeClass("visible").addClass("invisible")
                        }
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
    <li class="breadcrumb-item">{{ __('Lead name') }}: {{ $lead->client->full_name ?? $lead->full_name }}</li>
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
                                        @can('deal-stage')
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
                                        @endcan
                                        <option
                                            value="9" {{ old('stage_id', $lead->stage_id) == 9 ? 'selected' : '' }}>
                                            {{ __('Lost') }}
                                        </option>
                                    </select>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-12 col-lg-8">
                            @if($lead->invoice_id <> 0)
                            @else
                                @can('transfer-deal-to-invoice')
                                    <div id="stage_id_shown"
                                         class="{{ $lead->stage_id >= 7 ? 'visible': 'invisible' }}">
                                        <form action="{{ route('lead.convert.order', $lead) }}"
                                              onSubmit="return confirm('Are you sure?');"
                                              method="post" class="pull-right">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-success btn-sm"
                                                    >{{ __('Convert to invoice') }} <i class="ti-money"></i>
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
                </div>
                @include('partials.comments', ['subject' => $lead])
                @include('partials.events', ['subject' => $lead])
                @include('leads.partials.reservation')
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
                        <h5 class="d-inline-block">{{ __('Description') }}</h5>
                    </div>
                    <div class="card-body activity-social">
                        <ul>
                            <li>
                                <p>
                                    {!! $lead->description !!}
                                </p>
                            </li>
                            <li>
                                {{ __('Created at:') }}
                                {{ Carbon\Carbon::parse($lead->created_at)->format('Y-m-d H:i') }}
                            </li>
                        </ul>
                    </div>
                </div>
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
    <div class="modal fade" id="sign-in-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('New appointment') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('events.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-2">
                        <input type="hidden" name="client_id" value="{{ $lead->client_id}}">
                        <input type="hidden" name="lead_id" value="{{ $lead->id}}">
                        <input type="hidden" name="user_id" value="{{ auth()->id()}}">
                        <div class="row">
                            <div class="form-group input-group-sm col-md-6">
                                <label for="name">{{ __('Title') }}</label>
                                <input class="form-control form-control-sm"
                                       type="text"
                                       name="name"
                                       id="name">
                            </div>
                            <div class="form-group input-group-sm col-md-6">
                                <label for="event_date">{{ __('Date of appointment') }}</label>
                                <input id="event_date"
                                       class="form-control form-control-sm"
                                       name="event_date"
                                       type="datetime-local"
                                       required/>
                            </div>
                        </div>
                        <div class="form-group input-group-sm">
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
                        <div class="row">
                            <div class="form-group input-group-sm col-md-2">
                                <label for="currency">{{__('Currency')}}</label>
                                <select class="form-control form-control-sm" id="currency" name="currency">
                                    <option value="try">TRY</option>
                                    <option value="usd">USD</option>
                                    <option value="euro">EURO</option>
                                </select>
                            </div>
                            <div class="form-group col-md-10">
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
                                <select name="share_with[]" class="js-event-sells custom-select custom-select-sm"
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
                        @endcan
                        <div class="form-group input-group-sm">
                            <label for="description">{{ __('Description') }}</label>
                            <textarea type="text" name="description"
                                      id="description" class="form-control form-control-sm"></textarea>
                        </div>
                        <div class="form-group input-group-sm">
                            <label for="place">{{ __('Place') }}</label>
                            <input class="form-control form-control-sm" type="text" name="place" id="place">
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

