<?php

namespace App\Services;

use App\Models\Admin\Color;

class ColorService
{
    public function createColor(array $data): Color
    {
        return Color::create($data);
    }

    public function updateColor(Color $color, array $data): bool
    {
        return $color->update($data);
    }

    public function deleteColor(Color $color): ?bool
    {
        return $color->delete();
    }
}
