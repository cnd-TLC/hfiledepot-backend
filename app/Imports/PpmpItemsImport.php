<?php

namespace App\Imports;

use App\Models\PpmpItem;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class PpmpItemsImport implements ToModel, WithBatchInserts, WithChunkReading, WithHeadingRow, WithValidation
{
    private $allowed_items = [];
    private $ppmp_id;

    public function __construct($data, $id)
    {
        $this->allowed_items = $data;
        $this->ppmp_id = $id;
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
            'code' => 'required',
            'category' => 'required',
            'general_description' => [
                'required',
                Rule::in($this->allowed_items)
            ],
            'unit' => 'required',
            'quantity' => 'required',
            'mode' => [
                'required',
                Rule::in(['Bidding', 'Shopping'])
            ],
            'estimated_budget' => 'required',
            'ppmp_id' => [
                'required',
                Rule::in([$this->ppmp_id])
            ]
        ];

    }
    
    public function model(array $row)
    {
        return new PpmpItem([
            'code' => $row['code'],
            'category' => $row['category'],
            'general_desc' => $row['general_description'],
            'unit' => $row['unit'],
            'quantity' => $row['quantity'], //validate 0 if lumpsum
            'lumpsum' => 0, //validate if lumpsum

            // 'quantity' => $row['quantity'] == 'lumpsum' ? $row['quantity'] : 0,
            // 'lumpsum' => $row['quantity'] == 'lumpsum' ? 1 : 0,

            'mode_of_procurement' => $row['mode'],
            'estimated_budget' => $row['estimated_budget'],
            'jan' => $row['jan'],
            'feb' => $row['feb'],
            'mar' => $row['mar'],
            'apr' => $row['apr'],
            'may' => $row['may'],
            'jun' => $row['jun'],
            'jul' => $row['jul'],
            'aug' => $row['aug'],
            'sept' => $row['sept'],
            'oct' => $row['oct'],
            'nov' => $row['nov'],
            'dec' => $row['dec'],
            'ppmp_id' => $row['ppmp_id']
        ]);
    }
}
