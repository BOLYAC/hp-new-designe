@extends('layouts.vertical.master')
@section('title', '| Sales page')
@section('style_before')
    <!-- Datatables.css -->
    <link rel="stylesheet" href="{{ asset('assets/css/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/datatable-extension.css') }}">
    <!-- Plugins css start-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/summernote.css') }}">
    <style>
        .select2-container--open {
            z-index: 999999999999999 !important;
        }
    </style>
@endsection

@section('script')
    <!-- Datatables.js -->
    <script src="{{asset('assets/js/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables/datatable-extension/dataTables.bootstrap4.min.js')}}"></script>
    <!-- Plugins JS start-->
    <script src="{{ asset('assets/js/editor/summernote/summernote.js') }}"></script>
    <script src="{{ asset('assets/js/editor/summernote/summernote.custom.js') }}"></script>
    <scritp>
        <script>
            $('select[name="currency"]').on('change', function () {
                var currency = $(this).val();
                $('.currIco').html('');
                switch (currency) {
                    case "TRY":
                        $('.currIco').append(`₺`)
                        break;
                    case "USD":
                        $('.currIco').append(`$`)
                        break;
                    case "EUR":
                        $('.currIco').append(`€`)
                        break;
                    default:
                        $('.currIco').append(``)
                }
            });

            function percentage(num, per) {
                return (num / 100) * per;
            }

            $('input[name="month"], input[name="price"], input[name="installment"]').on('change keyup', function () {
                let month = $('#month').val();
                let price = $('#price').val();
                let installment = $('#installment').val();
                let total = (price - installment) / month;
                let granTotal = Math.round((total + Number.EPSILON) * 100) / 100
                $('#monthly_payment').val(granTotal)
            });
            $('input[name="commission_rate"]').on('change keyup', function () {
                let rate = $('#commission_rate').val();
                //let total = $('#commission_total').val();
                let price = $('#price').val();
                let total = percentage(rate, price)
                $('#commission_total').val(total)
            });
            $('input[name="commission_total"]').on('change keyup', function () {
                let total = $('#commission_total').val();
                let price = $('#price').val();
                let rate = Math.round(((total / price) * 100)) + "%";
                $('#commission_rate').val(rate)
            });
        </script>
    </scritp>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('invoices.index') }}">{{ __('Sales list') }}</a></li>
    <li class="breadcrumb-item">{{ __('Sales page') }}</li>
    <li class="breadcrumb-item">{{ __('Lead name') }}: <small class="ml-5">{{ $invoice->client_name }}</small></li>
@endsection

@section('content')

    <div class="container-fluid">
        <div class="row">
            <!-- Left column start -->
            <div class="col-lg-12 col-xl-9">
                <!-- Flying Word card start -->
                <div class="card b-t-primary">
                    <form action="{{ route('invoices.update', $invoice) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col">
                                    <label for="nationality">Nationality</label>
                                    <input class="form-control form-control-sm" name="nationality"
                                           id="nationality"
                                           value="{{ old('nationality', $invoice->nationality) }}">
                                </div>
                                <div class="form-group col">
                                    <label for="passport_id">Passport or ID number</label>
                                    <input class="form-control form-control-sm" name="passport_id"
                                           id="passport_id"
                                           value="{{ old('passport_id', $invoice->passport_id) }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="address">Address</label>
                                <input class="form-control form-control-sm" name="address"
                                       id="address" value="{{ old('address', $invoice->address) }}">
                            </div>
                            <div class="row">
                                <div class="form-group col-12">
                                    <label for="project">Project name</label>
                                    <input class="form-control form-control-sm" name="project"
                                           id="project" value="{{ old('project', $invoice->project) }}">
                                </div>
                                <div class="form-group col">
                                    <label for="country_province">Province/Country</label>
                                    <input class="form-control form-control-sm" name="country_province"
                                           id="country_province"
                                           value="{{ old('country_province', $invoice->country_province) }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col">
                                    <label for="section_plot">Section/Plot</label>
                                    <input class="form-control form-control-sm" name="section_plot"
                                           id="section_plot"
                                           value="{{ old('section_plot', $invoice->section_plot) }}">
                                </div>
                                <div class="form-group col">
                                    <label for="block_num">Block No</label>
                                    <input class="form-control form-control-sm" name="block_num"
                                           id="block_num" value="{{ old('block_num', $invoice->block_num) }}">
                                </div>
                                <div class="form-group col">
                                    <label for="room_number">No of room</label>
                                    <input class="form-control form-control-sm" name="room_number"
                                           id="room_number"
                                           value="{{ old('room_number', $invoice->room_number) }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col">
                                    <label for="floor_number">Floor No</label>
                                    <input class="form-control form-control-sm" name="floor_number"
                                           id="floor_number"
                                           value="{{ old('floor_number', $invoice->floor_number) }}">
                                </div>
                                <div class="form-group col">
                                    <label for="gross_square">Gross M²</label>
                                    <input class="form-control form-control-sm" name="gross_square"
                                           id="gross_square"
                                           value="{{ old('gross_square', $invoice->gross_square) }}">
                                </div>
                                <div class="form-group col">
                                    <label for="flat_num">Flat No</label>
                                    <input class="form-control form-control-sm" name="flat_num"
                                           id="flat_num" value="{{ old('flat_num', $invoice->flat_num) }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="currency">Currency</label>
                                <select class="form-control" name="currency" id="currency">
                                    <option value="" selected>-- Select currency</option>
                                    <option
                                        value="TRY" {{ old('currency', $invoice->currency) == 'TRY' ? 'selected' : '' }}>
                                        TRY
                                    </option>
                                    <option
                                        value="USD" {{ old('currency', $invoice->currency) == 'USD' ? 'selected' : '' }}>
                                        USD
                                    </option>
                                    <option
                                        value="EUR" {{ old('currency', $invoice->currency) == 'EUR' ? 'selected' : '' }}>
                                        EUR
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Price') }}</label>
                                <div class="input-group">
                                    <input class="form-control" type="text"
                                           placeholder="{{ __('Price') }}"
                                           name="price"
                                           id="price" value="{{ old('price', $invoice->price) }}">
                                    <div class="input-group-append"><span class="input-group-text currIco"></span></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Cash/Installment') }}</label>
                                <div class="input-group">
                                    <input class="form-control" type="text"
                                           placeholder="{{ __('Cash/Installment') }}"
                                           name="installment"
                                           id="installment" value="{{ old('installment', $invoice->installment) }}">
                                    <div class="input-group-append"><span class="input-group-text currIco"
                                                                          id="basic-addon3"></span></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Month') }}</label>
                                <div class="input-group">
                                    <input class="form-control" type="text"
                                           placeholder="{{ __('Month') }}"
                                           name="month"
                                           id="month" value="{{ old('month', $invoice->month) }}">
                                    <div class="input-group-append"><span class="input-group-text currIco"
                                                                          id="basic-addon3"><i
                                                class="icon-time"></i></span></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label" for="note">{{ __('Note') }}</label>
                                <textarea name="note" id="note" class="summernote"
                                          placeholder="Note">{{ old('note', $invoice->note) }}</textarea>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-sm-12 text-right">
                                    <button type="submit"
                                            class="btn btn-primary m-r-10">{{ __('Submit Details') }}</button>
                                    <a href="{{ url()->previous() }}" class="btn btn-default">{{ __('Cancel') }}</a>
                                </div>
                            </div>
                        </div>

                </div>
                <!-- Flying Word card end -->
            </div>
            <!-- Left column end -->
            <!-- Right column start -->
            <div class="col-lg-12 col-xl-3">
                <!-- Recent Searches card start -->
                <div class="card job-right-header">
                    <div class="card-header b-b-primary">
                        <h5 class="text-muted">Client information</h5>
                    </div>
                    <div class="card-body">
                        <div>
                            {{ __('Full name:') }}
                            <p>{{ $invoice->client_name }}</p>
                        </div>
                    </div>
                    <div class="card-header b-b-primary">
                        <h5>{{ __('Owner') }}</h5>
                    </div>
                    <div class="card-body">
                        <div>
                            {{ __('Deal owner:') }}
                            <span class="label label-success">{{ $invoice->owner_name }}</span>
                        </div>
                        <br>
                        <div>
                            {{ __('Seller(s):') }}
                            @php $sellRep = collect($invoice->sells_name)->toArray() @endphp
                            @foreach( $sellRep as $name)
                                <span class="label label-inverse">{{ $name }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- Recent Searches card end -->
                <div class="card job-right-header">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="total">{{ __('Payment per month') }}</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="monthly_payment"
                                       value="{{ old('monthly_payment', $invoice->monthly_payment) }}">
                                <div class="input-group-append"><span class="input-group-text currIco"
                                                                      id="basic-addon3"></span></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="commission_rate">{{ __('Commission rate') }}</label>
                            <div class="input-group">
                                <input type="text" class="form-control"
                                       name="commission_rate" id="commission_rate"
                                       value="{{ old('commission_rate', $invoice->commission_rate) }}">
                                <div class="input-group-append"><span class="input-group-text currIco"
                                                                      id="basic-addon3"></span></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="commission_total">{{__ ('Total commission')}}</label>
                            <div class="input-group">
                                <input type="text" class="form-control"
                                       name="commission_total" id="commission_total"
                                       value="{{ old('commission_total', $invoice->commission_total) }}">
                                <div class="input-group-append"><span class="input-group-text currIco"
                                                                      id="basic-addon3"></span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Right column end -->
            </form>
        </div>
    </div>

@endsection

