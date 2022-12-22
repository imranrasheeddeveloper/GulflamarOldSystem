


<style>
    @charset "utf-8";
    body {
        margin: 0;
    }
    body, table, td, p, a, li, blockquote {
        -webkit-text-size-adjust: none!important;
        
        font-style: normal;
        font-weight: 400;
    }
   tr td{
        font-size: 10px
    }

    </style>
    
    <div class="shuppin-table" style="font-size: 14px; width:2480px;">
    <table style="width: 100%;" cellpadding="5px">

    <table style="width: 100%; margin-left: auto; margin-right: auto;padding-bottom:20px;">
        <tbody>
        <tr style="">
        <td style="width: 33%; text-align: center; vertical-align: middle;"><img src="https://i.ibb.co/WcmVhRL/main-Logo1.jpg" width="150" height="50" /></td>
        <td style="width: 33%; text-align: center; vertical-align: middle;font-weight:bold;font-size:15px"><h5><b><strong>Document Management</strong></b></h5></td>
        {{-- <td style="width: 33%; text-align: center; vertical-align: middle;">
            <table style="width: 100%; margin-left: auto; margin-right: auto;">
                <tbody>
                <tr style="border:1px solid black;">
                <td style="border:1px solid black; width: 30%; text-align: center; vertical-align: middle;font-weight:bold;background-color: #3d85c6; color:white;" ><strong><b>No.</b></strong></td>
                <td style="border:1px solid black; width: 70%; text-align: center; vertical-align: middle;">{{$PaymentRequest->id}}</td>
                </tr>

                <tr style="border:1px solid black;">
                    <td style="border:1px solid black; width: 30%; text-align: center; vertical-align: middle;font-weight:bold;background-color: #3d85c6; color:white;"><strong><b>Date</b></strong></td>
                    <td style="border:1px solid black; width: 70%; text-align: center; vertical-align: middle;">{{$PaymentRequest->date}}</td>
                    </tr>
                    <tr style="border:1px solid black;">
                        <td style="border:1px solid black; width: 30%; text-align: center; vertical-align: middle;font-weight:bold;background-color: #3d85c6; color:white;"><strong><b>Total Amount</b></strong></td>
                        <td style="border:1px solid black; width: 70%; text-align: center; vertical-align: middle;color:red;" align="center">{{$PaymentRequest->total_amount}}</td>
                        </tr>
                </tbody>
                </table>
            </td> --}}
        </tr>
        </tbody>
        </table>
    <table style="width: 100%;" cellpadding="5px">
    <tbody>
    <tr style="border: 1px solid #ccc;">
    <td style="border: 1px solid #fff; width: 4%; background-color: #ccc;" align="center">
    {{-- <strong>ID #</strong><br /><strong>مسلسل</strong> --}}
    <strong>#</strong>
    </td>
    <td align="center" style="border: 1px solid #fff; width: 15%; background-color: #ccc;"><strong>Recipient</strong><br /><strong>المستفید</strong></td>
    <td align="center" style="border: 1px solid #fff; width: 15%; background-color: #ccc;"><strong>Recipient Type</strong><br /><strong> نوع المستفید</strong></td>
    <td align="center" style="border: 1px solid #fff; width: 10%; background-color: #ccc;"><strong>Document Type</strong></td>

    <td align="center" style="border: 1px solid #fff; width: 10%; background-color: #ccc;"><strong>Method</strong><br /><strong>طریقة</strong></td>
    <td align="center" style="border: 1px solid #fff; width: 15%; background-color: #ccc;"><strong>Date</strong></td>
    <td align="center" style="border: 1px solid #fff; width: 15%; background-color: #ccc;"><strong>Received at</strong></td>
    <td align="center" style="border: 1px solid #fff; width: 15%; background-color: #ccc;"><strong>Received by</strong></td>


    {{-- <td align="center" style="border: 1px solid #fff; width: 15%; background-color: #ccc;"><strong>Description</strong></td> --}}


    </tr>
    @foreach ($PaymentRequest as $key => $payment)
    
    <tr style="">
        <td align="center" style=" padding: 15px 0px 15px 5px; border: 1px solid #ccc;">{{$key+1}}</td>  
        <td align="center" style=" padding: 15px 0px 15px 5px; border: 1px solid #ccc;">{{$payment->resource_owner_name}} , {{$payment->resource_owner_id}}</td>  
        <td align="center" style=" padding: 15px 0px 15px 5px; border: 1px solid #ccc;">{{$payment->recipient_type}}</td>  
        <td align="center" style=" padding: 15px 0px 15px 5px; border: 1px solid #ccc;">{{$payment->recipient}}</td>  

        <td align="center" style=" padding: 15px 0px 15px 5px; border: 1px solid #ccc;">{{$payment->method}}</td>  
        <td align="center" style=" padding: 15px 0px 15px 5px; border: 1px solid #ccc;">{{$payment->date}}</td>  
        <td align="center" style=" padding: 15px 0px 15px 5px; border: 1px solid #ccc;">{{$payment->received_at}}</td>  
        <td align="center" style=" padding: 15px 0px 15px 5px; border: 1px solid #ccc;">{{$payment->received_by}}</td>  



         
        {{-- <td align="center" style=" padding: 15px 0px 15px 5px; border: 1px solid #ccc;">{{Str::limit($payment->description, 40 , ' ..')}}</td>   --}}
        
    </tr>
    
    
    @endforeach

    </tbody>
    </table>
               

    </div>
    
    
    
    
    