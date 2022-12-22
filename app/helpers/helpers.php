<?php

use Illuminate\Pagination\LengthAwarePaginator;

use App\Models\User;
use App\Models\audit;
use Illuminate\Support\Str;


function changeDateFormate($date, $date_format)
{

  return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->format($date_format);
}

function recordActivity(User $user, $action, $itemName, $itemUnique, $tableName)
{

  $audit = new audit();

  $audit->user_id = $user->id;
  $audit->detail = 'User ' . $user->name . '(' . $user->id . ') ' . $action . ' ' . $itemName . '(' . $itemUnique . ') in ' . $tableName . ' Table';
  $audit->save();
}

function paginaterhelp($page, $items, $request)

{


  $page = $page;

  // Number of items per page
  $perPage = 10;

  // Start displaying items from this number;
  $offSet = ($page * $perPage) - $perPage; // Start displaying items from this number

  // Get only the items you need using array_slice 

  if (is_array($items)) {
    $itemsForCurrentPage = array_slice($items, $offSet, $perPage, false);
  } else {
    $itemsForCurrentPage = $items->slice($offSet, $perPage);
    /*$itemsForCurrentPage = array_slice($items->toArray(), $offSet, $perPage, false);*/
  }

  // Return the paginator with only 10 items but with the count of all items and set the it on the correct page
  $result = new LengthAwarePaginator($itemsForCurrentPage, count($items), $perPage, $page, ['path' => $request->url(), 'query' => $request->query()]);

  //  $result = json_decode(json_encode($result,true),true);

  return $result->items();
}


function is_url_permission($request_url, $route_url, $table_name = null, $action = null, $permissions = null)
{

  $temp_permission = null;

  // $contains_url = Str::contains($request_url, $route_url);

  if ($request_url == $route_url) {


    $temp_permission = "matched";

    if (isset($table_name) && isset($action) && isset($permissions)) {

      $temp_actions = $permissions[$table_name];

      if (isset($temp_actions)) {

        $action_check = Str::contains($temp_actions, $action);

        if ($action_check == true) {

          $temp_permission = "permission_granted";
        } else {

          $temp_permission = "permission_denied";
        }
      }
    }
    
  } else {

    $temp_permission = "not_matched";
  }


  return $temp_permission;
}
