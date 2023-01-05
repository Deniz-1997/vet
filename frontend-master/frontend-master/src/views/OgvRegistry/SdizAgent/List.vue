<template>
  <sdiz-agent-filter-list
    v-if="accessGrantedAuthorities(viewPrivileges)"
    v-model="model"
    :get-list="model.get_list_link"
    :is-show-additional-button="false"
    :router-link="model.router_link"
  >
    <template #[`sdiz-owner-filter`]="{ model }">
      <v-col cols="12" lg="4" md="6" sm="3" xl="4">
        <ManufacturerAutocomplete
          v-model="model.owner_id"
          label="Владелец партии"
          placeholder="Выберите владельца партии"
          show-name-in-tooltip
        />
      </v-col>
    </template>
  </sdiz-agent-filter-list>
</template>

<script lang="ts">
import { Component } from 'vue-property-decorator';
import SdizAgentFilterList from '@/views/SdizAgent/components/List.vue';
import SdizAgent from '@/views/SdizAgent/SdizAgent.vue';
import { AgentOgvVueModel } from '@/models/Sdiz/Ogv/AgentOgv.vue';
import ManufacturerAutocomplete from '@/components/ManufacturerAutocomplete/ManufacturerAutocomplete.vue';
import { EAction } from '@/models/roles';

@Component({
  name: 'sdiz-agent-ogv-list',
  components: {
    SdizAgentFilterList,
    ManufacturerAutocomplete,
  },
})
export default class SdizAgentOgvList extends SdizAgent {
  model: AgentOgvVueModel = new AgentOgvVueModel();
  viewPrivileges = EAction.READ_AGENT_DATA_REGISTER;
}
</script>

<style lang="scss" scoped></style>
