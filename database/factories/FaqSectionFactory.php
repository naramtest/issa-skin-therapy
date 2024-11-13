<?php

namespace Database\Factories;

use App\Models\FaqSection;
use Illuminate\Database\Eloquent\Factories\Factory;

class FaqSectionFactory extends Factory
{
    protected $model = FaqSection::class;

    public function definition(): array
    {
        return [
            "title" => [
                "en" => fake()->words(2, true),
                "ar" => "قسم " . fake()->words(1, true),
            ],
            "description" => [
                "en" => fake()->paragraph(),
                "ar" => "وصف " . fake()->paragraph(),
            ],
            "sort_order" => fake()->numberBetween(0, 100),
            "is_active" => fake()->boolean(80),
            "is_product_section" => false,
        ];
    }

    public function productSection(): static
    {
        return $this->state(
            fn(array $attributes) => [
                "is_product_section" => true,
                "title" => [
                    "en" => "Product FAQs",
                    "ar" => "الأسئلة الشائعة عن المنتجات",
                ],
                "description" => [
                    "en" => "Common questions about our products",
                    "ar" => "الأسئلة الشائعة حول منتجاتنا",
                ],
                "sort_order" => 0,
            ]
        );
    }

    /**
     * Indicate that the section is active.
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
     * Indicate that the section is inactive.
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
     * Create common FAQ sections like Account, Orders, etc.
     */
    public function common(): static
    {
        return $this->sequence(
            [
                "title" => [
                    "en" => "Account & Profile",
                    "ar" => "الحساب والملف الشخصي",
                ],
                "description" => [
                    "en" =>
                        "Common questions about your account, registration, and profile settings.",
                    "ar" =>
                        "أسئلة شائعة حول حسابك والتسجيل وإعدادات الملف الشخصي.",
                ],
                "sort_order" => 1,
            ],
            [
                "title" => [
                    "en" => "Orders & Shipping",
                    "ar" => "الطلبات والشحن",
                ],
                "description" => [
                    "en" =>
                        "Everything you need to know about orders, shipping, and delivery.",
                    "ar" =>
                        "كل ما تحتاج إلى معرفته عن الطلبات والشحن والتوصيل.",
                ],
                "sort_order" => 2,
            ],
            [
                "title" => [
                    "en" => "Returns & Refunds",
                    "ar" => "الإرجاع واسترداد الأموال",
                ],
                "description" => [
                    "en" =>
                        "Information about our return policy and refund process.",
                    "ar" => "معلومات حول سياسة الإرجاع وعملية استرداد الأموال.",
                ],
                "sort_order" => 3,
            ],
            [
                "title" => [
                    "en" => "Products & Services",
                    "ar" => "المنتجات والخدمات",
                ],
                "description" => [
                    "en" =>
                        "Learn more about our products, services, and how to use them.",
                    "ar" =>
                        "تعرف على المزيد حول منتجاتنا وخدماتنا وكيفية استخدامها.",
                ],
                "sort_order" => 4,
            ]
        );
    }
}
