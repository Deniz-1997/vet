<template>
  <UiControl :name="name" :value="innerValue">
    <AutocompleteComponent
      v-model="innerValue"
      :items="items"
      item-value="role_id"
      item-text="name"
      :is-disabled="readonly"
      :is-multiple="multi"
      :deletable-chips="!readonly"
      :filter="() => true"
      return-object
      chips
      @searchInputUpdate="onInput"
    >
      <template #label>
        <span class="d-flex align-center">
          <label-component :label="label" />
          <v-icon class="ml-1 mb-2" small @click="isModalShow.roles = true">mdi-progress-question</v-icon>
        </span>
      </template>
      <RoleCardModal :id="roleIds" v-model="isModalShow.roles" :title="label" />
    </AutocompleteComponent>
  </UiControl>
</template>

<script lang="ts">
import { Component, Vue, Prop, Model } from 'vue-property-decorator';
import { Debounce, Memoize } from '@/utils/global/decorators/method';
import { oneof } from '@/utils/global/props-validator/oneof';
import { OneOf } from '@/services/models/common';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import RoleCardModal from '@/components/Administration/Roles/RoleCardModal.vue';

const SortDirection = ['DESC', 'ASC'] as const;
const SortColumn = ['description', 'role'] as const;

@Component({
  name: 'OrganizationPicker',
  components: { AutocompleteComponent, RoleCardModal },
})
export default class OrganizationPicker extends Vue {
  @Model('change', { type: [Object, Array], required: true }) readonly value!: any | any[];
  @Prop({ type: String, required: true }) readonly label!: string;
  @Prop({ type: String, required: true }) readonly name!: string;
  @Prop({ type: Boolean, default: false }) readonly includingClosed!: boolean;
  @Prop({ type: Boolean }) readonly readonly!: boolean;
  @Prop({ type: Boolean }) readonly multi!: boolean;
  @Prop({ type: String, default: 'description', validator: oneof(SortColumn) })
  readonly sortBy!: OneOf<typeof SortColumn>;
  @Prop({ type: String, default: 'ASC', validator: oneof(SortDirection) })
  readonly sortAs!: OneOf<typeof SortDirection>;

  isModalShow = { roles: false };
  items: any[] = [];
  searchString = '';

  get innerValue() {
    return Array.isArray(this.value) ? this.value : [this.value];
  }

  set innerValue(v) {
    this.$emit('change', v);
  }

  get roleIds() {
    return this.innerValue.map(({ id }) => id);
  }

  async mounted() {
    this.items = await this.fetchDefaultList();
  }

  @Debounce()
  async onInput(searchString: string) {
    this.searchString = searchString || '';
    if (searchString?.length > 2) {
      this.items = await this.fetchList(searchString);
    } else {
      this.items = await this.fetchDefaultList();
    }
  }

  @Memoize()
  async fetchDefaultList() {
    const list = await this.fetchList('');
    return [...this.innerValue, ...list.filter(({ id }) => this.innerValue.find((item) => item.id === id))];
  }

  @Memoize()
  async fetchList(filter: string) {
    const { data } = await this.$service.roles.find({
      filter,
      pageable: {
        pageNumber: 0,
        pageSize: 20,
        sort: [{ property: this.sortBy, direction: this.sortAs }],
      },
      actual: !this.includingClosed,
    });

    return data;
  }
}
</script>
