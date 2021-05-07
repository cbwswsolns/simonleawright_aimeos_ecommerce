<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /* Note: as table name is "users", there is no need to specify a table property for this model as "users" is the default name */

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    // MODEL METHODS

    /**
     * Create draft page
     *
     * @param array $attributes [page model attributes]
     *
     * @return \App\Models\Page
     */
    public function createDraftPage($attributes)
    {
        $page = $this->pages()->firstOrCreate($attributes);

        return $page;
    }


    // MODEL RELATIONSHIPS

    /**
     * Set up HasMany relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pages()
    {
        return $this->hasMany('\App\Models\Page');
    }
}
