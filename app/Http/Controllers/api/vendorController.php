<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Pagination\LengthAwarePaginator;

use App\Models\vendor_base;
use App\Models\vendorService;

use App\Http\Resources\vendorResource;

use App\Exports\vendorExport;


use Maatwebsite\Excel\Facades\Excel;

use Validator;
use JWTAuth;
use Auth;
use DB;

class vendorController extends Controller
{

    public function vendorCsv(Request $request){

        return Excel::download(new vendorExport($request->searchTerm,(int)$request->limit), 'vendors.xlsx');
    }

    public function getVendors(Request $request){

        if ($request->searchTerm != '' && $request->searchTerm) {
            $resourceRecords = vendor_base::where(DB::raw("CONCAT(`code`, ' ', `name`)"), 'LIKE', "%" . $request->searchTerm . "%")->orderBy('id','desc')->get();
        }
        else
        {
            $resourceRecords = vendor_base::orderBy('id','desc')->get();
        }
        

        $totalRows = count($resourceRecords);

        $resourceRecords = $this->paginaterhelp($request->page_no,$resourceRecords,$request);

        return vendorResource::collection($resourceRecords)->additional(['success' => true,'totalRows' => $totalRows,'message'=>'Vendors fetched.']);

    }

    public function deleteVendor(Request $request){

        $vendor = vendor_base::find($request->id);

        if(!$vendor){

            return response()->json(['message'=>'Invalid User','success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);  
        }

        $vendor->delete();

        $user = JWTAuth::user();
        $action = 'DELETED';
        $itemName = $vendor->name;
        $itemUnique = $vendor->id;
        $tableName = 'Vendors';
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);

        return response()->json(['message'=>'Vendor Deleted','success' => true,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);
        

    }

    public function addVendor(Request $request){

        //return $request->all();

        $messages = [
                       
                        'name.required'  => 'Specify Company name.',
                        'code.required'  => 'Specify Vendor Code.',
                        'cr_no.numeric'  => 'Cr Number Should be a valid Number type.',

                        'vat_no.numeric'  => 'VAT Number Should be a valid Number type.',
                        'contact_ops_no.numeric'  => 'Contact Number (Operations) Should be a valid Number type.',
                        'contact_billing_no.numeric'  => 'Contact Number (Billing) Should be a valid Number type.',

                        'contact_gov_no.numeric'  => 'Contact Number (Gov Relations) Should be a valid Number type.',
                        'cr_file.file'  => 'upload valid CR Attachment.',
                        'vat_file.file'  => 'upload valid VAT Attachment.',
                        'contract.file'  => 'upload valid Contract Attachment.',

                    ];

        $validate = Validator::make($request->all(), [

            'code'   => 'required|min:1|max:4|unique:vendor_base',
            'name'   => 'required',
            'cr_no'   => 'nullable|numeric',
            'cr_file'   => 'nullable|file',
            'vat_file'   => 'nullable|file',
            'contract'   => 'nullable|file',

            'vat_no'   => ' nullable|numeric',
            'contact_ops_no'   => 'nullable|numeric',
            'contact_billing_no'   => 'nullable|numeric',

            'contact_gov_no'   => 'nullable|numeric',

            ],$messages);


        if ($validate->fails()) {

                    return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => []], 200);            
                }

        $input = $request->all();

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

            
        }

        if ($error) {

                    return response()->json(['message'=>$tmpErrorMsg,'success' => false,'data' => []], 200);            
                }

        $next_id = vendor_base::max('id') + 1;

        $input['id'] = $next_id;

        if($request->has('cr_file'))
        {
            $input['cr_file'] = $this->img_upload($request->cr_file);
        }

        if($request->has('vat_file'))
        {
            $input['vat_file'] = $this->img_upload($request->vat_file);
        }

        if($request->has('contract'))
        {
            $input['contract'] = $this->img_upload($request->contract);
        }

        $vendor = vendor_base::create($input);

        foreach ($input['clientServices'] as $key => $service) {

            $clientService = new vendorService();

            $clientService->vendor = $input['id'];
            $clientService->name = $service['name'];
            $clientService->period = $service['period'];
            $clientService->rate = $service['rate'];
            $clientService->save();

        }

        $user = JWTAuth::user();
        $action = 'CREATED';
        $itemName = $vendor->name;
        $itemUnique = $vendor->id;
        $tableName = 'Vendors';
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);

        return response()->json(['message'=>'Vendor added successfully','success' => true,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);


    }

    public function getVendorDetail(Request $request){

        $vendor = vendor_base::find($request->id);

        if(!$vendor){

            return response()->json(['message'=>'Invalid ID','success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);  
        }

        return (new vendorResource($vendor))->additional(['success' => true,'message'=>'Vendor Detail fetched.']);
    }

    public function updateVendor(Request $request){

        $vendor= vendor_base::find($request->id);

        $messages = [
                       
                        'name.required'  => 'Specify Company name.',
                        'code.required'  => 'Specify Vendor Code.',
                        'cr_no.numeric'  => 'Cr Number Should be a valid Number type.',

                        'vat_no.numeric'  => 'VAT Number Should be a valid Number type.',
                        'contact_ops_no.numeric'  => 'Contact Number (Operations) Should be a valid Number type.',
                        'contact_billing_no.numeric'  => 'Contact Number (Billing) Should be a valid Number type.',

                        'contact_gov_no.numeric'  => 'Contact Number (Gov Relations) Should be a valid Number type.',
                        'cr_file.file'  => 'upload valid CR Attachment.',
                        'vat_file.file'  => 'upload valid VAT Attachment.',
                        'contract.file'  => 'upload valid Contract Attachment.',

                    ];

        $validate = Validator::make($request->all(), [

            'code'   => 'required|min:1|max:4|unique:vendor_base,code,'.$request->id,
            'name'   => 'required',
            'cr_no'   => 'nullable|numeric',
            'cr_file'   => 'nullable|file',
            'vat_file'   => 'nullable|file',
            'contract'   => 'nullable|file',

            'vat_no'   => ' nullable|numeric',
            'contact_ops_no'   => 'nullable|numeric',
            'contact_billing_no'   => 'nullable|numeric',

            'contact_gov_no'   => 'nullable|numeric',

            ],$messages);


        if ($validate->fails()) {

                    return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => []], 200);            
                }

        $input = $request->all();

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

            
        }

        if ($error) {

                    return response()->json(['message'=>$tmpErrorMsg,'success' => false,'data' => []], 200);            
                }

        $input['cr_file;'] = $vendor->cr_file;
        $input['vat_file'] = $vendor->vat_file;

        if($request->has('cr_file') && $request->cr_file != '' && $request->cr_file != null)
        {
            $input['cr_file'] = $this->img_upload($request->cr_file);
        }

        if($request->has('vat_file') && $request->vat_file != '' && $request->vat_file != null)
        {
            $input['vat_file'] = $this->img_upload($request->vat_file);
        }

        if($request->has('contract'))
        {
            $input['contract'] = $this->img_upload($request->contract);
        }


        $vendor->update($input);

        foreach ($vendor->vendorServices as $key => $service) {

                $service->delete();

        }

        foreach ($input['clientServices'] as $key => $service) {

            $clientService = new vendorService();

            $clientService->vendor = $vendor->id;
            $clientService->name = $service['name'];
            $clientService->period = $service['period'];
            $clientService->rate = $service['rate'];
            $clientService->save();

        }

        $user = JWTAuth::user();
        $action = 'UPDATED';
        $itemName = $vendor->name;
        $itemUnique = $vendor->id;
        $tableName = 'Vendors';
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);

        return response()->json(['message'=>'Vendor updated successfully','success' => true,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);

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
            //dd($result);
            return $result;
            
        }

        return $data;
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
