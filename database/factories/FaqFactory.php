<?php
// database/factories/FaqFactory.php

namespace Database\Factories;

use App\Models\Faq;
use App\Models\FaqSection;
use Illuminate\Database\Eloquent\Factories\Factory;

class FaqFactory extends Factory
{
    protected $model = Faq::class;

    public function definition(): array
    {
        return [
            "faq_section_id" => FaqSection::factory(),
            "question" => [
                "en" => fake()->sentence() . "?",
                "ar" => "هل " . fake()->sentence() . "؟",
            ],
            "answer" => [
                "en" => fake()->paragraphs(2, true),
                "ar" => "جواب " . fake()->paragraphs(2, true),
            ],
            "sort_order" => fake()->numberBetween(0, 100),
            "is_active" => fake()->boolean(80), // 80% chance of being active
        ];
    }

    /**
     * FAQ for product-related questions
     */
    public function productFaqs(): static
    {
        return $this->sequence(
            [
                "question" => [
                    "en" =>
                        "How do I know which product is right for my skin type?",
                    "ar" => "كيف أعرف المنتج المناسب لنوع بشرتي؟",
                ],
                "answer" => [
                    "en" =>
                        "To determine the best product for your skin type:\n\n1. Identify your skin type (dry, oily, combination, sensitive)\n2. Look for product descriptions that match your skin concerns\n3. Check the ingredients list for known irritants if you have sensitive skin\n4. Start with a sample size or patch test when trying new products\n5. Consult our skin quiz tool for personalized recommendations",
                    "ar" =>
                        "لتحديد أفضل منتج لنوع بشرتك:\n\n1. حددي نوع بشرتك (جافة، دهنية، مختلطة، حساسة)\n2. ابحثي عن وصف المنتجات التي تناسب مشاكل بشرتك\n3. تحققي من قائمة المكونات\n4. ابدئي بعينة صغيرة\n5. استشيري أداة اختبار البشرة للحصول على توصيات شخصية",
                ],
            ],
            [
                "question" => [
                    "en" => "What is the shelf life of your products?",
                    "ar" => "ما هي مدة صلاحية منتجاتكم؟",
                ],
                "answer" => [
                    "en" =>
                        "Our products typically have a shelf life of 24-36 months when unopened. Once opened, most products should be used within 12 months for optimal effectiveness. Each product has a PAO (Period After Opening) symbol indicating its specific shelf life after opening. Store products in a cool, dry place away from direct sunlight to maintain their quality.",
                    "ar" =>
                        "تتمتع منتجاتنا عادةً بفترة صلاحية تتراوح بين 24-36 شهراً عندما تكون غير مفتوحة. بمجرد فتحها، يجب استخدام معظم المنتجات في غضون 12 شهراً للحصول على أفضل فعالية. يحتوي كل منتج على رمز PAO يشير إلى مدة صلاحيته المحددة بعد الفتح. قومي بتخزين المنتجات في مكان بارد وجاف بعيداً عن أشعة الشمس المباشرة للحفاظ على جودتها.",
                ],
            ],
            [
                "question" => [
                    "en" => "Are your products tested on animals?",
                    "ar" => "هل يتم اختبار منتجاتكم على الحيوانات؟",
                ],
                "answer" => [
                    "en" =>
                        "No, we are proudly cruelty-free. None of our products or ingredients are tested on animals at any stage of product development. We're certified by Leaping Bunny and PETA as a cruelty-free brand. We also ensure our suppliers adhere to the same ethical standards.",
                    "ar" =>
                        "لا، نحن فخورون بأننا خالون من القسوة. لا يتم اختبار أي من منتجاتنا أو مكوناتنا على الحيوانات في أي مرحلة من مراحل تطوير المنتج. نحن معتمدون من Leaping Bunny و PETA كعلامة تجارية خالية من القسوة. نحن نضمن أيضاً التزام موردينا بنفس المعايير الأخلاقية.",
                ],
            ],
            [
                "question" => [
                    "en" => "Can I use multiple products together?",
                    "ar" => "هل يمكنني استخدام عدة منتجات معاً؟",
                ],
                "answer" => [
                    "en" =>
                        "Yes, our products are designed to work together harmoniously. However, we recommend introducing new products gradually and following these guidelines:\n\n1. Start with basic products (cleanser, moisturizer, sunscreen)\n2. Add treatments one at a time\n3. Wait 1-2 weeks before adding another product\n4. Follow our recommended layering guide\n5. Some ingredients shouldn't be used together - check our compatibility chart",
                    "ar" =>
                        "نعم، تم تصميم منتجاتنا للعمل معاً بتناغم. ومع ذلك، نوصي بإدخال المنتجات الجديدة تدريجياً واتباع هذه الإرشادات:\n\n1. ابدئي بالمنتجات الأساسية (المنظف، المرطب، واقي الشمس)\n2. أضيفي العلاجات واحداً تلو الآخر\n3. انتظري 1-2 أسبوع قبل إضافة منتج آخر\n4. اتبعي دليل الطبقات الموصى به\n5. بعض المكونات لا يجب استخدامها معاً - راجعي جدول التوافق",
                ],
            ],
            [
                "question" => [
                    "en" => "What ingredients do you avoid in your products?",
                    "ar" => "ما هي المكونات التي تتجنبونها في منتجاتكم؟",
                ],
                "answer" => [
                    "en" =>
                        "We maintain a strict 'No-No' ingredient list that includes:\n\n- Parabens\n- Synthetic fragrances\n- Phthalates\n- Sulfates (SLS/SLES)\n- Mineral oils\n- Formaldehyde\n- Artificial colors\n\nAll our ingredients are carefully selected for both safety and efficacy, and we regularly update our formulations based on the latest scientific research.",
                    "ar" =>
                        "نحتفظ بقائمة صارمة من المكونات الممنوعة التي تشمل:\n\n- البارابينات\n- العطور الاصطناعية\n- الفثالات\n- الكبريتات\n- الزيوت المعدنية\n- الفورمالديهايد\n- الألوان الاصطناعية\n\nيتم اختيار جميع مكوناتنا بعناية من أجل السلامة والفعالية، ونقوم بتحديث تركيباتنا بانتظام بناءً على أحدث الأبحاث العلمية.",
                ],
            ]
        );
    }

    /**
     * FAQ for shipping questions
     */
    public function shippingFaqs(): static
    {
        return $this->sequence(
            [
                "question" => [
                    "en" => "What are your shipping rates?",
                    "ar" => "ما هي تكاليف الشحن؟",
                ],
                "answer" => [
                    "en" =>
                        "Our shipping rates vary by location:\n\n- UAE: Free shipping on orders over AED 200\n- GCC: Free shipping on orders over AED 500\n- International: Free shipping on orders over AED 750\n\nStandard shipping times:\n- UAE: 1-3 business days\n- GCC: 3-5 business days\n- International: 7-14 business days",
                    "ar" =>
                        "تختلف أسعار الشحن حسب الموقع:\n\n- الإمارات: شحن مجاني للطلبات التي تزيد عن 200 درهم\n- دول الخليج: شحن مجاني للطلبات التي تزيد عن 500 درهم\n- دولي: شحن مجاني للطلبات التي تزيد عن 750 درهم\n\nأوقات الشحن القياسية:\n- الإمارات: 1-3 أيام عمل\n- دول الخليج: 3-5 أيام عمل\n- دولي: 7-14 يوم عمل",
                ],
            ],
            [
                "question" => [
                    "en" => "Do you offer express shipping?",
                    "ar" => "هل تقدمون خدمة الشحن السريع؟",
                ],
                "answer" => [
                    "en" =>
                        "Yes, we offer express shipping options:\n\n- UAE: Next-day delivery (order before 2 PM)\n- GCC: 2-3 business days\n- International: 3-5 business days\n\nExpress shipping rates are calculated at checkout based on your location and order weight.",
                    "ar" =>
                        "نعم، نقدم خيارات الشحن السريع:\n\n- الإمارات: التوصيل في اليوم التالي (للطلبات قبل 2 مساءً)\n- دول الخليج: 2-3 أيام عمل\n- دولي: 3-5 أيام عمل\n\nيتم احتساب رسوم الشحن السريع عند الدفع بناءً على موقعك ووزن طلبك.",
                ],
            ]
        );
    }

    /**
     * FAQ for returns and refunds
     */
    public function returnFaqs(): static
    {
        return $this->sequence(
            [
                "question" => [
                    "en" => "What is your return policy?",
                    "ar" => "ما هي سياسة الإرجاع الخاصة بكم؟",
                ],
                "answer" => [
                    "en" =>
                        "Our return policy includes:\n\n- 30-day return window\n- Items must be unused and in original packaging\n- Free returns for UAE customers\n- International customers responsible for return shipping\n- Full refund to original payment method\n\nExceptions:\n- Hygiene products (opened)\n- Sale items\n- Custom orders",
                    "ar" =>
                        "تتضمن سياسة الإرجاع لدينا:\n\n- فترة إرجاع 30 يوماً\n- يجب أن تكون المنتجات غير مستخدمة وفي عبواتها الأصلية\n- إرجاع مجاني لعملاء الإمارات\n- العملاء الدوليون مسؤولون عن رسوم إرجاع الشحن\n- استرداد كامل لطريقة الدفع الأصلية\n\nالاستثناءات:\n- منتجات النظافة (المفتوحة)\n- المنتجات المخفضة\n- الطلبات المخصصة",
                ],
            ],
            [
                "question" => [
                    "en" => "How long do refunds take to process?",
                    "ar" => "كم تستغرق عملية استرداد الأموال؟",
                ],
                "answer" => [
                    "en" =>
                        "Refund processing times:\n\n1. Return Receipt: 1-2 business days\n2. Quality Check: 1-2 business days\n3. Refund Initiation: 1 business day\n4. Bank Processing:\n   - Credit Cards: 5-10 business days\n   - Debit Cards: 7-14 business days\n   - Bank Transfer: 3-5 business days",
                    "ar" =>
                        "أوقات معالجة الاسترداد:\n\n1. استلام المرتجع: 1-2 يوم عمل\n2. فحص الجودة: 1-2 يوم عمل\n3. بدء الاسترداد: يوم عمل واحد\n4. معالجة البنك:\n   - بطاقات الائتمان: 5-10 أيام عمل\n   - بطاقات الخصم: 7-14 يوم عمل\n   - التحويل البنكي: 3-5 أيام عمل",
                ],
            ]
        );
    }

    /**
     * Create an active FAQ
     */
    public function active(): static
    {
        return $this->state(
            fn(array $attributes) => [
                "is_active" => true,
            ]
        );
    }

    /**
     * Create an inactive FAQ
     */
    public function inactive(): static
    {
        return $this->state(
            fn(array $attributes) => [
                "is_active" => false,
            ]
        );
    }

    /**
     * Set a specific sort order
     */
    public function sorted(int $order): static
    {
        return $this->state(
            fn(array $attributes) => [
                "sort_order" => $order,
            ]
        );
    }
}
