<template>
  <div v-if="isShow" class="pop-up">
    Старое значение:
    {{ value }}
  </div>
</template>

<script lang="ts">
import get from 'lodash/get';
import { Vue, Component, Prop } from 'vue-property-decorator';

@Component({})
export default class extends Vue {
  @Prop({ type: Object, default: () => ({}) }) readonly data!: any;
  @Prop({ type: String, default: () => '' }) readonly prop!: string;

  get value() {
    return get(this.data, this.prop + '.oldValue', '—');
  }

  get isShow() {
    return get(this.data, this.prop + '.editCode', '') !== 'NONE';
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';

.pop-up {
  background: $white-color;
  border: 1px solid $light-grey-color;
  border-radius: 5px;
  display: none;
  padding: 5px 15px;
  position: absolute;
  font-size: 13px;
  left: 0;
  top: -35px;
}

.elementsInput:hover {
  .pop-up {
    display: block;
  }
}
</style>
