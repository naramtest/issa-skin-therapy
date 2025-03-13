<?php

namespace App\Services\SEO\SchemaServices;

use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use Spatie\SchemaOrg\Schema;

class ContactPageSchemaService extends BaseSchemaService
{
    public function generate(): string
    {
        $this->getInfo();

        // Create ContactPage schema
        $contactPageSchema = Schema::contactPage()
            ->name(getPageTitle(__("store.Contact Us")))
            ->description(
                __(
                    "store.Get in touch with our team for any inquiries or assistance"
                )
            )
            ->url(URL::route("contact.index"))
            ->datePublished(Carbon::parse("2024-06-15"))
            ->dateModified(Carbon::parse("2024-11-20"));

        // Create Organization schema with Contact specific information
        $organizationSchema = $this->createOrganizationSchema();

        // If the organization has multiple contact points, include them
        if (count($this->info->email) > 0 || count($this->info->phone) > 0) {
            $contactPoints = [];

            foreach ($this->info->email as $email) {
                $contactPoints[] = Schema::contactPoint()
                    ->contactType("Customer Service")
                    ->email($email["email"]);
            }

            foreach ($this->info->phone as $phone) {
                $contactPoints[] = Schema::contactPoint()
                    ->contactType("Customer Support")
                    ->telephone($phone["number"]);
            }

            $organizationSchema->contactPoint($contactPoints);
        }

        // Create WebPage schema
        $webPageSchema = $this->createWebPageSchema(
            name: getPageTitle(__("store.Contact Us")),
            description: __(
                "store.Get in touch with our team for any inquiries or assistance"
            ),
            image: asset("storage/test/hero2.webp")
        );

        // Add location data if available
        if ($this->info->address) {
            $locationSchema = Schema::place()
                ->name($this->info->name)
                ->address(
                    Schema::postalAddress()->streetAddress($this->info->address)
                );

            return $this->combineSchemas([
                $organizationSchema,
                $webPageSchema,
                $contactPageSchema,
                $locationSchema,
            ]);
        }

        // Combine schemas
        return $this->combineSchemas([
            $organizationSchema,
            $webPageSchema,
            $contactPageSchema,
        ]);
    }
}
