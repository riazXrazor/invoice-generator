<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'client_id', 'invoice_no', 'invoice_date', 'challan_no',
        'dispatched_through', 'subtotal', 'tax_amount', 'grand_total',
        'has_different_shipping_address', 'shipping_name', 'shipping_address',
        'shipping_gstin', 'shipping_state', 'shipping_state_code',
    ];

    protected $casts = [
        'invoice_date' => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function getAmountInWordsAttribute()
    {
        $number = $this->grand_total;
        $no = floor($number);
        $point = round($number - $no, 2) * 100;
        $hundred = null;
        $digits_1 = strlen($no);
        $i = 0;
        $str = [];
        $words = [
            '0' => '', '1' => 'ONE', '2' => 'TWO',
            '3' => 'THREE', '4' => 'FOUR', '5' => 'FIVE', '6' => 'SIX',
            '7' => 'SEVEN', '8' => 'EIGHT', '9' => 'NINE',
            '10' => 'TEN', '11' => 'ELEVEN', '12' => 'TWELVE',
            '13' => 'THIRTEEN', '14' => 'FOURTEEN',
            '15' => 'FIFTEEN', '16' => 'SIXTEEN', '17' => 'SEVENTEEN',
            '18' => 'EIGHTEEN', '19' => 'NINETEEN', '20' => 'TWENTY',
            '30' => 'THIRTY', '40' => 'FORTY', '50' => 'FIFTY',
            '60' => 'SIXTY', '70' => 'SEVENTY',
            '80' => 'EIGHTY', '90' => 'NINETY',
        ];
        $digits = ['', 'HUNDRED', 'THOUSAND', 'LAKH', 'CRORE'];
        while ($i < $digits_1) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += ($divider == 10) ? 1 : 2;
            if ($number) {
                $counter = count($str);
                $plural = ($counter && $number > 9) ? '' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' AND ' : null;
                $str[] = ($number < 21) ? $words[$number].' '.$digits[$counter].$plural.' '.$hundred
                    : $words[floor($number / 10) * 10].' '.$words[$number % 10].' '.$digits[$counter].$plural.' '.$hundred;
            } else {
                $str[] = null;
            }
        }
        $str = array_reverse($str);
        $result = implode('', $str);
        $points = ($point) ? ' AND '.$words[floor($point / 10) * 10].' '.$words[$point % 10].' PAISE' : '';

        return trim($result).' RUPEES'.$points.' ONLY';
    }
}
