@extends('layouts.vertical.master')
@section('title', '| Contact')

@section('style_before')
@endsection

@section('script')
    <!-- Plugins JS start-->
    <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item">{{ __('Contact') }}</li>
@endsection


@section('content')

    <div class="container-fluid">
        <div class="row">
            @foreach($users as $user)
                <div class="col-md-6 col-lg-6 col-xl-4 box-col-6">
                    <div class="card custom-card">
                        <div class="card-header"><img class="img-fluid"
                                                      src=""
                                                      alt="">
                        </div>
                        <div class="card-profile"><img class="rounded-circle"
                                                       src="{{ asset('storage/' . $user->image_path) }}"
                                                       alt=""></div>
                        <ul class="card-social">
                            <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                            <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                            <li><a href="#"><i class="fa fa-instagram"></i></a></li>
                        </ul>
                        <div class="text-center profile-details">
                            <h4>{{ $user->name }}</h4>
                            <h6>{{ $user->roles->first()->name }}</h6>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
