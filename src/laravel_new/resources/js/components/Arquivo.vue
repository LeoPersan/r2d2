<template>
    <input type="file" class="form-control" @change="onFileChange" maxlength="200" :accept="accept" :required="required" :multiple="multiple" />
</template>
<script>
	export default {
        model: {
            prop: 'files',
            event: 'change'
        },
        data() {
            return {
                arquivos: this.value || []
            }
        },
		props: {
            files: {
                default: false
            },
            required: {
                type: Boolean,
                default: false
            },
            accept: {
                type: String,
                default: '*'
            },
            url: {
                type: [String, Boolean],
                default: false
            },
            multiple: {
                type: Boolean,
                default: false
            },
        },
		methods: {
            onFileChange(e) {
				var files = e.target.files || e.dataTransfer.files;
				if (!files.length) return;
                if (this.url)
                    this.postFiles(files)
                else
                    this.readFilesAsBase64(files)
			},
            readFilesAsBase64(files) {
                var vm = this;
                [...files].forEach(file => {
                    let reader = new FileReader()
                    reader.onload = (e) => {
                        if (vm.multiple)
                            vm.arquivos.push(e.target.result)
                        else
                            vm.arquivos = e.target.result
                        vm.$emit('change', vm.arquivos)
                    };
                    reader.readAsDataURL(file);
                });
            },
            postFiles(files) {
                let form = new FormData();
                [...files].forEach(file => {
                    form.append('files[]', file)
                })
                axios.post(this.url, form, {headers:{'content-type':'multipart/form-data'}})
                    .then(response => this.$emit('change', response.data.paths))
            }
		},
	}
</script>
