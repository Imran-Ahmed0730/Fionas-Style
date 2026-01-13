<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;
use App\Models\Admin\Setting;

class HeaderComposer
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        // Cache settings for 1 hour (3600 seconds)
        $settings = Cache::remember('site_settings', 3600, function () {
            return Setting::pluck('value', 'key')->toArray();
        });

        // Pass individual settings to view
        $view->with([
            'siteLogo' => $settings['site_logo'] ?? null,
            'siteName' => $settings['site_name'] ?? config('app.name'),
            'siteEmail' => $settings['email'] ?? '',
            'sitePhone' => $settings['phone'] ?? '',
            'siteWhatsapp' => $settings['whatsapp'] ?? '',
            'facebookUrl' => $settings['facebook'] ?? '',
            'twitterUrl' => $settings['twitter'] ?? '',
            'linkedinUrl' => $settings['linkedin'] ?? '',
            'pinterestUrl' => $settings['pinterest'] ?? '',
            'instagramUrl' => $settings['instagram'] ?? '',
            'youtubeUrl' => $settings['youtube'] ?? '',
        ]);
    }
}
