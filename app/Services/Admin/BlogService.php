<?php

namespace App\Services\Admin;

use App\Models\Admin\Blog;
use Illuminate\Support\Str;

class BlogService
{
    public function store(array $data): Blog
    {
        $data['slug'] = Str::slug($data['title']) . uniqid();

        if (isset($data['thumbnail'])) {
            $data['thumbnail'] = saveImagePath($data['thumbnail'], null, 'blog/thumbnail');
        }
        if (isset($data['cover_photo'])) {
            $data['cover_photo'] = saveImagePath($data['cover_photo'], null, 'blog/cover');
        }
        if (isset($data['meta_image'])) {
            $data['meta_image'] = saveImagePath($data['meta_image'], null, 'blog/meta');
        }

        return Blog::create($data);
    }

    public function update(Blog $blog, array $data): bool
    {
        if ($data['title'] !== $blog->title) {
            $data['slug'] = Str::slug($data['title']) . uniqid();
        }

        if (isset($data['thumbnail'])) {
            $data['thumbnail'] = saveImagePath($data['thumbnail'], $blog->thumbnail, 'blog/thumbnail');
        }
        if (isset($data['cover_photo'])) {
            $data['cover_photo'] = saveImagePath($data['cover_photo'], $blog->cover_photo, 'blog/cover');
        }
        if (isset($data['meta_image'])) {
            $data['meta_image'] = saveImagePath($data['meta_image'], $blog->meta_image, 'blog/meta');
        }

        return $blog->update($data);
    }

    public function destroy(Blog $blog): bool
    {
        if ($blog->thumbnail && file_exists($blog->thumbnail)) {
            unlink($blog->thumbnail);
        }
        if ($blog->cover_photo && file_exists($blog->cover_photo)) {
            unlink($blog->cover_photo);
        }
        if ($blog->meta_image && file_exists($blog->meta_image)) {
            unlink($blog->meta_image);
        }
        return $blog->delete();
    }

    public function changeStatus(Blog $blog): bool
    {
        return $blog->update([
            'status' => !$blog->status
        ]);
    }
}
