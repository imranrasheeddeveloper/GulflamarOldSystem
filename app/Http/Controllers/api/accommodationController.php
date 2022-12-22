<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Pagination\LengthAwarePaginator;

use App\Models\accommodation_base;
use App\Models\accommodationPayment;
use App\Models\accommodationBillPayment;


use App\Http\Resources\accommodationResource;
use App\Http\Resources\rentPaymentResource;
use App\Http\Resources\billPaymentResource;

use App\Exports\accommodationExport;
use App\Exports\rentPayemntExport;
use App\Exports\billPayemntExport;


use Maatwebsite\Excel\Facades\Excel;


use Illuminate\Support\Facades\Auth;

use Validator;
use JWTAuth;
use DB;
class accommodationController extends Controller
{

    public function accommodationCsv(Request $request){

        return Excel::download(new accommodationExport($request->searchTerm,(int)$request->limit), 'accommodation.xlsx');
    }

    public function getAccommodation(Request $request){

        $advanceSearch = json_decode($request->advanceSearch);

        $query = accommodation_base::query();

        $query->where(DB::raw("CONCAT(`id`, ' ', `name`)"), 'LIKE', "%" . $request->searchTerm . "%");

        /*if ($request->searchTerm != '' && $request->searchTerm) {
            $resourceRecords = accommodation_base::where(DB::raw("CONCAT(`id`, ' ', `name`)"), 'LIKE', "%" . $request->searchTerm . "%")->orderBy('due-date','ASC')->get();
        }
        else
        {
            $resourceRecords = accommodation_base::orderBy('due-date','ASC')->get();
        }*/

        if($advanceSearch->name != '' && $advanceSearch->name != null && $advanceSearch->name != 'select_value'){

                $query->where('name', 'LIKE', "%" . $advanceSearch->name . "%");
            }


        if($advanceSearch->location != '' && $advanceSearch->location != null && $advanceSearch->location != 'select_value'){

                $query->where('location', 'LIKE', "%" . $advanceSearch->location . "%");
            }

        if($advanceSearch->address != '' && $advanceSearch->address != null && $advanceSearch->address != 'select_value'){

                $query->where('address', 'LIKE', "%" . $advanceSearch->address . "%");
            }
        if($advanceSearch->rooms != '' && $advanceSearch->rooms != null && $advanceSearch->rooms != 'select_value'){

                $query->where('rooms', 'LIKE', "%" . $advanceSearch->rooms . "%");
            }
         if($advanceSearch->beds != '' && $advanceSearch->beds != null && $advanceSearch->beds != 'select_value'){

                $query->where('beds', 'LIKE', "%" . $advanceSearch->beds . "%");
            }
        if($advanceSearch->contract_startdate != '' && $advanceSearch->contract_startdate != null && $advanceSearch->contract_startdate != 'select_value'){

                $query->where('contract_startdate', 'LIKE', "%" . $advanceSearch->contract_startdate . "%");
            }
        if($advanceSearch->contract_enddate != '' && $advanceSearch->contract_enddate != null && $advanceSearch->contract_enddate != 'select_value'){

                $query->where('contract_enddate', 'LIKE', "%" . $advanceSearch->contract_enddate . "%");
            }
        if($advanceSearch->rent != '' && $advanceSearch->rent != null && $advanceSearch->rent != 'select_value'){

                $query->where('rent',$advanceSearch->rent);
            }
        if($advanceSearch->services != '' && $advanceSearch->services != null && $advanceSearch->services != 'select_value'){

                if (!empty($advanceSearch->services)) {
                    $services = implode(",",$advanceSearch->services);
                }

                $query->where('services', 'LIKE', "%" . $services . "%");
            }
        if($advanceSearch->dueDate != '' && $advanceSearch->dueDate != null && $advanceSearch->dueDate != 'select_value'){

                $query->where('due-date', 'LIKE', "%" . $advanceSearch->dueDate . "%");
            }
        if($advanceSearch->period != '' && $advanceSearch->period != null && $advanceSearch->period != 'select_value'){

                $query->where('period', 'LIKE', "%" . $advanceSearch->period . "%");
            }
        if($advanceSearch->apartment != '' && $advanceSearch->apartment != null && $advanceSearch->apartment != 'select_value'){

                $query->where('apartment', 'LIKE', "%" . $advanceSearch->apartment . "%");
            }
        if($advanceSearch->electricityAccountNo != '' && $advanceSearch->electricityAccountNo != null && $advanceSearch->electricityAccountNo != 'select_value'){

                $query->where('electricityAccountNo', 'LIKE', "%" . $advanceSearch->electricityAccountNo . "%");
            }
        if($advanceSearch->waterBillAccountNo != '' && $advanceSearch->waterBillAccountNo != null && $advanceSearch->waterBillAccountNo != 'select_value'){

                $query->where('waterBillAccountNo', 'LIKE', "%" . $advanceSearch->waterBillAccountNo . "%");
            }
        if($advanceSearch->contactName != '' && $advanceSearch->contactName != null && $advanceSearch->contactName != 'select_value'){

                $query->where('contactName', 'LIKE', "%" . $advanceSearch->contactName . "%");
            }
        if($advanceSearch->contactNo != '' && $advanceSearch->contactNo != null && $advanceSearch->contactNo != 'select_value'){

                $query->where('contactNo', 'LIKE', "%" . $advanceSearch->contactNo . "%");
            }
        if($advanceSearch->accountName != '' && $advanceSearch->accountName != null && $advanceSearch->accountName != 'select_value'){

                $query->where('accountName', 'LIKE', "%" . $advanceSearch->accountName . "%");
            }
        if($advanceSearch->bankName != '' && $advanceSearch->bankName != null && $advanceSearch->bankName != 'select_value'){

                $query->where('bankName', 'LIKE', "%" . $advanceSearch->bankName . "%");
            }
        if($advanceSearch->iban != '' && $advanceSearch->iban != null && $advanceSearch->iban != 'select_value'){

                $query->where('iban', 'LIKE', "%" . $advanceSearch->iban . "%");
            }
        if($advanceSearch->fixedElectricityAmount != '' && $advanceSearch->fixedElectricityAmount != null && $advanceSearch->fixedElectricityAmount != 'select_value'){

                $query->where('fixedElectricityAmount', 'LIKE', "%" . $advanceSearch->fixedElectricityAmount . "%");
            }
        if($advanceSearch->fixedWaterAmount != '' && $advanceSearch->fixedWaterAmount != null && $advanceSearch->fixedWaterAmount != 'select_value'){

                $query->where('fixedWaterAmount', 'LIKE', "%" . $advanceSearch->fixedWaterAmount . "%");
            }
        if($advanceSearch->notes != '' && $advanceSearch->notes != null && $advanceSearch->notes != 'select_value'){

                $query->where('notes', 'LIKE', "%" . $advanceSearch->notes . "%");
            }
           

        
        $resourceRecords = $query->orderBy('due-date','ASC')->get();
        $totalRows = count($resourceRecords);

        $resourceRecords = $this->paginaterhelp($request->page_no,$resourceRecords,$request);

        return accommodationResource::collection($resourceRecords)->additional(['success' => true,'totalRows' => $totalRows,'message'=>'Accommodations fetched.']);

    }

    public function addAccommodation(Request $request){

        

        $messages = [
                       
                        'name.required'  => 'Specify Accommodation name.',
                        'fixedElectricityAmount.max'  => 'Electricity Fix Amount out of range.',
                        'fixedWaterAmount.max'  => 'Water Fix Amount out of range.',
                        'rent.max'  => 'Rent Amount out of range.',
                        'beds.max'  => 'Beds value out of range.',
                        'electricityAccountNo.max'  => 'Electricity Account no too long.',
                        'waterBillAccountNo.max'  => 'Water Bill Account no too long.',

                    ];

        $validate = Validator::make($request->all(), [

            'name'   => 'required',
            'electricityAccountNo'   => 'nullable|numeric|max:999999999999999',
            'waterBillAccountNo'   => 'nullable|numeric|max:999999999999999',
            'contactNo'   => 'nullable|max:12',
            'iban'   => 'nullable|max:24',
            'fixedElectricityAmount'   => 'nullable|numeric|max:9999999999',
            'fixedWaterAmount'   => 'nullable|numeric|max:9999999999',
            'rent'   => 'nullable|numeric|max:9999999999',
            'beds'   => 'nullable|numeric|max:9999999999',
            'coordinates' => 'required'
            ],$messages);


        if ($validate->fails()) {

                    return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => []], 200);            
                }
        //return $request->all();

        $next_id = accommodation_base::max('id') + 1;
        $request->request->add(['id' => $next_id]);
        $input = $request->all();

        $input['services'] = implode(",",$request->services);
        $input['due-date'] = $request->dueDate;
        //dd($input);
        $input['created_by'] = Auth::user()->name;
        $input['updated_by'] = null;
        $coordinates =  $input['coordinates'];
        $coordinatesArray = explode(',', $coordinates);
        
        $input['latitude'] = $coordinatesArray[0];
        $input['longitude'] = $coordinatesArray[1];
        $input['latitude'] = preg_replace('/\s+/', '', $coordinatesArray[0]);
        $input['longitude'] = preg_replace('/\s+/', '', $coordinatesArray[1]);
        $accommodation = accommodation_base::create($input);

        $user = JWTAuth::user();
        $action = 'CREATED';
        $itemName = $accommodation->name;
        $itemUnique = $accommodation->id;
        $tableName = 'Accommodations';
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);

        return response()->json(['message'=>'Accommodation added successfully','success' => true,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);


    }

    
    public function getAccommodationDetail(Request $request){

        $accommodation = accommodation_base::find($request->id);

        if(!$accommodation){

            return response()->json(['message'=>'Invalid Entry','success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);  
        }

        
        //dd(new accommodationResource($accommodation));
        return (new accommodationResource($accommodation))->additional(['success' => true,'message'=>'Accommodation Details fetched.']);

    }

    public function updateAccommodation(Request $request){

        $accommodation = accommodation_base::find($request->id);

        if(!$accommodation){

            return response()->json(['message'=>'Invalid Entry','success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);  
        }

        $messages = [
                       
                        'name.required'  => 'Specify Accommodation name.',
                        'fixedElectricityAmount.max'  => 'Electricity Fix Amount out of range.',
                        'fixedWaterAmount.max'  => 'Water Fix Amount out of range.',
                        'rent.max'  => 'Rent Amount out of range.',
                        'beds.max'  => 'Beds value out of range.',
                        'electricityAccountNo.max'  => 'Electricity Account no too long.',
                        'waterBillAccountNo.max'  => 'Water Bill Account no too long.',

                    ];

        $validate = Validator::make($request->all(), [

            'name'   => 'required',
            'electricityAccountNo'   => 'nullable|numeric|max:999999999999999',
            'waterBillAccountNo'   => 'nullable|numeric|max:999999999999999',
            'contactNo'   => 'nullable|max:12',
            'iban'   => 'nullable|max:24',
            'fixedElectricityAmount'   => 'nullable|numeric|max:9999999999',
            'fixedWaterAmount'   => 'nullable|numeric|max:9999999999',
            'rent'   => 'nullable|numeric|max:9999999999',
            'beds'   => 'nullable|numeric|max:9999999999',

            ],$messages);


        if ($validate->fails()) {

                    return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => []], 200);            
                }



        $input = $request->all();

        if ($request->has('services')) {
            
            $input['services'] = implode(",",$request->services);
        }
        
        if ($request->has('dueDate')) {
            
            $input['due-date'] = $request->dueDate;
        }


        $input['updated_by'] = Auth::user()->name;

        $coordinates =  $input['coordinates'];
        $coordinatesArray = explode(',', $coordinates);
        
        $input['latitude'] = $coordinatesArray[0];
        $input['longitude'] = $coordinatesArray[1];
        $input['latitude'] = preg_replace('/\s+/', '', $coordinatesArray[0]);
        $input['longitude'] = preg_replace('/\s+/', '', $coordinatesArray[1]);

        $accommodation->update($input);

        //dd($accommodation);

        $user = JWTAuth::user();
        $action = 'UPDATED';
        $itemName = $accommodation->name;
        $itemUnique = $accommodation->id;
        $tableName = 'Accommodations';
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);


        return response()->json(['message'=>'Accommodation Updated','success' => true,'data' => []], 201);

    }

    public function deleteAccommodation(Request $request){

        $accommodation = accommodation_base::find($request->id);

        if(!$accommodation){

            return response()->json(['message'=>'Invalid Entry','success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);  
        }
        $error = false;
        if($accommodation->employee)
        {
            $error = true;
            $errorMsg = 'Cannot Delete! Assigned to employee(s)';
        }
        
        if($accommodation->resourceAllocations)
        {
            $error = true;
            $errorMsg = 'Cannot Delete! Assigned to resource Center';
        }
        
        if($accommodation->payments)
        {
            $error = true;
            $errorMsg = 'Cannot Delete! Assigned to Rent Payment(s)';
        }
        
        if($accommodation->employee)
        {
            $error = true;
            $errorMsg = 'Cannot Delete! Assigned to Bill Payment(s)';
        }
        
        if($error){

            return response()->json(['message'=>$errorMsg,'success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);  
        }

        $accommodation->delete();

        $user = JWTAuth::user();
        $action = 'DELETED';
        $itemName = $accommodation->name;
        $itemUnique = $accommodation->id;
        $tableName = 'Accommodations';
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);

        return response()->json(['message'=>'Deleted Successfully','success' => true,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);

    }

    public function addAccommodationPayment(Request $request){


        $messages = [
                       
                        'accommodation_base_id.required'  => 'Specify Accommodation for payment.',
                        'amount.required'  => 'Specify payment amount.',
                        'date.required'  => 'Specify date of payment.',

                        'periodFrom.required'  => 'Specify payment period.',
                        'periodTo.required'  => 'Specify payment period.',

                    ];

        $validate = Validator::make($request->all(), [

            'accommodation_base_id'   => 'required',
            'amount'   => 'required|numeric|max:999999999999',
            

            'periodFrom'   => 'required',
            'periodTo'   => 'required',
            'date'   => 'required',

            ],$messages);


        if ($validate->fails()) {

                    return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => []], 200);            
                }

        //dd($request->all());
        $input['created_by'] = Auth::user()->name;
        $input['updated_by'] = null;
        $accommodationPayment = accommodationPayment::create($request->all());

        $accommodation = accommodation_base::find($request->accommodation_base_id);
        $input_date['due-date'] = $request->periodTo;

        $accommodation->update($input_date);

        $user = JWTAuth::user();
        $action = 'CREATED';
        $itemName = 'New rent payment';
        $itemUnique = $accommodationPayment->id;
        $tableName = 'Accommodations payments';
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);

        return response()->json(['message'=>'Accommodation payment added successfully','success' => true,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);

    }

    public function rentPaymentCsv(Request $request){

        return Excel::download(new rentPayemntExport($request->searchTerm,(int)$request->limit,(int)$request->id), 'accommodationPayment.xlsx');
    }

    public function getAccommodationPayment(Request $request){

        if ($request->has('id')) {
        
            $accommodation = accommodation_base::find($request->id);

            if(!$accommodation){

                return response()->json(['message'=>'Invalid Entry','success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);  
            }

            if ($request->searchTerm != '' && $request->searchTerm) {

                $payments = $accommodation->payments()->where(DB::raw("CONCAT(`id`, ' ', `accommodation_base_id`)"), 'LIKE', "%" . $request->searchTerm . "%")->orWhereHas('accommodations', function( $query ) use ( $request ){

                      $query->where(DB::raw("CONCAT(`id`, ' ', `name`)"), 'LIKE', "%" . $request->searchTerm . "%");

                  })->latest()->get();
            }
            else
            {
                $payments = $accommodation->payments;
            }
        }
        else
        {
            if ($request->searchTerm != '' && $request->searchTerm) {

                    $payments = accommodationPayment::where(DB::raw("CONCAT(`id`, ' ', `accommodation_base_id`)"), 'LIKE', "%" . $request->searchTerm . "%")->orWhereHas('accommodations', function( $query ) use ( $request ){

                      $query->where(DB::raw("CONCAT(`id`, ' ', `name`)"), 'LIKE', "%" . $request->searchTerm . "%");

                  })->latest()->get();
            }
            else
            {
                $payments = accommodationPayment::latest()->get();
            }
        }

        $totalRows = count($payments);

        $resourceRecords = $this->paginaterhelp($request->page_no,$payments,$request);

        return rentPaymentResource::collection($resourceRecords)->additional(['success' => true,'totalRows' => $totalRows,'message'=>'Accommodations Payments fetched.']);

        
        


    }

    public function getPaymentDetails(Request $request){

        $accommodationPayment = accommodationPayment::find($request->id);

        if(!$accommodationPayment){

            return response()->json(['message'=>'Invalid Entry','success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);  
        }

        
        //dd(new accommodationResource($accommodation));
        return (new rentPaymentResource($accommodationPayment))->additional(['success' => true,'message'=>'Accommodation Payment Details fetched.']);

    }

    public function updateAccommodationPayment(Request $request){

        $accommodationPayment = accommodationPayment::find($request->id);

        if(!$accommodationPayment){

            return response()->json(['message'=>'Invalid Entry','success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);  
        }

        $messages = [
                       
                        'accommodation_base_id.required'  => 'Specify Accommodation for payment.',
                        'amount.required'  => 'Specify payment amount.',
                        'date.required'  => 'Specify date of payment.',

                        'periodFrom.required'  => 'Specify payment period.',
                        'periodTo.required'  => 'Specify payment period.',

                    ];

        $validate = Validator::make($request->all(), [

            'accommodation_base_id'   => 'required',
            'amount'   => 'required|numeric|max:999999999999',
            

            'periodFrom'   => 'required',
            'periodTo'   => 'required',
            'date'   => 'required',

            ],$messages);


        if ($validate->fails()) {

                    return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => []], 200);            
                }


        $input = $request->all();

        if ($input['newAttachment'] != '') {
            $input['attachment'] = $input['newAttachment'];
        }
        $input['updated_by'] = Auth::user()->name;
        $accommodationPayment->update($input);


        $user = JWTAuth::user();
        $action = 'UPDATED';
        $itemName = 'Rent payment';
        $itemUnique = $accommodationPayment->id;
        $tableName = 'Accommodations payments';
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);


        return response()->json(['message'=>'Rent Payment Updated','accommodationId' => $accommodationPayment->accommodations->id,'success' => true,'data' => []], 201);

    }

    public function deleteAccommodationPayment(Request $request){

        $accommodationPayment = accommodationPayment::find($request->id);

        if(!$accommodationPayment){

            return response()->json(['message'=>'Invalid Entry','success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);  
        }

        $accommodationPayment->delete();

        $user = JWTAuth::user();
        $action = 'DELETED';
        $itemName = 'Rent payment';
        $itemUnique = $accommodationPayment->id;
        $tableName = 'Accommodations payments';
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);


        return response()->json(['message'=>'Deleted Successfully','success' => true,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);

    }

    public function addAccommodationBillPayment(Request $request){


        $messages = [
                       
                        'accommodation_base_id.required'  => 'Specify Accommodation for payment.',
                        'amount.required'  => 'Specify payment amount.',
                        'billType.required'  => 'Specify bill type.',

                        'billMonth.required'  => 'Specify bill period.',

                    ];

        $validate = Validator::make($request->all(), [

            'accommodation_base_id'   => 'required',
            'amount'   => 'required|numeric|max:999999999999',
            
            'date' => 'required',
            'billType'   => 'required',
            'billMonth'   => 'required',

            ],$messages);


        if ($validate->fails()) {

                    return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => []], 200);            
                }

        $input['created_by'] = Auth::user()->name;
        $input['updated_by'] = null;
        $accommodationBillPayment = accommodationBillPayment::create($request->all());

        $user = JWTAuth::user();
        $action = 'CREATED';
        $itemName = 'NEW Bill payment';
        $itemUnique = $accommodationBillPayment->id;
        $tableName = 'Accommodations Bill payments';
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);


        return response()->json(['message'=>'Accommodation Bill payment added successfully','success' => true,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);

    }

    public function billPaymentCsv(Request $request){

        return Excel::download(new billPayemntExport($request->searchTerm,(int)$request->limit,(int)$request->id), 'accommodationBillPayment.xlsx');
    }

    public function getAccommodationBillPayment(Request $request){

        if ($request->has('id')) {

            $accommodation = accommodation_base::find($request->id);

            if(!$accommodation){

                return response()->json(['message'=>'Invalid Entry','success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);  
            }

            //$payments = $accommodation->billPayments;
            if ($request->searchTerm != '' && $request->searchTerm) {

                $payments = $accommodation->billPayments()->where(DB::raw("CONCAT(`id`, ' ', `accommodation_base_id`)"), 'LIKE', "%" . $request->searchTerm . "%")->orWhereHas('accommodations', function( $query ) use ( $request ){

                      $query->where(DB::raw("CONCAT(`id`, ' ', `name`)"), 'LIKE', "%" . $request->searchTerm . "%");

                  })->latest()->get();
            }
            else
            {
                $payments = $accommodation->billPayments;
            }

        }
        else
        {
            //$payments = accommodationBillPayment::latest()->get();

            if ($request->searchTerm != '' && $request->searchTerm) {

                    $payments = accommodationBillPayment::where(DB::raw("CONCAT(`id`, ' ', `accommodation_base_id`)"), 'LIKE', "%" . $request->searchTerm . "%")->orWhereHas('accommodations', function( $query ) use ( $request ){

                      $query->where(DB::raw("CONCAT(`id`, ' ', `name`)"), 'LIKE', "%" . $request->searchTerm . "%");

                  })->latest()->get();
            }
            else
            {
                $payments = accommodationBillPayment::latest()->get();
            }
        }

        $totalRows = count($payments);

        $resourceRecords = $this->paginaterhelp($request->page_no,$payments,$request);

        return billPaymentResource::collection($resourceRecords)->additional(['success' => true,'totalRows' => $totalRows,'message'=>'Accommodations Bill Payments fetched.']);

        
        


    }

    public function getBillPaymentDetails(Request $request){

        $accommodationBillPayment = accommodationBillPayment::find($request->id);

        if(!$accommodationBillPayment){

            return response()->json(['message'=>'Invalid Entry','success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);  
        }

        
        //dd(new accommodationResource($accommodation));
        return (new billPaymentResource($accommodationBillPayment))->additional(['success' => true,'message'=>'Accommodation Bill Payment Details fetched.']);

    }

    public function updateAccommodationBillPayment(Request $request){

        $accommodationBillPayment = accommodationBillPayment::find($request->id);

        if(!$accommodationBillPayment){

            return response()->json(['message'=>'Invalid Entry','success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);  
        }

        $messages = [
                       
                        'accommodation_base_id.required'  => 'Specify Accommodation for payment.',
                        'amount.required'  => 'Specify payment amount.',
                        'billType.required'  => 'Specify bill type.',

                        'billMonth.required'  => 'Specify bill period.',

                    ];

        $validate = Validator::make($request->all(), [

            'accommodation_base_id'   => 'required',
            'amount'   => 'required|numeric|max:999999999999',
            
            'date' => 'required',
            'billType'   => 'required',
            'billMonth'   => 'required',

            ],$messages);


        if ($validate->fails()) {

                    return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => []], 200);            
                }
                

        $input = $request->all();

        if ($input['newAttachment'] != '') {
            $input['attachment'] = $input['newAttachment'];
        }
        
        $input['updated_by'] = Auth::user()->name;
        
        $accommodationBillPayment->update($input);

        $user = JWTAuth::user();
        $action = 'UPDATED';
        $itemName = 'Bill payment';
        $itemUnique = $accommodationBillPayment->id;
        $tableName = 'Accommodations Bill payments';
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);


        return response()->json(['message'=>'Bill Payment Updated','accommodationId' => $accommodationBillPayment->accommodations->id,'success' => true,'data' => []], 201);

    }

    public function deleteAccommodationBillPayment(Request $request){

        $accommodationBillPayment = accommodationBillPayment::find($request->id);

        if(!$accommodationBillPayment){

            return response()->json(['message'=>'Invalid Entry','success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);  
        }

        $accommodationBillPayment->delete();

        $user = JWTAuth::user();
        $action = 'DELETED';
        $itemName = 'Bill payment';
        $itemUnique = $accommodationBillPayment->id;
        $tableName = 'Accommodations Bill payments';
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);
        return response()->json(['message'=>'Deleted Successfully','success' => true,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);

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
