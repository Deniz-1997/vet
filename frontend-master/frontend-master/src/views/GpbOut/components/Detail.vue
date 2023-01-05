<template>
  <gpb-out-form v-model="model" :update-link="model.update_apiendpoit" detail :is-show="isShow"></gpb-out-form>
</template>
<script lang="ts">
import { Component, Model, Prop } from 'vue-property-decorator';
import GpbOut from '@/views/GpbOut/GpbOut.vue';
import { getElementById } from '@/utils/methodsForViews';
import { GpbOutDataVueModel } from '@/models/Lot/GpbOut/GpbOutData.vue';
import { GpbOutDataOgvVueModel } from '@/models/Lot/Ogv/GpbOutDataOgv.vue';
import GpbOutForm from '@/views/GpbOut/components/Form.vue';

@Component({
  name: 'gpb-out-detail',
  components: {
    GpbOutForm,
  },
})
export default class GpbOutDetail extends GpbOut {
  @Model('change', { type: Object, required: true }) value!: GpbOutDataVueModel | GpbOutDataOgvVueModel;
  @Prop({ type: Boolean, default: true }) readonly isShow!: boolean;
  showItem: string | null = null;
  get model(): GpbOutDataVueModel | GpbOutDataOgvVueModel {
    return this.value;
  }
  set model(value) {
    this.$emit('change', value);
  }

  async created(): Promise<void> {
    this.showItem = this.model.show_apiendpoit;
    const response = await this.findElementById(parseInt(this.$route.params.id));
    this.$emit('change', this.model.constructor(response));
  }

  async findElementById(id) {
    return await getElementById(this, id);
  }
}
</script>
