<div class="card mt-4">
    <div class="card-header">
    </div>
    <div class="card-block">
        <div class="table-responsive">
            <div class="dt-responsive table-responsive">
                <table id="res-config" class="table table-bordered nowrap display compact">
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th>Date</th>
                        <th>Owner</th>
                        <th>Sales representative</th>
                        <th>Created at</th>
                        <th>Result</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($subject->events as $event)
                        <tr>
                            <td>
                                <a href="{{ route('events.edit', $event) }}">{{ $event->name }}</a>
                            </td>
                            <td>
                               {{ $event->event_date->format('Y-m-d H:m') }}
                            </td>
                            <td>
                                {{ $event->user->name ?? '' }}
                            </td>
                            <td>
                                @php $sellRep = collect($event->sells_name)->toArray() @endphp
                                @foreach( $sellRep as $name)
                                    <span class="badge badge-inverse">{{ $name }}</span>
                                @endforeach
                            </td>
                            <td>
                                {{ $event->created_at->format('Y-m-d H:m') }}
                            </td>
                            <td>
                                @php
                                    $i = $event->results;
                                    switch ($i) {
                                    case 0:
                                    echo 'None';
                                    break;
                                    case 1:
                                    echo 'Under evaluation';
                                    break;
                                    case 2:
                                    echo 'Postponed';
                                    break;
                                    case 3:
                                    echo 'Negative';
                                    break;
                                    case 4:
                                    echo 'Appointment not met';
                                    break;
                                    case 5:
                                    echo 'Reservation';
                                    break;
                                    case 6:
                                    echo 'Reservation Cancellation';
                                    break;
                                    case 7:
                                    echo 'Sale';
                                    break;
                                    case 8:
                                    echo 'Sale Cancellation';
                                    break;
                                    case 9:
                                    echo 'After Sale';
                                    break;
                                    case 10:
                                    echo 'Presentation';
                                    break;
                                    case 11:
                                    echo 'Follow up';
                                    break;
                                    }
                                @endphp
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
