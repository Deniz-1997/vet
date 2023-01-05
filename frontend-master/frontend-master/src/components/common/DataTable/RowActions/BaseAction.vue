<template>
  <v-tooltip bottom>
    <template v-slot:activator="{ on, attrs }">
      <div
        v-on="on"
        v-bind="attrs"
        class="action"
      >
        <text-component
          :variant="linked ? 'a' : 'span'"
          :href="href"
        >
          <v-btn
            v-on="$listeners"
            icon
          >
            <slot
              v-if="hasCustomIcon"
              name="customIcon"
            />
            <icon-component
              v-else
              :height="20"
              :icon-color="iconColor"
              :width="25"
            >
              <slot />
            </icon-component>
          </v-btn>
        </text-component>
      </div>
    </template>
    {{ hint }}
  </v-tooltip>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';
import IconComponent from '@/components/common/IconComponent/IconComponent.vue';
import TextComponent from '@/components/common/Text/Text.vue';

@Component({
  name: 'base-action',
  components: {
    IconComponent,
    TextComponent,
  },
})
export default class BaseAction extends Vue {
  @Prop({ type: String, default: '' }) hint!: string;
  @Prop({ type: String, default: '#' }) href!: string;
  @Prop({ type: String, default: '#828286' }) iconColor!: string;
  @Prop(Boolean) linked!: boolean;

  get hasCustomIcon(): boolean {
    return !!this.$slots.customIcon;
  }
}
</script>

<style scoped lang="scss">
  .action {
    display: inline-block;
  }
</style>
