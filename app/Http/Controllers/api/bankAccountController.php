<?php

namespace App\Http\Controllers\api;



use Illuminate\Pagination\LengthAwarePaginator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\pattyCash;

use App\Models\User;
use App\Models\bankAccount;
use App\Models\bankAccountTransaction;


use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use JWTAuth;
use Validator;
use App\Http\Resources\walletDataResource;
use Tymon\JWTAuth\Exceptions\JWTException;
use Carbon\Carbon;


use App\Models\Document;
use App\Http\Resources\documentDataResource;

use App\Models\employee;
use App\Models\vendor_base;
use App\Models\client_base;
use App\Models\accommodation_base;
use App\Models\Supplier;
use App\Models\PaymentRequest;
use App\Models\ownerBank;



use App\Models\userLevel;

// use Auth;
use App\Exports\bankAccountTransactionExport;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class bankAccountController extends Controller
{

    public function addBankAccount(Request $request)
    {


        $messages = [
            'account_name.required'  => 'Account Name must be required.',
            'bank_name.required'  => 'Bank Name must be required.',
            'iban.required'  => 'Iban must be required.',
            'balance.required'  => 'Balance must be required.',



        ];

        $validate = Validator::make($request->all(), [
            'account_name' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'balance' => 'required|numeric',
            // 'file.*' => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar',
            'iban' => 'required',


        ], $messages);

        if ($validate->fails()) {

            return response()->json(['message' => $validate->errors()->first(), 'success' => false, 'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);
        }

        $input = $request->all();

        $input['created_by'] = Auth::user()->name;
        $input['updated_by'] = null;


        $bank_account = bankAccount::create($input);


        $user = JWTAuth::user();
        $action = 'CREATED';
        $itemName = $bank_account->account_name . ' baank account';
        $itemUnique = $bank_account->id;
        $tableName = 'bank_accounts';
        recordActivity($user, $action, $itemName, $itemUnique, $tableName);

        return response()->json(['message' => 'Bank Account Created', 'success' => true, 'data' => json_decode(json_encode($bank_account))]);
    }


    public function getBankAccount(Request  $request)
    {

        $wallet = bankAccount::where('id', $request->id)->first();


        return response()->json(['message' => 'Bank Account Fetched', 'success' => true, 'data' => json_decode(json_encode($wallet))]);
    }


    public function updateBankAccount(Request $request)
    {


        $messages = [
            'id.required'  => 'Id must be required.',
            'account_name.required'  => 'Account Name must be required.',
            'bank_name.required'  => 'Bank Name must be required.',
            'iban.required'  => 'Iban must be required.',
            'balance.required'  => 'Balance must be required.',

        ];

        $validate = Validator::make($request->all(), [
            'id' => 'required|numeric',
            'account_name' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'balance' => 'required|numeric',
            // 'file.*' => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar',
            'iban' => 'required',



        ], $messages);

        if ($validate->fails()) {

            return response()->json(['message' => $validate->errors()->first(), 'success' => false, 'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);
        }

        $input = $request->all();

        // $input['created_by'] = Auth::user()->name;
        $input['updated_by'] = Auth::user()->name;


        bankAccount::updateOrCreate([
            'id' => $request->id
        ], $input);


        $bank_account = bankAccount::where('id', $request->id)->first();




        $user = JWTAuth::user();
        $action = 'updated';
        $itemName = $bank_account->user_id . ' bank account';
        $itemUnique = $bank_account->id;
        $tableName = 'bank_accounts';
        recordActivity($user, $action, $itemName, $itemUnique, $tableName);

        return response()->json(['message' => 'Bank Account Updated', 'success' => true, 'data' => json_decode(json_encode($bank_account))]);
    }


    public function deleteBankAccount(Request  $request)
    {

        $bank_account = bankAccount::where('id', $request->id)->first();

        $Transaction = bankAccountTransaction::where('bank_account_id', $request->id)->get();

        $length = $Transaction->count();

        if ($length > 0) {
            return response()->json(['message' => 'Bank Account cannot be deleted, Bank Account has Transactions', 'success' => false]);
        }
        $bank_account->delete();

        $user = JWTAuth::user();
        $action = 'Deleted';
        $itemName = $bank_account->account_name . ' bank account';
        $itemUnique = $bank_account->id;
        $tableName = 'bank_accounts';
        recordActivity($user, $action, $itemName, $itemUnique, $tableName);

        return response()->json(['message' => 'Bank Account Deleted Successfully', 'success' => true]);
    }

    public function getAllBankAccounts(Request  $request)
    {
        $advanceSearch = json_decode($request->advanceSearch);
        $query = bankAccount::query();

        $documentRecords = null;

        if ($request->searchTerm != '' && $request->searchTerm != null) {
            $query->orWhere('id', 'LIKE', "%" . $request->searchTerm . "%");
            $query->orWhere('iban', 'LIKE', "%" . $request->searchTerm . "%");
            $query->orWhere('account_name', 'LIKE', "%" . $request->searchTerm . "%");
            $query->orWhere('bank_name', 'LIKE', "%" . $request->searchTerm . "%");
        }


        if ($advanceSearch->balance != '' && $advanceSearch->balance != null) {
            $query->where('balance', (float)$advanceSearch->balance);
        }

        if ($advanceSearch->account_name != '' && $advanceSearch->account_name != null) {
            $query->orWhere('account_name', 'LIKE', "%" . $advanceSearch->account_name . "%");
        }
        if ($advanceSearch->bank_name != '' && $advanceSearch->bank_name != null) {
            $query->orWhere('bank_name', 'LIKE', "%" . $advanceSearch->bank_name . "%");
        }
        if ($advanceSearch->iban != '' && $advanceSearch->iban != null) {
            $query->orWhere('iban', 'LIKE', "%" . $advanceSearch->iban . "%");
        }

        $documentRecords = clone $query;

        $totalRows = $documentRecords->count();
        $documentRecords = $query->latest()->get();


        $resourceRecords = $this->paginaterhelp($request->page_no, $documentRecords, $request);


        return walletDataResource::collection($resourceRecords)->additional(['success' => true, 'base_url' => url('/'), 'success' => true, 'totalRows' => $totalRows, 'message' => 'Bank Accounts fetched.']);
    }
    public function addBankAccountTransaction(Request $request)
    {


        $messages = [
            'bank_account_id.required'  => 'bank_account_id must be required.',
            'transaction_type.required'  => 'Transaction Type must be required.',
            'sub_type.required'  => 'sub_type must be required.',
            'to_from.required'  => 'to/from must be required.',
            'credit_debit.required'  => 'credit_debit must be required.',
            'date.required'  => 'Date must be required.',
            'amount.required'  => 'Amount must be required.',



        ];

        $validate = Validator::make($request->all(), [
            'bank_account_id' => 'required|numeric',
            'transaction_type' => 'required|string',
            'amount' => 'required|numeric',
            'date' => 'required|string|max:255',

        ], $messages);

        if ($validate->fails()) {

            return response()->json(['message' => $validate->errors()->first(), 'success' => false, 'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);
        }


        // validation for dynamic fields on change transaction type
        if ($request->transaction_type == "Client Payment" || $request->transaction_type == "Vendor Payment" || $request->transaction_type == "Supplier Payment" || $request->transaction_type == "Transfer to Owner") {
            $validate = Validator::make($request->all(), [
                'to_from' => 'required',
            ], $messages);
        } else if ($request->transaction_type == "Employee Payments" || $request->transaction_type == "HR Payments" || $request->transaction_type == "Travel Expense" || $request->transaction_type == "Purchase" || $request->transaction_type == "Return Transfer" || $request->transaction_type == "Utility Payments" || $request->transaction_type == "Others" || $request->transaction_type == "Withdrawal" || $request->transaction_type == "Transfer to Other Bank") {
            $validate = Validator::make($request->all(), [
                'sub_type' => 'required',
            ], $messages);
            if ($request->transaction_type == "Utility Payments" && $request->sub_type != "Fuel" && $request->sub_type != "Others" && $request->sub_type != "") {
                $validate = Validator::make($request->all(), [
                    'to_from' => 'required',
                ], $messages);
            } else if ($request->transaction_type == "Withdrawal" && $request->sub_type == "Petty Cash" && $request->sub_type != "") {
                $validate = Validator::make($request->all(), [
                    'to_from' => 'required',
                ], $messages);
            }
        } else if ($request->transaction_type == "Un-Verified" || $request->transaction_type == "Others") {
            $validate = Validator::make($request->all(), [
                'credit_debit' => 'required',
            ], $messages);
        } else if ($request->transaction_type == "Petty Cash") {
            $validate = Validator::make($request->all(), [
                'sub_type' => 'required',
                'to_from' => 'required',
            ], $messages);
        }

        if ($validate->fails()) {

            return response()->json(['message' => $validate->errors()->first(), 'success' => false, 'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);
        }



        $input = $request->all();
        $input['amount'] = (float) $request->amount;

        if (isset($request->credit_debit)) {

            $bank_account = bankAccount::where('id', $request->bank_account_id)->first();
            if ($request->credit_debit == "Credit") {

                $bank_account->balance = (float) $bank_account->balance + (float) $input['amount'];
            } else if ($request->credit_debit == "Debit") {
                $bank_account->balance = (float) $bank_account->balance - (float) $input['amount'];
            }
            $bank_account->save();
        }
        if (($request->transaction_type == "Withdrawal" && $request->sub_type == "Petty Cash") || $request->transaction_type == "Petty Cash") {

            $wallet_tmp = wallet::where('id', $request->to_from)->first();
            $wallet_tmp->balance = (float) $wallet_tmp->balance + (float) $input['amount'];
            $wallet_tmp->save();
        }

        if ($request->transaction_type == "Transfer to Other Bank") {

            $bank_account_tmp = bankAccount::where('id', $request->sub_type)->first();
            $bank_account_tmp->balance = (float) $bank_account_tmp->balance + (float) $input['amount'];
            $bank_account_tmp->save();
            $bank_transaction_tmp = new bankAccountTransaction();
            $bank_transaction_tmp->bank_account_id = $request->sub_type;
            $bank_transaction_tmp->transaction_type = "Transfer from other bank";
            $bank_transaction_tmp->sub_type = "SR ( ".(float) $input['amount']." ) transferred from ".$bank_account->account_name;

            $bank_transaction_tmp->amount = (float) $input['amount'];
            $bank_transaction_tmp->date = Carbon::today()->toDateString();
            $bank_transaction_tmp->created_by = Auth::user()->name;

            $bank_transaction_tmp->save();
            $input['to_from'] = $bank_transaction_tmp->id;

        }

        $input['created_by'] = Auth::user()->name;
        $input['updated_by'] = null;

        if ($input['transaction_type'] == "Client Payment" || $input['transaction_type'] == "Vendor Payment") {
            if ($request->has('sub_type') && !empty(trim($request->sub_type))) {
                $time = strtotime($request->sub_type);
                $month = date('m', strtotime("+1 week", $time));
                $year = date('Y', strtotime("+1 week", $time));
                $input['sub_type'] = $year . "-" . $month;
            }
        } else if ($input['transaction_type'] == "Employee Payments" || $input['transaction_type'] == "HR Payments") {
            if (is_array($request->to_from)) {

                $input['to_from'] = json_encode($request->to_from);
            }
        }


        $bank_transaction = bankAccountTransaction::create($input);


        $user = JWTAuth::user();
        $action = 'CREATED';
        $itemName = $bank_transaction->transaction_type . ' bankAccount';
        $itemUnique = $bank_transaction->id;
        $tableName = 'bank_accounts';
        recordActivity($user, $action, $itemName, $itemUnique, $tableName);

        return response()->json(['message' => 'Bank Account Transaction Created', 'success' => true, 'data' => json_decode(json_encode($bank_transaction))]);
    }

    public function getBankTransaction(Request  $request)
    {

        $Document = bankAccountTransaction::where('id', $request->id)->first();
        if ($Document->transaction_type == "Client Payment") {
            $Document->to_from_name = client_base::where('client_code', $Document->to_from)->pluck('client_name')->first();
        } else if ($Document->transaction_type == "Employee Payments" || $Document->transaction_type == "HR Payments") {
            if (isset($Document->to_from) & !empty($Document->to_from) && $Document->to_from != null && $Document->to_from != '') {
                $Document->to_from =  json_decode($Document->to_from, true);
                // $Document->to_from_name = '';
            }
        } else if ($Document->transaction_type == "Vendor Payment") {
            $Document->to_from_name = vendor_base::where('id', $Document->to_from)->pluck('name')->first();
        } else if ($Document->transaction_type == "Utility Payments" && $Document->sub_type != "Fuel" && $Document->sub_type != "Others" && $Document->sub_type != "") {
            $Document->to_from_name = accommodation_base::where('id', $Document->to_from)->pluck('name')->first();
        } else if ($Document->transaction_type == "Supplier Payment") {
            $Document->to_from_name = Supplier::where('id', $Document->to_from)->pluck('name')->first();
        } else if (($Document->transaction_type == "Withdrawal" && $Document->sub_type == "Petty Cash") || $Document->transaction_type == "Petty Cash") {

            $Document->to_from_name = wallet::where('id', $Document->to_from)->pluck('account_name')->first();
        }else if ($Document->transaction_type == "Transfer to Owner") {

            $Document->to_from_name = ownerBank::where('id', $Document->to_from)->pluck('account_name')->first();
        }
        else if ($Document->transaction_type == "Transfer to Other Bank") {

            $Document->to_from_name = bankAccount::where('id', $Document->sub_type)->pluck('account_name')->first();
        }
        return response()->json(['message' => 'bank Account Transaction fetched', 'success' => true, 'base_url' => url('/'), 'data' => json_decode(json_encode($Document))]);
    }

    public function updateBankAccountTransaction(Request $request)
    {


        $messages = [
            'bank_account_id.required'  => 'bank_account_id must be required.',
            'transaction_type.required'  => 'Transaction Type must be required.',
            'sub_type.required'  => 'sub_type must be required.',
            'to_from.required'  => 'to/from must be required.',
            'credit_debit.required'  => 'credit_debit must be required.',
            'date.required'  => 'Date must be required.',
            'amount.required'  => 'Amount must be required.',



        ];

        $validate = Validator::make($request->all(), [
            'bank_account_id' => 'required|numeric',
            'transaction_type' => 'required|string',
            'amount' => 'required|numeric',
            // 'file.*' => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar',
            'date' => 'required|string|max:255',


        ], $messages);

        if ($validate->fails()) {

            return response()->json(['message' => $validate->errors()->first(), 'success' => false, 'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);
        }

        $bankAccountTransaction_check = bankAccountTransaction::where('id', $request->id)->first();
        
        if($bankAccountTransaction_check->transaction_type == "Transfer from other bank"){
            return response()->json(['message' => "This transaction is dependent on others. Don't update/delete", 'success' => false, 'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);

        }

        // validation for dynamic fields on change transaction type
        if ($request->transaction_type == "Client Payment" || $request->transaction_type == "Vendor Payment" || $request->transaction_type == "Supplier Payment") {
            $validate = Validator::make($request->all(), [
                'to_from' => 'required',
            ], $messages);
        } else if ($request->transaction_type == "Employee Payments" || $request->transaction_type == "HR Payments" || $request->transaction_type == "Travel Expense" || $request->transaction_type == "Purchase" || $request->transaction_type == "Return Transfer" || $request->transaction_type == "Utility Payments" || $request->transaction_type == "Others" || $request->transaction_type == "Withdrawal" || $request->transaction_type == "Transfer to Other Bank") {
            $validate = Validator::make($request->all(), [
                'sub_type' => 'required',
            ], $messages);
            if ($request->transaction_type == "Utility Payments" && $request->sub_type != "Fuel" && $request->sub_type != "Others" && $request->sub_type != "") {
                $validate = Validator::make($request->all(), [
                    'to_from' => 'required',
                ], $messages);
            } else if ($request->transaction_type == "Withdrawal" && $request->sub_type == "Petty Cash" && $request->sub_type != "") {
                $validate = Validator::make($request->all(), [
                    'to_from' => 'required',
                ], $messages);
            }
        } else if ($request->transaction_type == "Un-Verified" || $request->transaction_type == "Others") {
            $validate = Validator::make($request->all(), [
                'credit_debit' => 'required',
            ], $messages);
        } else if ($request->transaction_type == "Petty Cash") {
            $validate = Validator::make($request->all(), [
                'sub_type' => 'required',
                'to_from' => 'required',
            ], $messages);
        }

        if ($validate->fails()) {

            return response()->json(['message' => $validate->errors()->first(), 'success' => false, 'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);
        }

        $input = $request->all();
        $input['amount'] = (float) $request->amount;
        if (isset($request->credit_debit)) {

            $Document = bankAccountTransaction::where('id', $request->id)->first();
            $bank_account = bankAccount::where('id', $request->bank_account_id)->first();

            if ($Document->credit_debit == "Credit") {

                $bank_account->balance = (float) $bank_account->balance - (float) $Document->amount;
            } else if ($request->credit_debit == "Debit") {
                $bank_account->balance = (float) $bank_account->balance + (float) $Document->amount;
            }
            $bank_account->save();


            if ($request->credit_debit == "Credit") {

                $bank_account->balance = (float) $bank_account->balance + (float) $input['amount'];
            } else if ($request->credit_debit == "Debit") {
                $bank_account->balance = (float) $bank_account->balance - (float) $input['amount'];
            }
            $bank_account->save();
        }
        if (($Document->transaction_type == "Withdrawal" && $Document->sub_type == "Petty Cash") || $Document->transaction_type == "Petty Cash") {

            $wallet_tmp_1 = wallet::where('id', $Document->to_from)->first();
            $wallet_tmp_1->balance = (float) $wallet_tmp_1->balance - (float) $Document->amount;
            $wallet_tmp_1->save();
        }

        if (($request->transaction_type == "Withdrawal" && $request->sub_type == "Petty Cash") || $request->transaction_type == "Petty Cash") {

            $wallet_tmp = wallet::where('id', $request->to_from)->first();
            $wallet_tmp->balance = (float) $wallet_tmp->balance + (float) $input['amount'];
            $wallet_tmp->save();
        }

        if ($input['transaction_type'] == "Client Payment" || $input['transaction_type'] == "Vendor Payment") {
            if ($request->has('sub_type') && !empty(trim($request->sub_type))) {
                $time = strtotime($request->sub_type);
                $month = date('m', strtotime("+1 week", $time));
                $year = date('Y', strtotime("+1 week", $time));
                $input['sub_type'] = $year . "-" . $month;
            }
        } else if ($input['transaction_type'] == "Employee Payments" || $input['transaction_type'] == "HR Payments") {
            if (is_array($request->to_from)) {

                $input['to_from'] = json_encode($request->to_from);
            }
        }


        if ($Document->transaction_type == "Transfer to Other Bank") {

            // $bank_account_tt = bankAccount::where('id', $request->bank_account_id)->first();
            // $bank_account_tt->balance = (float) $bank_account_tt->balance + (float) $Document->amount;
            // $bank_account_tt->save();

            $bank_account_tmp = bankAccount::where('id', $Document->sub_type)->first();
            $bank_account_tmp->balance = (float) $bank_account_tmp->balance - (float) $Document->amount;
            $bank_account_tmp->save();

        }


        if ($request->transaction_type == "Transfer to Other Bank" && $Document->transaction_type == "Transfer to Other Bank") {

            $bank_account_tmp_t = bankAccount::where('id', $request->sub_type)->first();
            $bank_account_tmp_t->balance = (float) $bank_account_tmp_t->balance + (float) $input['amount'];
            $bank_account_tmp_t->save();

            if($Document->sub_type != $request->sub_type){
                $bank_transaction_tmp_d = bankAccountTransaction::where('id', $Document->to_from)->first();
                $bank_transaction_tmp_d->delete();

                $bank_transaction_tmp = new bankAccountTransaction();
                $bank_transaction_tmp->bank_account_id = $request->sub_type;
                $bank_transaction_tmp->transaction_type = "Transfer from other bank";
                $bank_transaction_tmp->sub_type = "SR ( ".(float) $input['amount']." ) transferred from ".$bank_account_tmp_t->account_name;
    
                $bank_transaction_tmp->amount = (float) $input['amount'];
                $bank_transaction_tmp->date = Carbon::today()->toDateString();
                $bank_transaction_tmp->created_by = Auth::user()->name;
    
                $bank_transaction_tmp->save();
            }else if($Document->sub_type == $request->sub_type){


                $bank_transaction_tmp = bankAccountTransaction::where('id', $Document->to_from)->first();
                $bank_transaction_tmp->bank_account_id = $request->sub_type;
                $bank_transaction_tmp->transaction_type = "Transfer from other bank";
                $bank_transaction_tmp->sub_type = "SR ( ".(float) $input['amount']." ) transferred from ".$bank_account_tmp->account_name;
    
                $bank_transaction_tmp->amount = (float) $input['amount'];
                $bank_transaction_tmp->date = Carbon::today()->toDateString();
                $bank_transaction_tmp->created_by = Auth::user()->name;
    
                $bank_transaction_tmp->save();

            }




            $input['to_from'] = $bank_transaction_tmp->id;

        }else if($request->transaction_type != "Transfer to Other Bank" && $Document->transaction_type == "Transfer to Other Bank"){
            $bank_transaction_tmp_d = bankAccountTransaction::where('id', $Document->to_from)->first();
            $bank_transaction_tmp_d->delete();
        }

        // $input['created_by'] = Auth::user()->name;
        $input['updated_by'] = Auth::user()->name;


        bankAccountTransaction::updateOrCreate([
            'id' => $request->id
        ], $input);


        $bank_transaction = bankAccountTransaction::where('id', $request->id)->first();




        $user = JWTAuth::user();
        $action = 'updated';
        $itemName = $bank_transaction->transaction_type . ' bankAccountTransaction';
        $itemUnique = $bank_transaction->id;
        $tableName = 'bank_accounts';
        recordActivity($user, $action, $itemName, $itemUnique, $tableName);

        return response()->json(['message' => 'Bank Account Transaction Updated', 'success' => true, 'data' => json_decode(json_encode($bank_transaction))]);
    }


    public function deleteBankAccountTransaction(Request  $request)
    {

        $bank_account_transaction = bankAccountTransaction::where('id', $request->id)->first();
        $bankAccount = bankAccount::where('id', $bank_account_transaction->bank_account_id)->first();

        if($bank_account_transaction->transaction_type == "Transfer from other bank"){
            return response()->json(['message' => "This transaction is dependent on others. Don't update/delete", 'success' => false, 'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);

        }

        if ($bank_account_transaction->credit_debit == "Credit") {

            $bankAccount->balance = (float) $bankAccount->balance - (float) $bank_account_transaction->amount;
        } else if ($bank_account_transaction->credit_debit == "Debit") {

            $bankAccount->balance = (float) $bankAccount->balance + (float) $bank_account_transaction->amount;
        }
        $bankAccount->save();

        if (($bank_account_transaction->transaction_type == "Withdrawal" && $bank_account_transaction->sub_type == "Petty Cash") || $bank_account_transaction->transaction_type == "Petty Cash") {

            $wallet_tmp = wallet::where('id', $bank_account_transaction->to_from)->first();
            $wallet_tmp->balance = (float) $wallet_tmp->balance - (float) $bank_account_transaction->amount;
            $wallet_tmp->save();
        }else if($bank_account_transaction->transaction_type == "Transfer to Other Bank"){
            $bank_account_tmp = bankAccount::where('id', $bank_account_transaction->sub_type)->first();
            $bank_account_tmp->balance = (float) $bank_account_tmp->balance - (float) $bank_account_transaction->amount;
            $bank_account_tmp->save();
            $bank_transaction_tmp_d = bankAccountTransaction::where('id', $bank_account_transaction->to_from)->first();
            $bank_transaction_tmp_d->delete();
        }

        $user = JWTAuth::user();
        $action = 'Deleted';
        $itemName = $bankAccount->bank_account_id . ' bankAccount';
        $itemUnique = $bankAccount->id;
        $tableName = 'bank_account_transactions';
        recordActivity($user, $action, $itemName, $itemUnique, $tableName);

        $bank_account_transaction->delete();
        return response()->json(['message' => 'Bank Account Transaction Deleted Successfully', 'success' => true]);
    }

    public function getAllBankTransactions(Request  $request)
    {
        $advanceSearch = json_decode($request->advanceSearch);
        $query = bankAccountTransaction::query();
        $query->where('bank_account_id', $request->id);
        $documentRecords = null;

        if ($request->searchTerm != '' && $request->searchTerm != null) {
            $query->orWhere('transaction_type', 'LIKE', "%" . $request->searchTerm . "%");
            // $query->orWhere('resource_owner_id', 'LIKE', "%".$request->searchTerm."%");
            // $query->orWhere('resource_owner_name', 'LIKE', "%".$request->searchTerm."%");
            $query->orWhere('amount', $request->searchTerm);
            $query->orWhere('id', $request->searchTerm);
        }

        if ($request->day != '' && $request->day != null) {
            if ($request->day == "today") {
                $query->whereDate('created_at', Carbon::today());
            } else if ($request->day == "yesterday") {
                $query->whereDate('created_at', Carbon::yesterday());
            }
        }
        if ($advanceSearch->transaction_type != '' && $advanceSearch->transaction_type != null) {
            $query->where('transaction_type', 'LIKE', "%" . $advanceSearch->transaction_type . "%");
        }
        if ($advanceSearch->notes != '' && $advanceSearch->notes != null) {
            $query->where('notes', 'LIKE', "%" . $advanceSearch->notes . "%");
        }

        if ($advanceSearch->date != '' && $advanceSearch->date != null) {
            $query->where('date', 'LIKE', "%" . $advanceSearch->date . "%");
        }
        $documentRecords = clone $query;

        $totalRows = $documentRecords->count();
        $documentRecords = $query->latest()->get();
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


        $resourceRecords = $this->paginaterhelp($request->page_no, $documentRecords, $request);


        return documentDataResource::collection($resourceRecords)->additional(['success' => true, 'base_url' => url('/'), 'success' => true, 'totalRows' => $totalRows, 'message' => 'bank transactios fetched.']);
    }



    public function bankAccountResourceOwner(Request  $request)
    {

        $userLevels = null;
        if ($request->type == "Accommodation") {

            $userLevels = accommodation_base::select('name', 'id')->where(DB::raw("CONCAT(`name`, ' ', `id`)"), 'LIKE', "%" . $request->id . "%")->get();
        } else if ($request->type == "Client") {

            $userLevels = client_base::select('client_name AS name', 'client_code AS id')->where(DB::raw("CONCAT(`client_name`, ' ', `client_code`)"), 'LIKE', "%" . $request->id . "%")->get();
        } else if ($request->type == "Employee") {

            $userLevels = employee::select('name', 'emp_id AS id')->where(DB::raw("CONCAT(`name`, ' ', `emp_id`)"), 'LIKE', "%" . $request->id . "%")->get();
        } else if ($request->type == "Supplier") {

            $userLevels = Supplier::select('name', 'id')->where(DB::raw("CONCAT(`name`, ' ', `id`)"), 'LIKE', "%" . $request->id . "%")->get();
        } else if ($request->type == "Vendor") {

            $userLevels = vendor_base::select('name', 'id')->where(DB::raw("CONCAT(`name`, ' ', `id`)"), 'LIKE', "%" . $request->id . "%")->get();
        } else if ($request->type == "Payment Request") {

            $userLevels = PaymentRequest::latest()->select('total_amount', 'id')->get();
            foreach ($userLevels as $key => $userLevel) {
                $userLevel->name = "ID = " . $userLevel->id . " , Amount = " . $userLevel->total_amount;
            }
        } else if ($request->type == "PettyCash") {

            $userLevels = wallet::select('account_name AS name', 'id')->where(DB::raw("CONCAT(`account_name`, ' ', `id`)"), 'LIKE', "%" . $request->id . "%")->get();
        }
        else if ($request->type == "Bank") {

            $userLevels = bankAccount::select('account_name AS name', 'id')->where(DB::raw("CONCAT(`account_name`, ' ', `id`)"), 'LIKE', "%" . $request->id . "%")->get();
        }else if ($request->type == "owner_bank") {

            $userLevels = ownerBank::select('account_name AS name', 'id')->where(DB::raw("CONCAT(`account_name`, ' ', `id`)"), 'LIKE', "%" . $request->id . "%")->get();
        }







        return response()->json(['message' => 'Bank Account Resource Owners Fetched', 'success' => true, 'data' => json_decode(json_encode($userLevels))]);
    }
















    public function bank_accounts_transactions_export_excel(Request $request){
        
        $user_id = $request->user_id;

        return Excel::download(new bankAccountTransactionExport($request->searchTerm,(int)$request->limit,(int)$user_id), 'bank_accounts_transactions_export_excel.xlsx');

    }








    function img_upload($tmpImage)

    {
        $image = $tmpImage;
        $file_fullname = $image->getClientOriginalName();
        $file_name = pathinfo($file_fullname, PATHINFO_FILENAME);
        $file_extension = $image->getClientOriginalExtension();
        $rand_namer = now();
        $rand_namer = preg_replace('/\s+/', '_', trim($rand_namer));
        $rand_namer = preg_replace('/:+/', '_', trim($rand_namer));
        $file_namefor_db = $file_name . '_' . $rand_namer . '.' . $file_extension;
        $file_namefor_db = preg_replace('/\s+/', '_', trim($file_namefor_db));

        $image->storeAs('documents', $file_namefor_db, 'public');
        $pic = ('storage/documents/' . $file_namefor_db);

        return $pic;
    }


    function paginaterhelp($page, $items, $request)

    {


        $page = $page;

        // Number of items per page
        $perPage = 10;

        // Start displaying items from this number;
        $offSet = ($page * $perPage) - $perPage; // Start displaying items from this number

        // Get only the items you need using array_slice 

        if (is_array($items)) {
            $itemsForCurrentPage = array_slice($items, $offSet, $perPage, false);
        } else {
            $itemsForCurrentPage = $items->slice($offSet, $perPage);
            /*$itemsForCurrentPage = array_slice($items->toArray(), $offSet, $perPage, false);*/
        }

        // Return the paginator with only 10 items but with the count of all items and set the it on the correct page
        $result = new LengthAwarePaginator($itemsForCurrentPage, count($items), $perPage, $page, ['path' => $request->url(), 'query' => $request->query()]);

        //  $result = json_decode(json_encode($result,true),true);

        return $result->items();
    }
}
