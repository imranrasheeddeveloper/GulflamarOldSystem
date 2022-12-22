<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Pagination\LengthAwarePaginator;

use App\Models\client_base;
use App\Models\clientService;

use App\Http\Resources\clientResource;

use App\Exports\clientExport;
use App\Models\ClientLocation;
use Maatwebsite\Excel\Facades\Excel;

use Validator;
use JWTAuth;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Support\Facades\DB as FacadesDB;

class clientController extends Controller
{

    public function clientCsv(Request $request){

        return Excel::download(new clientExport($request->searchTerm,(int)$request->limit), 'client.xlsx');
    }

    public function getClients(Request $request){

        if ($request->searchTerm != '' && $request->searchTerm) {
            $resourceRecords = client_base::where(DB::raw("CONCAT(`client_code`, ' ', `client_name`)"), 'LIKE', "%" . $request->searchTerm . "%")->orderBy('client_code','desc')->get();
        }
        else
        {
            $resourceRecords = client_base::orderBy('client_code','desc')->get();
        }
        

        $totalRows = count($resourceRecords);

        $resourceRecords = $this->paginaterhelp($request->page_no,$resourceRecords,$request);

        return clientResource::collection($resourceRecords)->additional(['success' => true,'totalRows' => $totalRows,'message'=>'Clients fetched.']);

    } 


    public function getClientLocations(Request $request){
       $location =  ClientLocation ::select(DB::raw('id AS value'),'location_name as text')->where('client_code', $request->client_code)->get();
       return response()->json(['message'=>'Client Locations Fetched','success' => true,'data' => json_decode(json_encode($location))]);
    }

    public function createClient(Request $request){

        $messages = [
                       
                        'client_name.required'  => 'Specify Client name.',
                        'cr_no.numeric'  => 'Cr Number Should be a valid Number type.',

                        'vat_no.numeric'  => 'VAT Number Should be a valid Number type.',
                        'cr.file'  => 'upload valid CR Attachment.',
                        'vat_cert.file'  => 'upload valid VAT Attachment.',
                        'contract.file'  => 'upload valid Contract Attachment.',

                    ];

        $validate = Validator::make($request->all(), [
            'client_name'   => 'required|unique:client_base',
            'cr_no'   => 'nullable|numeric',
            'cr'   => 'nullable|file',
            'vat_cert'   => 'nullable|file',
            'vat_no'   => ' nullable|numeric',
            'contract'   => ' nullable|file',

            ],$messages);


        if ($validate->fails()) {

                    return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => []], 200);            
                }

        $input =  $request->all();

        if (array_key_exists('services', $input)) {
            $input['services'] = json_decode($input['services']);

            $input['clientServices'] =  $this->convertObject($input['services']);

            $input['services'] = '';


        }
        else
        {
            $input['clientServices'] = [];
            $input['services'] = '';
        }


        //Client Locations
        
        if (array_key_exists('location', $input)) {
            $input['location'] = json_decode($input['location']);
            $input['clientlocations'] =  $this->convertObject($input['location']);
            $input['services'] = '';
        }
        else
        {
            $input['clientlocations'] = [];
            $input['location'] = '';
        }
        $error = false;

        foreach ($input['clientlocations'] as $key => $service) {

            if (!$error) {

                if (empty(trim($service['location_name']))) {

                    $error = true;

                    $tmpErrorMsg = 'Location Name Cannot Be empty.';
                    // code...
                }
                 if (empty(trim($service['coordinates']))) {

                    $error = true;

                    $tmpErrorMsg = 'Service Period Cannot Be empty.';
                    // code...
                }
            }
        }
   
        foreach ($input['clientServices'] as $key => $service) {

            if (!$error) {

                if (empty(trim($service['name']))) {

                    $error = true;

                    $tmpErrorMsg = 'Service Name Cannot Be empty.';
                    // code...
                }

                if (empty(trim($service['period']))) {

                    $error = true;

                    $tmpErrorMsg = 'Service Period Cannot Be empty.';
                    // code...
                }

                if (empty(trim($service['rate']))) {

                    $error = true;

                    $tmpErrorMsg = 'Service Rate Cannot Be empty.';
                    // code...
                }
            }  
        }

        if ($error) {
        return response()->json(['message'=>$tmpErrorMsg,'success' => false,'data' => []], 200);            
        }

        $next_id = client_base::max('client_code') + 1;
         $input['client_code'] = $next_id;

        if($request->has('fat_details') && $request->fat_details != '' && $request->fat_details != null)
        {
            $input['fat_details'] = implode(',',json_decode($input['fat_details']));
            //dd($input);
        }
        
        if($request->has('cr') && $request->cr != '' && $request->cr != null)
        {
            $input['cr'] = $this->img_upload($request->cr);
            //dd($input);
        }

        if($request->has('vat_cert') && $request->vat_cert != '' && $request->vat_cert != null)
        {
            $input['vat_cert'] = $this->img_upload($request->vat_cert);
        }

        if($request->has('contract') && $request->contract != '' && $request->contract != null)
        {
            $input['contract'] = $this->img_upload($request->contract);
        }

        if($request->has('daysWeek'))
        {
            $input['days-week'] = $request->daysWeek;
        }

        if($request->has('hoursDay'))
        {
            $input['hours-day'] = $request->hoursDay;
        }
        $input['created_by'] = Auth::user()->name;
        $input['updated_by'] = null;
        $client = client_base::create($input);

        foreach ($input['clientServices'] as $key => $service) {

            $clientService = new clientService();
            $clientService->client = $input['client_code'];
            $clientService->name = $service['name'];
            $clientService->period = $service['period'];
            $clientService->rate = $service['rate'];
            $clientService->save();

        }

        foreach ($input['clientlocations'] as $key => $location) {
            
             $coordinates =  $location['coordinates'];
             $coordinatesArray = explode(',', $coordinates);
             $coordinatess['latitude'] = $coordinatesArray[0];
             $coordinatess['longitude'] = $coordinatesArray[1];
             $coordinatess['latitude'] = preg_replace('/\s+/', '', $coordinatesArray[0]);
             $coordinatess['longitude'] = preg_replace('/\s+/', '', $coordinatesArray[1]);    


             $latitude =  $coordinatess['latitude'];
             $longitude = $coordinatess['longitude'];
            // location_map
            $link = "https://www.google.com/maps/search/".$latitude.",".$longitude."/@".$latitude.",".$longitude."z";
           
            ClientLocation::create([
            'client_code' => $input['client_code'],
            'location_name' => $location['location_name'],
            'latitude' => $coordinatess['latitude'],
            'longitude' => $coordinatess['longitude'],
            'location_map' => $link

        ]);
         }

        $user = JWTAuth::user();
        $action = 'CREATED';
        $itemName = $client->client_name;
        $itemUnique = $next_id;
        $tableName = 'Clients';
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);
        
        return response()->json(['message'=>'Client Created','success' => true,'data' => $input]);
        

    }

    public function getClientDetail(Request $request){

        $client = client_base::where('client_code',$request->client_code)->first();

        if(!$client){

            return response()->json(['message'=>'Invalid ID','success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);  
        }
        return (new clientResource($client))->additional(['success' => true,'message'=>'Client Detail fetched.']);
    }

    public function updateClient(Request $request){

        $client = client_base::where('client_code',$request->client_code)->first();

        if(!$client){

            return response()->json(['message'=>'Invalid Entry','success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);  
        }

        
        $messages = [
                       
                        'client_name.required'  => 'Specify Client name.',
                        'cr_no.numeric'  => 'Cr Number Should be a valid Number type.',

                        'vat_no.numeric'  => 'VAT Number Should be a valid Number type.',
                        'cr.file'  => 'upload valid CR Attachment.',
                        'vat_cert.file'  => 'upload valid VAT Attachment.',
                        'contract.file'  => 'upload valid Contract Attachment.',

                    ];

        $validate = Validator::make($request->all(), [
            'client_name'   => 'required|unique:client_base,client_name,'.$request->client_code.',client_code',
            'cr_no'   => 'nullable|numeric',
            'cr'   => 'nullable|file',
            'vat_cert'   => 'nullable|file',

            'vat_no'   => ' nullable|numeric',
            'contract'   => ' nullable|file',

            ],$messages);


        if ($validate->fails()) {

                    return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => []], 200);            
                }

        $input =  $request->all();

        if (array_key_exists('services', $input)) {
            $input['services'] = json_decode($input['services']);

            $input['clientServices'] =  $this->convertObject($input['services']);

            $input['services'] = '';


        }
        else
        {
            $input['clientServices'] = [];
            $input['services'] = '';
        }
          //Client locations
        if (array_key_exists('location', $input)) {
            $input['location'] = json_decode($input['location']);

            $input['clientlocations'] =  $this->convertObject($input['location']);

            $input['services'] = '';
        }
        else
        {
            $input['clientlocations'] = [];
            $input['location'] = '';
        }
        $error = false;

        foreach ($input['clientServices'] as $key => $service) {

            if (!$error) {

                if (empty(trim($service['name']))) {

                    $error = true;

                    $tmpErrorMsg = 'Service Name Cannot Be empty.';
                    // code...
                }

                if (empty(trim($service['period']))) {

                    $error = true;

                    $tmpErrorMsg = 'Service Period Cannot Be empty.';
                    // code...
                }

                if (empty(trim($service['rate']))) {

                    $error = true;

                    $tmpErrorMsg = 'Service Rate Cannot Be empty.';
                    // code...
                }
            }
            foreach ($input['clientlocations'] as $key => $service) {

                if (!$error) {
    
                    if (empty(trim($service['location_name']))) {
    
                        $error = true;
    
                        $tmpErrorMsg = 'Location Name Cannot Be empty.';
                        // code...
                    }
                }
    
                
            }
            
        }

        if ($error) {

                    return response()->json(['message'=>$tmpErrorMsg,'success' => false,'data' => []], 200);            
                }

        

        if($request->has('fat_details') && $request->fat_details != '' && $request->fat_details != null)
        {
            $input['fat_details'] = implode(',',json_decode($input['fat_details']));
            //dd($input);
        }
        
        if($request->has('cr') && $request->cr != '' && $request->cr != null)
        {
            $input['cr'] = $this->img_upload($request->cr);
            //dd($input);
        }

        if($request->has('vat_cert') && $request->vat_cert != '' && $request->vat_cert != null)
        {
            $input['vat_cert'] = $this->img_upload($request->vat_cert);
        }

        if($request->has('contract') && $request->contract != '' && $request->contract != null)
        {
            $input['contract'] = $this->img_upload($request->contract);
        }

        if($request->has('daysWeek'))
        {
            $input['days-week'] = $request->daysWeek;
        }

        if($request->has('hoursDay'))
        {
            $input['hours-day'] = $request->hoursDay;
        }

        //dd($input['clientServices']);
        $input['updated_by'] = Auth::user()->name;

        $client->update($input);
        

        
        ($input['clientlocations']);
        foreach ($client->clientServices as $key => $service) {

                $service->delete();

        }
        DB::table('client_locations')->where('client_code', $input['client_code'])->delete();

    
        foreach ($input['clientServices'] as $key => $service) {

            $clientService = new clientService();

            $clientService->client = $client->client_code;
            $clientService->name = $service['name'];
            $clientService->period = $service['period'];
            $clientService->rate = $service['rate'];
            $clientService->save();

        }

        foreach ($input['clientlocations'] as $key => $location) {
            
             $coordinates =  $location['coordinates'];
             $coordinatesArray = explode(',', $coordinates);
             $coordinatess['latitude'] = $coordinatesArray[0];
             $coordinatess['longitude'] = $coordinatesArray[1];
             $coordinatess['latitude'] = preg_replace('/\s+/', '', $coordinatesArray[0]);
             $coordinatess['longitude'] = preg_replace('/\s+/', '', $coordinatesArray[1]);    


             $latitude =  $coordinatess['latitude'];
             $longitude = $coordinatess['longitude'];
            // location_map
            $link = "https://www.google.com/maps/search/".$latitude.",".$longitude."/@".$latitude.",".$longitude."z";
           
            ClientLocation::create([
            'client_code' => $input['client_code'],
            'location_name' => $location['location_name'],
            'latitude' => $coordinatess['latitude'],
            'longitude' => $coordinatess['longitude'],
            'location_map' => $link

        ]);
         }

        // foreach ($input['clientlocations'] as $key => $location) {
        // ClientLocation::create([
        //     'client_code' => $input['client_code'],
        //     'location_name' => $location['location_name'],
        // ]);
   // }
        $user = JWTAuth::user();
        $action = 'UPDATED';
        $itemName = $client->client_name;
        $itemUnique = $client->client_code;
        $tableName = 'Clients';
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);


        return response()->json(['message'=>'Client Updated','success' => true,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);


    }

    public function deleteClient(Request $request){

        $client = client_base::where('client_code',$request->client_code)->first();

        if(!$client){

            return response()->json(['message'=>'Invalid ID','success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);  
        }

        $client->delete();

        $user = JWTAuth::user();
        $action = 'DELETED';
        $itemName = $client->client_name;
        $itemUnique = $client->client_code;
        $tableName = 'Clients';
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);
        return response()->json(['message'=>'Client Deleted','success' => true,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);
        

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

        $image-> storeAs('image' ,$file_namefor_db,'public');
        $pic = ('storage/image/' . $file_namefor_db);

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

    function convertObject($data){

        if (is_array($data) || is_object($data))
        {
            $result = [];
            foreach ($data as $key => $value)
            {
                $tmpResult = [];
                foreach ($value as $tmpKey => $tmp)
                {
                    $tmpResult[$tmpKey] =  $tmp;
                }

                $result[$key] =  $tmpResult;
            }
            //
            ($result);
            return $result;
            
        }

        return $data;
    }
}
