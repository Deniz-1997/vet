<template>
  <div>
    <sdiz-agent-form v-model="model" :is-create="false" :is-show="false" :is-detail="true" />
  </div>
</template>

<script lang="ts">
import { Component } from 'vue-property-decorator';
import { mixins } from 'vue-class-component';
import { AdditionalMix } from '@/utils/mixins/additional';
import { RequestMix } from '@/utils/mixins/request';
import SdizAgentForm from '@/views/SdizAgent/components/Form.vue';
import { getElementById } from '@/utils/methodsForViews';
import { AgentOgvVueModel } from '@/models/Sdiz/Ogv/AgentOgv.vue';
import { SdizVueModel } from '@/models/Sdiz/Data/Sdiz.vue';
import { LotDataVueModel } from '@/models/Lot/Data/LotData.vue';

@Component({
  name: 'SdizAgentOgvView',
  components: { SdizAgentForm },
})
export default class SdizAgentOgvView extends mixins(AdditionalMix, RequestMix) {
  model: AgentOgvVueModel = new AgentOgvVueModel();
  showItem: string = 'sdiz/showAgent';

  async created(): Promise<void> {
    const data = await this.findElementById(parseInt(this.$route.params.id));
    this.model = new AgentOgvVueModel(data);

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
@import '@/assets/styles/_variables.scss';
@import '@/assets/styles/_mixins.scss';
</style>
