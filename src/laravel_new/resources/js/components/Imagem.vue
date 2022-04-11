<template>
    <div>
        <Arquivo :required="required" :accept="accept" :url="url" :multiple="multiple" @change="files => imagens = files"/>
		<br>
        <div class="row" v-if="multiple">
            <div class="col-6 col-md-4 col-lg-3 col-xl-2 text-center" v-for="(imagem, k) in imagens" :key="k">
                <button type="button" class="btn btn-danger deletar" @click="deleteImage(k)">
                    <i class="fas fa-times"></i>
                </button>
                <img class="img-thumbnail" :src="imagem">
            </div>
        </div>
        <div class="row" v-else>
            <div class="col-6">
                <img class="img-thumbnail" :src="imagens">
            </div>
        </div>
    </div>
</template>
<script>
import Arquivo from './Arquivo'
export default {
    data() {
        return {
            imagens: []
        }
    },
    watch: {
        imagens(newValue, oldValue) {
            this.$emit('change', this.imagens)
        }
    },
    props: {
        required: {
            type: Boolean,
            default: false
        },
        accept: {
            type: String,
            default: 'image/*'
        },
        url: {
            type: [String, Boolean],
            default: false
        },
        multiple: {
            type: Boolean,
            default: false
        }
    },
	methods: {
        deleteImage(i) {
            this.imagens.splice(i,1)
        }
    }
}
</script>
<style scoped>
	.img-thumbnail {
		max-height: 120px;
	}
    button.deletar {
        position: absolute;
        top: 0;
        right: 15px;
    }
</style>
