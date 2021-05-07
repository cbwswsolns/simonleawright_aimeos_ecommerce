<?php

namespace App\Http\Controllers\PublicArea\Sitemap;

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
     * Display the sitemap page
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        $categories = $this->aimeosService->getAllCategories();

        $products = $this->aimeosService->getAllProducts();

        return view('public.sitemap.display', compact('categories', 'products'));
    }
}
