<?php

namespace App\Http\Controllers\api;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Pagination\LengthAwarePaginator;

use Tymon\JWTAuth\Exceptions\JWTException;

use App\Models\User;
use App\Models\Document;
use App\Models\PaymentRequest;
use App\Models\Payment;


use App\Models\invoice_base;
use App\Models\invoice_expense;
use App\Models\expenseItem;

use App\Http\Resources\paymentDataResource;

use App\Models\employee;
use App\Models\vendor_base;
use App\Models\client_base;
use App\Models\accommodation_base;
use App\Models\Supplier;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use App\Http\Resources\invoiceResource;
use App\Http\Resources\updateInvoiceResource;
use App\Http\Resources\ExpenceItemResource;

use App\Exports\invoiceExport;
use App\Exports\expenceItemExport;


use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Crypt;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


use App\Models\userLevel;

use Validator;
use JWTAuth;
// use Auth;
use DB;
use PDF;


class paymentRequestController extends Controller
{
    public function addPaymentRequest(Request $request)
    {

        $messages = [
            'initiated_by.required'  => 'initiated_by must be required.',
            'total_amount.required'  => 'total_amount must be required.',
            'status.required'  => 'status must be required.',
            'date.required'  => 'Date must be required.',
        ];

        $validate = Validator::make($request->all(), [
            'initiated_by' => 'required|string',
            'total_amount' => 'required|numeric',
            'status' => 'required|string',
            'date' => 'required|string|max:255',

            // 'file.*' => 'required|mimes:pdf,doc,docx',
            // 'file.*' => 'required|file|max:10000|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar',

        ], $messages);

        if ($validate->fails()) {

            return response()->json(['message' => $validate->errors()->first(), 'success' => false, 'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);
        }

        $input = $request->all();
        $input['total_amount'] = (float)$input['total_amount'];
        $input['created_by'] = Auth::user()->name;
        $input['updated_by'] = null;



        foreach (json_decode($request->items) as $item) {

            $files = null;

            if (isset($item)) {

                $validate =  $this->IsValidatePaymentItem($item);
                if ($validate['success'] == false) {
                    return response()->json(['message' => $validate['message'], 'success' => false, 'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);
                }

                if ($item->attachment_length > 0 && $item->attachment_file_name != null) {

                    if ($request->hasFile($item->attachment_file_name)) {

                        $files = $request->file($item->attachment_file_name);
                    }
                }

                if ($files != null) {
                    // For file validation as per payment item
                    $validate_temp = Validator::make($request->all(), [
                        $item->attachment_file_name . '.*' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar',
                    ]);

                    if ($validate_temp->fails()) {

                        return response()->json(['message' => $validate_temp->errors()->first(), 'success' => false, 'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);
                    }
                }
            }
        }


        $PaymentRequest = PaymentRequest::create($input);


        foreach (json_decode($request->items) as $item) {


            if (isset($item)) {
                $files = null;
                if ($request->hasFile($item->attachment_file_name)) {

                    $files = $request->file($item->attachment_file_name);
                }


                $attachments = [];

                if ($files != null) {

                    // set all file name's with path into array and json_encode to store in DB 
                    foreach ($files as $file) {
                        $attachments[] =  $this->img_upload($file);
                    }
                }
                $item->attachment = [];

                $item->attachment = json_encode($attachments);


                $item->request_payment_id = $PaymentRequest->id;
                $item->created_by = Auth::user()->name;

                $Payment = Payment::create(json_decode(json_encode($item), true));
            }
        }








        $user = JWTAuth::user();
        $action = 'CREATED';
        $itemName = $PaymentRequest->initiated_by . ' Payment Request';
        $itemUnique = $PaymentRequest->id;
        $tableName = 'payment_requests && payments';
        recordActivity($user, $action, $itemName, $itemUnique, $tableName);

        return response()->json(['message' => 'Payment Request Created', 'success' => true, 'data' => json_decode(json_encode($PaymentRequest))]);
    }




    public function getAllPymentRequests(Request  $request)
    {
        $advanceSearch = json_decode($request->advanceSearch);
        $query = PaymentRequest::query();
        $advance_query = Payment::query();
        $IsAdvanceSearch = false;
        $documentRecords = null;



        if ($request->searchTerm != '' && $request->searchTerm != null && $request->searchTerm) {
            // $query->where(DB::raw("CONCAT(`request_payment_id`, ' ', `request_owner`)"), 'LIKE', "%" . $request->searchTerm . "%");
            $query->orWhere('id', $request->searchTerm);
            $query->orWhere('status', $request->searchTerm);
            $query->orWhere('total_amount', $request->searchTerm);
        }



        if ($advanceSearch->request_type != '' && $advanceSearch->request_type != null) {
            $IsAdvanceSearch = true;

            $advance_query->where('request_type', $advanceSearch->request_type);
        }
        if ($advanceSearch->request_owner_id != ''  && $advanceSearch->request_owner != '' && $advanceSearch->request_owner != null) {
            $IsAdvanceSearch = true;
            $advance_query->where('request_owner', $advanceSearch->request_owner);
            $advance_query->where('request_owner_id', $advanceSearch->request_owner_id);
        }
        if ($advanceSearch->payment_type != '' && $advanceSearch->payment_type != null) {
            $IsAdvanceSearch = true;
            $advance_query->where('payment_type', $advanceSearch->payment_type);
        }
        if ($advanceSearch->status != '' && $advanceSearch->status != null) {
            $IsAdvanceSearch = true;
            $advance_query->where('status', $advanceSearch->status);
        }


        if ($IsAdvanceSearch == true) {

            $documentRecords = $advance_query->latest()->get(['id', 'request_payment_id']);
            $request_payment_id_array = [];
            $AllData = [];

            foreach ($documentRecords as $documentRecord) {
                $request_payment_id_array[] = $documentRecord->request_payment_id;
            }
            $request_payment_id_array = array_unique($request_payment_id_array);

            foreach ($request_payment_id_array as $request_payment_id) {
                $temp_array = null;
                $temp_array = $documentRecords->where('request_payment_id', $request_payment_id)->all();
                $payment_id_array = [];
                foreach ($temp_array as $t_array) {
                    $payment_id_array[] = $t_array->id;
                }
                $payment_id_array = array_unique($payment_id_array);
                $payemnt_req = PaymentRequest::where('id', $request_payment_id)->first();
                $payemnt_req->payments = Payment::whereIn('id', $payment_id_array)->latest()->get();
                foreach ($payemnt_req->payments as $payment) {
                    $payment->attachment = json_decode($payment->attachment);
                }
                // $payemnt_req->payments = json_decode($payemnt_req->payments);
                $AllData[] = $payemnt_req;
            }
        }



        if (isset($AllData)) {
            $totalRows = count($request_payment_id_array);
            $documentRecords = $AllData;
        } else {

            $documentRecords = clone $query;

            $totalRows = $documentRecords->count();
            $documentRecords = $query->with('Payments')->latest()->get();
            foreach ($documentRecords as $documentRecord) {
                // $documentRecord['resource_owner'] = $documentRecord->resource_owner_name .' , '.$documentRecord->resource_owner_id;

                foreach ($documentRecord->Payments as $payment) {
                    $payment['attachment'] = json_decode($payment->attachment);
                }
                // $documentRecord->attachment = json_decode($documentRecord->attachment);
            }
        }




        $resourceRecords = $this->paginaterhelp($request->page_no, $documentRecords, $request);


        return paymentDataResource::collection($resourceRecords)->additional(['success' => true, 'base_url' => url('/'), 'success' => true, 'totalRows' => $totalRows, 'Advance Search' => $IsAdvanceSearch, 'message' => 'Payment Request fetched.']);
    }










    public function getPaymentRequest(Request  $request)
    {

        $PaymentRequest = PaymentRequest::where('id', $request->id)->with('Payments')->first();
        foreach ($PaymentRequest->Payments as $payment) {
            $payment['attachment'] = json_decode($payment->attachment);
            $payment['filteredOptions'] = [];
            $payment['new_attachment'] = [];
            $payment['attachment_file_name'] = '';
            $payment['attachment_length'] = 0;
        }

        return response()->json(['message' => 'Payment Request fetched', 'success' => true, 'base_url' => url('/'), 'data' => json_decode(json_encode($PaymentRequest))]);
    }






    public function updatePaymentRequest(Request $request)
    {




        $messages = [
            'initiated_by.required'  => 'initiated_by must be required.',
            'total_amount.required'  => 'total_amount must be required.',
            'status.required'  => 'status must be required.',
            'date.required'  => 'Date must be required.',


        ];

        $validate = Validator::make($request->all(), [
            'initiated_by' => 'required|string',
            'total_amount' => 'required|numeric',
            'status' => 'required|string',
            'date' => 'required|string|max:255',

            // 'file.*' => 'required|mimes:pdf,doc,docx',
            // 'file.*' => 'required|file|max:10000|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar',

        ], $messages);

        if ($validate->fails()) {

            return response()->json(['message' => $validate->errors()->first(), 'success' => false, 'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);
        }

        $input = $request->all();

        $diffArray = null;

        $input['total_amount'] = (float)$input['total_amount'];

        // $input['created_by'] = Auth::user()->name;
        $input['updated_by'] = Auth::user()->name;

        $length_items =  count(json_decode($request->items));
        if ($length_items == 0) {
            // if items length == 0 , user remove all items from frontend
            $PaymentRequest = PaymentRequest::where('id', $request->id)->with('Payments')->first();
            if (isset($PaymentRequest)) {

                foreach ($PaymentRequest->payments as $sub_payment) {
                    $sub_payment->attachment = json_decode($sub_payment->attachment);

                    foreach ($sub_payment->attachment as $sub_payment_attachment) {
                        if (File::exists($sub_payment_attachment)) {
                            File::delete($sub_payment_attachment);
                        }
                    }
                    $deletePayment = Payment::where('id', $sub_payment->id)->first();
                    $deletePayment->delete();
                }
            }
        } else {
            // if items length greater than 0

            foreach (json_decode($request->items) as $item) {

                $files = null;

                if (isset($item)) {

                    $validate =  $this->IsValidatePaymentItem($item);
                    if ($validate['success'] == false) {
                        return response()->json(['message' => $validate['message'], 'success' => false, 'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);
                    }

                    if ($item->attachment_length > 0 && $item->attachment_file_name != null) {    // if attachment is present

                        if ($request->hasFile($item->attachment_file_name)) {

                            $files = $request->file($item->attachment_file_name);
                        }
                    }

                    if ($files != null) {
                        // For file validation as per payment item
                        $validate_temp = Validator::make($request->all(), [
                            $item->attachment_file_name . '.*' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar',
                        ]);

                        if ($validate_temp->fails()) {

                            return response()->json(['message' => $validate_temp->errors()->first(), 'success' => false, 'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))]);
                        }
                    }
                }
            }




            $PaymentRequest = PaymentRequest::updateOrCreate([
                'id' => $request->id
            ], $input);
            $Payments_id_array = Payment::where('request_payment_id', $PaymentRequest->id)->pluck('id')->toArray();
            $newPayments_id_array = [];

            foreach (json_decode($request->items) as $item) {


                if (isset($item)) {
                    $files = null;
                    if ($request->hasFile($item->attachment_file_name)) {

                        $files = $request->file($item->attachment_file_name);
                    }


                    $attachments = [];
                    // $old_attachments =json_decode($item->attachment);
                    $old_attachments = $item->attachment;

                    foreach ($old_attachments as $file) {
                        $attachments[] =  $file;
                    }

                    if ($files != null) {

                        // set all file name's with path into array and json_encode to store in DB 
                        foreach ($files as $file) {
                            $attachments[] =  $this->img_upload($file);
                        }
                    }
                    $item->attachment = [];

                    $item->attachment = json_encode($attachments);



                    if (isset($item->id)) {

                        $newPayments_id_array[] = $item->id;

                        $item->updated_by = Auth::user()->name;
                        $Payment = Payment::updateOrCreate([
                            'id' => $item->id
                        ], json_decode(json_encode($item), true));
                    } else {
                        $item->request_payment_id = $PaymentRequest->id;
                        $item->created_by = Auth::user()->name;
                        $Payment = Payment::create(json_decode(json_encode($item), true));
                    }
                }
            }


            $diffArray =  array_diff_assoc($Payments_id_array, $newPayments_id_array);
            if (isset($diffArray)) {
                foreach ($diffArray as $key => $value) {
                    $deletePayment = Payment::where('id', $value)->first();

                    $deletePayment->attachment = json_decode($deletePayment->attachment);
                    foreach ($deletePayment->attachment as $sub_payment_attachment) {
                        if (File::exists($sub_payment_attachment)) {
                            File::delete($sub_payment_attachment);
                        }
                    }
                    $deletePayment->delete();
                }
            }
        }

        $user = JWTAuth::user();
        $action = 'Updated';
        $itemName = $PaymentRequest->initiated_by . ' Payment Request';
        $itemUnique = $PaymentRequest->id;
        $tableName = 'payment_requests && payments';
        recordActivity($user, $action, $itemName, $itemUnique, $tableName);

        return response()->json(['message' => 'Payment Request Updated', 'success' => true, 'data' => json_decode(json_encode($PaymentRequest))]); //PaymentRequest  $diffArray

    }






    function IsValidatePaymentItem($item)
    {
        $item = json_decode(json_encode($item), true);


        $messages = [
            'request_type.required'  => 'Request Type Of All Payments must be required.',
            'request_owner.required'  => 'Resource Owner Of All Payments must be required.',
            'request_owner_id.required'  => 'Resource Owner Id Of All Payments must be required.',
            'payment_type.required'  => 'Payment Type Of All Payments must be required.',
            'amount.required'  => 'Amount Of All Payments must be required.',
            'status.required'  => 'Status Of All Payments must be required.',
        ];

        $validate = Validator::make($item, [
            'request_type' => 'required|string',
            'request_owner' => 'required|string',
            'request_owner_id' => 'required|numeric',
            'payment_type' => 'required|string',
            'amount' => 'required|numeric',
            'status' => 'required|string',

            // 'file.*' => 'required|file|max:10000|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar',

        ], $messages);


        if ($validate->fails()) {

            return ['message' => $validate->errors()->first(), 'success' => false];
        } else {
            return ['message' => '', 'success' => true];
        }
    }








    public function deletePayment(Request  $request)
    {

        $PaymentRequest = PaymentRequest::where('id', $request->id)->with('Payments')->first();

        foreach ($PaymentRequest->payments as $sub_payment) {
            $sub_payment->attachment = json_decode($sub_payment->attachment);

            foreach ($sub_payment->attachment as $sub_payment_attachment) {
                if (File::exists($sub_payment_attachment)) {
                    File::delete($sub_payment_attachment);
                }
            }
            $deletePayment = Payment::where('id', $sub_payment->id)->first();
            $deletePayment->delete();
        }




        $user = JWTAuth::user();
        $action = 'Deleted';
        $itemName = $PaymentRequest->initiated_by . ' Payment Request';
        $itemUnique = $PaymentRequest->id;
        $tableName = 'payment_requests && payments';
        recordActivity($user, $action, $itemName, $itemUnique, $tableName);



        $PaymentRequest->delete();




        return response()->json(['message' => 'Payment Request Deleted Successfully', 'success' => true]);
    }








    public function printPayment(Request $request, $id)
    {

        $PaymentRequest = PaymentRequest::where('id', $id)->with('Payments')->first();
        // return $PaymentRequest;


        PDF::SetTitle('Payment Request');
        PDF::AddPage('Landscape', 'A4');
        
        // PDF::SetMargins(10, 30, 10);
        PDF::SetMargins(5, 20, 5, true);
        // PDF::SetAutoPageBreak(true, 10);



        PDF::SetFont('dejavusans', '', 8);
        // PDF::SetFont('aefurat', '', 8);




        // $fontname = PDF::addTTFfont('../../../public/Cairo-VariableFont_wght.ttf', 'TrueTypeUnicode', '', 96);

        // use the font
        // PDF::SetFont($fontname, '', 14, '', false);





        // PDF::AddPage();




        $html = '';
        foreach($PaymentRequest->payments as $payment){
            if($payment->status == "Paid"){
                $payment->status = 'مدفوع'.'<br>'.$payment->status;
                $payment->color = '';


            }else{
                $payment->status = 'غیر مدفوعة'.'<br>'.$payment->status;
                $payment->color = 'color:red;';

            }




            $payment->bank_name = '';
            $payment->account_name = '';
            $payment->iban = '';
            $payment->payment_type_check = $payment->payment_type;

            if($payment->payment_type == "Bank Transfer"){

               $documentResourceOwner = $this->documentResourceOwner($payment->request_type,$payment->request_owner_id);
               if(isset($documentResourceOwner)){
                   $payment->bank_name = $documentResourceOwner->bankName;
                   $payment->account_name = $documentResourceOwner->accountName;
                       if(isset($documentResourceOwner->iban)){
                           $payment->iban = $documentResourceOwner->iban;
                       }
               }


            }
            
            if($payment->payment_type == "Cash"){
                $payment->payment_type = 'نقدا'.'<br>'.$payment->payment_type;


            }else{
                $payment->payment_type = 'حوالة بنكية'.'<br>'.$payment->payment_type;

            }
            
        }
        // return $PaymentRequest;

        $html = view('print.payment', compact('PaymentRequest'))->render();

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


        PDF::Output('payment-request-'.Str::random(10).'.pdf', 'I');
        // https://app.gulflamar.com/images/invoice/gl-back.png

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
        $file_namefor_db = $file_name . '_' . $rand_namer . '.' . $file_extension;
        $file_namefor_db = preg_replace('/\s+/', '_', trim($file_namefor_db));

        $image->storeAs('payments', $file_namefor_db, 'public');
        $pic = ('storage/payments/' . $file_namefor_db);

        return $pic;
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








     function documentResourceOwner($type,$id){

        $userLevels = null;
        if($type == "Accommodation"){
            
           $userLevels = accommodation_base::where('id',$id)->first();

            
        } else if($type == "Client"){ 

            $userLevels = client_base::where('client_code',$id)->first();


        } else if($type == "Employee"){
        
            $userLevels = employee::where('emp_id',$id)->first();


        } else if($type == "Supplier"){

            $userLevels = Supplier::where('id',$id)->first();
            $userLevels->bankName = $userLevels->bank_name;
            $userLevels->accountName = $userLevels->account_name;

        } else if($type == "Vendor"){ 

           $userLevels = vendor_base::where('id',$id)->first();

        }

        return $userLevels;



}




}
