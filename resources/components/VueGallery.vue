<template>
    <div class="container-fluid">
        <!--
        <gallery :images="imageList" :index="index" @close="index = null">
        </gallery>
        -->
        <div v-lazy-container="{ selector: 'div' }">
            <!--[if IE]>
            <div
            class="image d-none d-md-block" 
            v-for="image, imageIndex in imageList"
            v-lazy:background-image = "image.href"
            :data-src = "image.href"
            @click="index = imageIndex"
            :style="{ width: '16%', height: '10.7vw' }"
            >   
            </div>
            <![endif]-->
            <!--[if !IE] -->
            <div class="image d-none d-md-block" v-for="image in imageList" v-lazy:background-image="image.href" :data-src="image.href" :style="{ width: '16vw', height: '10.7vw'}" @click="relocate(image.title)">
            </div>
            <!-- <![endif]-->
            <!--[if IE]>
            <div
            class="image d-md-none d-lg-none d-xl-none"
            v-for="image, imageIndex in imageList"
            v-lazy:background-image = "image.href"
            :data-src = "image.href"
            @click="index = imageIndex"
            :style="{ width: '36%', height: '24vw' }"
            >
            </div>
            <![endif]-->
            <!--[if !IE] -->
            <div class="image d-md-none d-lg-none d-xl-none" v-for="image in imageList" v-lazy:background-image="image.href" :data-src="image.href" :style="{ width: '36vw', height: '24vw' }" @click="relocate(image.title)">
            </div>
            <!-- <![endif]-->
        </div>
    </div>
</template>

<script>
//import Gallery from './gallery.vue'
import VueLazyload from 'vue-lazyload'

Vue.use(VueLazyload, {
    preLoad: 0,
    error: '/error.jpg',
    loading: '/loading.gif',
})

export default {
/*
    components: {
        'gallery': Gallery
    },
*/
    props: ['images'],

    data: function() {
        return {
            imageList: this.images,
            index: null
        }
    },
    methods: {
        relocate: function($name) {
            location.href="/image/" + $name
        },
    },

}
</script>
