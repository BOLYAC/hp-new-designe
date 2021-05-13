<?php

namespace App\Http\Controllers;

use App\Agency;
use App\Imports\ClientsImport;
use App\Imports\LeadsImport;
use App\Models\Client;

use App\Models\Source;
use App\Models\Task;
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
        $users = User::all();
        $sources = Source::all();
        $agencies = Agency::all();


        return view('clients.index', compact('users', 'sources', 'agencies'));
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
            $d = $request->get('phone_check');
            switch ($d) {
                case '1':
                    $clients->Where('client_number', 'LIKE', '%' . $request->get('country') . '%')
                        ->orWhere('client_number_2', 'LIKE', '%' . $request->get('country') . '%');
                    break;
                case '2':
                    $clients->Where('client_number', 'not like', '%' . $request->get('country') . '%')
                        ->orWhere('client_number_2', 'not like', '%' . $request->get('country') . '%');
                    break;
                case '3':
                    $clients->Where('client_number', 'LIKE', '%' . $request->get('country') . '%')
                        ->orWhere('client_number_2', 'LIKE', '%' . $request->get('country') . '%');
                    break;
                case '4':
                    $clients->Where('client_number', 'not like', '%' . $request->get('country') . '%')
                        ->orWhere('client_number_2', 'not like', '%' . $request->get('country') . '%');
                    break;
                case '5':
                    $clients->Where('client_number', 'like', $request->get('country') . '%')
                        ->orWhere('client_number_2', 'like', $request->get('country') . '%');
                    break;
                case '6':
                    $clients->Where('client_number', 'like', '%' . $request->get('country'))
                        ->orWhere('client_number_2', 'like', '%' . $request->get('country'));
                    break;
                case '7':
                    $clients->whereNull('client_number')->orWhere('client_number', '=', '')
                        ->orWhereNull('client_number_2')->orWhere('client_number_2', '=', '');
                    break;
                case '8':
                    $clients->whereNotNull('client_number')->orWhere('client_number', '<>', '')
                        ->orwhereNotNull('client_number_2')->orWhere('client_number_2', '<>', '');
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
                return '<a href="clients/' . $clients->id . '/edit">' . $clients->full_name . '</a>';
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
                            return '<span class="label label-inverse-info">' . __('New Lead') . '</span>';
                            break;
                        case 8:
                            return '<span class="label label-inverse-info">' . __('No Answer') . '</span>';
                            break;
                        case 12:
                            return '<span class="label label-inverse-info">' . __('In progress') . '</span>';
                            break;
                        case 3:
                            return '<span class="badge badge-light-primary">' . __('Potential
                appointment') . '</span>';
                            break;
                        case 4:
                            return '<span class="badge badge-light-primary">' . __('Appointment set') . '</span>';
                            break;
                        case 10:
                            return '<span class="badge badge-light-primary">' . __('Appointment
                follow up') . '</span>';
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
                        return '<label class="txt-success">' . __('Low') . '</label>';
                        break;
                    case 2:
                        return '<label class="txt-warning">' . __('Medium') . '</label>';
                        break;
                    case 3:
                        return '<label class="txt-danger">' . __('High') . '</label>';
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

        return redirect()->route('clients.edit', $client)->with('toast_success', 'Client created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param Client $client
     * @return view
     */
    public function show(Client $client): View
    {
        return view('clients.show', compact('client'));
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
        $notes = $client->notes()->get();
        $clientDocuments = $client->documents()->get();
        $previous_record = Client::where('id', '<', $client->id)->orderBy('id', 'desc')->first();
        $next_record = Client::where('id', '>', $client->id)->orderBy('id')->first();
        $tasks = Task::whereHas('client', function ($query) use ($client) {
            $query->where('client_id', $client->id);
        })->get()->sortByDesc('created_at');
        return view('clients.edit', compact('client', 'notes', 'tasks', 'users', 'sources', 'agencies', 'clientDocuments', 'next_record', 'previous_record'));
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
            'client_email' => ['nullable', 'string', 'email', 'max:255', 'unique:clients,client_email,' . $client->id . ',id,deleted_at,NULL'],
            'client_email_2' => ['nullable', 'string', 'email', 'max:255', 'unique:clients,client_email_2,' . $client->id . ',id,deleted_at,NULL'],
            'client_number' => ['nullable', 'string', 'max:255', 'unique:clients,client_number,' . $client->id . ',id,deleted_at,NULL'],
            'client_number_2' => ['nullable', 'string', 'max:255', 'unique:clients,client_number_2,' . $client->id . ',id,deleted_at,NULL'],
        ]);

        $data_request = $request->except('_token', 'files');

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

        /*        */
//+    $fullname = $request->first_name . ' ' . $request->last_name;

        $data_request['type'] = $request->has('type') ? 1 : 0;
        if (!$request->lang) {
            $data_request['lang'] = null;
        }
        if (!$request->country) {
            $data_request['country'] = null;
        }
        if (!$request->nationality) {
            $data_request['nationality'] = null;
        }

        $data_request['type'] = 1;

        $client->fill($data_request)->save();
        /*$client->fill([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'full_name' => $fullname === '' ? $request->full_name : $fullname,
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
                'appointment_date' => $request->appointment_date,
                'type' => $request->has('type') ? 1 : 0,
                'status' => $request->status,
                'requirements' => $request->requirements,
                'priority' => $request->priority,
                //'source' => $request->source,
                'source_id' => $request->source_id,

            ])->save();*/

        return redirect()->route('clients.edit', $client)->with('toast_success', 'Client updated successfully');
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
        return redirect()->route('clients.index')->with('toast_success', 'Client deleted successfully');
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

            return redirect()->route('clients.index')->with('toast_success', 'File upload  successfully');
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
            $user = $request->get('user_id');
            $u = User::findOrFail($user);
            $source = $request->get('source_id');
            $team = $u->currentTeam->id;
            $file = $request->file('file');

            $import = new LeadsImport($user, $source, $team);

            $import->import($file);


            if ($import->failures()->isNotEmpty()) {
                return back()->withFailures($import->failures());
            }

            return redirect()->route('clients.index')->with('toast_success', 'File upload  successfully');
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

        return redirect()->back()->with('toast_success', 'File upload  successfully');
    }
}
