<?php

namespace App\Http\ViewComposers;

use App\Services\Aimeos\AimeosService;
use Illuminate\View\View;

class CategoryViewComposer
{
    /**
     * Aimeos service instance
     *
     * @var \App\Services\Aimeos\AimeosService $aimeosService [the Aimeos service instance]
     */
    protected $aimeosService;


    /**
     * Create a new composer instance.
     *
     * @param \App\Services\Aimeos\AimeosService $aimeosService [the Aimeos service instance]
     *
     * @return void
     */
    public function __construct(AimeosService $aimeosService)
    {
        // Dependencies automatically resolved by service container...
        $this->aimeosService = $aimeosService;
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
        $categories = $this->aimeosService->getAllCategories();

        $view->with('categories', $categories);
    }
}
