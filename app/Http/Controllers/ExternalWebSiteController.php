<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ExternalWebSiteController extends Controller
{
    public function getExpoForm(Request $request)
    {
        $d = $request->all();

        /*$this->validate($request, [
            'form.name' => 'required|regex:/^[a-z0-9\s]+$/i',
            'fields.name.value' => 'required|regex:/^[a-z0-9\s]+$/i',
            'fields.email.value' => ['nullable', 'string', 'email', 'max:255', 'unique:clients,client_email,deleted_at'],
            'fields.field_6268666.value' => ['nullable', 'max:255', 'unique:clients,client_number,deleted_at'],
            'meta.date.value' => 'date',
            'meta.user_agent.value' => 'required|url',
        ]);*/

        $input = [
            'form_name' => $d['form']['name'],
            'full_name' => $d['fields']['name']['value'],
            'email' => $d['fields']['email']['value'],
            'phone' => $d['fields']['field_6268666']['value'],
            'submit_date' => $d['meta']['date']['value'],
            'page_name' => $d['meta']['page_url']['value'],
            'user_agent' => $d['meta']['user_agent']['value']
        ];

        $client = new Client([
            'form_name' => $input['form_name'],
            'full_name' => $input['full_name'],
            'client_email' => $input['email'],
            'client_number' => $input['phone'],
            'source_id' => 10,
            'status' => 1,
            'agency_id' => 1,
            'ad_click_date' => $input['submit_date'],
            'search_partner_network' => $input['user_agent'],
            'adset_name' => $input['page_name'],
            'user_id' => 89,
            'created_by' => 1,
            'updated_by' => 1,
            'department_id' => 1
        ]);

        $client->save();

        \Illuminate\Support\Facades\Log::info($client);
        return response()->json(['success' => true, 'message' => 'Mesajınız gönderildi. Teşekkür ederiz']);
    }

    public function getHashimForm(Request $request)
    {
        $d = $request->all();

        /*$this->validate($request, [
            'form.name' => 'required|regex:/^[a-z0-9\s]+$/i',
            'fields.name.value' => 'required|regex:/^[a-z0-9\s]+$/i',
            'fields.email.value' => ['nullable', 'string', 'email', 'max:255', 'unique:clients,client_email,deleted_at'],
            'fields.field_6268666.value' => ['nullable', 'max:255', 'unique:clients,client_number,deleted_at'],
            'meta.date.value' => 'date',
            'meta.user_agent.value' => 'required|url',
        ]);*/

        $input = [
            'form_name' => $d['form']['name'],
            'full_name' => $d['fields']['name']['value'],
            'email' => $d['fields']['email']['value'],
            'phone' => $d['fields']['field_6268666']['value'],
            'submit_date' => $d['meta']['date']['value'],
            'page_name' => $d['meta']['page_url']['value'],
            'user_agent' => $d['meta']['user_agent']['value']
        ];

        $client = new Client([
            'form_name' => $input['form_name'],
            'full_name' => $input['full_name'],
            'client_email' => $input['email'],
            'client_number' => $input['phone'],
            'source_id' => 10,
            'status' => 1,
            'agency_id' => 1,
            'ad_click_date' => $input['submit_date'],
            'search_partner_network' => $input['user_agent'],
            'adset_name' => $input['page_name'],
            'user_id' => 26,
            'created_by' => 1,
            'updated_by' => 1,
            'department_id' => 1
        ]);

        $client->save();

        \Illuminate\Support\Facades\Log::info($client);
        return response()->json(['success' => true, 'message' => 'Mesajınız gönderildi. Teşekkür ederiz']);
    }

    public function getExpoShowForm()
    {

        $d = $request->all();

        $input = [
            'form_name' => $d['form']['name'],
            'full_name' => $d['fields']['full_name']['value'],
            'email' => $d['fields']['email']['value'],
            'phone' => $d['fields']['phone_number']['value'],
            'submit_date' => $d['meta']['date']['value'],
            'page_name' => $d['meta']['page_url']['value'],
            'user_agent' => $d['meta']['user_agent']['value']
        ];

        $client = new Client([
            'form_name' => $input['form_name'],
            'full_name' => $input['full_name'],
            'client_email' => $input['email'],
            'client_number' => $input['phone'],
            'source_id' => 10,
            'status' => 1,
            'agency_id' => 1,
            'ad_click_date' => $input['submit_date'],
            'search_partner_network' => $input['user_agent'],
            'adset_name' => $input['page_name'],
            'user_id' => 89,
            'created_by' => 1,
            'updated_by' => 1,
            'department_id' => 4
        ]);

        $client->save();

        \Illuminate\Support\Facades\Log::info($client);
        return response()->json(['success' => true, 'message' => 'Mesajınız gönderildi. Teşekkür ederiz']);
    }
}
