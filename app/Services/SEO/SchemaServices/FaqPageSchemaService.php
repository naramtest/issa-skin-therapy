<?php

namespace App\Services\SEO\SchemaServices;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;
use Spatie\SchemaOrg\Schema;

class FaqPageSchemaService extends BaseSchemaService
{
    protected Collection $faqSections;

    public function setFaqSections(Collection $faqSections): static
    {
        $this->faqSections = $faqSections;
        return $this;
    }

    public function generate(): string
    {
        $this->getInfo();

        $allQuestions = $this->generateAllQuestions();
        // Create FAQPage schema
        $faqPageSchema = Schema::fAQPage()
            ->name(getPageTitle(__("store.FAQ")))
            ->description(
                __(
                    "store.Frequently asked questions about our products and services"
                )
            )
            ->url(URL::route("faq.index"))
            ->datePublished(Carbon::parse("2024-06-15"))
            ->dateModified(Carbon::parse("2024-11-20"))
            ->mainEntity($allQuestions);

        // Create Organization schema
        $organizationSchema = $this->createOrganizationSchema();

        // Create WebPage schema
        $webPageSchema = $this->createWebPageSchema(
            name: getPageTitle(__("store.FAQ")),
            description: __(
                "store.Frequently asked questions about our products and services"
            )
        );

        // Create FAQ schema

        // Combine schemas
        return $this->combineSchemas([
            $organizationSchema,
            $webPageSchema,
            $faqPageSchema,
        ]);
    }

    /**
     * Generate all questions in a flat array, without creating separate FAQPage entities
     */
    protected function generateAllQuestions(): array
    {
        if (!isset($this->faqSections)) {
            return [];
        }

        $allQuestions = [];

        foreach ($this->faqSections as $section) {
            foreach ($section->faqs as $faq) {
                $allQuestions[] = Schema::question()
                    ->name($faq->question)
                    ->acceptedAnswer(
                        Schema::answer()->text(strip_tags($faq->answer))
                    );
            }
        }

        return $allQuestions;
    }
}
