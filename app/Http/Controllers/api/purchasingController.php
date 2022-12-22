<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\accommodationResource;
use App\Models\projectResource;
use App\Models\employeeResource;


use App\Http\Resources\purchaseResource;

use Illuminate\Pagination\LengthAwarePaginator;

use App\Models\employeeResource AS employeeItemResource;

use App\Models\purchasing;
use App\Models\purchased_item;

use App\Exports\purchaseExport;


use Maatwebsite\Excel\Facades\Excel;


use DB;
use JWTAuth;
use Illuminate\Support\Facades\Auth;
use Validator;

class purchasingController extends Controller
{

    public function purchaseCsv(Request $request){

        return Excel::download(new purchaseExport($request->searchTerm,(int)$request->limit), 'purchaseOrders.xlsx');
    }

    public function getAllResources(Request $request){

        $projectResourceItems = projectResource::select(DB::raw('name AS value'),'name as text');
        $accommodationResourceItems = accommodationResource::select(DB::raw('name AS value'),'name as text');
        $employeeResourceItems = employeeItemResource::select(DB::raw('name AS value'),'name as text');

        $resourceItems = $projectResourceItems->unionAll($accommodationResourceItems)
                        ->unionAll($employeeResourceItems)
                        ->get();
        return response()->json(['message'=>'Resource Items fetched','success' => true,'data' => json_decode(json_encode($resourceItems))]);
    }

    public function getAllResourcesForExpenss(Request $request){

        $projectResourceItems = projectResource::select(DB::raw('name AS value'),'name as text');
        $accommodationResourceItems = accommodationResource::select(DB::raw('name AS value'),'name as text');
        $employeeResourceItems = employeeItemResource::select(DB::raw('name AS value'),'name as text');

        $resourceItems = $projectResourceItems->unionAll($accommodationResourceItems)
                        ->unionAll($employeeResourceItems)
                        ->get();
        return response()->json(['message'=>'Resource Items fetched','success' => true,'data' => json_decode(json_encode($resourceItems))]);
    }

    public function createPurchase(Request $request){

            $messages = [
                       
                        'purchaseType.required'  => 'Specify purchase type.',
                        'date.required'  => 'Specify purchasing date.',
                        'vat_not_vat.required'  => 'Specify purchasing vat_not_vat.',


                    ];

        $validate = Validator::make($request->all(), [

            'purchaseType'   => 'required',
            'date'   => 'required',
            'vat_not_vat'   => 'required',


            ],$messages);


        if ($validate->fails()) {

                    return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => []], 200);            
                }

        if ($request->purchaseType == 'Service') {
            $service_name_index = 'serviceName';
        }
        else
        {
            $service_name_index = 'name';
        }
        foreach ($request->resourceItems as $key => $item) {

            if ($item[$service_name_index] == '') {
               
               return response()->json(['message'=>'Item name cannot be empty','success' => false,'data' => []], 200);   
            }

        }
        $input = $request->all();
        $input['grandTotal'] = '0';
        $input['created_by'] = Auth::user()->name;
        $input['updated_by'] = null;


        $purchasing = purchasing::create($input);

        $tmpGrandTotal = 0;
        //dd( $request->resourceItems);
        foreach ($request->resourceItems as $key => $item) {
                    
                    $canSave = true;
                    
                    $tmpGrandTotal += $item['total'];
                    $purchaseItem = new purchased_item;

                    $purchaseItem->purchasingId = $purchasing->id;
                    if ($request->purchaseType == 'Product') {
                        
                        $productItem = projectResource::where('name',$item['name'])->first();
                        if($productItem)
                        {
                            $purchaseItem->itemType = 'project';
                            $purchaseItem->itemId = $productItem->id;
                            
                        }
                        else
                        {
                            $productItem = accommodationResource::where('name',$item['name'])->first();
                            if($productItem)
                            {
                                $purchaseItem->itemType = 'accommodation';
                                $purchaseItem->itemId = $productItem->id;
                            }
                            else
                            {
                                $productItem = employeeItemResource::where('name',$item['name'])->first();
                                if($productItem)
                                {
                                    $purchaseItem->itemType = 'employee';
                                    $purchaseItem->itemId = $productItem->id;
                                }
                                else
                                {
                                    $canSave = false;
                                }
                            }
                        }
                        
                        
        
                         $purchaseItem->name = $item['name'];
                    }
                    else
                    {
                        $purchaseItem->name = $item['serviceName'];
                    }
                   
                    $purchaseItem->quantity = $item['quantity'];
                    $purchaseItem->rate = $item['rate'];
                    $purchaseItem->total = $item['total'];
                    
                    if($canSave)
                    {
                        $purchaseItem->save();
                        $canSave = true;
                    }
                }
        $purchasing->grandTotal = $tmpGrandTotal;
        $purchasing->save();

        $user = JWTAuth::user();
        $action = 'CREATED';
        $itemName = $purchasing->purchaseType.' Purchase';
        $itemUnique = $purchasing->id;
        $tableName = 'Purchasings';
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);

        return response()->json(['message'=>'Purchase Created','success' => true,'data' => []], 201);

    }

    public function getPurchaseList(Request $request){

        $advanceSearch = json_decode($request->advanceSearch);
        
        $query = purchasing::query();

        if ($request->searchTerm != '' && $request->searchTerm != null && $request->searchTerm) {
            $query->where(DB::raw("CONCAT(`id`, ' ', `purchaseType`, ' ', `grandTotal`, ' ', `date`)"), 'LIKE', "%" . $request->searchTerm . "%")->latest()->get();
        }

        if($advanceSearch->purchaseType != '' && $advanceSearch->purchaseType != null && $advanceSearch->purchaseType){
            $query->where('purchaseType', 'LIKE', "%" . $advanceSearch->purchaseType . "%");

        }
        if($advanceSearch->amount != '' && $advanceSearch->amount != null){
            $query->where('net_total', '=', $advanceSearch->amount);

        }
        if($advanceSearch->fromDate != '' && $advanceSearch->fromDate != null && $advanceSearch->toDate != '' && $advanceSearch->toDate != null){

            $query->whereBetween('date', [$advanceSearch->fromDate, $advanceSearch->toDate]);

        }

        if($advanceSearch->seller_type != '' && $advanceSearch->seller_type != null){
            $query->where('seller_type', '=', $advanceSearch->seller_type);

        }
        if($advanceSearch->seller_name != '' && $advanceSearch->seller_name != null){
            $query->where('request_seller', 'LIKE',  "%" .$advanceSearch->seller_name. "%");

        }

        if($advanceSearch->vat_not_vat != '' && $advanceSearch->vat_not_vat != null && $advanceSearch->vat_not_vat){
            $query->where('vat_not_vat', '=', $advanceSearch->vat_not_vat);

        }


        $totalRecordswithFilter=clone $query;
        $totalRows = $totalRecordswithFilter->count();
        $resourceRecords = $query->orderBy('date','DESC')->get();
        // ->latest()

        $resourceRecords = $this->paginaterhelp($request->page_no,$resourceRecords,$request);

        return purchaseResource::collection($resourceRecords)->additional(['success' => true,'totalRows' => $totalRows,'message'=>'Purchase Records fetched.']);


    }

    public function deletePurchase(Request $request){

        $purchasing = purchasing::find($request->id);

        if(!$purchasing){

            return response()->json(['message'=>'Invalid Entry','success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);  
        }
        
        foreach ($purchasing->purchasedItems as $key => $item) {

            $item->delete();
        }

        $purchasing->delete();

        $user = JWTAuth::user();
        $action = 'DELETED';
        $itemName = $purchasing->purchaseType.' Purchase';
        $itemUnique = $purchasing->id;
        $tableName = 'Purchasings';
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);

        return response()->json(['message'=>'Deleted Successfully','success' => true,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);

    }

    public function getPurchaseDetail(Request $request){
        
        $purchasing = purchasing::find($request->id);

        if(!$purchasing){

            return response()->json(['message'=>'Invalid Entry','success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);  
        }

        $statement = DB::select("SHOW TABLE STATUS LIKE 'purchasings'");
        $nextId = $statement[0]->Auto_increment;

        return (new purchaseResource($purchasing))->additional(['nextId' => $nextId,'success' => true,'message'=>'Purchasing Details fetched.']);

    }

    public function updatePurchase(Request $request){

        $purchasing = purchasing::find($request->id);

        if(!$purchasing){

            return response()->json(['message'=>'Invalid Entry','success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);  
        }

        $messages = [
                       
                        'purchaseType.required'  => 'Specify purchase type.',
                        'date.required'  => 'Specify purchasing date.',

                    ];

        $validate = Validator::make($request->all(), [

            'purchaseType'   => 'required',
            'date'   => 'required',

            ],$messages);


        if ($validate->fails()) {

                    return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => []], 200);            
                }

        $purchasing['updated_by'] = Auth::user()->name;
        $purchasing->update($request->all());

        foreach ($purchasing->purchasedItems as $key => $item) {

            $item->delete();
        }

        $tmpGrandTotal = 0;
        //dd( $request->resourceItems);
        foreach ($request->resourceItems as $key => $item) {
                    
                    $canSave = true;
                    
                    $tmpGrandTotal += $item['total'];
                    $purchaseItem = new purchased_item;

                    $purchaseItem->purchasingId = $purchasing->id;
                    if ($request->purchaseType == 'Product') {
                        
                        $productItem = projectResource::where('name',$item['name'])->first();
                        if($productItem)
                        {
                            $purchaseItem->itemType = 'project';
                            $purchaseItem->itemId = $productItem->id;
                        }
                        else
                        {
                            $productItem = accommodationResource::where('name',$item['name'])->first();
                            if($productItem)
                            {
                                $purchaseItem->itemType = 'accommodation';
                                $purchaseItem->itemId = $productItem->id;
                            }
                            else
                            {
                                $productItem = employeeItemResource::where('name',$item['name'])->first();
                                if($productItem)
                                {
                                    $purchaseItem->itemType = 'employee';
                                    $purchaseItem->itemId = $productItem->id;
                                }
                                else
                                {
                                    $canSave = false;
                                }
                            }
                        }
                         $purchaseItem->name = $item['name'];
                    }
                    else
                    {
                        $purchaseItem->name = $item['serviceName'];
                    }
                   
                    $purchaseItem->quantity = $item['quantity'];
                    $purchaseItem->rate = $item['rate'];
                    $purchaseItem->total = $item['total'];
                    
                    if($canSave)
                    {
                        $purchaseItem->save();
                        $canSave = true;
                    }
                }
        $purchasing->grandTotal = $tmpGrandTotal;
        $purchasing->save();

        $user = JWTAuth::user();
        $action = 'UPDATED';
        $itemName = $purchasing->purchaseType.' Purchase';
        $itemUnique = $purchasing->id;
        $tableName = 'Purchasings';
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);

        return response()->json(['message'=>'Purchase Updated','success' => true,'data' => []], 201);

    }

    public function imgUpload(Request  $request){

        $messages = [
                       
                        'image.file'  => 'upload valid file.',

                    ];

        $validate = Validator::make($request->all(), [

            'image'   => 'required|file',

            ],$messages);


        if ($validate->fails()) {

                    return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => []], 200);            
                }

        /*$validate = Validator::make($request->all(), [

            'image'   => 'image',

            ],$messages);


        if (!$validate->fails()) {

                    return response()->json(['message'=>'Upload valid PDF','success' => false,'data' => []], 401);            
                }*/


        $image = $request->file('image');
        $file_fullname = $image->getClientOriginalName();
        $file_name = pathinfo($file_fullname, PATHINFO_FILENAME);
        $file_extension = $image->getClientOriginalExtension();
        $rand_namer = now();
        $rand_namer = preg_replace('/\s+/', '_', trim($rand_namer));
        $rand_namer = preg_replace('/:+/', '_', trim($rand_namer));
        $file_namefor_db =$file_name . '_' . $rand_namer . '.' . $file_extension;
        $file_namefor_db = preg_replace('/\s+/', '_', trim($file_namefor_db));

        $image-> storeAs('image' ,$file_namefor_db,'public');
        $pic = ('storage/image/' . $file_namefor_db);

        return response()->json(['message'=>'File uploaded','success' => true,'data' => ['image'=>$pic]], 201);


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
