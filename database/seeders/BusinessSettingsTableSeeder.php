<?php
namespace Database\Seeders;
use App\Models\BusinessSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class BusinessSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       BusinessSetting::create([
           'logo' =>'image.jpg',
           'favicon' => 'favicon.png',
           'company_name'    => 'HelloBD',
           'address'         => 'Uttara, Dhaka 1229',
           'google_location' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3649.2367477993316!2d90.41460516429885!3d23.845725491046448!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c6652c0b863d%3A0xc404e4ca02255b30!2sKawla%20Bazar!5e0!3m2!1sen!2sbd!4v1576324555027!5m2!1sen!2sbd',
           'email'           => 'info@hellobd.com',
           'phone_number'    => '+8801800000000',
           'web_address'    => 'https://www.hellobd.co',
           'hot_line'       => '+8801610000000',
           'facebook'       => 'https://fb.com/hellobd.co',
           'twitter'        => 'https://www.twitter.com',
           'instagram'      => 'https://www.instagram.com',
           'pinterest'      => 'https://www.pinterest.com',
           'youtube'        => 'https://www.youtube.com',
           'about'          => 'this is about company',
           'tag_line'       => '',

       ]);
    }
}
