<template>

    <div>

        <input type="hidden" id="media_ids" name="media_ids" :value="myMediaIds">

        <div class="form-group">
            <label for="body">Body:</label>

            <tinymce v-if="myBodyValidationError" name="body" v-model="body" :class="'form-control is-invalid'" api-key="v1gkuzjx6m21my5hghsd21sl2uaqkfopmmacs5pzktyrk20j" :initial-value="myBodyText" :plugins="myPlugins" :toolbar ="myToolbar1" :init="myInit"></tinymce>   

            <tinymce v-else name="body" v-model="body" :class="'form-control'" api-key="v1gkuzjx6m21my5hghsd21sl2uaqkfopmmacs5pzktyrk20j" :initial-value="myBodyText" :plugins="myPlugins" :toolbar ="myToolbar1" :init="myInit"></tinymce>   

            <span v-if="myBodyValidationError" :class="'invalid-feedback'">

                <strong>{{ myBodyValidationErrorMessage }} </strong>

            </span>

        </div>

    </div>

</template>


<script>

import Editor from '@tinymce/tinymce-vue';


export default {

    components: {
        'tinymce': Editor // <- Important part
    },

    props: ['parentFormHasPassedValidation', 'oldBodyText', 'bodyValidationError', 'bodyValidationErrorMessage', 'wysiwygId', 'mediaIds'],

    data: function() { 
        var that = this;

        return { 

            state: 'init',

            lastState: this.state,

            myParentFormHasPassedValidation: this.parentFormHasPassedValidation,

            body: '',

            myMediaIds: this.mediaIds,

            myBodyText: this.oldBodyText,

            myBodyValidationError: this.bodyValidationError,

            myBodyValidationErrorMessage: this.bodyValidationErrorMessage,

            myToolbar1: 'undo redo | bold italic underline forecolor backcolor | alignleft aligncenter alignright alignjustify | hr bullist numlist outdent indent | link image table | code preview',

            myPlugins: "link image code preview imagetools table lists hr wordcount",
           
            myInit: {
              
                images_dataimg_filter: function(img) {
                    return false;
                    return img.hasAttribute('internal-blob');
                },
                convert_urls : false,
                height:500,
                width: '100%',
                image_title: true,
                automatic_uploads: true, 
                relative_urls: true,
                image_class_list: [
                    {title: 'Image Fluid', value: 'img-fluid'},
                ],
                table_cell_class_list: [
                    {title: 'x', value: 'x'},
                    {title: 'y', value: 'y'},
                ],

                // override default upload handler to simulate successful upload
                images_upload_handler: function (blobInfo, success, failure,folderName) {
                    var xhr, formData;

                    // CBW - Add (to TinyMce legacy code): fire image upload started event
                    that.uploadstart();
                    // CBW - End Add

                    xhr = new XMLHttpRequest();
                    xhr.withCredentials = false;
                  

                    // CBW - Edit (to TinyMce legacy code): set AJAX upload path and set csrf in AJAX request header.  Also ensure that Symfony request class can identify the XHR request by setting X-Requested-With in request header

                    // xhr.open('POST', 'postAcceptor.php');

                    xhr.open('POST', '/admin/pages/wysiwyg/media/upload/ajax');
                    var token = document.head.querySelector("[name=csrf-token]").content;
                    xhr.setRequestHeader("X-CSRF-Token", token);
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

                    // CBW - End Edit
                  
                    xhr.onload = function() {
                        var json;

                        // CBW - Edit (to TinyMce legacy code): relocate JSON.parse statement to allow populatation of body (image) validation error field upon a XHR response code other than 200

                        /*
                        if (xhr.status != 200) {
                            failure('HTTP Error: ' + xhr.status);
                            return;
                        }

                        json = JSON.parse(xhr.responseText);
                        */

                        json = JSON.parse(xhr.responseText);

                        if (xhr.status != 200) {
                            that.myBodyValidationError = true;
                            that.myBodyValidationErrorMessage = json.errors.file[0];
                            failure('HTTP Error: ' + xhr.status);
                            return;
                        }
                   
                        // CBW - End Edit 

                        if (!json || typeof json.location != 'string') {
                            failure('Invalid JSON: ' + xhr.responseText);
                            return;
                        }


                        // CBW - Add (to TinyMce legacy code): fire image upload completed event
                        that.uploadcomplete(json.id);
                        // CBW - End Add

                        success(json.location);
                    };
                 
                    formData = new FormData();
                    formData.append('file', blobInfo.blob(), blobInfo.filename());
                    // CBW - Add (to TinyMce legacy code): Page id associated with editor
                    formData.append('wysiwygid', that.wysiwygId);
                    // CBW - End Add
              
                    xhr.send(formData);                   
                },

                /*
                URL of our upload handler (for more details check: https://www.tiny.cloud/docs/configure/file-image-upload/#images_upload_url)
                images_upload_url: 'postAcceptor.php',
                here we add custom filepicker only to Image dialog
                */
                file_picker_types: 'image',
                /* and here's our custom image picker*/
                file_picker_callback: function (cb, value, meta) {
                    var input = document.createElement('input');

                    input.setAttribute('type', 'file');

                    input.setAttribute('accept', 'image/*');

                    /*
                      Note: In modern browsers input[type="file"] is functional without
                      even adding it to the DOM, but that might not be the case in some older
                      or quirky browsers like IE, so you might want to add it to the DOM
                      just in case, and visually hide it. And do not forget do remove it
                      once you do not need it anymore.
                    */

                    input.onchange = function () {
                      var file = this.files[0];

                      var reader = new FileReader();

                      reader.onload = function () {
                        /*
                          Note: Now we need to register the blob in TinyMCEs image blob
                          registry. In the next release this part hopefully won't be
                          necessary, as we are looking to handle it internally.
                        */
                        var id = 'blobid' + (new Date()).getTime();

                        var blobCache =  tinymce.activeEditor.editorUpload.blobCache;

                        var base64 = reader.result.split(',')[1];

                        var blobInfo = blobCache.create(id, file, base64);

                        blobCache.add(blobInfo);

                        /* call the callback and populate the Title field with the file name */
                        cb(blobInfo.blobUri(), { title: file.name });
                      };

                      reader.readAsDataURL(file);

                    };

                    input.click();
                }
            } 
        }
    },
    mounted() {   
        // Form just been submitted?
        if (localStorage.lastState && (localStorage.lastState == 'submitted')) {
            // Passed validation?
            if (this.myParentFormHasPassedValidation) {
                // Intialise to prop value for media ids
                this.myMediaIds = this.mediaIds;                    
            }
            else
            // Failed Validation
            {
                if (localStorage.myMediaIds) {
                    // restore media ids associated with the "old", submitted body data
                    this.myMediaIds = localStorage.myMediaIds;
                }
            }
        }

        this.state = 'drafting';

        localStorage.lastState = this.state;
    },
    methods:
    {
        uploadstart () {
            this.state = 'uploading';

            this.$emit('preventsubmit', true);
        },

        uploadcomplete (id) {
            this.state = 'drafting';

            this.myMediaIds = this.myMediaIds + id + ',';

            localStorage.myMediaIds = this.myMediaIds;

            this.$emit('preventsubmit', false);
        },

        parentFormSubmitted() {
            this.state = 'submitted';
            localStorage.lastState = this.state;

            localStorage.myMediaIds = this.myMediaIds;
        },

        resetlocalstorage() {
            localStorage.myMediaIds = '';
        },
    },
}

</script>
