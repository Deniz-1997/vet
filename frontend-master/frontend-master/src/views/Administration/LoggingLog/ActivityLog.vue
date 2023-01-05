<template>
  <div class="activityLog">
    <logging-list v-if="this.$route.path === '/activityLog'"/>
    <router-view></router-view>
  </div>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator'
import LoggingList from "@/views/Administration/LoggingLog/components/LoggingList.vue"

@Component({
  name: 'logging',
  components: { LoggingList }
})

export default class ActivityLog extends Vue {
  @Prop({type: String, default: 'Реестр пользователей'}) public title!: String;
}
</script>