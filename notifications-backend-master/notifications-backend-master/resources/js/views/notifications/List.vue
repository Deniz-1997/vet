<template>
    <v-app class="body-column">
        <table-curd v-bind:api="table.notifications.list.api"
                    v-bind:api-data="table.notifications.list.api_data"
                    v-bind:api-url="table.notifications.list.url"
                    v-bind:headers="table.notifications.list.headers"
                    v-bind:name="table.notifications.list.name"
                    v-bind:template-item="table.notifications.list.templateItem"
                    v-bind:templates="table.notifications.list.templates">
            <template v-slot:default="slotProps">
                <v-container>
                    <v-row>
                        <v-col cols="12">
                            <v-text-field label="Имя" v-model="slotProps.edit.name"></v-text-field>
                        </v-col>
                        <v-col cols="12">
                            <v-text-field label="Статус" v-model="slotProps.edit.status"></v-text-field>
                        </v-col>
                        <v-col cols="12">
                            <v-autocomplete :items="completeNameChannel" :loading="autocomplete.channel.isLoading"
                                            :search-input.sync="autocomplete.channel.search" color="black"
                                            hide-selected item-text="name"
                                            item-value="id" label="Имя канала" placeholder="Введите имя канала"
                                            v-model="slotProps.edit.channel_id"></v-autocomplete>
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
                    channel: {start_loading_entries: false, isLoading: false, entries: [], search: null}
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
            completeNameChannel() {
                let t = window.completeMethod(this.autocomplete.channel, null,
                    '/api/v2/channels/list?api_token=' + window.auth.token + '&paginator=false&limit=10000',
                    null);
                console.log(t);
                return t;
            },
        },

        watch: {
            'autocomplete.channel.search': function (val) {
                window.methodSearch(val, this.completeNameChannel, this.autocomplete.channel,
                    '/api/v2/channels/list?api_token=' + window.auth.token + '' +
                    '&paginator=false&where=' + JSON.stringify([
                        ['name', 'like', val]
                    ]));
            }
        },

        created() {
            window.fetchAutocomplete('/api/v2/channels/list?api_token=' + window.auth.token + '&paginator=false&limit=10000',
                this.autocomplete.channel);
        }
    }
</script>

<style scoped>
</style>
