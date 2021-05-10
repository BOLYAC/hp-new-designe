<?php

namespace App\Imports;

use App\Models\Client;
use Illuminate\Support\Facades\Auth;
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
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Client([
          'public_id'      => $row['Lead ID'],
          'full_name'      => $row['Company	First Name'] ?? '',
          'last_name'      => $row['Last Name'] ?? '',
          'client_email'   => $row['Email'],
          'client_number'  => $row['Mobile'],
          'city'           => $row['City'] ?? '',
          'country'        => $row['Country'] ?? '',
          'campaigne_name' => $row['Ad Campaign Name'] ?? '', 
          'user_id'        => $this->user ?? Auth::id(),
          'source_id'      => $this->source,
          'team_id'        => $this->team,
          'created_by'     => Auth::id(),
          'updated_by'     => Auth::id()
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
            '*.email'           => ['nullable','string', 'email', 'max:255', 'unique:clients,client_email,deleted_at'],
            '*.phone_number'    => ['nullable','max:255', 'unique:clients,client_number,deleted_at'],
        ];
    }
}
