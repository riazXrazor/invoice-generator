<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CompanyDetail;

class CompanyDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CompanyDetail::firstOrCreate([], [
            'company_name' => 'UNIQUE FOOD PRODUCTS',
            'address_line_1' => 'CHAKARBARIA, KUNDRALI, BARUIPUR, 24 PG(S),',
            'address_line_2' => '.PIN-746310.',
            'state' => 'WEST BENGAL',
            'contact_no' => null,
            'gstin' => '19ACNPL1586D1ZD',
            'bank_name' => 'UNION BANK',
            'bank_account_no' => '046113100000690',
            'bank_ifs_code' => 'UBIN0804614',
            'bank_branch' => 'KUNDARALI BRANCH',
            'bank_detail_heading' => 'Company\'s Bank Detail: KUNDARALI BRANCH, SOUTH 24 PARGANAS -743302.',
            'declaration' => "Declare that this invoice shows the actual price of the\nGoods described and that all particular are true & perfect.\nGoods once sold not be taken back.",
            'jurisdiction' => 'SUBJECT TO BARUIPUR JURISDICTION',
        ]);
    }
}
