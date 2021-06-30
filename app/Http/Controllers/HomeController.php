<?php

namespace App\Http\Controllers;

use App\Agency;
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
        $allClients = Client::count();
        $todayTasks = Task::with(['agency', 'client'])->archive(false)->whereDate('date', Carbon::today())->count();
        $olderTask = Task::with(['agency', 'client'])->archive(true)->whereDate('date', '<', Carbon::today())->count();
        $tomorrowTasks = Task::with(['agency', 'client'])->archive(false)->whereDate('date', Carbon::tomorrow())->count();
        $pendingTasks = Task::with(['agency', 'client'])->archive(false)->whereDate('date', '<', Carbon::today())->count();
        $completedTasks = Task::with(['agency', 'client'])->archive(true)->get();
        $events = Event::whereDate('event_date', Carbon::today())->count();

        return view('dashboard.index',
            compact('todayTasks', 'pendingTasks', 'olderTask', 'events', 'completedTasks', 'tomorrowTasks', 'allClients')
        );
    }

    public function userNew()
    {
        $clients = Client::select(['id', 'full_name', 'country', 'nationality', 'status', 'priority', 'type', 'created_at', 'updated_at'])
            ->where('status', 1);
        return Datatables::of($clients)
            ->setRowId('id')
            ->editColumn('created_at', function ($clients) {
                return optional($clients->created_at)->format('d-m-Y') ?? '';
            })
            ->editColumn('updated_at', function ($clients) {
                return optional($clients->updated_at)->format('d-m-Y') ?? '';
            })
            ->editColumn('full_name', function ($clients) {
                return '<div class="product-name"><a href = "clients/' . $clients->id . '/edit" > ' . $clients->full_name . '</a></div>';
            })
            ->editColumn('country', function ($clients) {
                if (is_null($clients->country)) {
                    return $clients->getRawOriginal('country') ?? '';
                } else {
                    $cou = '';
                    $countries = collect($clients->country)->toArray();
                    foreach ($countries as $name) {
                        $cou .= '<span class="badge badge-light-primary"> ' . $name . '</span>';
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
                        $cou .= '<span class="badge badge-light-primary"> ' . $name . '</span> ';
                    }
                    return $cou;
                }
            })
            ->addColumn('status',
                function ($clients) {
                    $i = $clients->status;
                    switch ($i) {
                        case 1:
                            return '<span class="badge badge-primary">' . __('new Lead') . '</span>';
                            break;
                        case 8:
                            return '<span class="badge badge-primary"> ' . __('No Answer') . '</span>';
                            break;
                        case 12:
                            return '<span class="badge badge-primary" > ' . __('In progress') . ' </span > ';
                            break;
                        case 3:
                            return '<span class="badge badge-primary" > ' . __('Potential appointment') . ' </span > ';
                            break;
                        case 4:
                            return '<span class="badge badge-primary" > ' . __('Appointment set') . ' </span > ';
                            break;
                        case 10:
                            return '<span class="badge badge-primary" > ' . __('Appointment follow up') . ' </span > ';
                            break;
                        case 5:
                            return '<span class="badge badge-primary" > ' . __('Sold') . ' </span > ';
                            break;
                        case 13:
                            return '<span class="badge badge-danger" > ' . __('Unreachable') . ' </span > ';
                            break;
                        case 7:
                            return '<span class="badge badge-danger" > ' . __('Not interested') . ' </span > ';
                            break;
                        case 11:
                            return '<span class="badge badge-danger" > ' . __('Low budget') . ' </span > ';
                            break;
                        case 9:
                            return '<span class="badge badge-danger" > ' . __('Wrong Number') . ' </span > ';
                            break;
                        case 14:
                            return '<span class="badge badge-danger" > ' . __('Unqualified') . ' </span > ';
                            break;
                    };
                })
            ->addColumn('priority', function ($clients) {
                $i = $clients->priority;
                switch ($i) {
                    case 1:
                        return '<label class="txt-success f-w-600" > ' . __('Low') . ' </label > ';
                        break;
                    case 2:
                        return '<label class="txt-warning f-w-600" > ' . __('Medium') . ' </label > ';
                        break;
                    case 3:
                        return '<label class="txt-danger f-w-600" > ' . __('High') . ' </label > ';
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

    public function agenciesAll()
    {
        $agencies = Agency::with(['clients'])->select(['id', 'name', 'company_type', 'phone', 'created_at'])->get();
        return DataTables::of($agencies)
            ->editColumn('company_type', function ($agency) {
                return $agency->company_type === 1 ? __('Company') : __('Freelance');
            })
            ->editColumn('name', function ($agency) {
                if (auth()->user()->hasPermissionTo('department-agencies-sell')) {
                    return '<div class="product-name" ><a href = "agencies/' . $agency->id . '" > ' . $agency->name . '</a ></div > ';
                } else {
                    return '<div class="product-name" ><a href = "agencies/' . $agency->id . '/edit" > ' . $agency->name . '</a ></div > ';

                }
            })
            ->addColumn('created_at',
                function ($agency) {
                    return optional($agency->created_at)->format('d-m-Y') ?? '';
                })
            ->editColumn('phone', function ($agency) {
                return $agency->phone;
            })
            ->rawColumns(['name'])
            ->make(true);
    }

    public function todayTask()
    {
        $todayTasks = Task::with(['agency', 'client'])->archive(false)->whereDate('date', Carbon::today());
        return Datatables::of($todayTasks)
            ->setRowId('id')
            ->editColumn('title', function ($todayTask) {
                if ($todayTask->source_type === 'App\Agency') {
                    return '<a href="' . route('agencies.edit', ['agency' => $todayTask->agency->id]) . '" class="email-name" >' . $todayTask->title ?? '' . '</a>';
                } else {
                    return '<a href="' . route('clients.edit', ['client' => $todayTask->client_id]) . '"class="email-name" >' . $todayTask->title ?? '' . '</a>';
                }
            })
            ->editColumn('name', function ($todayTask) {
                return $todayTask->client->full_name ?? $todayTask->agency->title ?? '';
            })
            ->editColumn('country', function ($todayTask) {
                if (is_null($todayTask->client->country)) {
                    return '<div class="col-form-label">' . $todayTask->client->getRawOriginal('country') ?? '' . '</div>';
                } else {
                    $cou = '';
                    $countries = collect($todayTask->client->country)->toArray();
                    foreach ($countries as $name) {
                        $cou .= ' <span class="badge badge-inverse"> ' . $name . '</span> ';
                    }
                    return $cou;
                }
            })
            ->editColumn('nationality', function ($todayTask) {
                if (is_null($todayTask->client->nationality)) {
                    return $todayTask->client->getRawOriginal('nationality') ?? '';
                } else {
                    $cou = '';
                    $nat = collect($todayTask->client->nationality)->toArray();
                    foreach ($nat as $name) {
                        $cou .= ' <span class="badge badge-inverse"> ' . $name . '</span> ';
                    }
                    return $cou;
                }
            })
            ->addColumn('date',
                function ($todayTask) {
                    return optional($todayTask->date)->format('d-m-Y') ?? '';
                })
            ->rawColumns(['title', 'name', 'country', 'nationality', 'date'])
            ->make(true);

    }

    public function tomorrowTasks()
    {
        $tomorrowTasks = Task::with(['agency', 'client'])->archive(false)->whereDate('date', Carbon::tomorrow());
        return Datatables::of($tomorrowTasks)
            ->setRowId('id')
            ->editColumn('title', function ($tomorrowTask) {
                if ($tomorrowTask->source_type === 'App\Agency') {
                    return '<a href="' . route('agencies.edit', ['agency' => $tomorrowTask->agency->id]) . '" class="email-name" >' . $tomorrowTask->title ?? '' . '</a>';
                } else {
                    return '<a href="' . route('clients.edit', ['client' => $tomorrowTask->client_id]) . '"class="email-name" >' . $tomorrowTask->title ?? '' . '</a>';
                }
            })
            ->editColumn('name', function ($tomorrowTask) {
                return $tomorrowTask->client->full_name ?? $tomorrowTask->agency->title ?? '';
            })
            ->editColumn('country', function ($tomorrowTask) {
                if (is_null($tomorrowTask->client->country)) {
                    return '<div class="col-form-label">' . $tomorrowTask->client->getRawOriginal('country') ?? '' . '</div>';
                } else {
                    $cou = '';
                    $countries = collect($tomorrowTask->client->country)->toArray();
                    foreach ($countries as $name) {
                        $cou .= '<span class="badge badge-inverse"> ' . $name . '</span> ';
                    }
                    return $cou;
                }
            })
            ->editColumn('nationality', function ($tomorrowTask) {
                if (is_null($tomorrowTask->client->nationality)) {
                    return $tomorrowTask->client->getRawOriginal('nationality') ?? '';
                } else {
                    $cou = '';
                    $nat = collect($tomorrowTask->client->nationality)->toArray();
                    foreach ($nat as $name) {
                        $cou .= '<span class="badge badge-inverse">' . $name . '</span> ';
                    }
                    return $cou;
                }
            })
            ->addColumn('date',
                function ($tomorrowTask) {
                    return optional($tomorrowTask->date)->format('d-m-Y') ?? '';
                })
            ->rawColumns(['title', 'name', 'country', 'nationality', 'date'])
            ->make(true);
    }

    public function pendingTasks()
    {
        $pendingTasks = Task::with(['agency', 'client'])->archive(false)->whereDate('date', '<', Carbon::today());
        return Datatables::of($pendingTasks)
            ->setRowId('id')
            ->editColumn('title', function ($pendingTask) {
                if ($pendingTask->source_type === 'App\Agency') {
                    return '<a href="' . route('agencies.edit', ['agency' => $pendingTask->agency->id]) . '" class="email-name" >' . $pendingTask->title ?? '' . '</a>';
                } else {
                    return '<a href="' . route('clients.edit', ['client' => $pendingTask->client_id]) . '"class="email-name" >' . $pendingTask->title ?? '' . '</a>';
                }
            })
            ->editColumn('name', function ($pendingTask) {
                return $pendingTask->client->full_name ?? $pendingTask->agency->title ?? '';
            })
            ->editColumn('country', function ($pendingTask) {
                if (is_null($pendingTask->client->country)) {
                    return '<div class="col-form-label">' . $pendingTask->client->getRawOriginal('country') ?? '' . '</div>';
                } else {
                    $cou = '';
                    $countries = collect($pendingTask->client->country)->toArray();
                    foreach ($countries as $name) {
                        $cou .= '<span class="badge badge-inverse"> ' . $name . '</span> ';
                    }
                    return $cou;
                }
            })
            ->editColumn('nationality', function ($pendingTask) {
                if (is_null($pendingTask->client->nationality)) {
                    return $pendingTask->client->getRawOriginal('nationality') ?? '';
                } else {
                    $cou = '';
                    $nat = collect($pendingTask->client->nationality)->toArray();
                    foreach ($nat as $name) {
                        $cou .= '<span class="badge badge-inverse">' . $name . '</span> ';
                    }
                    return $cou;
                }
            })
            ->addColumn('date',
                function ($pendingTask) {
                    return optional($pendingTask->date)->format('d-m-Y') ?? '';
                })
            ->rawColumns(['title', 'name', 'country', 'nationality', 'date'])
            ->make(true);
    }

    public function completedTasks()
    {
        $completedTasks = Task::with(['agency', 'client'])->archive(true);
        return Datatables::of($completedTasks)
            ->setRowId('id')
            ->addColumn('checked', '<div class="round-product"><i class="icofont icofont-check"></i></div>')
            ->editColumn('title', function ($completedTask) {
                if ($completedTask->source_type === 'App\Agency') {
                    return '<a href="' . route('agencies.edit', ['agency' => $completedTask->agency->id]) . '" class="email-name" >' . $completedTask->title ?? '' . '</a>';
                } else {
                    return '<a href="' . route('clients.edit', ['client' => $completedTask->client_id]) . '"class="email-name" >' . $completedTask->title ?? '' . '</a>';
                }
            })
            ->editColumn('name', function ($completedTask) {
                return $completedTask->client->full_name ?? $completedTask->agency->title ?? '';
            })
            ->editColumn('country', function ($completedTask) {
                if (is_null($completedTask->client->country)) {
                    return '<div class="col-form-label">' . $completedTask->client->getRawOriginal('country') ?? '' . '</div>';
                } else {
                    $cou = '';
                    $countries = collect($completedTask->client->country)->toArray();
                    foreach ($countries as $name) {
                        $cou .= '<span class="badge badge-inverse"> ' . $name . '</span> ';
                    }
                    return $cou;
                }
            })
            ->editColumn('nationality', function ($completedTask) {
                if (is_null($completedTask->client->nationality)) {
                    return $completedTask->client->getRawOriginal('nationality') ?? '';
                } else {
                    $cou = '';
                    $nat = collect($completedTask->client->nationality)->toArray();
                    foreach ($nat as $name) {
                        $cou .= '<span class="badge badge-inverse">' . $name . '</span> ';
                    }
                    return $cou;
                }
            })
            ->editColumn('date',
                function ($completedTask) {
                    return optional($completedTask->date)->format('d-m-Y') ?? '';
                })
            ->editColumn('updated_at', function ($completedTask) {
                return Carbon::parse($completedTask->updated_at)->format('d-m-Y');
            })
            ->rawColumns(['checked', 'title', 'name', 'country', 'nationality', 'date'])
            ->make(true);
    }

    public function getCountry(Request $request)
    {
        $data = [];

        $key = $request->get('q');

        if ($key) {
            $data = Country::where(function ($query) use ($key) {
                $query->where('id', 'like', ' % ' . $key . ' % ')
                    ->orWhere('name', 'LIKE', ' % ' . $key . ' % ');
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
                $query->where('id', 'like', ' % ' . $key . ' % ')
                    ->orWhere('name', 'LIKE', ' % ' . $key . ' % ');
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
                $query->where('id', 'like', ' % ' . $key . ' % ')
                    ->orWhere('name', 'LIKE', ' % ' . $key . ' % ');
            })->get(['id', 'name']);
        }
        return response()->json($data);
    }
}
