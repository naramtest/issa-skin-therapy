<?php

namespace App\Services\Utils;

class ArabicTransliterationService
{
    protected array $arabicToEnglish = [
        // Arabic letters to English
        "ا" => "a",
        "أ" => "a",
        "إ" => "a",
        "آ" => "aa",
        "ب" => "b",
        "ت" => "t",
        "ث" => "th",
        "ج" => "j",
        "ح" => "h",
        "خ" => "kh",
        "د" => "d",
        "ذ" => "dh",
        "ر" => "r",
        "ز" => "z",
        "س" => "s",
        "ش" => "sh",
        "ص" => "s",
        "ض" => "d",
        "ط" => "t",
        "ظ" => "z",
        "ع" => "a",
        "غ" => "gh",
        "ف" => "f",
        "ق" => "q",
        "ك" => "k",
        "ل" => "l",
        "م" => "m",
        "ن" => "n",
        "ه" => "h",
        "و" => "w",
        "ي" => "y",
        "ى" => "a",
        "ة" => "h",
        "ء" => "a",

        // Arabic numerals
        "٠" => "0",
        "١" => "1",
        "٢" => "2",
        "٣" => "3",
        "٤" => "4",
        "٥" => "5",
        "٦" => "6",
        "٧" => "7",
        "٨" => "8",
        "٩" => "9",
    ];

    protected array $commonWords = [
        // Common Arabic names
        "محمد" => "Mohammed",
        "أحمد" => "Ahmed",
        "علي" => "Ali",
        "عبدالله" => "Abdullah",
        "عبدالرحمن" => "Abdulrahman",
        "خالد" => "Khalid",
        "سعد" => "Saad",
        "فهد" => "Fahd",
        "عبدالعزيز" => "Abdulaziz",
        "يوسف" => "Youssef",
        "إبراهيم" => "Ibrahim",
        "عمر" => "Omar",
        "حسن" => "Hassan",
        "حسين" => "Hussein",
        "صالح" => "Saleh",
        "فاطمة" => "Fatima",
        "عائشة" => "Aisha",
        "خديجة" => "Khadija",
        "زينب" => "Zainab",
        "مريم" => "Mariam",
        "نورا" => "Nora",
        "سارة" => "Sarah",
        "رنا" => "Rana",
        "دانا" => "Dana",
        "نارام" => "Naram",
        "ريم" => "Reem",
        "لينا" => "Lina",
        "نوال" => "Nawal",
        "هند" => "Hind",
        "أمل" => "Amal",

        // Gulf/Middle East cities
        "الرياض" => "Riyadh",
        "جدة" => "Jeddah",
        "الدمام" => "Dammam",
        "مكة" => "Mecca",
        "المدينة" => "Medina",
        "الطائف" => "Taif",
        "الأحساء" => "Al Ahsa",
        "الجبيل" => "Jubail",
        "الخبر" => "Khobar",
        "تبوك" => "Tabuk",
        "أبها" => "Abha",
        "نجران" => "Najran",
        "الكويت" => "Kuwait",
        "الجهراء" => "Jahra",
        "الأحمدي" => "Ahmadi",
        "حولي" => "Hawalli",
        "الفروانية" => "Farwaniya",
        "مبارك الكبير" => "Mubarak Al Kabeer",
        "دبي" => "Dubai",
        "أبو ظبي" => "Abu Dhabi",
        "الشارقة" => "Sharjah",
        "عجمان" => "Ajman",
        "رأس الخيمة" => "Ras Al Khaimah",
        "الفجيرة" => "Fujairah",
        "أم القيوين" => "Umm Al Quwain",
        "العين" => "Al Ain",
        "الدوحة" => "Doha",
        "الريان" => "Al Rayyan",
        "الوكرة" => "Al Wakrah",
        "المنامة" => "Manama",
        "المحرق" => "Muharraq",
        "الرفاع" => "Riffa",
        "مدينة عيسى" => "Isa Town",
        "بيروت" => "Beirut",
        "طرابلس" => "Tripoli",
        "صيدا" => "Sidon",
        "صور" => "Tyre",
        "زحلة" => "Zahle",
        "عمان" => "Amman",
        "الزرقاء" => "Zarqa",
        "إربد" => "Irbid",
        "الكرك" => "Karak",
        "بغداد" => "Baghdad",
        "البصرة" => "Basra",
        "الموصل" => "Mosul",
        "أربيل" => "Erbil",
        "القاهرة" => "Cairo",
        "الإسكندرية" => "Alexandria",
        "الجيزة" => "Giza",
        "الأقصر" => "Luxor",
        "أسوان" => "Aswan",

        // Common address terms
        "شارع" => "Street",
        "طريق" => "Road",
        "شارع الملك" => "King Street",
        "طريق الملك" => "King Road",
        "حي" => "District",
        "منطقة" => "Area",
        "مبنى" => "Building",
        "شقة" => "Apartment",
        "فيلا" => "Villa",
        "مجمع" => "Complex",
        "مركز" => "Center",
        "برج" => "Tower",
        "مول" => "Mall",
        "مستشفى" => "Hospital",
        "جامعة" => "University",
        "مدرسة" => "School",
        "مسجد" => "Mosque",
        "حديقة" => "Park",
        "سوق" => "Market",
        "بنك" => "Bank",
        "فندق" => "Hotel",
        "مطار" => "Airport",
        "محطة" => "Station",
        "ميناء" => "Port",
        "كورنيش" => "Corniche",
        "واحة" => "Oasis",

        // Directions and locations
        "شمال" => "North",
        "جنوب" => "South",
        "شرق" => "East",
        "غرب" => "West",
        "وسط" => "Center",
        "أمام" => "Front",
        "خلف" => "Behind",
        "بجانب" => "Next to",
        "قريب من" => "Near",
        "بعيد عن" => "Far from",
    ];

    /**
     * Transliterate and format for specific field types
     */
    public function transliterateForField(
        string $text,
        string $fieldType = "default"
    ): string {
        $transliterated = $this->transliterate($text);

        return match ($fieldType) {
            "name" => $this->formatName($transliterated),
            "address" => $this->formatAddress($transliterated),
            "city" => $this->formatCity($transliterated),
            default => $transliterated,
        };
    }

    /**
     * Transliterate Arabic text to English
     */
    public function transliterate(string $text): string
    {
        if (empty($text)) {
            return $text;
        }

        // First, try direct word mapping for common names/places
        $transliterated = $this->translateCommonWords($text);

        // Then transliterate remaining Arabic characters
        $transliterated = $this->transliterateArabicChars($transliterated);

        // Clean up the result
        $transliterated = $this->cleanupText($transliterated);

        return $transliterated;
    }

    /**
     * Translate common Arabic words to their English equivalents
     */
    protected function translateCommonWords(string $text): string
    {
        // Combine both arrays and sort by length (longest first) to avoid partial matches
        $allMappings = array_merge($this->commonWords, $this->arabicToEnglish);
        uksort($allMappings, function ($a, $b) {
            return mb_strlen($b) - mb_strlen($a);
        });

        // Only process multi-character mappings first
        foreach ($allMappings as $arabic => $english) {
            if (mb_strlen($arabic) > 1) {
                $text = str_replace($arabic, $english, $text);
            }
        }

        return $text;
    }

    /**
     * Transliterate individual Arabic characters
     */
    protected function transliterateArabicChars(string $text): string
    {
        $chars = mb_str_split($text);
        $result = "";

        foreach ($chars as $char) {
            if (isset($this->arabicToEnglish[$char])) {
                $result .= $this->arabicToEnglish[$char];
            } elseif ($this->isArabicChar($char)) {
                // Fallback for unmapped Arabic characters
                $result .= "a";
            } else {
                $result .= $char;
            }
        }

        return $result;
    }

    /**
     * Check if a character is Arabic
     */
    protected function isArabicChar(string $char): bool
    {
        if (empty($char)) {
            return false;
        }

        $unicode = mb_ord($char);
        return ($unicode >= 0x0600 && $unicode <= 0x06ff) || // Arabic block
            ($unicode >= 0x0750 && $unicode <= 0x077f) || // Arabic Supplement
            ($unicode >= 0x08a0 && $unicode <= 0x08ff) || // Arabic Extended-A
            ($unicode >= 0xfb50 && $unicode <= 0xfdff) || // Arabic Presentation Forms-A
            ($unicode >= 0xfe70 && $unicode <= 0xfeff); // Arabic Presentation Forms-B
    }

    /**
     * Clean up the transliterated text
     */
    protected function cleanupText(string $text): string
    {
        // Remove multiple spaces
        $text = preg_replace("/\s+/", " ", $text);

        // Remove leading/trailing spaces
        $text = trim($text);

        // Remove any remaining non-ASCII characters except spaces and basic punctuation
        $text = preg_replace('/[^\x20-\x7E]/', "", $text);

        // Capitalize first letter of each word for names
        $text = ucwords(strtolower($text));

        return $text;
    }

    /**
     * Format name fields
     */
    protected function formatName(string $name): string
    {
        $name = ucwords(strtolower(trim($name)));

        // Handle common name patterns
        $name = str_replace(
            [" Al ", " El ", " Bin ", " Ibn "],
            [" al ", " el ", " bin ", " ibn "],
            $name
        );

        return $name;
    }

    /**
     * Format address fields
     */
    protected function formatAddress(string $address): string
    {
        return ucwords(strtolower(trim($address)));
    }

    /**
     * Format city fields
     */
    protected function formatCity(string $city): string
    {
        return ucwords(strtolower(trim($city)));
    }

    /**
     * Add custom mapping for specific words
     */
    public function addCustomMapping(string $arabic, string $english): self
    {
        $this->commonWords[$arabic] = $english;
        return $this;
    }

    /**
     * Add multiple custom mappings
     */
    public function addCustomMappings(array $mappings): self
    {
        $this->commonWords = array_merge($this->commonWords, $mappings);
        return $this;
    }

    /**
     * Get all current mappings
     */
    public function getMappings(): array
    {
        return array_merge($this->commonWords, $this->arabicToEnglish);
    }
}
