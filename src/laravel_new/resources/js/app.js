require('./bootstrap');

window.Vue = require('vue').default;

Vue.use(require('./plugins/caw'));
Vue.use(require('vue-the-mask'));
Vue.use(require('v-money'), {
    decimal: ',',
    thousands: '.',
    prefix: 'R$ ',
    precision: 2,
})
Vue.component('v-select', require('vue-select').VueSelect)
Vue.component('Modal', require('./components/Modal.vue').default);
Vue.component('Arquivo', require('./components/Arquivo.vue').default);
Vue.component('Imagem', require('./components/Imagem.vue').default);
Vue.component('submit-btn', require('./components/SubmitBtn.vue').default);
Vue.component('editor', require('@tinymce/tinymce-vue').default);
Vue.component('tinymce', require('./components/Tinymce.vue').default);


if (window.vue == undefined) {
    window.vue = new Vue({
        el: '#app',
        methods: {
            modal(message, succes = () => {}, ok, cancel) {
                this.$refs.modal.configModal('', message, ok, cancel, ()=>{
                    succes()
                    this.$refs.modal.hide();
                });
                this.$refs.modal.show();
            },
            destroy(action, e) {
                this.modal(e.target.getAttribute('confirm'), () => {
                    this.form.loading = true
                    axios.post(action, new FormData(e.target)).then(response=>{
                        this.form.loading = false
                        this.modal(response.data.msg, ()=>{
                            if(response.data.redirect != undefined)
                                window.location.href = response.data.redirect;
                        }, 'OK', '');
                    }).catch(response=>{
                        this.form.loading = false
                        console.log(response)
                    })
                }, e.target.getAttribute('ok'), e.target.getAttribute('cancel'))
            }
        }
    });
}
