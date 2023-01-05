<template>
  <div>
    <v-tooltip top max-width="500" open-delay="500">
      <template #activator="{ on, activatorAttrs }">
        <span v-if="placeholder" v-bind="activatorAttrs" class="hint_span" v-on="on">
          <AutocompleteComponent
            v-model="innerValue"
            is-action-block
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
            @onChange="searchString = ''"
            @searchInputUpdate="onInput"
            :name="name"
          >
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
          </AutocompleteComponent>
        </span>
      </template>
      {{ !isDisabled ? placeholder : label }}
    </v-tooltip>
  </div>
</template>

<script lang="ts">
import { Component, Vue, Prop, Model } from 'vue-property-decorator';
import isEmpty from 'lodash/isEmpty';
import { Debounce } from '@/utils/global/decorators/method';
import { oneof } from '@/utils/global/props-validator/oneof';
import { ISubjectItem, ISubjectData, OneOf } from '@/services/models/common';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';

const SortDirection = ['DESC', 'ASC'] as const;
const SortColumn = ['subject.name', 'subject.inn', 'subject.kpp', 'subject.ogrn'] as const;

@Component({
  name: 'SubjectAutocomplete',
  components: { AutocompleteComponent },
})
export default class SubjectAutocomplete extends Vue {
  @Model('change', { type: [Object, Array, Number], default: null }) readonly value!: ISubjectItem;
  @Prop({ type: String, required: false, default: '' }) readonly label!: string;
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
  @Prop({ type: Boolean, required: false, default: false }) readonly isLaboratory!: boolean;

  @Prop({ type: String, required: false, default: '' }) readonly name!: string;

  $promiseId!: string;
  items: ISubjectItem[] = [];
  searchString = '';
  isOpenManufacturesCard = false;
  isLoading = false;
  loadQueue: Promise<any>[] = [];

  get innerValue() {
    if (typeof this.value === 'number') {
      return {
        subject_id: this.value,
      };
    }
    return this.value ? this.value.subject_id : this.value;
  }

  set innerValue(v: any) {
    if (this.isReturnObject) {
      this.$emit('change', !isEmpty(v) ? v : null);
    } else {
      this.$emit('change', !isEmpty(v) ? v.subject_id : null);
    }
  }

  get options() {
    if (this.isLaboratory) {
      return this.items.map((item) => this.normalize(item.subject_data));
    }
    return this.items.map((item) => this.normalize(item));
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
    if (this.value && !isEmpty(this.value)) {
      this.items = [...this.items, this.value];
    }
  }

  pushLoader(promise) {
    this.loadQueue.push(promise);
    const id = Math.random().toString(36).slice(0, 6);
    this.$promiseId = id;

    Promise.all(this.loadQueue).finally(() => {
      if (this.$promiseId === id) {
        this.loadQueue.splice(0, this.loadQueue.length);
      }
    });

    return promise;
  }

  normalize(item: any = {}): any {
    return { ...item, short_name: item.short_name || item.name };
  }

  @Debounce(2000)
  async onInput(searchString: any) {
    this.isLoading = true;
    this.searchString = searchString?.short_name ?? searchString?.name ?? (searchString || '');
    let items: ISubjectItem[] = [...this.items];
    if (this.searchString?.length > 3) {
      items = await this.fetchList(this.searchString);
    }

    this.items = items;
    this.isLoading = false;
  }

  async fetchDefaultList() {
    return await this.fetchList('');
  }

  // eslint-disable-next-line max-lines-per-function
  async fetchList(filter: string) {
    if (this.isLaboratory) {
      const { content } = await this.$store.dispatch('organization/searchOrganization', {
        filter: '',
        subjectType: 'UL',
        actual: true,
        pageable: {
          pageNumber: 0,
          pageSize: 15,
          sort: [{ property: this.sortBy, direction: this.sortAs }],
        },
      });

      const list = (content || []).map((subject) => subject);
      if (this.value && !list.find((item) => item.subject_id === this.value.subject_id)) {
        list.unshift(this.value);
      }
      return list;
    } else {
      const data = await this.pushLoader(
        this.$store.dispatch('organization/getElevatorSubject', {
          filter,
          pageable: {
            pageNumber: 0,
            pageSize: 15,
            sort: [{ property: this.sortBy, direction: this.sortAs }],
          },
          actual: !this.includingClosed,
          with_total_count: false,
        })
      );
      const list = (data.content || []).map((subject) => subject);
      if (this.value && !list.find((item) => item.subject_id === this.value.subject_id)) {
        list.unshift(this.value);
      }
      return list;
    }
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
