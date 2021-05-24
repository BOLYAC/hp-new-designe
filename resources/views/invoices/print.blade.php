@extends('layouts.vertical.master')
@section('title', '| Print')
@section('style_before')
    <!-- Plugins css start-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/print.css') }}">
@endsection

@section('script')
    <!-- Plugins JS start-->
    <script src="{{ asset('assets/js/counter/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('assets/js/counter/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('assets/js/counter/counter-custom.js') }}"></script>
    <script src="{{ asset('assets/js/print.js') }}"></script>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('invoices.index') }}">{{ __('Sales list') }}</a></li>
    <li class="breadcrumb-item">{{ __('Sales page') }}</li>
    <li class="breadcrumb-item">{{ __('Print') }}</li>
    <li class="breadcrumb-item">{{ __('Lead name') }}: <p class="f-w-600">{{ $invoice->client_name }}</p></li>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="invoice">
                            <div>
                                <div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="media">
                                                <div class="media-left"><img class="media-object img-60"
                                                                             src="{{ asset('assets/images/HASHIM_PROPERTY.png') }}"
                                                                             alt=""></div>
                                                <div class="media-body m-l-20">

                                                </div>
                                            </div>
                                            <!-- End Info-->
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="text-md-right">
                                                <h3>{{ __('Invoice #') }}<span
                                                        class="digits counter">{{ $invoice->id }}</span></h3>
                                                <p>{{ __('Date:') }} <span
                                                        class="digits"> {{ $invoice->created_at->format('Y-m-d') }}</span>
                                                </p>
                                            </div>
                                            <!-- End Title-->
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <!-- End InvoiceTop-->
                                <div class="row">
                                    <div class="col-md-11">
                                        <div class="media">
                                            <div class="media-left"><img class="media-object rounded-circle img-60"
                                                                         src="{{ asset('storage/' . $invoice->user->image_path)  }}"
                                                                         alt=""></div>
                                            <div class="media-body m-l-20 row">
                                                <div class="col-4">
                                                    <h5 class="media-heading">{{ __('Sales owner') }}</h5>
                                                    <p><strong>{{ $invoice->user->name  }}</strong></p>
                                                </div>
                                                <div class="col-4">
                                                    <h5 class="media-heading">{{ __('Sales type') }}</h5>
                                                    <p><strong>{{ $invoice->user->name  }}</strong></p>
                                                </div>
                                                <div class="col-4">
                                                    <h5 class="media-heading">{{ __('Sales stage') }}</h5>
                                                    <p><strong>{{ $invoice->user->name  }}</strong></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="text-md-right" id="project">
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <!-- End Invoice Mid-->
                                <div>
                                    <div class="table-responsive invoice-table" id="table">
                                        <table class="table table-bordered table-striped">
                                            <tbody>
                                            <tr>
                                                <td class="item">
                                                    <h6 class="p-2 mb-0">{{ __('Source') }}</h6>
                                                </td>
                                                <td class="Hours">
                                                    <h6 class="p-2 mb-0">{{ __('Customer Name') }}</h6>
                                                </td>
                                                <td class="Rate">
                                                    <h6 class="p-2 mb-0">{{ __('Sales details') }}</h6>
                                                </td>
                                                <td class="subtotal">
                                                    <h6 class="p-2 mb-0">{{ __('Sales Price') }}</h6>
                                                </td>
                                                <td class="subtotal">
                                                    <h6 class="p-2 mb-0">{{ __('Commission Rate') }}</h6>
                                                </td>
                                                <td class="subtotal">
                                                    <h6 class="p-2 mb-0">{{ __('Commission') }}</h6>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p><strong>{{ $invoice->client->source->name }}</strong></p>
                                                </td>
                                                <td>
                                                    <p><strong>{{ $invoice->client->full_name }}</strong></p>
                                                </td>
                                                <td>
                                                    <p>
                                                        <strong>{{ __('Block N°:') }}</strong>{{ $invoice->block_num ?? '' }}
                                                        <strong>{{ __('No of Rooms:') }}</strong>{{ $invoice->floor_number ?? ''}}
                                                        <strong>{{ __('Floor No:') }}</strong>{{ $invoice->flat_num ?? ''}}
                                                        <strong>{{ __('Flat No') }}</strong>{{ $invoice->room_number?? ''}}
                                                        <strong>{{ __('Gross M²') }}</strong>{{ $invoice->gross_square ?? '' }}
                                                    </p>
                                                </td>
                                                <td>
                                                    <p>
                                                        @switch($invoice->currency)
                                                            @case('TRY')
                                                            <strong>{{ number_format($invoice->price, 3) ?? '0.00' }} {{ $invoice->currency }}</strong>
                                                            @break
                                                            @case('EUR')
                                                            <strong>{{ number_format($invoice->price, 2) ?? '0.00' }} {{ $invoice->currency }}</strong>
                                                            @break
                                                            @case('USD')
                                                            <strong>{{ number_format($invoice->price, 2) ?? '0.00' }} {{ $invoice->currency }}</strong>
                                                            @break
                                                        @endswitch
                                                    </p>
                                                </td>
                                                <td>
                                                    <p><strong>{{ $invoice->commission_rate }} %</strong></p>
                                                </td>
                                                <td>
                                                    <p>
                                                        @switch($invoice->currency)
                                                            @case('TRY')
                                                            <strong>{{ number_format($invoice->commission_total, 3) ?? '0.00' }} {{ $invoice->currency }}</strong>
                                                            @break
                                                            @case('EUR')
                                                            <strong>{{ number_format($invoice->commission_total, 2) ?? '0.00' }} {{ $invoice->currency }}</strong>
                                                            @break
                                                            @case('USD')
                                                            <strong>{{ number_format($invoice->commission_total, 2) ?? '0.00' }} {{ $invoice->currency }}</strong>
                                                            @break
                                                        @endswitch
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td class="Rate">
                                                    <h6 class="mb-0 p-2">{{ __('Subtotal:') }}</h6>
                                                </td>
                                                <td class="payment digits">
                                                    <h6 class="mb-0 p-2">
                                                        @switch($invoice->currency)
                                                            @case('TRY')
                                                            {{ number_format($invoice->commission_total) ?? '0.00' }} {{ $invoice->currency }}
                                                            @break
                                                            @case('EUR')
                                                            {{ number_format($invoice->commission_total, 2) ?? '0.00' }} {{ $invoice->currency }}
                                                            @break
                                                            @case('USD')
                                                            {{ number_format($invoice->commission_total, 2) ?? '0.00' }} {{ $invoice->currency }}
                                                            @break
                                                        @endswitch
                                                    </h6>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td class="Rate">
                                                    <h6 class="mb-0 p-2">{{ __('Tax:') }}</h6>
                                                </td>
                                                <td class="payment digits">
                                                    <h6 class="mb-0 p-2"></h6>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td class="Rate">
                                                    <h6 class="mb-0 p-2">{{ __('Total:') }}</h6>
                                                </td>
                                                <td class="payment digits">
                                                    <h6 class="mb-0 p-2">
                                                        @switch($invoice->currency)
                                                            @case('TRY')
                                                            {{ number_format($invoice->commission_total) ?? '0.00' }} {{ $invoice->currency }}
                                                            @break
                                                            @case('EUR')
                                                            {{ number_format($invoice->commission_total, 2) ?? '0.00' }} {{ $invoice->currency }}
                                                            @break
                                                            @case('USD')
                                                            {{ number_format($invoice->commission_total, 2) ?? '0.00' }} {{ $invoice->currency }}
                                                            @break
                                                        @endswitch
                                                    </h6>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- End Table-->
                                    <div class="row mt-4">
                                        <div class="col-md-8">
                                            <div>
                                                <h6>{{ __('Company Details') }}</h6>
                                                <p class="legal">
                                                    <strong>{{ __('Company Name:') }}</strong><br>{{$invoice->project->company_name ?? ''}}
                                                    <br><strong>{{ __('Company Address:') }}</strong><br>{{$invoice->project->company_name ?? ''}}
                                                    <br><strong>{{ __('Tax branch:') }}</strong><br>{{$invoice->project->tax_branch ?? ''}}
                                                    <br><strong>{{ __('Tax ID:') }}</strong><br>{{$invoice->project->tax_number ?? ''}}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End InvoiceBot-->
                            </div>
                            <div class="col-sm-12 text-center mt-4 row">
                                <div class="col-6 mx-auto">
                                    <p class="legal">
                                        {{ __('General Manager') }}<br><b>FERHAT POSMA</b>
                                    </p>
                                </div>
                                <div class="col-6 mx-auto">
                                    <p class="legal">
                                        {{ __('C.E.O') }}<br><b>ALPAY ÇEPNİ</b>
                                    </p>
                                </div>
                                <div class="col-12 mx-auto">
                                    <p class="legal">
                                        {{ __('Chairman') }}<br><b>HAŞİM SÜNGÜ</b>
                                    </p>
                                </div>
                            </div>
                            <div class="col-sm-12 text-center mt-4">
                                <button class="btn btn btn-primary mr-2" type="button"
                                        onclick="myFunction()">{{ __('Print') }}
                                </button>
                                <button class="btn btn-secondary" type="button">Cancel</button>
                            </div>
                            <!-- End Invoice-->
                            <!-- End Invoice Holder-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
