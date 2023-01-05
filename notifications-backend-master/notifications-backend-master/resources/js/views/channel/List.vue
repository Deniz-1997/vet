<template>
    <v-app class="body-column">

        <table-curd v-bind:api="table.channel.list.api"
                    v-bind:api-data="table.channel.list.api_data"
                    v-bind:api-url="table.channel.list.url"
                    v-bind:headers="table.channel.list.headers"
                    v-bind:name="table.channel.list.name"
                    v-bind:template-item="table.channel.list.templateItem"
                    v-bind:templates="table.channel.list.templates">

            <template v-slot:default="slotProps">
                <v-container>
                    <v-row>
                        <v-col cols="12">
                            <v-text-field label="Channel Name" v-model="slotProps.edit.name"></v-text-field>
                        </v-col>
                        <v-col cols="6">
                            <v-switch :label="`Send sms`" v-model="slotProps.edit.send_sms"></v-switch>
                        </v-col>
                        <v-col cols="6">
                            <v-text-field :disabled="!slotProps.edit.send_sms"
                                          label="Limit sms per day"
                                          min="1" type="number" v-model="slotProps.edit.sms_limit"></v-text-field>
                        </v-col>
                        <v-col cols="6">
                            <v-switch :label="`Send email`" v-model="slotProps.edit.send_email"></v-switch>
                        </v-col>
                        <v-col cols="6">
                            <v-text-field :disabled="!slotProps.edit.send_email"
                                          label="Limit email per day"
                                          min="1" type="number" v-model="slotProps.edit.email_limit"></v-text-field>
                        </v-col>
                    </v-row>
                </v-container>
            </template>
        </table-curd>
    </v-app>
</template>

<script>
    import Templates from "../events/Templates";

    export default {
        components: {Templates},
        data() {
            return {
                table: table
            }
        }
    }
</script>

<style scoped>
</style>
