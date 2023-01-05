<template>
    <v-app class="body-column">
        <table-curd v-bind:api="table.template.list.api"
                    v-bind:api-data="table.template.list.api_data"
                    v-bind:api-url="table.template.list.url"
                    v-bind:headers="table.template.list.headers"
                    v-bind:name="table.template.list.name"
                    v-bind:template-item="table.template.list.templateItem"
                    v-bind:templates="table.template.list.templates">
            <template v-slot:default="slotProps">

                <v-container>
                    <v-row>
                        <v-col cols="12">
                            <v-text-field label="Имя" v-model="slotProps.edit.name"></v-text-field>
                        </v-col>
                        <v-col cols="12">
                            <v-color-picker class="ma-2" hide-canvas hide-inputs show-swatches
                                            v-model="slotProps.edit.color"></v-color-picker>
                        </v-col>
                        <v-col cols="4">
                            <v-switch label="Показывать статус" v-model="slotProps.edit.show_status_notify"></v-switch>
                        </v-col>
                        <v-col cols="4">
                            <v-switch label="Показывать дату" v-model="slotProps.edit.show_date"></v-switch>
                        </v-col>
                        <v-col cols="4">
                            <v-switch label="Показывать время" v-model="slotProps.edit.show_time"></v-switch>
                        </v-col>
                        <v-col cols="12">
                            <v-text-field label="Формат даты" v-model="slotProps.edit.format_date"
                                          value="YYYY-MM-DD H:i:s"></v-text-field>
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
            }
        }
    }
</script>

<style scoped>
</style>
