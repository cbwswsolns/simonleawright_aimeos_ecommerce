<template>

	<div class="col-md-6">


        <!-- all props & events -->
    <CoolLightBox     
      :items="imgs" 
      :index="lbindex"
      loop
      :useZoomBar="true"
      :fullScreen="true"
      @close="lbindex = null">
    </CoolLightBox>


		<div class="row">

            <div class="col-md-4 align-items-center d-none d-md-block">
            	
				<div width="100px" v-for="(image, idx) in imgs" @click = "index = idx">

					<img :src="image" class="img-fluid img-thumbnail" width="100px"></img>

				</div>

            </div>

			<div class="col-md-8 d-none d-md-block" @click="lbindex=index">
				
				<img :src="imgs[index]" class="img-fluid"></img>

			</div>

			<div class="col-12 text-center d-md-none d-lg-none d-xl-none" @click="lbindex=index">
				
				<img :src="imgs[index]" class="img-fluid"></img>

			</div>
            	
			<div class="col-3 d-md-none d-lg-none d-xl-none top-buffer" width="100px" v-for="(image, idx) in imgs" @click = "index = idx">

				<img :src="image" class="img-fluid img-thumbnail" width="100px"></img>

			</div>

        </div>

	</div>

</template>

<script>

import CoolLightBox from 'vue-cool-lightbox'
import 'vue-cool-lightbox/dist/vue-cool-lightbox.min.css'

export default {
    components: {
        CoolLightBox
    },

    props: ['images'],

	data() {
		return {
		  imgs: this.images,
		  visible: false,
		  index: 0,   // default: 0
		  lbindex: null
		}
	},
}
</script>
