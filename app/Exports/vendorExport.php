<?php

namespace App\Exports;

use App\Models\vendor_base;
use Maatwebsite\Excel\Concerns\FromCollection;

use App\Http\Resources\vendorExportResource;

use Maatwebsite\Excel\Concerns\WithHeadings;

use DB;

class vendorExport implements FromCollection, WithHeadings
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
        $query = vendor_base::query();

        if ($this->search != '' && $this->search) {
            $records = $query->where(DB::raw("CONCAT(`code`, ' ', `name`)"), 'LIKE', "%" . $this->search . "%");
        }
        if ($this->limit != '' && $this->limit) {

            $query->limit($this->limit);
        }
        
        $query->orderBy('id','desc');
        
        $records = $query->get();

        return vendorExportResource::collection($records);
    }

    public function headings(): array
    {
        return ["ID", "Vendor Code", "Company Name", "Company Name (ar)", "CR Number", "VAT Number", "Contact Name (Operations)", "Contact Number (Operations)", "Contact Name (Billing)", "Contact Number (Billing)", "Contact Name (Gov Relations)", "Contact Number (Gov Relations)"];
    }
}
