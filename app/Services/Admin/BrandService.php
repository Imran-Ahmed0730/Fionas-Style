<?php

namespace App\Services\Admin;

use App\Models\Admin\Brand;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class BrandService
{
    public function createBrand(array $data, ?UploadedFile $image, ?UploadedFile $metaImage): Brand
    {
        $data['slug'] = Str::slug($data['name']);

        $data['image'] = $image ? saveImagePath($image, null, 'brand') : null;
        $data['meta_image'] = $metaImage ? saveImagePath($metaImage, null, 'brand/meta-image') : null;

        return Brand::create($data);
    }

    public function updateBrand(Brand $brand, array $data, ?UploadedFile $image, ?UploadedFile $metaImage): bool
    {
        if ($brand->name != $data['name']) {
             $data['slug'] = Str::slug($data['name']);
        } // Controller re-validates unique name but doesn't explicitly update slug if name changes?
          // Line 113 in original controller: 'slug' => Str::slug($request->name),
          // So it updates slug every time.
        $data['slug'] = Str::slug($data['name']);

        if ($image && $image->isValid()) {
            $data['image'] = saveImagePath($image, $brand->image, 'brand');
        } else {
            $data['image'] = $brand->image;
        }

        if ($metaImage && $metaImage->isValid()) {
            $data['meta_image'] = saveImagePath($metaImage, $brand->meta_image, 'brand/meta-image');
        } else {
            $data['meta_image'] = $brand->meta_image;
        }

        return $brand->update($data);
    }

    public function deleteBrand(Brand $brand): ?bool
    {
        if ($brand->image && file_exists($brand->image)) {
            unlink($brand->image);
        }
        if ($brand->meta_image && file_exists($brand->meta_image)) {
            unlink($brand->meta_image);
        }
        return $brand->delete();
    }

    public function changeStatus(Brand $brand): bool
    {
        $status = $brand->status == 1 ? 0 : 1;
        return $brand->update([
            'status' => $status,
        ]);
    }
}
