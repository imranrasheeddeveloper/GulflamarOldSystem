<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Pagination\LengthAwarePaginator;

use Tymon\JWTAuth\Exceptions\JWTException;

use App\Http\Resources\auditResource;

use App\Models\audit;

use Validator;
use JWTAuth;
use Auth;
use DB;

class auditController extends Controller
{

    public function getAuditTrail(Request $request){

        if ($request->searchTerm != '' && $request->searchTerm) {
            $resourceRecords = audit::where(DB::raw("CONCAT(`id`, ' ', `detail`)"), 'LIKE', "%" . $request->searchTerm . "%")->orderBy('id','desc')->get();
        }
        else
        {
            $resourceRecords = audit::orderBy('id','desc')->get();
        }
        

        $totalRows = count($resourceRecords);

        $resourceRecords = $this->paginaterhelp($request->page_no,$resourceRecords,$request);

        return auditResource::collection($resourceRecords)->additional(['success' => true,'totalRows' => $totalRows,'message'=>'Audit Trail fetched.']);

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
