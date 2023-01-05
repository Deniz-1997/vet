<template>
    <div class="monitoring">
        <monitoring-main-page v-if="this.$route.path === '/monitoring'"/>
        <router-view></router-view>
    </div>
</template>

<script lang="ts">
    import { Component, Prop, Vue } from 'vue-property-decorator'
    import MonitoringMainPage from "./components/MainPage/MainPage.vue"

    @Component({
        name: 'monitoring',
        components: { MonitoringMainPage }
    })

    export default class Monitoring extends Vue {
        @Prop({type: String, default: 'АРМ «Государственный мониторинг»'}) public title!: String;
    }
</script>

<style lang="scss" scoped>

</style>