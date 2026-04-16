<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyDetail extends Model
{
    protected $fillable = [
        'company_name',
        'address_line_1',
        'address_line_2',
        'state_code',
        'contact_no',
        'gstin',
        'bank_name',
        'bank_account_no',
        'bank_ifs_code',
        'bank_branch',
        'bank_detail_heading',
        'declaration',
        'jurisdiction',
        'invoice_template',
    ];

    public function stateModel()
    {
        return $this->belongsTo(State::class, 'state_code', 'code');
    }

    public function getStateAttribute()
    {
        return $this->stateModel?->name ?? '';
    }
}
