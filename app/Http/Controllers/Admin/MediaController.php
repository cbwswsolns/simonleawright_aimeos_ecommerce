<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * AJAX request - delete media item
     *
     * @param \Illuminate\Http\Request $request [the current request instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function ajaxDeleteMediaItem(Request $request)
    {
        $mediaItem = (new Media())->where('id', $request->id)->first();

        $mediaItem->delete();

        return response('Deletion Successful', 200);
    }
}
