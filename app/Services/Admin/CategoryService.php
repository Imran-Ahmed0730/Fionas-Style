<?php

namespace App\Services\Admin;

use App\Models\Admin\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class CategoryService
{
    public function createCategory(array $data, ?UploadedFile $icon, ?UploadedFile $coverPhoto, ?UploadedFile $metaImage): Category
    {
        $data['slug'] = Str::slug($data['name']);

        $data['icon'] = $icon ? saveImagePath($icon, null, 'category/icon') : null;
        $data['cover_photo'] = $coverPhoto ? saveImagePath($coverPhoto, null, 'category/cover-photo') : null;
        $data['meta_image'] = $metaImage ? saveImagePath($metaImage, null, 'category/meta-image') : null;

        if (isset($data['parent_id']) && $data['parent_id'] != 0) {
            $parentCategory = Category::find($data['parent_id']);
            $data['level'] = $parentCategory ? $parentCategory->level + 1 : 1;
        } else {
            $data['level'] = 1;
        }

        return Category::create($data);
    }

    public function updateCategory(Category $category, array $data, ?UploadedFile $icon, ?UploadedFile $coverPhoto, ?UploadedFile $metaImage): bool
    {
        $data['slug'] = Str::slug($data['name']);

        if ($icon && $icon->isValid()) {
            $data['icon'] = saveImagePath($icon, $category->icon, 'category/icon');
        } else {
            $data['icon'] = $category->icon;
        }

        if ($coverPhoto && $coverPhoto->isValid()) {
            $data['cover_photo'] = saveImagePath($coverPhoto, $category->cover_photo, 'category/cover-photo');
        } else {
            $data['cover_photo'] = $category->cover_photo;
        }

        if ($metaImage && $metaImage->isValid()) {
            $data['meta_image'] = saveImagePath($metaImage, $category->meta_image, 'category/meta-image');
        } else {
            $data['meta_image'] = $category->meta_image;
        }

        if (isset($data['parent_id']) && $data['parent_id'] != 0) {
            $parentCategory = Category::find($data['parent_id']);
            $data['level'] = $parentCategory ? $parentCategory->level + 1 : 1;
        } else {
             $data['level'] = 1;
        }

        return $category->update($data);
    }

    public function deleteCategory(Category $category): ?bool
    {
        if ($category->icon && file_exists($category->icon)) {
            unlink($category->icon);
        }
        if ($category->cover_photo && file_exists($category->cover_photo)) {
            unlink($category->cover_photo);
        }
        if ($category->meta_image && file_exists($category->meta_image)) {
            unlink($category->meta_image);
        }
        return $category->delete();
    }

    public function changeStatus(Category $category): bool
    {
        $status = $category->status == 1 ? 0 : 1;
        return $category->update([
            'status' => $status,
        ]);
    }

    public function homeInclude(Category $category): bool
    {
        $included = $category->included_to_home == 1 ? 0 : 1;
        return $category->update([
            'included_to_home' => $included,
        ]);
    }

    public function featuredInclude(Category $category): bool
    {
        $featured = $category->is_featured == 1 ? 0 : 1;
        return $category->update([
            'is_featured' => $featured,
        ]);
    }

    public function getActiveCategories()
    {
        return Category::active()->orderBy('name', 'asc')->get();
    }
}
