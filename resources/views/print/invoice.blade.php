

<!-- EXAMPLE OF CSS STYLE -->
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
</style>

<div class="shuppin-table" style="font-size: 14px; width:2480px;">
<table style="width: 100%;" cellpadding="5px">
<tbody>
<tr>
<td style="border-bottom: 2px solid #035f8e; width: 50%; text-align: left;">
<h1><strong><span style="color: #035f8e;">Tax Invoice</span></strong></h1>
</td>
<td style="border-bottom: 2px solid #035f8e; width: 50%; text-align: right;" colspan="2">
<h1><strong><span style="color: #035f8e;">فاتورة ضريبية</span></strong></h1>
</td>
</tr>
<tr>
<td style="background-color: #035f8e;">
<p><strong><span style="color: #fff;">Gulf Lamar for Operations &amp; Maintenance</span></strong></p>
</td>
<td><span style="color: #035f8e;"><strong>Invoice No.</strong></span></td>
<td><span style="color: #035f8e;"><strong>{{$invoice->invoice_number ?? ''}}</strong></span></td>
</tr>
<tr>
<td style="background-color: #035f8e;">
<p><strong><span style="color: #fff;">VAT #&nbsp;300575364500003</span></strong></p>
</td>
<td><span style="color: #035f8e;"><strong>Invoice Date:</strong></span></td>
<td><span style="color: #035f8e;"><strong>{{date("d-m-Y", strtotime($invoice->invoice_date))}}</strong></span></td>
</tr>
</tbody>
</table>
<br /><br />
<table style="width: 100%; border: 1px solid #ccc;" cellpadding="5px">
<tbody>
<tr>
<td style="width: 17%; background-color: #ccc; border-bottom: 1px solid #fff;"><strong>Client</strong></td>
<td style="border-bottom: 1px solid #ccc; width: 28%;">{{$invoice->client->client_name ?? ''}}</td>
<td style="border-bottom: 1px solid #ccc; text-align: center; width: 8%; color: #ff0000;"><strong>{{$invoice->client->client_code ?? ''}}</strong></td>
<td style="border-bottom: 1px solid #ccc; text-align: right; width: 30%;">{{$invoice->client->client_name_ar ?? ''}}</td>
<td style="background-color: #ccc; border-bottom: 1px solid #fff; text-align: right; width: 17%;"><strong>اسم العميل</strong></td>
</tr>
<tr>
<td style="background-color: #ccc; border-bottom: 1px solid #fff;"><strong>VAT Number</strong></td>
<td style="border-bottom: 1px solid #ccc;">{{$invoice->client->vat_no ?? ''}}</td>
<td style="border-bottom: 1px solid #ccc;">&nbsp;</td>
<td style="border-bottom: 1px solid #ccc; text-align: right;">{{$invoice->client->vat_no ?? ''}}</td>
<td style="background-color: #ccc; border-bottom: 1px solid #fff; text-align: right;"><strong>رقم ضريبة القيمة المضافة</strong></td>
</tr>
<tr>
<td style="background-color: #ccc; border-bottom: 1px solid #fff;"><strong>CR Number</strong></td>
<td style="border-bottom: 1px solid #ccc;">{{$invoice->client->cr_no ?? ''}}</td>
<td style="border-bottom: 1px solid #ccc;">&nbsp;</td>
<td style="border-bottom: 1px solid #ccc; text-align: right;">{{$invoice->client->cr_no ?? ''}}</td>
<td style="background-color: #ccc; border-bottom: 1px solid #fff; text-align: right;"><strong>رقم السجل التجاري</strong></td>
</tr>
<tr>
<td style="background-color: #ccc; border-bottom: 1px solid #fff;"><strong>Department</strong></td>
<td style="border-bottom: 1px solid #ccc;">{{$invoice->client->dept_en ?? ''}}</td>
<td style="border-bottom: 1px solid #ccc;">&nbsp;</td>
<td style="border-bottom: 1px solid #ccc; text-align: right;">{{$invoice->client->dept_ar ?? ''}}</td>
<td style="background-color: #ccc; border-bottom: 1px solid #fff; text-align: right;"><strong>قسم</strong></td>
</tr>
<tr>
<td style="background-color: #ccc; border-bottom: 1px solid #fff;"><strong>Address</strong></td>
<td style="border-bottom: 1px solid #ccc;">{{$invoice->client->address ?? ''}}</td>
<td style="border-bottom: 1px solid #ccc;">&nbsp;</td>
<td style="border-bottom: 1px solid #ccc; text-align: right;">{{$invoice->client->address ?? ''}}</td>
<td style="background-color: #ccc; border-bottom: 1px solid #fff; text-align: right;"><strong>عنوان</strong></td>
</tr>
<tr>
<td style="background-color: #ccc; border-bottom: 1px solid #fff;"><strong>Location</strong></td>
<td style="border-bottom: 1px solid #ccc;">{{$invoice->location_en ?? ''}}</td>
<td style="border-bottom: 1px solid #ccc;">&nbsp;</td>
<td style="border-bottom: 1px solid #ccc; text-align: right;">{{$invoice->location_en ?? ''}}</td>
<td style="background-color: #ccc; border-bottom: 1px solid #fff; text-align: right;"><strong>موقع</strong></td>
</tr>
<tr>
<td style="background-color: #ccc;"><strong>Period</strong></td>
<td>{{$invoice->month ?? ''}} / {{$invoice->year ?? ''}}</td>
<td>&nbsp;</td>
<td style="text-align: right;">{{$invoice->year ?? ''}} / {{$invoice->month ?? ''}}</td>
<td style="text-align: right; background-color: #ccc;"><strong>فترة</strong></td>
</tr>
</tbody>
</table><br /><br />
<table style="width: 100%;" cellpadding="5px">
<tbody>
<tr style="border: 1px solid #ccc;">
<td style="border: 1px solid #fff; width: 11%; background-color: #ccc;">
<p><strong>ID #</strong><br /><strong>مسلسل</strong></p>
</td>
<td style="border: 1px solid #fff; width: 35%; background-color: #ccc;"><strong>DESCRIPTION</strong><br /><strong>الوصف</strong></td>
<td style="border: 1px solid #fff; width: 18%; background-color: #ccc;"><strong>QUANTITY</strong><br /><strong>العدد</strong></td>
<td style="border: 1px solid #fff; width: 18%; background-color: #ccc;"><strong>PRICE</strong><br /><strong>السعر</strong></td>
<td style="border: 1px solid #fff; width: 18%; background-color: #ccc;"><strong>TOTAL</strong><br /><strong>الاجمالى</strong></td>
</tr>
@foreach ($invoice->invoiceExpences as $key => $expence)

<tr style="">
	<td align="left" style=" padding: 15px 0px 15px 5px; border: 1px solid #ccc;">{{$key+1}}</td>  
	<td align="left" style=" padding: 15px 0px 15px 5px; border: 1px solid #ccc;">
			{{$expence->expenseitem->name}} <div align="right"> {{ $expence->expenseitem->name_ar}} </div>
	</td>
	<td align="right" style=" padding: 15px 0px 15px 5px; border: 1px solid #ccc;">{{number_format( $expence->quantity, 2 )}}</td>  
	<td align="right" style=" padding: 15px 0px 15px 5px; border: 1px solid #ccc;">SR {{number_format( $expence->amount, 2 )}}</td>
	<td align="right" style=" padding: 15px 0px 15px 5px; border: 1px solid #ccc;">SR {{number_format( $expence->amount * $expence->quantity, 2 )}}</td>  
</tr>


@endforeach
<tr height="40px">
<td rowspan="4" colspan="2" >
        
        <table style=" margin: auto;border: 2px solid #06608f;width: 120px;text-align: center;">
            <tr><td colspan="3" style="text-align: center; "><h4 style="color: #06608f;margin-bottom: 5px;">Scan for more info</h4>  </td></tr>
            <tr>
                
                <td colspan="3"> <img   src="https://app.gulflamar.com/storage/qrImage/qrcode.svg" > </td>
                
            </tr>
            <tr><td colspan="3"></td></tr>
        </table>
</td>
<td style="border: 1px solid #fff; text-align: center; background-color: #ccc;" colspan="2"><strong>Subtotal</strong><br /><strong>مبلغ إجمالي</strong></td>
<td style="border: 1px solid #fff; text-align: center; vertical-align: middle; background-color: #ccc;">SR {{number_format( $invoice->subtotal, 2 )}}</td>
</tr>
<tr>
<td style="border: 1px solid #fff; text-align: center; background-color: #ccc;" colspan="2"><strong>Value Added Tax (15%)</strong><br /><strong>ضريبة القيمه المضافه</strong></td>
<td style="border: 1px solid #fff; text-align: center; vertical-align: middle; background-color: #ccc;">SR {{number_format( $invoice->vat, 2 )}}</td>
</tr>
<tr>
<td style="border: 1px solid #fff; text-align: center; background-color: #ccc;" colspan="2"><strong>Net Total</strong><br /><strong>الاجمالى بعد الضريبه</strong></td>
<td style="border: 1px solid #fff; text-align: center; vertical-align: middle; background-color: #ccc;">SR {{number_format( $invoice->net_total, 2 )}}</td>
</tr>
<tr><td colspan="3" ></td></tr>
<tr>
<td style="text-align: left;" colspan="2"><span style="color: #016492;">Thank you for choosing Gulf Lamar</span></td>
<td></td>
<td style="text-align: right;" colspan="2"><span style="color: #016492;">شكرا لاختياركم لامار الخليجية</span></td>
</tr>
</tbody>
</table>
		   
<table style="width: 100%; margin-left: auto; margin-right: auto;">
<tbody>
<tr>
<td style="width: 25%; text-align: center; vertical-align: middle;"><img src="https://app.gulflamar.com/images/invoice/accounts_sign.png" width="74" height="73" /></td>
<td style="width: 12.5%; text-align: center; vertical-align: middle;">&nbsp;</td>
<td style="width: 25%; text-align: center; vertical-align: middle;"><img src="https://app.gulflamar.com/images/invoice/project_sign.png" alt="" width="121" height="73" /></td>
<td style="width: 12.5%; text-align: center; vertical-align: middle;">&nbsp;</td>
<td style="width: 25%; text-align: center; vertical-align: middle;">&nbsp;</td>
</tr>
<tr>
<td style="border-top: 1px solid #000000; text-align: center;">Accounts Department</td>
<td>&nbsp;</td>
<td style="border-top: 1px solid #000000; text-align: center;">Project Management</td>
<td>&nbsp;</td>
<td style="border-top: 1px solid #000000; text-align: center;">Invoice Receiving</td>
</tr>
</tbody>
</table>
</div>