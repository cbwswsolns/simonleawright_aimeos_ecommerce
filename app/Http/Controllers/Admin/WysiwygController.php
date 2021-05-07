<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\WysiwygMediaUploadFormRequest;
use App\Services\Contracts\WysiwygServiceInterface;

class WysiwygController extends Controller
{
    /**
     * Wysiwyg service instance
     *
     * @var \App\Services\Contracts\WysiwygServiceInterface $wysiwygService [the Wysiwyg service instance]
     */
    protected $wysiwygService;


    /**
     * Create a new controller instance.
     *
     * @param \App\Services\Contracts\WysiwygServiceInterface $wysiwygService [the Wysiwyg service instance]
     *
     * @return void
     */
    public function __construct(WysiwygServiceInterface $wysiwygService)
    {
        $this->middleware('auth');

        $this->wysiwygService = $wysiwygService;
    }


    /**
     * AJAX request - WYSIWYG media upload
     *
     * @param \App\Http\Requests\WysiwygMediaUploadFormRequest $request [the current request instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function ajaxWysiwygMediaUpload(WysiwygMediaUploadFormRequest $request)
    {
        $mediaData = $this->wysiwygService->attachMediaToWysiwyg($request['wysiwygid'], $request->validated()['file']);

        return \Response::json(['location' => '/storage/' . $mediaData['path'], 'id' => $mediaData['model']->id]);
    }
}
