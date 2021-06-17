@extends('layouts.vertical.master')
@section('title', '| Calls')


@section('breadcrumb-items')
    <li class="breadcrumb-item">{{ __('Call') }}</li>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-6 mx-auto call-chat-body">
                <div class="card shadow-0 border">
                    <div class="card-body p-0">
                        <div class="row chat-box">
                            <!-- Chat right side start-->
                            <div class="col pr-xl-0 mx-auto chat-right-aside">
                                <!-- chat start-->
                                <div class="chat">
                                    <!-- chat-header start-->
                                    <div class="chat-header clearfix">
                                        <div class="about">

                                        </div>
                                        <ul class="list-inline float-left float-sm-right chat-menu-icons">
                                            <li class="list-inline-item"><a id="click2call" href="#"><i
                                                        class="icon-headphone-alt"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- chat-header end-->
                                    <!-- chat-history start-->
                                    <div class="chat-history">
                                        <div class="row">
                                            <div class="col text-center pr-0 call-content">
                                                <div>
                                                    <div class="total-time">
                                                        <h2 class="digits">36 : 56</h2>
                                                    </div>
                                                    <div class="call-icons">
                                                    </div>
                                                    <button class="btn btn-danger-gradien btn-block btn-lg">END CALL
                                                    </button>
                                                    <div class="receiver-img"><img
                                                            src="{{ asset('assets/images/other-images/receiver-img.jpg') }}"
                                                            alt=""></div>
                                                </div>
                                            </div>
                                            <div class="col-sm-7 pl-0 caller-img"><img class="img-fluid"
                                                                                       src="{{ asset('assets/images/other-images/caller.jpg') }}"
                                                                                       alt=""></div>
                                        </div>
                                    </div>
                                    <!-- chat-history ends-->
                                    <!-- chat end-->
                                    <!-- Chat right side ends-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

