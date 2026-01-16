<?php

namespace App\Services;

use App\Models\Admin\Banner;
use Illuminate\Http\UploadedFile;

class BannerService
{
    public function createBanner(array $data, ?UploadedFile $image): Banner
    {
        $imagePath = $image ? saveImagePath($image, null, 'banner') : null;
        $data['image'] = $imagePath;
        
        return Banner::create($data);
    }

    public function updateBanner(Banner $banner, array $data, ?UploadedFile $image): bool
    {
        if ($image && $image->isValid()) {
            $data['image'] = saveImagePath($image, $banner->image, 'banner');
        } else {
            $data['image'] = $banner->image;
        }

        return $banner->update($data);
    }

    public function deleteBanner(Banner $banner): ?bool
    {
        if ($banner->image && file_exists($banner->image)) {
            unlink($banner->image);
        }
        return $banner->delete();
    }

    public function changeStatus(Banner $banner): bool
    {
        $status = $banner->status == 1 ? 0 : 1;
        return $banner->update([
            'status' => $status,
        ]);
    }
}
