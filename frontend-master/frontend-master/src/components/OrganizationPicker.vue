<template>
  <UiControl tag="span" :name="name" :value="innerValue">
    <AutocompleteComponent
      v-model="innerValue"
      :items="options"
      :label="label"
      item-value="subject_id"
      item-text="short_name"
      :is-disabled="readonly"
      :is-multiple="multi"
      :deletable-chips="!readonly"
      :filter="filter"
      return-object
      chips
      @onChange="searchString = ''"
      @searchInputUpdate="onInput"
      :name="idElement"
    >
      <template #prepend-item>
        <div class="organization-picker__caption px-4 pb-2">
          Если вашей организации нет в списке, попробуйте уточнить запрос или указать ИНН
        </div>
      </template>
      <template #item="{ item, attrs }">
        <div v-bind="attrs" :key="item.subject_id" class="d-flex">
          <UiCheckbox :checked="attrs.inputValue" name="picked" class="mr-2" />
          <div>
            <UiHighlightedText
              v-if="item.name"
              class="organization-picker__text"
              :text="item.name"
              :search="searchString"
              tag="div"
            />
            <span class="organization-picker__caption">
              <span v-if="item.inn">
                ИНН:
                <UiHighlightedText :text="item.inn" :search="searchString" />
              </span>
              <span v-if="item.kpp">
                КПП:
                <UiHighlightedText :text="item.kpp" :search="searchString" />
              </span>
              <span v-if="item.ogrn">
                ОГРН:
                <UiHighlightedText :text="item.ogrn" :search="searchString" />
              </span>
            </span>
          </div>
        </div>
      </template>
    </AutocompleteComponent>
  </UiControl>
</template>

<script lang="ts">
import { Component, Vue, Prop, Model } from 'vue-property-decorator';
import { Debounce, Memoize } from '@/utils/global/decorators/method';
import { oneof } from '@/utils/global/props-validator/oneof';
import { ISubjectItem, ISubjectData, OneOf } from '@/services/models/common';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import isEmpty from 'lodash/isEmpty';

const SortDirection = ['DESC', 'ASC'] as const;
const SortColumn = ['subject.name', 'subject.inn', 'subject.kpp', 'subject.ogrn'] as const;

@Component({
  name: 'OrganizationPicker',
  components: { AutocompleteComponent },
})
export default class OrganizationPicker extends Vue {
  // @Model('change', { type: [Object, Array], required: true }) readonly value!: ISubjectItem | ISubjectItem[];
  @Model('change', { type: [Object, Array], required: true }) readonly value!: any;
  @Prop({ type: String, required: true }) readonly label!: string;
  @Prop({ type: String, required: true }) readonly name!: string;
  @Prop({ type: Boolean, default: false }) readonly includingClosed!: boolean;
  @Prop({ type: Boolean }) readonly readonly!: boolean;
  @Prop({ type: Boolean }) readonly multi!: boolean;
  @Prop({ type: String, default: 'subject.name', validator: oneof(SortColumn) }) readonly sortBy!: OneOf<
    typeof SortColumn
  >;
  @Prop({ type: String, default: 'ASC', validator: oneof(SortDirection) }) readonly sortAs!: OneOf<
    typeof SortDirection
  >;
  @Prop({ type: String, required: false }) readonly idElement!: string;

  items: ISubjectItem[] = [];
  searchString = '';
  loadQueue: Promise<any>[] = [];
  $promiseId!: string;

  get innerValue() {
    return this.value;
  }

  set innerValue(v) {
    this.$emit('change', !isEmpty(v) ? v : []);
  }

  get options() {
    const picked = Array.isArray(this.innerValue) ? this.innerValue : [this.innerValue];
    return [
      ...picked,
      ...this.items.filter(({ subject_id }) => !picked.find((item) => item.subject_id === subject_id)),
    ].map((item) => this.normalize(item?.subject_data || (item as ISubjectData)));
  }

  get filter() {
    return ({ name, inn, ogrn, kpp }: ISubjectData, query = '') => {
      return (
        (name || '').toLowerCase().includes(query.toLowerCase()) ||
        (inn || '').toLowerCase().includes(query.toLowerCase()) ||
        (ogrn || '').toLowerCase().includes(query.toLowerCase()) ||
        (kpp || '').toLowerCase().includes(query.toLowerCase())
      );
    };
  }

  normalize(item: ISubjectData): ISubjectData {
    return { ...item, short_name: item.short_name || item.name };
  }

  @Debounce(2000)
  async onInput(searchString: string) {
    this.searchString = searchString || '';
    let items: ISubjectItem[] = [];
    if (this.searchString?.length > 3) {
      items = await this.fetchList(this.searchString);
    }

    this.items = items;
  }

  async fetchDefaultList() {
    return await this.fetchList('');
  }

  @Memoize()
  async fetchList(filter: string) {
    const { data } = await this.$axios.post('/api/subject/subjects', {
      filter,
      pageable: {
        pageNumber: 0,
        pageSize: 15,
        sort: [{ property: this.sortBy, direction: this.sortAs }],
      },
      actual: !this.includingClosed,
      with_total_count: false,
    });

    return data.content || [];
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
