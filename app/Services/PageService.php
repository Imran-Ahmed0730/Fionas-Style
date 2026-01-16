<?php

namespace App\Services;

use App\Models\Admin\Page;
use Illuminate\Http\UploadedFile;

class PageService
{
    /**
     * Create a new page.
     *
     * @param array $data
     * @param UploadedFile|null $image
     * @return Page
     */
    public function createPage(array $data, ?UploadedFile $image): Page
    {
        $metaImage = $image ? saveImagePath($image, null, 'page') : null;
        
        $data['slug'] = str()->slug($data['title']) . uniqid();
        $data['meta_image'] = $metaImage;

        return Page::create($data);
    }

    /**
     * Update an existing page.
     *
     * @param Page $page
     * @param array $data
     * @param UploadedFile|null $image
     * @return bool
     */
    public function updatePage(Page $page, array $data, ?UploadedFile $image): bool
    {
        if ($page->title != $data['title']) {
            $data['slug'] = str()->slug($data['title']) . uniqid();
        } else {
            $data['slug'] = $page->slug;
        }

        if ($image && $image->isValid()) {
            $data['meta_image'] = saveImagePath($image, $page->meta_image, 'page');
        } else {
            $data['meta_image'] = $page->meta_image;
        }

        return $page->update($data);
    }

    /**
     * Delete a page.
     *
     * @param Page $page
     * @return bool|null
     */
    public function deletePage(Page $page): ?bool
    {
        // Ideally we should delete the image too if it exists, 
        // but sticking to original logic which didn't seem to delete it explicitly 
        // or relied on model observers/helper handling.
        // Assuming original destroy only deleted the record.
        if ($page->meta_image) {
            deleteImage($page->meta_image);
        }
        return $page->delete();
    }

    /**
     * Change page status.
     *
     * @param Page $page
     * @return bool
     */
    public function changeStatus(Page $page): bool
    {
        return $page->update([
            'status' => !$page->status,
        ]);
    }
}
