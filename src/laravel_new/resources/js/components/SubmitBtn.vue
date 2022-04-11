<template>
	<button type="submit" class="btn btn-primary btn-block">
        <slot></slot>
    </button>
</template>
<script>
	export default {
		props: ['url'],
		methods: {
            submit_form(process_return) {
                if(process_return !== false){
                    this.$parent.form.submit(this.url, this.onSuccess);
                }else{
                    this.$parent.form.submit(this.url, () => {});
                }
            },

            onSuccess(response) {
                try {
                    if(response.data.result) {
                        this.$root.$refs.modal.configModal('Sucesso', response.data.msg, 'OK', '', ()=>{
                            if(response.data.redirect == undefined){
                                history.back();
                            }else if(response.data.redirect == ''){
                                window.location.reload();
                            } else {
                                window.location.href = response.data.redirect;
                            }
                            this.$root.$refs.modal.hide();
                        });
                        this.$root.$refs.modal.show();
                    } else {
                        this.$root.$refs.modal.configModal('Aviso', response.data.msg, 'OK', '', ()=>{
                            if(response.data.redirect?.length)
                                window.location.href = response.data.redirect;
                            this.$root.$refs.modal.hide();
                        });
                        this.$root.$refs.modal.show();
                    }
                } catch (e) {
                    console.log(e);
                }
            },
		}
	}
</script>
