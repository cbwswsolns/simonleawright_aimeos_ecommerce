<?php

namespace App\Http\ViewComposers;

use Aimeos\Shop\Facades\Shop;
use Illuminate\View\View;

class ShopNavViewComposer
{
    /**
     * Create a new composer instance
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        foreach (config('shop.page.shop-nav') as $name) {
            $params['aiheader'][$name] = Shop::get($name)->getHeader();
            $params['aibody'][$name] = Shop::get($name)->getBody();
        }

        $view->with('params', $params);
    }
}
