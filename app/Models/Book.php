<?php

namespace App\Models;

use App\Indexes\BookIndexConfigurator;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use ScoutElastic\Searchable;

final class Book extends Model
{
    use Searchable;

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'publisher',
        'type',
        'category',
        'authors',
        'writers',
        'characterDesigners',
        'illustrators',
        'translators',
        'cartoonists',
        'tags',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['published_at'];

    /**
     * Elasticsearch index configurator.
     *
     * @var string
     */
    protected $indexConfigurator = BookIndexConfigurator::class;

    /**
     * Elasticsearch mapping.
     *
     * @var array
     */
    protected $mapping = [
        'properties' => [
            'name' => [
                'type' => 'text',
            ],

            'slogan' => [
                'type' => 'text',
            ],

            'description' => [
                'type' => 'text',
            ],

            'price' => [
                'type' => 'long',
            ],

            'pages' => [
                'type' => 'long',
            ],

            'publisher' => [
                'type' => 'keyword',
            ],

            'type' => [
                'type' => 'keyword',
            ],

            'category' => [
                'type' => 'keyword',
            ],

            'authors' => [
                'type' => 'keyword',
            ],

            'writers' => [
                'type' => 'keyword',
            ],

            'character_designers' => [
                'type' => 'keyword',
            ],

            'illustrators' => [
                'type' => 'keyword',
            ],

            'translators' => [
                'type' => 'keyword',
            ],

            'cartoonists' => [
                'type' => 'keyword',
            ],

            'tags' => [
                'type' => 'keyword',
            ],

            'published_at' => [
                'type' => 'date',
            ],
        ]
    ];

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->getKey(),
            'name' => $this->name,
            'slogan' => $this->slogan,
            'description' => $this->description,
            'price' => $this->price,
            'pages' => $this->pages,
            'publisher' => $this->publisher->name,
            'type' => $this->type->name,
            'category' => $this->category->name,
            'authors' => $this->authors->pluck('name')->toArray(),
            'writers' => $this->writers->pluck('name')->toArray(),
            'character_designers' => $this->characterDesigners->pluck('name')->toArray(),
            'illustrators' => $this->illustrators->pluck('name')->toArray(),
            'translators' => $this->translators->pluck('name')->toArray(),
            'cartoonists' => $this->cartoonists->pluck('name')->toArray(),
            'tags' => $this->tags->pluck('name')->toArray(),
            'published_at' => $this->published_at->toISOString(),
        ];
    }

    /**
     * Get book type.
     *
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    /**
     * Get book category.
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get book series.
     *
     * @return BelongsTo
     */
    public function series(): BelongsTo
    {
        return $this->belongsTo(Series::class);
    }

    /**
     * Get book publisher.
     *
     * @return BelongsTo
     */
    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class);
    }

    /**
     * Get book author.
     *
     * @return BelongsToMany
     */
    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Creator::class, 'book_author');
    }

    /**
     * Get book writer.
     *
     * @return BelongsToMany
     */
    public function writers(): BelongsToMany
    {
        return $this->belongsToMany(Creator::class, 'book_writer');
    }

    /**
     * Get book character designer.
     *
     * @return BelongsToMany
     */
    public function characterDesigners(): BelongsToMany
    {
        return $this->belongsToMany(Creator::class, 'book_character_designer');
    }

    /**
     * Get book illustrator.
     *
     * @return BelongsToMany
     */
    public function illustrators(): BelongsToMany
    {
        return $this->belongsToMany(Creator::class, 'book_illustrator');
    }

    /**
     * Get book translator.
     *
     * @return BelongsToMany
     */
    public function translators(): BelongsToMany
    {
        return $this->belongsToMany(Creator::class, 'book_translator');
    }

    /**
     * Get book cartoonists.
     *
     * @return BelongsToMany
     */
    public function cartoonists(): BelongsToMany
    {
        return $this->belongsToMany(Creator::class, 'book_cartoonist');
    }

    /**
     * Get book tags.
     *
     * @return BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Get BookWalker product link.
     *
     * @return string
     */
    public function getLinkAttribute(): string
    {
        return sprintf('https://www.bookwalker.com.tw/product/%d', $this->bookwalker_id);
    }
}
