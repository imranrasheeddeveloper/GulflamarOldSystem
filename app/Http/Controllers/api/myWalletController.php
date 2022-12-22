<?php

namespace App\Http\Controllers\api;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\pattyCash;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use JWTAuth;
use Validator;
use App\Http\Resources\walletDataResource;

use App\Exports\walletExport;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class myWalletController extends Controller
{
    public function my_walletCsv(Request $request){
        
        $user_id = $request->user_id;

        return Excel::download(new walletExport($request->searchTerm,(int)$request->limit,(int)$user_id), 'walletsExport.xlsx');

    }
    public function addWallet(Request $request){


        $messages = [
            'user_id.required'  => 'User must be required.',
            'account_name.required'  => 'Account Name must be required.',
            'date.required'  => 'Date must be required.',
            'balance.required'  => 'Balance must be required.',



        ];
    
        $validate = Validator::make($request->all(), [ 
            'user_id' => 'required|numeric',
            'account_name' => 'required|string|max:255',
            'balance' => 'required|numeric',
            // 'file.*' => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar',
            'date' => 'required|string|max:255',


        ],$messages);

        if ($validate->fails()) { 
                  
                return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);            
            }

        $input = $request->all();

        $input['created_by'] = Auth::user()->name;
        $input['updated_by'] = null;


        $wallet = Wallet::create($input);


        $user = JWTAuth::user();
        $action = 'CREATED';
        $itemName = $wallet->user_id.' Wallet';
        $itemUnique = $wallet->id;
        $tableName = 'wallets';
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);
        
        return response()->json(['message'=>'Wallet Created','success' => true,'data' => json_decode(json_encode($wallet))]);

    }


    public function getAllWallets(Request  $request){ 
        $advanceSearch = json_decode($request->advanceSearch);
        $query = Wallet::query();

        $user = JWTAuth::user();
        $query->where('user_id',$user->id);

        $documentRecords = null;

        if($request->searchTerm != '' && $request->searchTerm != null){
            // $query->orWhere('id', 'LIKE', "%".$request->searchTerm."%");
            // $query->orWhere('user_id', 'LIKE', "%".$request->searchTerm."%");
            // $query->orWhere('account_name', 'LIKE', "%".$request->searchTerm."%");
            $query->where(DB::raw("CONCAT(`id`, ' ',`account_name`,' ')"), 'LIKE', "%" . $request->searchTerm . "%");

        }


        if($advanceSearch->balance != '' && $advanceSearch->balance != null){
            $query->where('balance',(float)$advanceSearch->balance);

        }
        // if($advanceSearch->user_id != '' && $advanceSearch->user_id != null){
        //     $query->orWhere('user_id',$advanceSearch->user_id);

        // }
        if($advanceSearch->account_name != '' && $advanceSearch->account_name != null){
            $query->where('account_name', 'LIKE', "%".$advanceSearch->account_name."%");

        }
        if($advanceSearch->date != '' && $advanceSearch->date != null){
            $query->where('date', 'LIKE', "%".$advanceSearch->date."%");

        }
        $documentRecords=clone $query;
        
        $totalRows = $documentRecords->count();
        $documentRecords = $query->latest()->with('user')->get();
        foreach($documentRecords as $item){
            $item['user_name'] = $item->user->name;
        }

    $resourceRecords = $this->paginaterhelp($request->page_no,$documentRecords,$request);


    return walletDataResource::collection($resourceRecords)->additional(['success' => true,'base_url' => url('/'),'user_id' => $user->id,'success' => true,'totalRows' => $totalRows,'message'=>'Wallets fetched.']);

}




public function getWallet(Request  $request){ 

    $wallet = Wallet::where('id',$request->id)->with('user')->first();


    return response()->json(['message'=>'Wallet Fetched','success' => true,'data' => json_decode(json_encode($wallet))]);


}



public function updateWallet(Request $request){


    $messages = [
        'id.required'  => 'Id must be required.',
        'user_id.required'  => 'User must be required.',
        'account_name.required'  => 'Account Name must be required.',
        'date.required'  => 'Date must be required.',
        'balance.required'  => 'Balance must be required.',



    ];

    $validate = Validator::make($request->all(), [
        'id' => 'required|numeric',
        'user_id' => 'required|numeric',
        'account_name' => 'required|string|max:255',
        'balance' => 'required|numeric',
        // 'file.*' => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar',
        'date' => 'required|string|max:255',


    ],$messages);

    if ($validate->fails()) { 
              
            return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);            
        }

    $input = $request->all();

    // $input['created_by'] = Auth::user()->name;
    $input['updated_by'] = Auth::user()->name;


        Wallet::updateOrCreate([
        'id' => $request->id
        ],$input);


        $wallet = Wallet::where('id',$request->id)->with('user')->first();


    

    $user = JWTAuth::user();
    $action = 'updated';
    $itemName = $wallet->user_id.' Wallet';
    $itemUnique = $wallet->id;
    $tableName = 'wallets';
    recordActivity($user,$action,$itemName,$itemUnique,$tableName);
    
    return response()->json(['message'=>'Wallet Updated','success' => true,'data' => json_decode(json_encode($wallet))]);

}



public function deleteWallet(Request  $request){ 

    $wallet = Wallet::where('id', $request->id)->first();

    $Transaction = pattyCash::where('wallet_id', $request->id)->get();

    $length = $Transaction->count();

    if($length > 0){
        return response()->json(['message'=>'Wallet cannot be deleted, Wallet has Transactions','success' => false]);
    }
    $wallet->delete();

    $user = JWTAuth::user();
    $action = 'Deleted';
    $itemName = $wallet->account_name.' Wallet';
    $itemUnique = $wallet->id;
    $tableName = 'wallets';
    recordActivity($user,$action,$itemName,$itemUnique,$tableName);

    return response()->json(['message'=>'Wallet Deleted Successfully','success' => true]);



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
    $file_namefor_db =$file_name . '_' . $rand_namer . '.' . $file_extension;
    $file_namefor_db = preg_replace('/\s+/', '_', trim($file_namefor_db));

    $image-> storeAs('documents' ,$file_namefor_db,'public');
    $pic = ('storage/documents/' . $file_namefor_db);

    return $pic;
}


function paginaterhelp($page,$items,$request)

{

    
            $page = $page; 

            // Number of items per page
            $perPage = 10;

            // Start displaying items from this number;
            $offSet = ($page * $perPage) - $perPage; // Start displaying items from this number

            // Get only the items you need using array_slice 
           
          if(is_array($items))
           {
               $itemsForCurrentPage = array_slice($items, $offSet, $perPage, false);
           }
           else{
                  $itemsForCurrentPage = $items->slice($offSet, $perPage);
                /*$itemsForCurrentPage = array_slice($items->toArray(), $offSet, $perPage, false);*/
           }
            
            // Return the paginator with only 10 items but with the count of all items and set the it on the correct page
            $result = new LengthAwarePaginator($itemsForCurrentPage, count($items), $perPage, $page, ['path' => $request->url(), 'query' => $request->query()]);

          //  $result = json_decode(json_encode($result,true),true);
            
            return $result->items();

}




}
