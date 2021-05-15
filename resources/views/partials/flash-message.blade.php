@if ($message = Session::get('success'))
    <div class="alert alert-success dark alert-dismissible fade show" role="alert"><i data-feather="thumbs-up"></i>
        <p> {{ $message }}</p>
        <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
    </div>
@endif

@if ($message = Session::get('error'))
    <div class="alert alert-danger dark alert-dismissible fade show" role="alert"><i data-feather="thumbs-down"></i>
        <p> {{ $message }}</p>
        <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
    </div>
@endif

@if ($message = Session::get('warning'))
    <div class="alert alert-warning dark alert-dismissible fade show" role="alert"><i data-feather="bell"></i>
        <p>{{ $message }}</p>
        <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
    </div>
@endif

@if ($message = Session::get('info'))
    <div class="alert alert-info dark alert-dismissible fade show" role="alert"><i data-feather="help-circle"></i>
        <p> {{ $message }}</p>
        <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger icons-alert">
        <button type="button" class="close" data-dismiss="alert">×</button>

    </div>
    <div class="alert alert-danger dark alert-dismissible fade show" role="alert"><i data-feather="thumbs-down"></i>
        <p> {{ __(' Please check the form below for errors') }}</p>
        <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
    </div>
@endif

@if($message = Session::get('errors'))
    <div class="alert alert-primary" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{!! $error !!}</li>
            @endforeach
        </ul>
    </div>
@endif
