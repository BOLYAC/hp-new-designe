@extends('layouts.vertical.master')
@section('title', '| Deals')

@section('style_before')
    <!-- Notification.css -->
    <link rel="stylesheet" href="{{ asset('assets/css/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/datatable-extension.css') }}">
@endsection

@section('style')

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
            let table = $('#res-config').DataTable();
            table.on('click', '.delete', function () {
                $tr = $(this).closest('tr');
                if ($($tr).hasClass('child')) {
                    $tr = $tr.prev('.parent');
                }
                let data = table.row($tr).data();
                $('#deleteForm').attr('action', 'leads/' + data[0]);
                $('#deleteModal').modal('show');
            })
        });
    </script>

@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item">{{ __('Deals') }}</li>
@endsection

@section('breadcrumb-title')

@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mx-auto">
                @include('partials.flash-message')
                <div class="card">
                    <div class="card-header p-3 b-t-primary">
                        @can('lead-create')
                            <a href="{{ route('leads.create') }}"
                               class="btn btn-sm btn-outline-primary">{{__('New deal')}}<i class="icon-plus"></i></a>
                        @endcan
                    </div>
                    <div class="card-body">
                        <div class="order-history dt-ext table-responsive">
                            <table id="res-config"
                                   class="table task-list-table table-striped table-bordered nowrap"
                                   style="width:100%">
                                <thead>
                                <tr>
                                    <th width="4%">N°</th>
                                    <th data-priority="1">{{ __('Client') }}</th>
                                    <th data-priority="4">{{ __('Stage') }}</th>
                                    <th>{{ __('Assigned') }}</th>
                                    <th>
                                        {{ __('Sell representative') }}
                                    </th>
                                    <th></th>
                                    <th data-priority="2">
                                        {{ __('Action') }}
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($leads as $key => $lead)
                                    <tr>
                                        <td>{{ $lead->id }}</td>
                                        <td>
                                            @if($lead->client)
                                                {{ $lead->client->full_name ?? '' }}
                                            @else
                                                {{ $lead->lead_name ?? '' }}
                                            @endif
                                        </td>
                                        <td>
                                            @switch($lead->stage_id)
                                                @case(1)
                                                <span
                                                    class="badge badge-light-primary">{{ __('In contact') }}</span>
                                                @break;
                                                @case(2)
                                                <span
                                                    class="badge badge-light-primary">{{ __('Appointment Set') }}</span>
                                                @break;
                                                @case(3)
                                                <span
                                                    class="badge badge-light-primary">{{ __('Follow up') }}</span>
                                                @break;
                                                @case(4)
                                                <span
                                                    class="badge badge-light-primary">{{ __('Reservation') }}</span>
                                                @break;
                                                @case(5)
                                                <span
                                                    class="badge badge-light-primary">{{ __('contract signed') }}</span>
                                                @break;
                                                @case(6)
                                                <span
                                                    class="badge badge-light-primary">{{ __('Down payment') }}</span>
                                                @break;
                                                @case(7)
                                                <span
                                                    class="badge badge-light-primary">{{ __('Developer invoice') }}</span>
                                                @break;
                                                @case(8)
                                                <span
                                                    class="badge badge-light-success">{{ __('Won Deal') }}</span>
                                                @break;
                                                @case(9)
                                                <span class="badge badge-light-danger">{{ __('Lost') }}</span>
                                                @break;
                                            @endswitch
                                        </td>
                                        <td>
                                          <span class="f-w-600">
                                            {{ $lead->user->name }}
                                          </span>
                                        </td>
                                        <td>
                                            @php $sellRep = collect($lead->sells_names)->toArray() @endphp
                                            @foreach( $sellRep as $name)
                                                <span class="badge badge-secondary">{{ $name }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            @if($lead->invoice_id <> 0)
                                                <span class="badge badge-success">{{ __('Deal Won') }}</span>
                                            @else
                                                @can('transfer-deal-to-invoice')
                                                    <form action="{{ route('lead.convert.order', $lead) }}"
                                                          onSubmit="return confirm('Are you sure?');"
                                                          method="post">
                                                        @csrf
                                                        <button type="submit"
                                                                class="btn btn-success btn-outline-success btn-sm"
                                                                href="">{{ __('Convert to invoice') }} <i
                                                                class="fa fa-mail-forward"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            @endif
                                        </td>
                                        <td class="action-icon">
                                            <a href="{{ route('leads.show', $lead) }}"
                                               class="m-r-15 text-muted f-18"><i
                                                    class="icofont icofont-eye-alt"></i></a>
                                            <a href="#!"
                                               class="m-r-15 text-muted f-18 delete"><i
                                                    class="icofont icofont-trash"></i></a>
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
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete deal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="/leads" method="POST" id="deleteForm">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body p-b-0">
                        <p>Are sur you want to delete this deal?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Delete <i class="ti-trash-alt"></i></button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

