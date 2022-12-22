<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\settingsResource;

use App\Models\User;
use App\Models\settings;

use Validator;
use JWTAuth;
use Auth;
use DB;

class settingController extends Controller
{

    public function getsettings(Request $request){

        $settings = settings::first();

        if(!$settings){

            return response()->json(['message'=>'Invalid ID','success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);  
        }
        return (new settingsResource($settings))->additional(['success' => true,'message'=>'Settings fetched.']);
    }

    public function updateSettings(Request $request){

        $input = $request->all();
        $settings = settings::first();

        if(!$settings){

            return response()->json(['message'=>'Entry Not Found','success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);  
        }

        $messages = [
                        'vat.required'  => 'VAT is required.',
                    ];
    
        $validate = Validator::make($request->all(), [ 
            'vat' => 'required', 
        ],$messages);

        if ($validate->fails()) { 
                  
                return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);            
            }

        

        //return $input['invoice_copy'];
        $settings->update($input);

        $user = JWTAuth::user();
        $action = 'UPDATED';
        $itemName = 'Global Terms';
        $itemUnique = 1;
        $tableName = 'Settings';
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);

        return response()->json(['message'=>'Settings Updated','success' => true,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);

    }

    public function deleteImage(Request $request){ //params => tableName, uniqueColumn, uniqueValue, imageColumn

        $model = 'App\Models\\'.$request->tableName;
        $record = $model::where($request->uniqueColumn,$request->uniqueValue)->first();

        if(!$record){

            return response()->json(['message'=>'Invalid Request','success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);  
        }

        $record->{$request->imageColumn} = null;
        $record->save();

        return response()->json(['message'=>'Image Removed successfully','success' => true,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);
    }
}
