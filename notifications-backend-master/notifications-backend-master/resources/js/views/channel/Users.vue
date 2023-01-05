<template>
    <v-app class="body-column">
        <table-curd v-bind:api="table.channel.users.api"
                    v-bind:api-data="table.channel.users.api_data"
                    v-bind:api-url="table.channel.users.url"
                    v-bind:headers="table.channel.users.headers"
                    v-bind:name="table.channel.users.name"
                    v-bind:template-item="table.channel.users.templateItem"
                    v-bind:templates="table.channel.users.templates">
            <template v-slot:default="slotProps">
                <v-container>
                    <v-row>
                        <v-col cols="12">
                            <v-autocomplete :items="completeNameUser" :loading="autocomplete.user.isLoading"
                                            :search-input.sync="autocomplete.user.search" color="black"
                                            hide-selected item-text="name"
                                            item-value="id" label="Имя пользователя"
                                            placeholder="Введите имя пользователя"
                                            v-model="slotProps.edit.user_id"></v-autocomplete>
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
                    user: {start_loading_entries: false, isLoading: false, entries: [], search: null},
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
                return this.autocomplete.channel.entries.map(e => {
                    const name = e.name;
                    return Object.assign({}, e, {name})
                });
            },
            completeNameUser() {
                return this.autocomplete.user.entries.map(e => {
                    const name = e.name;
                    return Object.assign({}, e, {name})
                });
            },
        },

        watch: {
            'autocomplete.user.search': function (val) {
                window.methodSearch(val, this.completeNameUser, this.autocomplete.user,
                    '/api/v2/user/list?api_token=' + window.auth.token + '' +
                    '&paginator=false&where=' + JSON.stringify([
                        ['name', 'like', val]
                    ]));
            },
            'autocomplete.channel.search': function (val) {
                window.methodSearch(val, this.completeNameChannel, this.autocomplete.channel,
                    '/api/v2/channels/list?api_token=' + window.auth.token + '' +
                    '&paginator=false&where=' + JSON.stringify([
                        ['name', 'like', val]
                    ]));
            },
        },

        created() {
            window.fetchAutocomplete('/api/v2/user/list?api_token=' + window.auth.token + '&paginator=false&limit=10000',
                this.autocomplete.user);

            window.fetchAutocomplete('/api/v2/channels/list?api_token=' + window.auth.token + '&paginator=false&limit=10000',
                this.autocomplete.channel);
        }
    }
</script>

<style scoped>
</style>
