<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\pattyCash;
use App\Models\Wallet;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use JWTAuth;
use Validator;
use App\Http\Resources\walletDataResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;





use Illuminate\Support\Facades\Auth;

class PattyCashController extends Controller
{
    public function addTransaction(Request $request)
    {

        $item_number = 0;

        $allTransactions = [];

        foreach (json_decode($request->items) as $item) {

            $files = null;

            $item_number++;

            $validate =  $this->IsValidateTransaction($item);
            if ($validate['success'] == false) {
                return response()->json(['message' => $validate['message'] . " In item " . $item_number, 'success' => false, 'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);
            }

            if ($item->attachment_length > 0 && $item->attachment_file_name != null) {

                if ($request->hasFile($item->attachment_file_name)) {

                    $files = $request->file($item->attachment_file_name);
                }
            }

            if ($files != null) {
                // For file validation as per transaction item
                $validate_temp = Validator::make($request->all(), [
                    $item->attachment_file_name . '.*' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar',
                ]);

                if ($validate_temp->fails()) {

                    return response()->json(['message' => $validate_temp->errors()->first(), 'success' => false, 'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);
                }
            }
        }



        foreach (json_decode($request->items) as $item) {


            if (isset($item)) {
                $files = null;
                if ($request->hasFile($item->attachment_file_name)) {

                    $files = $request->file($item->attachment_file_name);
                }


                $attachments = [];

                if ($files != null) {

                    // set all file name's with path into array and json_encode to store in DB 
                    foreach ($files as $file) {
                        $attachments[] =  $this->img_upload($file);
                    }
                }
                $item->attachment = [];

                $item->attachment = json_encode($attachments);

                $item->approve = (bool) $item->approve;
                $item->unit_price = (float) $item->unit_price;
                $item->quantity = (float) $item->quantity;
                $item->total = (float) $item->total;



                if (isset($item->assign_to_entity_id) && $item->assign_to_entity_id != '' && isset($item->assign_to_entity) && $item->assign_to_entity != '') {
                    $item->assign_to_entity_id = (int) $item->assign_to_entity_id;
                    $item->assign_to_entity = (string) $item->assign_to_entity;
                    // $item->assign_to_entity_id =  $item->assign_to_entity_id;
                    // $item->assign_to_entity = $item->assign_to_entity;
                } else {
                    $item->assign_to_entity_id = null;
                    $item->assign_to_entity = null;
                }

                $item->wallet_id = $request->id;
                $item->created_by = Auth::user()->name;

                $allTransactions[] = $item;

                $pattyCash = pattyCash::create(json_decode(json_encode($item), true));
            }
        }

 

        $user = JWTAuth::user();
        $action = 'CREATED';
        $itemName = 'multiple transaction against wallet wallet_id: ' . $request->id;
        $itemUnique = $request->id;
        $tableName = 'patty_cashes';
        recordActivity($user, $action, $itemName, $itemUnique, $tableName);
        $wallet = [];

        return response()->json(['message' => 'Transaction Created', 'success' => true, 'walletId' => $request->id, 'data' => json_decode(json_encode($allTransactions))]);
    }


    public function getTransaction(Request  $request){ 

        $Document = pattyCash::where('id', $request->id)->first();
        $Document->attachment = json_decode($Document->attachment);
        $Document->approve =  $Document->approve ? 'true' : 'false';
    
        return response()->json(['message'=>'Transactions fetched','success' => true,'base_url' => url('/'),'data' => json_decode(json_encode($Document))]);
    
    
    }


    public function getAllTransaction(Request  $request){ 

    
        $advanceSearch = json_decode($request->advanceSearch);
        $query = pattyCash::query();
        $query->where('wallet_id', $request->id);

        $documentRecords = null;

        if($request->searchTerm != '' && $request->searchTerm != null){
            // $query->where('id',$request->searchTerm);
            $query->where(DB::raw("CONCAT(`id`, ' ', `credit_debit` , ' ' , `total`)"), 'LIKE', "%" . $request->searchTerm . "%");


        }
        if($request->day != '' && $request->day != null){
            if($request->day == "today"){
                $query->whereDate('created_at', Carbon::today());

            }else if($request->day == "yesterday"){
                $query->whereDate('created_at', Carbon::yesterday());

            }

        }

        if($advanceSearch->fromDate != '' && $advanceSearch->fromDate != null && $advanceSearch->toDate != '' && $advanceSearch->toDate != null){
            $query->whereBetween('date', [$advanceSearch->fromDate, $advanceSearch->toDate]);


        }


        if($advanceSearch->transaction_type != '' && $advanceSearch->transaction_type != null){
            $query->where('transaction_type',$advanceSearch->transaction_type);

        }
        if($advanceSearch->assign_to_category != '' && $advanceSearch->assign_to_category != null){
            $query->where('assign_to_category',$advanceSearch->assign_to_category);

        }
        if($advanceSearch->total != '' && $advanceSearch->total != null){
            $query->where('total',(float)$advanceSearch->total);

        }
        if($advanceSearch->assign_to_entity != '' && $advanceSearch->assign_to_entity != null && $advanceSearch->assign_to_entity_id != '' && $advanceSearch->assign_to_entity_id != null){
            $query->where('assign_to_entity', $advanceSearch->assign_to_entity);
            $query->where('assign_to_entity_id', $advanceSearch->assign_to_entity_id);


        }
        if($advanceSearch->approve != '' && $advanceSearch->approve != null){
            if($advanceSearch->approve == '1' || $advanceSearch->approve == '0' || $advanceSearch->approve == 1 || $advanceSearch->approve == 0){

            $query->where('approve', $advanceSearch->approve);
            }

        }
        if($advanceSearch->notes != '' && $advanceSearch->notes != null){
            $query->where('notes', 'LIKE', "%" . $advanceSearch->notes . "%");

        }
        if($advanceSearch->description != '' && $advanceSearch->description != null){
            $query->where('description', 'LIKE', "%" . $advanceSearch->description . "%");

        }

        $documentRecords=clone $query;
        
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
        }

    $resourceRecords = $this->paginaterhelp($request->page_no,$documentRecords,$request);


    return walletDataResource::collection($resourceRecords)->additional(['success' => true,'base_url' => url('/'),'success' => true,'totalRows' => $totalRows,'message'=>'Transaction fetched.']);

    }


    public function updateTransaction(Request $request){


        $messages = [
            'id.required'  => 'Id must be required.',
            'wallet_id.required'  => 'wallet_id must be required.',
            'transaction_type.required'  => 'Transaction Type must be required.',
            'date.required'  => 'Date must be required.',
            'approve.required'  => 'Approve must be required.',
            'unit_price.required'  => 'Unit Price must be required.',
            'quantity.required'  => 'Quantity must be required.',
            'total.required'  => 'Total must be required.',
            'attachment.required'  => 'Previous Attachment must be required.',


        ];

        $validate = Validator::make($request->all(), [
            'id' => 'required',
            'wallet_id' => 'required',
            'transaction_type' => 'required|string|max:255',
            'date' => 'required|string|max:255',
            'approve' => 'required',
            'attachment' => 'required',

            'unit_price' => 'required|numeric',
            'quantity' => 'required|numeric',
            'total' => 'required|numeric',

            'file.*' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar',

        ], $messages);
    
        if ($validate->fails()) { 
                  
                return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);            
            }
    
        $input = $request->all();
    
        $attachments = [];
    
        foreach (json_decode($request->attachment) as $file) {
        $attachments[] =  $file;
        }
        $files = $request->file;
    
        if($files != null)
        {
         foreach($files as $file){
            $attachments[] =  $this->img_upload($file);
         }
         }
    
            // $input['approve'] = (bool) $request->approve;
            // $input['approve'] = (bool) $request->approve ? 1 : 0;
            $input['approve'] = $request->approve === 'true'? true: false;

            if($input['approve'] === true){

                $input['approve'] = 1;
            }else{

                $input['approve'] = 0;
            }
            $input['unit_price'] = (float) $request->unit_price;
            $input['quantity'] = (float) $request->quantity;
            $input['total'] = (float) $request->total;

            $Transaction = pattyCash::where('id', $request->id)->first();
            $wallet = Wallet::where('id', $Transaction->wallet_id)->first();
    
    
            if($Transaction->approve == 1 || $Transaction->approve == '1'){
    
                if($Transaction->credit_debit == "Credit"){
    
                    $wallet->balance = (float) $wallet->balance - (float) $Transaction->total;
        
                }else if($Transaction->credit_debit == "Debit"){
                    $wallet->balance = (float) $wallet->balance + (float) $Transaction->total;
                }

                if($input['credit_debit'] == "Credit"){
    
                    $wallet->balance = (float) $wallet->balance + $input['total'];
        
                }else if($input['credit_debit'] == "Debit"){
                    $wallet->balance = (float) $wallet->balance - $input['total'];
                }
                
            }
            $wallet->save();
            if (isset($request->assign_to_entity_id) && $request->assign_to_entity_id != '' && isset($request->assign_to_entity) && $request->assign_to_entity != '') {
                $input['assign_to_entity_id'] = (int) $request->assign_to_entity_id;
                $input['assign_to_entity'] = (string) $request->assign_to_entity;
                // $request->assign_to_entity_id =  $request->assign_to_entity_id;
                // $request->assign_to_entity = $request->assign_to_entity;
            } else {
                $input['assign_to_entity_id'] = null;
                $input['assign_to_entity'] = null;
            }
    
        $input['updated_by'] = Auth::user()->name;
        $input['attachment'] = json_encode($attachments);
        $id = null;
        if($request->id != null && $request->id != '' && $request->id != 0){
            $id = $request->id;
        }
        $Document = pattyCash::updateOrCreate(['id' => $id],$input);

        // update transaction onboth side on sender side and receiver side both side pending
        //if type wallet find the assign to entiyty id and update the transaction on both side


        // if (isset($request->assign_to_entity_id) && $request->assign_to_entity_id != '' && isset($request->assign_to_entity) && $request->assign_to_entity != '') {
        
        //     if($request->assign_to_entity == 'wallet'){
        //         $wallet = Wallet::where('id', $request->assign_to_entity_id)->first();
        //         $Transaction = pattyCash::where('id', $request->id)->first();
        //     }
        // }
        $Document->attachment = json_decode($Document->attachment); 
    
        $user = JWTAuth::user();
        $action = 'Updated';
        $itemName = $Document->wallet_id.' WalletId';
        $itemUnique = $Document->id;
        $tableName = 'patty_cashes';
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);
    
        
        return response()->json(['message'=>'Transaction Updated','success' => true,'data' => json_decode(json_encode($Document))]);
    
    }



    public function deleteTransaction(Request  $request){ 

        $Transaction = pattyCash::where('id', $request->id)->first();
        $wallet = Wallet::where('id', $Transaction->wallet_id)->first();


        if($Transaction->approve == 1 || $Transaction->approve == '1'){

            if($Transaction->credit_debit == "Credit"){

                $wallet->balance = $wallet->balance - $Transaction->total;
    
            }else if($Transaction->credit_debit == "Debit"){
                $wallet->balance = $wallet->balance + $Transaction->total;
    
    
            }
        }



        $Transaction->delete();
        $wallet->save();

        $user = JWTAuth::user();
        $action = 'Deleted';
        $itemName = $Transaction->transaction_type.' Transaction';
        $itemUnique = $Transaction->id;
        $tableName = 'patty_cashes';
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);
    
        return response()->json(['message'=>'Transaction Deleted Successfully','success' => true]);
    
    
    
    }

    public function updateTransactionStatus(Request  $request){ 
        $approve = null;
        if($request->approve === true){

            $approve = 1;
        }else{

            $approve = 0;
        }
        $Transaction = pattyCash::where('id', $request->id)->first();
        if($Transaction->accept == '1' || $Transaction->accept == 1){
            $Transaction->approve = $approve;
            $wallet = Wallet::where('id', $Transaction->wallet_id)->first();
            if($approve == 1){
                $Transaction->approved_by = Auth::user()->name;

                if($Transaction->credit_debit == "Credit"){

                    $wallet->balance = $wallet->balance + $Transaction->total;
    
                }else if($Transaction->credit_debit == "Debit"){
                    $wallet->balance = $wallet->balance - $Transaction->total;
                }
            }else if($approve == 0){
                $Transaction->approved_by = null;

                if($Transaction->credit_debit == "Credit"){

                    $wallet->balance = $wallet->balance - $Transaction->total;
    
                }else if($Transaction->credit_debit == "Debit"){
                    $wallet->balance = $wallet->balance + $Transaction->total;
    
    
                }

            }
            $Transaction->save();
            $wallet->save();

        }else{
            return response()->json(['message'=>'Transaction Not Approved','success' => false]);

        }

        $user = JWTAuth::user();
        $action = 'Updated';
        $itemName = $Transaction->transaction_type.' Transaction';
        $itemUnique = $Transaction->id;
        $tableName = 'patty_cashes,wallets';
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);
    
        return response()->json(['message'=>'Transaction Updated Successfully','success' => true]);
    
    
    
    }   


    public function updateTransactionApproveStatus(Request  $request){ 
        $accept = null;

        if($request->accept === true){

            $accept = 1;
        }else{

            $accept = 0;
        }


        $Transaction = pattyCash::where('id', $request->id)->first();
        $Transaction->accept = $accept;




            $Transaction->save();


        $user = JWTAuth::user();
        $action = 'Updated';
        $itemName = $Transaction->transaction_type.' Transaction';
        $itemUnique = $Transaction->id;
        $tableName = 'patty_cashes,wallets';
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);
    
        return response()->json(['message'=>'Transaction Updated Successfully','success' => true]);
    
    
    
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

        $image->storeAs('pattyCash', $file_namefor_db, 'public');
        $pic = ('storage/pattyCash/' . $file_namefor_db);

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





    function IsValidateTransaction($item)
    {
        $item = json_decode(json_encode($item), true);



        $messages = [
            'transaction_type.required'  => 'Transaction Type must be required.',
            'date.required'  => 'Date must be required.',
            'approve.required'  => 'Approve must be required.',
            'unit_price.required'  => 'Unit Price must be required.',
            'quantity.required'  => 'Quantity must be required.',
            'total.required'  => 'Total must be required.',

        ];

        $validate = Validator::make($item, [
            'transaction_type' => 'required|string|max:255',
            'date' => 'required|string|max:255',
            'approve' => 'required|boolean',

            'unit_price' => 'required|numeric',
            'quantity' => 'required|numeric',
            'total' => 'required|numeric',

            // 'file.*' => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar',

        ], $messages);

        if ($validate->fails()) {

            return ['message' => $validate->errors()->first(), 'success' => false];
        } else {
            return ['message' => '', 'success' => true];
        }
    }
}
