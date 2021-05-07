<?php

namespace App\Models;

use App\Models\Traits\HasMedia;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasMedia;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'media';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $fillable = ['page_id', 'filename', 'name'];


    // MODEL METHODS

    /**
     * Get the file associated with this model
     *
     * @return array
     */
    public function getFile()
    {
        return [$this->filename];
    }


    // MODEL RELATIONSHIPS

    /**
     * Set up BelongsTo relationship
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function page()
    {
        return $this->belongsTo(Page::class);
    }


    /**
     * Get the parent mediaable model (page or wysiwyg).
     */
    public function mediaable()
    {
        return $this->morphTo();
    }
}
