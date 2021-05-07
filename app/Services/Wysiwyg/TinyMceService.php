<?php

namespace App\Services\Wysiwyg;

use App\Models\Media;
use App\Models\Wysiwyg;
use App\Services\Contracts\WysiwygServiceInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class TinyMceService implements WysiwygServiceInterface
{
    /**
     * Create a new service instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    /**
     * Attach Media to Wysiwyg
     *
     * @param integer                       $wysiwygId [id of wysiwyg model to attach to]
     * @param \Illuminate\Http\UploadedFile $file      [the media file to attach]
     *
     * @return void
     */
    public function attachMediaToWysiwyg($wysiwygId, UploadedFile $file)
    {
        $path = (resolve('App\Services\Contracts\FileManagerInterface'))->store($file, 'media');

        $wysiwyg = (new Wysiwyg())->where('id', $wysiwygId)->first();

        // Attach the associated database record for referencing the media item
        $media = $wysiwyg->attachMediaRecord(
            new Media(['filename' => $path, 'name' => $file->getClientOriginalName()])
        );

        return ['path' => $path, 'model' => $media];
    }


    /**
     * Sync Media to reflect existing TinyMce body text
     *
     * @param string $body     [the Wysiwyg body text]
     * @param array  $mediaIds [array of ids of all media records created during Wysiwyg edit]
     *
     * @return void
     */
    public function syncMedia($body, $mediaIds)
    {
        $filteredMediaIds = [];

        if ($mediaIds) {
            // Get all media records created as part of Wysiwyg editing
            $mediaItems = (new Media())->findMany($mediaIds);

            // Filter out media records and associated storage not in the final WYSIWYG html page

            foreach ($mediaItems as $mediaItem) {
                if (!Str::contains($body, $mediaItem->filename)) {
                    // Associated stored file will be deleted via a model "deleting" event listener.
                    $mediaItem->delete();

                    continue;
                }

                $filteredMediaIds[] = $mediaItem->id;
            };
        }

        return $filteredMediaIds;
    }
}
