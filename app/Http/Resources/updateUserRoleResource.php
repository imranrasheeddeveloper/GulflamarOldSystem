<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class updateUserRoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
                'id' => $this->id,
                'name' => $this->name,
                'userCount' => count($this->user),
                'employees' => explode(',',$this->permissions->employees),
                'resource' => explode(',',$this->permissions->resource),
                'resource_item' => explode(',',$this->permissions->resource_item),
                'accommodation' => explode(',',$this->permissions->accommodation),
                'rent_payment' => explode(',',$this->permissions->rent_payment),
                'bill_payment'=> explode(',',$this->permissions->bill_payment),
                'client' => explode(',',$this->permissions->client),
                'invoice'=> explode(',',$this->permissions->invoice),
                'purchase_order'=> explode(',',$this->permissions->purchase_order),
                'vendor'=> explode(',',$this->permissions->vendor),

                'document'=> explode(',',$this->permissions->document),
                'payment'=> explode(',',$this->permissions->payment),
                'supplier'=> explode(',',$this->permissions->supplier),
                'supplier_type'=> explode(',',$this->permissions->supplier_type),

                'wallets'=> explode(',',$this->permissions->wallets),
                'my_wallets'=> explode(',',$this->permissions->my_wallets),

                'petty_cash'=> explode(',',$this->permissions->petty_cash),
                'petty_cash_approve'=> explode(',',$this->permissions->petty_cash_approve),
                'bank_account'=> explode(',',$this->permissions->bank_account),
                'my_bank_account'=> explode(',',$this->permissions->my_bank_account),
                'expense_accounts'=> explode(',',$this->permissions->expense_accounts),
                'call_center'=> explode(',',$this->permissions->call_center),
                'assets_managments'=> explode(',',$this->permissions->assets_managments),
                'asset_maintenances'=> explode(',',$this->permissions->asset_maintenances),
                'asset_work_orders'=> explode(',',$this->permissions->asset_work_orders),



                'user_level'=> explode(',',$this->permissions->user_level),
                'user'=> explode(',',$this->permissions->user),
                'dashboard'=> $this->permissions->dashboard,
                
                ];
    }
}
