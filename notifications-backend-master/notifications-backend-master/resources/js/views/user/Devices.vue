<template>
    <v-app class="body-column">
        <table-curd v-bind:api="table.user.devices.api"
                    v-bind:api-data="table.user.devices.api_data"
                    v-bind:api-url="table.user.devices.url"
                    v-bind:headers="table.user.devices.headers"
                    v-bind:name="table.user.devices.name"
                    v-bind:template-item="table.user.devices.templateItem"
                    v-bind:templates="table.user.devices.templates">
            <template v-slot:default="slotProps">
                <v-container>
                    <v-row>
                        <p>В данный момент невозможно создать запись для пользователей.</p>
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
        },
    }
</script>

<style scoped>
</style>
