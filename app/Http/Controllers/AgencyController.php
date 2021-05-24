<?php

namespace App\Http\Controllers;

use App\Agency;
use App\Models\Note;
use App\Models\Task;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class AgencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    function __construct()
    {
        $this->middleware('permission:agency-list|agency-create|agency-edit|agency-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:agency-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:agency-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:agency-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|Response
     * @throws Exception
     */
    public function index(Request $request)
    {
        $agencies = Agency::with(['clients'])->select(['id', 'name', 'company_type', 'phone'])->get();
        if ($request->ajax()) {
            return DataTables::of($agencies)
                ->editColumn('company_type', function ($agency) {
                    return $agency->company_type === 1 ? __('Company') : __('Freelance');
                })
                ->editColumn('name', function ($agency) {
                    if (auth()->user()->hasPermissionTo('department-agencies-sell')) {
                        return '<a href="agencies/' . $agency->id . '">' . $agency->name . '</a>';
                    } else {
                        return '<a href="agencies/' . $agency->id . '/edit">' . $agency->name . '</a>';
                    }
                })
                ->editColumn('phone', function ($agency) {
                    return $agency->phone;
                })
                ->addColumn('action',
                    '<a class="dropdown-toggle addon-btn" data-toggle="dropdown"
            aria-expanded="true">
              <i class="icofont icofont-ui-settings"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-right">
              @can(\'agency-edit\')
                  <a class="dropdown-item pl-2" href="{{ route(\'agencies.edit\', $id) }}">
                  <i class="fa fa-edit"></i> {{ __(\'Edit agency\') }}</a>
                  @can(\'department-agencies-sell\')
                  <a class="dropdown-item pl-2" href="{{ route(\'agencies.sells-office-edit\', $id) }}">
                  <i class="fa fa-edit"></i> {{ __(\'edit agency sells\') }}</a>
                  @endcan
              @endcan
              @can(\'client-delete\')
                  <form
                      action="{{ route(\'agencies.destroy\', $id) }}"
                      method="post" role="form">
                      @csrf
                      @method(\'DELETE\')
                      <button type="submit"
                              class="dropdown-item pl-2">
                          <i class="icon-trash"></i>  {{ __(\'Delete agency\') }}
                      </button>
                  </form>
              @endcan
          </div>')
                ->addColumn('details_url', function ($agency) {
                    return route('api.agency_single_details', $agency->id);
                })
                ->rawColumns(['name', 'action'])
                ->make(true);
        }
        return view('agencies.index');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|Response|View
     */
    public function create()
    {
        return view('agencies.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {

        $data = $request->except('_token');

        $data['status'] = $request->has('status') ? 1 : 0;
        Agency::create($data);

        return redirect()->route('agencies.index')
            ->with('toast_success', 'Agency created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return void
     */
    public function show($id): void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Agency $agency
     * @return Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|Response
     */
    public function edit(Agency $agency)
    {
        $agency->with('clients')->get();
        return view('agencies.edit', compact('agency'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Agency $agency
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Agency $agency): \Illuminate\Http\RedirectResponse
    {
        $data = $request->except('_token', '_method');
        $data['status'] = $request->has('status') ? 1 : 0;
        $agency->forceFill($data)->save();

        return redirect()->route('agencies.index')
            ->with('toast_success', 'Agency updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Agency $agency
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Agency $agency): \Illuminate\Http\RedirectResponse
    {
        $agency->delete();
        return redirect()->route('agencies.index')
            ->with('toast_success', 'Agency deleted successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Agency $agency
     * @param $id
     * @return Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|Response
     */
    public function getAgencySellsOffice(Agency $agency, $id)
    {

        $agency = Agency::findOrFail($id);

        $agency->with('clients')->get();

        $tasks = Task::whereHasMorph(
            'Taskable',
            ['App\Agency'],
            function ($query) use ($agency) {
                $query->where('agency_id', $agency->id);
            }
        )->get()->sortByDesc('created_at');

        $notes = Note::whereHasMorph(
            'Noteable',
            [Agency::class],
            function ($query) use ($agency) {
                $query->where('agency_id', $agency->id);
            }
        )->get()->sortByDesc('date')->sortByDesc('favorite');

        return view('agencies.sells-office-edit', compact('agency', 'tasks'));
    }
}
