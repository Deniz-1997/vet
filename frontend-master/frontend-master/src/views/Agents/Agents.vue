<template>
  <div class="agents">
    <v-container>
      <AgentsList v-if="$route.path === '/contracts'"/>
      <router-view></router-view>
    </v-container>
  </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import AgentsList from "@/views/Agents/components/AgentsList.vue"

@Component({
  name: 'agents',
  components: { AgentsList }
})

export default class Agents extends Vue {

}
</script>
