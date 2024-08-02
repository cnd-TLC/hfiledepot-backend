<?php

namespace App\Imports;

use App\Models\PrItem;
use App\Models\PpmpItem;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class PrItemsImport implements ToModel, WithBatchInserts, WithChunkReading, WithHeadingRow, WithValidation
{
    private $allowed_items = [];
    private $pr_id;

    public function __construct($data, $id)
    {
        $this->allowed_items = $data;
        $this->pr_id = $id;
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
            'unit_cost' => 'required',
            'quantity' => 'required',
            'pr_id' => [
                'required',
                Rule::in([$this->pr_id])
            ]
        ];
    }

    public function model(array $row)
    {
        $data = PpmpItem::where('code', $row['code'])->first();

        return new PrItem([
            'item_no' => $row['code'],
            'unit' => $data['unit'],
            'category' => $data['category'],
            'item_description' => $data['general_desc'],
            'quantity' => $row['quantity'], //validate max
            'unit_cost' => $row['unit_cost'],
            'lumpsum' => $data['lumpsum'],
            'mode_of_procurement' => $data['mode_of_procurement'],
            'pr_id' => $row['pr_id'],
        ]);       
    }
}
