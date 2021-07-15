<?php

namespace App\Http\Controllers;

use App\Agency;
use App\Imports\AgenciesImport;
use App\Imports\ClientsImport;
use App\Imports\LeadsImport;
use App\Models\Client;

use App\Models\Department;
use App\Models\Lead;
use App\Models\Source;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use JsonException;
use Maatwebsite\Excel\HeadingRowImport;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class ClientsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     */
    public function __construct()
    {
        $this->middleware('permission:client-list|client-create|client-edit|client-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:client-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:client-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:client-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $sources = Source::all();
        $agencies = Agency::all();

        if (\auth()->user()->hasRole('Admin')) {
            $users = User::all();
            $teams = Team::all();
            $departments = Department::all();
            return view('clients.index', compact('users', 'sources', 'agencies', 'departments', 'teams'));
        } elseif (\auth()->user()->hasPermissionTo('team-manager')) {
            if (auth()->user()->ownedTeams()->count() > 0) {
                $teams = auth()->user()->ownedTeams;
                //$teams = auth()->user()->allTeams();
                foreach ($teams as $u) {
                    foreach ($u->users as $ut) {
                        $users[] = $ut;
                    }
                }
            }
            return view('clients.index', compact('users', 'sources', 'agencies', 'teams'));
        } elseif (\auth()->user()->hasPermissionTo('desk-manager')) {
            $teams = Team::whereIn('id', ['4', '7', '15']);
            foreach ($teams as $u) {
                foreach ($u->users as $ut) {
                    $users[] = $ut->id;
                }
            }
            return view('clients.index', compact('users', 'sources', 'agencies', 'teams'));
        } elseif (\auth()->user()->hasPermissionTo('multiple-department')) {
            $teams = Team::whereIn('id', ['5', '4', '7', '15']);
            foreach ($teams as $u) {
                foreach ($u->users as $ut) {
                    $users[] = $ut->id;
                }
            }
            return view('clients.index', compact('users', 'sources', 'agencies', 'teams'));
        } else {
            $users = User::all();
            $teams = Team::all();
            return view('clients.index', compact('users', 'sources', 'agencies', 'teams'));
        }
    }

    /**
     * Make json response for datatable
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function anyData(Request $request)
    {
        $clients = Client::with(['source', 'user', 'tasks', 'agency']);

        if ($request->get('status')) {
            $clients->where('status', '=', $request->get('status'));
        }
        if ($request->get('source')) {
            $clients->where('source_id', '=', $request->get('source'));
        }
        if ($request->get('priority')) {
            $clients->where('priority', '=', $request->get('priority'));
        }
        if ($request->get('agency')) {
            $clients->where('agency_id', '=', $request->get('agency'));
        }
        if ($request->get('user')) {
            $clients->where('user_id', '=', $request->get('user'));
        }
        if ($request->get('team')) {
            $clients->where('team_id', '=', $request->get('team'));
        }
        if ($request->get('department')) {
            $clients->where('department_id', '=', $request->get('department'));
        }
        if ($request->get('daysActif')) {
            $clients->where('updated_at', '<=', \Carbon\Carbon::today()->subDays($request->get('daysActif')));
        }

        if ($request->country_check === 'true') {
            $d = $request->get('country_type');
            switch ($d) {
                case '1':
                    $clients->Where('country', 'LIKE', '%' . $request->get('country') . '%')
                        ->orWhereJsonContains('country', $request->get('country'));
                    break;
                case '2':
                    $clients->Where('country', 'not like', '%' . $request->get('country') . '%')
                        ->orWhereJsonDoesntContain('country', $request->get('country'));
                    break;
                case '3':
                    $clients->Where('country', 'sounds like', '%' . $request->get('country') . '%');
                    break;
                case '4':
                    $clients->Where('country', 'not sounds like', '%' . $request->get('country') . '%');
                    break;
                case '5':
                    $clients->Where('country', 'like', $request->get('country') . '%');
                    break;
                case '6':
                    $clients->Where('country', 'like', '%' . $request->get('country'));
                    break;
                case '7':
                    $clients->whereNull('country')->orWhere('country', '=', '');
                    break;
                case '8':
                    $clients->whereNotNull('country')->orWhere('country', '<>', '');
                    break;
            }
        }
        if ($request->phone_check === 'true') {
            $d = $request->get('phone_type');
            switch ($d) {
                case '1':
                    $clients->Where('client_number', 'LIKE', '%' . $request->get('phone') . '%')
                        ->orWhere('client_number_2', 'LIKE', '%' . $request->get('phone') . '%');
                    break;
                case '2':
                    $clients->Where('client_number', 'not like', '%' . $request->get('phone') . '%')
                        ->orWhere('client_number_2', 'not like', '%' . $request->get('phone') . '%');
                    break;
                case '3':
                    $clients->Where('client_number', 'LIKE', '%' . $request->get('phone') . '%')
                        ->orWhere('client_number_2', 'LIKE', '%' . $request->get('phone') . '%');
                    break;
                case '4':
                    $clients->Where('client_number', 'not like', '%' . $request->get('phone') . '%')
                        ->orWhere('client_number_2', 'not like', '%' . $request->get('phone') . '%');
                    break;
                case '5':
                    $clients->Where('client_number', 'like', $request->get('phone') . '%')
                        ->orWhere('client_number_2', 'like', $request->get('phone') . '%');
                    break;
                case '6':
                    $clients->Where('client_number', 'like', '%' . $request->get('phone'))
                        ->orWhere('client_number_2', 'like', '%' . $request->get('phone'));
                    break;
                case '7':
                    $clients->whereNull('client_number')->orWhere('client_number', '=', 'phone')
                        ->orWhereNull('client_number_2')->orWhere('client_number_2', '=', 'phone');
                    break;
                case '8':
                    $clients->whereNotNull('client_number')->orWhere('client_number', '<>', 'phone')
                        ->orwhereNotNull('client_number_2')->orWhere('client_number_2', '<>', 'phone');
                    break;
            }
        }

        if ($request->filterDateBase !== 'none') {
            $date = explode('-', $request->get('daterange'));
            $from = $date[0];
            $to = $date[1];

            $from = Carbon::parse($from)
                ->startOfDay()        // 2018-09-29 00:00:00.000000
                ->toDateTimeString(); // 2018-09-29 00:00:00

            $to = Carbon::parse($to)
                ->endOfDay()          // 2018-09-29 23:59:59.000000
                ->toDateTimeString(); // 2018-09-29 23:59:59

            $d = $request->get('filterDateBase');
            switch ($d) {
                case 'creation':
                    $clients->whereBetween('created_at', [$from, $to]);
                    break;
                case 'modification':
                    $clients->whereBetween('updated_at', [$from, $to]);
                    break;
                case 'arrival':
                    $clients->whereBetween('appointment_date', [$from, $to]);
                    break;
            }
        }

        if ($request->lastUpdate === 'true') {
            $clients->whereHas('tasks', function ($query) {
                $query->where('archive', '=', 0);
            }, '=', 0)
                ->WhereDoesntHave('tasks');
        }
        $clients->OrderByDesc('created_at');

        return Datatables::of($clients)
            ->setRowId('id')
            ->addColumn('check', '<input type="checkbox" class="checkbox-circle check-task" name="selected_clients[]" value="{{ $id }}">')
            ->editColumn('public_id', function ($clients) {
                return $clients->public_id ?? '';
            })
            ->editColumn('full_name', function ($clients) {
                return '<a href="clients/' . $clients->id . '">' . $clients->complete_name ?? $clients->full_name . '</a>';
            })
            ->editColumn('country', function ($clients) {
                if (is_null($clients->country)) {
                    return $clients->getRawOriginal('country') ?? '';
                } else {
                    $cou = '';
                    $countries = collect($clients->country)->toArray();
                    foreach ($countries as $name) {
                        $cou .= '<span class="badge badge-pill badge-primary">' . $name . '</span>';
                    }
                    return $cou;
                }
            })
            ->editColumn(
                'status',
                function ($clients) {
                    $i = $clients->status;
                    switch ($i) {
                        case 1:
                            return '<span class="badge badge-light-info">' . __('New Lead') . '</span>';
                            break;
                        case 8:
                            return '<span class="badge badge-light-info">' . __('No Answer') . '</span>';
                            break;
                        case 12:
                            return '<span class="badge badge-light-info">' . __('In progress') . '</span>';
                            break;
                        case 3:
                            return '<span class="badge badge-light-primary">' . __('Potential appointment') . '</span>';
                            break;
                        case 4:
                            return '<span class="badge badge-light-primary">' . __('Appointment set') . '</span>';
                            break;
                        case 10:
                            return '<span class="badge badge-light-primary">' . __('Appointment follow up') . '</span>';
                            break;
                        case 5:
                            return '<span class="badge badge-light-success">' . __('Sold') . '</span>';
                            break;
                        case 13:
                            return '<span class="badge badge-light-warning">' . __('Unreachable') . '</span>';
                            break;
                        case 7:
                            return '<span class="badge badge-light-warning">' . __('Not interested') . '</span>';
                            break;
                        case 11:
                            return '<span class="badge badge-light-warning">' . __('Low budget') . '</span>';
                            break;
                        case 9:
                            return '<span class="badge badge-light-warning">' . __('Wrong Number') . '</span>';
                            break;
                        case 14:
                            return '<span class="badge badge-light-warning">' . __('Unqualified') . '</span>';
                            break;
                        case 15:
                            return '<span class="badge badge-light-warning">' . __('Lost') . '</span>';
                            break;
                    }
                }
            )
            ->editColumn(
                'source_id',
                function ($clients) {
                    return optional($clients->source)->name;
                }
            )
            ->editColumn(
                'agency_id',
                function ($clients) {
                    return optional($clients->agency)->name;
                }
            )
            ->editColumn('priority', function ($clients) {
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
                }
            })
            ->editColumn(
                'user_id',
                function ($clients) {
                    return '<span class="badge badge-success">' . optional($clients->user)->name . '</span> <a href="#" class="assign"><i class="icofont icofont-plus f-w-600"></i></a>';
                }
            )
            ->editColumn('action', '<a class="dropdown-toggle addon-btn" data-toggle="dropdown"
                                                       aria-expanded="true">
                                                        <i class="icofont icofont-ui-settings"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                    @can(\'client-edit\')
                                                            <a class="dropdown-item" href="{{ route(\'clients.show\', $id) }}">
                                                            <i class="fa fa-eye"></i>show lead</a>
                                                        @endcan
                                                        @can(\'client-edit\')
                                                            <a class="dropdown-item" href="{{ route(\'clients.edit\', $id) }}">
                                                            <i class="icofont icofont-ui-edit"></i>Edit lead</a>
                                                        @endcan
                                                        @can(\'client-delete\')
                                                            <form
                                                                action="{{ route(\'clients.destroy\', $id) }}"
                                                                method="post" role="form">
                                                                @csrf
                                                                @method(\'DELETE\')
                                                                <button type="submit"
                                                                        class="dropdown-item">
                                                                    <i class="icofont icofont-trash"></i> Delete lead
                                                                </button>
                                                            </form>
                                                        @endcan
                                                    </div>')
            ->addColumn(
                'created_at',
                function ($clients) {
                    return '<span class="text-semibold">' . $clients->created_at->format('Y/m/d') . '</span>';
                }
            )
            ->rawColumns(['check', 'client_number', 'client_email', 'full_name', 'country', 'status', 'priority', 'user_id', 'action', 'created_at'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|Response|View
     */
    public function create()
    {
        $users = User::all();
        $sources = Source::all();
        $agencies = Agency::all();
        return view('clients.create', compact('users', 'sources', 'agencies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'source' => 'required',
            'agency' => 'required',
            'client_email' => ['nullable', 'string', 'email', 'max:255', 'unique:clients,client_email,deleted_at'],
            'client_email_2' => ['nullable', 'string', 'email', 'max:255', 'unique:clients,client_email_2,deleted_at'],
            'client_number' => ['nullable', 'string', 'max:255', 'unique:clients,client_number,deleted_at'],
            'client_number_2' => ['nullable', 'string', 'max:255', 'unique:clients,client_number_2,deleted_at'],
        ]);
        $client = Client::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'full_name' => $request->first_name . ' ' . $request->last_name,
            'client_number' => $request->client_number,
            'client_number_2' => $request->client_number_2,
            'client_email' => $request->client_email,
            'client_email_2' => $request->client_email_2,
            'address' => $request->address,
            'zipcode' => $request->zipcode,
            'city' => $request->city,
            'country' => $request->country,
            'nationality' => $request->nationality,
            'budget' => $request->budget,
            'rooms' => $request->rooms,
            'appointment_date' => $request->appointment_date ?? now(),
            'type' => $request->has('type') ? 1 : 0,
            'status' => $request->status,
            'requirements' => $request->requirements,
            'priority' => $request->priority,
            'agency_id' => $request->agency,
            'lang' => $request->lang,
            'source_id' => $request->source,
            'user_id' => Auth::id(),
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
            'description' => $request->description,
            'duration_stay' => $request->duration_stay,
            'budget_request' => $request->budget_request,
            'rooms_request' => $request->rooms_request,
            'requirements_request' => $request->requirements_request
        ]);

        return redirect()->route('clients.edit', $client)->with('toast_success', __('Lead created successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param Client $client
     * @return Application|Factory|\Illuminate\Contracts\View\View|View
     */
    public function show(Client $client)
    {
        $users = User::all();
        return view('clients.show', compact('client', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Client $client
     * @return Application|Factory|View
     */
    public function edit(Client $client)
    {
        $users = User::all();
        $sources = Source::all();
        $agencies = Agency::all();
        $clientDocuments = $client->documents()->get();
        $previous_record = Client::where('id', '<', $client->id)->orderBy('id', 'desc')->first();
        $next_record = Client::where('id', '>', $client->id)->orderBy('id')->first();
        return view('clients.edit', compact('client', 'users', 'sources', 'agencies', 'clientDocuments', 'next_record', 'previous_record'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Client $client
     * @return RedirectResponse
     */
    public function update(Request $request, Client $client): RedirectResponse
    {
        $request->validate([
            'source_id' => 'required',
            'agency_id' => 'required',
            'client_email' => ['nullable', 'string', 'email', 'max:255', 'unique:clients,client_email,' . $client->id . ',id,deleted_at,NULL'],
            'client_email_2' => ['nullable', 'string', 'email', 'max:255', 'unique:clients,client_email_2,' . $client->id . ',id,deleted_at,NULL'],
            'client_number' => ['nullable', 'string', 'max:255', 'unique:clients,client_number,' . $client->id . ',id,deleted_at,NULL'],
            'client_number_2' => ['nullable', 'string', 'max:255', 'unique:clients,client_number_2,' . $client->id . ',id,deleted_at,NULL'],
        ]);

        $data_request = $request->except('_token', 'files');

        $oldStatus = $client->status;

        if ($client->client_number) {
            if (auth()->user()->can('cant-update-field') && isset($data_request['client_number'])) {
                unset($data_request['client_number']);
            }
        }
        if ($client->client_number_2) {
            if (auth()->user()->can('cant-update-field') && isset($data_request['client_number_2'])) {
                unset($data_request['client_number_2']);
            }
        }

        if (!$request->lang) {
            $data_request['lang'] = null;
        }
        if (!$request->country) {
            $data_request['country'] = null;
        }
        if (!$request->nationality) {
            $data_request['nationality'] = null;
        }

        $data_request['type'] = 0;

        $client->fill($data_request)->save();

        if ($client->status !== $oldStatus) {
            $i = $client->status;

            switch ($i) {
                case 1:
                    $status = 'New Lead';
                    break;
                case 8:
                    $status = 'No Answer';
                    break;
                case 12:
                    $status = 'In progress';
                    break;
                case 3:
                    $status = 'Potential appointment';
                    break;
                case 4:
                    $status = 'Appointment set';
                    break;
                case 10:
                    $status = 'Appointment follow up';
                    break;
                case 5:
                    $status = 'Sold';
                    break;
                case 13:
                    $status = 'Unreachable';
                    break;
                case 7:
                    $status = 'Not interested';
                    break;
                case 11:
                    $status = 'Low budget';
                    break;
                case 9:
                    $status = 'Wrong Number';
                    break;
                case 14:
                    $status = 'Unqualified';
                    break;
                case 15:
                    $status = 'Lost';
                    break;
            }

            $client->StatusLog()->create([
                'status_name' => $status,
                'updated_by' => \auth()->id(),
                'user_name' => \auth()->user()->name,
                'status_id' => $request->get('status')
            ]);
        }

        return redirect()->route('clients.edit', $client)->with('toast_success', __('Lead updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Client $client
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(Client $client): RedirectResponse
    {
        $client->delete();
        return redirect()->route('clients.index')->with('toast_success', __('Lead deleted successfully'));
    }


    public function importExportView()
    {
        $users = User::all();
        $sources = Source::all();
        return view('clients.import', compact('users', 'sources'));
    }

    public function importExportViewZoho()
    {
        $users = User::all();
        $sources = Source::all();
        return view('clients.zoho-import', compact('users', 'sources'));
    }


    public function import(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'file' => $request->file,
                'extension' => strtolower($request->file->getClientOriginalExtension()),
            ],
            [
                'file' => 'required',
                'extension' => 'required|in:xlsx,xls',
            ]
        );

        if ($request->hasFile('file')) {
            $user = $request->get('user_id');
            $u = User::findOrFail($user);
            $source = $request->get('source_id');
            $team = $u->currentTeam->id;
            $file = $request->file('file');

            $import = new ClientsImport($user, $source, $team);

            $import->import($file);


            if ($import->failures()->isNotEmpty()) {
                return back()->withFailures($import->failures());
            }

            return redirect()->route('clients.index')->with('toast_success', __('File upload successfully'));
        }
    }

    public function importFromZoho(Request $request)
    {


        $validator = Validator::make(
            $request->all(),
            [
                'file' => $request->file,
                'extension' => strtolower($request->file->getClientOriginalExtension()),
            ],
            [
                'file' => 'required',
                'extension' => 'required|in:xlsx,xls',
            ]
        );

        if ($request->hasFile('file')) {
            $user = User::findOrFail($request->get('user_id'));
            $source = $request->get('source_id');
            $team = $user->currentTeam->id;
            $file = $request->file('file');
            //$import = new AgenciesImport($user, $source, $team);
            /*$headings = (new HeadingRowImport)->toArray($file);
            dd($headings);*/
            $import = new LeadsImport($user, $source, $team);
            $import->import($file);
            if ($import->failures()->isNotEmpty()) {
                return back()->withFailures($import->failures());
            }

            return redirect()->route('clients.index')->with('toast_success', __('File upload successfully'));
        }
    }

    public function fetch(Request $request)
    {
        $key = $request->get('client_search');

        if ($key) {
            $resultClient = Client::where(function ($query) use ($key) {
                $query->where('full_name', 'like', '%' . $key . '%')
                    ->orWhere('public_id', 'LIKE', '%' . $key . '%')
                    ->orWhere('client_number', 'LIKE', '%' . $key . '%')
                    ->orWhere('client_number_2', 'LIKE', '%' . $key . '%')
                    ->orWhere('client_email', 'like', '%' . $key . '%')
                    ->orWhere('first_name', 'like', '%' . $key . '%')
                    ->orWhere('last_name', 'like', '%' . $key . '%');
            })->get(['full_name', 'client_email', 'client_number', 'client_number_2', 'id']);

            $data = $resultClient;

            try {
                return json_encode($data, JSON_THROW_ON_ERROR);
            } catch (JsonException $e) {
                return response()->json([
                    'message' => 'Something wrong'
                ], \Symfony\Component\HttpFoundation\Response::HTTP_OK);
            }
        }
    }

    public function listMember()
    {
        $members = Client::orderBy('created_at', 'desc')->get();
        return response()->json($members);
    }

    public function storeMember(Request $request)
    {
        $member = Client::create($request->all());

        return response()->json($member);
    }

    public function deleteMember($id)
    {
        Client::destroy($id);

        return response()->json("ok");
    }

    public function exampleClient()
    {
        $users = User::all();
        $sources = Source::all();
        $clients = Client::all();
        //dd($clients);
        return view('clients.example-2', compact('clients', 'users', 'sources'));
    }


    public function updateClient(Request $request)
    {

        $validator = \Validator::make($request->all(), [
            'update' => 'present|array',
        ]);

        if ($validator->fails()) {
            return back()->with('toast_error', $validator->messages()->all()[0])->withInput();
        }


        foreach ($request['update'] as $updateid) {
            $data = [
                'called' => $request->has('call-' . $updateid) ? 1 : 0,
                'spoken' => $request->has('speak-' . $updateid) ? 1 : 0,
                //'source_id' => $request['source_id-' . $updateid],
                'status' => $request['status-' . $updateid],
                'budget' => $request['budget-' . $updateid],
                'next_call' => $request['next_call-' . $updateid],
                'priority' => $request['priority-' . $updateid],
                //'user_id' => $request['inCharge-' . $updateid],
            ];
            DB::beginTransaction();
            try {
                DB::table('clients')->where('id', $updateid)
                    ->update($data);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                dd($e);
            }
        }

        return redirect()->back()->with('toast_success', __('File upload  successfully'));
    }

    public function composeEmail($email, Client $client)
    {
        return \view('inbox.compose', compact('email', 'client'));
    }

    public function getFieldReport()
    {
//        $drink = Drink::where('shopId', $request->session()->get('shopId'))->first();
//
//        if($drink) {
//            $drink = $drink->attributesToArray();
//        }
//        else {
//            $drink = Drink::firstOrNew(['shopId' => $request->session()->get('shopId')]);
//
//            // get the column names for the table
//            $columns = Schema::getColumnListing($drink->getTable());
//            // create array where column names are keys, and values are null
//            $columns = array_fill_keys($columns, null);
//
//            // merge the populated values into the base array
//            $drink = array_merge($columns, $drink->attributesToArray());
//        }

        $sources = Source::all();
        $agencies = Agency::all();

        $users = User::all();
        $teams = Team::all();
        $departments = Department::all();


        $client = new Client();
        $table = $client->getTable();
        $columns = DB::getSchemaBuilder()->getColumnListing($table);

        $remove = [
            'lead_id',
            'updated_at',
            'created_at',
            'deleted_at',
            'updated_by',
            'created_by',
            'next_call',
            'called',
            'spoken',
            'appointment_date',
            'zipcode',
            'address',
            'public_id',
            'id',
            'client_email',
            'client_email_2',
            'client_number',
            'client_number_2',
            'budget',
            'rooms',
            'duration_stay',
            'requirements',
            'type',
            'team_id',
            'department_id',
            'city'
        ];
        $newArr = array_filter($columns, function ($value) use ($remove) {
            return !in_array($value, $remove);
        });

        return \view('clients.field-report', compact('newArr', 'sources', 'agencies', 'users', 'teams', 'departments'));
    }

    public function postFieldReport(Request $request)
    {
        $data = $request->validate([
            "fields" => "required|array|min:3",
            "fields.*" => "required|string|distinct",
        ]);

        $r = [
            'tasks',
            'notes',
        ];

        $newArr = array_filter($data['fields'], function ($value) use ($r) {
            return !in_array($value, $r);
        });

        $leads = Client::query();
        $leads = $leads->with(['source', 'user', 'tasks', 'agency', 'notes']);

        if ($request->get('status')) {
            $leads->where('status', '=', $request->get('status'));
        }
        if ($request->get('source')) {
            $leads->where('source_id', '=', $request->get('source'));
        }
        if ($request->get('priority')) {
            $leads->where('priority', '=', $request->get('priority'));
        }
        if ($request->get('agency')) {
            $leads->where('agency_id', '=', $request->get('agency'));
        }
        if ($request->get('user')) {
            $leads->where('user_id', '=', $request->get('user'));
        }
        if ($request->get('team')) {
            $leads->where('team_id', '=', $request->get('team'));
        }
        if ($request->get('department')) {
            $leads->where('department_id', '=', $request->get('department'));
        }
        if ($request->get('daysActif')) {
            $leads->where('updated_at', '<=', \Carbon\Carbon::today()->subDays($request->get('daysActif')));
        }

        if ($request->country_check === 'true') {
            $d = $request->get('country_type');
            switch ($d) {
                case '1':
                    $leads->Where('country', 'LIKE', '%' . $request->get('country') . '%')
                        ->orWhereJsonContains('country', $request->get('country'));
                    break;
                case '2':
                    $leads->Where('country', 'not like', '%' . $request->get('country') . '%')
                        ->orWhereJsonDoesntContain('country', $request->get('country'));
                    break;
                case '3':
                    $leads->Where('country', 'sounds like', '%' . $request->get('country') . '%');
                    break;
                case '4':
                    $leads->Where('country', 'not sounds like', '%' . $request->get('country') . '%');
                    break;
                case '5':
                    $leads->Where('country', 'like', $request->get('country') . '%');
                    break;
                case '6':
                    $leads->Where('country', 'like', '%' . $request->get('country'));
                    break;
                case '7':
                    $leads->whereNull('country')->orWhere('country', '=', '');
                    break;
                case '8':
                    $leads->whereNotNull('country')->orWhere('country', '<>', '');
                    break;
            }
        }

        if ($request->filterDateBase !== 'none') {
            $date = explode('-', $request->get('daterange'));
            $from = $date[0];
            $to = $date[1];

            $from = Carbon::parse($from)
                ->startOfDay()        // 2018-09-29 00:00:00.000000
                ->toDateTimeString(); // 2018-09-29 00:00:00

            $to = Carbon::parse($to)
                ->endOfDay()          // 2018-09-29 23:59:59.000000
                ->toDateTimeString(); // 2018-09-29 23:59:59

            $d = $request->get('filterDateBase');
            switch ($d) {
                case 'creation':
                    $leads->whereBetween('created_at', [$from, $to]);
                    break;
                case 'modification':
                    $leads->whereBetween('updated_at', [$from, $to]);
                    break;
                case 'arrival':
                    $leads->whereBetween('appointment_date', [$from, $to]);
                    break;
            }
        }

        if ($request->lastUpdate === 'true') {
            $leads->whereHas('tasks', function ($query) {
                $query->where('archive', '=', 0);
            }, '=', 0)
                ->WhereDoesntHave('tasks');
        }

        $leads = $leads->get();

        $fields = $request->fields;
        return \View::make('clients.partials._table-report', compact('fields', 'leads'));

    }

    public function postViewReport()
    {
        return view('clients.report', compact('clients'));
    }
}
