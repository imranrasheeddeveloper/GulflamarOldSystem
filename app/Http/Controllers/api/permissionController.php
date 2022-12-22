<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Pagination\LengthAwarePaginator;

use App\Models\User;
use App\Models\userLevel;
use App\Models\userLevelPermission;

use App\Http\Resources\userRoleResource;
use App\Http\Resources\updateUserRoleResource;

use Validator;
use JWTAuth;
use Auth;
use DB;

class permissionController extends Controller
{

    public function getRoles(Request $request)
    {
        if ($request->searchTerm != '' && $request->searchTerm) {
            $resourceRecords = userLevel::where(DB::raw("CONCAT(`id`, ' ', `name`)"), 'LIKE', "%" . $request->searchTerm . "%")->orderBy('id','desc')->get();
        }
        else
        {
            $resourceRecords = userLevel::orderBy('id','desc')->get();
        }
        

        $totalRows = count($resourceRecords);

        $resourceRecords = $this->paginaterhelp($request->page_no,$resourceRecords,$request);

        return userRoleResource::collection($resourceRecords)->additional(['success' => true,'totalRows' => $totalRows,'message'=>'Roles fetched.']);
    }

    function createRole(Request $request)

    {
        $messages = [
                       
                        'name.required'  => 'Specify User role name.',

                    ];

        $validate = Validator::make($request->all(), [
            'name'   => 'required',

            ],$messages);


        if ($validate->fails()) {

                    return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => []], 200);            
                }

        //dd($request->all());

        $parentUserLevel = new userLevel();
        $parentUserLevel->name = $request->name;

        $parentUserLevel->save();
        $userLevel = new userLevelPermission();
        $userLevel->userLevelId = $parentUserLevel->id;
        $userLevel->employees = implode(',',$request->employees);
        $userLevel->resource = implode(',',$request->resource);
        $userLevel->resource_item = implode(',',$request->resource_item);
        $userLevel->accommodation = implode(',',$request->accommodation);
        $userLevel->rent_payment = implode(',',$request->rent_payment);
        $userLevel->bill_payment = implode(',',$request->bill_payment);
        $userLevel->client = implode(',',$request->client);
        $userLevel->invoice = implode(',',$request->invoice);
        $userLevel->purchase_order = implode(',',$request->purchase_order);
        $userLevel->vendor = implode(',',$request->vendor);

        $userLevel->document = implode(',',$request->document);
        $userLevel->payment = implode(',',$request->payment);
        $userLevel->supplier = implode(',',$request->supplier);
        $userLevel->supplier_type = implode(',',$request->supplier_type);



        $userLevel->wallets = implode(',',$request->wallets);
        $userLevel->my_wallets = implode(',',$request->my_wallets);

        $userLevel->petty_cash = implode(',',$request->petty_cash);
        $userLevel->petty_cash_approve = implode(',',$request->petty_cash_approve);
        $userLevel->bank_account = implode(',',$request->bank_account);
        $userLevel->my_bank_account = implode(',',$request->my_bank_account);
        $userLevel->expense_accounts = implode(',',$request->expense_accounts);
        $userLevel->call_center = implode(',',$request->call_center);








        $userLevel->user_level = implode(',',$request->user_level);
        $userLevel->user = implode(',',$request->user);
        $userLevel->dashboard = $request->dashboard;
        $userLevel->save();

        $user = JWTAuth::user();
        $action = 'CREATED';
        $itemName = $parentUserLevel->name;
        $itemUnique = $parentUserLevel->id;
        $tableName = 'User Levels';
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);

        return response()->json(['message'=>'New Role Created','success' => true,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);
        
    }

    public function getRoleDetail(Request $request){

        $userLevel = userLevel::find($request->id);

        if(!$userLevel){

            return response()->json(['message'=>'Invalid ID','success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);  
        }
        return (new updateUserRoleResource($userLevel))->additional(['success' => true,'message'=>'Role Detail fetched.']);
    }

    public function updateRoleDetail(Request $request){

        $messages = [
                       
                        'name.required'  => 'Specify User role name.',

                    ];

        $validate = Validator::make($request->all(), [
            'name'   => 'required',

            ],$messages);


        if ($validate->fails()) {

                    return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => []], 200);            
                }

        //dd($request->all());
        $parentUserLevel = userLevel::find($request->id);

        if(!$parentUserLevel){

            return response()->json(['message'=>'Invalid ID','success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);  
        }
        $parentUserLevel->name = $request->name;

        $parentUserLevel->save();

        $userLevel = $parentUserLevel->permissions;
        $userLevel->employees = implode(',',$request->employees);
        $userLevel->resource = implode(',',$request->resource);
        $userLevel->resource_item = implode(',',$request->resource_item);
        $userLevel->accommodation = implode(',',$request->accommodation);
        $userLevel->rent_payment = implode(',',$request->rent_payment);
        $userLevel->bill_payment = implode(',',$request->bill_payment);
        $userLevel->client = implode(',',$request->client);
        $userLevel->invoice = implode(',',$request->invoice);
        $userLevel->purchase_order = implode(',',$request->purchase_order);
        $userLevel->vendor = implode(',',$request->vendor);

        $userLevel->document = implode(',',$request->document);
        $userLevel->payment = implode(',',$request->payment);
        $userLevel->supplier = implode(',',$request->supplier);
        $userLevel->supplier_type = implode(',',$request->supplier_type);

        $userLevel->wallets = implode(',',$request->wallets);
        $userLevel->my_wallets = implode(',',$request->my_wallets);
        $userLevel->petty_cash = implode(',',$request->petty_cash);
        $userLevel->petty_cash_approve = implode(',',$request->petty_cash_approve);
        $userLevel->bank_account = implode(',',$request->bank_account);
        $userLevel->my_bank_account = implode(',',$request->my_bank_account);
        $userLevel->expense_accounts = implode(',',$request->expense_accounts);
        $userLevel->call_center = implode(',',$request->call_center);
        $userLevel->assets_managments = implode(',',$request->assets_managments);
        $userLevel->asset_maintenances = implode(',',$request->asset_maintenances);
        $userLevel->asset_work_orders = implode(',',$request->asset_work_orders);



        $userLevel->user_level = implode(',',$request->user_level);
        $userLevel->user = implode(',',$request->user);
        $userLevel->dashboard = $request->dashboard;
        $userLevel->save();

        $user = JWTAuth::user();
        $action = 'UPDATED';
        $itemName = $parentUserLevel->name;
        $itemUnique = $parentUserLevel->id;
        $tableName = 'User Levels';
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);

        return response()->json(['message'=>'Role Updated','success' => true,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);

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
