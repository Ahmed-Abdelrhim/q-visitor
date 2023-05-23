<?php

namespace App\Import;

use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

use App\Models\Worker;

class ImportWorkers implements ToModel, WithHeadingRow, WithValidation
{
    public int $visit_id;
    public int $visitor_id;

    public function __construct($visit_id, $visitor_id)
    {
        $this->visit_id = $visit_id;
        $this->visitor_id = $visitor_id;
    }


    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'national_number' => ['required', 'numeric'],
        ];
    }
    //    public function headings(): array
    //    {
    //        return [
    //            'name',
    //            'national_number',
    //        ];
    //    }

    public function model(array $row)
    {
        return new Worker([
            'name' => $row['name'],
            'nat_id' => $row['national_number'],
            'visit_id' => $this->visit_id,
            'visitor_id' => $this->visitor_id,
            'is_scan' => 0,
            'created_at' => Carbon::now(),
        ]);
    }
}
