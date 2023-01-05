<template>
    <v-app class="body-column">
        <table-curd v-bind:api="table.dictionary.groupUser.api"
                    v-bind:api-data="table.dictionary.groupUser.api_data"
                    v-bind:api-url="table.dictionary.groupUser.url"
                    v-bind:headers="table.dictionary.groupUser.headers"
                    v-bind:name="table.dictionary.groupUser.name"
                    v-bind:template-item="table.dictionary.groupUser.templateItem"
                    v-bind:templates="table.dictionary.groupUser.templates">
            <template v-slot:default="slotProps">
                <v-container>
                    <v-row>
                        <v-col cols="12">
                            <v-text-field label="Имя" v-model="slotProps.edit.name"></v-text-field>
                        </v-col>
                        <v-col cols="6">
                            <v-switch label="Фильтровать оповещения" v-model="slotProps.edit.filter_notifications"></v-switch>
                        </v-col>
                        <v-col cols="6">
                            <v-switch label="Ответственный" v-model="slotProps.edit.responsible_notifications"></v-switch>
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
                table: table
            }
        }
    }
</script>

<style scoped>
</style>
