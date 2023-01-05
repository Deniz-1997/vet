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
            no-data-text="Если пользователя нет в списке, попробуйте уточнить запрос"
            item-value="user_id"
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
                </div>
              </div>
            </template>
          </AutocompleteComponent>
        </span>
      </template>
      {{ tooltipText }}
    </v-tooltip>
  </div>
</template>

<script lang="ts">
import { Component, Vue, Prop, Model } from 'vue-property-decorator';
import isEmpty from 'lodash/isEmpty';
import { Debounce } from '@/utils/global/decorators/method';
import { ISubjectData } from '@/services/models/common';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';

@Component({
  name: 'UserAutocomplete',
  components: { AutocompleteComponent },
})
export default class ManufacturerAutocomplete extends Vue {
  @Model('change', { type: [Object, Array, Number], default: null }) readonly value!: any;
  @Prop({ type: String, default: '' }) readonly label!: string;
  @Prop({ type: Boolean, default: false }) readonly includingClosed!: boolean;
  @Prop({ type: Boolean }) readonly readonly!: boolean;
  @Prop({ type: Boolean }) readonly multi!: boolean;
  @Prop({ type: String, required: false, default: 'Выберите пользователя' }) readonly placeholder!: string;

  @Prop({ type: Boolean, required: false, default: true }) readonly clereables!: boolean;

  @Prop({ type: Boolean, required: false, default: false }) readonly isDisabled!: boolean;
  @Prop({ type: Boolean, required: false, default: false }) readonly returnObject!: boolean;
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
        user_id: this.value,
      };
    }
    return this.value;
  }

  set innerValue(v: any) {
    if (this.returnObject) {
      this.$emit('change', !isEmpty(v) ? v : null);
    } else {
      this.$emit(
        'change',
        (v || []).map(({ user_id }) => user_id)
      );
    }
  }

  get tooltipText() {
    if (!this.showNameInTooltip || !this.value) {
      return !this.isDisabled ? this.placeholder : this.label;
    }

    if (this.returnObject) {
      return this.value.full_name ?? this.value.name;
    }

    return this.options.find((item) => item.user_id === this.value)?.full_name ?? '';
  }

  get options() {
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

  // async created() {
  //   if (this.value) {
  //     const id = this.value?.user_id ?? this.value;
  //     const { data } = await this.$axios.post('/api/security/user/show', { id });
  //     console.log(data);
  //     this.items = [...this.items, data.content];
  //   }
  // }

  normalize(item: any = {}): any {
    return { ...item, full_name: item.full_name };
  }

  @Debounce(1000)
  async onInput(searchString: any) {
    this.isLoading = true;
    this.searchString = searchString?.full_name ?? (searchString || '');
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
    const { data } = await this.$axios.post('/api/security/user/find/', {
      filter,
      pageable: {
        pageNumber: 0,
        pageSize: 15,
      },
      actual: true,
    });
    const list = (data.content || []).map((user) => ({
      ...user,
      name: user.full_name,
    }));
    return list;
    // const list = (data.content || []).map((user) => ({
    // ...manufacturer.subject,
    // subject_data: {
    //   ...manufacturer.subject?.subject_data,
    //   subject_id: manufacturer.subject?.subject_id,
    // },
    // }));
    // if (
    //   this.value &&
    //   !list.find((item) => item.subject_id === (typeof this.value === 'object' ? this.value.subject_id : this.value))
    // ) {
    //   const id = this.value?.subject_id ?? this.value;
    //   list.unshift((await this.$axios.get(`/api/subject/manufacturer/subject/${id}`)).data.subject);
    // }
    // return list;
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
