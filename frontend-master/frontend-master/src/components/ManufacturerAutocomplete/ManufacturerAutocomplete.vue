<template>
  <div>
    <v-tooltip top max-width="500" open-delay="500">
      <template #activator="{ on, activatorAttrs }">
        <span v-if="placeholder" v-bind="activatorAttrs" class="hint_span" v-on="on">
          <AutocompleteComponent
            v-model="innerValue"
            :is-action-block="isActionBlock"
            :items="options"
            :label="label"
            no-data-text="Если вашей организации нет в списке, попробуйте уточнить запрос"
            item-value="subject_id"
            item-text="name"
            :is-disabled="isDisabled"
            :clearable="clereables"
            :placeholder="placeholder"
            :filter="filter"
            return-object
            :name="idElement"
            @onChange="searchString = ''"
            @searchInputUpdate="onInput"
          >
            <!-- <template #prepend-item>
              <div class="organization-picker__caption px-4 pb-2">
                Если вашей организации нет в списке, попробуйте уточнить запрос или указать ИНН
              </div>
            </template> -->

            <template #item="{ item, attrs }">
              <div v-bind="attrs" :key="item.subject_id" class="d-flex">
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

            <template #action-item>
              <added-counterparty @showModal="onAddedManufacturesCard" @close="onCloseManufacturesCard" />
            </template>
          </AutocompleteComponent>
        </span>
      </template>
      {{ tooltipText }}
    </v-tooltip>
    <manufacturers-card
      v-if="isOpenManufacturesCard"
      v-model="isOpenManufacturesCard"
      @close="onCloseManufacturesCard"
    />
  </div>
</template>

<script lang="ts">
import { Component, Vue, Prop, Model } from 'vue-property-decorator';
import isEmpty from 'lodash/isEmpty';
import { Debounce } from '@/utils/global/decorators/method';
import { oneof } from '@/utils/global/props-validator/oneof';
import { ISubjectData, OneOf } from '@/services/models/common';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import AddedCounterparty from '@/components/AddedCounterparty/AddedCounterparty.vue';
import ManufacturersCard from '@/components/Manufacturers/ManufacturersEdit/ManufacturersCard.vue';

const SortDirection = ['DESC', 'ASC'] as const;
const SortColumn = ['subject.name', 'subject.inn', 'subject.kpp', 'subject.ogrn'] as const;

@Component({
  name: 'ManufacturerAutocomplete',
  components: { AutocompleteComponent, AddedCounterparty, ManufacturersCard },
})
export default class ManufacturerAutocomplete extends Vue {
  @Model('change', { type: [Object, Array, Number], default: null }) readonly value!: any;
  @Prop({ type: String, required: true }) readonly label!: string;
  @Prop({ type: Boolean, default: false }) readonly includingClosed!: boolean;
  @Prop({ type: Boolean }) readonly readonly!: boolean;
  @Prop({ type: Boolean }) readonly multi!: boolean;
  @Prop({ type: String, default: 'subject.name', validator: oneof(SortColumn) }) readonly sortBy!: OneOf<
    typeof SortColumn
  >;
  @Prop({ type: String, default: 'ASC', validator: oneof(SortDirection) }) readonly sortAs!: OneOf<
    typeof SortDirection
  >;
  @Prop({ type: String, required: false, default: 'Выберите производителя' }) readonly placeholder!: string;

  @Prop({ type: Boolean, required: false, default: true }) readonly clereables!: boolean;

  @Prop({ type: Boolean, required: false, default: false }) readonly isDisabled!: boolean;
  @Prop({ type: Boolean, required: false, default: false }) readonly isReturnObject!: boolean;
  @Prop({ type: Boolean, required: false, default: true }) readonly isActionBlock!: boolean;
  @Prop({ type: String, required: false }) readonly idElement!: string;
  /** флаг: отображкние в подсказке названия организации, когда поле заполнено */
  @Prop({ type: Boolean, required: false, default: false }) readonly showNameInTooltip!: boolean;

  items: any[] = [];
  searchString = '';
  isOpenManufacturesCard = false;
  isLoading = false;

  get innerValue() {
    if (typeof this.value === 'number') {
      return {
        subject_id: this.value,
      };
    }
    return this.value;
  }

  set innerValue(v: any) {
    if (this.isReturnObject) {
      this.$emit('change', !isEmpty(v) ? v : null);
    } else {
      this.$emit('change', !isEmpty(v) ? v.subject_id : null);
    }
  }

  get tooltipText() {
    if (!this.showNameInTooltip || !this.value) {
      return !this.isDisabled ? this.placeholder : this.label;
    }

    if (this.isReturnObject) {
      return this.value.short_name ?? this.value.name;
    }

    return this.options.find((item) => item.subject_id === this.value)?.short_name ?? '';
  }

  get options() {
    return this.items.map((item) => (item ? this.normalize(item.subject_data) : null));
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

  async created() {
    if (this.value) {
      const id = this.value?.subject_id ?? this.value;
      const { data } = await this.$axios.get(`/api/subject/manufacturer/subject/${id}`);
      this.items = [...this.items, data.subject];
    }
  }

  normalize(item: any = {}): any {
    return { ...item, short_name: item.short_name || item.name };
  }

  @Debounce(4000)
  async onInput(searchString: any) {
    this.isLoading = true;
    this.searchString = searchString?.short_name ?? searchString?.name ?? (searchString || '');
    let items: any[] = [...this.items];
    if (this.searchString?.length > 3) {
      items = await this.fetchList(this.searchString);
    }

    this.items = items;
    this.isLoading = false;
  }

  async fetchDefaultList() {
    return await this.fetchList('');
  }

  async fetchList(filter: string) {
    const { data } = await this.$axios.post('/api/subject/manufacturer/manufacturers/search', {
      filter,
      pageable: {
        pageNumber: 0,
        pageSize: 15,
        sort: [{ property: this.sortBy, direction: this.sortAs }],
      },
      actual: !this.includingClosed,
      with_total_count: false,
    });
    const list = (data.content || []).map((manufacturer) => ({
      ...manufacturer.subject,
      subject_data: {
        ...manufacturer.subject?.subject_data,
        subject_id: manufacturer.subject?.subject_id,
      },
    }));
    if (
      this.value &&
      !list.find((item) => item.subject_id === (typeof this.value === 'object' ? this.value.subject_id : this.value))
    ) {
      const id = this.value?.subject_id ?? this.value;
      list.unshift((await this.$axios.get(`/api/subject/manufacturer/subject/${id}`)).data.subject);
    }
    return list;
  }

  onAddedManufacturesCard() {
    this.isOpenManufacturesCard = true;
  }

  async onCloseManufacturesCard() {
    this.isOpenManufacturesCard = false;
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
