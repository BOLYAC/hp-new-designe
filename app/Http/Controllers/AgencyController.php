<?php

namespace App\Http\Controllers;

use App\Agency;
use App\Models\Client;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AgencyController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $agencies = Agency::with(['clients'])->select(['id', 'name'])->get();
      if ($request->ajax()) {
        return DataTables::of($agencies)
        ->editColumn('name', function ($agency) {
          return $agency->name;
        })
        ->addColumn('action', 
        '<a class="dropdown-toggle addon-btn" data-toggle="dropdown"
            aria-expanded="true">
              <i class="icofont icofont-ui-settings"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-right">
              @can(\'agency-edit\')
                  <a class="dropdown-item" href="{{ route(\'agencies.edit\', $id) }}">
                  <i class="icofont icofont-ui-edit"></i>Edit agency</a>
              @endcan
              @can(\'client-delete\')
                  <form
                      action="{{ route(\'agencies.destroy\', $id) }}"
                      method="post" role="form">
                      @csrf
                      @method(\'DELETE\')
                      <button type="submit"
                              class="dropdown-item">
                          <i class="icofont icofont-trash"></i> Delete client
                      </button>
                  </form>
              @endcan
          </div>')
          ->addColumn('details_url', function ($agency) {
            return route('api.agency_single_details', $agency->id);
          })
          ->make(true);
      }
        return view('agencies.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('agencies.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Agency $agency)
    {
        $agency->with('clients')->get();
        return view('agencies.edit', compact('agency'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Agency $agency)
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Agency $agency)
    {
      $agency->delete();
      return redirect()->route('agencies.index')
        ->with('toast_success', 'Agency deleted successfully');
    }
}
