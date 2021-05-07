<?php

namespace App\Http\ViewComposers\Page;

use App\Models\Page;
use Illuminate\View\View;

class PageComposer
{
    /**
     * The page model instance
     *
     * @var \App\Models\Page
     */
    protected $page;


    /**
     * Create a new composer instance
     *
     * @param \App\Models\Page $page [the page model instance]
     *
     * @return void
     */
    public function __construct(Page $page)
    {
        // Dependencies automatically resolved by service container...
        $this->page = $page;
    }


    /**
     * Bind data to the view.
     *
     * @param View $view [the view instance]
     *
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('pages', $this->page->get());
    }
}
