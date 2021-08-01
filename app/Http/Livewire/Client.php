<?php

namespace App\Http\Livewire;

use AloTech\Click2;
use App\Agency;
use App\Models\Source;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Client extends Component
{

    public $mode, $client, $updateMode, $sources, $agencies;

    public $budget_request_list = [
        ['id' => 1, 'text' => 'Less then 50K'],
        ['id' => 2, 'text' => '50K-100K'],
        ['id' => 3, 'text' => '100K-150K'],
        ['id' => 4, 'text' => '150K200K'],
        ['id' => 5, 'text' => '200K-300K'],
        ['id' => 6, 'text' => '300K-400k'],
        ['id' => 7, 'text' => '400k-500K'],
        ['id' => 8, 'text' => '500K-600k'],
        ['id' => 9, 'text' => '600K-1M'],
        ['id' => 10, 'text' => '1M-2M'],
        ['id' => 11, 'text' => 'More then 2M'],
    ];
    public $rooms_request_list = [
        ['id' => 1, 'text' => '0 + 1'],
        ['id' => 2, 'text' => '1 + 1'],
        ['id' => 3, 'text' => '2 + 1'],
        ['id' => 4, 'text' => '3 + 1'],
        ['id' => 5, 'text' => '4 + 1'],
        ['id' => 6, 'text' => '5 + 1'],
        ['id' => 7, 'text' => '6 + 1'],
    ];
    public $requirements_request_list = [
        ['id' => 1, 'text' => 'Investments'],
        ['id' => 2, 'text' => 'Life style'],
        ['id' => 3, 'text' => 'Investments + Life style'],
        ['id' => 4, 'text' => 'Citizenship'],
    ];
    public $country_edit, $nationality_edit, $lang_edit, $description_edit,
        $status_edit, $priority_edit, $budget_request_edit, $rooms_request_edit,
        $requirements_request_edit, $source_id_edit, $campaign_name_edit, $agency_id_edit,
        $appointment_date_edit, $duration_stay_edit = [], $phone_number_edit, $phone_number_2_edit;


    public function mount($client)
    {
        $this->mode = 'show';
        $this->sources = Source::all();
        $this->agencies = Agency::all();
        $this->country_edit = $client->country;
        $this->nationality_edit = $client->nationality;
        $this->lang_edit = $client->lang;
        $this->description_edit = $client->description;
        $this->status_edit = $client->status;
        $this->priority_edit = $client->priority;
        $this->budget_request_edit = $client->budget_request;
        $this->rooms_request_edit = $client->rooms_request;
        $this->requirements_request_edit = $client->requirements_request;
        $this->source_id_edit = $client->source_id;
        $this->campaign_name_edit = $client->campaign_name;
        $this->agency_id_edit = $client->agency_id;
        $this->appointment_date_edit = $client->appointment_date;
        $this->duration_stay_edit = $client->duration_stay;
        $this->phone_number_edit = $client->client_number;
        $this->phone_number_2_edit = $client->client_number_2;
    }

    public function render()
    {
        return view('livewire.client')
            ->extends('layouts.vertical.master');
    }

    public function updateMode($mode)
    {
        $this->mode = $mode;
    }

    public function editLead()
    {
        $data = [
            'country' => $this->country_edit,
            'nationality' => $this->nationality_edit,
            'lang' => $this->lang_edit,
            'description' => $this->description_edit,
            'status' => $this->status_edit,
            'priority' => $this->priority_edit,
            'budget_request' => $this->budget_request_edit,
            'rooms_request' => $this->rooms_request_edit,
            'requirements_request' => $this->requirements_request_edit,
            'source_id' => $this->source_id_edit,
            'campaigne_name' => $this->campaign_name_edit,
            'agency_id' => $this->agency_id_edit,
            'appointment_date' => $this->appointment_date_edit,
            'duration_stay' => $this->duration_stay_edit,
            'client_number' => $this->phone_number_edit,
            'client_number_2' => $this->phone_number_2_edit,
        ];



        if ($data) {
            $this->client->update($data);
            $this->updateMode('show');
            $this->emit('alert', ['type' => 'success', 'message' => 'Lead updated successfully!']);
        } else {
            $this->emit('alert', ['type' => 'danger', 'message' => 'There is something wrong!, Please try again.']);
        }
    }

    public function makeCall($phone)
    {
        if ($phone === 'ph1') {
            $phone = $this->client->client_number;
        } else {
            $phone = $this->client->client_number_2;
        }

        if (session()->has('current_call')) {
            $this->emit('alert', ['type' => 'danger', 'message' => 'Already in Call']);
        }

        $aloTech = Session::get('alotech');
        $phoneNumber = $phone;
        $click2 = new Click2($aloTech);
        $res = $click2->call([
            'phonenumber' => $phoneNumber,
            'hangup_url' => 'http://crm.hashim.com.tr/',
            'masked' => '1'
        ]);
        Session::put('current_call', $click2);

        $this->emit('alert', ['type' => 'success', 'message' => 'Call started']);
    }
}
