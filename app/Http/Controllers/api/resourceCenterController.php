<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\resourceCenterresource;
use App\Http\Resources\resourceCenterItemResource;

use Illuminate\Pagination\LengthAwarePaginator;

use App\Models\resource_allocation;
use App\Models\projectResource;
use App\Models\accommodationResource;
use App\Models\employeeResource AS employeeItemResource;
use App\Http\Resources\projectItemResource;
use App\Exports\resourceCenterExport;


use Maatwebsite\Excel\Facades\Excel;


use DB;
use Validator;
use JWTAuth;
use Auth;

class resourceCenterController extends Controller
{
    public function resourceCsv(Request $request){

        return Excel::download(new resourceCenterExport($request->searchTerm,(int)$request->limit), 'resourceCenter.xlsx');
    }

    public function allocateResource(Request $request){

            // dd($request->all());

                $messages = [
                        'resourceOwnerId.required'  => 'Specify Resource Owner.',
                        'resourceOwnerType.required'  => 'Specify Owner Type.',
                        'signature.required'  => 'Signature missing.',
                    ];
    
        $validate = Validator::make($request->all(), [ 
            'resourceOwnerId' => 'required',
            'resourceOwnerType' => 'required',
            // 'resource_owner_location' => 'required', 
            // 'device_token' => 'required', 
        ],$messages);

        if ($validate->fails()) { 
                  
                return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);            
            }

        $error = false;
        $request['created_by'] = Auth::user()->name;
        $request['updated_by'] = null;

        foreach ($request->resourceItems as $key => $item) {

            if (!$error) {

                if (empty(trim($item['name']))) {

                    $error = true;

                    $tmpErrorMsg = 'Resource item Name Cannot Be empty.';
                    // code...
                }

                if (empty(trim($item['quantity']))) {

                    $error = true;

                    $tmpErrorMsg = 'Item Quantity Cannot Be empty.';
                    // code...
                }
            }

            
        }

        if ($error) {

                    return response()->json(['message'=>$tmpErrorMsg,'success' => false,'data' => []], 200);            
                }



            $resourceType = $request->resourceOwnerType;

            if ($resourceType == 'Project') {

                $allocation = resource_allocation::create($request->all());

                foreach ($request->resourceItems as $key => $item) {

                    $allocation->projectItems()
                        ->attach($item['name'],['quantity'=>$item['quantity']]);
                }

            } else if($resourceType == 'Accommodation'){
               
               $allocation = resource_allocation::create($request->all());

                foreach ($request->resourceItems as $key => $item) {

                    $allocation->accommodationItems()
                        ->attach($item['name'],['quantity'=>$item['quantity']]);
                }

            }
            else
            {
                $allocation = resource_allocation::create($request->all());

                foreach ($request->resourceItems as $key => $item) {

                    $allocation->employeeItems()
                        ->attach($item['name'],['quantity'=>$item['quantity']]);
                }
            }


        $user = JWTAuth::user();
        $action = 'CREATED';
        $itemName = $allocation->resourceOwnerType.' Resource';
        $itemUnique = $allocation->id;
        $tableName = 'Resources';
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);

        

        
        return response()->json(['message'=>'Resource allocated','success' => true,'data' => json_decode(json_encode([]))]);

    }

    public function getAllocatedResources(Request $request){
//dd(resource_allocation::count());
        $advanceSearch = json_decode($request->advanceSearch);

        $query = resource_allocation::query();
        
        if($request->searchTerm != '' && $request->searchTerm != null && $request->searchTerm != 'select_value'){

            $query->where(DB::raw("CONCAT(`id`, ' ',`resourceOwnerType`, ' ', `resourceOwnerId`)"), 'LIKE', "%" . $request->searchTerm . "%");
            
        }


        if($advanceSearch->resourceType != '' && $advanceSearch->resourceType != null && $advanceSearch->resourceType != 'select_value'){

                $query->where('resourceOwnerType', 'LIKE', "%" . $advanceSearch->resourceType . "%");
            }

        if($advanceSearch->resourceOwner != '' && $advanceSearch->resourceOwner != null && $advanceSearch->resourceOwner != 'select_value'){


                $resourceType = $advanceSearch->resourceType;

                if ($resourceType == 'Project') {

                    $query->WhereHas('project', function( $tmpquery ) use ( $advanceSearch ){

                      $tmpquery->where(DB::raw("CONCAT(`client_code`, ' ', `client_name`)"), 'LIKE', "%" . $advanceSearch->resourceOwner . "%");

                  });

                } else if($resourceType == 'Accommodation'){

                    $query->WhereHas('accommodation', function( $tmpquery ) use ( $advanceSearch ){

                      $tmpquery->where(DB::raw("CONCAT(`id`, ' ', `name`)"), 'LIKE', "%" . $advanceSearch->resourceOwner . "%");

                  });
                   
                   
                }
                else
                {
                    //dd($resourceType);

                    $query->WhereHas('employee', function( $tmpquery ) use ( $advanceSearch ){

                      $tmpquery->where("emp_id", $advanceSearch->resourceOwner);

                  });
                    
                }

                
            }

        if($advanceSearch->allocationDate != '' && $advanceSearch->allocationDate != null && $advanceSearch->allocationDate != 'select_value'){

                

                $query->whereDate('allocationDate', '=', $advanceSearch->allocationDate);
            }

        if($advanceSearch->notes != '' && $advanceSearch->notes != null && $advanceSearch->notes != 'select_value'){

                $query->where('notes', 'LIKE', "%" . $advanceSearch->notes . "%");
            }
        
        $totalRecordswithFilter=clone $query;
        
        // $page = $request->page_no ?? 1;
        // $start = ($page * 10) -10 ;
        // $query->offset($start);
        // $query->limit($page * 10);
        //dd($start);
        $resourceRecords = $query->latest()->get();
        $totalRows = $totalRecordswithFilter->count();

        $resourceRecords = $this->paginaterhelp($request->page_no,$resourceRecords,$request);

        return resourceCenterresource::collection($resourceRecords)->additional(['success' => true,'totalRows' => $totalRows,'message'=>'Resource Records fetched.']);

    }

    public function deleteAllocation(Request $request){

        $resource_allocation = resource_allocation::find($request->id);

        if(!$resource_allocation){

            return response()->json(['message'=>'Invalid Entry','success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);  
        }

        $resource_allocation->delete();

        $user = JWTAuth::user();
        $action = 'DELETED';
        $itemName = $resource_allocation->resourceOwnerType.' Resource';
        $itemUnique = $resource_allocation->id;
        $tableName = 'Resources';
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);

        return response()->json(['message'=>'Deleted Successfully','success' => true,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);

    }

    public function getAllocatedResourceDetail(Request $request){
        
        $resource_allocation = resource_allocation::find($request->id);

        if(!$resource_allocation){

            return response()->json(['message'=>'Invalid Entry','success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);  
        }

        $statement = DB::select("SHOW TABLE STATUS LIKE 'resource_allocations'");
        $nextId = $statement[0]->Auto_increment;
        
        return (new resourceCenterresource($resource_allocation))->additional(['nextId' => $nextId,'success' => true,'message'=>'Resource Details fetched.']);

    }

    public function updateAllocatedResources(Request $request){
        //dd($request->resourceItems);
        $request['updated_by'] = Auth::user()->name;

        $resource_allocation = resource_allocation::find($request->id);

        if(!$resource_allocation){

            return response()->json(['message'=>'Invalid Entry','success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);  
        }


            $messages = [
                        'resourceOwnerId.required'  => 'Specify Resource Owner.',
                        'resourceOwnerType.required'  => 'Specify Owner Type.',
                    ];
    
        $validate = Validator::make($request->all(), [ 
            'resourceOwnerId' => 'required',
            'resourceOwnerType' => 'required', 
            // 'device_token' => 'required', 
        ],$messages);

        if ($validate->fails()) { 
                  
                return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);            
            }


        $error = false;

        foreach ($request->resourceItems as $key => $item) {

            if (!$error) {

                if (empty(trim($item['name']))) {

                    $error = true;

                    $tmpErrorMsg = 'Resource item Name Cannot Be empty.';
                    // code...
                }

                if (empty(trim($item['quantity']))) {

                    $error = true;
                    $tmpErrorMsg = 'Item Quantity Cannot Be empty.';
                    // code...
                }
            }

            
        }

        if ($error) {

                    return response()->json(['message'=>$tmpErrorMsg,'success' => false,'data' => []], 200);            
                }



        $resourceType = $request->resourceOwnerType;



        $resource_allocation->projectItems()->detach();
        $resource_allocation->accommodationItems()->detach();
        $resource_allocation->employeeItems()->detach();

            if ($resourceType == 'Project') {

                $resource_allocation->update($request->all());

                foreach ($request->resourceItems as $key => $item) {

                        if (is_numeric($item['name'])) {
                            $tmpIndex = $item['name'];
                        } else {
                            $tmpIndex = $item['id'];
                        }
                        
                        
                    $resource_allocation->projectItems()
                        ->attach($tmpIndex,['quantity'=>$item['quantity']]);
                }

            } else if($resourceType == 'Accommodation'){

                
               
               $resource_allocation->update($request->all());

                foreach ($request->resourceItems as $key => $item) {

                    if (is_numeric($item['name'])) {
                            $tmpIndex = $item['name'];
                        } else {
                            $tmpIndex = $item['id'];
                        }

                    $resource_allocation->accommodationItems()
                        ->attach($tmpIndex,['quantity'=>$item['quantity']]);
                }

            }
            else
            {
                
                
                $resource_allocation->update($request->all());

                //return $request->resourceItems;
                foreach ($request->resourceItems as $key => $item) {

                    if (is_numeric($item['name'])) {
                            $tmpIndex = $item['name'];
                        } else {
                            $tmpIndex = $item['id'];
                        }

                    $resource_allocation->employeeItems()
                        ->attach($tmpIndex,['quantity'=>$item['quantity']]);
                    
                }

                //dd($resource_allocation->employeeItems->toArray());
            }

        
        $user = JWTAuth::user();
        $action = 'UPDATED';
        $itemName = $resource_allocation->resourceOwnerType.' Resource';
        $itemUnique = $resource_allocation->id;
        $tableName = 'Resources';
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);

        
        return response()->json(['message'=>'Entry Updated','success' => true,'data' => json_decode(json_encode([]))]);




    }

    public function getResourceItems(Request $request){

        $resourceType = $request->resourceType;

            if ($resourceType == 'Project') {

                $resourceItems = projectResource::select(DB::raw('id AS value'),'name as text')->where(DB::raw("CONCAT(`id`, ' ', `name`)"), 'LIKE', "%" . $request->searchTerm . "%")->latest()->get();

            } else if($resourceType == 'Accommodation'){
               
               $resourceItems = accommodationResource::select(DB::raw('id AS value'),'name as text')->where(DB::raw("CONCAT(`id`, ' ', `name`)"), 'LIKE', "%" . $request->searchTerm . "%")->latest()->get();

            }
            else
            {
                $resourceItems = employeeItemResource::select(DB::raw('id AS value'),'name as text')->where(DB::raw("CONCAT(`id`, ' ', `name`)"), 'LIKE', "%" . $request->searchTerm . "%")->latest()->get();
            }

        $totalRows = count($resourceItems);

        $resourceItems = $this->paginaterhelp($request->page_no,$resourceItems,$request);

           return projectItemResource::collection($resourceItems)->additional(['success' => true,'totalRows' => $totalRows,'message'=>'Resource Items fetched.']);
    }

    public function deleteResourceItem(Request $request){

        $resourceType = $request->resourceType;

            if ($resourceType == 'Project') {

                $resourceItems = projectResource::find($request->id);

                $tableName = 'Project Resources';

            } else if($resourceType == 'Accommodation'){
               

               $resourceItems = accommodationResource::find($request->id);

               $tableName = 'Accommodation Resources';

            }
            else
            {

                $resourceItems = employeeItemResource::find($request->id);

                $tableName = 'Employee Resources';
            }

        if(!$resourceItems){

            return response()->json(['message'=>'Invalid Entry','success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);  
        }

        $resourceItems->delete();

        $user = JWTAuth::user();
        $action = 'DELETED';
        $itemName = $resourceItems->name.' Resource Item';
        $itemUnique = $resourceItems->id;
        
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);


        return response()->json(['message'=>'Deleted Successfully','success' => true,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);

    }

    public function addResourceItem(Request $request){


        $messages = [
                        'name.required'  => 'Specify Resource Name.',
                        'resourceType.required'  => 'Specify Resource Type.',
                    ];
    
        $validate = Validator::make($request->all(), [ 
            'name' => 'required',
            'resourceType' => 'required', 
        ],$messages);

        $resourceType = $request->resourceType;

        if ($validate->fails()) { 
                  
                return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);            
            }

        if ($resourceType == 'Project') {

                $resourceItems = projectResource::create($request->all());
                $returnUrl = '/project/items';

                $tableName = 'Project Resources';

        } 
        else if($resourceType == 'Accommodation'){
               

               $resourceItems = accommodationResource::create($request->all());
               $returnUrl = '/accommodation/items';

               $tableName = 'Accommodation Resources';

        }
        else
        {

                $resourceItems = employeeItemResource::create($request->all());
                $returnUrl = '/employee/items';

                $tableName = 'Employee Resources';
            } 


        $user = JWTAuth::user();
        $action = 'CREATED';
        $itemName = $resourceItems->name.' Resource Item';
        $itemUnique = $resourceItems->id;
        
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);


        return response()->json(['message'=>'Resource Item Created','success' => true,'returnUrl' => $returnUrl,'data' => json_decode(json_encode($resourceItems))]);


    }

    public function getResourceItemDetail(Request $request){

        $messages = [
                        'id.required'  => 'Specify Resource.',
                        'resourceType.required'  => 'Specify Resource Type.',
                    ];
    
        $validate = Validator::make($request->all(), [ 
            'id' => 'required',
            'resourceType' => 'required', 
        ],$messages);

        $resourceType = $request->resourceType;

        if ($validate->fails()) { 
                  
                return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);            
            }

        if ($resourceType == 'Project') {

                $resourceItem = projectResource::find($request->id);

        } 
        else if($resourceType == 'Accommodation'){
               

               $resourceItem = accommodationResource::find($request->id);

        }
        else
        {

                $resourceItem = employeeItemResource::find($request->id);
            } 

        if(!$resourceItem){

            return response()->json(['message'=>'Invalid Entry','success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);  
        }

        return (new resourceCenterItemResource($resourceItem))->additional(['success' => true,'message'=>'Resource Item Details fetched.']);

    }

    public function updateResourceItem(Request $request){


        $messages = [
                        'id.required'  => 'Invalid ID.',
                        'name.required'  => 'Specify Resource Name.',
                        'resourceType.required'  => 'Specify Resource Type.',
                    ];
    
        $validate = Validator::make($request->all(), [ 
            'name' => 'required',
            'resourceType' => 'required', 
            'id' => 'required',
        ],$messages);

        $resourceType = $request->resourceType;

        if ($validate->fails()) { 
                  
                return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);            
            }

        if ($resourceType == 'Project') {
                $resourceItem = projectResource::find($request->id);

                $resourceItem->update($request->all());
                $returnUrl = '/project/items';

                $tableName = 'Project Resources';

        } 
        else if($resourceType == 'Accommodation'){
               
               $resourceItem = accommodationResource::find($request->id);
               $resourceItem->update($request->all());
               $returnUrl = '/accommodation/items';

               $tableName = 'Accommodation Resources';

        }
        else
        {
                $resourceItem = employeeItemResource::find($request->id);
                $resourceItem->update($request->all());
                $returnUrl = '/employee/items';

                $tableName = 'Employees Resources';
            } 


        $user = JWTAuth::user();
        $action = 'UPDATED';
        $itemName = $resourceItem->name.' Resource Item';
        $itemUnique = $resourceItem->id;
        
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);


        return response()->json(['message'=>'Resource Item Updated','success' => true,'returnUrl' => $returnUrl,'data' => json_decode(json_encode($resourceItem))]);


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
