<template>
    <div :id="'modal_'+id" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{title}}</h5>
                </div>
                    <div class="modal-body">
                    <p>{{text}}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" v-on:click="hide" v-if="btn_cancel != ''">{{btn_cancel}}</button>
                    <button type="button" class="btn btn-primary" v-on:click="runConfirmFn" v-if="btn_confirm != ''">{{btn_confirm}}</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                title: 'title',
                text: 'text',
                btn_confirm: 'Confirm',
                btn_cancel: '',
                fn_confirm: function () {},
                id: this._uid,
            }
        },
        mounted() {
            this.id = this._uid;
        },
        methods:{
            configModal(title, text, btn_confirm, btn_cancel, fn_confirm){
                this.title = title;
                this.text = text;
                this.btn_confirm = btn_confirm;
                this.btn_cancel = btn_cancel;
                if(fn_confirm){
                    this.fn_confirm = fn_confirm;
                }
            },
            setConfirmFn(fn){
                this.fn_confirm = fn;
            },
            runConfirmFn(){
                try {
                    this.fn_confirm();
                }catch (e){
                    console.log(e);
                }
            },
            show (timeout) {
                $('#modal_'+this.id).modal('show');

                let thisCopy = Object.assign({} , this);
                if(!isNaN(timeout)){
                    setTimeout(function(){
                        thisCopy.runConfirmFn();
                    }, timeout);
                }
            },
            hide () {
                $('#modal_'+this.id).modal('hide');
            },
        }
    }
</script>


