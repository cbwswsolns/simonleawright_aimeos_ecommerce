<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(
            ['secure.partials.admin.nav.top_menu_left', 'public.layouts.nav.top_menu_left', 'public.layouts.nav.top_menu_no_js'],
            'App\Http\ViewComposers\Page\PageComposer'
        );

        View::composer(
            ['public.layouts.nav.top_menu_left', 'secure.partials.admin.nav.top_menu_left'],
            'App\Http\ViewComposers\CategoryViewComposer'
        );

        View::composer(
            ['public.layouts.nav.top_menu_right'],
            'App\Http\ViewComposers\ShopNavViewComposer'
        );
    }


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
