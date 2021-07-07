<?php

namespace App\Http\Controllers;

use App\Agency;
use App\Mail\SendCreateEventMail;
use App\Models\Client;
use App\Models\Department;
use App\Models\Event;
use App\Models\Lead;
use App\Models\Source;
use App\Models\Team;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\Facades\DataTables;

class EventsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|View
     */
    public function index(Request $request)
    {
        if ($request->has('today-event')) {
            $events = Event::whereDate('event_date', Carbon::today())->orderBy('event_date', 'desc')->get();
        } else {
            $events = Event::orderBy('event_date', 'desc')->get();
        }
        if (\auth()->user()->hasRole('Admin')) {
            $users = User::all();
            $teams = Team::all();
            $departments = Department::all();
            return view('events.index', compact('events', 'departments', 'teams', 'users'));
        } elseif (\auth()->user()->hasPermissionTo('team-manager')) {
            if (auth()->user()->ownedTeams()->count() > 0) {
                $teamUsers = auth()->user()->ownedTeams;
                $teams = auth()->user()->allTeams();
                foreach ($teamUsers as $u) {
                    foreach ($u->users as $ut) {
                        $users[] = $ut;
                    }
                }
            }
            return view('events.index', compact('events', 'users', 'teams'));
        }
    }

    /**
     * Make json response for datatable
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function anyData(Request $request)
    {
        $events = Event::with(['client', 'user']);

        if ($request->get('user')) {
            $events->where('user_id', '=', $request->get('user'));
        }
        if ($request->get('department')) {
            $events->where('department_id', '=', $request->get('department'));
        }
        if ($request->get('team')) {
            $events->where('team_id', '=', $request->get('team'));
        }
        if ($request->get('daterange')) {
            $date = explode('-', $request->get('daterange'));
            $from = $date[0];
            $to = $date[1];

            $from = \Carbon\Carbon::parse($from)
                ->startOfDay()        // 2018-09-29 00:00:00.000000
                ->toDateTimeString(); // 2018-09-29 00:00:00

            $to = Carbon::parse($to)
                ->endOfDay()          // 2018-09-29 23:59:59.000000
                ->toDateTimeString(); // 2018-09-29 23:59:59

            $events->whereBetween('event_date', [$from, $to]);
        }

        $events->OrderByDesc('created_at');


        return Datatables::of($events)
            ->setRowId('id')
            ->editColumn('name', function ($events) {
                return '<a href="' . route('events.show', $events) . '">' . ($events->name ?? '') . '</a>';
            })
            ->editColumn('full_name', function ($events) {
                return $events->lead_name ?? $events->client->full_name;
            })
            ->editColumn('event_date', function ($events) {
                return Carbon::parse($events->event_date)->format('Y-m-d H:i');
            })
            ->editColumn('place', function ($events) {
                return $events->place ?? '';
            })
            ->addColumn('user', function ($events) {
                return '<span class="badge badge-success">' . optional($events->user)->name . '</span>';
            })
            ->addColumn('action', '<a href="{{ route(\'events.show\', $id) }}"
                                               class="m-r-15 text-muted f-18"><i
                                                    class="icofont icofont-eye-alt"></i></a>
                                            <a href="#!"
                                               class="m-r-15 text-muted f-18 delete"><i
                                                    class="icofont icofont-trash"></i></a>')
            ->rawColumns(['user', 'name', 'action'])
            ->make(true);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|View
     */
    public function create()
    {
        $users = User::all();
        return view('events.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {

        $this->validate($request, [
            'client_id' => 'required',
        ]);


        $data = $request->except('share_with', 'files');

        $users = $request->get('share_with');

        $adminEmail = User::findOrFail(1);
        if ($request->has('share_with')) {
            $u = User::whereIn('id', $users)->pluck('name');
            $data['sellers'] = $users;
            if (auth()->user()->hasRole('Admin')) {
                $uEmails = User::whereIn('id', $users)->pluck('email');
            }
        }

        $client = Client::findOrFail($request->get('client_id'));
        $user = User::find($request->user_id);
        $team = $user->current_team_id ?? 1;

        $data['created_by'] = Auth::id();
        $data['user_id'] = $request->user_id ?? Auth::id();
        $data['team_id'] = $team;
        $data['owner_name'] = Auth::user()->name;
        $data['lead_name'] = $client->full_name;
        $data['lead_number'] = $client->lead_email;
        $data['lead_budget'] = $client->budget;
        $data['lead_lang'] = $client->lang;

        if ($request->has('share_with')) {
            $data['sell_rep'] = $users[0];
            $data['sells_name'] = $u;
        }

        $event = Event::create($data);

        $link = route('events.edit', $event);

        $emailData = [
            'title' => $data['name'],
            'client' => $client->full_name,
            'user' => $user->name,
            'date' => $request->get('event_date'),
            'place' => $data['place'],
            'description' => $data['description'],
            'link' => $link
        ];

        $uEmails[] = $adminEmail->email;
        $uEmails[] = auth()->user()->email;

        Mail::to($uEmails)->send(new SendCreateEventMail($emailData));

        if ($request->has('share_with')) {
            $event->SharedEvents()->attach($users, ['added_by' => Auth::id(), 'user_name' => Auth::user()->name]);
        }

        return redirect()->route('events.index')
            ->with('toast_success', __('Event created successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param Event $event
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|View
     */
    public
    function show(Event $event)
    {
        $users = User::all();
        //dd($event);
        return \view('events.show', compact('event', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Event $event
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|View
     */
    public
    function edit(Event $event)
    {
        $users = User::all();
        if ($event->user_id === \auth()->id()) {
            return view('events.edit', compact('event', 'users'));
        } else {
            return view('events.edit', compact('event', 'users'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Event $event
     */
    public
    function update(Request $request, Event $event)
    {
        $users = $request->get('share_with');

        if ($request->has('share_with')) {
            $u = User::whereIn('id', $users)->pluck('name');
        }
        $user = User::find($request->user_id);
        $team = $user->current_team_id ?? 1;

        $data = $request->except('share_with', 'files');

        $data['created_by'] = Auth::id();

        $data['user_id'] = $request->user_id ?? Auth::id();
        $data['sellers'] = $users;
        $data['team_id'] = $team;
        $data['owner_name'] = $user->name;
        if ($request->has('share_with')) {
            $data['sell_rep'] = $users[0];
            $data['sells_name'] = $u;
        }

        $event->update($data);
        $event->SharedEvents()->detach();
        if ($request->get('feedback')) {
            $lead = Lead::findOrfail($event->lead_id);
            $lead->comments()->create([
                'external_id' => Uuid::uuid4()->toString(),
                'description' => $request->get('feedback'),
                'user_id' => $request->user_id ?? Auth::id()
            ]);
        }

        if ($request->has('share_with')) {
            $event->SharedEvents()->attach($users, ['added_by' => Auth::id(), 'user_name' => Auth::user()->name]);
        }

        return redirect()->route('events.index')
            ->with('toast_success', __('Event updated successfully'));
    }

    /**
     * @param Event $event
     * @return RedirectResponse
     */
    public
    function replicate(Event $event): RedirectResponse
    {
        $newEvent = $event->replicate();
        $newEvent->created_at = \Carbon\Carbon::now();
        $newEvent->updated_at = \Carbon\Carbon::now();

        $newEvent->save();
        return redirect()->route('events.edit', $newEvent)
            ->with('toast_success', __('Event duplicated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Event $event
     * @return RedirectResponse
     * @throws \Exception
     */
    public
    function destroy(Event $event): RedirectResponse
    {
        $event->delete();
        return redirect()->route('events.index')
            ->with('toast_success', __('Event deleted successfully'));
    }

    public
    function dataAjax(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = [];

        $key = $request->get('q');

        if ($key) {
            $data = Client::where(function ($query) use ($key) {
                $query->where('full_name', 'like', '%' . $key . '%')
                    ->orWhere('public_id', 'LIKE', '%' . $key . '%')
                    ->orWhere('client_number', 'LIKE', '%' . $key . '%')
                    ->orWhere('client_email', 'like', '%' . $key . '%')
                    ->orWhere('first_name', 'like', '%' . $key . '%')
                    ->orWhere('last_name', 'like', '%' . $key . '%');
            })->get(['id', 'full_name']);
        }
        return response()->json($data);
    }

    public
    function showReport($val)
    {
        if ($val === 'today') {
            $events = Event::whereDate('event_date', Carbon::today()->toDateString())->get();
        } elseif ($val === 'tomorrow') {
            $events = Event::whereDate('event_date', Carbon::tomorrow()->toDateString())->get();
        }
        if ($events->isEmpty()) {
            return back()->with('toast_error', __('There is no appointment in this date'))->withInput();
        }

        return view('events.report', compact('events', 'val'));
    }

    public
    function createReport(Request $request, $val = array())
    {
        if ($val === 'today') {
            $events = Event::whereDate('event_date', Carbon::today()->toDateString())->get();
        } elseif ($val === 'tomorrow') {
            $events = Event::whereDate('event_date', Carbon::tomorrow()->toDateString())->get();
        } else {
            $d = $request->all();
            $t = array_keys($d);
            $p = explode("_", $t[0]);
            $to = $p[0] . ' ' . $p[1];
            $from = $val;
            $events = Event::whereBetween('event_date', [$from, $to])->get();
        }

        if ($events->isEmpty()) {
            return back()->with('toast_error', __('There is no appointment in this date'))->withInput();
        }

        //return view('events.preview',compact('events'));
        $pdf = PDF::loadView('events.preview', compact('events', 'val'));
        $pdf->setPaper('Tabloid', 'landscape');
        return $pdf->stream('test_pdf.pdf');
    }

    public function customReport(Request $request)
    {
        if (!empty($request->from_date) && !empty($request->to_date)) {
            $from = $request->from_date;
            $to = $request->to_date;
        } else {
            $from = now();
            $to = now();
        }
        $from = Carbon::parse($from)
            ->startOfDay()        // 2018-09-29 00:00:00.000000
            ->toDateTimeString(); // 2018-09-29 00:00:00

        $to = Carbon::parse($to)
            ->endOfDay()          // 2018-09-29 23:59:59.000000
            ->toDateTimeString(); // 2018-09-29 23:59:59

        $events = Event::whereBetween('created_at', [$from, $to])
            ->get();

        if ($events->isEmpty()) {
            return back()->with('toast_error', __('There is no appointment in this date'))->withInput();
        }

        $val = [$from, $to];
        return view('events.report', compact('events', 'val'));

        /*$pdf = PDF::loadView('events.preview', compact('events', 'val'));
        $pdf->setPaper('Tabloid', 'landscape');
        return $pdf->stream('test_pdf.pdf');*/
    }
}
