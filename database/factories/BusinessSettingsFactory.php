<?php
use Faker\Generator as Faker;

$factory->define(\App\Models\BusinessSetting::class, function (Faker $faker) {
    $title=$faker->name;
    return [
        'logo' => $faker->imageUrl(500, 500),
        'favicon' => $faker->imageUrl(500, 500),
        'company_name'    => 'AmarPC',
        'address'         => '54/B Molla Bari Kawlar Bazar Uttara, Dhaka 1229',
        'google_location' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3649.2367477993316!2d90.41460516429885!3d23.845725491046448!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c6652c0b863d%3A0xc404e4ca02255b30!2sKawla%20Bazar!5e0!3m2!1sen!2sbd!4v1576324555027!5m2!1sen!2sbd',
        'email'           => 'info@clicknbuy.com',
        'phone_number'    => '+8801854969657',
        'web_address'    => 'https://www.clicknbuy.co',
        'hot_line'       => '+8801616626263',
        'facebook'       => 'https://fb.com/clicknbuy.co',
        'twitter'        => 'https://www.twitter.com',
        'instagram'      => 'https://www.instagram.com',
        'pinterest'      => 'https://www.pinterest.com',
        'youtube'        => 'https://www.youtube.com',
        'about'          => $faker->text,
        'tag_line'       => '',
        'delivery_charge'       => 60.00

    ];
});
