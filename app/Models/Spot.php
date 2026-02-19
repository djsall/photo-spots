<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;
use Staudenmeir\EloquentJsonRelations\Relations\BelongsToJson;

/**
 * @property-read ?array $images
 * @property-read Collection<Technique> $techniques
 * @property-read Collection<Category> $categories
 * @property-read Collection<EnvironmentalFactor> $environmentalFactors
 */
class Spot extends Model
{
    use HasJsonRelationships, SoftDeletes;

    protected $fillable = [
        'name', 'technique_ids', 'category_ids', 'environmental_factor_ids', 'user_id', 'distance', 'images', 'urls', 'access',
        'difficulty', 'description', 'order',
    ];

    protected function casts(): array
    {
        return [
            'distance' => 'integer',
            'technique_ids' => 'array',
            'category_ids' => 'array',
            'environmental_factor_ids' => 'array',
            'images' => 'json',
            'urls' => 'json',
        ];
    }

    public function techniques(): BelongsToJson
    {
        return $this->belongsToJson(Technique::class, 'technique_ids');
    }

    public function categories(): BelongsToJson
    {
        return $this->belongsToJson(Category::class, 'category_ids');
    }

    public function environmentalFactors(): BelongsToJson
    {
        return $this->belongsToJson(EnvironmentalFactor::class, 'environmental_factor_ids');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
