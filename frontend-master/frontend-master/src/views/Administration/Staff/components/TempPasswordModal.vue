<template>
    <div>
        <v-row>
            <v-col cols="12">
                <div class="title">
                    <span>Пользователю присвоен временный пароль: {{ value }}</span>
                </div>
            </v-col>
        </v-row>
    </div>
</template>

<script lang="ts">
    import { Component, Vue, Prop } from "vue-property-decorator";

    @Component({
        name: "temp-password-modal",
    })
    export default class TempPasswordModal extends Vue {
        @Prop(String) readonly value!: string;
    }

</script>

<style lang="scss" scoped>
    @import "@/assets/styles/_variables.scss";
    @import "@/assets/styles/_mixins.scss";

    .title {
        align-items: center;
        display: flex;
        justify-content: space-between;
        font-style: normal;
        font-weight: 500;
        font-size: 24px;
        line-height: 24px;
        color: $black-color;

        @include respond-to("medium") {
            font-size: 22px;
        }

        @include respond-to("small") {
            font-size: 18px;
        }
    }
</style>
