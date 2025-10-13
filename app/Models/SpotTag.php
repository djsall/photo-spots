<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class SpotTag extends Model
{
    /** @use HasFactory<\Database\Factories\SpotTagFactory> */
    use HasFactory, HasJsonRelationships, SoftDeletes;

    protected $fillable = [
        'name', 'description',
    ];
}
