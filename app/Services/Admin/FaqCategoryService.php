<?php

namespace App\Services\Admin;

use App\Models\Admin\FaqCategory;

class FaqCategoryService
{
    public function store(array $data): FaqCategory
    {
        $category = FaqCategory::create($data);

        if (isset($data['question']) && count($data['question']) > 0) {
            foreach ($data['question'] as $key => $question) {
                if (!empty($question) && !empty($data['answer'][$key])) {
                    $category->faqs()->create([
                        'question' => $question,
                        'answer' => $data['answer'][$key],
                        'status' => 1, // Default active
                        'added_by' => auth()->id() ?? 1,
                    ]);
                }
            }
        }

        return $category;
    }

    public function update(FaqCategory $faqCategory, array $data): bool
    {
        $faqCategory->update($data);

        // Sync FAQs: Delete existing and recreate
        // Ideally we would update existing ones if IDs were provided, but for this simple form re-creation is acceptable
        $faqCategory->faqs()->delete();

        if (isset($data['question']) && count($data['question']) > 0) {
            foreach ($data['question'] as $key => $question) {
                if (!empty($question) && !empty($data['answer'][$key])) {
                    $faqCategory->faqs()->create([
                        'question' => $question,
                        'answer' => $data['answer'][$key],
                        'status' => 1,
                        'added_by' => auth()->id() ?? 1,
                    ]);
                }
            }
        }

        return true;
    }

    public function destroy(FaqCategory $faqCategory): bool
    {
        $faqCategory->faqs()->delete();
        return $faqCategory->delete();
    }

    public function changeStatus(FaqCategory $faqCategory): bool
    {
        return $faqCategory->update([
            'status' => !$faqCategory->status
        ]);
    }
}
