<?php

namespace App\Imports;

use App\Models\Client;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class LeadsImport implements
    ToModel,
    WithHeadingRow,
    WithBatchInserts,
    WithChunkReading,
    WithValidation,
    SkipsOnError,
    SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;

    private $user;
    private $source;
    private $team;

    public function __construct($user, $source, $team)
    {
        $this->user = $user;
        $this->source = $source;
        $this->team = $team;
        set_time_limit(500000);
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Client([
            'last_name'             => $row['last_name'] ?? '',
            'first_name'            => $row['first_name'] ?? '',
            'status'                => $row['lead_status'],
            'client_email'          => $row['email'],
            'client_number'         => $row['mobile'],
            'city'                  => $row['city'] ?? '',
            'country'               => $row['country'] ?? '',
            'nationality'           => $row['nationality'] ?? '',
            'lead_source'           => $row['lead_source'],
            'lead_status'           => $row['lead_status'],
            'last_activity_time'    => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['last_activity_time']),
            'social_media_source'   => $row['social_media_source'],
            'ad_network'            => $row['ad_network'],
            'search_partner_network'=> $row['search_partner_network'],
            'ad_campaign_name'      => $row['ad_campaign_name'],
            'adgroup_name'          => $row['adgroup_name'],
            'ad'                    => $row['ad'],
            'ad_click_date'         => $row['ad_click_date'],
            'adset_name'            => $row['adset_name'],
            'form_name'             => $row['form_name'],
            'ad_name'               => $row['ad_name'],
            'reason_lost'           => $row['reason_lost'],
            'created_at'            => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['created_time']),
            'updated_at'            => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['modified_time']),
            'user_id'               => $row['lead_owner'],
            'source_id'             => $this->source,
            'team_id'               => User::where('id', $row['lead_owner'])->value('current_team_id'),
            'created_by'            => $row['created_by'],
            'updated_by'            => $row['modified_by'],
            'description'           => $row['description'],
            'imported_from_zoho'    => 1,
            'zoho_id'               => $row['lead_id'],
            'department_id'         => User::where('id', $row['lead_owner'])->value('department_id'),
            'customer_id'             => $row['customer_id'],
        ]);
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function rules(): array
    {
        return [
            '*.email' => ['nullable', 'string', 'max:255', 'unique:clients,client_email,deleted_at'],
            '*.phone_number' => ['nullable', 'max:255', 'unique:clients,client_number,deleted_at'],
        ];
    }
}
