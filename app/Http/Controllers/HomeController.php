<?php

namespace App\Http\Controllers;

use App\Jobs\AssignedClientEmailJob;
use App\Models\Client;
use App\Models\Country;
use App\Models\Event;
use App\Models\Language;
use App\Models\Nationality;
use App\Models\Source;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;

class HomeController extends Controller
{
    /**s
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     */
    public function index()
    {
        $clients = Client::whereType(true)->count();
        $sources = Source::all();
        $users = User::all();
        $allClients = Client::count();
        $todayTasks = Task::with(['agency', 'client'])->archive(false)->whereDate('date', Carbon::today())->get();
        $olderTask = Task::with(['agency', 'client'])->archive(true)->whereDate('date', '<', Carbon::today())->count();
        $tomorrowTasks = Task::with(['agency', 'client'])->archive(false)->whereDate('date', Carbon::tomorrow())->get();
        $pendingTasks = Task::with(['agency', 'client'])->archive(false)->whereDate('date', '<', Carbon::today())->get();
        $completedTasks = Task::with(['agency', 'client'])->archive(true)->get();
        if (auth()->user()->hasRole('User')) {
            $events = Event::whereHas('user', function ($query) {
                $query->whereDate('event_date', Carbon::today())
                    ->where('user_id', Auth::id());
            })->count();
        } else {
            $events = Event::whereDate('event_date', Carbon::today())->count();
        }


        return view('dashboard.index', compact('todayTasks', 'pendingTasks', 'olderTask', 'events', 'clients', 'completedTasks', 'allClients', 'tomorrowTasks', 'sources', 'users'));
    }

    public function userNew()
    {
        $clients = Client::select(['id', 'full_name', 'country', 'nationality', 'status', 'priority', 'type', 'created_at', 'updated_at'])
            ->where('type', true);
        return Datatables::of($clients)
            ->setRowId('id')
            ->editColumn('created_at', function ($clients) {
                return optional($clients->created_at)->format('d-m-Y') ?? '';
            })
            ->editColumn('updated_at', function ($clients) {
                return optional($clients->updated_at)->format('d-m-Y') ?? '';
            })
            ->editColumn('full_name', function ($clients) {
                return '<div class="product-name"><a href="clients/' . $clients->id . '/edit">' . $clients->full_name . '</a></div>';
            })
            ->editColumn('country', function ($clients) {
                if (is_null($clients->country)) {
                    return $clients->getRawOriginal('country') ?? '';
                } else {
                    $cou = '';
                    $countries = collect($clients->country)->toArray();
                    foreach ($countries as $name) {
                        $cou .= '<span class="badge badge-light-primary">' . $name . '</span>';
                    }
                    return $cou;
                }
            })
            ->editColumn('nationality', function ($clients) {
                if (is_null($clients->nationality)) {
                    return $clients->getRawOriginal('nationality') ?? '';
                } else {
                    $cou = '';
                    $nat = collect($clients->nationality)->toArray();
                    foreach ($nat as $name) {
                        $cou .= '<span class="badge badge-light-primary">' . $name . '</span>';
                    }
                    return $cou;
                }
            })
            ->addColumn('status',
                function ($clients) {
                    $i = $clients->status;
                    switch ($i) {
                        case 1:
                            return '<span class="badge badge-primary">' . __('New Lead') . '</span>';
                            break;
                        case 8:
                            return '<span class="badge badge-primary">' . __('No Answer') . '</span>';
                            break;
                        case 12:
                            return '<span class="badge badge-primary">' . __('In progress') . '</span>';
                            break;
                        case 3:
                            return '<span class="badge badge-primary">' . __('Potential appointment') . '</span>';
                            break;
                        case 4:
                            return '<span class="badge badge-primary">' . __('Appointment set') . '</span>';
                            break;
                        case 10:
                            return '<span class="badge badge-primary">' . __('Appointment follow up') . '</span>';
                            break;
                        case 5:
                            return '<span class="badge badge-primary">' . __('Sold') . '</span>';
                            break;
                        case 13:
                            return '<span class="badge badge-danger">' . __('Unreachable') . '</span>';
                            break;
                        case 7:
                            return '<span class="badge badge-danger">' . __('Not interested') . '</span>';
                            break;
                        case 11:
                            return '<span class="badge badge-danger">' . __('Low budget') . '</span>';
                            break;
                        case 9:
                            return '<span class="badge badge-danger">' . __('Wrong Number') . '</span>';
                            break;
                        case 14:
                            return '<span class="badge badge-danger">' . __('Unqualified') . '</span>';
                            break;
                    };
                })
            ->addColumn('priority', function ($clients) {
                $i = $clients->priority;
                switch ($i) {
                    case 1:
                        return '<label class="txt-success f-w-600">' . __('Low') . '</label>';
                        break;
                    case 2:
                        return '<label class="txt-warning f-w-600">' . __('Medium') . '</label>';
                        break;
                    case 3:
                        return '<label class="txt-danger f-w-600">' . __('High') . '</label>';
                        break;
                };
            })
            ->addColumn('appointment_date',
                function ($clients) {
                    return optional($clients->appointment_date)->format('d-m-Y') ?? '';
                })
            ->rawColumns(['full_name', 'status', 'priority', 'country', 'nationality'])
            ->make(true);
    }


    public function getCountry(Request $request)
    {
        $data = [];

        $key = $request->get('q');

        if ($key) {
            $data = Country::where(function ($query) use ($key) {
                $query->where('id', 'like', '%' . $key . '%')
                    ->orWhere('name', 'LIKE', '%' . $key . '%');
            })->get(['id', 'name']);
        }
        return response()->json($data);
    }

    public function getNationality(Request $request)
    {
        $data = [];
        $key = $request->get('q');
        if ($key) {
            $data = Nationality::where(function ($query) use ($key) {
                $query->where('id', 'like', '%' . $key . '%')
                    ->orWhere('name', 'LIKE', '%' . $key . '%');
            })->get(['id', 'name']);
        }
        return response()->json($data);
    }

    public function getLanguage(Request $request)
    {
        $data = [];
        $key = $request->get('q');
        if ($key) {
            $data = Language::where(function ($query) use ($key) {
                $query->where('id', 'like', '%' . $key . '%')
                    ->orWhere('name', 'LIKE', '%' . $key . '%');
            })->get(['id', 'name']);
        }
        return response()->json($data);
    }
}
