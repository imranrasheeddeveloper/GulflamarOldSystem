<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class userRoleResource extends JsonResource
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
                'employees' => $this->decodePermisions($this->permissions->employees),
                'resource' => $this->decodePermisions($this->permissions->resource),
                'resource_item' => $this->decodePermisions($this->permissions->resource_item),
                'accommodation' => $this->decodePermisions($this->permissions->accommodation),
                'rent_payment' => $this->decodePermisions($this->permissions->rent_payment),
                'bill_payment'=> $this->decodePermisions($this->permissions->bill_payment),
                'client' => $this->decodePermisions($this->permissions->client),
                'invoice'=> $this->decodePermisions($this->permissions->invoice),
                'purchase_order'=> $this->decodePermisions($this->permissions->purchase_order),
                'vendor'=> $this->decodePermisions($this->permissions->vendor),

                'document'=> $this->decodePermisions($this->permissions->document),
                'payment'=> $this->decodePermisions($this->permissions->payment),
                'supplier'=> $this->decodePermisions($this->permissions->supplier),
                'supplier_type'=> $this->decodePermisions($this->permissions->supplier_type),
                'wallets'=> $this->decodePermisions($this->permissions->wallets),
                'my_wallets'=> $this->decodePermisions($this->permissions->my_wallets),
                'petty_cash'=> $this->decodePermisions($this->permissions->petty_cash),
                'petty_cash_approve'=> $this->decodePermisions($this->permissions->petty_cash_approve),
                'bank_account'=> $this->decodePermisions($this->permissions->bank_account),
                'my_bank_account'=> $this->decodePermisions($this->permissions->my_bank_account),
                'expense_accounts'=> $this->decodePermisions($this->permissions->expense_accounts),
                'call_center'=> $this->decodePermisions($this->permissions->call_center),





                'user_level'=> $this->decodePermisions($this->permissions->user_level),
                'user' => $this->decodePermisions($this->permissions->user),
                'dashboard'=> $this->permissions->dashboard,
                
                ];
    }

    function decodePermisions($permission)

    {   $permissions = [];
        if ($permission != null && $permission != '') {

                        foreach (explode(',',$permission) as $key => $singlePermission) {

                            switch ($singlePermission) {
                                        case "C":
                                            $permissions[] = 'Create';

                                            break;

                                        case "D":
                                            $permissions[] = 'Delete';

                                            break;

                                        case "E":
                                            $permissions[] = 'Edit';

                                            break;

                                        case "V":
                                            $permissions[] = 'View';

                                            break;

                                          default:
                                            break;
                                        }
                        }
        }

        return $permissions;
    }
}
