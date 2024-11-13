<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\FaqSection;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Product FAQ Section
        $productSection = FaqSection::factory()->productSection()->create();

        // Create Product FAQs
        Faq::factory()
            ->productFaqs()
            ->count(5)
            ->sequence(
                fn($sequence) => [
                    "faq_section_id" => $productSection->id,
                    "sort_order" => $sequence->index + 1,
                    "is_active" => true,
                ]
            )
            ->create();

        // Common FAQ Sections with their respective FAQs
        $sections = [
            [
                "title" => [
                    "en" => "Account & Registration",
                    "ar" => "الحساب والتسجيل",
                ],
                "description" => [
                    "en" =>
                        "Common questions about your account, login, and registration process.",
                    "ar" =>
                        "الأسئلة الشائعة حول حسابك وتسجيل الدخول وعملية التسجيل.",
                ],
                "sort_order" => 1,
                "faqs_count" => 3,
                "identifier" => "account", // Added identifier for matching
            ],
            [
                "title" => [
                    "en" => "Orders & Payment",
                    "ar" => "الطلبات والدفع",
                ],
                "description" => [
                    "en" =>
                        "Everything you need to know about placing orders and payment methods.",
                    "ar" => "كل ما تحتاج لمعرفته حول تقديم الطلبات وطرق الدفع.",
                ],
                "sort_order" => 2,
                "faqs_count" => 4,
                "identifier" => "orders",
            ],
            [
                "title" => [
                    "en" => "Shipping & Delivery",
                    "ar" => "الشحن والتوصيل",
                ],
                "description" => [
                    "en" =>
                        "Find answers about shipping methods, delivery times, and tracking orders.",
                    "ar" =>
                        "احصل على إجابات حول طرق الشحن وأوقات التوصيل وتتبع الطلبات.",
                ],
                "sort_order" => 3,
                "faqs_count" => 4,
                "identifier" => "shipping",
            ],
            [
                "title" => [
                    "en" => "Returns & Refunds",
                    "ar" => "الإرجاع واسترداد الأموال",
                ],
                "description" => [
                    "en" => "Learn about our return policy and refund process.",
                    "ar" => "تعرف على سياسة الإرجاع وعملية استرداد الأموال.",
                ],
                "sort_order" => 4,
                "faqs_count" => 3,
                "identifier" => "returns",
            ],
        ];

        foreach ($sections as $sectionData) {
            $faqs_count = $sectionData["faqs_count"];
            $identifier = $sectionData["identifier"];
            unset($sectionData["faqs_count"], $sectionData["identifier"]);

            $section = FaqSection::factory()->create($sectionData);

            // Create FAQs based on section identifier
            $faqFactory = match ($identifier) {
                "account" => Faq::factory()
                    ->count($faqs_count)
                    ->sequence(
                        [
                            "question" => [
                                "en" => "How do I create an account?",
                                "ar" => "كيف يمكنني إنشاء حساب؟",
                            ],
                            "sort_order" => 1,
                        ],
                        [
                            "question" => [
                                "en" => "How can I reset my password?",
                                "ar" => "كيف يمكنني إعادة تعيين كلمة المرور؟",
                            ],
                            "sort_order" => 2,
                        ],
                        [
                            "question" => [
                                "en" => "Can I change my email address?",
                                "ar" =>
                                    "هل يمكنني تغيير عنوان بريدي الإلكتروني؟",
                            ],
                            "sort_order" => 3,
                        ]
                    ),
                "orders" => Faq::factory()
                    ->count($faqs_count)
                    ->sequence(
                        [
                            "question" => [
                                "en" => "What payment methods do you accept?",
                                "ar" => "ما هي طرق الدفع المقبولة لديكم؟",
                            ],
                            "sort_order" => 1,
                        ],
                        [
                            "question" => [
                                "en" => "How can I track my order?",
                                "ar" => "كيف يمكنني تتبع طلبي؟",
                            ],
                            "sort_order" => 2,
                        ],
                        [
                            "question" => [
                                "en" =>
                                    "Can I modify my order after placing it?",
                                "ar" => "هل يمكنني تعديل طلبي بعد تقديمه؟",
                            ],
                            "sort_order" => 3,
                        ],
                        [
                            "question" => [
                                "en" =>
                                    "Is it safe to use my credit card on your website?",
                                "ar" =>
                                    "هل من الآمن استخدام بطاقتي الائتمانية على موقعكم؟",
                            ],
                            "sort_order" => 4,
                        ]
                    ),
                "shipping" => Faq::factory()
                    ->shippingFaqs()
                    ->count($faqs_count),
                "returns" => Faq::factory()->returnFaqs()->count($faqs_count),
                default => Faq::factory()->count($faqs_count),
            };

            $faqFactory
                ->sequence(
                    fn($sequence) => [
                        "faq_section_id" => $section->id,
                        "sort_order" => $sequence->index + 1,
                        "is_active" => true,
                    ]
                )
                ->create();
        }
    }
}
