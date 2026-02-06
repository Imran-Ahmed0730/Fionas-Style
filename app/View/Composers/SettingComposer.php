<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;

class SettingComposer
{
    public function compose(View $view)
    {
        $settings = Cache::remember('settings', config('cache_settings.long', 3600), function () {
            $logo = getSetting('site_logo');
            $email = getSetting('email');
            $phone = getSetting('phone');
            $address = getSetting('address');
            $facebook = getSetting('facebook_url');
            $x = getSetting('x_url');
            $linkedin = getSetting('linkedin_url');
            $instagram = getSetting('instagram_url');
            $pinterest = getSetting('pinterest_url');
            $youtube = getSetting('youtube_url');

            $homeUrl = route('home');
            $devName = e(getSetting('developed_by'));
            $devUrl = getSetting('developed_by_url');

            $devLink = $devUrl ? '<a target="_blank" href="'.e($devUrl).'">'.$devName.'</a>' : $devName;

            $copyright = '&copy; '.date('Y').' <a href="'.e($homeUrl).'">Fiona\'s Style</a>. All rights reserved. Developed By '.$devLink;

            return [
                'logo' => $logo,
                'email' => $email,
                'phone' => $phone,
                'address' => $address,
                'facebook_url' => $facebook,
                'x_url' => $x,
                'linkedin_url' => $linkedin,
                'instagram_url' => $instagram,
                'pinterest_url' => $pinterest,
                'youtube_url' => $youtube,
                'copyright_text' => $copyright,
            ];
        });

        $view->with('settings', (object) $settings);
    }
}
