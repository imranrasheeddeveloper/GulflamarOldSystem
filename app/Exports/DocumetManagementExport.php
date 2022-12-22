<?php

namespace App\Exports;

use App\Models\resource_allocation;
use Maatwebsite\Excel\Concerns\FromCollection;

use App\Http\Resources\resourceCenterExportresource;

use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Http\Resources\walletDataResourceExport;
use App\Models\pattyCash;
use App\Models\Wallet;
use App\Models\Document;



use DB;

class DocumetManagementExport implements FromCollection, WithHeadings
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

        $query = Document::query();



        $documentRecords = null;

        if ($this->search != '' && $this->search != null) {
            $records = $query->where(DB::raw("CONCAT(`id`, ' ',`recipient`,' ',`recipient_type`,' ',`resource_owner_id`,' ',`resource_owner_name`,' ',`recipient`)"), 'LIKE', "%" . $this->search  . "%");
            
        }


        if ($this->limit != '' && $this->limit) {

            $query->limit($this->limit);
        }

        $query->orderBy('id', 'desc');
        $records = $query->get(['id', 'recipient_type', 'recipient', 'resource_owner_id','resource_owner_name','date','method','description','received_by','received_at','created_by','updated_by','created_at','updated_at']);

        return walletDataResourceExport::collection($records);
    }

    public function headings(): array
    {
        return ["ID", "RECIPIENT TYPE", "DOCUMENT TYPE", "Recipient ID","Recipient Name", "Date","Method","Description", "Received By", "Received At", "Created By", "Updated By", "Created At", "Updated At"];
        // return [];
        
    }
}
