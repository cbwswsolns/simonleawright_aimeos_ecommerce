<?php

namespace App\Http\Controllers\PublicArea\TermsAndPrivacyAndCancel;

use App\Http\Controllers\Controller;

class DisplayController extends Controller
{
    /**
     * Display the privacy page
     *
     * @return \Illuminate\Http\Response
     */
    public function displayPrivacy()
    {
        return view('public.privacy.display');
    }

    /**
     * Display the terms page
     *
     * @return \Illuminate\Http\Response
     */
    public function displayTerms()
    {
        return view('public.terms.display');
    }

    /**
     * Display the cancel page
     *
     * @return \Illuminate\Http\Response
     */
    public function displayCancel()
    {
        return view('public.cancel.display');
    }
}
