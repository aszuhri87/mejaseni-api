<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $result = DB::transaction(function (){
            $admin = \App\Models\Company::updateOrCreate([
                'name' => 'PT Semua Aplikasi Indonesia',
                'telephone' => '082225486622',
                'email' =>'info@mejaseni.com',
                'address' => 'Jl. Daradasih No.8, Patangpuluhan, Wirobrajan, Kota Yogyakarta, Daerah Istimewa Yogyakarta 55251',
                'lat' => '',
                'lng' => '',
                'vision' => 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Ullam laborum blanditiis necessitatibus cupiditate error ea impedit facere autem voluptas distinctio dolor, nemo beatae. Minus earum est illo praesentium corporis assumenda minima. Quidem culpa fuga suscipit.',
                'mission' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fugiat repellat harum et reiciendis quos impedit hic maxime architecto perferendis cumque consequatur magni animi nostrum voluptatem molestias quasi enim molestiae, dolorum reprehenderit suscipit doloribus id? Optio non illum natus sunt distinctio, officiis aut accusantium facilis aliquid pariatur! Reprehenderit minus aut distinctio explicabo? Aperiam alias reiciendis, voluptates quis exercitationem nihil officia eum recusandae labore quibusdam harum? Possimus distinctio voluptatum aperiam amet consequatur.'
            ]);
        });
    }
}
