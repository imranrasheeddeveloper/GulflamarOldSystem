<?php

namespace App\Exports;

use App\Models\resource_allocation;
use Maatwebsite\Excel\Concerns\FromCollection;

use App\Http\Resources\resourceCenterExportresource;

use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Http\Resources\walletDataResourceExport;
use App\Models\pattyCash;

use DB;

class walletTransactionExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($search, int $limit, int $wallet_id)
    {
        $this->search = $search;
        $this->limit = (int) $limit;
        $this->wallet_id = (int) $wallet_id;
    }


    public function collection()
    {

        $query = pattyCash::query();
        $query->where('wallet_id', $this->wallet_id);

        $documentRecords = null;

        if ($this->search != '' && $this->search != null) {
            $records = $query->where(DB::raw("CONCAT(`id`, ' ', `credit_debit` , ' ' , `total`)"), 'LIKE', "%" . $this->search . "%");
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
        return ["ID", "Wallet ID", "Transaction Type", "Assign To Category", "Assign To Entity", "Assign To Entity ID", "Approve", "Accept", "Date", "Unit Price", "Quantity", "Total", "Credit/Debit", "Description", "Notes", "Attachment", "Created By", "Updated By", "Created At", "Updated At"];
    }
}
