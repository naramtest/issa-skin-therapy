<?php

namespace App\Observers;

use App\Models\FaqSection;
use App\Services\Faq\FaqService;

readonly class FaqSectionObserver
{
    public function __construct(private FaqService $faqService)
    {
    }

    public function saved(FaqSection $section): void
    {
        $this->faqService->clearCache();
    }

    public function deleted(FaqSection $section): void
    {
        $this->faqService->clearCache();
    }
}
