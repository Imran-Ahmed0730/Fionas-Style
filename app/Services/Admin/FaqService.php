<?php

namespace App\Services\Admin;

use App\Models\Admin\Faq;

class FaqService
{
    public function store(array $data): Faq
    {
        $data['added_by'] = auth()->id() ?? 1;
        return Faq::create($data);
    }

    public function update(Faq $faq, array $data): bool
    {
        return $faq->update($data);
    }

    public function destroy(Faq $faq): bool
    {
        return $faq->delete();
    }

    public function changeStatus(Faq $faq): bool
    {
        return $faq->update([
            'status' => !$faq->status
        ]);
    }
}
