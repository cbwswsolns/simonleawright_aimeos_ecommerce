<?php

namespace App\Services\Contracts;

use Illuminate\Http\UploadedFile;

interface WysiwygServiceInterface
{
    /**
     * Attach Media to Wysiwyg (Model)
     *
     * @param integer                       $wysiwygId [id of wysiwyg model to attach to]
     * @param \Illuminate\Http\UploadedFile $file      [the file to store]
     *
     * @return void
     */
    public function attachMediaToWysiwyg($wysiwygId, UploadedFile $file);
}
