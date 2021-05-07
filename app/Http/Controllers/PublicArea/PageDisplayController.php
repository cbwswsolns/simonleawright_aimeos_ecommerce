<?php

namespace App\Http\Controllers\PublicArea;

use App\Http\Controllers\Controller;
use App\Models\Page;

class PageDisplayController extends Controller
{
    /**
     * Display the default/custom page
     *
     * @param \App\Models\Page $page [the page model instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Page $page)
    {
        if (!$page->exists) {
            $page = $page->where('name', config('page.homepage'))->first();
        }

        return view('public.page.display', compact('page'));
    }
}
