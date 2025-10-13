<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class SpotCategory extends Model
{
    use HasJsonRelationships, SoftDeletes;

    protected $fillable = [
        'name', 'description',
    ];
}
