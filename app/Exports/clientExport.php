<?php

namespace App\Exports;

use App\Models\client_base;
use Maatwebsite\Excel\Concerns\FromCollection;

use App\Http\Resources\clientExportResource;

  use Maatwebsite\Excel\Concerns\WithHeadings;

  use DB;

class clientExport implements FromCollection, WithHeadings
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
        $query = client_base::query();

        if ($this->search != '' && $this->search) {
            $records = $query->where(DB::raw("CONCAT(`client_code`, ' ', `client_name`)"), 'LIKE', "%" . $this->search . "%");
        }
        if ($this->limit != '' && $this->limit) {

            $query->limit($this->limit);
        }
        
        $query->orderBy('client_code','desc');
        
        $records = $query->get();

        return clientExportResource::collection($records);
    }

    public function headings(): array
    {
        return ["Client Code", "Client Name", "Client Name Arabic", "Address", "CR Number", "VAT Number", "Ajeer License", "Department", "Department (ar)", "Contract Type", "Contract Status", "Operational Services", "Project Start Date", "Project End Date", "Working Days", "Working Hours", "Contact Name", "Contact Number"];
    }
}
