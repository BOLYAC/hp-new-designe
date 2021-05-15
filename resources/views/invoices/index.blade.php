@extends('layouts.vertical.master')
@section('title', '| Invoices')

@section('style_before')
    <!-- Notification.css -->
    <link rel="stylesheet" href="{{ asset('assets/css/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/datatable-extension.css') }}">
@endsection

@section('script')

    <script src="{{asset('assets/js/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables/datatable-extension/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables/datatable-extension/buttons.bootstrap4.min.js')}}"></script>

    <script src="{{asset('assets/js/datatables/datatable-extension/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables/datatable-extension/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables/datatable-extension/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables/datatable-extension/dataTables.colReorder.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables/datatable-extension/dataTables.rowReorder.min.js')}}"></script>

    <script>
        $(document).ready(function () {
            // Start Edit record
            let table = $('#res-config').DataTable();
        });
    </script>

@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item">{{ __('Invoices') }}</li>
@endsection

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mx-auto">
                <!-- Zero config.table start -->
                @include('partials.flash-message')
                <div class="card b-t-primary">
                    @can('order-create')
                        <div class="card-header b-t-primary p-2">
                            <a href="{{ route('invoices.create') }}" class="btn btn-sm btn-primary">{{ __('New
                                Invoice') }}<i class="icon-plus"></i></a>
                        </div>
                    @endcan
                    <div class="card-body">
                        <div class="order-history dt-ext table-responsive">
                            <table id="res-config" class="table table-striped display table-bordered nowrap"
                                   width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>{{ __('Client') }}</th>
                                    <th>{{ __('Project') }}</th>
                                    <th>{{ __('Assigned') }}</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($invoices as $key => $invoice)
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td>{{ $invoice->client->full_name ?? '' }}</td>
                                        <td>{{ $invoice->project ?? '' }} </td>
                                        <td>
                                                    <span class="badge badge-success">
                                                    {{ $invoice->user->name?? '' }}
                                                    </span>
                                        </td>
                                        <td class="flex-container">
                                            <div class="btn-group pull-right">
                                                <a class="btn btn-sm btn-outline-primary"
                                                   href="{{ route('invoices.edit', $invoice) }}">{{__('View sales')}}
                                                    <i
                                                        class="icon-eye"></i></a>
                                            </div>
                                            @can('event-delete')
                                                <form
                                                    action="{{ route('invoices.destroy', $invoice) }}"
                                                    method="post" role="form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-xs btn-warning">
                                                        {{ __('Delete sales') }}
                                                        <i class="icon-trash"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">

                    </div>
                </div>
            </div>
            <!-- Zero config.table end -->
        </div>
    </div>

@endsection

