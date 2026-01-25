<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;
use Staudenmeir\EloquentJsonRelations\Relations\BelongsToJson;

class Spot extends Model
{
    use HasJsonRelationships, SoftDeletes;

    protected $fillable = [
        'name', 'tag_ids', 'category_ids', 'environmental_factor_ids', 'distance', 'images', 'urls', 'access',
        'difficulty', 'description', 'rating', 'order',
    ];

    protected function casts(): array
    {
        return [
            'distance' => 'integer',
            'tag_ids' => 'array',
            'category_ids' => 'array',
            'environmental_factor_ids' => 'array',
            'images' => 'json',
            'urls' => 'json',
        ];
    }

    public function tags(): BelongsToJson
    {
        return $this->belongsToJson(SpotTag::class, 'tag_ids');
    }

    public function categories(): BelongsToJson
    {
        return $this->belongsToJson(SpotCategory::class, 'category_ids');
    }

    public function environmentalFactors(): BelongsToJson
    {
        return $this->belongsToJson(EnvironmentalFactor::class, 'environmental_factor_ids');
    }
}
