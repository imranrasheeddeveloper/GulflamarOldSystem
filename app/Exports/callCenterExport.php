<?php

namespace App\Exports;

use App\Models\resource_allocation;
use Maatwebsite\Excel\Concerns\FromCollection;

use App\Http\Resources\resourceCenterExportresource;

use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Http\Resources\walletDataResourceExport;
use App\Models\pattyCash;
use App\Models\CallCenter;
use App\Models\User;



use DB;

class callCenterExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($search, int $limit)
    {
        $this->search = $search;
        $this->limit = (int) $limit;
    }


    public function collection()
    {

        $query = CallCenter::query();


        $documentRecords = null;



        if($this->search != '' && $this->search != null){

            $query->where(DB::raw("CONCAT(`id`, ' ', `name` , ' ' , `issue`, ' ' , `status`)"), 'LIKE', "%" . $this->search . "%");

        }


        if ($this->limit != '' && $this->limit) {

            $query->limit($this->limit);
        }

        $query->orderBy('id', 'desc');

        $records = $query->get();
        foreach($records as $item){
            $item['department_name'] = User::where('id',$item->department)->pluck('name')->first();

        }

        return walletDataResourceExport::collection($records);
    }

    public function headings(): array
    {

        return ["ID","Name", "GL Id","Project","Date","Time","Issue","Department ID","Status","Issue Resolution","Priority","Notes","Created By", "Updated By", "Created At", "Updated At", "Department Name",];
    }
}
