<?php

namespace App\Exports;

use App\Models\resource_allocation;
use Maatwebsite\Excel\Concerns\FromCollection;

use App\Http\Resources\resourceCenterExportresource;

use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Http\Resources\walletDataResourceExport;
use App\Models\pattyCash;
use App\Models\Wallet;


use DB;

class walletExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($search, int $limit, int $user_id=null)
    {
        $this->search = $search;
        $this->limit = (int) $limit;
        $this->user_id = (int) $user_id;
    }


    public function collection()
    {

        $query = Wallet::query();

        if( $this->user_id != null && $this->user_id != ''){
            $query->where('user_id', $this->user_id);

        }

        $documentRecords = null;

        if ($this->search != '' && $this->search != null) {
            $records = $query->where(DB::raw("CONCAT(`id`, ' ', `account_name` , ' ' , `balance`,'',`user_id`)"), 'LIKE', "%" . $this->search . "%");
        }


        if ($this->limit != '' && $this->limit) {

            $query->limit($this->limit);
        }

        $query->orderBy('id', 'desc');

        $records = $query->get();

        return walletDataResourceExport::collection($records);
    }

    public function headings(): array
    {
        return ["ID", "User Id", "Account Name", "Balance", "Date", "Created By", "Updated By", "Created At", "Updated At"];
    }
}
