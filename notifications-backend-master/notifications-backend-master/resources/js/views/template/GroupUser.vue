<template>
    <v-app class="body-column">
        <table-curd v-bind:api="table.template.groupUser.api"
                    v-bind:api-data="table.template.groupUser.api_data"
                    v-bind:api-url="table.template.groupUser.url"
                    v-bind:headers="table.template.groupUser.headers"
                    v-bind:name="table.template.groupUser.name"
                    v-bind:template-item="table.template.groupUser.templateItem"
                    v-bind:templates="table.template.groupUser.templates">
            <template v-slot:default="slotProps">

                <v-container>
                    <v-row>
                        <v-col cols="12">
                            <v-autocomplete :items="completeNameTemplate" :loading="autocomplete.template.isLoading"
                                            :search-input.sync="autocomplete.template.search" color="black"
                                            hide-selected item-text="name"
                                            item-value="id" label="Имя шаблона" placeholder="Введите имя шаблона"
                                            v-model="slotProps.edit.template_id"></v-autocomplete>
                        </v-col>
                        <v-col cols="12">
                            <v-autocomplete :items="completeNameGroup" :loading="autocomplete.group.isLoading"
                                            :search-input.sync="autocomplete.group.search" color="black"
                                            hide-selected item-text="name"
                                            item-value="id" label="Имя группы пользователя"
                                            placeholder="Введите имя группы"
                                            v-model="slotProps.edit.group_id"></v-autocomplete>
                        </v-col>
                        <v-col cols="12">
                            <v-text-field label="Приоритет"
                                          min="1" type="number" v-model="slotProps.edit.priority"></v-text-field>
                        </v-col>

                        <v-col cols="12">
                            <v-text-field label="Задержка отправки уведомления ( в минутах )"
                                          min="1" type="number"
                                          v-model="slotProps.edit.delay_send"></v-text-field>
                        </v-col>
                    </v-row>
                </v-container>
            </template>
        </table-curd>
    </v-app>
</template>

<script>
    export default {
        data() {
            return {
                table: table,
                autocomplete: {
                    group: {start_loading_entries: false, isLoading: false, entries: [], search: null},
                    template: {start_loading_entries: false, isLoading: false, entries: [], search: null}
                },
            }
        },
        mounted() {
            Event.$on('clearSearchInput', () => {
                $.each(this.autocomplete, (e, v) => {
                    v.search = null;
                });
            });
        },
        computed: {
            completeNameTemplate() {
                return window.completeMethod(this.autocomplete.template, null,
                    '/api/v2/template/list?api_token=' + window.auth.token + '&paginator=false&limit=10000',
                    null);
            },
            completeNameGroup() {
                return window.completeMethod(this.autocomplete.group, null,
                    '/api/v2/dictionary/group-users?api_token=' + window.auth.token + '&paginator=false&limit=10000',
                    null);
            },
        },

        watch: {
            'autocomplete.group.search': function(val){
                window.methodSearch(val, this.completeNameGroup, this.autocomplete.group,
                    '/api/v2/dictionary/group-users?api_token=' + window.auth.token + '' +
                    '&paginator=false&where=' + JSON.stringify([
                        ['name', 'like', val]
                    ]));
            },
            'autocomplete.template.search': function(val){
                window.methodSearch(val, this.completeNameTemplate, this.autocomplete.group,
                    '/api/v2/template/list?api_token=' + window.auth.token + '' +
                    '&paginator=false&where=' + JSON.stringify([
                        ['name', 'like', val]
                    ]));
            },
        },

        created() {
            window.fetchAutocomplete('/api/v2/template/list?api_token=' + window.auth.token + '&paginator=false&limit=10000',
                this.autocomplete.group);

            window.fetchAutocomplete('/api/v2/dictionary/group-users?api_token=' + window.auth.token + '&paginator=false&limit=10000',
                this.autocomplete.group);
        }
    }
</script>

<style scoped>
</style>
