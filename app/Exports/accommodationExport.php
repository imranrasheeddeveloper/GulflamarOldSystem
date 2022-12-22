<?php

namespace App\Exports;

use App\Models\accommodation_base;
use Maatwebsite\Excel\Concerns\FromCollection;

use App\Http\Resources\accommodationExportResource;

use Maatwebsite\Excel\Concerns\WithHeadings;


use DB;

class accommodationExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct($search,int $limit)
    {
        $this->search = $search;
        $this->limit = (int) $limit;
    }

    public function collection()
    {
        $query = accommodation_base::query();

        if ($this->search != '' && $this->search) {
            $records = $query->where(DB::raw("CONCAT(`id`, ' ', `name`)"), 'LIKE', "%" . $this->search . "%");
        }
        if ($this->limit != '' && $this->limit) {

            $query->limit($this->limit);
        }
        
        $query->orderBy('due-date','ASC');
        
        $records = $query->get();

        return accommodationExportResource::collection($records);
    }

    public function headings(): array
    {
        return ["ID", "Name", "Location", "Address", "Rooms", "Beds", "Contact Start Date", "Contact End Date", "Rent", "Services", "Due Date", "Period", "Unit No.", "Electricity Account No", "Water Bill Account No", "Contact Name", "Contact Number", "Account Name", "Bank Name", "IBAN", "Fixed Electricity Amount", "Fixed Water Amount"];
    }}
