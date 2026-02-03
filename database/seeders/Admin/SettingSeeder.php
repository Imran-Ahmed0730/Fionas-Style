<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['key' => 'address', 'value' => "Dhaka, Bangladesh"],
            ['key' => 'business_name', 'value' => "Fiona's Style"],
            ['key' => 'closed_on', 'value' => json_encode(["1", "2"])],
            ['key' => 'closing_time', 'value' => "06:00 PM"],
            ['key' => 'developed_by', 'value' => "Imran Ahmed"],
            ['key' => 'development_mode', 'value' => "0"],
            ['key' => 'developed_by_url', 'value' => "https://www.devimranahmed.com"],
            ['key' => 'email', 'value' => "info@ecom.com"],
            ['key' => 'facebook_url', 'value' => "https://www.facebook.com"],
            ['key' => 'free_delivery_above', 'value' => "100"],
            ['key' => 'guest_account', 'value' => "0"],
            ['key' => 'instagram_url', 'value' => "https://www.instagram.com"],
            ['key' => 'linkedin_url', 'value' => "https://www.linkedin.com"],
            ['key' => 'max_category_level', 'value' => "3"],
            ['key' => 'meta_description', 'value' => "An ecommerce website that sells products of different types"],
            ['key' => 'opening_time', 'value' => "09:00 AM"],
            ['key' => 'phone', 'value' => "0123456789"],
            ['key' => 'pinterest_url', 'value' => "https://www.pinterest.com"],
            ['key' => 'shop_map_location', 'value' => "N/A"],
            ['key' => 'short_bio', 'value' => "An ecommerce website that sells products of different types"],
            ['key' => 'site_dark_logo', 'value' => "backend/assets/img/AdminLTEFullLogo.png"],
            ['key' => 'site_favicon', 'value' => "backend/assets/img/AdminLTELogo.png"],
            ['key' => 'site_footer_logo', 'value' => "backend/assets/img/AdminLTEFullLogo.png"],
            ['key' => 'site_logo', 'value' => "backend/assets/img/AdminLTEFullLogo.png"],
            ['key' => 'site_name', 'value' => "Fiona's Style"],
            ['key' => 'site_url', 'value' => "https://fionas-style.test"],
            ['key' => 'user_verification', 'value' => "0"],
            ['key' => 'whatsapp', 'value' => "0123456789"],
            ['key' => 'x_url', 'value' => "https://www.x.com"],
            ['key' => 'youtube_url', 'value' => "https://www.youtube.com"],
            // Add more key-value pairs here
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
