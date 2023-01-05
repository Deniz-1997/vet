<template>
  <div>
    <v-tooltip top max-width="500" open-delay="500">
      <template #activator="{ on, activatorAttrs }">
        <span v-if="placeholder" v-bind="activatorAttrs" class="hint_span" v-on="on">
          <AutocompleteComponent
            v-model="innerValue"
            :items="options"
            :label="label"
            no-data-text="Если показателя потребительских свойств нет в списке, попробуйте уточнить запрос"
            item-value="id"
            item-text="name"
            :is-disabled="isDisabled"
            :clearable="clereables"
            :placeholder="placeholder"
            return-object
            @onChange="searchString = ''"
            @searchInputUpdate="onInput"
          />
        </span>
      </template>
      {{ placeholder }}
    </v-tooltip>
  </div>
</template>

<script lang="ts">
import { Component, Vue, Prop, Watch, Model } from 'vue-property-decorator';
import isEmpty from 'lodash/isEmpty';
import { Debounce } from '@/utils/global/decorators/method';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';

@Component({
  name: 'QuallityDictionary',
  components: { AutocompleteComponent },
})
export default class QuallityDictionary extends Vue {
  @Model('change', { type: [Object, Array, Number], required: true }) readonly value!: any;
  @Prop({ type: [Object, Array, Number], required: false, default: [] }) readonly itemsList!: any;
  @Prop({ type: String, required: true }) readonly label!: string;
  @Prop({ type: Boolean, default: false }) readonly includingClosed!: boolean;
  @Prop({ type: Boolean }) readonly readonly!: boolean;
  @Prop({ type: Boolean }) readonly multi!: boolean;
  @Prop({ type: String, required: false, default: 'Выберите наименование потребительского свойства' })
  readonly placeholder!: string;

  @Prop({ type: Boolean, required: false, default: false }) readonly clereables!: boolean;

  @Prop({ type: Boolean, required: false, default: false }) readonly isDisabled!: boolean;
  searchString = '';
  isLoading = false;
  temporaryValue: any = null;
  items: any[] = this.itemsList;

  get innerValue() {
    if (this.value) {
      this.temporaryValue = { ...this.value };
    }
    return this.value;
  }

  set innerValue(v: any) {
    const value = !isEmpty(v) ? { id: v.id, code: v.code, name: v.name } : null;

    this.temporaryValue = v;
    this.$emit('change', value);
  }

  get options() {
    return this.items.map((item) => this.normalize(item));
  }

  normalize(item: any = {}): any {
    return { ...item, name: item.name };
  }

  beforeMount() {
    if (this.value) {
      this.items.push(this.value);
    }
  }

  @Debounce(1000)
  async onInput(searchString: any) {
    this.isLoading = true;
    this.searchString = searchString || '';
    if (
      !(
        this.temporaryValue &&
        searchString &&
        searchString.includes(this.temporaryValue.name || this.temporaryValue.short_name)
      ) &&
      this.searchString?.length > 3
    ) {
      await this.fetchList(this.searchString);
    }

    if (!this.searchString) {
      this.items = [];
    }

    this.isLoading = false;
  }

  async fetchDefaultList() {
    return await this.fetchList('');
  }

  async fetchList(filter?: string) {
    const { data } = await this.$axios.post('/api/nci/qualityIndicators', {
      filter,
      pageable: {
        pageNumber: 0,
        pageSize: 15,
      },
      actual: true,
      only_with_limits: false,
    });
    this.items = data.content;
  }

  @Watch('itemsList')
  changeItemsList() {
    this.items = this.itemsList;
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';

.organization-picker {
  &__caption {
    font-size: 11px;
    color: $medium-grey-color;
  }

  &__text {
    font-size: 13px;
  }
}
</style>
