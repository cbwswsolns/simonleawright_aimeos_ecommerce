<?php

namespace App\Policies;

use App\Models\Page;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PagePolicy
{
    use HandlesAuthorization;


    /**
     * Determine whether the user can view the page.
     *
     * @param \App\Models\User $user [the authenticated user instance]
     * @param \App\Models\Page $page [the page model instance]
     *
     * @return mixed
     */
    public function view(User $user, Page $page)
    {
        return $page->state === 'active';
    }


    /**
     * Determine whether the user can create pages.
     *
     * @param \App\Models\Artists\User $user [the authenticated user instance]
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return ($user->superuser === 1);
    }


    /**
     * Determine whether the user can update the page.
     *
     * @param \App\Models\User $user [the authenticated user instance]
     * @param \App\Models\Page $page [the page model instance]
     *
     * @return mixed
     */
    public function update(User $user, Page $page)
    {
        return ($user->superuser === 1);
    }


    /**
     * Determine whether the user can delete the page.
     *
     * @param \App\Models\User $user [the authenticated user instance]
     * @param \App\Models\Page $page [the page model instance]
     * @return mixed
     */
    public function delete(User $user, Page $page)
    {
        $defaultPages = config('page');

        return (($user->superuser === 1) && !in_array($page->name, $defaultPages));
    }
}
