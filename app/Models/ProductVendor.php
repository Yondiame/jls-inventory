<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Laravel\Lumen\Auth\Authorizable;

class ProductVendor extends Pivot
{

    protected $table = "product_vendor";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vendor_case_pack', 'backup', 'vendor_order_unit',  'vendor_sku', 'product_id', 'vendor_id', 'vendors_title'
    ];
}
