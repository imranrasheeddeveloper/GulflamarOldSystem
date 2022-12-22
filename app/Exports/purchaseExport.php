<?php

namespace App\Exports;

use App\Models\purchasing;
use Maatwebsite\Excel\Concerns\FromCollection;

use App\Http\Resources\purchaseExportResource;

use Maatwebsite\Excel\Concerns\WithHeadings;

use DB;

class purchaseExport implements FromCollection, WithHeadings
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
        $query = purchasing::query();

        if ($this->search != '' && $this->search) {
            $records = $query->where(DB::raw("CONCAT(`id`, ' ', `purchaseType`)"), 'LIKE', "%" . $this->search . "%");
        }
        if ($this->limit != '' && $this->limit) {

            $query->limit($this->limit);
        }
        
        $query->orderBy('id','desc');
        
        $records = $query->get();

        return purchaseExportResource::collection($records);
    }

    public function headings(): array
    {
        return ["ID", "Purchase Type", "Date", "Sub Total", "VAT", "Total","Seller Type","Request Seller Id","Request Seller"];

    }
}
