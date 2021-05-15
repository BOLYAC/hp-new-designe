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
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('invoices.index') }}"></a>{{ __('Sales list') }}</li>
    <li class="breadcrumb-item">{{ __('Sales page') }}</li>
    <li class="breadcrumb-item">{{ __('Lead name') }}: <small class="ml-5">{{ $invoice->client_name }}</small></li>
@endsection
@section('content')

    <div class="container-fluid">
        @include('partials.flash-message')
        <div class="row">
            <!-- Left column start -->
            <div class="col-md-8">
                <!-- Flying Word card start -->
                <div class="card">
                    <div class="card-header b-t-primary b-b-primary p-2 d-flex justify-content-between">
                        <h5>Project: {{ $invoice->project }}</h5>
                        @can('invoice-edit')
                            <a class="btn btn-sm btn-primary"
                               href="{{ route('invoices.edit', $invoice) }}">{{ __('Edit') }}</a>
                        @endcan
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <table class="table m-0">
                                    <tbody>
                                    <tr>
                                        <th scope="row">{{ __('Province/Country') }}</th>
                                        <td>{{ $invoice->country_province ?? ''}}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">{{ __('Section/Plot') }}</th>
                                        <td>{{ $invoice->block_num ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">{{ __('Block No') }}</th>
                                        <td>{{ $invoice->room_number ?? ''}}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">{{ __('No of Rooms') }}</th>
                                        <td>{{ $invoice->room_number ?? ''}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-lg-6">
                                <table class="table m-0">
                                    <tbody>
                                    <tr>
                                        <th scope="row">{{ __('Floor No:') }}</th>
                                        <td>{{ $invoice->floor_number ?? ''}}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">{{ __('Gross M²') }}</th>
                                        <td>{{ $invoice->gross_square ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">{{ __('Flat No') }}</th>
                                        <td>{{ $invoice->flat_num ?? ''}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <ul>
                                    <li><strong>{{ __('Price:') }}</strong></li>
                                    <li><strong>{{ __('Cash/Installment:') }}</strong></li>
                                    <li><strong>{{ __('Month:') }}</strong></li>
                                </ul>
                            </div>
                            <div class="col-6">
                                <ul class="f-right text-right">
                                    <li>{{ number_format($invoice->price, 2) }} {{ $invoice->currency }}</li>
                                    <li>{{ number_format($invoice->installment, 2) }} {{ $invoice->currency }}</li>
                                    <li>{{ $invoice->month }} {{ __('Month') }}</li>
                                </ul>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <h6><strong>{{ __('Total') }}</strong></h6>
                            </div>
                            <div class="col-6">
                                <h6 class="f-right text-right">{{ number_format($invoice->price - $invoice->installment , 2) }} {{ $invoice->currency }}</h6>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <ul>
                                    <li><strong>{{ __('Invoice created:') }}</strong></li>
                                    <li>{{ $invoice->created_at->format('m/d/Y') }}</li>
                                    <li><strong>{{ __('Status:') }}</strong></li>
                                    <li>{{ $invoice->status == 2 ? 'Partially paid' : 'Paid' }}</li>
                                </ul>
                            </div>
                            <div class="col-6">
                                <ul class="f-right text-right">
                                    <li><strong>{{ __('Amount due:') }}</strong></li>
                                    <li>{{ number_format($amount, 2) ?? '0.00' }} {{ $invoice->currency }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Flying Word card end -->
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header b-b-primary">
                        <h5 class="text-muted">{{ __('Client:') }} {{ $invoice->client->full_name }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <ul>
                                    <li><strong>{{ __('Nationality:') }}</strong></li>
                                    <li>{{ $invoice->nationality ?? '' }}</li>
                                    <br>
                                    <li><strong>{{ __('Passport or ID:') }}</strong></li>
                                    <li>{{ $invoice->passport_id }}</li>
                                    <br>
                                    <li><strong>{{ __('Address:') }}</strong></li>
                                    <li>{{ $invoice->address }}</li>
                                    <br>

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 text-right">
                    <button type="submit" class="btn btn-primary"
                            data-toggle="modal"
                            data-target="#paymentModal">{{ __('Register payment') }}
                    </button>
                </div>
            </div>
        </div>

        @include('invoices._paymentList')

    </div>
    <!-- Page body end -->
    <!-- Edit modal start -->
    <div class="modal fade" id="paymentModal" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Add Payment') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <hr>
                <div class="text-center">
                    <h2>{{ __('Amount due') }}</h2>
                    <h3>{{ number_format($amount, 2) ?? '0.00' }} {{ $invoice->currency }}</h3>
                </div>
                <form action="{{ route('payments.store') }}" method="POST" id="paymentForm">
                    @csrf
                    <div class="modal-body p-b-0">
                        <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
                        <input type="hidden" name="user_id" value="{{ $invoice->user_id }}">
                        <div class="form-group">
                            <label for="">{{ __('Amount in') }} {{ $invoice->currency }}</label>
                            <input type="number" name="amount" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">{{ __('Payment date') }}</label>
                            <input type="date" name="payment_date" class="form-control">
                        </div>
                        <div class="form-group">
                            <select name="payment_source" id="source" class="form-control">
                                <option value="bank">{{ __('Bank') }}</option>
                                <option value="cash">{{ __('Cash') }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="description">{{ __('Description') }}</label>
                            <textarea class="summernote" name="description" id="description"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{__('Save')}} <i class="ti-save-alt"></i>
                        </button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Edit modal end -->
@endsection

