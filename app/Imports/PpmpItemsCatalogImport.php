<?php

namespace App\Imports;

use App\Models\PpmpItemsCatalog;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class PpmpItemsCatalogImport implements ToModel, WithBatchInserts, WithChunkReading, WithHeadingRow, WithValidation
{
    private $departments = [
        'City Mayor\'s Office (CMO)',
        'City Administrator\'s Office (CAO)',
        'City Tourism Office (CTO)',
        'City Planning and Development Office (CPDO)',
        'City Budget Office (CBO)',
        'City Accountant\'s Office (CAO)',
        'City General Services Office (CGSO)',
        'City Legal Office (CLO)',
        'City Human Resource Management Office (CHRMO)',
        'City Zoning Administration Office (CZAO)',
        'City Treasurer\'s Office (CTO)',
        'City Assessor\'s Office (CAO)',
        'City Civil Registrar\'s Office (CCRO)',
        'City Health Office (CHO)',
        'City Social Welfare and Development Office (CSWDO)',
        'City Engineer\'s Office (CEO)',
        'City Agriculture Services Office (CASO)',
        'City Environment and Natural Resources Office (CENRO)',
        'City Veterinary Office (CVO)',
        'City Disaster and Risk Reduction Management Office (CDRRMO)',
        'Permits and Licensing (PL)',
        'Public Information (PI)',
        'BAPAS (BAPAS)',
        'Traffic and Security (TS)',
        'Market Operations (MO)',
        'BAC Secretary (BAC)',
        'DILG-Sorsogon City (DILG)',
        'Sangguniang Panlungsod (SP)',
        'City Information and Communications Technology Office (CICTO)',
        'BAC (BAC)',
    ]; 

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
            'department' => [
                'required',
                Rule::in($this->departments)
            ],
            'item' => 'required',
            'year' => 'required',
            'unit' => 'required',
        ];
    }

    public function model(array $row)
    {
        return new PpmpItemsCatalog([
            'general_desc' => $row['item'],
            'unit' => $row['unit'],
            'year' => $row['year'],
            'department' => $row['department'],
        ]);
    }
}
