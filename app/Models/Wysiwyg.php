<?php

namespace App\Models;

use App\Models\Media;
use Illuminate\Database\Eloquent\Model;
use ShiftOneLabs\LaravelCascadeDeletes\CascadesDeletes;

class Wysiwyg extends Model
{
    use CascadesDeletes;

    /* Note: as table name is "wysiwygs", there is no need to specify a table property for this model as "wysiwygs" is the default name */

    protected $cascadeDeletes = ['media'];

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];


    // MODEL METHODS

    /**
     * Attach a media item to the wysiwyg
     *
     * @param \App\Models\Media $media [the media model instance]
     *
     * @return bool
     */
    public function attachMediaRecord(Media $media)
    {
        return $this->media()->save($media);
    }


    // MODEL RELATIONSHIPS

    /**
     * Set up BelongsTo relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function page()
    {
        return $this->belongsTo(Page::class);
    }


    /**
     * Get all of the wysiwyg media
     *
     * @return Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function media()
    {
        return $this->morphMany(Media::class, 'mediaable');
    }
}
