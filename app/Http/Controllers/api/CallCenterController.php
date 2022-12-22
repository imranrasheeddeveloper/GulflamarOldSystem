<?php

namespace App\Http\Controllers\api;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\CallCenter;

use App\Models\pattyCash;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use JWTAuth;
use Validator;
use App\Http\Resources\walletDataResource;

use App\Exports\callCenterExport;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Namshi\JOSE\JWT;

class CallCenterController extends Controller
{
    public function call_center_export_excel(Request $request){
        
        // $query = CallCenter::query();


        // $documentRecords = null;



        // if($request->searchTerm != '' && $request->searchTerm != null){

        //     $query->where(DB::raw("CONCAT(`id`, ' ', `name` , ' ' , `issue`, ' ' , `status`)"), 'LIKE', "%" . $request->searchTerm . "%");

        // }


        // if ($request->limit != '' && $request->limit) {

        //     $query->limit($this->limit);
        // }

        // $query->orderBy('id', 'desc');

        // $records = $query->get();
        // foreach($records as $item){
        //     $item['department_name'] = User::where('id',$item->department)->pluck('name')->first();

        // }
        // return $records;

        return Excel::download(new callCenterExport($request->searchTerm,(int)$request->limit), 'call_center_export_excel.xlsx');

    }
    public function addCallCenter(Request $request)
    {

        $messages = [
            'name.required'  => 'name must be required.',
            'name_id.required'  => 'name must be required.',
            'project.required'  => 'project must be required.',
            'date.required'  => 'date must be required.',
            'time.required'  => 'time must be required.',
            'department.required'  => 'department must be required.',
            'status.required'  => 'status must be required.',
            'resolution.required'  => 'resolution must be required.',
            'issue.required'  => 'issue must be required.',
            'MethodOfContact.required'  => 'MethodOfContact must be required.',
            'contactDetail.required'  => 'contactDetail must be required.',
        ];
    
        $validate = Validator::make($request->all(), [ 
            'name' => 'required|string|max:255',
            'name_id' => 'required',
            'project' => 'required|string',
            'date' => 'required|string|max:255',
            'time' => 'required|string|max:255',
            'department' => 'required|numeric',
            'status' => 'required|string|max:255',
            'resolution' => 'required|string',
            'issue' => 'required|string',
            'MethodOfContact.required'  => 'MethodOfContact must be required.',
            'contactDetail.required'  => 'contactDetail must be required.',

        ],$messages);


        if ($validate->fails()) { 
             return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);            
        }

        $input = $request->all();

        $input['created_by'] = Auth::user()->name;
        $input['updated_by'] = null;


        $wallet = CallCenter::create($input);


        $user = JWTAuth::user();
        $action = 'CREATED';
        $itemName = $wallet->name.' CallCenter';
        $itemUnique = $wallet->id;
        $tableName = 'CallCenter';
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);
        
        return response()->json(['message'=>'Call Created','success' => true,'data' => json_decode(json_encode($wallet))]);

    }


    public function getAllCallCenterData(Request  $request){ 
        $advanceSearch = json_decode($request->advanceSearch);
        $query = CallCenter::query();
        $user = JWTAuth::user();
        if($request->day != '' && $request->day != null){
            if($request->day == "today"){
                $query->whereDate('created_at', Carbon::today());

            }else if($request->day == "yesterday"){
                $query->whereDate('created_at', Carbon::yesterday());
            }elseif($request->day == "my_requests"){
                $query->where('department', $user->id);
            }
        }
        if($request->searchTerm != '' && $request->searchTerm != null){
            // $query->where('id',$request->searchTerm);
            $query->where(DB::raw("CONCAT(`id`, ' ', `name` , ' ' , `issue`, ' ' , `status`)"), 'LIKE', "%" . $request->searchTerm . "%");


        }

        $documentRecords = null;

        if($advanceSearch->name != '' && $advanceSearch->name != null){
            $query->where(DB::raw("CONCAT(`name` , ' ' , `name_id`)"), 'LIKE', "%".$advanceSearch->name."%");

        }

        if($advanceSearch->fromDate != '' && $advanceSearch->fromDate != null && $advanceSearch->toDate != '' && $advanceSearch->toDate != null){
            $query->whereBetween('date', [$advanceSearch->fromDate, $advanceSearch->toDate]);
        }
        if($advanceSearch->status != '' && $advanceSearch->status != null){
            $query->where('status', $advanceSearch->status);

        }
        if($advanceSearch->resolution != '' && $advanceSearch->resolution != null){
            $query->where('resolution', $advanceSearch->resolution);

        }

        $documentRecords=clone $query;
        
        $totalRows = $documentRecords->count();
        $documentRecords = $query->latest()->with('user')->get();
        foreach($documentRecords as $item){
            $item['department_name'] = $item->user->name;
            $item->name= $item->name.' , '.$item->name_id;

        }

    $resourceRecords = $this->paginaterhelp($request->page_no,$documentRecords,$request);


    return walletDataResource::collection($resourceRecords)->additional(['success' => true,'base_url' => url('/'),'success' => true,'totalRows' => $totalRows,'message'=>'Call Center Data fetched.']);

}




public function getCallData(Request  $request){ 

    $wallet = CallCenter::where('id',$request->id)->with('user')->first();


    return response()->json(['message'=>'CallCenter Fetched','success' => true,'data' => json_decode(json_encode($wallet))]);


}



public function updateCallCenter(Request $request){


    $messages = [
        'id.required'  => 'Id must be required.',
        'name.required'  => 'name must be required.',
        'name_id.required'  => 'name must be required.',
        'project.required'  => 'project must be required.',
        'date.required'  => 'date must be required.',
        'time.required'  => 'time must be required.',
        'department.required'  => 'department must be required.',
        'status.required'  => 'status must be required.',
        'resolution.required'  => 'resolution must be required.',
        'issue.required'  => 'issue must be required.',
        'issue_resolution.required'  => 'issue_resolution must be required.',
        'MethodOfContact.required'  => 'MethodOfContact must be required.',
        'contactDetail.required'  => 'contactDetail must be required.',
    ];

    $validate = Validator::make($request->all(), [
        'id' => 'required|numeric',
        'name' => 'required|string|max:255',
        'name_id' => 'required',
        'project' => 'required|string',
        'date' => 'required|string|max:255',
        'time' => 'required|string|max:255',
        'department' => 'required|numeric',
        'status' => 'required|string|max:255',
        'resolution' => 'required|string',
        'issue' => 'required|string',
        'issue_resolution' => 'required|string',
        'MethodOfContact.required'  => 'MethodOfContact must be required.',
        'contactDetail.required'  => 'contactDetail must be required.',

    ],$messages);

    if ($validate->fails()) { 
              
            return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);            
        }

    $input = $request->all();

    $input['updated_by'] = Auth::user()->name;


    CallCenter::updateOrCreate(['id' => $request->id],$input);

    $wallet = CallCenter::where('id',$request->id)->with('user')->first();
    $user = JWTAuth::user();
    $action = 'updated';
    $itemName = $wallet->name.' CallCenter';
    $itemUnique = $wallet->id;
    $tableName = 'CallCenter';

    recordActivity($user,$action,$itemName,$itemUnique,$tableName);
    
    return response()->json(['message'=>'Call Center Data Updated','success' => true,'data' => json_decode(json_encode($wallet))]);

}



public function deleteCallCenter(Request  $request){ 

    $wallet = CallCenter::where('id', $request->id)->first();

    $user = JWTAuth::user();
    $action = 'Deleted';
    $itemName = $wallet->name.' CallCenter';
    $itemUnique = $wallet->id;
    $tableName = 'CallCenter';
    recordActivity($user,$action,$itemName,$itemUnique,$tableName);
    $wallet->delete();


    return response()->json(['message'=>'Call Center Data Deleted Successfully','success' => true]);



}


public function updateCallCenterStatus(Request  $request){ 



    $CallCenter = CallCenter::where('id', $request->id)->first();
    $CallCenter->status = $request->status;
    $CallCenter->save();


    $user = JWTAuth::user();
    $action = 'Updated';
    $itemName = $CallCenter->name.' CallCenter';
    $itemUnique = $CallCenter->id;
    $tableName = 'CallCenter';
    recordActivity($user,$action,$itemName,$itemUnique,$tableName);

    return response()->json(['message'=>'Status Updated Successfully','success' => true]);



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
