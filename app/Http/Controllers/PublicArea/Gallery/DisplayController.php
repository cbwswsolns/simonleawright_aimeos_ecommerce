<?php

namespace App\Http\Controllers\PublicArea\Gallery;

use App\Http\Controllers\Controller;
use App\Services\Aimeos\AimeosService;

class DisplayController extends Controller
{
    /**
     * Aimeos service instance
     *
     * @var \App\Services\Aimeos\AimeosService $aimeosService [the Aimeos service instance]
     */
    protected $aimeosService;


    /**
     * Create a new controller instance.
     *
     * @param \App\Services\Aimeos\AimeosService $aimeosService [the Aimeos service instance]
     *
     * @return void
     */
    public function __construct(AimeosService $aimeosService)
    {
        $this->aimeosService = $aimeosService;
    }


    /**
     * Display gallery of images
     *
     * @param string $category [the category name]
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke($category = null)
    {
        $categoryCount = $this->aimeosService->getCategoryCount();

        $images = $this->aimeosService->getProductsByCategory($category);

        return view('public.gallery.display', compact('categoryCount', 'images'));
    }
}
