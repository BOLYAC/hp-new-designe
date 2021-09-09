<?php

namespace App\Http\Controllers;

use App\Jobs\AssignedClientEmailJob;
use App\Jobs\MassAssignedClientEmailJob;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use JsonException;
use Ramsey\Uuid\Uuid;

class SalesController extends Controller
{
    public function transfer(Request $request)
    {
        $client = Client::findOrFail($request->clientId);

        if ($client->agency_id === 0 || $client->agency_id === null || $client->agency_id === '') {
            return redirect()->back()->with('toast_error', __('This lead don\'t have Agency'));
        }

        if ($client->status === 0 || $client->status === null || $client->status === '') {
            return redirect()->back()->with('toast_error', __('This lead must have a status'));
        }

        $l[] = json_encode($client->user_id);
        $n = $client->user->name;
        $s[] = $n;
        // Get status name
        $i = $client->status;
        switch ($i) {
            case 1:
                $status = __('New Lead');
                break;
            case 8:
                $status = __('No Answer');
                break;
            case 12:
                $status = __('In progress');
                break;
            case 3:
                $status = __('Potential appointment');
                break;
            case 4:
                $status = __('Appointment set');
                break;
            case 10:
                $status = __('Appointment follow up');
                break;
            case 5:
                $status = __('Sold');
                break;
            case 13:
                $status = __('Unreachable');
                break;
            case 7:
                $status = __('Not interested');
                break;
            case 11:
                $status = __('Low budget');
                break;
            case 9:
                $status = __('Wrong Number');
                break;
            case 14:
                $status = __('Unqualified');
                break;
            case 15:
                $status = __('Lost');
                break;
        }

        $lead['client_id'] = $request->clientId;
        $lead['external_id'] = Uuid::uuid4()->toString();
        $lead['created_by'] = $client->user_id;
        $lead['updated_by'] = $client->user_id;
        $lead['user_created_id'] = Auth::id();
        $lead['user_assigned_id'] = $client->user_id;
        $lead['owner_name'] = $client->user->name;
        $lead['user_id'] = $client->user_id;
        $lead['sell_rep'] = $client->user_id;
        $lead['deadline'] = now();
        $lead['sellers'] = $l;
        $lead['stage_id'] = 1;
        $lead['lead_name'] = $client->full_name ?? '';
        $lead['lead_email'] = $client->client_email ?? '';
        $lead['lead_phone'] = $client->client_number ?? '';
        $lead['sells_names'] = $s;
        $lead['description'] = $client->description ?? '';
        $lead['country'] = $client->country ?? '';
        $lead['nationality'] = $client->nationality ?? '';
        $lead['language'] = $client->lang ?? '';
        $lead['priority'] = $client->priority ?? '';
        $lead['status_id'] = $client->status ?? 99;
        $lead['status_name'] = $status ?? '';
        $lead['source_name'] = $client->source->name ?? '';
        $lead['source_id'] = $client->source_id ?? '';
        $lead['agency_name'] = $client->agency->name ?? '';
        $lead['agency_id'] = $client->agency_id ?? '';

        $lead['budget_request'] = $client->budget_request ?? '';
        $lead['rooms_request'] = $client->rooms_request ?? '';
        $lead['requirement_request'] = $client->requirements_request ?? '';

        $lead['companies_name'] = $client->campaigne_name ?? '';

        $lead = Lead::create($lead);
        //$client->update(['lead_id' => $lead->id]);
        $lead->ShareWithSelles()->attach($l, ['added_by' => Auth::id(), 'user_name' => Auth::user()->name]);
        return redirect()->route('leads.show', $lead)->with('toast_success', __('Deal created successfully for') . $client->full_name ?? '');
    }


    public function transferToInvoice(Request $request)
    {
        $invoice = Invoice::create([
            'external_id' => Uuid::uuid4()->toString(),
            'client_id' => $request->get('clientId'),
            'user_id' => Auth::id(),
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
            'status' => 1,
        ]);
    }

    public function share(Request $request)
    {

        $audits = DB::table('audits')
            ->where('auditable_id', $request->client_id)
            ->where('old_values->user_id', $request->user_id)
            ->get();

        if (count($audits)) {
            return \Response::json(array("errors" => __('already have been assigned to this user')), 422);
        }

        $user = User::find($request->get('user_id'));

        $team = $user->current_team_id ?? 1;

        $client = Client::find($request->get('client_id'));
        $client->update([
            'user_id' => $request->get('user_id'),
            'team_id' => $team,
            'type' => '1',
            'department_id' => $user->department_id
        ]);

        $client->tasks()->update([
            'user_id' => $user->id,
            'team_id' => $team,
            'department_id' => $user->department_id
        ]);

        $link = route('clients.edit', $client);

        $data = [
            'full_name' => $client->full_name,
            'assigned_by' => Auth::user()->name,
            'email' => $client->user->email,
            'link' => $link
        ];
        //AssignedClientEmailJob::dispatch($data);
        try {
            return json_encode($data, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return back()->withError($e->getMessage())->withInput();
        }

    }

    /**
     * @param Request $request
     * @return false|string
     */
    public function massShare(Request $request)
    {
        $user = User::find($request->get('user_id'));

        $team = $user->current_team_id ?? 1;

        Client::whereIn('id', $request->get('clients'))
            ->update([
                'user_id' => $request->get('user_id'),
                'team_id' => $team,
                'department_id' => $user->department_id,
                'type' => '1'
            ]);

        $result = Client::whereIn('id', $request->get('clients'))->get();

        foreach ($result as $d) {
            $d->tasks()->update([
                'user_id' => $user->id,
                'team_id' => $team,
                'department_id' => $user->department_id,
            ]);

            $link = route('clients.edit', $d->id);

            $data[] = array(
                'full_name' => $d->full_name,
                'assigned_by' => Auth::user()->name,
                'email' => $d->user->email,
                'link' => $link
            );
        }
        //MassAssignedClientEmailJob::dispatch($data);

        try {
            return json_encode($data, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return back()->withError($e->getMessage())->withInput();
        }
    }

    public function shareLead(Request $request)
    {
        // Form validation
        $this->validate($request, [
            "share_with" => "required|array|min:1",
            "share_with.*" => "required|string|distinct|min:1",
        ]);

        $users = $request->get('share_with');
        $u = User::whereIn('id', $users)->pluck('name');
        $user = User::find($users[0]);
        $team = $user->current_team_id ?? 1;

        $lead = Lead::find($request->get('lead_id'));
        $lead->update([
            'sell_rep' => $users[0],
            'team_id' => $team,
            'sellers' => $users,
            'sells_names' => $u,
            'department_id' => $user->department_id,
        ]);

        $lead->ShareWithSelles()->detach();
        $lead->ShareWithSelles()->attach($users, ['added_by' => Auth::id(), 'user_name' => Auth::user()->name]);

        $link = route('leads.show', $lead);

        $data = [
            'full_name' => $lead->full_name,
            'assigned_by' => Auth::user()->name,
            'email' => $lead->user->email,
            'link' => $link
        ];

        //AssignedClientEmailJob::dispatch($data);

        return redirect()->back()->with('toast_success', __('Sellers updated successfully'));

    }

    public function shareClient(Request $request)
    {
        // Form validation
        $this->validate($request, [
            "share_with" => "required|array|min:1",
            "share_with.*" => "required|string|distinct|min:1",
        ]);

        $users = $request->get('share_with');
        $u = User::whereIn('id', $users)->pluck('name');
        $lead = Client::find($request->get('lead_id'));
        $lead->update([
            'sellers' => $users,
            'sells_names' => $u,
        ]);

        $lead->shareClientWith()->detach();
        $lead->shareClientWith()->attach($users, ['added_by' => Auth::id(), 'user_name' => Auth::user()->name]);

        $link = route('clients.show', $lead);

        $data = [
            'full_name' => $lead->full_name,
            'assigned_by' => Auth::user()->name,
            'email' => $lead->user->email,
            'link' => $link
        ];

        //AssignedClientEmailJob::dispatch($data);

        return redirect()->back()->with('toast_success', __('Client shared successfully'));
    }

    public function massShareClient(Request $request)
    {
        // Form validation
        $this->validate($request, [
            "users_ids" => "required|array|min:1",
            "users_ids.*" => "required|string|distinct|min:1",
        ]);

        $users = $request->get('users_ids');
        $u = User::whereIn('id', $users)->pluck('name');
        $leads = Client::whereIn('id', $request->get('clients'))->get();

        foreach ($leads as $lead) {
            $lead->update([
                'sellers' => $users,
                'sells_names' => $u,
            ]);
            $lead->shareClientWith()->detach();
            $lead->shareClientWith()->attach($users, ['added_by' => Auth::id(), 'user_name' => Auth::user()->name]);
        }
        //AssignedClientEmailJob::dispatch($data);

        return redirect()->back()->with('toast_success', __('Client shared successfully'));
    }
}
