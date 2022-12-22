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

use Auth;
use DB;

class ownerBanksController extends Controller
{
    public function addOwnerBank(Request $request)
    {


        $messages = [
            'account_name.required'  => 'Account Name must be required.',
            'bank_name.required'  => 'Bank Name must be required.',
            'iban.required'  => 'Iban must be required.',
        ];

        $validate = Validator::make($request->all(), [
            'account_name' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            // 'file.*' => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar',
            'iban' => 'required',


        ], $messages);

        if ($validate->fails()) {

            return response()->json(['message' => $validate->errors()->first(), 'success' => false, 'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);
        }

        $input = $request->all();

        $input['created_by'] = Auth::user()->name;
        $input['updated_by'] = null;


        $bank_account = ownerBank::create($input);


        $user = JWTAuth::user();
        $action = 'CREATED';
        $itemName = $bank_account->account_name . ' owner bank';
        $itemUnique = $bank_account->id;
        $tableName = 'owner_banks';
        recordActivity($user, $action, $itemName, $itemUnique, $tableName);

        return response()->json(['message' => 'Owner Bank Created', 'success' => true, 'data' => json_decode(json_encode($bank_account))]);
    }


    public function getOwnerBank(Request  $request)
    {

        $wallet = ownerBank::where('id', $request->id)->first();


        return response()->json(['message' => 'Owner Bank Fetched', 'success' => true, 'data' => json_decode(json_encode($wallet))]);
    }


    public function updateOwnerBank(Request $request)
    {


        $messages = [
            'id.required'  => 'Id must be required.',
            'account_name.required'  => 'Account Name must be required.',
            'bank_name.required'  => 'Bank Name must be required.',
            'iban.required'  => 'Iban must be required.',

        ];

        $validate = Validator::make($request->all(), [
            'id' => 'required|numeric',
            'account_name' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            // 'file.*' => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar',
            'iban' => 'required',



        ], $messages);

        if ($validate->fails()) {

            return response()->json(['message' => $validate->errors()->first(), 'success' => false, 'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);
        }

        $input = $request->all();

        // $input['created_by'] = Auth::user()->name;
        $input['updated_by'] = Auth::user()->name;


        ownerBank::updateOrCreate([
            'id' => $request->id
        ], $input);


        $bank_account = ownerBank::where('id', $request->id)->first();




        $user = JWTAuth::user();
        $action = 'updated';
        $itemName = $bank_account->user_id . ' owner bank';
        $itemUnique = $bank_account->id;
        $tableName = 'owner_banks';
        recordActivity($user, $action, $itemName, $itemUnique, $tableName);

        return response()->json(['message' => 'Owner Bank Updated', 'success' => true, 'data' => json_decode(json_encode($bank_account))]);
    }


    public function deleteOwnerBank(Request  $request)
    {

        $bank_account = ownerBank::where('id', $request->id)->first();

        $bank_account->delete();

        $user = JWTAuth::user();
        $action = 'Deleted';
        $itemName = $bank_account->account_name . ' owner bank';
        $itemUnique = $bank_account->id;
        $tableName = 'owner_banks';
        recordActivity($user, $action, $itemName, $itemUnique, $tableName);

        return response()->json(['message' => 'Owner Bank Deleted Successfully', 'success' => true]);
    }

    public function getAllOwnerBanks(Request  $request)
    {
        $advanceSearch = json_decode($request->advanceSearch);
        $query = ownerBank::query();

        $documentRecords = null;

        if ($request->searchTerm != '' && $request->searchTerm != null) {
            $query->orWhere('id', 'LIKE', "%" . $request->searchTerm . "%");
            $query->orWhere('iban', 'LIKE', "%" . $request->searchTerm . "%");
            $query->orWhere('account_name', 'LIKE', "%" . $request->searchTerm . "%");
            $query->orWhere('bank_name', 'LIKE', "%" . $request->searchTerm . "%");
        }




        // if ($advanceSearch->account_name != '' && $advanceSearch->account_name != null) {
        //     $query->orWhere('account_name', 'LIKE', "%" . $advanceSearch->account_name . "%");
        // }
        // if ($advanceSearch->bank_name != '' && $advanceSearch->bank_name != null) {
        //     $query->orWhere('bank_name', 'LIKE', "%" . $advanceSearch->bank_name . "%");
        // }
        // if ($advanceSearch->iban != '' && $advanceSearch->iban != null) {
        //     $query->orWhere('iban', 'LIKE', "%" . $advanceSearch->iban . "%");
        // }

        $documentRecords = clone $query;

        $totalRows = $documentRecords->count();
        $documentRecords = $query->latest()->get();


        $resourceRecords = $this->paginaterhelp($request->page_no, $documentRecords, $request);


        return walletDataResource::collection($resourceRecords)->additional(['success' => true, 'base_url' => url('/'), 'success' => true, 'totalRows' => $totalRows, 'message' => 'Owner Banks fetched.']);
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
