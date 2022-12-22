<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\city;

class cityController extends Controller
{
    public function getAllCities(Request  $request){ 

        $Records = city::all();
        foreach($Records as $Record){
            $Record->fullName = $Record->name_en.' - '.$Record->name_ar;
        }
    

    $totalRows = count($Records);

    return response()->json(['message'=>'Supplier_Types Item fetched','success' => true,'totalRows' => $totalRows,'data' => json_decode(json_encode($Records))]);



}
}
