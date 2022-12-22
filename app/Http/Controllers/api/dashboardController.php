<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Pagination\LengthAwarePaginator;

use App\Models\employee;
use App\Models\invoice_base;
use App\Models\accommodationResource;
use App\Models\projectResource;
use App\Models\employeeResource;
use App\Models\purchased_item;
use App\Models\accommodation_base;


use App\Http\Resources\rentPaymentResource;
use App\Http\Resources\accommodationResource AS accommodationBaseResource;

use Validator;
use JWTAuth;
use Auth;
use DB;

class dashboardController extends Controller
{
    public function dashboardStats(Request $request){

            $stat = [];

            $tmpStat['icon'] = 'UserIcon';
            $tmpStat['color'] = 'light-info';
            $tmpStat['title'] = employee::where('status','Available')->count();
            $tmpStat['subtitle'] = 'Available Employees';
            $tmpStat['customClass'] = 'mb-2 ';

            $stat[] = $tmpStat;
            $tmpStat = [];

            $tmpStat['icon'] = 'UserIcon';
            $tmpStat['color'] = 'light-info';
            $tmpStat['title'] = employee::where('status','Deployed')->count();
            $tmpStat['subtitle'] = 'Deployed Employees';
            $tmpStat['customClass'] = 'mb-2 ';

            $stat[] = $tmpStat;
            $tmpStat = [];

            $tmpStat['icon'] = 'UserIcon';
            $tmpStat['color'] = 'light-danger';
            $tmpStat['title'] = employee::where( function ($q) {
                                        $q->where('status','=','Deployed')
                                            ->orWhere('status','=','Available');
                                    })->whereDate('iqama_expiry_date','<=', today())->count();
            $tmpStat['subtitle'] = 'Expired Iqamas';
            $tmpStat['customClass'] = 'mb-2 ';

            $stat[] = $tmpStat;
            $tmpStat = [];

            $stat = (object) $stat;

            return response()->json(['message'=>'Stats Fetched successfully','success' => true,'data' => $stat]);

    } 

    public function getMonthlyRevenues(Request $request){

        //$data = invoice_base::whereYear('invoice_date', '=', '2021')->get();
        $monthlyData = [];
        for ($i=1; $i<=12; $i++) {
            $tmpTotal = 0;
            $tmpData = invoice_base::whereYear('invoice_date', '=', $request->year)->whereMonth('invoice_date', $i)->get();

            foreach ($tmpData as $key => $record) {
                
                if ($record->net_total != '' && $record->net_total != null) {
                    $tmpTotal += $record->net_total;
                }

            }
            $monthlyData[] = round($tmpTotal, 2);
        }

        $maxValue = max($monthlyData);
        return response()->json(['message'=>'Revenue Stats Fetched successfully','success' => true,'data' => $monthlyData,'maxValue' => $maxValue]);

    }

    public function dashboardProductStat(Request $request){


        $projectResourceItems = projectResource::get();
        $accommodationResourceItems = accommodationResource::get();
        $employeeResourceItems = employeeResource::get();


        $productArray= [];
        foreach ($projectResourceItems as $key => $item) {
                $tmpArray = [];
                $tmpArray['name'] = $item->name;
                $tmpArray['quantity_out'] = $item->resource->sum('pivot.quantity');
                $tmpArray['quantity_in'] = purchased_item::where('name',$item->name)->sum('quantity');


                $productArray[]= $tmpArray;
        }

        foreach ($accommodationResourceItems as $key => $item) {
                $tmpArray = [];
                $tmpArray['name'] = $item->name;
                $tmpArray['quantity_out'] = $item->resource->sum('pivot.quantity');
                $tmpArray['quantity_in'] = purchased_item::where('name',$item->name)->sum('quantity');


                $productArray[]= $tmpArray;
        }

        foreach ($employeeResourceItems as $key => $item) {
                $tmpArray = [];
                $tmpArray['name'] = $item->name;
                $tmpArray['quantity_out'] = $item->resource->sum('pivot.quantity');
                $tmpArray['quantity_in'] = purchased_item::where('name',$item->name)->sum('quantity');


                $productArray[]= $tmpArray;
        }



        //$productArray = (object)$productArray;
        //dd($productArray);

        return response()->json(['message'=>'Product Stats Fetched successfully','success' => true,'data' => $productArray]);
    }

    public function dashboardUpcomingRents(Request $request){

        $resourceRecords = accommodation_base::orderBy('due-date','ASC')->limit(3)->get();
        return accommodationBaseResource::collection($resourceRecords)->additional(['success' => true,'message'=>'Upcoming Rents fetched.']);


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
