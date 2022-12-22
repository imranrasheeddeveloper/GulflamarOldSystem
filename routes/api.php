<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\userLevelController;
use App\Http\Controllers\api\employeeController;
use App\Http\Controllers\api\resourceCenterController;
use App\Http\Controllers\api\purchasingController;
use App\Http\Controllers\api\accommodationController;
use App\Http\Controllers\api\vendorController;
use App\Http\Controllers\api\clientController;
use App\Http\Controllers\api\invoiceController;
use App\Http\Controllers\api\permissionController;
use App\Http\Controllers\api\auditController;
use App\Http\Controllers\api\settingController;
use App\Http\Controllers\api\dashboardController;
use App\Http\Controllers\api\supplierController;
use App\Http\Controllers\api\cityController;
use App\Http\Controllers\api\bankController;
use App\Http\Controllers\api\TaskController;
use App\Http\Controllers\api\DocumentController;
use App\Http\Controllers\api\paymentRequestController;
use App\Http\Controllers\api\walletController;
use App\Http\Controllers\api\PattyCashController;
use App\Http\Controllers\api\myWalletController;

use App\Http\Controllers\api\mypattyCashController;
use App\Http\Controllers\api\bankAccountController;
use App\Http\Controllers\api\ownerBanksController;
use App\Http\Controllers\api\ExpenseAccountController;
use App\Http\Controllers\api\CallCenterController;
use App\Http\Controllers\api\RequestCenterController;
use App\Http\Controllers\api\assetsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [UserController::class, 'login']);

Route::post('/signup', [UserController::class, 'signup']);

Route::get('/employeeCsv', [employeeController::class, 'employeeCsv']);
Route::get('/resourceCsv', [resourceCenterController::class, 'resourceCsv']);
Route::get('/accommodationCsv', [accommodationController::class, 'accommodationCsv']);
Route::get('/rentPaymentCsv', [accommodationController::class, 'rentPaymentCsv']);
Route::get('/billPaymentCsv', [accommodationController::class, 'billPaymentCsv']);
Route::get('/clientCsv', [clientController::class, 'clientCsv']);
Route::post('/documentCsv', [UserController::class, 'addUser']);
Route::get('/invoiceCsv', [invoiceController::class, 'invoiceCsv']);
Route::get('/expenceItemCsv', [invoiceController::class, 'expenceItemCsv']);
Route::get('/purchaseCsv', [purchasingController::class, 'purchaseCsv']);
Route::get('/vendorCsv', [vendorController::class, 'vendorCsv']);

    //My Wallets Export routes 

Route::get('/walletCsv', [walletController::class, 'walletCsv']);
Route::get('/my_walletCsv', [myWalletController::class, 'my_walletCsv']);
Route::get('/bank_accounts_transactions_export_excel', [bankAccountController::class, 'bank_accounts_transactions_export_excel']);
Route::get('/call_center_export_excel', [CallCenterController::class, 'call_center_export_excel']);



Route::get('/document_management_export_excel', [DocumentController::class, 'document_management_export_excel']);




//Wallets transaction export to excel Request routes 

Route::get('/walletTransactionsExport', [walletController::class, 'walletTransactionsExport']);

Route::group(['middleware' => ['jwt.verify', 'cors', 'UserPermission']], function () {

    Route::get('/getUserLevel', [userLevelController::class, 'getUserLevel']);

    Route::post('/deleteImage', [settingController::class, 'deleteImage']);



    Route::get('/getemployee', [employeeController::class, 'getemployee']);
    Route::post('/createEmployee', [employeeController::class, 'createEmployee']);
    Route::get('/getEmployeeDetail', [employeeController::class, 'getEmployeeDetail']);
    Route::post('/updateEmployee', [employeeController::class, 'updateEmployee']);
    Route::get('/deleteEmployee', [employeeController::class, 'deleteEmployee']);

    Route::get('/vendorsDropdown', [employeeController::class, 'vendorsDropdown']);
    Route::get('/clientsDropdown', [employeeController::class, 'clientsDropdown']);
    Route::get('/accommodationsDropdown', [employeeController::class, 'accommodationsDropdown']);
    Route::get('/professionsDropdown', [employeeController::class, 'professionsDropdown']);

    Route::get('/getEmployeeDropdown', [employeeController::class, 'getEmployeeDropdown']);
    Route::get('/getResourceItemsDropdown', [employeeController::class, 'getResourceItemsDropdown']);

    Route::post('/allocateResource', [resourceCenterController::class, 'allocateResource']);
    Route::get('/getAllocatedResources', [resourceCenterController::class, 'getAllocatedResources']);
    Route::get('/getAllocatedResourceDetail', [resourceCenterController::class, 'getAllocatedResourceDetail']);
    Route::get('/deleteAllocation', [resourceCenterController::class, 'deleteAllocation']);
    Route::post('/updateAllocatedResources', [resourceCenterController::class, 'updateAllocatedResources']);

    Route::get('/getResourceItems', [resourceCenterController::class, 'getResourceItems']);
    Route::get('/deleteResourceItem', [resourceCenterController::class, 'deleteResourceItem']);
    Route::post('/addResourceItem', [resourceCenterController::class, 'addResourceItem']);
    Route::get('/getResourceItemDetail', [resourceCenterController::class, 'getResourceItemDetail']);
    Route::post('/updateResourceItem', [resourceCenterController::class, 'updateResourceItem']);


    Route::get('/getAllResources', [purchasingController::class, 'getAllResources']);

    Route::post('/imgUpload', [purchasingController::class, 'imgUpload']);
    Route::post('/createPurchase', [purchasingController::class, 'createPurchase']);
    Route::get('/getPurchaseList', [purchasingController::class, 'getPurchaseList']);
    Route::get('/deletePurchase', [purchasingController::class, 'deletePurchase']);
    Route::get('/getPurchaseDetail', [purchasingController::class, 'getPurchaseDetail']);
    Route::post('/updatePurchase', [purchasingController::class, 'updatePurchase']);

    Route::post('/jwt/refresh-token', [UserController::class, 'refreshToken']);

    Route::get('/getAccommodation', [accommodationController::class, 'getAccommodation']);
    Route::post('/addAccommodation', [accommodationController::class, 'addAccommodation']);
    Route::get('/getAccommodationDetail', [accommodationController::class, 'getAccommodationDetail']);
    Route::post('/updateAccommodation', [accommodationController::class, 'updateAccommodation']);
    Route::get('/deleteAccommodation', [accommodationController::class, 'deleteAccommodation']);

    //accommodation payment
    Route::post('/addAccommodationPayment', [accommodationController::class, 'addAccommodationPayment']);

    Route::get('/getAccommodationPayment', [accommodationController::class, 'getAccommodationPayment']);
    Route::get('/getPaymentDetails', [accommodationController::class, 'getPaymentDetails']);
    Route::post('/updateAccommodationPayment', [accommodationController::class, 'updateAccommodationPayment']);
    Route::get('/deleteAccommodationPayment', [accommodationController::class, 'deleteAccommodationPayment']);

    //accommodation Bill Payment

    Route::post('/addAccommodationBillPayment', [accommodationController::class, 'addAccommodationBillPayment']);
    Route::get('/getAccommodationBillPayment', [accommodationController::class, 'getAccommodationBillPayment']);
    Route::get('/getBillPaymentDetails', [accommodationController::class, 'getBillPaymentDetails']);
    Route::post('/updateAccommodationBillPayment', [accommodationController::class, 'updateAccommodationBillPayment']);

    Route::get('/deleteAccommodationBillPayment', [accommodationController::class, 'deleteAccommodationBillPayment']);


    //vendors routes


    Route::get('/getVendors', [vendorController::class, 'getVendors']);
    Route::get('/deleteVendor', [vendorController::class, 'deleteVendor']);
    Route::post('/addVendor', [vendorController::class, 'addVendor']);
    Route::get('/getVendorDetail', [vendorController::class, 'getVendorDetail']);
    Route::post('/updateVendor', [vendorController::class, 'updateVendor']);

    //client routes

    Route::get('/getClients', [clientController::class, 'getClients']);
    Route::post('/createClient', [clientController::class, 'createClient']);
    Route::get('/getClientDetail', [clientController::class, 'getClientDetail']);
    Route::post('/updateClient', [clientController::class, 'updateClient']);
    Route::get('/getClientLocations', [clientController::class, 'getClientLocations']);
    
    Route::get('/deleteClient', [clientController::class, 'deleteClient']);

    //invoice routes

    Route::get('/getInvoices', [invoiceController::class, 'getInvoices']);
    Route::get('/getExpenceItemsDropdown', [invoiceController::class, 'getExpenceItemsDropdown']);
    Route::post('/addInvoice', [invoiceController::class, 'addInvoice']);
    Route::get('/getInvoiceDetail', [invoiceController::class, 'getInvoiceDetail']);
    Route::post('/updateInvoice', [invoiceController::class, 'updateInvoice']);
    Route::get('/deleteInvoice', [invoiceController::class, 'deleteInvoice']);

    Route::get('/getExpenceItems', [invoiceController::class, 'getExpenceItems']);
    Route::get('/getExpenceItemDetail', [invoiceController::class, 'getExpenceItemDetail']);
    Route::post('/updateExpenceItem', [invoiceController::class, 'updateExpenceItem']);
    Route::post('/createExpenceItem', [invoiceController::class, 'createExpenceItem']);
    Route::get('/deleteExpenceItem', [invoiceController::class, 'deleteExpenceItem']);

    //Route::get('/print/{invoice_number}', [invoiceController::class, 'printInvoice']);

    //user Level routes

    Route::get('/getRoles', [permissionController::class, 'getRoles']);
    Route::post('/createRole', [permissionController::class, 'createRole']);
    Route::get('/getRoleDetail', [permissionController::class, 'getRoleDetail']);
    Route::post('/updateRoleDetail', [permissionController::class, 'updateRoleDetail']);



    //users  routes

    Route::get('/getUsers', [UserController::class, 'getUsers']);
    Route::get('/getAllSystemUsers', [UserController::class, 'getAllSystemUsers']);

    Route::get('/getRolesDropdown', [UserController::class, 'getRolesDropdown']);
    Route::post('/addUser', [UserController::class, 'addUser']);
    Route::get('/getUserDetail', [UserController::class, 'getUserDetail']);
    Route::post('/updateUser', [UserController::class, 'updateUser']);

    Route::get('/getProfile', [UserController::class, 'getProfile']);
    Route::post('/updateProfile', [UserController::class, 'updateProfile']);

    Route::get('/getPermissions', [UserController::class, 'getPermissions']);

    //Audit routes

    Route::get('/getAuditTrail', [auditController::class, 'getAuditTrail']);

    //app Settings variables

    Route::get('/getsettings', [settingController::class, 'getsettings']);
    Route::post('/updateSettings', [settingController::class, 'updateSettings']);

    //dashboard info
    Route::get('/dashboardStats', [dashboardController::class, 'dashboardStats']);
    Route::get('/getMonthlyRevenues', [dashboardController::class, 'getMonthlyRevenues']);
    Route::get('/dashboardProductStat', [dashboardController::class, 'dashboardProductStat']);
    Route::get('/dashboardUpcomingRents', [dashboardController::class, 'dashboardUpcomingRents']);





    //Supplier routes



    Route::post('/createSupplier', [SupplierController::class, 'createSupplier']);

    Route::get('/getAllSuppliers', [SupplierController::class, 'getAllSuppliers']);

    Route::get('/getSupplier', [SupplierController::class, 'getSupplier']);


    Route::post('/updateSupplier', [SupplierController::class, 'updateSupplier']);



    Route::post('/addSupplierType', [SupplierController::class, 'addSupplierType']);
    Route::post('/deleteSupplier', [SupplierController::class, 'deleteSupplier']);


    Route::get('/getSupplierType', [SupplierController::class, 'getSupplierType']);
    Route::get('/getAllSupplierType', [SupplierController::class, 'getAllSupplierType']);
    Route::post('/updateSupplierType', [SupplierController::class, 'updateSupplierType']);
    Route::post('/deleteSupplierType', [SupplierController::class, 'deleteSupplierType']);




    Route::get('/getAllCities', [cityController::class, 'getAllCities']);
    Route::get('/getAllBanks', [bankController::class, 'getAllBanks']);



    // Todo Task

    Route::get('/getAllAssignee', [TaskController::class, 'getAllAssignee']);
    Route::get('/getAllTasks', [TaskController::class, 'getAllTasks']);

    Route::post('/addTask', [TaskController::class, 'addTask']);
    Route::post('/isCompleteTask', [TaskController::class, 'isCompleteTask']);

    Route::post('/deleteTask', [TaskController::class, 'deleteTask']);




    //Document routes 


    Route::post('/createDocument', [DocumentController::class, 'createDocument']);

    Route::get('/getAllDocuments', [DocumentController::class, 'getAllDocuments']);

    Route::get('/getDocument', [DocumentController::class, 'getDocument']);

    Route::get('/documentResourceOwner', [DocumentController::class, 'documentResourceOwner']);


    Route::post('/updateDocument', [DocumentController::class, 'updateDocument']);


    Route::post('/deleteDocument', [DocumentController::class, 'deleteDocument']);


    //Payemnt Request routes 

    Route::post('/addPaymentRequest', [paymentRequestController::class, 'addPaymentRequest']);

    Route::get('/getAllPymentRequests', [paymentRequestController::class, 'getAllPymentRequests']);

    Route::get('/getPaymentRequest', [paymentRequestController::class, 'getPaymentRequest']);



    Route::post('/updatePaymentRequest', [paymentRequestController::class, 'updatePaymentRequest']);


    Route::post('/deletePayment', [paymentRequestController::class, 'deletePayment']);




    //Wallets Request routes 

    Route::post('/addWallet', [walletController::class, 'addWallet']);

    Route::get('/getAllWallets', [walletController::class, 'getAllWallets']);

    Route::get('/getWallet', [walletController::class, 'getWallet']);

    Route::post('/updateWallet', [walletController::class, 'updateWallet']);


    Route::post('/deleteWallet', [walletController::class, 'deleteWallet']);


    //For patty Cash, Transactions

    Route::post('/addTransaction', [PattyCashController::class, 'addTransaction']);
    Route::get('/getTransaction', [PattyCashController::class, 'getTransaction']);
    Route::get('/getAllTransaction', [PattyCashController::class, 'getAllTransaction']);


    Route::post('/updateTransaction', [PattyCashController::class, 'updateTransaction']);

    Route::post('/deleteTransaction', [PattyCashController::class, 'deleteTransaction']);



    Route::post('/updateTransactionStatus', [PattyCashController::class, 'updateTransactionStatus']);
    Route::post('/updateTransactionApproveStatus', [PattyCashController::class, 'updateTransactionApproveStatus']);










    //My Wallets Request routes 

    Route::post('/addMyWallet', [myWalletController::class, 'addWallet']);

    Route::post('/updateMyWallet', [myWalletController::class, 'updateWallet']);

    Route::get('/getAllMyWallets', [myWalletController::class, 'getAllWallets']);

    Route::get('/getMyWallet', [myWalletController::class, 'getWallet']);

    Route::post('/deleteMyWallet', [myWalletController::class, 'deleteWallet']);



    //For My patty Cash, Transactions

    Route::post('/addMyTransaction', [mypattyCashController::class, 'addTransaction']);
    Route::get('/getMyTransaction', [mypattyCashController::class, 'getTransaction']);
    Route::get('/getAllMyTransaction', [mypattyCashController::class, 'getAllTransaction']);


    Route::post('/updateMyTransaction', [mypattyCashController::class, 'updateTransaction']);

    Route::post('/deleteMyTransaction', [mypattyCashController::class, 'deleteTransaction']);

    


    //For Bank Accounts

    Route::get('/getAllBankAccounts', [bankAccountController::class, 'getAllBankAccounts']);
    Route::post('/addBankAccount', [bankAccountController::class, 'addBankAccount']);
    Route::post('/deleteBankAccount', [bankAccountController::class, 'deleteBankAccount']);
    Route::get('/getBankAccount', [bankAccountController::class, 'getBankAccount']);
    Route::post('/updateBankAccount', [bankAccountController::class, 'updateBankAccount']);



    //For Bank Account, Transactions

    Route::post('/addBankAccountTransaction', [bankAccountController::class, 'addBankAccountTransaction']);

    Route::get('/getAllBankTransactions', [bankAccountController::class, 'getAllBankTransactions']);

    Route::get('/getBankTransaction', [bankAccountController::class, 'getBankTransaction']);

    Route::post('/updateBankAccountTransaction', [bankAccountController::class, 'updateBankAccountTransaction']);

    Route::post('/deleteBankAccountTransaction', [bankAccountController::class, 'deleteBankAccountTransaction']);

    Route::get('/bankAccountResourceOwner', [bankAccountController::class, 'bankAccountResourceOwner']);


    //For Owner Bank Accounts



    Route::get('/getAllOwnerBanks', [ownerBanksController::class, 'getAllOwnerBanks']);
    Route::post('/addOwnerBank', [ownerBanksController::class, 'addOwnerBank']);
    Route::post('/deleteOwnerBank', [ownerBanksController::class, 'deleteOwnerBank']);
    Route::get('/getOwnerBank', [ownerBanksController::class, 'getOwnerBank']);
    Route::post('/updateOwnerBank', [ownerBanksController::class, 'updateOwnerBank']);


        // Expense Account Request routes 

        Route::post('/addExpenseAccount', [ExpenseAccountController::class, 'addExpenseAccount']);

        Route::post('/updateExpenseAccount', [ExpenseAccountController::class, 'updateExpenseAccount']);
    
        Route::get('/getExpenseAccounts', [ExpenseAccountController::class, 'getExpenseAccounts']);
    
        Route::get('/getExpenseAccount', [ExpenseAccountController::class, 'getExpenseAccount']);
    
        Route::post('/deleteExpenseAccount', [ExpenseAccountController::class, 'deleteExpenseAccount']);



        // Expense Account Summary Request routes 






    Route::get('/getExpenseAccountAllSummary', [ExpenseAccountController::class, 'getExpenseAccountAllSummary']);


        Route::get('/getAllTransactionExpenseAccount', [ExpenseAccountController::class, 'getAllTransactionExpenseAccount']);

        Route::post('/addExpenseCategory', [ExpenseAccountController::class, 'addExpenseCategory']);

        Route::get('/getExpenseCategoryDetail', [ExpenseAccountController::class, 'getExpenseCategoryDetail']);

        Route::post('/deleteExpenseAccountCatgory', [ExpenseAccountController::class, 'deleteExpenseAccountCatgory']);






            //Call Center Request routes 

    Route::post('/addCallCenter', [CallCenterController::class, 'addCallCenter']);

    Route::post('/updateCallCenter', [CallCenterController::class, 'updateCallCenter']);

    Route::get('/getAllCallCenterData', [CallCenterController::class, 'getAllCallCenterData']);

    Route::get('/getCallData', [CallCenterController::class, 'getCallData']);

    Route::post('/deleteCallCenter', [CallCenterController::class, 'deleteCallCenter']);
    Route::post('/updateCallCenterStatus', [CallCenterController::class, 'updateCallCenterStatus']);

    // Assets routes
    Route::get('/getAsset', [assetsController::class, 'getAsset']);
    Route::post('/createAsset', [assetsController::class, 'createAsset']);
    Route::post('/delete_asset', [assetsController::class, 'delete_asset']);
    Route::get('/getAssetById', [assetsController::class, 'getAssetById']);

    
    Route::get('/getWorkOrder', [assetsController::class, 'getWorkOrder']);
    Route::post('/addWorkOrder' , [assetsController::class, 'addWorkOrder']);
    Route::get('/getAssetsDropdown', [assetsController::class, 'getAssetsDropdown']);
    Route::post('/deleteWorkOrder', [assetsController::class, 'deleteWorkOrder']);
    Route::get('/getWorkOrderById', [assetsController::class, 'getWorkOrderById']);
    Route::post('/updateWorkOrder', [assetsController::class, 'updateWorkOrder']);
    Route::post('/updateAsset', [assetsController::class, 'updateAsset']);

    Route::get('/getAssetMaintenance', [assetsController::class, 'getAssetMaintenance']);
    Route::post('/addAssetMaintenance', [assetsController::class, 'addAssetMaintenance']);
    Route::post('/deleteAssetMaintenance', [assetsController::class, 'deleteAssetMaintenance']);
    Route::post('/updateAssetMaintenance', [assetsController::class, 'updateAssetMaintenance']);
    Route::get('/getAssetMaintenanceById', [assetsController::class, 'getAssetMaintenanceById']);


    Route::get('/getAssetsType', [assetsController::class, 'getAssetsType']);
    Route::post('/addAssetType', [assetsController::class, 'addAssetType']);
    Route::post('/deleteAssetType', [assetsController::class, 'deleteAssetType']);
    Route::post('/updateAssetType', [assetsController::class, 'updateAssetType']);
    Route::get('/getAssetTypeById', [assetsController::class, 'getAssetTypeById']);


    Route::get('/getAssetsModel', [assetsController::class, 'getAssetsModel']);
    Route::post('/addAssetModel', [assetsController::class, 'addAssetModel']);
    Route::post('/deleteAssetModel', [assetsController::class, 'deleteAssetModel']);
    Route::post('/updateAssetModel', [assetsController::class, 'updateAssetModel']);
    Route::get('/getAssetModelById', [assetsController::class, 'getAssetModelById']);


    Route::get('/getAssetsMake', [assetsController::class, 'getAssetsMake']);
    Route::post('/addAssetMake', [assetsController::class, 'addAssetMake']);
    Route::post('/deleteAssetMake', [assetsController::class, 'deleteAssetMake']);
    Route::post('/updateAssetMake', [assetsController::class, 'updateAssetMake']);
    Route::get('/getAssetMakeById', [assetsController::class, 'getAssetMakeById']);


    Route::get('/getAssetsCapacity', [assetsController::class, 'getAssetsCapacity']);
    Route::post('/addAssetCapacity', [assetsController::class, 'addAssetCapacity']);
    Route::post('/deleteAssetCapacity', [assetsController::class, 'deleteAssetCapacity']);
    Route::post('/updateAssetCapacity', [assetsController::class, 'updateAssetCapacity']);
    Route::get('/getAssetCapacityById', [assetsController::class, 'getAssetCapacityById']);

    Route::get('/getAssetCapacityDropdown', [assetsController::class, 'getAssetCapacityDropdown']);
    Route::get('/getAssetTypeDropdown', [assetsController::class, 'getAssetTypeDropdown']);
    Route::get('/getAssetMakeDropdown', [assetsController::class, 'getAssetMakeDropdown']);
    Route::get('/getAssetModelDropdown', [assetsController::class, 'getAssetModelDropdown']);

});
