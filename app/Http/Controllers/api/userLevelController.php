<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\userLevelResource;


use App\Models\userLevel;

use Validator;
use JWTAuth;
use Auth;

class userLevelController extends Controller
{
    public function getUserLevel(Request $request){


            $userLevels = userLevel::get();

            return userLevelResource::collection($userLevels)->additional(['success' => true,'message'=>'User levels fetched.']);
    }
}
