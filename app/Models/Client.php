<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['name', 'address', 'gstin', 'state_code'];

    public function stateModel()
    {
        return $this->belongsTo(State::class, 'state_code', 'code');
    }

    public function getStateAttribute()
    {
        return $this->stateModel?->name ?? '';
    }
}
