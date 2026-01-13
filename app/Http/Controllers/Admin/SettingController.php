<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Setting;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;

class SettingController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('permission:Settings Update',only: ['goToSection']),
        ];
    }
    public function create(){
        return view('backend.setting.form');
    }
    public function store(Request $request){
        $request->validate([
            'key' => 'required|unique:settings',
        ]);
        Setting::create($request->all());
        return redirect()->route('admin.setting.index')->with('success','Setting created successfully');
    }
    //
    public function index(){
        $data['items'] = Setting::latest()->get();
        return view('backend.setting.list',$data);
    }

    public function update(Request $request)
    {
        $prevUrl = url()->previous();
        $section = $this->getSection($prevUrl);

        // Validate based on section
        $this->validateSection($request, $section);

        // Update settings
        $this->updateSettings($request, $section);

        return back()->with('success', 'Settings updated successfully');
    }

    /**
     * Get section from previous URL
     */
    private function getSection($url)
    {
        if (Str::contains($url, 'contact')) {
            return 'contact';
        } elseif (Str::contains($url, 'logos-favicon')) {
            return 'logos-favicon';
        } elseif (Str::contains($url, 'social-media')) {
            return 'social-media';
        } elseif (Str::contains($url, 'activation')) {
            return 'activation';
        } elseif (Str::contains($url, 'store')) {
            return 'store';
        } elseif (Str::contains($url, 'site')) {
            return 'site';
        }

        return 'site'; // default
    }

    /**
     * Validate request based on section
     */
    private function validateSection(Request $request, $section)
    {
        $rules = $this->getValidationRules($section);

        if (!empty($rules)) {
            $request->validate($rules);
        }
    }

    /**
     * Get validation rules for each section
     */
    private function getValidationRules($section)
    {
        switch ($section) {
            case 'site':
                return [
                    'site_name' => 'required|string|max:255',
                    'business_name' => 'required|string|max:255',
                    'site_url' => 'required|url|max:255',
                    'short_bio' => 'required|string|max:500',
                    'meta_description' => 'required|string|max:500',
                    'meta_keywords' => 'required|string|max:255',
                ];

            case 'contact':
                return [
                    'email' => 'required|email|max:255',
                    'phone' => 'required|string|max:20',
                    'whatsapp' => 'required|string|max:20',
                ];

            case 'logos-favicon':
                $rules = [];
                $imageFields = ['site_logo', 'site_footer_logo', 'site_dark_logo', 'site_favicon'];

                foreach ($imageFields as $field) {
                    if (request()->hasFile($field)) {
                        $rules[$field] = 'image|mimes:jpeg,png,jpg|max:2048'; // 2MB max
                    }
                }

                return $rules;

            case 'social-media':
                return [
                    'facebook' => 'nullable|url|max:255',
                    'twitter' => 'nullable|url|max:255',
                    'instagram' => 'nullable|url|max:255',
                    'linkedin' => 'nullable|url|max:255',
                    'youtube' => 'nullable|url|max:255',
                    'pinterest' => 'nullable|url|max:255',
                    'tiktok' => 'nullable|url|max:255',
                ];

            case 'activation':
                return [
                    'user_verification' => 'required|in:0,1',
                    'free_delivery_above' => 'required|integer|min:0',
                    'guest_account' => 'required|min:0,1',
                ];

            case 'store':
                return [
                    'address' => 'nullable|string|max:500',
                    'shop_map_location' => 'nullable|string',
                    'opening_time' => 'nullable|date_format:H:i',
                    'closing_time' => 'nullable|date_format:H:i',
                    'closed_on' => 'nullable|string|max:255',
                    'delivery_charge' => 'nullable|numeric|min:0',
                    'minimum_order' => 'nullable|numeric|min:0',
                ];

            default:
                return [];
        }
    }

    /**
     * Update settings based on section
     */
    private function updateSettings(Request $request, $section)
    {
        $settings = Setting::all();

        foreach ($settings as $setting) {
            // Handle image uploads
            if ($this->isImageField($setting->key)) {
                $this->updateImageSetting($request, $setting);
            }
            // Handle regular fields
            else {
                $this->updateRegularSetting($request, $setting, $section);
            }

            $setting->save();
        }
    }

    /**
     * Check if field is an image field
     */
    private function isImageField($key)
    {
        return in_array($key, [
            'site_logo',
            'site_footer_logo',
            'site_dark_logo',
            'site_favicon'
        ]);
    }

    /**
     * Update image setting
     */
    private function updateImageSetting(Request $request, Setting $setting)
    {
        if ($request->hasFile($setting->key)) {
            $image = $request->file($setting->key);

            // Determine dimensions and quality based on image type
            [$width, $height, $quality] = $this->getImageSettings($setting->key);

            // Use the saveImage helper function
            $setting->value = saveImage(
                image: $image,
                oldImage: $setting->value,
                folderName: 'website-images',
                quality: $quality,
                maxWidth: $width,
                maxHeight: $height
            );
        }
        // Keep existing value if no new image uploaded
    }

    /**
     * Get image settings based on type
     */
    private function getImageSettings($key)
    {
        switch ($key) {
            case 'site_logo':
            case 'site_footer_logo':
            case 'site_dark_logo':
                return [400, 150, 90]; // width, height, quality

            case 'site_favicon':
                return [64, 64, 95]; // favicon should be small and high quality

            default:
                return [500, 500, 85];
        }
    }

    /**
     * Update regular (non-image) setting
     */
    private function updateRegularSetting(Request $request, Setting $setting, $section)
    {

        // Update if value is present in request
        if ($request->has($setting->key)) {
            $value = $request->input($setting->key);

            // Handle nullable fields for store section
            if ($section === 'store' && $this->isNullableStoreField($setting->key)) {
                $setting->value = $value ?? null;
            } else {
                $setting->value = $value;
            }
        }
    }

    /**
     * Check if field is nullable in store section
     */
    private function isNullableStoreField($key)
    {
        return in_array($key, [
            'address',
            'shop_map_location',
            'opening_time',
            'closing_time',
            'closed_on'
        ]);
    }

    /**
     * Navigate to specific setting section
     */
    public function goToSection($slug)
    {
        $data = [];

        // Define permissions for each section
        $permissions = [
            'site' => 'Settings Site',
            'activation' => 'Settings Activation',
            'contact' => 'Settings Contact',
            'logos-favicon' => 'Settings Logo & Favicon',
            'social-media' => 'Settings Social Media',
            'store' => 'Settings Store',
        ];

        // Check permissions
        if (array_key_exists($slug, $permissions) && !auth()->user()->can($permissions[$slug])) {
            abort(403, 'User does not have the right permissions.');
        }

        // Map slug to view
        $viewMap = [
            'site' => 'index',
            'contact' => 'contact',
            'logos-favicon' => 'logo_favicon',
            'social-media' => 'social_media',
            'activation' => 'activation',
            'store' => 'store',
        ];

        // Get view or redirect to default
        $view = $viewMap[$slug] ?? null;

        if (!$view) {
            return redirect()->route('admin.setting.edit', 'site');
        }

        return view('backend.setting.' . $view, $data);
    }

}
