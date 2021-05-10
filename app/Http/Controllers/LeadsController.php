<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\²;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Ramsey\Uuid\Uuid;

class LeadsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $leads = Lead::all();
        return view('leads.index', compact('leads'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $clients = Client::all();
        $users = User::all();
        return view('leads.create', compact('clients', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $client = Client::findOrFail($request->client_id);

        $lead = Lead::create(
            [
                'title' => $request->title,
                'description' => $request->description,
                'user_id' => $request->user_assigned_id,
                'user_assigned_id' => $request->user_assigned_id,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'status_id' => $request->status,
                'user_created_id' => auth()->id(),
                'external_id' => Uuid::uuid1()->toString(),
                'client_id' => $request->client_id,
                'deadline' => now(),
                'lead_name' => $client->full_name,
                'lead_email' => $client->client_email,
                'lead_phone' => $client->client_number,
                'owner_name' => Auth::user()->name,
            ]
        );

        return redirect()->route('leads.show', $lead)->with('toast_success', 'Lead created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param Lead $lead
     * @return Application|Factory|View
     */
    public function show(Lead $lead)
    {
      $users = User::all();
      return view('leads.show', compact('lead', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return void
     */
    public function edit($id): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function update(Request $request, $id): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Lead $lead
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Lead $lead): \Illuminate\Http\RedirectResponse
    {
        $lead->delete();

        return redirect()->route('leads.index')->with('success', 'Deal deleted successfully.');
    }

    public function convertToOrder(Lead $lead)
    {
        $client = Client::findOrFail($lead->client_id);

        $data['client_name'] = $client->full_name;
        $data['client_id'] = $client->id;
        $data['sells_name'] = $lead->sells_names;
        $data['sells_ids'] = $lead->sellers;
        $data['owner_name'] = Auth::user()->name;
        $data['lead_name'] = $lead->owner_name;
        $data['lead_owner_id'] = $lead->user_id;
        $data['status'] = 3;
        $data['user_id'] = \auth()->id();
        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();
        $data['external_id'] = Uuid::uuid4()->toString();
        $data['lead_id'] = $lead->id;

        $invoice = Invoice::create($data);
        $lead->stage_id = 8;
        $lead->invoice_id = $invoice->id;
        $lead->save();
        return redirect()->route('invoices.edit', $invoice->external_id)->with('success', 'transferred to invoice');
    }

    public function changeStage(Request $request)
    {
        $d = $request->all();
        $lead = Lead::findOrFail($request->get('lead_id'));

        $lead->update([
            'stage_id' => $request->get('stage_id')
        ]);
        try {
            return json_encode($lead, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return back()->withError($e->getMessage())->withInput();
        }
    }

    public function dealChangeOwner(Request $request)
    {
        $user = User::find($request->get('user_id'));

        $team = $user->current_team_id ?? 1;

        $lead = Lead::find($request->get('lead_id'));

        $lead->update(['user_id' => $request->get('user_id'), 'team_id' => $team]);

        $link = route('leads.show', $lead);
        $data = [
            'full_name' => $lead->lead_name,
            'assigned_by' => Auth::user()->name,
            'email' => $lead->user->email,
            'link' => $link
        ];
        //AssignedClientEmailJob::dispatch($data);
        try {
            return json_encode($data, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return back()->withError($e->getMessage())->withInput();
        }
    }
}
