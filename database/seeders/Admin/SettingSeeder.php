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
            ['key' => 'site_name', 'value' => 'Laragon'],
            ['key' => 'business_name', 'value' => 'Laragon Business'],
            ['key' => 'phone', 'value' => '0123456789'],
            ['key' => 'whatsapp', 'value' => '0123456789'],
            ['key' => 'email', 'value' => 'info@ecom.com'],
            ['key' => 'address', 'value' => 'Dhaka, Bangladesh'],
            ['key' => 'site_url', 'value' => 'https://laragon.test'],
            ['key' => 'opening_time', 'value' => '09:00 AM'],
            ['key' => 'closing_time', 'value' => '06:00 PM'],
            ['key' => 'closed_on', 'value' => '["1", "2"]'],
            ['key' => 'free_delivery_above', 'value' => '100'],
            ['key' => 'guest_account', 'value' => '0'], //0=>No verification, 1=>Email verification 2=> Phone verification
            ['key' => 'user_verification', 'value' => '0'], //0=>No verification, 1=>Email verification 2=> Phone verification
            ['key' => 'developed_by', 'value' => 'Developer'], //0=>No verification, 1=>Email verification 2=> Phone verification
            ['key' => 'developed_by_url', 'value' => 'https://www.developer.com'], //0=>No verification, 1=>Email verification 2=> Phone verification

            ['key' => 'short_bio', 'value' => 'An ecommerce website that sells products of different types'],
            ['key' => 'site_favicon', 'value' => 'backend/assets/img/AdminLTELogo.png'],
            ['key' => 'meta_description', 'value' => 'This is a boiler template for ecommerce website'],
            ['key' => 'site_logo', 'value' => 'backend/assets/img/AdminLTEFullLogo.png'],
            ['key' => 'site_dark_logo', 'value' => 'backend/assets/img/AdminLTEFullLogo.png'],
            ['key' => 'site_footer_logo', 'value' => 'backend/assets/img/AdminLTEFullLogo.png'],
            ['key' => 'facebook_url', 'value' => 'https://www.facebook.com'],
            ['key' => 'instagram_url', 'value' => 'https://www.instagram.com'],
            ['key' => 'x_url', 'value' => 'https://www.x.com'],
            ['key' => 'pinterest_url', 'value' => 'https://www.pinterest.com'],
            ['key' => 'linkedin_url', 'value' => 'https://www.linkedin.com'],
            ['key' => 'youtube_url', 'value' => 'https://www.youtube.com'],
            // Add more key-value pairs here
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
