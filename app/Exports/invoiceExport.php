<?php

namespace App\Exports;

use App\Models\invoice_base;
use Maatwebsite\Excel\Concerns\FromCollection;

use App\Http\Resources\invoiceExportResource;

use Maatwebsite\Excel\Concerns\WithHeadings;

use DB;

class invoiceExport implements FromCollection, WithHeadings
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
        $query = invoice_base::query();

        if ($this->search != '' && $this->search) {
            

            $classObj = $this;
            $records = $query->where(DB::raw("CONCAT(`invoice_number`, ' ', `client_code`)"), 'LIKE', "%" . $this->search . "%")->orWhereHas('client', function( $tmpquery ) use ( $classObj ){

                      $tmpquery->where(DB::raw("CONCAT(`client_code`, ' ', `client_name`)"), 'LIKE', "%" . $classObj->search . "%");

                  });

        }
        if ($this->limit != '' && $this->limit) {

            $query->limit($this->limit);
        }
        
        $query->orderBy('invoice_number','desc');
        
        $records = $query->get();

        return invoiceExportResource::collection($records);
    }

    public function headings(): array
    {
        return ["Invoice Number", "Date", "Status", "Client Code", "Client Name", "Location", "Period", "Subtotal", "VAT", "Total"];
    }
}
