<template>
  <sdiz-agent-filter-list
    v-if="accessGrantedAuthorities(model.register_agent_privileges)"
    v-model="model"
    :get-list="model.get_list_link"
    :router-link="model.router_link"
  ></sdiz-agent-filter-list>
</template>

<script lang="ts">
import { Component } from 'vue-property-decorator';
import { AgentVueModel } from '@/models/Sdiz/Agent.vue';
import SdizAgentFilterList from '@/views/SdizAgent/components/List.vue';
import SdizAgent from '@/views/SdizAgent/SdizAgent.vue';

@Component({
  name: 'sdiz-agent-list',
  components: {
    SdizAgentFilterList,
  },
})
export default class SdizAgentList extends SdizAgent {
  model: AgentVueModel = new AgentVueModel();
}
</script>

<style lang="scss" scoped></style>
