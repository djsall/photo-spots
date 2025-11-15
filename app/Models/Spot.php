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
        'name', 'tag_ids', 'category_ids', 'distance', 'images', 'url', 'access', 'difficulty', 'description', 'rating',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'distance' => 'integer',
            'tag_ids' => 'array',
            'category_ids' => 'array',
            'images' => 'array',
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
}
