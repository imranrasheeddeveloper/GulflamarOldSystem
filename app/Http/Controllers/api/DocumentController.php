<?php

namespace App\Http\Controllers\api;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Pagination\LengthAwarePaginator;

use Tymon\JWTAuth\Exceptions\JWTException;

use App\Models\User;
use App\Models\Document;
use App\Http\Resources\documentDataResource;

use App\Models\employee;
use App\Models\vendor_base;
use App\Models\client_base;
use App\Models\accommodation_base;
use App\Models\Supplier;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


use Illuminate\Support\Facades\Auth;

use App\Models\userLevel;
use App\Models\Wallet;
use Illuminate\Support\Str;

use Validator;
use JWTAuth;
// use Auth;
// use DB;
use PDF;

use App\Exports\DocumetManagementExport;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\DB;


class DocumentController extends Controller
{
    public function createDocument(Request $request){


        $messages = [
            'recipient_type.required'  => 'Recipient Type must be required.',
            'recipient.required'  => 'Recipient must be required.',
            'date.required'  => 'Date must be required.',
            'resource_owner_id.required'  => 'Resource Owner must be required.',
            'resource_owner_name.required'  => 'Resource Owner must be required.',


            'method.required'  => 'Method must be required.',
            'received_by.required'  => 'Received by must be required.',
            'received_at.required'  => 'Received at must be required.',
        ];
    
        $validate = Validator::make($request->all(), [ 
            'recipient_type' => 'required|string|max:255',
            'recipient' => 'required|string|max:255',
            // 'file.*' => 'required|mimes:pdf,doc,docx',
            'file.*' => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar',
            'date' => 'required|string|max:255',
            'resource_owner_id' => 'required',
            'resource_owner_name' => 'required|string',
            'method' => 'required|string',
            'received_by' => 'required|string',
            'received_at' => 'required|string',



        ],$messages);

        if ($validate->fails()) { 
                  
                return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);            
            }

        $input = $request->all();

            $attachments = [];

            $files = $request->file;
            if($files != null)
            {
                foreach($files as $file)
                {
                   $attachments[] =  $this->img_upload($file);

                }
            }


        $input['created_by'] = Auth::user()->name;
        $input['updated_by'] = null;

        $input['attachment'] = json_encode($attachments);

        $Document = Document::create($input);
        $Document->attachment = json_decode($Document->attachment); 

        $user = JWTAuth::user();
        $action = 'CREATED';
        $itemName = $Document->recipient_type.' Document';
        $itemUnique = $Document->id;
        $tableName = 'documents';
        recordActivity($user,$action,$itemName,$itemUnique,$tableName);
        
        return response()->json(['message'=>'Document Created','success' => true,'data' => json_decode(json_encode($Document))]);

    }



    public function getAllDocuments(Request  $request){ 
        $advanceSearch = json_decode($request->advanceSearch);
        $query = Document::query();

        $documentRecords = null;

        if($request->searchTerm != '' && $request->searchTerm != null){
            $query->orWhere('recipient_type', 'LIKE', "%".$request->searchTerm."%");
            $query->orWhere('resource_owner_id', 'LIKE', "%".$request->searchTerm."%");
            $query->orWhere('resource_owner_name', 'LIKE', "%".$request->searchTerm."%");
            $query->orWhere('recipient', 'LIKE', "%".$request->searchTerm."%");
            $query->orWhere('id', $request->searchTerm);


        }


        if($advanceSearch->recipient_type != '' && $advanceSearch->recipient_type != null){
            $query->where('recipient_type', 'LIKE', "%".$advanceSearch->recipient_type."%");

        }
        if($advanceSearch->recipient != '' && $advanceSearch->recipient != null){
            $query->where('recipient', 'LIKE', "%".$advanceSearch->recipient."%");

        }
        if($advanceSearch->date != '' && $advanceSearch->date != null){
            $query->where('date', 'LIKE', "%".$advanceSearch->date."%");

        }
        $documentRecords=clone $query;
        
        $totalRows = $documentRecords->count();
        $documentRecords = $query->latest()->get();
        foreach($documentRecords as $documentRecord){
            $documentRecord['resource_owner'] = $documentRecord->resource_owner_name .' , '.$documentRecord->resource_owner_id;

            $documentRecord->attachment = json_decode($documentRecord->attachment);
        }


    $resourceRecords = $this->paginaterhelp($request->page_no,$documentRecords,$request);


    return documentDataResource::collection($resourceRecords)->additional(['success' => true,'base_url' => url('/'),'success' => true,'totalRows' => $totalRows,'message'=>'Suppliers fetched.']);

}




public function deleteDocument(Request  $request){ 

    $document = Document::where('id', $request->id)->first();
    $document->attachment = json_decode($document->attachment);
    foreach($document->attachment as $doc){
        if (File::exists($doc)) 
        { 
            File::delete($doc);
        }

    }


    $user = JWTAuth::user();
    $action = 'Deleted';
    $itemName = $document->recipient_type.' Document';
    $itemUnique = $document->id;
    $tableName = 'documents';
    recordActivity($user,$action,$itemName,$itemUnique,$tableName);

    $document->delete();
    return response()->json(['message'=>'Document Deleted Successfully','success' => true]);



}

public function getDocument(Request  $request){ 

    $Document = Document::where('id', $request->id)->first();
    $Document->attachment = json_decode($Document->attachment);
    return response()->json(['message'=>'Document fetched','success' => true,'base_url' => url('/'),'data' => json_decode(json_encode($Document))]);


}

public function documentResourceOwner(Request  $request){

            $userLevels = null;
            if($request->type == "Accommodation"){
                
               $userLevels = accommodation_base::select('name','id')->where(DB::raw("CONCAT(`name`, ' ', `id`)"), 'LIKE', "%" . $request->id . "%")->get();

                
            } else if($request->type == "Client"){ 

                $userLevels = client_base::select('client_name AS name','client_code AS id')->where(DB::raw("CONCAT(`client_name`, ' ', `client_code`)"), 'LIKE', "%" . $request->id . "%")->get();


            } else if($request->type == "Employee"){
            
                $userLevels = employee::select('name','emp_id AS id')->where(DB::raw("CONCAT(`name`, ' ', `emp_id`)"), 'LIKE', "%" . $request->id . "%")->get();


            } else if($request->type == "Supplier"){

               $userLevels = Supplier::select('name','id')->where(DB::raw("CONCAT(`name`, ' ', `id`)"), 'LIKE', "%" . $request->id . "%")->get();

            } else if($request->type == "Vendor"){ 

               $userLevels = vendor_base::select('name','id')->where(DB::raw("CONCAT(`name`, ' ', `id`)"), 'LIKE', "%" . $request->id . "%")->get();

            }
            else if($request->type == "Wallet"){

                $userLevels = Wallet::select('account_name AS name','id')->where(DB::raw("CONCAT(`account_name`, ' ', `id`)"), 'LIKE', "%" . $request->id . "%")->get();
    
            }

            if($userLevels == null){
                
               $userLevels = accommodation_base::select('name','id')->where(DB::raw("CONCAT(`name`, ' ', `id`)"), 'LIKE', "%" . $request->id . "%")->get();

            }
            

            

            return response()->json(['message'=>'Document Resource Owners Fetched','success' => true,'data' => json_decode(json_encode($userLevels))]);



}



public function updateDocument(Request $request){


    $messages = [
        'id.required'  => 'Id must be required.',
        'recipient_type.required'  => 'Recipient Type must be required.',
        'recipient.required'  => 'Recipient must be required.',
        'date.required'  => 'Date must be required.',
        'attachment.required'  => 'Previous Attachment must be required.',
        'created_by.required'  => 'Created By must be required.',
        'resource_owner_id.required'  => 'Resource Owner must be required.',
        'resource_owner_name.required'  => 'Resource Owner must be required.',


        'method.required'  => 'Method must be required.',
        'received_by.required'  => 'Received by must be required.',
        'received_at.required'  => 'Received at must be required.',


    ];

    $validate = Validator::make($request->all(), [ 
        'id' => 'required',
        'recipient_type' => 'required|string|max:255',
        'recipient' => 'required|string|max:255',
        // 'file.*' => 'required|mimes:pdf,doc,docx',
        'attachment' => 'required',
        'file.*' => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar',

        'date' => 'required|string|max:255',
        'created_by' => 'required|string|max:255',
        'resource_owner_id' => 'required',
        'resource_owner_name' => 'required|string',


        'method' => 'required|string',
        'received_by' => 'required|string',
        'received_at' => 'required|string',
    ],$messages);

    if ($validate->fails()) { 
              
            return response()->json(['message'=>$validate->errors()->first(),'success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);            
        }

    $input = $request->all();

        $attachments = [];

        $files = $request->file;
        $old_attachments =json_decode($request->attachment);
        $length_attachments = 0;
        $length_files = 0;

        $length_attachments =  count($old_attachments);
        
        if($files != null){
            $length_files =  count($files);
            
        }
        if($length_files == 0 && $length_attachments == 0){
            return response()->json(['message'=>'Atleast One attachment Required','success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);            

            
        }


        foreach ($old_attachments as $file) {
            $attachments[] =  $file;
        }

        if($files != null)
        {
            foreach($files as $file)
            {
               $attachments[] =  $this->img_upload($file);

            }
        }


    $input['updated_by'] = Auth::user()->name;

    $input['attachment'] = json_encode($attachments);
    $id = null;
    if($request->id != null && $request->id != ''&& $request->id != 0){
        $id = $request->id;
    }
    $Document = Document::updateOrCreate(['id' => $id],$input);
    $Document->attachment = json_decode($Document->attachment); 

    $user = JWTAuth::user();
    $action = 'Updated';
    $itemName = $Document->recipient_type.' Document';
    $itemUnique = $Document->id;
    $tableName = 'documents';
    recordActivity($user,$action,$itemName,$itemUnique,$tableName);

    
    return response()->json(['message'=>'Document Updated','success' => true,'data' => json_decode(json_encode($Document))]);

}




public function document_management_export_excel(Request $request){
        
    return Excel::download(new DocumetManagementExport($request->searchTerm,(int)$request->limit), 'document_management.xlsx');

}





public function document_management_export_pdf(Request $request)
{

    $PaymentRequest = Document::where(DB::raw("CONCAT(`id`, ' ',`recipient`,' ',`recipient_type`,' ',`resource_owner_id`,' ',`resource_owner_name`,' ',`recipient`)"), 'LIKE', "%" . $request->searchTerm . "%")->orderBy('id','desc')->take($request->limit )->get();
    // return $PaymentRequest;


    PDF::SetTitle('Payment Request');
    PDF::AddPage('Landscape', 'A4');
    
    // PDF::SetMargins(10, 30, 10);
    PDF::SetMargins(5, 20, 5, true);
    // PDF::SetAutoPageBreak(true, 10);



    PDF::SetFont('dejavusans', '', 8);
   

    $html = '';


    $html = view('print.document_management', compact('PaymentRequest'))->render();

    $bMargin = PDF::getBreakMargin();

    $auto_page_break = PDF::getAutoPageBreak();

    PDF::SetFooterMargin(50);
    // PDF::SetAutoPageBreak(false, 0);
    // PDF::SetAutoPageBreak(true, 0);

    // PDF::Image('https://app.gulflamar.com/images/invoice/gl-back.png', 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
    // PDF::setImageScale(PDF_IMAGE_SCALE_RATIO);
    PDF::setPageMark();
    PDF::setPrintFooter(false);
    // PDF::setPrintFooter(false);
    //////////////////////////////

    PDF::writeHTML($html, true, true, true, false, '');


    PDF::Output('document_management-'.Str::random(10).'.pdf', 'I');

}










    function img_upload($tmpImage)

    {
        $image = $tmpImage;
        $file_fullname = $image->getClientOriginalName();
        $file_name = pathinfo($file_fullname, PATHINFO_FILENAME);
        $file_extension = $image->getClientOriginalExtension();
        $rand_namer = now();
        $rand_namer = preg_replace('/\s+/', '_', trim($rand_namer));
        $rand_namer = preg_replace('/:+/', '_', trim($rand_namer));
        $file_namefor_db =$file_name . '_' . $rand_namer . '.' . $file_extension;
        $file_namefor_db = preg_replace('/\s+/', '_', trim($file_namefor_db));

        $image-> storeAs('documents' ,$file_namefor_db,'public');
        $pic = ('storage/documents/' . $file_namefor_db);

        return $pic;
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
