@extends('layouts.app')

@section('content')
<!-- Main-body start -->
<div class="main-body">
  <div class="page-wrapper">
    <!-- Page header start -->
    <div class="page-header">
      <div class="page-header-title">
        <h4>New agency</h4>
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
          <li class="breadcrumb-item"><a href="#">New agency</a>
          </li>
        </ul>
      </div>
    </div>
    <!-- Page header end -->
    <!-- Page body start -->
    <div class="page-body">
      <div class="row">
        <div class="col-8 mx-auto">
          <div class="card">
            <div class="card-header">
            </div>
            <form action="{{ route('agencies.store') }}" method="post" enctype="multipart/form-data">
              @csrf
              <div class="card-block">
                <div class="row">
                  <div class="form-group input-group-sm col">
                    <label for="title">Title</label>
                    <input class="form-control form-control-sm" type="text" name="title" id="title"
                      value="{{ old('title') }}">
                  </div>
                  <div class="form-group input-group-sm col">
                    <label for="name">Name</label>
                    <input class="form-control form-control-sm" type="text" name="name" id="name"
                      value="{{ old('name') }}">
                  </div>
                </div>
                <div class="row">
                  <div class="form-group input-group-sm col">
                    <label for="in_charge">Owner</label>
                    <input class="form-control sm" type="text" name="in_charge" id="in_charge"
                      value="{{ old('in_charge') }}">
                  </div>
                  <div class="form-group input-group-sm col">
                    <label for="tax_number">Tax number</label>
                    <input class="form-control sm" type="text" name="tax_number" id="tax_number"
                      value="{{ old('tax_number') }}">
                  </div>
                </div>
                <div class="row">
                  <div class="form-group input-group-sm col">
                    <label for="phone">Agency phone</label>
                    <input class="form-control sm" type="text" name="phone" id="phone" value="{{ old('phone') }}">
                  </div>
                  <div class="form-group input-group-sm col">
                    <label for="email">Agency email</label>
                    <input class="form-control sm" type="text" name="email" id="email" value="{{ old('email') }}">
                  </div>
                </div>
                <div class="row">
                  <div class="form-group input-group-sm col">
                    <label for="commission_rate">Commission rate</label>
                    <input class="form-control sm" type="text" name="commission_rate" id="commission_rate"
                      value="{{ old('commission_rate') }}">
                  </div>
                  <div class="form-group input-group-sm col">
                    <label for="contract_status">Contract status</label>
                    <input class="form-control sm" type="text" name="contract_status" id="contract_status"
                      value="{{ old('contract_status') }}">
                  </div>
                </div>
                <div class="form-group input-group-sm">
                  <label for="address">Address</label>
                  <textarea class="form-control sm" type="text" name="address"
                    id="address">{{ old('address') }}</textarea>
                </div>
                <div class="form-group input-group-sm">
                  <label for="note">Note</label>
                  <textarea class="form-control sm" type="text" name="note" id="note"> {{ old('note') }}</textarea>
                </div>
                <div class="form-group">
                  <div class="border-checkbox-section pl-4">
                    <div class="border-checkbox-group border-checkbox-group-primary">
                      <input class="border-checkbox" type="checkbox" id="status" name="status">
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
        <!-- Zero config.table end -->
      </div>
    </div>
  </div>
  <!-- Page body end -->
</div>

@endsection
@push('scripts')

@endpush