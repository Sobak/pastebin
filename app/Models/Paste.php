<?php

namespace App\Models;

use App\Services\Slugger;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $slug
 */
class Paste extends Model
{
    protected $appends = [
        'slug',
    ];

    public function getSlugAttribute($value)
    {
        return (new Slugger())->encode($this->attributes['id']);
    }
}
