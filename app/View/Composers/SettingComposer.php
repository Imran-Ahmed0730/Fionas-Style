<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;

class SettingComposer
{
    public function compose(View $view)
    {
        $settings = Cache::remember('settings', 3600, function () {
            return [
                'logo' => getSetting('site_logo'),
                'email' => getSetting('email'),
                'phone' => getSetting('phone'),
                'address' => getSetting('address'),
                'facebook_url' => getSetting('facebook_url'),
                'x_url' => getSetting('x_url'),
                'linkedin_url' => getSetting('linkedin_url'),
                'instagram_url' => getSetting('instagram_url'),
                'pinterest_url' => getSetting('pinterest_url'),
                'youtube_url' => getSetting('youtube_url'),
                'copyright_text' => getSetting('copyright_text'),
            ];
        });

        $view->with('settings', (object) $settings);
    }
}
