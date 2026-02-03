<?php
use App\Models\Admin\Setting;
use Carbon\Carbon;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;

/**
 * Save or update image with WebP conversion and compression
 *
 * @param \Illuminate\Http\UploadedFile $image New image file
 * @param string|null $oldImage Old image path (null for new save)
 * @param string $folderName Folder name inside uploads directory
 * @param int $quality Quality for WebP (1-100, default: 80)
 * @param int|null $maxWidth Maximum width (null for no resize)
 * @param int|null $maxHeight Maximum height (null for no resize)
 * @return string New image path
 */
function saveImagePath($image, $oldImage = null, $folderName = 'images', $quality = 80, $maxWidth = null, $maxHeight = null)
{
    // Delete old image if exists (for update operation)
    if ($oldImage && File::exists(public_path($oldImage))) {
        File::delete(public_path($oldImage));
    }

    // Generate unique filename with .webp extension
    $currentDate = Carbon::now()->toDateString();
    $imageName = $currentDate . '-' . uniqid() . '.webp';

    // Prepare path
    $path = 'uploads/' . $folderName . '/';
    $fullPath = public_path($path);

    // Create directory if it doesn't exist
    if (!File::exists($fullPath)) {
        File::makeDirectory($fullPath, 0777, true);
    }

    // Initialize ImageManager with GD driver (or Imagick)
    $manager = new ImageManager(new Driver());

    // Read and process image
    $img = $manager->read($image->getRealPath());

    // Resize if dimensions are specified
    if ($maxWidth || $maxHeight) {
        $img->scale(
            width: $maxWidth,
            height: $maxHeight
        );
    }

    // Save as WebP with compression
    $img->toWebp($quality)->save($fullPath . $imageName);

    return $path . $imageName;
}

/**
 * Delete image from storage
 *
 * @param string|null $imagePath
 * @return bool
 */
function deleteImage($imagePath)
{
    if ($imagePath && File::exists(public_path($imagePath))) {
        return File::delete(public_path($imagePath));
    }

    return false;
}

/**
 * Get setting value by key
 *
 * @param string $key
 * @param mixed $default Default value if setting not found
 * @return mixed
 */
function getSetting($key, $default = '')
{
    $setting = Setting::where('key', $key)->first();
    return $setting?->value ?? $default;
}

/**
 * Get currency settings
 *
 * @return array
 */
function getCurrency()
{
    return [
        'name' => getSetting('currency_name', 'USD'),
        'symbol' => getSetting('currency_symbol', '$'),
        'position' => getSetting('currency_position', 'left'),
    ];
}
