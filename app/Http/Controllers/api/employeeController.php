<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\employeeResource;
use App\Http\Resources\singleEmployeeResource;

use Illuminate\Pagination\LengthAwarePaginator;


use App\Models\employee;
use App\Models\vendor_base;
use App\Models\client_base;
use App\Models\accommodation_base;
use App\Models\profession_base;

use App\Http\Requests\addEmployeeValidate;

use App\Models\projectResource;
use App\Models\accommodationResource;
use App\Models\employeeResource AS employeeItemResource;

use App\Exports\employeeExport;
use App\Models\ClientLocation;
use Illuminate\Support\Facades\Auth;


use Maatwebsite\Excel\Facades\Excel;

use Carbon\Carbon;
use Validator;
use JWTAuth;
use DB;

class employeeController extends Controller
{

    public function employeeCsv(Request $request){

        //dd($request->all());
        return Excel::download(new employeeExport($request->searchTerm,(int)$request->limit,$request->iqama_expiry_date,$request->vendor,$request->client,$request->status), 'employee.xlsx');
    }
    public function getemployee(Request $request){


            /*$userLevels = employee::where(DB::raw("CONCAT(`name`, ' ', `emp_id`, ' ', `contact_number`, ' ',`nationality`)"), 'LIKE', "%" . $request->searchTerm . "%")->get();

            $totalRows = count(employee::where(DB::raw("CONCAT(`name`, ' ', `emp_id`, ' ', `contact_number`, ' ',`nationality`)"), 'LIKE', "%" . $request->searchTerm . "%")->get());*/

            $advanceSearch = json_decode($request->advanceSearch);

            //dd($advanceSearch);
            $query = employee::query();

            $query->where(DB::raw("CONCAT(`name`, ' ', `emp_id`)"), 'LIKE', "%" . $request->searchTerm . "%");

            if($advanceSearch->name != '' && $advanceSearch->name != null && $advanceSearch->name != 'select_value'){

                $query->where('name', 'LIKE', "%" . $advanceSearch->name . "%");
            }

            if($advanceSearch->emp_id != '' && $advanceSearch->emp_id != null && $advanceSearch->emp_id != 'select_value'){

                $query->where('emp_id', 'LIKE', "%" . $advanceSearch->emp_id . "%");
            }

            if($advanceSearch->nationality != '' && $advanceSearch->nationality != null && $advanceSearch->nationality != 'select_value'){

                $query->where('nationality', 'LIKE', "%" . $advanceSearch->nationality . "%");
            }

            if($advanceSearch->religion != '' && $advanceSearch->religion != null && $advanceSearch->religion != 'select_value'){

                $query->where('religion', 'LIKE', "%" . $advanceSearch->religion . "%");
            }

            if($advanceSearch->dob != '' && $advanceSearch->dob != null && $advanceSearch->dob != 'select_value'){

                $query->whereDate('dob', '=', $advanceSearch->dob);
            }

            if($advanceSearch->age != '' && $advanceSearch->age != null && $advanceSearch->age != 'select_value'){

                $query->where('age', 'LIKE', "%" . $advanceSearch->age . "%");
            }

            if($advanceSearch->contact_number != '' && $advanceSearch->contact_number != null && $advanceSearch->contact_number != 'select_value'){

                $query->where('contact_number', 'LIKE', "%" . $advanceSearch->contact_number . "%");
            }

            if($advanceSearch->joining_date != '' && $advanceSearch->joining_date != null && $advanceSearch->joining_date != 'select_value'){

                $query->whereDate('joining_date', '=', $advanceSearch->joining_date);
            }

            if($advanceSearch->benefits != '' && $advanceSearch->benefits != null && $advanceSearch->benefits != 'select_value'){

                $query->where('benefits', 'LIKE', "%" . $advanceSearch->benefits . "%");
            }

            if($advanceSearch->iban != '' && $advanceSearch->iban != null && $advanceSearch->iban != 'select_value'){

                $query->where('iban', 'LIKE', "%" . $advanceSearch->iban . "%");
            }

            if($advanceSearch->vacation_date != '' && $advanceSearch->vacation_date != null && $advanceSearch->vacation_date != 'select_value'){

                $query->whereDate('vacation_date', '=', $advanceSearch->vacation_date);
            }

            if($advanceSearch->notes != '' && $advanceSearch->notes != null && $advanceSearch->notes != 'select_value'){

                $query->where('notes', 'LIKE', "%" . $advanceSearch->notes . "%");
            }

            if($advanceSearch->iqama_no != '' && $advanceSearch->iqama_no != null && $advanceSearch->iqama_no != 'select_value'){

                $query->where('iqama_no', 'LIKE', "%" . $advanceSearch->iqama_no . "%");
            }

            if($advanceSearch->iqama_expiry_date != '' && $advanceSearch->iqama_expiry_date != null && $advanceSearch->iqama_expiry_date != 'select_value'){

                $query->whereDate('iqama_expiry_date', '<=', $advanceSearch->iqama_expiry_date);
            }

            if($advanceSearch->iqama_profession != '' && $advanceSearch->iqama_profession != null && $advanceSearch->iqama_profession != 'select_value'){

                
                $query->where('iqama_profession', $advanceSearch->iqama_profession->value);
            }

            if($advanceSearch->passport_number != '' && $advanceSearch->passport_number != null && $advanceSearch->passport_number != 'select_value'){

                $query->where('passport_number', 'LIKE', "%" . $advanceSearch->passport_number . "%");
            }

            if($advanceSearch->passport_expiry_date != '' && $advanceSearch->passport_expiry_date != null && $advanceSearch->passport_expiry_date != 'select_value'){

                $query->whereDate('passport_expiry_date', '<=', $advanceSearch->passport_expiry_date);
            }

            if($advanceSearch->ajeer_expiration_date != '' && $advanceSearch->ajeer_expiration_date != null && $advanceSearch->ajeer_expiration_date != 'select_value'){

                $query->whereDate('ajeer_expiration_date', '<=', $advanceSearch->ajeer_expiration_date);
            }

            if($advanceSearch->insurance_card_expirationDate != '' && $advanceSearch->insurance_card_expirationDate != null && $advanceSearch->insurance_card_expirationDate != 'select_value'){

                $query->whereDate('insurance_card_expirationDate', '<=', $advanceSearch->insurance_card_expirationDate);
            }

            if($advanceSearch->vendor != '' && $advanceSearch->vendor != null && $advanceSearch->vendor != 'select_value'){

                
                $query->where('vendor', $advanceSearch->vendor->value);
            }

            if($advanceSearch->salary_rate != '' && $advanceSearch->salary_rate != null && $advanceSearch->salary_rate != 'select_value'){

                $query->where('salary_rate', 'LIKE', "%" . $advanceSearch->salary_rate . "%");
            }


            if($advanceSearch->client != '' && $advanceSearch->client != null && $advanceSearch->client != 'select_value'){

                
                $query->where('client', $advanceSearch->client->value);
            }

            if($advanceSearch->status != '' && $advanceSearch->status != null && $advanceSearch->status != 'select_value'){

                $query->where('status', 'LIKE', "%" . $advanceSearch->status . "%");
            }

            if($advanceSearch->accommodation != '' && $advanceSearch->accommodation != null && $advanceSearch->accommodation != 'select_value'){

                
                $query->where('accommodation', $advanceSearch->accommodation->value);
            }

            if($advanceSearch->contract_start != '' && $advanceSearch->contract_start != null && $advanceSearch->contract_start != 'select_value'){

                $query->whereDate('contract_start', '=', $advanceSearch->contract_start);
            }

            if($advanceSearch->project_stop_date != '' && $advanceSearch->project_stop_date != null && $advanceSearch->project_stop_date != 'select_value'){

                $query->whereDate('project_stop_date', '=', $advanceSearch->project_stop_date);
            }

            if($advanceSearch->lang_eng != '' && $advanceSearch->lang_eng != null && $advanceSearch->lang_eng != 'select_value'){

                $query->where('lang_eng', 'LIKE', "%" . $advanceSearch->lang_eng . "%");
            }

            if($advanceSearch->lang_ar != '' && $advanceSearch->lang_ar != null && $advanceSearch->lang_ar != 'select_value'){

                $query->where('lang_ar', 'LIKE', "%" . $advanceSearch->lang_ar . "%");
            }

            if($advanceSearch->lang_hind != '' && $advanceSearch->lang_hind != null && $advanceSearch->lang_hind != 'select_value'){

                $query->where('lang_hinds', 'LIKE', "%" . $advanceSearch->lang_hinds . "%");
            }

            if($advanceSearch->appearance_presentable != '' && $advanceSearch->appearance_presentable != null && $advanceSearch->appearance_presentable != 'select_value'){

                $query->where('appearance_presentable', 'LIKE', "%" . $advanceSearch->appearance_presentable . "%");
            }

            if($advanceSearch->apprearance_beard != '' && $advanceSearch->apprearance_beard != null && $advanceSearch->apprearance_beard != 'select_value'){

                $query->where('apprearance_beard', 'LIKE', "%" . $advanceSearch->apprearance_beard . "%");
            }

            if($advanceSearch->skills != '' && $advanceSearch->skills != null && $advanceSearch->skills != 'select_value'){

                if (!empty($advanceSearch->skills)) {
                    $skills = implode(",",$advanceSearch->skills);
                }

                $query->where('skills', 'LIKE', "%" . $skills . "%");
            }

            if($advanceSearch->misconduct != '' && $advanceSearch->misconduct != null && $advanceSearch->misconduct != 'select_value'){

                $query->where('misconduct', 'LIKE', "%" . $advanceSearch->misconduct . "%");
            }

            $userLevels = $query->orderBy('emp_id','desc')->get();

            $totalRows = count($userLevels);

            $userLevels = $this->paginaterhelp($request->page_no,$userLevels,$request);

            return employeeResource::collection($userLevels)->additional(['success' => true,'totalRows' => $totalRows,'message'=>'Employees fetched.']);
    }

    public function getEmployeeDetail(Request $request){

        $employee = employee::find($request->emp_id);
        $clientLocation = ClientLocation::where('client_code',$employee->client)->get();
       
        if(!$employee){
            $employee->add(["client_location" => $clientLocation]);
            return response()->json(['message'=>'Invalid User','success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);  
        }

        return new singleEmployeeResource($employee);
    }

    public function vendorsDropdown(Request $request){

        $vendors = vendor_base::select(DB::raw('CAST(id AS CHAR) AS value'),'code as text')->get();

        
        return response()->json(['message'=>'Vendors fetched','success' => true,'data' => json_decode(json_encode($vendors))]);


    }

    public function deleteEmployee(Request $request){

        $employee = employee::find($request->emp_id);

        if(!$employee){

            return response()->json(['message'=>'Invalid User','success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);  
        }

        $employee->delete();
        $user = JWTAuth::user();
        $action = 'DELETED';
        $itemName = $employee->name;
        $itemUnique = $employee->emp_id;
        $tableName = 'Employees';
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);
        return response()->json(['message'=>'Employee Deleted','success' => true,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);
        

    }

    
    public function getEmployeeDropdown(Request $request){

            $resourceType = $request->resourceType;

            if ($resourceType == 'Project') {

                $userLevels = client_base::select('client_name AS name','client_code AS id')->where(DB::raw("CONCAT(`client_name`, ' ', `client_code`)"), 'LIKE', "%" . $request->id . "%")->get();

            } else if($resourceType == 'Accommodation'){
               
               $userLevels = accommodation_base::select('name','id')->where(DB::raw("CONCAT(`name`, ' ', `id`)"), 'LIKE', "%" . $request->id . "%")->get();

            }
            else
            {
                $userLevels = employee::select('name','emp_id AS id')->where(DB::raw("CONCAT(`name`, ' ', `emp_id`)"), 'LIKE', "%" . $request->id . "%")->get();
            }
            

            

            return response()->json(['message'=>'Resource Owners Fetched','success' => true,'data' => json_decode(json_encode($userLevels))]);

    }

    public function getResourceItemsDropdown(Request $request){

        $resourceType = $request->resourceType;

            if ($resourceType == 'Project') {

                $resourceItems = projectResource::select(DB::raw('id AS value'),'name as text')->get();

            } else if($resourceType == 'Accommodation'){
               
               $resourceItems = accommodationResource::select(DB::raw('id AS value'),'name as text')->get();

            }
            else
            {
                $resourceItems = employeeItemResource::select(DB::raw('id AS value'),'name as text')->get();
            }

        

        
        return response()->json(['message'=>'Resource Items fetched','success' => true,'data' => json_decode(json_encode($resourceItems))]);

    }

    public function clientsDropdown(Request $request){

        $clients = client_base::select(DB::raw('CAST(client_code AS CHAR) AS value'),'client_name as text')->get();

        
        return response()->json(['message'=>'Clients fetched','success' => true,'data' => json_decode(json_encode($clients))]);

    }

    public function accommodationsDropdown(Request $request){

        $accommodations = accommodation_base::select(DB::raw('CAST(id AS CHAR) AS value'),'name as text')->get();

        
        return response()->json(['message'=>'Accommodations fetched','success' => true,'data' => json_decode(json_encode($accommodations))]);

    }

    public function professionsDropdown(Request $request){

        $professions = profession_base::select('profession_en AS value',DB::raw('CONCAT(`profession_en`, "-", `profession_ar`) AS text'))->get();

        
        return response()->json(['message'=>'Professions fetched','success' => true,'data' => json_decode(json_encode($professions))]);

    }

    

    public function createEmployee(addEmployeeValidate $request){

        $messages = [
                       
                        'name.required'  => 'Specify Employee name.',

                    ];

        $validate = Validator::make($request->all(), [
            'name'   => 'required',

            ],$messages);


        if ($validate->fails()) {

                    return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => []], 200);            
                }
        if ($request->iqama_no ) {
            $employee = employee::where('iqama_no',$request->iqama_no)->first();
            if($employee){
                return response()->json(['message'=>'Employee already exists.','success' => false,'data' => []], 200);
            }
        }
        if ($request->passport_number ) {
            $employee = employee::where('passport_number',$request->passport_number)->first();
            if($employee){
                return response()->json(['message'=>'Employee already exists.','success' => false,'data' => []], 200);
            }
        }
        $next_id = employee::max('emp_id') + 1;
        $request->request->add(['emp_id' => $next_id]);

        $input = $request->all();

        if($request->has('emp_photo') && $request->emp_photo != '' && $request->emp_photo != null)
        {
            $input['emp_photo'] = $this->img_upload($request->emp_photo);
        }

        if($request->has('iqama') && $request->iqama != '' && $request->iqama != null)
        {
            $input['iqama'] = $this->img_upload($request->iqama);
        }

        if($request->has('passport') && $request->passport != '' && $request->passport != null)
        {
            $input['passport'] = $this->img_upload($request->passport);
        }

        if($request->has('passport_2') && $request->passport_2 != '' && $request->passport_2 != null)
        {
            $input['passport_2'] = $this->img_upload($request->passport_2);
        }

        if($request->has('ajeer') && $request->ajeer != '' && $request->ajeer != null)
        {
            $input['ajeer'] = $this->img_upload($request->ajeer);
        }

        if($request->has('insurance_card') && $request->insurance_card != '' && $request->insurance_card != null)
        {
            $input['insurance_card'] = $this->img_upload($request->insurance_card);
        }

        if($request->has('dob') && $request->dob != '' && $request->dob != null)
        {
            $input['age'] = Carbon::parse($request->dob)->age;
           
        }
         //dd($input);
         $input['created_by'] = Auth::user()->name;
         $input['updated_by'] = null;
        $emp = employee::create($input);

        $user = JWTAuth::user();
        $action = 'CREATED';
        $itemName = $emp->name;
        $itemUnique = $next_id;
        $tableName = 'Employees';
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);

        return response()->json(['message'=>'Employee Created','success' => true,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);

    }

    public function updateEmployee(Request $request){

        $messages = [
            'name.required'  => 'Specify Employee name.',
           ];

        $validate = Validator::make($request->all(), [
            'name'   => 'required',
            'age'   => 'nullable|numeric',

            'contact_number'   => 'nullable|string|size:12',
            'iban'   => 'nullable|max:24',
            'iqama_no'   => 'nullable|numeric|max:9999999999',
            'salary_rate'   => 'nullable|numeric|max:9999999999999',

            ],$messages);


        if ($validate->fails()) {

                    return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => []], 200);            
                }

        $input =  $request->all();
        //$image = $input['iqama'];

        
        $emp= employee::find($input['emp_id']);

        //return $emp;

        //$input = $request->data;
        //return $input['vendor'];

        if (array_key_exists('vendor', $input)) {
            $input['vendor'] = json_decode($input['vendor']);
            $input['vendor'] =  $this->convertObject($input['vendor']);

            //dd($input['vendor']);
            if ($input['vendor'] != null && $input['vendor'] != '') {
                $input['vendor'] = $input['vendor']['value'];
            }
        }

        if (array_key_exists('nationality', $input)) {
            $input['nationality'] = json_decode($input['nationality']);
            $input['nationality'] =  $this->convertObject($input['nationality']);

            //dd($input['vendor']);
            if ($input['nationality'] != null && $input['nationality'] != '') {
                $input['nationality'] = $input['nationality']['value'];
            }
        }

        if (array_key_exists('religion', $input)) {
            $input['religion'] = json_decode($input['religion']);
            $input['religion'] =  $this->convertObject($input['religion']);

            //dd($input['vendor']);
            if ($input['religion'] != null && $input['religion'] != '') {
                $input['religion'] = $input['religion']['value'];
            }
        }

        if (array_key_exists('accommodation', $input)) {
            $input['accommodation'] = json_decode($input['accommodation']);

            $input['accommodation'] =  $this->convertObject($input['accommodation']);

            if ($input['accommodation'] != null && $input['accommodation'] != '') {
                $input['accommodation'] = $input['accommodation']['value'];
            }
            $input['accommodation'] = (int)$input['accommodation'];
            //dd($input['accommodation']);
        }

        if (array_key_exists('iqama_profession', $input)) {
            $input['iqama_profession'] = json_decode($input['iqama_profession']);
            $input['iqama_profession'] =  $this->convertObject($input['iqama_profession']);
            if ($input['iqama_profession'] != null && $input['iqama_profession'] != '') {
                $input['iqama_profession'] = $input['iqama_profession']['value'];
            }
        }
        if (array_key_exists('client', $input)) {
            
        
            $input['client'] = json_decode($input['client']);
            $input['client'] =  $this->convertObject($input['client']);
            if ($input['client'] != null && $input['client'] != '') {
                $input['client'] = $input['client']['value'];
            }
        }

        if($request->has('emp_photo') && $request->emp_photo != '' && $request->emp_photo != null)
        {
            $input['emp_photo'] = $this->img_upload($request->emp_photo);
        }

        if($request->has('iqama') && $request->iqama != '' && $request->iqama != null)
        {
            $input['iqama'] = $this->img_upload($request->iqama);
        }

        if($request->has('passport') && $request->passport != '' && $request->passport != null)
        {
            $input['passport'] = $this->img_upload($request->passport);
        }

        if($request->has('passport_2') && $request->passport_2 != '' && $request->passport_2 != null)
        {
            $input['passport_2'] = $this->img_upload($request->passport_2);
        }

        if($request->has('ajeer') && $request->ajeer != '' && $request->ajeer != null)
        {
            $input['ajeer'] = $this->img_upload($request->ajeer);
        }

        if($request->has('insurance_card') && $request->insurance_card != '' && $request->insurance_card != null)
        {
            $input['insurance_card'] = $this->img_upload($request->insurance_card);
        }
        /*$image = $input['iqama'];
       
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

        return url($pic);*/
        
        

        
        $input = array_filter($input, 'strlen');
        
        //return $input;

        if($request->has('dob') && $request->dob != '' && $request->dob != null)
        {
            $input['age'] = Carbon::parse($request->dob)->age;
           
        }
        //dd($input);
        $input['updated_by'] = Auth::user()->name;
        
        $emp->update($input);

        $user = JWTAuth::user();
        $action = 'UPDATED';
        $itemName = $emp->name;
        $itemUnique = $emp->emp_id;
        $tableName = 'Employees';
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);

        return response()->json(['message'=>'Employee Updated','success' => true,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);
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
                $result[$key] =  $value;
            }

            return $result;
            
        }

        return $data;
    }
}
