<template>
  <div>
    <sdiz-agent-form
      v-if="accessGrantedAuthorities(model.view_data_privileges)"
      v-model="model"
      :is-create="false"
      :is-detail="true"
    />
  </div>
</template>

<script lang="ts">
import { Component, Mixins } from 'vue-property-decorator';
import { AdditionalMix } from '@/utils/mixins/additional';
import { RequestMix } from '@/utils/mixins/request';
import SdizAgentForm from '@/views/SdizAgent/components/Form.vue';
import { getElementById } from '@/utils/methodsForViews';
import { AgentVueModel } from '@/models/Sdiz/Agent.vue';
import { PermissionMix } from '@/utils/mixins/permission';
import { SdizVueModel } from '@/models/Sdiz/Data/Sdiz.vue';
import { LotDataVueModel } from '@/models/Lot/Data/LotData.vue';

@Component({
  name: 'SdizAgentList',
  components: { SdizAgentForm },
})
export default class SdizAgentView extends Mixins(AdditionalMix, RequestMix, PermissionMix) {
  model: AgentVueModel = new AgentVueModel();
  showItem = 'sdiz/showAgent';

  async created(): Promise<void> {
    const data = await this.findElementById(parseInt(this.$route.params.id));
    this.model = new AgentVueModel(data);

    const sdiz_id = data.sdiz?.id;

    if (sdiz_id) {
      const { response } = await this.$store.dispatch((this.model.sdiz as SdizVueModel).show_lot_apiendpoit, sdiz_id);

      (this.model.sdiz as SdizVueModel).objects.lot = new LotDataVueModel(response);
    }
  }

  async findElementById(id) {
    return await getElementById(this, id);
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';
</style>
