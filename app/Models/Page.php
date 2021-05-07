<?php

namespace App\Models;

use App\Models\Media;
use App\Models\Wysiwyg;
use Illuminate\Database\Eloquent\Model;
use ShiftOneLabs\LaravelCascadeDeletes\CascadesDeletes;

class Page extends Model
{
    use CascadesDeletes;

    protected $cascadeDeletes = ['wysiwyg', 'media'];

    /* Note: as table name is "pages", there is no need to specify a table property for this model as "pages" is the default name */

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];


    // MODEL METHODS

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'name';
    }


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


    /**
     * Create wysiwyg
     *
     * @param string $body [the body]
     *
     * @return \App\Models\Wysiwyg
     */
    public function createWysiwyg($body)
    {
        return $this->wysiwyg()->firstOrCreate(['body' => $body]);
    }


    /**
     * Update wysiwyg
     *
     * @param string $body [the body]
     *
     * @return \App\Models\Wysiwyg
     */
    public function updateWysiwyg($body)
    {
        $this->wysiwyg()->update(['body' => $body]);
    }


    // MODEL RELATIONSHIPS

    /**
     * Set up BelongsTo relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    /**
     * Get all of the page media
     *
     * @return Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function media()
    {
        return $this->morphMany(Media::class, 'mediaable');
    }


    /**
     * Set up HasOne relationship
     *
     * @return Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function wysiwyg()
    {
        return $this->hasOne(Wysiwyg::class);
    }
}
