<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $services=['topup','internet','wallet'];
        foreach ($services as $service)
        {
            Service::create([
                'name'=>$service,
                'notice'=>'Service temporarily unavailable, we will be back with better performance shortly.'
            ]);
        }

    }
}
