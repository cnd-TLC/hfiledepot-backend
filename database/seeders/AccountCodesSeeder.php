<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\ChartOfAccount;

class AccountCodesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */


    public function run()
    {
        include('AccountCodesList.php');

        foreach($account_codes as $code => $account_title) {
            ChartOfAccount::create([
                'code' => $code,
                'account_title' => $account_title
            ]);
        }
    }
}
