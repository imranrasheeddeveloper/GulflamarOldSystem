<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
// use JWTAuth;
// use Illuminate\Support\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class UserPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $user = Auth::user();
        $permissions = $this->get_user_level_permissions($user);

        $request_url = request()->path();
        $request_url = str_replace("api/", '', $request_url);

        // Routes_array have all routes/url that are allowed to access ,
        // if url matched and action also matched it will allow permission granted
        // if url matched but action not matched it will permission denied
        //  if the url is not  matched in this array then it will be also allowed to access , currently , in future we can add more url in this array


    

        //user_level_permissions checking url 
        // table => column name  in user_level_permissions table in database



        // Action

        //   C -> create, E-> edit, D-> delete,  V -> view, and some extra  L -> list, S -> search, A -> admin


        $view = 'V';    //for view listing 
        $create = 'C';  //for create item
        $edit = 'E';    //for edit item 
        $delete = 'D';  //for delete item 





        // for set table name , that we already set as column name in user_level_permissions table
        $employe = 'employees';
        $accommodation_base = 'accommodation';
        $rent_payment = 'rent_payment';
        $bill_payment = 'bill_payment';
        $client = 'client';
        $invoice = 'invoice';
        $bank_account = 'bank_account';
        $user_level = 'user_level';
        

        
        $document = 'document';
        $payment = 'payment';
        $supplier = 'supplier';
        $supplier_type = 'supplier_type';
        $wallet = 'wallets';
        $petty_cash = 'petty_cash';
        $petty_cash_approve = 'petty_cash_approve';
        $bank_account = 'bank_account';
        $call_center = 'call_center';
        $vendor = 'vendor';
        $my_bank_account = 'my_bank_account';
        $purchase_order = 'purchase_order';
        $user = 'user';
        $expense_accounts = 'expense_accounts';
        $resource = 'resource';
        $assets_managments = 'assets_managments';
        $asset_maintenances = 'asset_maintenances';
        $asset_work_orders = 'asset_work_orders';


        $Routes_array = array(


            // for employee 

            array("url" => 'getemployee', "table" => $employe, "action" => $view),
            array("url" => 'getEmployeeDetail', "table" => $employe, "action" => $edit),
            array("url" => 'createEmployee', "table" => $employe, "action" => $create),
            array("url" => 'deleteEmployee', "table" => $employe, "action" => $delete),


            //for accommodation

            array("url" => 'getAccommodation', "table" => $accommodation_base, "action" => $view),
            array("url" => 'getAccommodationDetail', "table" => $accommodation_base, "action" => $edit),
            array("url" => 'addAccommodation', "table" => $accommodation_base, "action" => $create),
            array("url" => 'deleteAccommodation', "table" => $accommodation_base, "action" => $delete),

            array("url" => 'getAccommodationPayment', "table" => $rent_payment, "action" => $view),
            array("url" => 'addAccommodationPayment', "table" => $rent_payment, "action" => $create),
            array("url" => 'getAccommodationBillPayment', "table" => $bill_payment, "action" => $view),



            //client

            array("url" => 'getClients', "table" => $client, "action" => $view),
            array("url" => 'getClientDetail', "table" => $client, "action" => $edit),
            array("url" => 'createClient', "table" => $client, "action" => $create),
            array("url" => 'deleteClient', "table" => $client, "action" => $delete),

            //invoice

            array("url" => 'getInvoices', "table" => $invoice, "action" => $view),
            array("url" => 'getInvoiceDetail', "table" => $invoice, "action" => $edit),
            array("url" => 'addInvoice', "table" => $invoice, "action" => $create),
            array("url" => 'deleteInvoice', "table" => $invoice, "action" => $delete),

            //bank_account


           array("url" => 'getAllBankAccounts', "table" => $bank_account, "action" => $view),
           array("url" => 'getBankAccount', "table" => $bank_account, "action" => $edit),
           array("url" => 'addBankAccount', "table" => $bank_account, "action" => $create),
           array("url" => 'deleteBankAccount', "table" => $bank_account, "action" => $delete),


           //calcenter
           array("url" => 'getAllCallCenterData', "table" => $call_center, "action" => $view),
           array("url" => 'getCallData', "table" => $call_center, "action" => $edit),
           array("url" => 'addCallCenter', "table" => $call_center, "action" => $create),
           array("url" => 'deleteCallCenter', "table" => $call_center, "action" => $delete),

          //vendor

          array("url" => 'getVendors', "table" => $vendor, "action" => $view),
          array("url" => 'getVendorDetail', "table" => $vendor, "action" => $edit),
          array("url" => 'addVendor', "table" => $vendor, "action" => $create),
          array("url" => 'deleteVendor', "table" => $vendor, "action" => $delete),


            
            
            // for document 
            
            array("url" => 'getAllDocuments', "table" => $document, "action" => $view),
            array("url" => 'createDocument', "table" => $document, "action" => $create),
            array("url" => 'getDocument', "table" => $document, "action" => $edit),
            array("url" => 'updateDocument', "table" => $document, "action" => $edit),
            array("url" => 'deleteDocument', "table" => $document, "action" => $delete),

            // for payment

            array("url" => 'getAllPymentRequests', "table" => $payment, "action" => $view),
            array("url" => 'addPaymentRequest', "table" => $payment, "action" => $create),
            array("url" => 'getPaymentRequest', "table" => $payment, "action" => $edit),
            array("url" => 'updatePaymentRequest', "table" => $payment, "action" => $edit),
            array("url" => 'deletePayment', "table" => $payment, "action" => $delete),


            // for Supplier

            array("url" => 'getAllSuppliers', "table" => $supplier, "action" => $view),
            array("url" => 'getAllSupplierType', "table" => $supplier_type, "action" => $view),
            
            array("url" => 'createSupplier', "table" => $supplier, "action" => $create),

            array("url" => 'getSupplier', "table" => $supplier, "action" => $edit),
            array("url" => 'getAllSupplierType', "table" => $supplier_type, "action" => $edit),

            array("url" => 'updateSupplier', "table" => $supplier, "action" => $edit),
            array("url" => 'deleteSupplier', "table" => $supplier, "action" => $delete),


            // for supplier type

            array("url" => 'getAllSupplierType', "table" => $supplier_type, "action" => $view),
            array("url" => 'addSupplierType', "table" => $supplier_type, "action" => $create),
            array("url" => 'getSupplierType', "table" => $supplier_type, "action" => $edit),
            array("url" => 'updateSupplierType', "table" => $supplier_type, "action" => $edit),
            array("url" => 'deleteSupplierType', "table" => $supplier_type, "action" => $delete),




            // for wallets 

            array("url" => 'getAllWallets', "table" => $wallet, "action" => $view),
            array("url" => 'addWallet', "table" => $wallet, "action" => $create),
            array("url" => 'updateWallet', "table" => $wallet, "action" => $edit),
            array("url" => 'deleteWallet', "table" => $wallet, "action" => $delete),



            // for petty Cash 


            array("url" => 'getAllTransaction', "table" => $petty_cash, "action" => $view),
            array("url" => 'addTransaction', "table" => $petty_cash, "action" => $create),
            array("url" => 'getTransaction', "table" => $petty_cash, "action" => $edit),
            array("url" => 'updateTransaction', "table" => $petty_cash, "action" => $edit),
            array("url" => 'deleteTransaction', "table" => $petty_cash, "action" => $delete),




            // for petty Cash Staus Update (approve/ not approve)


            // petty_cash_approve

            array("url" => 'updateTransactionStatus', "table" => $petty_cash_approve, "action" => $edit),

            // bank account


            array("url" => 'getAllBankAccounts', "table" => $bank_account, "action" => $view),
            array("url" => 'addBankAccount', "table" => $bank_account, "action" => $create),
            array("url" => 'updateBankAccount', "table" => $bank_account, "action" => $edit),
            array("url" => 'deleteBankAccount', "table" => $bank_account, "action" => $delete),

            // For bank account transaction

            array("url" => 'getAllBankTransactions', "table" => $bank_account, "action" => $view),
            array("url" => 'addBankAccountTransaction', "table" => $bank_account, "action" => $create),
            array("url" => 'updateBankAccountTransaction', "table" => $bank_account, "action" => $edit),
            array("url" => 'deleteBankAccountTransaction', "table" => $bank_account, "action" => $delete),


        //purchase_order

        array("url" => 'getPurchaseList', "table" => $purchase_order, "action" => $view),
        array("url" => 'createPurchase', "table" => $purchase_order, "action" => $create),
        array("url" => 'getPurchaseDetail', "table" => $purchase_order, "action" => $edit),
        array("url" => 'deletePurchase', "table" => $purchase_order, "action" => $delete),



       //getRoles
      array("url" => 'getRoles', "table" => $user_level, "action" => $view),
      array("url" => 'createRole', "table" => $user_level, "action" => $create),
      array("url" => 'getRoleDetail', "table" => $user_level, "action" => $edit),
      array("url" => 'deletePurchase', "table" => $user_level, "action" => $delete),

      //users
      array("url" => 'getUsers', "table" => $user, "action" => $view),
      array("url" => 'addUser', "table" => $user, "action" => $create),
      array("url" => 'getUserDetail', "table" => $user, "action" => $edit),
      array("url" => 'deletePurchase', "table" => $user, "action" => $delete),

      //expense_accounts
      
      array("url" => 'getExpenseAccounts', "table" => $expense_accounts, "action" => $view),
      array("url" => 'getExpenseAccounts', "table" => $expense_accounts, "action" => $create),
        
      //resource

      array("url" => 'getAllocatedResources', "table" => $resource, "action" => $view),
      array("url" => 'getAllocatedResourceDetail', "table" => $resource, "action" => $edit),
      array("url" => 'allocateResource', "table" => $resource, "action" => $create),

      
      // owner bank accounts


        array("url" => 'getAllOwnerBanks', "table" => $my_bank_account, "action" => $view),
        array("url" => 'addOwnerBank', "table" => $my_bank_account, "action" => $create),
        array("url" => 'getOwnerBank', "table" => $my_bank_account, "action" => $edit),
        array("url" => 'deleteOwnerBank', "table" => $my_bank_account, "action" => $delete),


        array("url" => 'getAsset', "table" => $assets_managments, "action" => $view),
        array("url" => 'createAsset', "table" => $my_bank_account, "action" => $create),
        array("url" => 'getAssetById', "table" => $my_bank_account, "action" => $edit),
        array("url" => 'delete_asset', "table" => $my_bank_account, "action" => $delete),


        array("url" => 'getAssetMaintenance', "table" => $asset_maintenances, "action" => $view),
        array("url" => 'addAssetMaintenance', "table" => $asset_maintenances, "action" => $create),
        array("url" => 'getAssetMaintenanceById', "table" => $asset_maintenances, "action" => $edit),
        array("url" => 'deleteAssetMaintenance', "table" => $asset_maintenances, "action" => $delete),

        array("url" => 'getWorkOrder', "table" => $asset_work_orders, "action" => $view),
        array("url" => 'addWorkOrder', "table" => $asset_work_orders, "action" => $create),
        array("url" => 'deleteWorkOrder', "table" => $asset_work_orders, "action" => $edit),
        array("url" => 'getWorkOrderById', "table" => $asset_work_orders, "action" => $delete),

        );

        foreach ($Routes_array as $route) {

            $temp_url_check = is_url_permission($request_url, $route['url']);
            if ($temp_url_check == "matched") { // if url match in given array

                $temp_action_check = is_url_permission($request_url, $route['url'], $route['table'], $route['action'], $permissions);

                if ($temp_action_check == "permission_granted") {
                    return $next($request);
                } else {
                    return response()->json(['message' => 'Permission denied on ' . $route['table'], 'success' => false, 'error' => 'You do not have permission to perform this action', 'data' => null,'permissions' => $permissions] , 403);

                }
            }
            // else if($temp_url_check == "not_matched"){


            // }

        }

        return $next($request);
    }

    public function get_user_level_permissions(User  $user)
    {

        $tmpPermissions = null;
        //   return $tmpPermissions =  $user->userLevel->permissions;
        if (isset($user)) {
            if (isset($user->userLevel)) {
                $tmpPermissions =  $user->userLevel->permissions;
            }
        }
        return $tmpPermissions;
    }
}

