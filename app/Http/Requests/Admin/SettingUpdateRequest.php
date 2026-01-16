<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class SettingUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // For simple store/creation of settings (less used, mostly seed or manual db, but controller has a store method)
        if ($this->routeIs('admin.setting.store')) {
             return [
                'key' => 'required|unique:settings',
             ];
        }

        $section = $this->getSection(url()->previous());

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
                    if ($this->hasFile($field)) {
                        $rules[$field] = 'image|mimes:jpeg,png,jpg|max:2048';
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
                ];

            default:
                return [];
        }
    }

    public function getSection($url)
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
}
