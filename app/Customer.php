<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'birth', 'document', 'email', 'phone', 'adresses', 'photo'
    ];

    protected $casts = [
        'adresses' => 'array'
    ];

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }
}
