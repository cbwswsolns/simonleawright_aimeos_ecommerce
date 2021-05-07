<?php

namespace App\Services\Page;

use App\Helpers\HtmlHelper;
use App\Models\Media;
use App\Models\Page;
use App\Services\Contracts\WysiwygServiceInterface;
use Illuminate\Http\UploadedFile;

class PageService
{
    /**
     * Wysiwyg service instance
     *
     * @var \App\Services\Contracts\WysiwygServiceInterface $wysiwygService [the Wysiwyg service instance]
     */
    protected $wysiwygService;


    /**
     * Create a new service instance.
     *
     * @param \App\Services\Contracts\WysiwygServiceInterface $wysiwygService [the Wysiwyg service instance]
     *
     * @return void
     */
    public function __construct(WysiwygServiceInterface $wysiwygService)
    {
        $this->wysiwygService = $wysiwygService;
    }


    /**
     * Create Draft method
     *
     * @return \App\Models\Page
     */
    public function createDraft()
    {
        $page = auth()->user()->createDraftPage(['name' => 'temp', 'state' => 'draft']);

        $page->createWysiwyg('');

        session()->flash('status', 'A draft page has now been created!');

        return $page;
    }


    /**
     * Update page method
     *
     * @param array            $data [the data to use to update the given page]
     * @param \App\Models\Page $page [the page model instance to update]
     *
     * @return \App\Models\Page
     */
    public function update(array $data, Page $page)
    {
        if ($page) {
            $page->update(['name' => $data['name'], 'title' => $data['title'], 'metadescription' => $data['metadescription'], 'state' => 'active']);

            // Post validation - purify WYSIWYG HTML
            $body = (new HtmlHelper())->purifyHTML($data['body']);

            $page->updateWysiwyg($body);

            $this->wysiwygService->syncMedia($body, explode(',', rtrim($data['media_ids'], ",")));

            $videoData = null;
            if (array_key_exists('video', $data)) {
                $videoData = $this->attachMedia($page->id, $data['video'], 'video');
            }
        }

        session()->flash('status', 'Page has now been updated!');

        return $page;
    }


    /**
     * Delete method
     *
     * @param \App\Models\Page $page [the page model to delete]
     *
     * @return void
     */
    public function delete(Page $page)
    {
        /* Associated related/child records will be deleted (via "on cascade" implementation)
           Associated stored files will be deleted via a model "deleting" event listener. */
        $page->delete();

        session()->flash('status', 'Page deleted!');
    }


    /**
     * Attach media
     *
     * @param integer                       $pageId   [id of page model to attach to]
     * @param \Illuminate\Http\UploadedFile $file     [the media file to attach]
     *
     * @return void
     */
    public function attachMedia($pageId, UploadedFile $file)
    {
        $path = (resolve('App\Services\Contracts\FileManagerInterface'))->store($file, 'media');

        $page = (new Page())->where('id', $pageId)->first();

        // Attach the associated database record for referencing the media item
        $media = $page->attachMediaRecord(
            new Media(['filename' => $path, 'name' => $file->getClientOriginalName()])
        );

        return ['path' => $path, 'model' => $media];
    }
}
