<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Pagination\LengthAwarePaginator;

use Tymon\JWTAuth\Exceptions\JWTException;

use App\Http\Resources\userSignupResource;
use App\Http\Resources\userResource;
use App\Http\Resources\userDataResource;

use App\Models\User;
use App\Models\userLevel;

use Validator;
use JWTAuth;
use Auth;
use DB;

class UserController extends Controller
{

   public function refreshToken()
    {
        return $this->respondWithToken(JWTAuth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth()->factory()->getTTL() * 60
        ]);
    }
   public function addUser(Request $request){

        $messages = [
                        'email.required'  => 'email is required.',
                        'email.email'  => 'email should be in correct formate.',
                        'email.unique'  => 'email already exist.',
                        'name.required'  => 'Name is required.',
                        'password.required'  => 'password is required.',
                        'role.required'  => 'Specify User role.',
                    ];
    
        $validate = Validator::make($request->all(), [ 
            'email' => 'required|email|unique:users',
            'name' => 'required', 
            'password' => 'required', 
            'mobile' => 'max:11', 
            'role' => 'required', 
            // 'device_token' => 'required', 
        ],$messages);

        if ($validate->fails()) { 
                  
                return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);            
            }


        $input = $request->all(); 
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        $user = JWTAuth::user();
        $action = 'CREATED';
        $itemName = $user->name;
        $itemUnique = $user->id;
        $tableName = 'Users';
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);
        
        return response()->json(['message'=>'User Created','success' => true,'data' => json_decode(json_encode([]))]);

    }

    public function getUserDetail(Request $request){

        $user = User::find($request->id);

        if(!$user){

            return response()->json(['message'=>'Invalid ID','success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);  
        }
        return (new userDataResource($user))->additional(['success' => true,'message'=>'User Detail fetched.']);
    }

    public function getProfile(Request $request){

        $user = JWTAuth::user();

        if(!$user){

            return response()->json(['message'=>'Invalid ID','success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);  
        }
        return (new userDataResource($user))->additional(['success' => true,'message'=>'User Profile fetched.']);
    }

    public function updateUser(Request $request){

        $input = $request->all();
        $user = User::find($request->id);

        if(!$user){

            return response()->json(['message'=>'Invalid ID','success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);  
        }

        $messages = [
                        'email.required'  => 'email is required.',
                        'email.email'  => 'email should be in correct formate.',
                        'email.unique'  => 'email already exist.',
                        'name.required'  => 'Name is required.',
                        //'password.required'  => 'password is required.',
                        'role.required'  => 'Specify User role.',
                    ];
    
        $validate = Validator::make($request->all(), [ 
            'email' => 'required|email|unique:users,email,'.$request->id,
            'name' => 'required', 
            //'password' => 'required', 
            'mobile' => 'max:11', 
            'role' => 'required', 
            // 'device_token' => 'required', 
        ],$messages);

        if ($validate->fails()) { 
                  
                return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);            
            }

        $error = false;

        if ($input['role'] != '' && $input['role'] != null) {

                if(is_object($input['role'])){

                $input['role'] = $input['role']->value;

                }
                if(is_array($input['role'])){

                    $input['role'] = $input['role']['value'];

                }
            if (!$error) {

                if (empty(trim($input['role']))) {

                    $error = true;

                    $tmpErrorMsg = 'Role Name Cannot Be empty.';
                    // code...
                }
            }

            
        }

        if ($error) {

                    return response()->json(['message'=>$tmpErrorMsg,'success' => false,'data' => []], 200);            
                }


        

        $input['password'] = $user->password;
        if ($request->has('password') && !empty(trim($request->password))) {
             $input['password'] = bcrypt($request->password);
        }

        //return $input['invoice_copy'];
        $user->update($input);

        $user = JWTAuth::user();
        $action = 'Updated';
        $itemName = $user->name;
        $itemUnique = $user->id;
        $tableName = 'Users';
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);

        return response()->json(['message'=>'User Updated successfully','success' => true,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);

    }

    public function updateProfile(Request $request){

        $input = $request->all();
        $user = JWTAuth::user();

        if(!$user){

            return response()->json(['message'=>'Invalid ID','success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);  
        }

        $messages = [
                        'email.required'  => 'email is required.',
                        'email.email'  => 'email should be in correct formate.',
                        'email.unique'  => 'email already exist.',
                        'name.required'  => 'Name is required.',
                        'pin.required'  => 'Pin should be 4 digits',
                        
                        //'password.required'  => 'password is required.',
                    ];
    
        $validate = Validator::make($request->all(), [ 
            'email' => 'required|email|unique:users,email,'.$user->id,
            'name' => 'required', 
            //'password' => 'required', 
            'mobile' => 'max:11', 
            'pin' => 'regex:/\b\d{4}\b/'
            // 'device_token' => 'required', 
        ],$messages);

        if ($validate->fails()) { 
                  
                return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);            
            }

        


        

        $input['password'] = $user->password;
        if ($request->has('password') && !empty(trim($request->password))) {
             $input['password'] = bcrypt($request->password);
        }

        //return $input['invoice_copy'];
        $user->name= $input['name'];
        $user->email= $input['email'];
        $user->password= $input['password'];
        $user->user_name= $input['user_name'];
        $user->mobile= $input['mobile'];
        $user->pin= $input['pin'];
        $user->save();

        /*$user = JWTAuth::user();
        $action = 'Updated';
        $itemName = $user->name;
        $itemUnique = $user->id;
        $tableName = 'Users';
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);*/

        return response()->json(['message'=>'Profile Updated successfully','success' => true,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);

    }

    public function login(Request  $request){ 
        
        
        if ($request->has(['user_name', 'email', 'password'])) {
           
            $messages = [
                'email.required'  => 'email is required.',
                'email.email'  => 'email should be in correct formate.',
            ];
             $validate = Validator::make($request->all(), [ 
             'email' => 'required|email', 
             'password' => 'required',
             ],$messages);
             $credentials = $request->only('email', 'password');

        } 
        elseif ($request->has(['user_name', 'password'])) {
            $messages = [
                'user_name.required'  => 'user name is required.',
            ];

             $validate = Validator::make($request->all(), [ 
             'user_name' => 'required', 
             'password' => 'required',
             ],$messages);

            $credentials = $request->only('user_name', 'password');
        }
        elseif ($request->has(['email', 'password'])) {
           
            $messages = [
                'email.required'  => 'email is required.',
                'email.email'  => 'email should be in correct formate.',
            ];
             $validate = Validator::make($request->all(), [ 
             'email' => 'required|email', 
             'password' => 'required',
             ],$messages);
            if ($validate->fails()) {      
                return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);            
                }
                $credentials = $request->only('email', 'password');
        }
        elseif ($request->has(['mobile', 'password'])) {

            $messages = [
                'mobile.required'  => 'mobile is required.',
                'mobile.max'  => 'mobile should be of 10 digits.',
            ];
             $validate = Validator::make($request->all(), [ 
             'mobile' => 'required|max:10', 
             'password' => 'required',
             ],$messages);

            if ($validate->fails()) {      
                return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);            
                }
                $credentials = $request->only('mobile', 'password');
        }
        else {
            $messages = [
                'email.required'  => 'email is required.',
                'email.email'  => 'email should be in correct formate.',
            ];
             $validate = Validator::make($request->all(), [ 
             'email' => 'required|email', 
             'password' => 'required',
             ],$messages);

            if ($validate->fails()) {      
                return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);            
            }

            $credentials = $request->only('email', 'password');
        }
        
            try {
                if (! $token = JWTAuth::attempt($credentials)) {
                    return response()->json(['message'=>'Credentials dont match','success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);
                }
            } catch (JWTException $e) {
                return response()->json(['message'=>'Error in token generation.','success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))], 500);
            }

        
                
            $user = JWTAuth::user();
            $user->token = $token;

            $user->ability = $this->get_permissions($user);
            
            return new userResource($user);
                 
    }

    public function getPermissions(Request $request){

            $user = JWTAuth::user();
            $user->token = 'hushdsnfkjn21324';
            $user->ability = $this->get_permissions($user);
            return new userResource($user);
    }

    public function getUsers(Request  $request){ 

        if ($request->searchTerm != '' && $request->searchTerm) {
            $resourceRecords = User::where(DB::raw("CONCAT(`id`, ' ', `email`, ' ', `name`)"), 'LIKE', "%" . $request->searchTerm . "%")->orderBy('id','desc')->get();
        }
        else
        {
            $resourceRecords = User::orderBy('id','desc')->get();
        }
        

        $totalRows = count($resourceRecords);

        $resourceRecords = $this->paginaterhelp($request->page_no,$resourceRecords,$request);

        return userDataResource::collection($resourceRecords)->additional(['success' => true,'totalRows' => $totalRows,'message'=>'Users fetched.']);

    }

    public function getAllSystemUsers(Request  $request){ 

        $resourceRecords = User::orderBy('id','desc')->get();
        
        return response()->json(['message'=>'System Users fetched','success' => true,'data' => json_decode(json_encode($resourceRecords))]);


    }

    public function getRolesDropdown(Request $request){


        $resourceItems = userLevel::select(DB::raw('id AS value'),'name as text')->get();

        return response()->json(['message'=>'Roles fetched','success' => true,'data' => json_decode(json_encode($resourceItems))]);

    }

    public function get_permissions(User  $user){ 


            $tmpPermissions =  $user->userLevel->permissions;

            $tmpPermissions = $tmpPermissions->toArray();

           // return $tmp;
            $permissions = [];
            
            foreach ((array)$tmpPermissions as $key => $value) {
                //echo $key;

                if($key != 'id' && $key != 'created_at' && $key != 'updated_at')
                {
                    if ($value != null && $value != '') {

                        if ($key == 'dashboard') {
                            
                            $permissions[] = [
                                                 'action' => $value,
                                                 'subject' => $key
                                            ];

                        } else {

                            foreach (explode(',',$value) as $childKey => $singlePermission) {

                                switch ($singlePermission) {
                                            case "C":
                                                $permissions[] = [
                                                        'action' => 'add/copy',
                                                        'subject' => $key
                                                    ];

                                                break;

                                            case "D":
                                                $permissions[] = [
                                                        'action' => 'delete',
                                                        'subject' => $key
                                                    ];

                                                break;

                                            case "E":
                                                $permissions[] = [
                                                        'action' => 'edit',
                                                        'subject' => $key
                                                    ];

                                                break;

                                            case "L":
                                                $permissions[] = [
                                                        'action' => 'list',
                                                        'subject' => $key
                                                    ];

                                                break;

                                            case "V":
                                                $permissions[] = [
                                                        'action' => 'view',
                                                        'subject' => $key
                                                    ];

                                                break;

                                            case "S":
                                                $permissions[] = [
                                                        'action' =>'search',
                                                        'subject' => $key
                                                    ];

                                                break;

                                            case "A":
                                                $permissions[] = [
                                                        'action' => 'admin',
                                                        'subject' => $key
                                                    ];

                                                break;


                                              
                                              default:
                                                break;
                                            }
                            }

                        }
                    }
                    
                }
            }

            /*$permissions[] = [
                                'action' => 'manage',
                                'subject' => 'all'
                            ];*/
            $permissions[] = [
                                'action' => 'view',
                                'subject' => 'navbar'
                            ];
            return $permissions;

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
