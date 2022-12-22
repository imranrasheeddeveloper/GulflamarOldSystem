<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bank;


class bankController extends Controller
{
    
    public function getAllBanks(Request  $request){ 

        $Records = Bank::all();
    $totalRows = count($Records);

    return response()->json(['message'=>'Supplier_Types Item fetched','success' => true,'totalRows' => $totalRows,'data' => json_decode(json_encode($Records))]);



}
}
