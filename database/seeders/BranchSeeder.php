<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $result = DB::transaction(function (){
        	$company = \App\Models\Company::whereNull('deleted_at')->first();
            $branchs = [
                [
                    'name' => 'Headquarters',
                    'telephone' => '0251 – 8417382',
                    'address' => 'Griya Permata Pamoyanan Blok C No 3A RT 04 RW 08 Pamoyanan Bogor Selatan, Kota Bogor, Jawa Barat',
                    'company_id' => $company->id,
                ],
                [
                    'name' => 'Branch Office',
                    'telephone' => '0274 – 4295095',
                    'address' => 'Pinang Ranti 2 No.A5 Pelemsewu, Sewon, Bantul, Yogyakarta',
                    'company_id' => $company->id,
                ]
            ];

            foreach ($branchs as $key => $branch) {
                if ($branch != null) {
                    \App\Models\Branch::firstOrCreate([
                        'name' => $branch['name'],
                        'telephone' => $branch['telephone'],
                        'address' => $branch['address'],
                        'company_id' => $branch['company_id']
                    ]);
                }
            }
        });
    }
}
