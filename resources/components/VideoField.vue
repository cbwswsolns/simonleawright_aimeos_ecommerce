<template>

    <div class="form-group">

        <label>Video File</label>

        <file-preview-form v-on:handleToggle="toggleShowFileUploadButton" :files="myVideoFiles" :ajax-route="myDeleteRoute" :format="'filename'"></file-preview-form>

        <input type="file" v-show="myShowFileUploadButton" class="form-control-file" name="video" id="video" aria-describedby="videoHelp">
        
        <small id="videoHelp" v-show="myShowFileUploadButton" class="form-text text-muted">Please upload a valid video file.</small>

    </div>

</template>

<script>

import FilePreviewForm from './FilePreviewForm.vue'

export default {

    props: ['showFileUploadButton', 'videoFiles', 'deleteRoute'],

    data: function() {

        return {
        	myShowFileUploadButton: this.showFileUploadButton,
        	myVideoFiles: this.videoFiles,
        	myDeleteRoute: this.deleteRoute
        }
    },
    components: {
        FilePreviewForm
    },
    methods: {
        toggleShowFileUploadButton(value) {
           this.myShowFileUploadButton = value;
        },
    },
}

</script>
