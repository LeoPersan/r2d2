<template>
    <editor :api-key="api_key" :value="value" @input="$emit('input', $event.replace('../../storage', '/storage'))" :init="{
        height: 200,
        menubar: false,
        images_upload_handler: upload_imagem,
        plugins: [
            'advlist autolink image lists link image charmap print preview searchreplace visualblocks code fullscreen insertdatetime media table paste help wordcount'
        ],
        toolbar: 'undo redo | formatselect fontsizeselect | bold italic forecolor backcolor | link | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | image | code removeformat | help'
    }"></editor>
</template>
<script>
export default {
    props: {
        value: String,
        url_upload_imagem: {
            type: [String, Boolean],
            default: false
        },
        api_key: {
            type: String
        }
    },
    methods: {
        upload_imagem(blobInfo, success, failure, progress) {
            var xhr, formData;

            xhr = new XMLHttpRequest();
            xhr.withCredentials = true;
            xhr.open('POST', this.url_upload_imagem);

            xhr.upload.onprogress = function (e) {
                progress(e.loaded / e.total * 100);
            };

            xhr.onload = function() {
                var json;

                if (xhr.status === 403) {
                    failure('HTTP Error: ' + xhr.status, { remove: true });
                    return;
                }

                if (xhr.status < 200 || xhr.status >= 300) {
                    failure('HTTP Error: ' + xhr.status);
                    return;
                }

                json = JSON.parse(xhr.responseText);

                if (!json || typeof json.location != 'string') {
                    failure('Invalid JSON: ' + xhr.responseText);
                    return;
                }

                success(json.location);
            };

            xhr.onerror = function () {
                failure('Image upload failed due to a XHR Transport error. Code: ' + xhr.status);
            };

            formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());
            formData.append('_token', document.querySelector('[name=csrf-token]').getAttribute('content'));

            xhr.send(formData);
        }
    }
}
</script>
