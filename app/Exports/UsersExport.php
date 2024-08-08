<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Excel;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;

class UsersExport implements FromQuery, Responsable, WithHeadings
{
    use Exportable;

    private $fileName = 'file.xlsx';

    private $writerType = Excel::XLSX;

    private $headers = [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ];

    public function headings(): array
    {
        return [
            'id',
            'name',
            'email',
            'email_verified_at',
            'username',
            'password',
            'department',
            'role',
            'status',
            'permissions',
            'remember_token',
            'created_at',
            'updated_at',
        ];
    }

    public function query()
    {
        return User::query();
    }

    public function prepareRows($rows)
    {
        // Add some logging to help debug the issue
        logger()->info('Preparing rows for export...');
        logger()->info($rows);

        return $rows;
    }
}
