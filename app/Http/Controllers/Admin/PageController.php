<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PageFormRequest;
use App\Models\Page;
use App\Services\Page\PageService;

class PageController extends Controller
{
    /**
     * Page service instance
     *
     * @var \App\Services\Page\PageService;
     */
    protected $pageService;


    /**
     * Create a new controller instance.
     *
     * @param \App\Services\Page\PageService $pageService [the page service instance]
     *
     * @return void
     */
    public function __construct(PageService $pageService)
    {
        $this->middleware('auth');

        $this->pageService = $pageService;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Page::class);

        $page = $this->pageService->createDraft();

        return view('secure.admin.page.create', compact('page'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Page $page [the page model instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {
        $this->authorize('update', $page);

        return view('secure.admin.page.edit', compact('page'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\PageFormRequest $request [the current request instance]
     * @param \App\Models\Page                   $page    [the page model instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function update(PageFormRequest $request, Page $page)
    {
        $this->authorize('update', $page);

        $this->pageService->update($request->all(), $page);

        return redirect()->route('aimeos_shop_admin');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Page $page [the page model instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page)
    {
        $this->authorize('delete', $page);

        $this->pageService->delete($page);

        return redirect()->route('aimeos_shop_admin');
    }
}
