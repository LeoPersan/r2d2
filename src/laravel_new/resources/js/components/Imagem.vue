<template>
    <div>
        <Arquivo :required="required" :accept="accept" :url="url" :multiple="multiple" v-model="imagens"/>
		<br>
        <div class="row" v-if="multiple">
            <div class="col-6 col-md-4 col-lg-3 col-xl-2 text-center" v-for="(imagem, k) in imagens" :key="k">
                <button type="button" class="btn btn-danger deletar" @click="imagens.splice(k,1)">
                    <i class="fas fa-times"></i>
                </button>
                <img class="img-thumbnail" :src="imagem">
            </div>
        </div>
        <div class="row" v-else>
            <div class="col-6">
                <button type="button" class="btn btn-danger deletar" @click="imagens = null">
                    <i class="fas fa-times"></i>
                </button>
                <img class="img-thumbnail" :src="imagens">
            </div>
        </div>
    </div>
</template>
<script>
export default {
    model: {
        prop: 'value',
        event: 'change'
    },
    data() {
        return {
            imagens: this.value || []
        }
    },
    watch: {
        imagens(newValue, oldValue) {
            this.$emit('change', this.imagens)
        }
    },
    props: {
        value: {
            default: false
        },
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
