<?php

namespace App\Exports;

use App\Models\resource_allocation;
use Maatwebsite\Excel\Concerns\FromCollection;

use App\Http\Resources\resourceCenterExportresource;

use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Http\Resources\walletDataResourceExport;



use Illuminate\Http\Request;

use App\Models\User;
use App\Models\bankAccount;
use App\Models\bankAccountTransaction;
use App\Models\Wallet;



use App\Models\employee;
use App\Models\vendor_base;
use App\Models\client_base;
use App\Models\accommodation_base;
use App\Models\Supplier;
use App\Models\PaymentRequest;
use App\Models\ownerBank;



use App\Models\userLevel;

// use Auth;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;



class bankAccountTransactionExport implements FromCollection, WithHeadings
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

        $query = bankAccountTransaction::query();

        if( $this->user_id != null && $this->user_id != ''){
            $query->where('bank_account_id', $this->user_id);

        }

        $documentRecords = null;

        if ($this->search != '' && $this->search != null) {
            $documentRecords = $query->where(DB::raw("CONCAT(`id`, ' ', `transaction_type` , ' ' , `amount`)"), 'LIKE', "%" . $this->search . "%");
        }


        if ($this->limit != '' && $this->limit) {

            $query->limit($this->limit);
        }

        $query->orderBy('id', 'desc');

        $documentRecords = $query->get();



        foreach ($documentRecords as $key => $document) {
            if ($document->transaction_type == "Client Payment") {
                $document->to_from_name = client_base::where('client_code', $document->to_from)->pluck('client_name')->first();
            } else if ($document->transaction_type == "Employee Payments" || $document->transaction_type == "HR Payments") {
                if (isset($document->to_from) & !empty($document->to_from) && $document->to_from != null && $document->to_from != '') {
                    // $document->to_from =  json_decode($document->to_from, true);
                    // $Document->to_from_name = '';
                    // }
                    $document->to_from =  json_decode($document->to_from, true);
                    $document->to_from_name = '';
                    $to_from_temp = '';
                    foreach ($document->to_from as $emp_id) {
                        $name = employee::where('emp_id', $emp_id)->pluck('name')->first();
                        $document->to_from_name .= $name . ' , ';
                        $to_from_temp .= $emp_id . ' , ';
                    }
                    $document->to_from = $to_from_temp;
                }
            } else if ($document->transaction_type == "Vendor Payment") {
                $document->to_from_name = vendor_base::where('id', $document->to_from)->pluck('name')->first();
            } else if ($document->transaction_type == "Utility Payments" && $document->sub_type != "Fuel" && $document->sub_type != "Others" && $document->sub_type != "") {
                $document->to_from_name = accommodation_base::where('id', $document->to_from)->pluck('name')->first();
            } else if ($document->transaction_type == "Supplier Payment") {
                $document->to_from_name = Supplier::where('id', $document->to_from)->pluck('name')->first();
            } else if (($document->transaction_type == "Withdrawal" && $document->sub_type == "Petty Cash") || $document->transaction_type == "Petty Cash") {

                $document->to_from_name = wallet::where('id', $document->to_from)->pluck('account_name')->first();
            }else if ($document->transaction_type == "Transfer to Owner") {

                $document->to_from_name = ownerBank::where('id', $document->to_from)->pluck('account_name')->first();
            }else if ($document->transaction_type == "Transfer to Other Bank") {

                $document->to_from_name = bankAccount::where('id', $document->sub_type)->pluck('account_name')->first();
            }
        }

        return walletDataResourceExport::collection($documentRecords);

    }

    public function headings(): array
    {
        return ["ID", "Bank Account ID", "TRANSACTION TYPE", "Date","SUB TYPE","To/From ID","Notes","AMOUNT","Credit/Debit", "Created By", "Updated By", "Created At", "Updated At","To/From Name"];

    }
}
