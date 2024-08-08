<?php

namespace App\Imports;

use App\Models\PpmpItemsCatalog;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class PpmpItemsCatalogImport implements ToModel, WithBatchInserts, WithChunkReading, WithHeadingRow, WithValidation
{
    private $departments = [
        'City Mayor\'s Office (CMO)',
        'City Administrator\'s Office (CAdmO)',
        'City Tourism Office (CToO)',
        'City Planning and Development Office (CPDO)',
        'City Budget Office (CBO)',
        'City Accountant\'s Office (CAccO)',
        'City General Services Office (CGSO)',
        'City Legal Office (CLO)',
        'City Human Resource Management Office (CHRMO)',
        'City Zoning Administration Office (CZAO)',
        'City Treasurer\'s Office (CTO)',
        'City Assessor\'s Office (CAsO)',
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
        'DILG-Sorsogon City (DILG)',
        'Sangguniang Panlungsod (SP)',
        'City Information and Communications Technology Office (CICTO)',
        'Bids and Awards Committee (BAC)'
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
            'department' => 'required',
            'item' => 'required',
            'year' => 'required',
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
