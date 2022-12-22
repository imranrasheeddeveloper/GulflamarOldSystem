<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Pagination\LengthAwarePaginator;

use Tymon\JWTAuth\Exceptions\JWTException;

use App\Http\Resources\userSignupResource;
use App\Http\Resources\userResource;
use App\Http\Resources\userDataResource;
use App\Http\Resources\supplierDataResource;


use App\Models\User;
use App\Models\supplierType;
use App\Models\Supplier;
use App\Models\SupplierItem;

use Illuminate\Support\Facades\Auth;

use App\Models\userLevel;

use Validator;
use JWTAuth;
// use Auth;
use DB;

class supplierController extends Controller
{
    public function addSupplierType(Request $request){

        $messages = [
            'type.required'  => 'Supplier Type must be required.',
        ];
    
        $validate = Validator::make($request->all(), [ 
            'type' => 'required|string|max:255',
        ],$messages);

        if ($validate->fails()) { 
                  
                return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);            
            }


        $input = $request->all(); 
        $supplierType = supplierType::create($input);

        $user = JWTAuth::user();
        $action = 'CREATED';
        $itemName = $supplierType->type.' Supplier Type';
        $itemUnique = $supplierType->id;
        $tableName = 'supplier_types';
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);
        
        return response()->json(['message'=>'Supplier Type Created','success' => true,'data' => json_decode(json_encode($supplierType))]);

    }
    public function createSupplier(Request $request){


        $messages = [
            'name.required'  => 'Name must be required.',
            'name_ar.required'  => 'Name Arabic must be required.',
            'vat_number.required'  => 'VAT number must be required.',
            // 'cr_number.required'  => 'CR number must be required.',
            // 'contact_name.required'  => 'Contact name must be required.',
            // 'contact_number.required'  => 'Contact number must be required.',
            // 'city.required'  => 'City must be required.',
            // 'bank_name.required'  => 'Bank name must be required.',
            // 'account_name.required'  => 'Account name must be required.',
            // 'iban.required'  => 'IBAN must be required.',
        ];
    
        $validate = Validator::make($request->all(), [ 
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'vat_number' => 'required|string|max:255',
            // 'cr_number' => 'required|string|max:255',
            // 'contact_name' => 'required|string|max:255',
            // 'contact_number' => 'required|string|max:255',
            // 'city' => 'required|string|max:255',
            // 'bank_name' => 'required|string|max:255',
            // 'account_name' => 'required|string|max:255',
            // 'iban' => 'required|string|max:255',
        ],$messages);

        if ($validate->fails()) { 
                  
                return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);            
            }
 
        $input = $request->all();
        $input['created_by'] = Auth::user()->name;
        $supplier = Supplier::create($input);
        foreach ($request->supplier_types as $type) {
            if(isset($type) && $type!=''){
                SupplierItem::create([
                    'supplierId' => $supplier->id,
                    'typeId' => $type,
                ]);
                
            }
           
          }
        $supplier = Supplier::where('id', $supplier->id)->with('supplierItems')->first();

        $user = JWTAuth::user();
        $action = 'CREATED';
        $itemName = $supplier->name.' Supplier';
        $itemUnique = $supplier->id;
        $tableName = 'suppliers';
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);
        
        return response()->json(['message'=>'Supplier Created','success' => true,'data' => json_decode(json_encode($supplier))]);

    }
    public function updateSupplier(Request $request){


        $messages = [
            'name.required'  => 'Name must be required.',
            'name_ar.required'  => 'Name Arabic must be required.',
            'vat_number.required'  => 'VAT number must be required.',

            // 'cr_number.required'  => 'CR number must be required.',
            // 'contact_name.required'  => 'Contact name must be required.',
            // 'contact_number.required'  => 'Contact number must be required.',
            // 'city.required'  => 'City must be required.',
            // 'bank_name.required'  => 'Bank name must be required.',
            // 'account_name.required'  => 'Account name must be required.',
            // 'iban.required'  => 'IBAN must be required.',
        ];
    
        $validate = Validator::make($request->all(), [ 
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'vat_number' => 'required|string|max:255',

            // 'cr_number' => 'required|string|max:255',
            // 'contact_name' => 'required|string|max:255',
            // 'contact_number' => 'required|string|max:255',
            // 'city' => 'required|string|max:255',
            // 'bank_name' => 'required|string|max:255',
            // 'account_name' => 'required|string|max:255',
            // 'iban' => 'required|string|max:255',
        ],$messages);

        if ($validate->fails()) { 
                  
                return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);            
            }
 
        $input = $request->all();
        $input['updated_by'] = Auth::user()->name;
        $supplierItems = SupplierItem::where('supplierId', $request->id)->get();
        foreach ($supplierItems as $item) {
            $item->delete();
        }
        foreach ($request->supplier_types as $type) {
            if(isset($type) && $type!=''){
                SupplierItem::create([
                    'supplierId' => $request->id,
                    'typeId' => $type,
                ]);
                
            }
           
          }
            Supplier::updateOrCreate([
                    'id' => $request->id
                    ],$input);
        $supplier = Supplier::where('id', $request->id)->with('supplierItems')->first();

        $user = JWTAuth::user();
        $action = 'CREATED';
        $itemName = $supplier->name.' Supplier';
        $itemUnique = $supplier->id;
        $tableName = 'suppliers';
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);
        
        return response()->json(['message'=>'Supplier Updated','success' => true,'data' => json_decode(json_encode($supplier))]);

    }
    public function getAllSuppliers(Request  $request){ 
        $advanceSearch = json_decode($request->advanceSearch);
        $query = supplier::query();

        $supplierRecords = null;

        if($request->searchTerm != '' && $request->searchTerm != null){
            $query->orWhere('name', 'LIKE', "%".$request->searchTerm."%");
            $query->orWhere('name_ar', 'LIKE', "%".$request->searchTerm."%");
            $query->orWhere('vat_number', 'LIKE', "%".$request->searchTerm."%");
            $query->orWhere('cr_number', 'LIKE', "%".$request->searchTerm."%");
            $query->orWhere('contact_number', 'LIKE', "%".$request->searchTerm."%");
            $query->orWhere('city', 'LIKE', "%".$request->searchTerm."%");
            $query->orWhere('bank_name', 'LIKE', "%".$request->searchTerm."%");


        }

        if($advanceSearch->supplierType != '' && $advanceSearch->supplierType != null){
            $SupplierIdArray = SupplierItem::whERE('typeId', $advanceSearch->supplierType)->pluck('supplierId')->toArray();
            if(isset($SupplierIdArray)){
                $query->whereIn('id', $SupplierIdArray);
            }
        }

        if($advanceSearch->city != '' && $advanceSearch->city != null){
            $query->where('city', 'LIKE', "%".$advanceSearch->city."%");

        }
        if($advanceSearch->bank_name != '' && $advanceSearch->bank_name != null){
            $query->where('bank_name', 'LIKE', "%".$advanceSearch->bank_name."%");

        }
        if($advanceSearch->name != '' && $advanceSearch->name != null){
            $query->where('name', 'LIKE', "%".$advanceSearch->name."%");

        }
        if($advanceSearch->vat_number != '' && $advanceSearch->vat_number != null){
            $query->where('vat_number', 'LIKE', "%".$advanceSearch->vat_number."%");

        }
        if($advanceSearch->cr_number != '' && $advanceSearch->cr_number != null){
            $query->where('cr_number', 'LIKE', "%".$advanceSearch->cr_number."%");

        }
        if($advanceSearch->contact_number != '' && $advanceSearch->contact_number != null){
            $query->where('contact_number', 'LIKE', "%".$advanceSearch->contact_number."%");

        }
        if($advanceSearch->iban != '' && $advanceSearch->iban != null){
            $query->where('iban', 'LIKE', "%".$advanceSearch->iban."%");

        }
        $supplierRecords=clone $query;
        
        $totalRows = $supplierRecords->count();
        $supplierRecords = $query->with('supplierItems')->get();


    $resourceRecords = $this->paginaterhelp($request->page_no,$supplierRecords,$request);


    return supplierDataResource::collection($resourceRecords)->additional(['success' => true,'totalRows' => $totalRows,'message'=>'Suppliers fetched.']);

}
    public function getSupplier(Request  $request){ 

        $supplier = supplier::where('id', $request->id)->with('supplierItems')->first();

    return response()->json(['message'=>'Suppliers Item fetched','success' => true,'data' => json_decode(json_encode($supplier))]);

    }
    public function deleteSupplier(Request  $request){ 

        $supplier = supplier::where('id', $request->id)->first();
        $supplierItems = SupplierItem::where('supplierId', $request->id)->get();
        foreach ($supplierItems as $item) {
            $item->delete();
        }

        $user = JWTAuth::user();
        $action = 'Deleted';
        $itemName = $supplier->name.' Supplier Type';
        $itemUnique = $supplier->id;
        $tableName = 'suppliers && supplieritems';
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);


        $supplier->delete();
    return response()->json(['message'=>'Supplier Deleted Successfully','success' => true]);



    }
    public function getAllSupplierType(Request  $request){
        $supplierRecords = null;
        $totalRows = null;
        if($request->searchTerm != '' && $request->searchTerm != null){
            $supplierRecords = supplierType::where('type', 'LIKE', "%".$request->searchTerm."%")->get();
        } 
            if(!isset($supplierRecords)){
                $supplierRecords = supplierType::all();
            }
            $totalRows = count($supplierRecords);

        // return response()->json(['message'=>'Supplier_Types Item fetched','success' => true,'totalRows' => $totalRows,'data' => json_decode(json_encode($supplierRecords))]);

        $resourceRecords = $this->paginaterhelp($request->page_no,$supplierRecords,$request);

        return supplierDataResource::collection($resourceRecords)->additional(['success' => true,'totalRows' => $totalRows,'message'=>'Supplier Types fetched.']);

    }


    public function getSupplierType(Request  $request){ 

        $supplierType = supplierType::where('id', $request->id)->first();

        return response()->json(['message'=>'SupplierType Item fetched','success' => true,'data' => json_decode(json_encode($supplierType))]);

    }

    public function updateSupplierType(Request  $request){ 

        $supplierType = supplierType::where('id', $request->id)->first();
        $supplierType->type = $request->type;
        $supplierType->save();

        $user = JWTAuth::user();

        $action = 'Updated';
        $itemName = $supplierType->type.' Supplier Type';
        $itemUnique = $supplierType->id;
        $tableName = 'supplier_types';
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);

        return response()->json(['message'=>'SupplierType Updated successfully','success' => true,'data' => json_decode(json_encode($supplierType))]);

    }
    public function deleteSupplierType(Request  $request){ 

        $supplierType = supplierType::where('id', $request->id)->first();

        $user = JWTAuth::user();
        $action = 'Deleted';
        $itemName = $supplierType->type.' Supplier Type';
        $itemUnique = $supplierType->id;
        $tableName = 'supplier_types';
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);

        $supplierType->delete();

        return response()->json(['message'=>'SupplierType Deleted successfully','success' => true,'data' => json_decode(json_encode($supplierType))]);

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
