<?php

namespace App\Services;

use App\Models\Admin\Slider;
use Illuminate\Http\UploadedFile;

class SliderService
{
    public function createSlider(array $data, ?UploadedFile $image): Slider
    {
        if ($image) {
            $data['image'] = saveImagePath($image, null, 'slider');
        }

        return Slider::create($data);
    }

    public function updateSlider(Slider $slider, array $data, ?UploadedFile $image): bool
    {
        if ($image) {
            $data['image'] = saveImagePath($image, $slider->image, 'slider');
        } else {
            $data['image'] = $slider->image;
        }

        return $slider->update($data);
    }

    public function deleteSlider(int $id): bool
    {
        $slider = Slider::findOrFail($id);
        if ($slider->image && file_exists($slider->image)) {
            unlink($slider->image);
        }
        return $slider->delete();
    }

    public function changeStatus(int $id): bool
    {
        $slider = Slider::findOrFail($id);
        $status = $slider->status == 1 ? 0 : 1;
        return $slider->update(['status' => $status]);
    }
}
