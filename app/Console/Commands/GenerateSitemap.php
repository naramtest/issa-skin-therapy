<?php

namespace App\Console\Commands;

use App\Models\Bundle;
use App\Models\Category;
use App\Models\Post;
use App\Models\Product;
use App\Models\ProductType;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = "sitemap:generate";
    protected $description = "Generate the sitemap";

    /**
     * Static page last update dates - update these when you modify content
     * @var array
     */
    protected array $contentUpdateDates = [
        "home" => "2024-03-10",
        "about" => "2024-03-10",
        "contact" => "2024-03-10",
        "faq" => "2024-03-10",
        "terms" => "2024-03-10",
        "privacy" => "2024-03-10",
        "refund" => "2024-03-10",
    ];

    public function handle()
    {
        $this->info("Generating sitemap...");

        $sitemap = Sitemap::create();

        // Static pages with more realistic last modified dates
        // Home page - updated more frequently
        $sitemap->add(
            Url::create("/")
                ->setLastModificationDate(
                    $this->getLastContentUpdateDate("home")
                )
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(1.0)
        );

        // Shop page - product listings change regularly
        $sitemap->add(
            Url::create("/shop")
                ->setLastModificationDate($this->getLatestProductDate())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.9)
        );

        // About page - rarely changes
        $sitemap->add(
            Url::create("/about")
                ->setLastModificationDate(
                    $this->getLastContentUpdateDate("about")
                )
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                ->setPriority(0.7)
        );

        // Contact page - rarely changes
        $sitemap->add(
            Url::create("/contact-us")
                ->setLastModificationDate(
                    $this->getLastContentUpdateDate("contact")
                )
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                ->setPriority(0.7)
        );

        // FAQ page - changes occasionally
        $sitemap->add(
            Url::create("/faq")
                ->setLastModificationDate(
                    $this->getLastContentUpdateDate("faq")
                )
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                ->setPriority(0.7)
        );

        // Legal pages - rarely change
        $sitemap->add(
            Url::create("/terms-conditions")
                ->setLastModificationDate(
                    $this->getLastContentUpdateDate("terms")
                )
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                ->setPriority(0.5)
        );

        $sitemap->add(
            Url::create("/privacy-policy")
                ->setLastModificationDate(
                    $this->getLastContentUpdateDate("privacy")
                )
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                ->setPriority(0.5)
        );

        $sitemap->add(
            Url::create("/refund_returns")
                ->setLastModificationDate(
                    $this->getLastContentUpdateDate("refund")
                )
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
                ->setPriority(0.5)
        );

        // Blog listing page - updated with new posts
        $sitemap->add(
            Url::create("/blog")
                ->setLastModificationDate($this->getLatestPostDate())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.8)
        );

        // Collections page - updated when new collections are added
        $sitemap->add(
            Url::create("/collections-page")
                ->setLastModificationDate($this->getLatestBundleDate())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.8)
        );

        // Add products
        $this->info("Adding products to sitemap...");
        Product::select(["slug", "updated_at", "published_at"])
            ->published()
            ->chunk(100, function ($products) use ($sitemap) {
                foreach ($products as $product) {
                    $sitemap->add(
                        Url::create("/product/{$product->slug}")
                            ->setLastModificationDate($product->updated_at)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                            ->setPriority(0.8)
                    );
                }
            });

        // Add bundles/collections
        $this->info("Adding bundles to sitemap...");
        Bundle::select(["slug", "updated_at", "published_at"])
            ->published()
            ->chunk(100, function ($bundles) use ($sitemap) {
                foreach ($bundles as $bundle) {
                    $sitemap->add(
                        Url::create("/collection/{$bundle->slug}")
                            ->setLastModificationDate($bundle->updated_at)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                            ->setPriority(0.8)
                    );
                }
            });

        // Add blog posts
        $this->info("Adding blog posts to sitemap...");
        Post::select(["slug", "updated_at", "published_at"])
            ->published()
            ->chunk(100, function ($posts) use ($sitemap) {
                foreach ($posts as $post) {
                    $sitemap->add(
                        Url::create("/post/{$post->slug}")
                            ->setLastModificationDate($post->updated_at)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                            ->setPriority(0.6)
                    );
                }
            });

        // Add categories
        $this->info("Adding categories to sitemap...");
        Category::select(["slug", "updated_at", "is_visible"])
            ->where("is_visible", true)
            ->chunk(100, function ($categories) use ($sitemap) {
                foreach ($categories as $category) {
                    $sitemap->add(
                        Url::create("/product-category/{$category->slug}")
                            ->setLastModificationDate($category->updated_at)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                            ->setPriority(0.7)
                    );
                }
            });

        // Add product types
        $this->info("Adding product types to sitemap...");
        ProductType::select(["slug", "updated_at"])->chunk(100, function (
            $types
        ) use ($sitemap) {
            foreach ($types as $type) {
                $sitemap->add(
                    Url::create("/product-category/{$type->slug}")
                        ->setLastModificationDate($type->updated_at)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setPriority(0.7)
                );
            }
        });

        // Write sitemap
        $sitemap->writeToFile(public_path("sitemap.xml"));

        $this->info("Sitemap generated successfully!");
    }

    /**
     * Get the last content update date for a static page
     *
     * @param string $page
     * @return \Carbon\Carbon
     */
    protected function getLastContentUpdateDate(string $page): Carbon
    {
        return isset($this->contentUpdateDates[$page])
            ? Carbon::parse($this->contentUpdateDates[$page])
            : Carbon::now()->subMonth();
    }

    /**
     * Get the date of the latest published product
     *
     * @return \Carbon\Carbon
     */
    protected function getLatestProductDate(): Carbon
    {
        $latestProduct = Product::published()->latest("updated_at")->first();
        return $latestProduct
            ? $latestProduct->updated_at
            : Carbon::now()->subWeek();
    }

    /**
     * Get the date of the latest published post
     *
     * @return \Carbon\Carbon
     */
    protected function getLatestPostDate(): Carbon
    {
        $latestPost = Post::published()->latest("published_at")->first();
        return $latestPost
            ? $latestPost->published_at
            : Carbon::now()->subWeek();
    }

    /**
     * Get the date of the latest published bundle
     *
     * @return \Carbon\Carbon
     */
    protected function getLatestBundleDate(): Carbon
    {
        $latestBundle = Bundle::published()->latest("updated_at")->first();
        return $latestBundle
            ? $latestBundle->updated_at
            : Carbon::now()->subWeek();
    }
}
