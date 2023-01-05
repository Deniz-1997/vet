<template>
  <v-list-item
    @click="selectAllWithToggle"
    ripple
  >
    <v-list-item-action>
      <v-icon>
        {{ selectAllIcon }}
      </v-icon>
    </v-list-item-action>
    <v-list-item-content>
      <v-list-item-title>
        Все
      </v-list-item-title>
    </v-list-item-content>
  </v-list-item>
</template>

<script lang="ts">
/* eslint-disable max-len */
import { Component, Model, Prop, Vue, Watch } from 'vue-property-decorator';
import { AutocompleteItem } from './AutocompleteComponent.vue';
import { SelectItem } from './SelectComponent.vue';

type Value = SelectItem[] | SelectItem | AutocompleteItem[] | AutocompleteItem;

@Component({
  name: 'all-selection',
})
export default class SelectComponent extends Vue {
  @Model('selectAllToggle', { type: [String, Number, Array, Object] }) readonly value!: Value;

  @Prop({ type: Array, default: () => [] }) readonly items!: SelectItem[] | AutocompleteItem[];

  get isAllSelected(): boolean {
    const { value, items } = this;

    if (Array.isArray(value)) {
      return value.length === items.length;
    }

    return false;
  }

  get isSomeSelected(): boolean {
    const { value } = this;

    if (Array.isArray(value)) {
      return value.length > 0 && !this.isAllSelected;
    }

    return false;
  }

  get selectAllIcon(): string {
    if (this.isAllSelected) {
      return 'mdi-close-box';
    }

    if (this.isSomeSelected) {
      return 'mdi-minus-box';
    }

    return 'mdi-checkbox-blank-outline';
  }

  selectAllWithToggle(): void {
    if (this.isAllSelected) {
      this.$emit('selectAllToggle', []);
    } else {
      this.$emit('selectAllToggle', this.items);
    }
  }

  @Watch('isAllSelected')
  handleAllSelectedChange(): void {
    this.$nextTick(() => {
      this.$emit('selectAllLogically', this.isAllSelected);
    });
  }
}
</script>
