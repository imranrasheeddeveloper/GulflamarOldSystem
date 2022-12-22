<?php


namespace App\Http\Controllers\api;

use Illuminate\Pagination\LengthAwarePaginator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\ExpenseAccount;

use App\Models\pattyCash;
use App\Models\ExpenseCategory;


use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use JWTAuth;
use Validator;
use App\Http\Resources\walletDataResource;
use Illuminate\Support\Facades\DB;


use App\Exports\walletExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;

class ExpenseAccountController extends Controller
{
    public function addExpenseAccount(Request $request)
    {


        $messages = [
            'account_name.required'  => 'Account Name must be required.',
            'period.required'  => 'period must be required.',



        ];

        $validate = Validator::make($request->all(), [
            'account_name' => 'required|string|max:255',
            // 'file.*' => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar',
            'period' => 'required|string|max:255',


        ], $messages);

        if ($validate->fails()) {

            return response()->json(['message' => $validate->errors()->first(), 'success' => false, 'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);
        }


        $input = $request->all();
        if ($request->has('period') && !empty(trim($request->period))) {
            $time = strtotime($request->period);
            $input['month'] = date('m', strtotime("+1 week", $time));
            $input['year'] = date('Y', strtotime("+1 week", $time));
        }
        $input['created_by'] = Auth::user()->name;
        $input['updated_by'] = null;

        $wallet = ExpenseAccount::create($input);


        $user = JWTAuth::user();
        $action = 'CREATED';
        $itemName = $wallet->account_name . ' ExpenseAccount';
        $itemUnique = $wallet->id;
        $tableName = 'ExpenseAccounts';
        recordActivity($user, $action, $itemName, $itemUnique, $tableName);

        return response()->json(['message' => 'ExpenseAccount Created', 'success' => true, 'data' => json_decode(json_encode($wallet))]);
    }


    public function getExpenseAccounts(Request  $request)
    {


        $advanceSearch = json_decode($request->advanceSearch);
        $query = ExpenseAccount::query();

        $user = JWTAuth::user();

        $documentRecords = null;

        if ($request->searchTerm != '' && $request->searchTerm != null) {
            $query->orWhere('id', 'LIKE', "%" . $request->searchTerm . "%");
            // $query->orWhere('user_id', 'LIKE', "%".$request->searchTerm."%");
            $query->orWhere('account_name', 'LIKE', "%" . $request->searchTerm . "%");
        }

        if($advanceSearch->account_name != '' && $advanceSearch->account_name != null){
            $query->orWhere('account_name', 'LIKE', "%".$advanceSearch->account_name."%");
        }
        $documentRecords = clone $query;
        $totalRows = $documentRecords->count();
        $total_balance = 0.0;
        $documentRecords = $query->latest()->get();
        $ExpenseCategory_data = ExpenseCategory::all()->groupBy('account_id');
        $attributes = array_keys($ExpenseCategory_data->toArray());

        foreach ($documentRecords as $item) {

            if ($item->year != null && $item->year != '' && $item->month != null && $item->month != '') {

                $date = $item->year . '-' . $item->month . '-01';
                $period = Carbon::createFromFormat('Y-m-d', $date);
            } else {
                $period = '';
            }
            $item['period'] = $period;
            $item['total'] = 0.00;
        if(count($ExpenseCategory_data) > 0){
            foreach ($attributes as $att) {
                if($item['id'] == $att){
                    $item['total'] = pattyCash::whereIn('id', $ExpenseCategory_data[$att]->pluck('transaction_id')->toArray())->where('transaction_type','<>','Credit')->where('transaction_type','<>','Adjustment')->where('transaction_type','<>','Fuel Credit')->get()->sum('total');
                    $item['total'] = number_format($item['total'],2);
                }
            }
        }
        $val = str_replace(",", "", $item['total']);
        $temp_balance =  $item->credit - $item->adjustment -  $val + $item->fuel_credit;
        $item['balance'] = round($temp_balance, 2);
        $total_balance = $total_balance + $item['balance'];
        }
        $total_wallet_balance = Wallet::sum('balance');
        $resourceRecords = $this->paginaterhelp($request->page_no, $documentRecords, $request);
        return walletDataResource::collection($resourceRecords)->additional(['success' => true, 'base_url' => url('/'), 'user_id' => $user->id, 'success' => true, 'total_balance' => round($total_balance, 2), 'total_wallet_balance' => round($total_wallet_balance, 2) ,'totalRows' => $totalRows,  'message' => 'Expense Account fetched.']);
    }

    function getAllExpenseAccounts(Request  $request){
         $query = ExpenseAccount::all();
         $temp_balance =  $query->credit - $query->adjustment -  $val + $query->fuel_credit;
         $item['balance'] = round($temp_balance, 2);

    }




    public function getExpenseAccount(Request  $request)
    {

        $wallet = ExpenseAccount::where('id', $request->id)->first();

        if ($wallet->year != null && $wallet->year != '' && $wallet->month != null && $wallet->month != '') {

            $date = $wallet->year . '-' . $wallet->month . '-01';
            $period = Carbon::createFromFormat('Y-m-d', $date);
        } else {
            $period = '';
        }
        $wallet['period'] = $period;
        $wallet['total'] = 0.00;

        $data = ExpenseCategory::where('account_id', $request->id)->pluck('transaction_id')->toArray();
        if(count($data) > 0){
            $pettyCash_total = pattyCash::whereIn('id', $data)->where('transaction_type','<>','Credit')->where('transaction_type','<>','Adjustment')->where('transaction_type','<>','Fuel Credit')->get()->sum('total');
            $wallet['total'] = number_format($pettyCash_total,2);

        }


        return response()->json(['message' => 'Expense Account Fetched', 'success' => true, 'data' => json_decode(json_encode($wallet))]);
    }



    public function updateExpenseAccount(Request $request)
    {


        $messages = [
            'id.required'  => 'Id must be required.',
            'account_name.required'  => 'Account Name must be required.',
            'period.required'  => 'Period must be required.',



        ];

        $validate = Validator::make($request->all(), [
            'id' => 'required|numeric',
            'account_name' => 'required|string|max:255',
            // 'file.*' => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar',
            'period' => 'required|string|max:255',


        ], $messages);

        if ($validate->fails()) {

            return response()->json(['message' => $validate->errors()->first(), 'success' => false, 'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);
        }

        $input = $request->all();
        if ($request->has('period') && !empty(trim($request->period))) {
            $time = strtotime($request->period);
            $input['month'] = date('m', strtotime("+1 week", $time));
            $input['year'] = date('Y', strtotime("+1 week", $time));
        }
        // $input['created_by'] = Auth::user()->name;
        $input['updated_by'] = Auth::user()->name;


        ExpenseAccount::updateOrCreate([
            'id' => $request->id
        ], $input);


        $wallet = ExpenseAccount::where('id', $request->id)->first();




        $user = JWTAuth::user();
        $action = 'updated';
        $itemName = $wallet->account_name . ' ExpenseAccount';
        $itemUnique = $wallet->id;
        $tableName = 'ExpenseAccounts';
        recordActivity($user, $action, $itemName, $itemUnique, $tableName);

        return response()->json(['message' => 'ExpenseAccount Updated', 'success' => true, 'data' => json_decode(json_encode($wallet))]);
    }



    public function deleteExpenseAccount(Request  $request)
    {

        $wallet = ExpenseAccount::where('id', $request->id)->first();


        $user = JWTAuth::user();
        $action = 'Deleted';
        $itemName = $wallet->account_name . ' ExpenseAccount';
        $itemUnique = $wallet->id;
        $tableName = 'ExpenseAccounts';
        recordActivity($user, $action, $itemName, $itemUnique, $tableName);

        $ExpenseCategory_delete = ExpenseCategory::where('account_id', $request->id)->get();
        if(count($ExpenseCategory_delete) > 0){
            foreach ($ExpenseCategory_delete as $item) {
                $item->delete();
            }
        }

        $wallet->delete();


        return response()->json(['message' => 'ExpenseAccount Deleted Successfully', 'success' => true]);
    }


    public function getExpenseCategoryDetail(Request  $request)
    {
        $documentRecords = [];

        $data = ExpenseCategory::where('account_id', $request->id)->pluck('transaction_id')->toArray();

        if(count($data) > 0){
            // $pettyCash = pattyCash::whereIn('id', $data)->get()->groupBy('transaction_type');

            // $attributes = array_keys($pettyCash->toArray());
    
            // foreach ($attributes as $att) {
    
            //     array_push($documentRecords, ['category' => $att, 'amount' => $pettyCash[$att],'account_id' => $request->id]);
            // }
        }


        $advanceSearch = json_decode($request->advanceSearch);
        // return $advanceSearch;

        // $pettyCash = $pettyCash[$request->category];
        // $query = $pettyCash;

        $query = pattyCash::query();
        $query->whereIn('id', $data);
        $query->with('wallets')->get();
        if($request->category != '' && $request->category != null){
            $query->where('transaction_type', $request->category);


        }
        
    
        // $query->where('account_id', $request->id);

        // $documentRecords = null;

        if($request->searchTerm != '' && $request->searchTerm != null){
            $query->where(DB::raw("CONCAT(`id`, ' ',`assign_to_entity`,' ',`assign_to_category`,' ')"), 'LIKE', "%" . $request->searchTerm . "%");


        }

        if ($request->day != '' && $request->day != null) {
            if ($request->day == "today") {
                $query->whereDate('created_at', Carbon::today());
            } else if ($request->day == "yesterday") {
                $query->whereDate('created_at', Carbon::yesterday());
            }
        }
        if ($advanceSearch->assign_to_category != '' && $advanceSearch->assign_to_category != null) {
            $query->where('assign_to_category', $advanceSearch->assign_to_category);
        }
        if ($advanceSearch->total != '' && $advanceSearch->total != null) {
            $query->where('total', (float)$advanceSearch->total);
        }
        if ($advanceSearch->assign_to_entity != '' && $advanceSearch->assign_to_entity != null && $advanceSearch->assign_to_entity_id != '') {
            $query->where('assign_to_entity', $advanceSearch->assign_to_entity)->where('assign_to_entity_id', $advanceSearch->assign_to_entity_id);
        }

        if ($advanceSearch->fromDate != '' && $advanceSearch->fromDate != null && $advanceSearch->toDate != '' && $advanceSearch->toDate != null) {
            $query->whereBetween('date', [$advanceSearch->fromDate, $advanceSearch->toDate]);
        }

        // if ($advanceSearch->transaction_type != '' && $advanceSearch->transaction_type != null) {
        //     $query->where('transaction_type', $advanceSearch->transaction_type);
        // }

        // if ($advanceSearch->approve != '' && $advanceSearch->approve != null) {
        //     if ($advanceSearch->approve == '1' || $advanceSearch->approve == '0' || $advanceSearch->approve == 1 || $advanceSearch->approve == 0) {

        //         $query->where('approve', $advanceSearch->approve);
        //     }
        // }

        if($advanceSearch->notes != '' && $advanceSearch->notes != null){
            $query->where('notes', 'LIKE', "%" . $advanceSearch->notes . "%");

        }
        if($advanceSearch->description != '' && $advanceSearch->description != null){
            $query->where('description', 'LIKE', "%" . $advanceSearch->description . "%");

        }

        $documentRecords = clone $query;

        $totalRows = $documentRecords->count();
        $query->orderBy('id', 'desc');
        $documentRecords = $query->get();
        foreach($documentRecords as $item){
            $item['attachment'] = json_decode($item->attachment);
                if($item->approve == 1){
                    $item->approve = true;

            }else{
                $item->approve = false;

            }

        if($item->accept == 1){
                $item->accept = true;

        }else{
            $item->accept = false;

        }
        }

        $resourceRecords = $this->paginaterhelp($request->page_no, $documentRecords, $request);


        return walletDataResource::collection($resourceRecords)->additional(['success' => true, 'base_url' => url('/'), 'success' => true, 'totalRows' => $totalRows, 'message' => 'Transaction fetched.']);
    }



    public function getExpenseAccountAllSummary(Request  $request)
    {


        $documentRecords =  [];

        $data = ExpenseCategory::where('account_id', $request->id)->pluck('transaction_id')->toArray();

        if(count($data) > 0){
            $pettyCash = pattyCash::whereIn('id', $data)->get()->groupBy('transaction_type')->map(function ($row) {
                return $row->sum('total');
            });

            $attributes = array_keys($pettyCash->toArray());
    
            foreach ($attributes as $att) {
                array_push($documentRecords, ['category' => $att, 'amount' => number_format($pettyCash[$att],2),'account_id' => $request->id]);
            }
        }





        $totalRows = count($documentRecords);


        $resourceRecords = $this->paginaterhelp($request->page_no, $documentRecords, $request);


        return walletDataResource::collection($resourceRecords)->additional(['success' => true, 'base_url' => url('/'), 'success' => true, 'totalRows' => $totalRows, 'message' => 'Transaction fetched.']);
    }
    public function getAllTransactionExpenseAccount(Request  $request)
    {

        $transactionIdArray = ExpenseCategory::all()->pluck('transaction_id')->toArray();

        $advanceSearch = json_decode($request->advanceSearch);
        $query = pattyCash::query();
        $query->whereNotIn('id', $transactionIdArray);
        $query->where('accept', 1);
        $query->where('approve', 1);
        $query->where('transaction_type', '!=' , 'Transfer to Wallet');
        
        
        $documentRecords = null;

        if ($request->searchTerm != '' && $request->searchTerm != null) {
            // $query->where('id',$request->searchTerm);
            $query->where(DB::raw("CONCAT(`id`, ' ', `credit_debit` , ' ' , `total`)"), 'LIKE', "%" . $request->searchTerm . "%");
        }

        if ($request->day != '' && $request->day != null) {
            if ($request->day == "today") {
                $query->whereDate('created_at', Carbon::today());
            } else if ($request->day == "yesterday") {
                $query->whereDate('created_at', Carbon::yesterday());
            }
        }

        if ($advanceSearch->fromDate != '' && $advanceSearch->fromDate != null && $advanceSearch->toDate != '' && $advanceSearch->toDate != null) {
            $query->whereBetween('date', [$advanceSearch->fromDate, $advanceSearch->toDate]);
        }

        if ($advanceSearch->transaction_type != '' && $advanceSearch->transaction_type != null) {
            $query->where('transaction_type', $advanceSearch->transaction_type);
        }
        if ($advanceSearch->assign_to_category != '' && $advanceSearch->assign_to_category != null) {
            $query->where('assign_to_category', $advanceSearch->assign_to_category);
        }
        if ($advanceSearch->total != '' && $advanceSearch->total != null) {
            $query->where('total', (float)$advanceSearch->total);
        }
        if ($advanceSearch->assign_to_entity != '' && $advanceSearch->assign_to_entity != null && $advanceSearch->assign_to_entity_id != '' && $advanceSearch->assign_to_entity_id != null) {
            $query->where('assign_to_entity', $advanceSearch->assign_to_entity);
            $query->where('assign_to_entity_id', $advanceSearch->assign_to_entity);
        }
        if($advanceSearch->notes != '' && $advanceSearch->notes != null){
            $query->where('notes', 'LIKE', "%" . $advanceSearch->notes . "%");

        }
        if($advanceSearch->description != '' && $advanceSearch->description != null){
            $query->where('description', 'LIKE', "%" . $advanceSearch->description . "%");

        }


        $documentRecords = clone $query;

        $totalRows = $documentRecords->count();
        $query->orderBy('id', 'desc');
        $documentRecords = $query->get();
        foreach ($documentRecords as $item) {
            $item['attachment'] = json_decode($item->attachment);
            if ($item->approve == 1) {
                $item->approve = true;
            } else {
                $item->approve = false;
            }

            if ($item->accept == 1) {
                $item->accept = true;
            } else {
                $item->accept = false;
            }
        $item['wallet_name'] = Wallet::where('id', $item->wallet_id)->pluck('account_name')->first();
        }

        $resourceRecords = $this->paginaterhelp($request->page_no, $documentRecords, $request);


        return walletDataResource::collection($resourceRecords)->additional(['success' => true, 'base_url' => url('/'), 'success' => true, 'totalRows' => $totalRows, 'message' => 'Transaction fetched.']);
    }




    public function addExpenseCategory(Request $request)
    {


        $messages = [

            'account_id.required'  => 'account id must be required.',
            'transaction_id.required'  => 'account id must be required.',

            // 'total.required'  => 'total must be required.',
        ];

        $validate = Validator::make($request->all(), [
            'account_id' => 'required|numeric',
            'transaction_id' => 'required|numeric',
            // 'total' => 'required',


        ], $messages);

        if ($validate->fails()) {

            return response()->json(['message' => $validate->errors()->first(), 'success' => false, 'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);
        }

        $pettyCash = pattyCash::where('id', $request->transaction_id)->first();
        $ExpenseAccount = ExpenseAccount::where('id', $request->account_id)->first();
        if($pettyCash->transaction_type == "Credit"){
            $ExpenseAccount->credit = $ExpenseAccount->credit + $pettyCash->total;

        }else if($pettyCash->transaction_type == "Adjustment"){
            $ExpenseAccount->adjustment = $ExpenseAccount->adjustment + $pettyCash->total;

        }
        // else if($pettyCash->transaction_type == "Refund"){
        //     $ExpenseAccount->refund = $ExpenseAccount->refund + $pettyCash->total;

        // }
        else if($pettyCash->transaction_type == "Fuel Credit"){
            $ExpenseAccount->fuel_credit = $ExpenseAccount->fuel_credit + $pettyCash->total;

        }
        $ExpenseAccount->save();

        $input = $request->all();

        $input['created_by'] = Auth::user()->name;
        $input['updated_by'] = null;

        $wallet = ExpenseCategory::create($input);

        // $ExpenseAccount = ExpenseAccount::where('id', $request->account_id)->first();
        // $ExpenseAccount->total = $ExpenseAccount->total + $request->total;
        // $ExpenseAccount->save();

        $user = JWTAuth::user();
        $action = 'CREATED';
        $itemName = $wallet->account_id . ' ExpenseCategory';
        $itemUnique = $wallet->id;
        $tableName = 'ExpenseCategory';
        recordActivity($user, $action, $itemName, $itemUnique, $tableName);

        return response()->json(['message' => 'ExpenseCategory Created', 'success' => true, 'data' => json_decode(json_encode($wallet))]);
    }


    public function deleteExpenseAccountCatgory(Request  $request)
    {

        $wallet = ExpenseCategory::where('transaction_id', $request->id)->first();

        $pettyCash = pattyCash::where('id', $wallet->transaction_id)->first();
        $ExpenseAccount = ExpenseAccount::where('id', $wallet->account_id)->first();
        if($pettyCash->transaction_type == "Credit"){
            $ExpenseAccount->credit = $ExpenseAccount->credit - $pettyCash->total;

        }else if($pettyCash->transaction_type == "Adjustment"){
            $ExpenseAccount->adjustment = $ExpenseAccount->adjustment - $pettyCash->total;

        }
        else if($pettyCash->transaction_type == "Fuel Credit"){
            $ExpenseAccount->fuel_credit = $ExpenseAccount->fuel_credit - $pettyCash->total;

        }
        $ExpenseAccount->save();


        $user = JWTAuth::user();
        $action = 'Deleted';
        $itemName = $wallet->account_name . ' ExpenseCategory';
        $itemUnique = $wallet->id;
        $tableName = 'ExpenseCategory';
        recordActivity($user, $action, $itemName, $itemUnique, $tableName);

        $wallet->delete();


        return response()->json(['message' => 'ExpenseAccount Category Deleted Successfully', 'success' => true]);
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

        $image->storeAs('expenseAccounts', $file_namefor_db, 'public');
        $pic = ('storage/expenseAccounts/' . $file_namefor_db);

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