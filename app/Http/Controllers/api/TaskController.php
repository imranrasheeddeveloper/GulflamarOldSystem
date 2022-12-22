<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;


use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Task;
use JWTAuth;
use Validator;


use Illuminate\Support\Facades\Auth;


class TaskController extends Controller
{
    public function getAllAssignee(Request  $request){
            $AllAssignees = User::all();

            $totalRows = count($AllAssignees);

        return response()->json(['message'=>'Assignees fetched','success' => true,'totalRows' => $totalRows,'data' => json_decode(json_encode($AllAssignees))]);



    }
    public function getAllTasks(Request  $request){
        $AllTasks = null;
        $userId = Auth::user()->id;
        if(isset($request->filter)){
            if($request->filter == "completed"){
                $AllTasks = Task::where('assignTo',$userId)->where('isCompleted',1)->with('Owner','assignee')->get();

            }
            if($request->filter == "assingedByMe"){
                $AllTasks = Task::where('ownerId',$userId)->with('Owner','assignee')->get();
            }
            if($request->filter == "otherCompleted"){
                $AllTasks = Task::where('ownerId',$userId)->where('isCompleted',1)->with('Owner','assignee')->get();
            }
        }
        if(isset($request->tag)){
            if($request->tag){
                $AllTasks = Task::where('tag',$request->tag)->where('ownerId',$userId)->with('Owner','assignee')->get();
            }
        }
        if(!isset($AllTasks)){
            $AllTasks = Task::where('assignTo',$userId)->where('isCompleted',0)->with('Owner','assignee')->get();

        }

        $totalRows = count($AllTasks);
        $userId = Auth::user()->id;

    return response()->json(['message'=>'All Tasks fetched','success' => true,'totalRows' => $totalRows,'systemOwner' => $userId,'data' => json_decode(json_encode($AllTasks))]);



}



public function addTask(Request  $request){
    $messages = [
        'assignTo.required'  => 'Assignee must be required.',
        'description.required'  => 'Description must be required.',
        'title.required'  => 'Title must be required.',
        'tag.required'  => 'Tag must be required.',
        'dueDate.required'  => 'DueDate must be required.',

    ];

    $validate = Validator::make($request->all(), [ 
        'assignTo' => 'required',
        'description' => 'required|string',
        'title' => 'required|string|max:255',
        'tag' => 'required|string|max:255',
        'dueDate' => 'required|string|max:255',
    ],$messages);

    if ($validate->fails()) { 
              
            return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);            
        }

        // $task = new Task();
        // $task->title = $request->title;
        // $task->description = $request->description;
        // $task->assignTo = $request->assignTo;
        // $task->tag = $request->tag;
        // $task->dueDate = $request->dueDate;

        // $task->save();
        $input = $request->all();
        if($request->id){
            if($request->systemOwner == Auth::user()->id){
                $task =   Task::updateOrCreate([
                    'id' => $request->id
                    ],$input);
                    $message = 'Task Updated';
            }
        }else{
            $input['ownerId'] = Auth::user()->id;
            $task = Task::create($input);
            $message = 'Task Added';
        }
        // $input['ownerId'] = Auth::user()->id;
        // $task = Task::create($input);
        // // $task =   Task::updateOrCreate([
        // //     'id' => $request->id
        // //     ],$input);

        //     $message = 'Task Created';
        //     if(isset($request->id)){
        //         $message = 'Task Updated';
        //     }

        return response()->json(['message'=>$message,'success' => true,'data' => json_decode(json_encode($task))]);



//     $AllTasks = Task::with('Owner','assignee')->get();

//     $totalRows = count($AllTasks);
//     $userId = Auth::user()->id;

// return response()->json(['message'=>'All Tasks fetched','success' => true,'totalRows' => $totalRows,'systemOwner' => $userId,'data' => json_decode(json_encode($AllTasks))]);



}





public function isCompleteTask(Request  $request){
    $messages = [
        'id.required'  => 'ID must be required.',
        'isCompleted.required'  => 'isCompleted must be required.',

    ];

    $validate = Validator::make($request->all(), [ 
        'id' => 'required',
        'isCompleted' => 'required',
    ],$messages);

    if ($validate->fails()) { 
              
            return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);            
        }

        $task =   Task::where('id',$request->id)->first();
        $task->isCompleted = $request->isCompleted;
        $task->save();

        return response()->json(['message'=>'Task Updated','success' => true,'data' => json_decode(json_encode($task))]);




}


public function deleteTask(Request  $request){
    $messages = [
        'id.required'  => 'ID must be required.',

    ];

    $validate = Validator::make($request->all(), [ 
        'id' => 'required',
    ],$messages);

    if ($validate->fails()) { 
              
            return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);            
        }

        $task = Task::where('id',$request->id)->first();
        $task->delete();

        return response()->json(['message'=>'Task Dleted','success' => true,'data' => json_decode(json_encode($task))]);




}

}
