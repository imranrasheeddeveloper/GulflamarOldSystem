<?php

namespace App\Exports;

use App\Models\accommodationBillPayment;
use Maatwebsite\Excel\Concerns\FromCollection;

use App\Http\Resources\billPaymentExportResource;

use Maatwebsite\Excel\Concerns\WithHeadings;

use DB;

class billPayemntExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct($search,int $limit,$id)
    {
        $this->search = $search;
        $this->limit = (int) $limit;
        $this->id = (int) $id;
    }

    public function collection()
    {
        if ($this->id != '' && $this->id) {
            
            $query = accommodationBillPayment::query();

            
            $query->where(DB::raw("CONCAT(`accommodation_base_id`)"),'=',$this->id);
            
        }
        else
        {   $query = accommodationBillPayment::query();
            if ($this->search != '' && $this->search) {

                    $classObj = $this;
                    $payments = $query->where(DB::raw("CONCAT(`id`, ' ', `accommodation_base_id`)"), 'LIKE', "%" . $this->search . "%")->orWhereHas('accommodations', function( $tmpquery ) use ( $classObj ){

                      $tmpquery->where(DB::raw("CONCAT(`id`, ' ', `name`)"), 'LIKE', "%" . $classObj->search . "%");

                  });
            }
        }

        if ($this->limit != '' && $this->limit) {

            $query->limit($this->limit);
        }
        
        $query->orderBy('id','desc');
        
        $records = $query->get();

        return billPaymentExportResource::collection($records);
    }

    public function headings(): array
    {
        return ["ID", "Accommodation", "Date","Bill Type", "Amount", "Period", "Notes"];
    }
}
