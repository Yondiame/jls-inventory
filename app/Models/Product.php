<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class Product extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'core_number',
        'internal_title',
        'restockable',
        'moq_pieces',
        'buffer_days',
        'minimum_level',
        'product_url',
        'note_for_next_order',
        'case_pack_pieces',
        'pieces_per_internal_box',
        'boxes_per_case',
        'tags_info',
        'hazmat',
        'active',
        'ignore_until',
        'notes',
    ];

    public function vendors(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Vendor::class)->withPivot('vendor_case_pack', 'backup', 'vendor_order_unit',  'vendor_sku', 'vendors_title');
//            ->wherePivot('backup', 0);
    }

//    public function backUpVendors(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
//    {
//        return $this->belongsToMany(Vendor::class)->withPivot('vendor_case_pack', 'backup', 'vendor_order_unit',  'vendor_sku', 'vendors_title')->wherePivot('backup', 1);
//    }

    public function tags(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function locations(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Location::class)->withPivot('quantity');
    }
}
