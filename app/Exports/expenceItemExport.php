<?php

namespace App\Exports;

use App\Models\expenseItem;
use Maatwebsite\Excel\Concerns\FromCollection;

use App\Http\Resources\ExpenceItemExportResource;

use Maatwebsite\Excel\Concerns\WithHeadings;

use DB;

class expenceItemExport implements FromCollection, WithHeadings
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
        $query = expenseItem::query();

        if ($this->search != '' && $this->search) {
            $records = $query->where(DB::raw("CONCAT(`id`, ' ', `name`)"), 'LIKE', "%" . $this->search . "%");
        }
        if ($this->limit != '' && $this->limit) {

            $query->limit($this->limit);
        }
        
        $query->orderBy('id','desc');
        
        $records = $query->get();

        return ExpenceItemExportResource::collection($records);
    }

    public function headings(): array
    {
        return ["ID", "NAME", "NAME (AR)"];
    }
}
