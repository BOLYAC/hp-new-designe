@extends('layouts.vertical.master')
@section('title', 'Calendar')

@section('style_after')
    <!-- Calender css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/fullcalendar.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/fullcalendar.print.min.css') }}" media='print'>
@endsection

@section('style')

@endsection

@section('script_after')
    <!-- Plugins JS start-->
    <script src="{{ asset('assets/js/calendar/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/calendar/fullcalendar.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            // page is now ready, initialize the calendar...
            $('#calendar').fullCalendar({
                // put your options and callbacks here
                height: 650,
                contentHeight: 600,
                aspectRatio: 2,
                defaultView: $(window).width() < 765 ? 'basicDay' : 'agendaWeek',
                events: [
                        @foreach($events as $event)
                    {
                        title: '{{ $event->lead_name  ?? $event->name ?? ''}}',
                        url: '{{ route('events.edit', $event->id) }}',
                        start: '{{ $event->event_date }}',
                        color: '{{ $event->color }}'
                    },
                    @endforeach
                ],
                displayEventTime: false,
            })
        });
    </script>

@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item">{{ __('Calendar') }}</li>
@endsection

@section('breadcrumb-title')

@endsection
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body b-t-primary">
                <div id='calendar'></div>
            </div>
        </div>
    </div>
@endsection

