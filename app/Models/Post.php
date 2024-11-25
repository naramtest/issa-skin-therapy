<?php

namespace App\Models;

use App\Enums\ProductStatus;
use App\Traits\HasPostSeo;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Tags\HasTags;
use Spatie\Translatable\HasTranslations;
use Str;

class Post extends Model implements HasMedia
{
    use HasPostSeo;
    use HasSEO;
    use HasTags;
    use HasTranslations;
    use InteractsWithMedia;
    use Prunable, SoftDeletes;

    public array $translatable = [
        "title",
        "body",
        "meta_title",
        "meta_description",
        "excerpt",
    ];
    protected $casts = [
        "status" => ProductStatus::class,
        "published_at" => "datetime",
        "scheduled_at" => "datetime",
        "edited" => "boolean",
    ];
    protected $fillable = [
        "title",
        "body",
        "meta_title",
        "slug",
        "user_id",
        "meta_description",
        "status",
        "published_at",
        "is_featured",
        "excerpt",
        "edited",
        "scheduled_at",
    ];

    protected static function booted(): void
    {
        static::saving(function (Post $post) {
            if (
                $post->status === ProductStatus::PUBLISHED &&
                empty($post->published_at)
            ) {
                $post->published_at = Carbon::now();
            }

            // Clear published_at when status changes to draft
            if (
                $post->status === ProductStatus::DRAFT &&
                $post->getOriginal("status") === ProductStatus::PUBLISHED->value
            ) {
                $post->published_at = null;
            }

            $post->excerpt ??=
                $post->meta_description ??
                Str::substr(strip_tags($post->body) ?? "", 0, 255);
            $post->meta_title ??= Str::substr($post->title, 0, 60);
        });
    }

    public function categories(): morphToMany
    {
        return $this->morphToMany(Category::class, "model", "categorizables");
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion(config("const.media.thumbnail"))
            ->format("webp")
            ->performOnCollections(config("const.media.featured"))
            ->width(400)
            ->height(400)
            ->optimize()
            ->quality(70);
        $this->addMediaConversion(config("const.media.optimized"))
            ->format("webp")
            ->optimize()
            ->quality(85)
            ->withResponsiveImages();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(config("const.media.featured"))->singleFile();

        $this->addMediaCollection(config("const.media.post.body"));
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function scopeVisible($query)
    {
        return $query->where("status", ProductStatus::PUBLISHED);
    }

    public function scopeByDate($query)
    {
        return $query->orderBy("published_at", "DESC");
    }

    public function prunable()
    {
        return static::where("deleted_at", "<=", now()->subMonth());
    }

    public function isScheduled(): bool
    {
        return $this->scheduled_at && $this->scheduled_at->isFuture();
    }

    public function getDynamicSEOData(): SEOData
    {
        return self::getPostSeoData($this);
    }

    protected function pruning(): void
    {
        $this->media()->delete();
    }

    //    TODO: add feed
    //    public function toFeedItem(): FeedItem
    //    {
    //        return FeedItem::create()
    //            ->id($this->id)
    //            ->title($this->title)
    //            ->summary($this->excerpt)
    //            ->updated($this->updated_at)
    //            ->link(route('post.show', ['post' => $this->slug]))
    //            ->authorName($this->author->name)
    //            ->authorEmail('info@alaanplus.com');
    //    }

    // TODO : add Search

    //    public function toSearchableArray(): array
    //    {
    //        return $this->withoutRelations()->toArray();
    //    }
    //
    //    public function shouldBeSearchable(): bool
    //    {
    //        return $this->status == PostStatus::PUBLISHED;
    //    }
}
