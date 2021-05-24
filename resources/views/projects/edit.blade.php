@extends('layouts.vertical.master')
@section('title', '| Project edit')
@section('style_before')
    <!-- Summernote.css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/summernote.css') }}">
@endsection

@section('script')
    <!-- Plugins JS start-->
    <script src="{{ asset('assets/js/editor/summernote/summernote.js') }}"></script>
    <script src="{{ asset('assets/js/editor/summernote/summernote.custom.js') }}"></script>

@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">{{ __('Project list') }} </a></li>
    <li class="breadcrumb-item">{{ __('edit project') }}: {{ $project->project_name }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-8 mx-auto">
                <!-- Zero config.table start -->
                @include('partials.flash-message')
                <div class="card">
                    <form action="{{ route('projects.update', $project) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card-body b-t-primary">
                            <div class="row">
                                <div class="form-group input-group-sm col-12">
                                    <label for="company_name">{{ __('Company name') }}</label>
                                    <input class="form-control form-control-sm" type="text" name="company_name"
                                           id="company_name"
                                           value="{{ old('company_name', $project->company_name) }}">
                                </div>
                                <div class="form-group input-group-sm col">
                                    <label for="phone_1">{{ __('Phone') }}</label>
                                    <input class="form-control form-control-sm" type="text" name="phone_1" id="phone_1"
                                           value="{{ old('phone_1', $project->phone_1) }}">
                                </div>
                                <div class="form-group input-group-sm col">
                                    <label for="phone_2">{{ __('Phone 2') }}</label>
                                    <input class="form-control sm" type="text" name="phone_2" id="phone_2"
                                           value="{{ old('phone_2', $project->phone_2) }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group input-group-sm col-sm">
                                    <label for="tax_number">{{ __('Tax ID') }}</label>
                                    <input class="form-control form-control-sm" type="text" name="tax_number"
                                           id="tax_number"
                                           value="{{ old('tax_number', $project->tax_number) }}">
                                </div>
                                <div class="form-group input-group-sm col-sm">
                                    <label for="tax_branch">{{ __('Tax branch') }}</label>
                                    <input class="form-control sm" type="text" name="tax_branch" id="tax_branch"
                                           value="{{ old('tax_branch', $project->tax_branch) }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group input-group-sm col">
                                    <label for="commission_rate">{{ __('Commission rate') }}</label>
                                    <input class="form-control sm" type="text" name="commission_rate"
                                           id="commission_rate"
                                           value="{{ old('commission_rate', $project->commission_rate) }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group input-group-sm col">
                                    <label for="project_name">{{ __('Project name') }}</label>
                                    <input class="form-control sm" type="text" name="project_name" id="project_name"
                                           value="{{ old('project_name', $project->project_name) }}">
                                </div>
                                <div class="form-group input-group-sm col">
                                    <label for="type">{{ __('Type') }}</label>
                                    <select name="type" id="type" class="form-control form-control-sm">
                                        <option
                                            value="1" {{ $project->type == 1 ? 'selected': '' }}>{{ __('Apartment') }}</option>
                                        <option
                                            value="2" {{ $project->type == 2 ? 'selected': '' }}>{{ __('Home Office') }}</option>
                                        <option
                                            value="3" {{ $project->type == 3 ? 'selected': '' }}>{{ __('Office') }}</option>
                                        <option
                                            value="4" {{ $project->type == 4 ? 'selected': '' }}>{{ __('Residential') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group input-group-sm col">
                                    <label for="address">{{ __('Address') }}</label>
                                    <textarea class="summernote" type="text" name="address"
                                              id="note"> {{ old('address', $project->address) }}</textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group input-group-sm col">
                                    <label for="phone">{{ __('Website') }}</label>
                                    <input class="form-control sm" type="text" name="link" id="link"
                                           value="{{ old('link', $project->link) }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group input-group-sm col-sm">
                                    <label for="min_price">{{ __('Min Price') }}</label>
                                    <input class="form-control sm" type="text" name="min_price" id="min_price"
                                           value="{{ old('min_price', $project->min_price) }}">
                                </div>
                                <div class="form-group input-group-sm col-sm">
                                    <label for="max_price">{{ __('Max price') }}</label>
                                    <input class="form-control sm" type="text" name="max_price" id="max_price"
                                           value="{{ old('max_price', $project->max_price) }}">
                                </div>
                                <div class="form-group input-group-sm col-sm">
                                    <label for="min_size">{{ __('Min size') }}</label>
                                    <input class="form-control sm" type="text" name="min_size" id="min_size"
                                           value="{{ old('min_size', $project->min_size) }}">
                                </div>
                                <div class="form-group input-group-sm col-sm">
                                    <label for="max_size">{{ __('Max size') }}</label>
                                    <input class="form-control sm" type="text" name="max_size" id="max_size"
                                           value="{{ old('max_size', $project->max_size) }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group input-group-sm col">
                                    <label for="email">{{ __('Map') }}</label>
                                    <input class="form-control sm" type="text" name="map" id="map"
                                           value="{{ old('map', $project->map) }}">
                                </div>
                                <div class="form-group input-group-sm col">
                                    <label for="email">{{ __('Drive Link') }}</label>
                                    <input class="form-control sm" type="text" name="drive" id="drive"
                                           value="{{ old('drive', $project->drive) }}">
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-sm btn-primary">
                                {{ __('save') }}
                                <i class="icon-save"></i></button>
                            <a href="{{ route('projects.index') }}"
                               class="btn btn-sm btn-warning">{{__('Cancel')}}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection