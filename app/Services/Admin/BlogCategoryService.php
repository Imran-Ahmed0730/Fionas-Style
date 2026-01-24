<?php

namespace App\Services\Admin;

use App\Models\Admin\BlogCategory;
use Illuminate\Support\Str;

class BlogCategoryService
{
    public function store(array $data): BlogCategory
    {
        $data['slug'] = Str::slug($data['name']).uniqid();

        if (isset($data['meta_image'])) {
            $data['meta_image'] = saveImagePath($data['meta_image'], null, 'blog-category');
        }

        return BlogCategory::create($data);
    }

    public function update(BlogCategory $blogCategory, array $data): bool
    {
        if ($data['name'] !== $blogCategory->name) {
            $data['slug'] = Str::slug($data['name']).uniqid();
        }

        if (isset($data['meta_image'])) {
            $data['meta_image'] = saveImagePath($data['meta_image'], $blogCategory->meta_image, 'blog-category');
        }

        return $blogCategory->update($data);
    }

    public function destroy(BlogCategory $blogCategory): bool
    {
        if ($blogCategory->meta_image && file_exists($blogCategory->meta_image)) {
            unlink($blogCategory->meta_image);
        }

        if ($blogCategory->blogs()->exists()) {
            $blogCategory->blogs()->update([
                'category_id' => 1
            ]);
        }

        return $blogCategory->delete();
    }

    public function changeStatus(BlogCategory $blogCategory): bool
    {
        return $blogCategory->update([
            'status' => !$blogCategory->status
        ]);
    }
}
