<!-- Vue component for file view and deletion -->
<template>
    <div class="container-fluid">
        <div v-if="myFormat == 'image'" class="row top-buffer my-handle">
            <div class="col-md-2" v-for="(file,index) in myData">
                <img class="img-fluid" v-bind:src="'/storage/' + file.filename">
                <br>
                <a href="#" @click="deleteItem(file.id,index)">delete</a>
            </div>
        </div>
        <div v-if="myFormat == 'filename'" class="row top-buffer my-handle" v-for="(file,index) in myData">
            <div class="col-md-12">
                <p>{{ file.name }}
                <a href="#" class="stretched-link text-danger" @click="deleteItem(file.id,index)">delete</a></p>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {

    props: ['files', 'ajaxRoute', 'format'],

    data: function() {

        return {
            myData: this.files,
            myAjaxRoute: this.ajaxRoute,
            myFormat: this.format
        }
    },
    methods: {

        deleteItem: function(id, index) {
            axios.delete(this.myAjaxRoute, {
                    params: {
                        id: id
                    }
                })
                .then((response) => {
                    // success message 
                    this.myData.splice(index, 1)
                });

            this.$emit('handleToggle', true);
        },
    },
}
</script>
