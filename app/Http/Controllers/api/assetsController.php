<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\assetsManagment;
use App\Models\asset_work_order;
use App\Models\assetMaintenance;
use App\Models\assetType;
use App\Models\assetModel;
use App\Models\assetMake;
use App\Models\assetCapacity;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use JWTAuth;
use Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Namshi\JOSE\JWT;
use App\Http\Resources\assetsMangmentResource;
use App\Http\Resources\assetsWorkOrderResources;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Resources\singleasseWorkOrderResources;
use App\Http\Resources\assetMaintenanceResources;
use App\Http\Resources\assetTypeResources;
use App\Http\Resources\singleAssetsManagmentResource;


class assetsController extends Controller
{
    
    public function createAsset(Request $request)
    {
        $item_number = 0;
        $allAssets = [];

        foreach (json_decode($request->items) as $item) {
            $files = null;
            $item_number++;
            $validate =  $this->IsValidateAsset($item);
            if ($validate['success'] == false) {
                return response()->json(['message' => $validate['message'] . " In item " . $item_number, 'success' => false, 'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);
            }
            if ($item->attachment_length > 0 && $item->attachment_file_name != null) {

                if ($request->hasFile($item->attachment_file_name)) {

                    $files = $request->file($item->attachment_file_name);
                }
            }
            if ($files != null) {
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
                $legal_documents = [];
                if ($files != null) {
                    foreach ($files as $file) {
                        $legal_documents[] =  $this->img_upload($file);
                    }
                }
                $item->legal_documents = json_encode($legal_documents);
                $item->asset_name = $item->asset_name;
                $item->year = $item->year;
                $item->fuel_type =  $item->fuel_type;
                $item->chassis_number = $item->chassis_number;
                $item->mileage = $item->mileage;
                $item->notes = $item->notes;
                $item->asset_type_id = $item->asset_type_id;
                $item->asset_model_id = $item->asset_model_id;
                $item->asset_make_id = $item->asset_make_id;
                $item->asset_capacity_id = $item->asset_capacity_id;
                $item->created_by = Auth::user()->name;
                $allAssets[] = $item;
                assetsManagment::create(json_decode(json_encode($item), true));
                $user = JWTAuth::user();
                $action = 'CREATED';
                $itemName = 'Assests added against' . $request->id;
                $itemUnique = $request->id;
                $tableName = 'AssetManagment';
                recordActivity($user, $action, $itemName, $itemUnique, $tableName);
            }
        }
        return response()->json(['message' => 'Assets Created', 'success' => true, 'data' => json_decode(json_encode($allAssets))]);
    }

   

    public function getAssetById(Request  $request){ 
    $assetsManagment = assetsManagment::where('id', $request->id)->first();
    $assetsManagment->legal_documents = json_decode($assetsManagment->legal_documents);
    return new singleAssetsManagmentResource($assetsManagment);
    //return response()->json(['message'=>'Document fetched','success' => true,'base_url' => url('/'),'data' => json_decode(json_encode($assetsManagment))]);
}

    public function updateAsset(Request $request)
    {
         $input = $request->all();
         $messages = [
            'asset_name.required'  => 'Asset Name must be required.',
            'year.required'  => 'year must be required.',
            'fuel_type.required'  => 'Fuel Type must be required.',
            'chassis_number.required'  => 'Chassis Number must be required.',
            'mileage.required'  => 'mileagemust be required.',

        ];

        $validate = Validator::make($input, [
            'asset_name' => 'required|string|max:255',
            'year' => 'required|string|max:255',
            'fuel_type' => 'required|string|max:255',
            'chassis_number' => 'required|string|max:255',
            'mileage' => 'required|string|max:255',
        ], $messages);

        if ($validate->fails()) { 
             return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);            
        }
        $attachments = [];
        foreach (json_decode($request->legal_documents) as $file) {
           $attachments[] =  $file;
        }
        $files = $request->file;
        if($files != null){
         foreach($files as $file)
            {
            $attachments[] =  $this->img_upload($file);
            }
            }
       $input['updated_by'] = Auth::user()->name;
       $input['legal_documents'] = json_encode($attachments);
       $assets = assetsManagment::updateOrCreate(['id' => $request->id],$input);

       $assetsManagment = assetsManagment::where('id', $request->id)->first();

       $user = JWTAuth::user();
       $action = 'Updated';
       $itemName = $assetsManagment->asset_name.' assetsManagment';
       $itemUnique = $assetsManagment->id;
       $tableName = 'assetsManagment';
       recordActivity($user,$action,$itemName,$itemUnique,$tableName);
        
       return response()->json(['message' => 'Asset Updated Succseflly', 'success' => true, 'data' => json_decode(json_encode($assets))]);
    }



    public function delete_asset(Request $request)
    {
    $assetsMangment = assetsManagment::where('id', $request->id)->first();
    $user = JWTAuth::user();
    $action = 'Deleted';
    $itemName = $assetsMangment->name.' assetsMangment';
    $itemUnique = $assetsMangment->id;
    $tableName = 'assetsMangment';
    recordActivity($user,$action,$itemName,$itemUnique,$tableName);
    $assetsMangment->delete();
    return response()->json(['message'=>'Asset Data Deleted Successfully','success' => true]);

     }

    public function getAsset(Request  $request){ 
        $advanceSearch = json_decode($request->advanceSearch);
        $query = assetsManagment::query();
        $user = JWTAuth::user();
        if($request->searchTerm != '' && $request->searchTerm != null){
            $query->where(DB::raw("CONCAT(`id`, ' ', `asset_name` , ' ' , `chassis_number`)"), 'LIKE', "%" . $request->searchTerm . "%");
        }
        $documentRecords = null;
        if($advanceSearch->asset_name != '' && $advanceSearch->asset_name != null){
            $query->where(DB::raw("CONCAT(`asset_name` , ' ' , `asset_name`)"), 'LIKE', "%".$advanceSearch->asset_name."%");
        }
        if($advanceSearch->fuel_type != '' && $advanceSearch->fuel_type != null){
            $query->where(DB::raw("CONCAT(`fuel_type` , ' ' , `fuel_type`)"), 'LIKE', "%".$advanceSearch->fuel_type."%");
        }
        if($advanceSearch->mileage != '' && $advanceSearch->mileage != null){
            $query->where('mileage', $advanceSearch->mileage);

        }
        if($advanceSearch->make != '' && $advanceSearch->make != null){
            $query->where('make', $advanceSearch->make)->orWhereHas('assetMake', function( $query ) use ( $advanceSearch ){
                $query->where(DB::raw("CONCAT(`title`)"), 'LIKE', "%" . $advanceSearch->make . "%");
            })->get();
        }
        if($advanceSearch->model != '' && $advanceSearch->model != null){
            $query->where('model', $advanceSearch->model)->orWhereHas('assetModel', function( $query ) use ( $advanceSearch ){
                $query->where(DB::raw("CONCAT(`title`)"), 'LIKE', "%" . $advanceSearch->model . "%");
            })->get();
        }
        if($advanceSearch->capacity != '' && $advanceSearch->capacity != null){
            $query->where('model', $advanceSearch->capacity)->orWhereHas('assetCapacity', function( $query ) use ( $advanceSearch ){
                $query->where(DB::raw("CONCAT(`title`)"), 'LIKE', "%" . $advanceSearch->capacity . "%");
            })->get();
        }
        if($advanceSearch->asset_type != '' && $advanceSearch->capacity != null){
            $query->where('asset_type', $advanceSearch->asset_type)->orWhereHas('asset_type', function( $query ) use ( $advanceSearch ){
                $query->where(DB::raw("CONCAT(`title`)"), 'LIKE', "%" . $advanceSearch->asset_type . "%");
            })->get();
        }

     $documentRecords=clone $query;
     $totalRows = $documentRecords->count();
     $query->orderBy('id', 'desc');
     $documentRecords = $query->get();
        foreach($documentRecords as $item){
            $item['attachment'] = json_decode($item->legal_documents);
        }
    $resourceRecords = $this->paginaterhelp($request->page_no,$documentRecords,$request);
    return assetsMangmentResource::collection($resourceRecords)->additional(['success' => true,'base_url' => url('/'),'success' => true,'totalRows' => $totalRows,'message'=>'AssetsMangment Data fetched.']);
}



 //////////////////////////////    Assets Work Order //////////////////////////////////

public function addWorkOrder(Request $request)
    {

        $messages = [
            'asset_id.required'  => 'asset_id must be required.',
            'asset_name.required'  => 'asset_name must be required.',
            'fromDate.required'  => 'fromDate must be required.',
            'toDate .required'  => 'toDate must be required.',
            'c_id.required'  => 'c_id must be required.',
            'rate_basis.required'  => 'rate_basis must be required.',
            'rate.required'  => 'rate must be required.',
            'check_out_reading.required'  => 'check_out_reading must be required.',
            'check_in_reading.required'  => 'check_in_reading must be required.',
            'location_name.required'  => 'location_name must be required.',
            'client_name.required'  => 'client_name must be required.',
            
        ];
    
        $validate = Validator::make($request->all(), [ 
            'asset_id' => 'required',
            'asset_name' => 'required',
            'fromDate' => 'required',
            'toDate' => 'required',
            'c_id' => 'required',
            'rate_basis' => 'required',
            'rate' => 'required',
            'location_name' => 'required',
            'client_name'  => 'required',
            'location_id'  => 'required',
            'check_in_reading' => 'required',
            'check_out_reading' => 'required',

        ],$messages);


        if ($validate->fails()) { 
             return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);            
        }

        $input = $request->all();

        $input['created_by'] = Auth::user()->name;
        $input['updated_by'] = null;

        assetsManagment::where('id', $input['asset_id'])->update(['mileage' => $input['check_in_reading']]);

        $asset_work_order = asset_work_order::create($input);
        $user = JWTAuth::user();
        $action = 'CREATED';
        $itemName = $asset_work_order->asset_name.' asset_work_order';
        $itemUnique = $asset_work_order->id;
        $tableName = 'asset_work_order';
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);
        
        return response()->json(['message'=>'Asset Work Order Created','success' => true,'data' => json_decode(json_encode($asset_work_order))]);

    }

    public function getAssetsDropdown(Request $request){
       $assetsManagment =  assetsManagment ::select('asset_name AS name','id AS id')->where(DB::raw("CONCAT(`asset_name`, ' ' , `id`)"), 'LIKE', "%" . $request->id . "%")->get();
       return response()->json(['message'=>'Assets Fetched','success' => true,'data' => json_decode(json_encode($assetsManagment))]);
    }

    public function deleteWorkOrder(Request $request){

       $asset_work_order = asset_work_order::where('id', $request->id)->first();
       $user = JWTAuth::user();
       $action = 'Deleted';
       $itemName = $asset_work_order->name.' assetsMangment';
       $itemUnique = $asset_work_order->id;
       $tableName = 'asset_work_order';
       recordActivity($user,$action,$itemName,$itemUnique,$tableName);
       $asset_work_order->delete();
       return response()->json(['message'=>'Asset Work Order Deleted Successfully','success' => true]);   
    }
    public function getWorkOrder(Request  $request){ 
        $advanceSearch = json_decode($request->advanceSearch);
        $query = asset_work_order::query();
        $user = JWTAuth::user();
        if($request->searchTerm != '' && $request->searchTerm != null){
            $query->where(DB::raw("CONCAT(`id`, ' ', `c_id` , ' ' , `rate_basis`, ' ' , `rate`  , ' ' , `check_out_reading` , ' ' , `check_in_reading` , ' ' , `scope` , ' ' , `notes` , ' ' , `asset_name` , ' ' , `location_name`)"), 'LIKE', "%" . $request->searchTerm . "%");
        }
        $documentRecords = null;
        if($advanceSearch->asset_name != '' && $advanceSearch->asset_name != null){
            $query->where(DB::raw("CONCAT(`asset_name` , ' ' , `asset_name`)"), 'LIKE', "%".$advanceSearch->asset_name."%");
        }
        if($advanceSearch->rate_basis != '' && $advanceSearch->rate_basis != null){
            $query->where('rate_basis', $advanceSearch->rate_basis);

        }
        if($advanceSearch->rate != '' && $advanceSearch->rate != null){
            $query->where('rate', $advanceSearch->rate);
        }
        if($advanceSearch->check_out_reading != '' && $advanceSearch->check_out_reading != null){
            $query->where('check_out_reading', $advanceSearch->check_out_reading);
        }
        if($advanceSearch->check_in_reading != '' && $advanceSearch->check_in_reading != null){
            $query->where('check_in_reading', $advanceSearch->check_in_reading);
        }
        if($advanceSearch->scope != '' && $advanceSearch->scope != null){
            $query->where('scope', $advanceSearch->scope);
        }
        if($advanceSearch->notes != '' && $advanceSearch->notes != null){
            $query->where('notes', $advanceSearch->notes);
        }
        if($advanceSearch->asset_name != '' && $advanceSearch->asset_name != null){
            $query->where('asset_name', $advanceSearch->asset_name);
        }
        if($advanceSearch->client_name != '' && $advanceSearch->client_name != null){
            $query->where('client_name', $advanceSearch->client_name);
        }
        if($advanceSearch->location_name != '' && $advanceSearch->location_name != null){
            $query->where('location_name', $advanceSearch->location_name);
        }
        if($advanceSearch->fromDate != '' && $advanceSearch->fromDate != null && $advanceSearch->toDate != '' && $advanceSearch->toDate != null){
            $query->whereBetween('fromDate', [$advanceSearch->fromDate, $advanceSearch->toDate]);
        }

     $documentRecords=clone $query;
     $totalRows = $documentRecords->count();
     $query->orderBy('id', 'desc');
     $documentRecords = $query->get();
     $resourceRecords = $this->paginaterhelp($request->page_no,$documentRecords,$request);
     return assetsWorkOrderResources::collection($resourceRecords)->additional(['success' => true,'base_url' => url('/'),'success' => true,'totalRows' => $totalRows,'message'=>'AssetsMangment Data fetched.']);
    }
    public function getWorkOrderById(Request $request){
       $work_order = asset_work_order::where('id', $request->id)->first();
       return new singleasseWorkOrderResources($work_order);
       return response()->json(['message'=>'Document fetched','success' => true,'base_url' => url('/'),'data' => json_decode(json_encode($work_order    ))]);
    }

    public function updateWorkOrder(Request $request)
    {

        $messages = [
            'asset_id.required'  => 'asset_id must be required.',
            'asset_name.required'  => 'asset_name must be required.',
            'fromDate.required'  => 'fromDate must be required.',
            'toDate .required'  => 'toDate must be required.',
            'c_id.required'  => 'c_id must be required.',
            'rate_basis.required'  => 'rate_basis must be required.',
            'rate.required'  => 'rate must be required.',
            'check_out_reading.required'  => 'check_out_reading must be required.',
            'check_in_reading.required'  => 'check_in_reading must be required.',
            'scope.required'  => 'scope must be required.',
            'location_name.required'  => 'location_name must be required.',
            'client_name.required'  => 'client_name must be required.',
            
        ];
    
        $validate = Validator::make($request->all(), [ 
            'asset_id' => 'required',
            'asset_name' => 'required',
            'fromDate' => 'required',
            'toDate' => 'required',
            'c_id' => 'required',
            'rate_basis' => 'required',
            'rate' => 'required',
            'scope' => 'required',
            'location_name' => 'required',
            'client_name'  => 'required',
            'location_id'  => 'required',
            'check_out_reading' => 'required',
            'check_in_reading' => 'required'

        ],$messages);


        if ($validate->fails()) { 
             return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);            
        }

        $input = $request->all();


        $input['updated_by'] = Auth::user()->name;
         asset_work_order::updateOrCreate([
            'id' => $request->id
        ], $input);

        assetsManagment::where('id', $input['asset_id'])->update(['mileage' => $input['check_in_reading']]);


        $asset_work_order = asset_work_order::where('id', $request->id)->first();


        $user = JWTAuth::user();
        $action = 'Updated';
        $itemName = $asset_work_order->asset_name.' asset_work_order';
        $itemUnique = $asset_work_order->id;
        $tableName = 'asset_work_order';
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);
        
        return response()->json(['message'=>'Asset Work Order Created','success' => true,'data' => json_decode(json_encode($asset_work_order))]);

    }

    ///// Helper Functions 

public function paginaterhelp($page,$items,$request){

            $page = $page; 
            $perPage = 10;
            $offSet = ($page * $perPage) - $perPage; // Start displaying items from this number
        if(is_array($items))
           {
            $itemsForCurrentPage = array_slice($items, $offSet, $perPage, false);
           }else{
            $itemsForCurrentPage = $items->slice($offSet, $perPage);
           }
            $result = new LengthAwarePaginator($itemsForCurrentPage, count($items), $perPage, $page, ['path' => $request->url(), 'query' => $request->query()]);
            return $result->items();
     }

 public  function img_upload($tmpImage)
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
        $image->storeAs('assets', $file_namefor_db, 'public');
        $pic = ('storage/assets/' . $file_namefor_db);

        return $pic;
    }
    
    function IsValidateAsset($item)
    {
        $item = json_decode(json_encode($item), true);
        $messages = [
            'asset_name.required'  => 'Asset Name must be required.',
            'asset_type.required'  => 'Asset Type be required.',
            'year.required'  => 'year must be required.',
            'model.required'  => 'model must be required.',
            'make.required'  => 'make must be required.',
            'capacity.required'  => 'capacity must be required.',
            'fuel_type.required'  => 'Fuel Type must be required.',
            'chassis_number.required'  => 'Chassis Number must be required.',
            'mileage.required'  => 'mileagemust be required.',

        ];

        $validate = Validator::make($item, [
            'asset_name' => 'required|string|max:255',
            'asset_type' => 'required|string|max:255',
            'year' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'make' => 'required|string|max:255',
            'capacity' => 'required|string|max:255',
            'fuel_type' => 'required|string|max:255',
            'chassis_number' => 'required|string|max:255',
            'mileage' => 'required|string|max:255',
        ], $messages);

        if ($validate->fails()) {

            return ['message' => $validate->errors()->first(), 'success' => false];
        } else {
            return ['message' => '', 'success' => true];
        }
    }


    ///////////////////// Asset Maintanace /////////////////////////////

    function addAssetMaintenance(Request $request){

        $input = $request->all();
         $messages = [
            'asset_name.required'  => 'Asset Name must be required.',
            'asset_id.required'  => 'Asset Id be required.',
            'maintenance_date.required'  => 'Maintenance Date be required.',
            'last_reading.required'  => 'model must be required.',
            'notes.required'  => 'capacity must be required.',

        ];

        $validate = Validator::make($input, [
            'asset_name' => 'required',
            'asset_id' => 'required',
            'maintenance_date' => 'required',
            'last_reading' => 'required',
        ], $messages);

        if ($validate->fails()) { 
             return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);            
        }
        $input = $request->all();

        $attachments = [];

        $files = $request->file;
        if($files != null){
            foreach($files as $file){
             $attachments[] =  $this->img_upload($file);
             }
            }
       $input['created_by'] = Auth::user()->name;
       $input['attachment'] = json_encode($attachments);
       $assetMaintenance = assetMaintenance::create($input);

       $user = JWTAuth::user();
        $action = 'CREATED';
        $itemName = $assetMaintenance->asset_name.' assetMaintenance';
        $itemUnique = $assetMaintenance->id;
        $tableName = 'assetMaintenance';
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);
        
       return response()->json(['message'=>'Asset Maintenance Created','success' => true,'data' => json_decode(json_encode($assetMaintenance))]);

    }

    function updateAssetMaintenance(Request $request){

        $input = $request->all();
         $messages = [
            'asset_name.required'  => 'Asset Name must be required.',
            'asset_id.required'  => 'Asset Id be required.',
            'maintenance_date.required'  => 'Maintenance Date be required.',
            'last_reading.required'  => 'model must be required.',
            'attachment.required'  => 'make must be required.',
            'notes.required'  => 'capacity must be required.',

        ];

        $validate = Validator::make($input, [
            'asset_name' => 'required|string|max:255',
            'asset_id' => 'required',
            'maintenance_date' => 'required',
            'last_reading' => 'required',
        ], $messages);

        if ($validate->fails()) { 
             return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);            
        }
        $attachments = [];
        foreach (json_decode($request->attachment) as $file) {
           $attachments[] =  $file;
        }
        $files = $request->file;
        if($files != null){
         foreach($files as $file)
            {
            $attachments[] =  $this->img_upload($file);
            }
            }
       $input['updated_by'] = Auth::user()->name;
       $input['attachment'] = json_encode($attachments);
       $assets = assetMaintenance::updateOrCreate(['id' => $request->id],$input);

       $assetMaintenance = assetMaintenance::where('id', $request->id)->first();

       $user = JWTAuth::user();
       $action = 'Updated';
       $itemName = $assetMaintenance->asset_name.' assetsManagment';
       $itemUnique = $assetMaintenance->id;
       $tableName = 'assetMaintenance';
       recordActivity($user,$action,$itemName,$itemUnique,$tableName);
        
       return response()->json(['message' => 'Asset Updated Succseflly', 'success' => true, 'data' => json_decode(json_encode($assets))]);

    }

    public function getAssetMaintenanceById(Request $request){
       $assetMaintenance = assetMaintenance::where('id', $request->id)->first();
       $assetMaintenance->attachment = json_decode($assetMaintenance->attachment);
       
       return response()->json(['message'=>'Document fetched','success' => true,'base_url' => url('/'),'data' => json_decode(json_encode($assetMaintenance))]);
    }

    public function deleteAssetMaintenance(Request $request){

       $assetMaintenance = assetMaintenance::where('id', $request->id)->first();
       $user = JWTAuth::user();
       $action = 'Deleted';
       $itemName = $assetMaintenance->asset_name.' assetMaintenance';
       $itemUnique = $assetMaintenance->id;
       $tableName = 'assetMaintenance';
       recordActivity($user,$action,$itemName,$itemUnique,$tableName);
       $assetMaintenance->delete();
       return response()->json(['message'=>'Asset Maintenance Deleted Successfully','success' => true]);   
    }


      public function getAssetMaintenance(Request  $request){ 
        $advanceSearch = json_decode($request->advanceSearch);
        $query = assetMaintenance::query();
        $user = JWTAuth::user();
        if($request->searchTerm != '' && $request->searchTerm != null){
            $query->where(DB::raw("CONCAT(`id`, ' ', `asset_name` , ' ' , `last_reading`, ' ' , `notes`  , ' ' , `maintenance_type`)"), 'LIKE', "%" . $request->searchTerm . "%");
        }
        $documentRecords = null;
        if($advanceSearch->asset_name != '' && $advanceSearch->asset_name != null){
            $query->where(DB::raw("CONCAT(`asset_name` , ' ' , `asset_name`)"), 'LIKE', "%".$advanceSearch->asset_name."%");
        }
        if($advanceSearch->maintenance_date != '' && $advanceSearch->maintenance_date != null){
            $query->where('maintenance_date', $advanceSearch->maintenance_date);

        }
        if($advanceSearch->maintenance_type != '' && $advanceSearch->maintenance_type != null){
            $query->where('maintenance_type', $advanceSearch->maintenance_type);
        }
        if($advanceSearch->last_reading != '' && $advanceSearch->last_reading != null){
            $query->where('last_reading', $advanceSearch->last_reading);
        }
        if($advanceSearch->notes != '' && $advanceSearch->notes != null){
            $query->where('notes', $advanceSearch->notes);
        }

     $documentRecords = clone $query;
     $totalRows = $documentRecords->count();
     $query->orderBy('id', 'desc');
     $documentRecords = $query->get();
     foreach($documentRecords as $item){
            $item['attachment'] = json_decode($item->attachment);
        }
     $resourceRecords = $this->paginaterhelp($request->page_no,$documentRecords,$request);
     return assetMaintenanceResources::collection($resourceRecords)->additional(['success' => true,'base_url' => url('/'),'success' => true,'totalRows' => $totalRows,'message'=>'AssetsMangment Data fetched.']);
    }

    ///////////// asset Types ////////////////////


    function addAssetType(Request $request){

        $input = $request->all();
         $messages = [
            'title.required'  => 'Title must be required.',
        ];
        $validate = Validator::make($input, [
            'title' => 'required',
        ], $messages);

        if ($validate->fails()) { 
             return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);            
        }
       $input = $request->all();
       $input['created_by'] = Auth::user()->name;
       $assetMaintenance = assetType::create($input);

       return response()->json(['message'=>'Asset Type Created','success' => true,'data' => json_decode(json_encode($assetMaintenance))]);

    }

    public function deleteAssetType(Request $request){
       $assetMaintenance = assetType::where('id', $request->id)->first();
       $assetMaintenance->delete();
       return response()->json(['message'=>'Asset Type Deleted Successfully','success' => true]);   
    }

    public function getAssetTypeById(Request $request){
       $assetMaintenance = assetType::where('id', $request->id)->first();
       $assetMaintenance->attachment = json_decode($assetMaintenance->attachment);
       return response()->json(['message'=>'Asset Type fetched','success' => true,'base_url' => url('/'),'data' => json_decode(json_encode($assetMaintenance))]);
    }

    public function getAssetsType(Request  $request){ 
        $query = assetType::query();
        $user = JWTAuth::user();
        if($request->searchTerm != '' && $request->searchTerm != null){
            $query->where(DB::raw("CONCAT(`id`, ' ', `title`)"), 'LIKE', "%" . $request->searchTerm . "%");
        }
        $documentRecords = null;
        $documentRecords = clone $query;
        $totalRows = $documentRecords->count();
        $query->orderBy('id', 'desc');
        $documentRecords = $query->get();
        $resourceRecords = $this->paginaterhelp($request->page_no,$documentRecords,$request);
        return assetTypeResources::collection($resourceRecords)->additional(['success' => true,'base_url' => url('/'),'success' => true,'totalRows' => $totalRows,'message'=>'AssetsMangment Data fetched.']);
    }

     function updateAssetType(Request $request){

        $input = $request->all();
         $messages = [
            'title.required'  => 'Title must be required.',
        ];

        $validate = Validator::make($input, [
            'title' => 'required|string|max:255',

        ], $messages);

        if ($validate->fails()) { 
             return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);            
        }
      
       $input['updated_by'] = Auth::user()->name;
       $assets = assetType::updateOrCreate(['id' => $request->id],$input);
       $assetMaintenance = assetType::where('id', $request->id)->first();
       return response()->json(['message' => 'Asset Updated Succseflly', 'success' => true, 'data' => json_decode(json_encode($assets))]);

    }

    /////////// asset Model ///////////////////

      function addAssetModel(Request $request){

        $input = $request->all();
         $messages = [
            'title.required'  => 'Title must be required.',
        ];
        $validate = Validator::make($input, [
            'title' => 'required',
        ], $messages);

        if ($validate->fails()) { 
             return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);            
        }
       $input = $request->all();
       $input['created_by'] = Auth::user()->name;
       $assetMaintenance = assetModel::create($input);
       return response()->json(['message'=>'Asset Type Created','success' => true,'data' => json_decode(json_encode($assetMaintenance))]);

    }

    public function deleteAssetModel(Request $request){
       $assetMaintenance = assetModel::where('id', $request->id)->first();
       $assetMaintenance->delete();
       return response()->json(['message'=>'Asset Type Deleted Successfully','success' => true]);   
    }

    public function getAssetModelById(Request $request){
       $assetMaintenance = assetModel::where('id', $request->id)->first();
       $assetMaintenance->attachment = json_decode($assetMaintenance->attachment);
       return response()->json(['message'=>'Asset Type fetched','success' => true,'base_url' => url('/'),'data' => json_decode(json_encode($assetMaintenance))]);
    }

    public function getAssetsModel(Request  $request){ 
        $query = assetModel::query();
        if($request->searchTerm != '' && $request->searchTerm != null){
            $query->where(DB::raw("CONCAT(`id`, ' ', `title`)"), 'LIKE', "%" . $request->searchTerm . "%");
        }
        $documentRecords = null;
        $documentRecords = clone $query;
        $totalRows = $documentRecords->count();
        $query->orderBy('id', 'desc');
        $documentRecords = $query->get();
        $resourceRecords = $this->paginaterhelp($request->page_no,$documentRecords,$request);
        return assetTypeResources::collection($resourceRecords)->additional(['success' => true,'base_url' => url('/'),'success' => true,'totalRows' => $totalRows,'message'=>'AssetsMangment Data fetched.']);
    }

    function updateAssetModel(Request $request){

        $input = $request->all();
         $messages = [
            'title.required'  => 'Title must be required.',
        ];

        $validate = Validator::make($input, [
            'title' => 'required|string|max:255',

        ], $messages);

        if ($validate->fails()) { 
             return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);            
        }
      
       $input['updated_by'] = Auth::user()->name;
       $assets = assetModel::updateOrCreate(['id' => $request->id],$input);
       $assetMaintenance = assetModel::where('id', $request->id)->first();
       return response()->json(['message' => 'Asset Updated Succseflly', 'success' => true, 'data' => json_decode(json_encode($assets))]);
    }

    //////. asset Make ///////////

     function addAssetMake(Request $request){

        $input = $request->all();
         $messages = [
            'title.required'  => 'Title must be required.',
        ];
        $validate = Validator::make($input, [
            'title' => 'required',
        ], $messages);

        if ($validate->fails()) { 
             return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);            
        }
       $input = $request->all();
       $input['created_by'] = Auth::user()->name;
       $assetMaintenance = assetMake::create($input);
       return response()->json(['message'=>'Asset Type Created','success' => true,'data' => json_decode(json_encode($assetMaintenance))]);

    }

    public function deleteAssetMake(Request $request){
       $assetMaintenance = assetMake::where('id', $request->id)->first();
       $assetMaintenance->delete();
       return response()->json(['message'=>'Asset Type Deleted Successfully','success' => true]);   
    }

    public function getAssetMakeById(Request $request){
       $assetMaintenance = assetMake::where('id', $request->id)->first();
       $assetMaintenance->attachment = json_decode($assetMaintenance->attachment);
       return response()->json(['message'=>'Asset Type fetched','success' => true,'base_url' => url('/'),'data' => json_decode(json_encode($assetMaintenance))]);
    }

    public function getAssetsMake(Request  $request){ 
        $query = assetMake::query();
        if($request->searchTerm != '' && $request->searchTerm != null){
            $query->where(DB::raw("CONCAT(`id`, ' ', `title`)"), 'LIKE', "%" . $request->searchTerm . "%");
        }
        $documentRecords = null;
        $documentRecords = clone $query;
        $totalRows = $documentRecords->count();
        $query->orderBy('id', 'desc');
        $documentRecords = $query->get();
        $resourceRecords = $this->paginaterhelp($request->page_no,$documentRecords,$request);
        return assetTypeResources::collection($resourceRecords)->additional(['success' => true,'base_url' => url('/'),'success' => true,'totalRows' => $totalRows,'message'=>'AssetsMangment Data fetched.']);
    }

    function updateAssetMake(Request $request){

        $input = $request->all();
         $messages = [
            'title.required'  => 'Title must be required.',
        ];

        $validate = Validator::make($input, [
            'title' => 'required|string|max:255',

        ], $messages);

        if ($validate->fails()) { 
             return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);            
        }
      
       $input['updated_by'] = Auth::user()->name;
       $assets = assetMake::updateOrCreate(['id' => $request->id],$input);
       $assetMaintenance = assetMake::where('id', $request->id)->first();
       return response()->json(['message' => 'Asset Updated Succseflly', 'success' => true, 'data' => json_decode(json_encode($assets))]);
    }

    //////////// Asset Capacity ///////////////////

      function addAssetCapacity(Request $request){

        $input = $request->all();
         $messages = [
            'title.required'  => 'Title must be required.',
        ];
        $validate = Validator::make($input, [
            'title' => 'required',
        ], $messages);

        if ($validate->fails()) { 
             return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);            
        }
       $input = $request->all();
       $input['created_by'] = Auth::user()->name;
       $assetMaintenance = assetCapacity::create($input);
       return response()->json(['message'=>'Asset Type Created','success' => true,'data' => json_decode(json_encode($assetMaintenance))]);

    }

    public function deleteAssetCapacity(Request $request){
       $assetMaintenance = assetCapacity::where('id', $request->id)->first();
       $assetMaintenance->delete();
       return response()->json(['message'=>'Asset Type Deleted Successfully','success' => true]);   
    }

    public function getAssetCapacityById(Request $request){
       $assetMaintenance = assetCapacity::where('id', $request->id)->first();
       $assetMaintenance->attachment = json_decode($assetMaintenance->attachment);
       return response()->json(['message'=>'Asset Type fetched','success' => true,'base_url' => url('/'),'data' => json_decode(json_encode($assetMaintenance))]);
    }

    public function getAssetsCapacity(Request  $request){ 
        $query = assetCapacity::query();
        if($request->searchTerm != '' && $request->searchTerm != null){
            $query->where(DB::raw("CONCAT(`id`, ' ', `title`)"), 'LIKE', "%" . $request->searchTerm . "%");
        }
        $documentRecords = null;
        $documentRecords = clone $query;
        $totalRows = $documentRecords->count();
        $query->orderBy('id', 'desc');
        $documentRecords = $query->get();
        $resourceRecords = $this->paginaterhelp($request->page_no,$documentRecords,$request);
        return assetTypeResources::collection($resourceRecords)->additional(['success' => true,'base_url' => url('/'),'success' => true,'totalRows' => $totalRows,'message'=>'AssetsMangment Data fetched.']);
    }

    function updateAssetCapacity(Request $request){

        $input = $request->all();
         $messages = [
            'title.required'  => 'Title must be required.',
        ];

        $validate = Validator::make($input, [
            'title' => 'required|string|max:255',

        ], $messages);

        if ($validate->fails()) { 
             return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);            
        }
      
       $input['updated_by'] = Auth::user()->name;
       $assets = assetCapacity::updateOrCreate(['id' => $request->id],$input);
       $assetMaintenance = assetCapacity::where('id', $request->id)->first();
       return response()->json(['message' => 'Asset Updated Succseflly', 'success' => true, 'data' => json_decode(json_encode($assets))]);
    }



    public function getAssetCapacityDropdown(Request $request){
       $assetCapacities =  assetCapacity ::select('title AS text','id AS id')->get();
       foreach ($assetCapacities as $key => $assetCapacity) {
                $assetCapacity->text =  $assetCapacity->text .  ", " . $assetCapacity->id;
        }
       return response()->json(['message'=>'Assets Fetched','success' => true,'data' => json_decode(json_encode($assetCapacity))]);
    }
    public function getAssetTypeDropdown(Request $request){
       $assetTypes =  assetType ::select('title AS text','id AS id')->get();
       foreach ($assetTypes as $key => $assetType) {
                $assetType->text =  $assetType->text .  ", " . $assetType->id;
        }
       return response()->json(['message'=>'Assets Fetched','success' => true,'data' => json_decode(json_encode($assetTypes))]);
    }
    public function getAssetMakeDropdown(Request $request){
       $assetMakes =  assetMake ::select('title AS text','id AS id')->get();
       foreach ($assetMakes as $key => $assetMake) {
                $assetMake->text =  $assetMake->text .  ", " . $assetMake->id;
        }
       return response()->json(['message'=>'Assets Fetched','success' => true,'data' => json_decode(json_encode($assetMake))]);
    }
    public function getAssetModelDropdown(Request $request){
       $assetModels =  assetModel ::select('title AS text','id AS id')->get();
       foreach ($assetModels as $key => $assetModel) {
                $assetModel->text =  $assetModel->text .  ", " . $assetModel->id;
        }
       return response()->json(['message'=>'Assets Fetched','success' => true,'data' => json_decode(json_encode($assetModel))]);
    }
}