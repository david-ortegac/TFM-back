<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Branch
 *
 * @property $id
 * @property $brand_id
 * @property $phone
 * @property $email
 * @property $address
 * @property $status
 * @property $created_at
 * @property $updated_at
 *
 * @property Brand $brand
 * @property Qualify[] $qualifies
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Branch extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['brand_id', 'phone', 'email', 'address', 'status'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand()
    {
        return $this->belongsTo(\App\Models\Brand::class, 'brand_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function qualifies()
    {
        return $this->hasMany(\App\Models\Qualify::class, 'id', 'branch_id');
    }
    
}
