<?php

namespace App\Exports;

use App\Models\resource_allocation;
use Maatwebsite\Excel\Concerns\FromCollection;

use App\Http\Resources\resourceCenterExportresource;

use Maatwebsite\Excel\Concerns\WithHeadings;

use DB;

class resourceCenterExport implements FromCollection, WithHeadings
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
        $query = resource_allocation::query();

        if ($this->search != '' && $this->search) {
            $records = $query->where(DB::raw("CONCAT(`id`, ' ',`resourceOwnerType`, ' ', `resourceOwnerId`)"), 'LIKE', "%" . $this->search . "%");
        }
        if ($this->limit != '' && $this->limit) {

            $query->limit($this->limit);
        }
        
        $query->orderBy('id','desc');
        
        $records = $query->get();

        return resourceCenterExportresource::collection($records);
    }

    public function headings(): array
    {
        return ["ID", "Owner Type", "Resource Owner", "Resource Owner ID", "Date", "Notes"];
    }
}
