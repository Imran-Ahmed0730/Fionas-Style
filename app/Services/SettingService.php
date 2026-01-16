<?php

namespace App\Services;

use App\Models\Admin\Setting;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class SettingService
{
    public function updateSettings(array $data, array $files, string $section): void
    {
        // Collect all keys we intend to update
        $keysToUpdate = array_merge(array_keys($data), array_keys($files));

        // 1. Optimization: Only fetch settings that are present in the request
        $settings = Setting::whereIn('key', $keysToUpdate)->get();

        // 2. Optimization: specific to validation or business logic
        // If we need to process specific sections, we might want to filter,
        // but currently we rely on the controller validation to only pass valid keys.

        DB::transaction(function () use ($settings, $data, $files, $section) {
            foreach ($settings as $setting) {
                // Handle image uploads
                if ($this->isImageField($setting->key)) {
                    if (isset($files[$setting->key])) {
                        $this->updateImageSetting($files[$setting->key], $setting);
                    }
                }
                // Handle regular fields
                else {
                    if (array_key_exists($setting->key, $data)) {
                        $this->updateRegularSetting($data[$setting->key], $setting, $section);
                    }
                }
            }
        });
    }

    private function isImageField($key)
    {
        return in_array($key, [
            'site_logo',
            'site_footer_logo',
            'site_dark_logo',
            'site_favicon'
        ]);
    }

    private function updateImageSetting(UploadedFile $image, Setting $setting)
    {
        // Determine dimensions and quality based on image type
        [$width, $height, $quality] = $this->getImageSettings($setting->key);

        // Use the saveImage helper function
        $setting->value = saveImagePath( // Using saveImagePath helper as per user requested correction, though original SettingController used saveImage - wait.
            // User asked to fix updateImagePath -> saveImagePath.
            // Original SettingController creates its own usage of saveImage?
            // "Use the saveImage helper function" comment in SettingController.
            // But helper file has saveImagePath.
            // Let's assume saveImagePath is the correct one to use globally now.
            $image,
            $setting->value,
            'website-images',
            $quality,
            $width,
            $height
        );
        $setting->save();
    }

    private function getImageSettings($key)
    {
        switch ($key) {
            case 'site_logo':
            case 'site_footer_logo':
            case 'site_dark_logo':
                return [400, 150, 90]; // width, height, quality

            case 'site_favicon':
                return [64, 64, 95];

            default:
                return [500, 500, 85];
        }
    }

    private function updateRegularSetting($value, Setting $setting, $section)
    {
        // Handle nullable fields for store section
        if ($section === 'store' && $this->isNullableStoreField($setting->key)) {
            $setting->value = $value ?? null;
        } else {
            $setting->value = $value;
        }
        $setting->save();
    }

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
}
