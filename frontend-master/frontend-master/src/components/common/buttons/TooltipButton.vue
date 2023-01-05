<template>
  <div @click="$emit('click')">
    <v-tooltip bottom>
      <template v-slot:activator="{ attrs }">
        <v-btn :outlined="false" class="mx-3" icon large v-bind="attrs">
          <icon-component height="30" width="30">
            <edit-icon v-if="type === 'edit'" />
            <print-icon v-if="type === 'print'" />
          </icon-component>
        </v-btn>
      </template>
      <span>{{ tooltipText }}</span>
    </v-tooltip>
  </div>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';
import IconComponent from '@/components/common/IconComponent/IconComponent.vue';
import PrintIcon from '@/components/common/IconComponent/icons/PrintIcon.vue';
import EditIcon from '@/components/common/IconComponent/icons/EditIcon.vue';

@Component({
  name: 'tooltip-button',
  components: {
    EditIcon,
    PrintIcon,
    IconComponent,
  },
})
export default class TooltipButton extends Vue {
  @Prop({ type: String, required: true }) readonly tooltipText!: string;

  @Prop({ type: String, default: '' }) readonly type!: string;
}
</script>

<style lang="scss" scoped>
img.icon {
  max-width: 100%;
  width: 30px;
}
</style>
