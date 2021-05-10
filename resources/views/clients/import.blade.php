@extends('layouts.app')

@push('style')
<!-- jquery file upload Frame work -->
<link href="{{ asset('assets/css/jquery.filer.css') }}" type="text/css" rel="stylesheet" />
<link href="{{ asset('assets/css/jquery.filer-dragdropbox-theme.css') }}" type="text/css" rel="stylesheet" />
@endpush

@section('content')

<!-- Main-body start -->
<div class="main-body">
  <div class="page-wrapper">
    <!-- Page header start -->
    <div class="page-header">
      <div class="page-header-title">
        <h4>Import clients</h4>
      </div>
      <div class="page-header-breadcrumb">
        <ul class="breadcrumb-title">
          <li class="breadcrumb-item">
            <a href="{{ route('home') }}">
              <i class="icofont icofont-home"></i>
            </a>
          </li>
          <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">Leads</a>
          </li>
        </ul>
      </div>
    </div>
    <!-- Page header end -->
    <!-- Page body start -->
    <div class="page-body">
      <!-- File upload card start -->
      <div class="row">
        <div class="card col-8 mx-auto">
          <div class="card-header">
            <h5>File Upload</h5>
          </div>
          <div class="card-block">
            <form action="{{ route('import') }}" method="post" enctype="multipart/form-data">
              @csrf
              <div class="form-group">
                <label for="user_id">Agent</label>
                <select class="form-control" name="user_id" id="user_id">
                  <option value="">-- Select agent --</option>
                  @foreach($users as $user)
                  <option value="{{ $user->id }}">{{ $user->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="source_id">Source</label>
                <select class="form-control" name="source_id" id="source_id">
                  <option value="" selected> -- Select source --</option>
                  @foreach($sources as $source)
                  <option value="{{ $source->id }}">{{ $source->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label>Upload File</label>
                <input name="file" type="file" class="form-control">
              </div>
              <button type="submit" class="btn btn-primary btn-outline-primary btn-block"><i class="ti-upload"></i>
                Import
              </button>
            </form>
          </div>
        </div>
        <!-- File upload card end -->
        <!-- Show Errors -->
        @if( session()->has('failures'))
        <div class="card col-8 mx-auto">
          <div class="card-header">
            <h5>Errors</h5>
          </div>
          <div class="card-block">
            <table class="table table-danger">
              <tr>
                <th>Row</th>
                <th>Attribute</th>
                <th>Errors</th>
                <th>Value</th>
              </tr>
              @foreach(session()->get('failures') as $validation)
              <tr>
                <td>{{ $validation->row() }}</td>
                <td>{{ $validation->attribute() }}</td>
                <td>
                  <ul>
                    @foreach($validation->errors() as $e)
                    <li>{{ $e }}</li>
                    @endforeach
                  </ul>
                </td>
                <td>
                  {{ $validation->values()[$validation->attribute()] }}
                </td>
              </tr>
              @endforeach
            </table>
          </div>
        </div>
        @endif
        <!-- End show errors -->
      </div>
    </div>
    <!-- Page body end -->
  </div>
</div>

@endsection

@push('scripts')
<!-- jquery file upload js -->
<script src="{{ asset('assets/js/jquery.filer.min.js') }}"></script>
<script src="{{ asset('assets/pages/filer/custom-filer.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/pages/filer/jquery.fileuploads.init.js') }}" type="text/javascript"></script>
@endpush