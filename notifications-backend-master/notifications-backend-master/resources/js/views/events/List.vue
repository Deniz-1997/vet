<template>
    <v-app class="body-column">
        <table-curd v-bind:api="table.events.list.api"
                    v-bind:api-data="table.events.list.api_data"
                    v-bind:api-url="table.events.list.url"
                    v-bind:headers="table.events.list.headers"
                    v-bind:name="table.events.list.name"
                    v-bind:template-item="table.events.list.templateItem"
                    v-bind:templates="table.events.list.templates">
            <template v-slot:default="slotProps">

                <v-container>
                    <v-row>
                        <v-col cols="6">
                            <v-text-field label="Имя" v-model="slotProps.edit.name"></v-text-field>
                        </v-col>
                        <v-col cols="6">
                            <v-switch label="Учитывать иерархию" v-model="slotProps.edit.hierarchy"></v-switch>
                        </v-col>

                        <v-col cols="4" id="col-icon">
                            <div v-if="slotProps.edit.file">
                                <div class="center-img" id="img-icon">
                                    <img :src="slotProps.edit.path_internal">
                                </div>
                                <v-btn v-show="slotProps.edit.file" @click="deleteIcon" id="btn-delete-icon" depressed
                                       color="error">Delete
                                </v-btn>
                            </div>
                        </v-col>
                        <v-col cols="8">
                            <vue-dropzone ref="dropzone" id="dropzone" v-on:vdropzone-sending="sendingEvent"
                                          v-on:vdropzone-success="successFile"
                                          :options="dropzoneOptions"></vue-dropzone>
                        </v-col>
                    </v-row>
                </v-container>
            </template>
        </table-curd>
    </v-app>
</template>

<script>
import vue2Dropzone from 'vue2-dropzone'
import 'vue2-dropzone/dist/vue2Dropzone.min.css'

export default {
    name: 'app',
    components: {
        vueDropzone: vue2Dropzone
    },
    data: function () {
        return {
            drop: null,
            id: 0,
            table: table,
            token: window.auth.token,
            dropzoneOptions: {
                url: '/api/v2/file/list?api_token=' + auth.token,
                addRemoveLinks: true,
                maxFiles: 1,
                thumbnailWidth: 150,
                maxFilesize: 0.5
            }
        }
    },
    methods: {
        successFile(file, response) {
            window.Vue.notify({
                group: 'success',
                title: 'Файл загружен',
                text: 'Файл успешно добавлен к событию'
            });
            Event.$emit('changeData');
            $('#img-icon').find('img').attr('src', response.path_internal);
            $('#btn-delete-icon').show();
            $('#col-icon').show();
        },
        sendingEvent(file, xhr, formData) {
            formData.append('event_id', this.id);
        },
        deleteIcon() {
            api.events.list(this.id).put({file_id: null}).then(r => {
                console.log('changeData');
                window.Vue.notify({
                    group: 'success',
                    title: 'Файл успешно удален',
                });
                Event.$emit('changeData');
                $('#img-icon').find('img').attr('src', '');
                $('#btn-delete-icon').hide();
                $('#col-icon').hide();
            }).catch(r => {
                console.error(r);
                alert('error delete icon');
            })
        }
    },
    computed: {
        getPath(file) {
        },
    },
    mounted() {
        Event.$on('editItem', e => {
            this.id = e.id;
        });
    },
}
</script>

<style scoped>
img {
    max-width: 100%;
}

.center-img {
    width: 60%;
    margin: 3em auto;
}
</style>
