<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;
use App\Models\Admin\Setting;

class FooterComposer
{
    public function compose(View $view)
    {
        $settings = Cache::remember('site_settings', 3600, function () {
            return Setting::pluck('value', 'key')->toArray();
        });

        $view->with([
            'footerAbout' => $settings['footer_about'] ?? '',
            'footerCopyright' => $settings['footer_copyright'] ?? '',
            'footerLogo' => $settings['site_footer_logo'] ?? null,
            'paymentMethods' => $settings['payment_methods'] ?? '',
        ]);
    }
}
