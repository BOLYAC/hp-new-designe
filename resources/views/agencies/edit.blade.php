@extends('layouts.app')
@push('style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
@endpush

@section('content')
<!-- Main-body start -->
<div class="main-body">
  <div class="page-wrapper">
    <!-- Page header start -->
    <div class="page-header">
      <div class="page-header-title">
        <h4>Ageny {{ $agency->name }}</h4>
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
          <li class="breadcrumb-item"><a href="{{ route('agencies.index') }}">Agencies</a>
          </li>
          <li class="breadcrumb-item"><a href="#">{{ $agency->name }}</a>
          </li>
        </ul>
      </div>
    </div>
    <!-- Page header end -->
    <!-- Page body start -->
    <div class="page-body">
      <div class="row">
        <div class="col">
          <div class="card">
            <div class="card-header">
            </div>
            <form action="{{ route('agencies.update', $agency) }}" method="post" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <div class="card-block">
                <div class="row">
                  <div class="form-group input-group-sm col">
                    <label for="title">Title</label>
                    <input class="form-control form-control-sm" type="text" name="title" id="title"
                      value="{{ old('title', $agency->title) }}">
                  </div>
                  <div class="form-group input-group-sm col">
                    <label for="name">Name</label>
                    <input class="form-control form-control-sm" type="text" name="name" id="name"
                      value="{{ old('name', $agency->name) }}">
                  </div>
                </div>
                <div class="row">
                  <div class="form-group input-group-sm col">
                    <label for="in_charge">Owner</label>
                    <input class="form-control sm" type="text" name="in_charge" id="in_charge"
                      value="{{ old('in_charge', $agency->in_charge) }}">
                  </div>
                  <div class="form-group input-group-sm col">
                    <label for="tax_number">Tax number</label>
                    <input class="form-control sm" type="text" name="tax_number" id="tax_number"
                      value="{{ old('tax_number' , $agency->tax_number) }}">
                  </div>
                </div>
                <div class="row">
                  <div class="form-group input-group-sm col">
                    <label for="phone">Agency phone</label>
                    <input class="form-control sm" type="text" name="phone" id="phone"
                      value="{{ old('phone', $agency->phone) }}">
                  </div>
                  <div class="form-group input-group-sm col">
                    <label for="email">Agency email</label>
                    <input class="form-control sm" type="text" name="email" id="email"
                      value="{{ old('email', $agency->email) }}">
                  </div>
                </div>
                <div class="row">
                  <div class="form-group input-group-sm col">
                    <label for="commission_rate">Commission rate</label>
                    <input class="form-control sm" type="text" name="commission_rate" id="commission_rate"
                      value="{{ old('commission_rate' , $agency->commission_rate) }}">
                  </div>
                  <div class="form-group input-group-sm col">
                    <label for="contract_status">Contract status</label>
                    <input class="form-control sm" type="text" name="contract_status" id="contract_status"
                      value="{{ old('contract_status', $agency->contract_status) }}">
                  </div>
                </div>
                <div class="form-group input-group-sm">
                  <label for="address">Address</label>
                  <textarea class="form-control sm" type="text" name="address"
                    id="address">{{ old('address',  $agency->address) }}</textarea>
                </div>
                <div class="form-group input-group-sm">
                  <label for="note">Note</label>
                  <textarea class="form-control sm" type="text" name="note"
                    id="note"> {{ old('note',  $agency->note) }}</textarea>
                </div>
                <div class="form-group">
                  <div class="border-checkbox-section pl-4">
                    <div class="border-checkbox-group border-checkbox-group-primary">
                      <input class="border-checkbox" type="checkbox" id="status" name="status"
                        {{ $agency->status == 1 ? 'checked' : '' }}>
                      <label class="border-checkbox-label" for="status">Status</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-footer">
                <button type="submit" class="btn btn-sm btn-outline-success">
                  save
                  <i class="ti-save"></i></button>
                <a href="{{ route('agencies.index') }}" class="btn btn-sm btn-outline-danger">Cancel</a>
              </div>
            </form>
          </div>
        </div>
        <div class="col">
          <div class="card">
            <div class="card-header">

            </div>
            <div class="card-block">
              <div class="table-responsive">
                <div class="dt-responsive table-responsive">
                  <table id="res-config" class="table table-bordered nowrap display compact">
                    <thead>
                      <tr>
                        <th>N°</th>
                        <th>New</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Assigned</th>
                        <th>Source</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($agency->clients as $client)
                      <tr>
                        <td>{{ $client->public_id }}</td>
                        <td>
                          @switch($client->type)
                          @case(true)
                          <label class="label label-success">Yes</label>
                          @break
                          @case(false)
                          <label class="label label-danger">No</label>
                          @break
                          @endswitch
                        </td>
                        <td>
                          <a href="{{ route('clients.edit', $client) }}">{{ $client->full_name }}</a>
                        </td>
                        <td>
                          @switch($client->status)
                          @case(1)
                          <span class="badge badge-default"> New Lead</span>
                          @break
                          @case(8)
                          <span class="badge badge-default">No Answer</span>
                          @break
                          @case(12)
                          <span class="badge badge-default">In progress</span>
                          @break
                          @case(3)
                          <span class="badge badge-default">Potential
                            appointment</span>
                          @break
                          @case(4)
                          <span class="badge badge-default">Appointment
                            set</span>
                          @break
                          @case(10)
                          <span class="badge badge-default">Appointment
                            follow up</span>
                          @break
                          @case(5)
                          <span class="badge badge-default">Sold</span>
                          @break
                          @case(13)
                          <span class="badge badge-default">Unreachable</span>
                          @break
                          @case(7)
                          <span class="badge badge-default">Not interested</span>
                          @break
                          @case(11)
                          <span class="badge badge-default">Low budget</span>
                          @break
                          @case(9)
                          <span class="badge badge-default">Wrong Number</span>
                          @break
                          Unqualified
                          @case(14)
                          <span class="badge badge-default">Wrong Number</span>
                          @break
                          @endswitch
                        </td>
                        <td><span class="badge badge-success">{{ $client->user->name }}</span></td>
                        <td>{{ optional($client->source)->name }}</td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Zero config.table end -->
      </div>
    </div>
  </div>
  <!-- Page body end -->
</div>

@endsection
@push('scripts')
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script>
  $(document).ready(function () {
    // Start Edit record
    let table = $('#res-config').DataTable();
  });
</script>
@endpush