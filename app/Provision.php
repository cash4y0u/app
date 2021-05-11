<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class Provision extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'number', 'amount', 'maturity', 'status', 'amount_paid', 'paid_at', 'time', 'received_at', 'coordinates'
    ];

    protected $casts = [
        'maturity' => 'date:Y-m-d',
        'amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'coordinates' => 'json'
    ];

    public function getFormattedAmountAttribute()
    {
        return number_format($this->attributes['amount'], 2, ',', '.');
    }

    public function getFormattedMaturityAttribute()
    {
        return Carbon::parse($this->attributes['maturity'])->format('d/m/Y');
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function scopeLasting($query)
    {
        return $query
        ->where('status', 'pending')
        ->orderBy('number', 'desc')
        ->first();
    }
}
