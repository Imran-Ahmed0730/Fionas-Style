<?php
use App\Models\Admin\Setting;
use Carbon\Carbon;

function saveImagePath($image, $folderName)
{

    $currentDate = Carbon::now()->toDateString();

    $imageName = $currentDate . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
    $path = 'uploads/'.$folderName.'/';
    if (!file_exists($path)) {
        mkdir($path, 0777, true);
    }

    $image->move(public_path($path), $imageName);
    //    $image->move(base_path().'assets/images/uploads/category', $imageName);

    $image = $path.$imageName;
    return $image;
}

function updateImagePath($newImage, $oldImage, $folderName)
{
//    dd($newImage);
    $path = 'uploads/'.$folderName.'/';
    // Delete the old image
    if ($oldImage && file_exists(public_path( $path. $oldImage))) {
        unlink(public_path($path . $oldImage));
    }

    $currentDate = Carbon::now()->toDateString();
    $imageName = $currentDate . '-' . uniqid() . '.' . $newImage->getClientOriginalExtension();

    if (!file_exists($path)) {
        mkdir($path, 0777, true);
    }

    $newImage->move(public_path($path), $imageName);
    $imagePath = $path.$imageName;
    return $imagePath;
}

function getSetting($key)
{
    $setting = Setting::where('key', $key)->first();
    return $setting->value ?? '';
}
