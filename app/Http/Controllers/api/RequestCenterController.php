<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\RequestCenter;
use Illuminate\Http\Request;
use Validator;

class RequestCenterController extends Controller
{
    public function index()
    {
        return view('request-center');
    }

    public function addRequestCenter(Request $request)
    {
        $validate = Validator::make($request->all(), [ 
            'name' => 'required|string|max:255',
            'type' => 'required',
            'issue' => 'required',
        ]);

        $requestCenter = new RequestCenter();
        $requestCenter->name = $request->name;
        $requestCenter->type = $request->type;
        $requestCenter->issue = $request->issue;
        $requestCenter->save();

        return redirect()->back();
        // return response()->json(['message'=>'Request Center Created','success'
        //  => true,'data' => json_decode(json_encode($requestCenter))]);
    }
}
