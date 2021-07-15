<?php

namespace App\Imports;

use App\Agency;
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


class AgenciesImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading, SkipsOnError, SkipsOnFailure
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
        return new Agency([
            'name'          => $row['agent_name'] ?? '',
            'title'         => $row['title'] ?? '',
            'in_charge'     => $row['contact_person'] ?? '',
            'phone'         => $row['phone'],
            'address'       => $row['location'],
            'email'         => $row['email'],
            'note'          => '<strong>Old note:</strong> ' . $row['old_note'] . '<br><br>' . '<strong>Note:</strong> ' . $row['note'] . '<br><br>' . '<strong>Web site:</strong> ' . $row['web_site'] . '<br>' . '<strong>Date:</strong> ' . $row['date'] . '<br>' . '<strong>Phone/Status:</strong> ' . $row['phone2'],
            'user_id'       => $this->user->id ?? Auth::id(),
            'department_id' => 3,
            'team_id'       => $this->team,
            'created_by'    => Auth::id(),
            'updated_by'    => Auth::id(),
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
}
