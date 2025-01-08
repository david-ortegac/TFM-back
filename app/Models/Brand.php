<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Brand
 *
 * @property $id
 * @property $property
 * @property $phone
 * @property $email
 * @property $address
 * @property $user_id
 * @property $status
 * @property $created_at
 * @property $updated_at
 *
 * @property User $user
 * @property Branch[] $branches
 * @property Invoice[] $invoices
 * @property Offer[] $offers
 * @property Qualify[] $qualifies
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Brand extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['property', 'phone', 'email', 'address', 'user_id', 'status'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function branches()
    {
        return $this->hasMany(\App\Models\Branch::class, 'id', 'brand_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoices()
    {
        return $this->hasMany(\App\Models\Invoice::class, 'id', 'brand_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function offers()
    {
        return $this->hasMany(\App\Models\Offer::class, 'id', 'brand_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function qualifies()
    {
        return $this->hasMany(\App\Models\Qualify::class, 'id', 'brand_id');
    }
    
}
