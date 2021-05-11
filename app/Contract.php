<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
            'amount',
            'amount_profit',
            'amount_provision',
            'amount_rate',
            'amount_total',
            'cycle',
            'maturity',
            'rate',
            'status',
            'rate_daily',
            'split'
    ];

    protected $casts = [
        'maturity' => 'date:Y-m-d',
    ];

    public function getFormattedAmountAttribute()
    {
        return number_format($this->attributes['amount'], 2, ',', '.');
    }

    public function getFormattedAmountProvisionAttribute()
    {
        return number_format($this->attributes['amount_provision'], 2, ',', '.');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function provisions()
    {
        return $this->hasMany(Provision::class);
    }
}
