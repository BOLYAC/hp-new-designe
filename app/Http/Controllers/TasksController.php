<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use JsonException;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    function __construct()
    {
        $this->middleware('permission:task-list|task-create|task-edit|task-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:task-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:task-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:task-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        if (\auth()->user()->hasRole('Admin')) {
            $users = User::all();
            $teams = Team::all();
            $departments = Department::all();
            return view('tasks.index', compact('users', 'departments', 'teams'));
        } elseif (\auth()->user()->hasPermissionTo('team-manager')) {
            if (auth()->user()->ownedTeams()->count() > 0) {
                $users = auth()->user()->currentTeam->allUsers();
                $teams = auth()->user()->allTeams();
            }
            return view('tasks.index', compact('users', 'teams'));
        }
    }

    /**
     * Make json respnse for datatables
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function anyData(Request $request)
    {
        $tasks = Task::with(['client', 'user', 'agency'])->select(['id', 'date', 'client_id', 'agency_id', 'title', 'user_id', 'archive', 'source_type']);

        if ($request->get('user')) {
            $tasks->where('user_id', '=', $request->get('user'));
        }
        if ($request->get('department')) {
            $tasks->where('department_id', '=', $request->get('department'));
        }
        if ($request->get('team')) {
            $tasks->where('team_id', '=', $request->get('team'));
        }
        if ($request->get('stat') == 1) {
            $tasks->archive(true);
        }
        if ($request->get('stat') == 2) {
            $tasks->archive(false);
        }

        if ($request->get('val') == 'custom') {
            $date = explode('-', $request->get('daterange'));
            $from = $date[0];
            $to = $date[1];

            $from = \Carbon\Carbon::parse($from)
                ->startOfDay()        // 2018-09-29 00:00:00.000000
                ->toDateTimeString(); // 2018-09-29 00:00:00

            $to = Carbon::parse($to)
                ->endOfDay()          // 2018-09-29 23:59:59.000000
                ->toDateTimeString(); // 2018-09-29 23:59:59

            $tasks->whereBetween('date', [$from, $to]);
        }

        $tasks->OrderByDesc('created_at');

        switch ($request->get('val')) {
            case 'today-tasks':
                $tasks->whereDate('date', Carbon::today());
                break;
            case 'future-tasks':
                $tasks->whereDate('date', '>', Carbon::today());
                break;
            case 'older-tasks':
                $tasks->whereDate('date', '<', Carbon::today());
                break;
            case 'pending-tasks':
                $tasks->archive(false)->whereDate('date', '<', Carbon::today());
                break;
            case 'completed-tasks':
                $tasks->archive(true);
                break;
        }

        return Datatables::of($tasks)
            ->setRowId('id')
            ->editColumn('date', function ($tasks) {
                return optional($tasks->date)->format('d-m-Y') ?? '';
            })
            ->editColumn('title', function ($tasks) {
                return $tasks->title ?? '';
            })
            ->editColumn('client_id', function ($tasks) {
                if ($tasks->source_type === 'App\Agency') {
                    if (auth()->user()->hasPermissionTo('department-agencies-sell')) {
                        return '<a href="/sales/agencies/' . $tasks->agency->id . '">' . $tasks->agency->name ?? '' . '</a>';
                    } else {
                        return '<a href="agencies/' . $tasks->agency->id . '/edit">' . $tasks->agency->name ?? '' . '</a>';
                    }
                } else {
                    return '<a href="clients/' . $tasks->client_id . '/edit">' . $tasks->client->full_name ?? '' . '</a>';
                }
            })
            ->addColumn('country', function ($tasks) {
                if (is_null($tasks->client->country)) {
                    return $tasks->client->getRawOriginal('country') ?? '';
                } else {
                    $cou = '';
                    $countries = collect($tasks->client->country)->toArray();
                    foreach ($countries as $name) {
                        $cou .= '<span class="badge badge-light-primary">' . $name . '</span>';
                    }
                    return $cou;
                }
            })
            ->addColumn('nationality', function ($tasks) {
                if (is_null($tasks->client->nationality)) {
                    return $tasks->client->getRawOriginal('nationality') ?? '';
                } else {
                    $cou = '';
                    $nat = collect($tasks->client->nationality)->toArray();
                    foreach ($nat as $name) {
                        $cou .= '<span class="badge badge-light-primary">' . $name . '</span>';
                    }
                    return $cou;
                }
            })
            ->addColumn('user_id',
                function ($tasks) {
                    return '<span class="badge badge-success">' . optional($tasks->user)->name . '</span> <a href="#" class="assign"><i class="icofont icofont-plus f-w-600"></i></a>';
                })
            ->addColumn('archive', function ($tasks) {
                return $tasks->archive === true ? '<label class="txt-success f-w-600">' . __('Done') . '</label>' : '<label class="txt-danger f-w-600">' . __('Pending') . '</label>';
            })
            ->addColumn('action', '<a class="dropdown-toggle addon-btn" data-toggle="dropdown"
                                                       aria-expanded="true">
                                                        <i class="icofont icofont-ui-settings"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        @can(\'task-edit\')
                                                            <a class="dropdown-item font-weight-bold" href="{{ route(\'tasks.edit\', $id) }}">
                                                            <i class="icofont icofont-ui-edit"></i>Edit task</a>
                                                        @endcan
                                                        @can(\'task-delete\')
                                                            <form
                                                                action="{{ route(\'tasks.destroy\', $id) }}"
                                                                method="post" role="form">
                                                                @csrf
                                                                @method(\'DELETE\')
                                                                <button type="submit"
                                                                        class="dropdown-item">
                                                                    <i class="icofont icofont-trash"></i> Delete task
                                                                </button>
                                                            </form>
                                                        @endcan
                                                    </div>')
            ->rawColumns(['user_id', 'archive', 'action', 'client_id', 'country', 'nationality'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public
    function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public
    function store(Request $request)
    {
        $data = $request->all();
        $data['user_id'] = Auth::id();

        Task::create($data);

        return redirect()->back()
            ->with('toast_success', 'Task created successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public
    function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Task $task
     * @return Application|Factory|View
     */
    public
    function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Task $task
     * @return Response
     */
    public
    function update(Request $request, Task $task)
    {
        $data = $request->all();
        $task->update($data);

        return redirect()->route('tasks.index')
            ->with('toast_success', 'Task created successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Task $task
     * @return void
     */
    public
    function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')
            ->with('toast_success', 'Task deleted successfully');
    }

    public
    function addTask(Request $request)
    {

        $note = Task::create([
            'title' => $request->nameTask,
            'body' => $request->bodyTask,
            'date' => $request->task_d,
            'client_id' => $request->client_id,
            'user_id' => Auth::id(),
        ]);

        $result = Task::query();
        $result->where('client_id', $request->client_id);
        $result->orderBy('created_at');
        $tasks = $result;
        return \View::make('clients.tasks.index', compact('tasks'));
    }

    public
    function archive(Request $request)
    {

        if ($request->get('archive')) {
            $task = Task::find($request->archive);
            $task->archive = !$task->archive;
            $task->update();
        }

        $result = Task::query();
        $result->where('client_id', $request->client_id);
        $result->orderBy('archive', 'ASC');
        $result->orderBy('created_at', 'DESC');
        $tasks = $result->get();

        return \View::make('clients.tasks.index', compact('tasks'));
    }

    public
    function assigneTask(Request $request)
    {
        $task = Task::find($request->task_assigned_id);
        $task->update([
            'user_id' => $request->user_id,
        ]);
        try {
            return json_encode($task, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return back()->withError($e->getMessage())->withInput();
        }
    }
}
