<template>
  <div>
    <select-component
      v-if="select"
      v-model="innerValue"
      :clearable="clearable"
      :is-required="required"
      :is-disabled="disabled"
      :return-object="isReturnObject"
      :is-multiple="multiple"
      :item-value="itemId"
      :items="options"
      :label="label"
      :no-data-text="notFoundText"
      :placeholder="placeholder"
      :item-text="itemTextToDisplay || 'label'"
      variant="micro"
    />
    <autocomplete-component
      v-else
      v-model="innerValue"
      :clearable="clearable"
      :is-disabled="disabled"
      :is-multiple="multiple"
      variant="micro"
      :loading="isLoading"
      :item-value="itemId"
      :items="options"
      :return-object="isReturnObject"
      :label="label"
      :no-data-text="notFoundText"
      :placeholder="placeholder"
      :required="required"
      :item-text="itemTextToDisplay || 'label'"
      @selectAllLogically="onSelectAllLogically"
      @searchInputUpdate="$emit('searchInputUpdate', $event)"
    />
  </div>
</template>

<script lang="ts">
import { Component, Model, Prop, Vue, Watch } from 'vue-property-decorator';
import nsiList from '@/views/NSI/config';
import moment from 'moment';
import SelectComponent from '@/components/common/inputs/SelectComponent.vue';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import isNumber from 'lodash/isNumber';
import uniqBy from 'lodash/uniqBy';
import { lotPurposesByLotType } from '@/utils/lotPurposesByLotType';

type Params = {
  url?: string;
  params?: object;
  size?: number;
  page?: number;
};

@Component({
  name: 'select-request-component',
  components: { AutocompleteComponent, SelectComponent },
})
export default class SelectRequestComponent extends Vue {
  @Model('selected') value!: any;

  @Prop({ type: String, default: 'Ничего не найдено' }) readonly notFoundText!: string;
  @Prop({ type: String, default: '' }) readonly label!: string;
  @Prop({ type: String, required: false }) readonly placeholder!: string;
  @Prop({ type: String, required: false }) readonly type!: string;
  @Prop({ type: String, required: false, default: '' }) readonly customUrl!: string;
  @Prop({ type: Object, required: false }) readonly lotType!: object;
  @Prop({ type: String, required: false, default: '' }) readonly purposeType!: string;
  @Prop({ type: String, required: false }) readonly storeLotType!: string;

  @Prop({ type: Boolean, default: true }) readonly clearable!: boolean;
  @Prop({ type: Boolean, default: false }) readonly multiple!: boolean;
  @Prop({ type: Boolean, default: false }) readonly disabled!: boolean;
  @Prop({ type: Boolean, default: true }) readonly isActive!: boolean;
  @Prop({ type: Object, default: () => ({}) }) readonly additionalParams!: Record<string, number | string | boolean>;

  @Prop({ type: Boolean, default: false }) readonly required!: boolean;
  @Prop({ type: Boolean, default: false }) readonly select!: boolean;
  @Prop({ type: Boolean, default: false }) readonly isLoading!: boolean;
  @Prop({ type: Boolean, default: false }) readonly isReturnObject!: boolean;
  @Prop({ type: Boolean, default: false }) readonly isId!: boolean;

  @Prop({ type: String, required: false, default: '' }) readonly itemText!: string;
  @Prop({ type: String, required: false, default: 'name' }) readonly itemName!: string;
  @Prop({ type: String, required: false, default: 'id' }) readonly itemId!: string;
  @Prop({ type: String, required: false, default: '' }) readonly itemTextToDisplay!: string;
  @Prop({ type: Boolean, default: false }) readonly preserveData!: boolean;

  @Prop({ type: Array, default: () => [] }) arrayNamesForLabel!: Array<any>;
  @Prop({ type: Array, default: () => [] }) customItems!: Array<any>;

  items: Array<{ id: number; label: string; inn?: number; kpp?: number }> = [];
  isItemsFilteredByActive = this.isActive;
  defaultParams = {
    params: {
      pageable: {
        pageable: {
          pageNumber: 0,
          pageSize: 100,
        },
        sort: [
          {
            property: 'name',
            direction: 'ASC',
          },
        ],
      },
    },
  };

  get params() {
    return { ...this.defaultParams, params: { ...this.defaultParams, ...this.additionalParams } };
  }

  mapFunctions = {
    default: (item) => ({ id: this.getSelectId(item), label: item.name }),
    preserveData: (item) => ({ ...item, label: item[this.itemName] }),
    'nsi-type-product-msh': (item) => ({
      ...item,
      id: parseInt(item.id),
      name: item.name,
      okpd2: item.okpd2,
      label: item.okpd2.name + ` ( ОКПД 2: ${item.okpd2.code}; ТН ВЭД: ${item.tnved.replace(/\s+/g, '')} )`,
    }),
    'nsi-okpd2-msh': (item) => ({
      ...item,
      id: item.id,
      name: item.name,
      code: item.code,
      label: item.name + ` ( ОКПД 2: ${item.code} )`,
      is_actual: item.is_actual,
    }),
    'nsi-okpd2-codes': (item) => ({ id: item.second, code: item.second, label: `${item.first} (${item.second})` }),
    'elevator-service-type': (item) => ({ ...item, label: item[this.itemName] }),
  };

  get storeData(): any[] {
    const typesToIgnoreStoreData = ['nsi-type-product-msh', 'nsi-okpd2-msh', 'nsi-lots-purpose', 'nsi-okpd2-codes'];

    if (typesToIgnoreStoreData.includes(this.type)) return [];
    else if (this.nsiByType.storeGetter) {
      return this.$store.getters[this.nsiByType.storeGetter] ?? [];
    }

    return [];
  }

  get innerValue(): any {
    return this.value;
  }

  set innerValue(value) {
    this.$emit('selected', value);
  }

  get getLotType() {
    return this.storeLotType;
  }

  get nsiByType() {
    return nsiList[this.type];
  }

  @Watch('customItems')
  onChangeItems(): void {
    this.clearOptionsAndAddNewOptionsFromItems();
  }

  onSelectAllLogically(value) {
    this.$emit('onSelectAllLogically', value);
  }

  @Watch('items')
  onChangeNsiItems(value, oldValue): void {
    this.$emit('onChangeNsiItems', {
      prev: oldValue,
      value: value,
      items: this.items,
    });
  }

  @Watch('value')
  onChangeValue(value, oldValue): void {
    this.$emit('onChange', {
      prev: oldValue,
      value: value,
      items: this.items,
    });
  }

  isInnerValueInItems() {
    const valueToFind = this.isReturnObject ? this.innerValue[this.itemId] : this.innerValue;
    return this.items.filter((e) => e[this.itemId] === valueToFind).length;
  }

  checkIfValueIsActual() {
    if (!this.innerValue) return;
    if (!this.isInnerValueInItems()) {
      this.$notify({
        group: 'notifications-m',
        duration: 5000,
        type: 'warning',
        title: `Указанное значение для поля "${this.label}" недоступно для выбора. Пожалуйста, выберите значение из актуальных`,
      });
    }
    this.innerValue = this.isInnerValueInItems() ? this.innerValue : null;
  }

  @Watch('isActive')
  async onIsActiveChange(_value) {
    await this.setItems();

    if (this.type === 'nsi-okpd2-msh') {
      this.checkIfValueIsActual();
    }
  }

  get options() {
    return uniqBy(this.items || [], 'id').filter(({ end_date, start_date }: any) => {
      return (
        (!end_date || this.$moment(end_date, 'DD.MM.YYYY HH:mm').isAfter(this.$moment())) &&
        (!start_date || this.$moment(start_date, 'DD.MM.YYYY HH:mm').isBefore(this.$moment()))
      );
    });
  }

  get isActiveChanged() {
    return this.isActive !== this.isItemsFilteredByActive;
  }

  async setItems() {
    const params = { ...this.params };

    if (this.customItems.length) {
      this.clearOptionsAndAddNewOptionsFromItems();
    }
    if (this.customUrl === '') {
      if ((this.items.length === 0 || this.isActiveChanged) && this.nsiByType) {
        params['url'] = this.nsiByType.apiUrl;
        if (this.lotType) {
          params.params = {
            ...params.params,
            ...this.lotType,
            actual: ['nsi-okpd2-msh', 'nsi-okpd2-codes'].includes(this.type) ? this.isActive : params.params['actual'],
          } as any;
        } else {
          params.params['actual'] = true;
        }
        await this.loadDataAndSetItems('nsi/getList', params);
      } else {
        this.getListForSelect(this.type);
      }
    } else {
      await this.loadDataAndSetItems(this.customUrl, {
        page: 1,
        size: 100,
      });
    }
  }

  async created() {
    await this.setItems();
  }

  async loadDataAndSetItems(url: string, params: Params): Promise<void> {
    if (this.storeData.length) {
      this.filterDataAndConvertArrayByType(this.storeData);

      return;
    }

    let { data, content } = await this.$store.dispatch(url, params);

    switch (this.type) {
      case 'nsi-type-product-msh':
        break;
      case 'nsi-okpd2-msh':
        break;
      case 'nsi-okpd2-codes':
        break;
      case 'nsi-lots-purpose':
        content = lotPurposesByLotType(content, this.purposeType);
        break;
      default:
        if (this.nsiByType.storeSetter) {
          this.$store.commit(this.nsiByType.storeSetter, content);
        }
        break;
    }
    this.filterDataAndConvertArrayByType(data || content);
  }

  getMoment(date) {
    return moment(date, 'DD.MM.YYYY').unix() - moment().unix();
  }

  filterByDates(item) {
    const typesToIgnore = ['nsi-type-product-msh', 'nsi-lots-purpose', 'nsi-okpd2-msh', 'nsi-okpd2-codes'];
    if (!typesToIgnore.includes(this.type)) {
      if (item.endDate && item.startDate)
        return this.getMoment(item.startDate) <= 0 && this.getMoment(item.startDate) >= 0;
      else {
        if (item.startDate) return this.getMoment(item.startDate) <= 0;
        else if (item.endDate) return this.getMoment(item.endDate) >= 0;
        return false;
      }
    }
    return item;
  }

  /**
   * Filter array by end date and start date.
   * Filtered array, will be converted for select-component.
   *
   * @param {Array} array
   * @return void
   */

  filterDataAndConvertArrayByType(array) {
    const typesWithMapFunction = ['nsi-type-product-msh', 'nsi-okpd2-msh', 'nsi-okpd2-codes', 'elevator-service-type'];

    const data = array
      .filter((item) => this.filterByDates(item))
      .map((item) => {
        if (typesWithMapFunction.includes(this.type)) {
          return this.mapFunctions[this.type](item);
        } else {
          if (this.customUrl === 'manufacturers/getList')
            return { id: parseInt(item.id), label: item.name, inn: item.inn };
          return this.preserveData ? this.mapFunctions.preserveData(item) : this.mapFunctions.default(item);
        }
      });

    this.isItemsFilteredByActive = this.isActive;
    if (['nsi-okpd2-msh', 'nsi-okpd2-codes'].includes(this.type)) {
      const filtered = (this.items = this.isActive ? data.filter((e) => e.is_actual === true) : data);
      return (this.items = filtered.sort(
        (a, b) =>
          (a.name || a.label || '').localeCompare(b.name || b.label || '') || (a.code || '').localeCompare(b.code || '')
      ));
    }
    return (this.items = data);
  }

  getSelectId(item) {
    if (this.isId) return item[this.itemId];
    return item.code !== undefined ? parseInt(item.code) : item.id;
  }

  clearOptionsAndAddNewOptionsFromItems() {
    this.items = [];
    let query;
    this.customItems.forEach((v) => {
      if (this.arrayNamesForLabel.length > 0) {
        query = this.arrayNamesForLabel.map((nameArr) => (v[nameArr] === undefined ? nameArr : v[nameArr])).join(' ');
      } else {
        query = v[this.itemText !== '' ? this.itemText : this.itemName];
      }
      this.items.push({ id: isNumber(v[this.itemId]) ? parseInt(v[this.itemId]) : v[this.itemId], label: query });
    });
  }

  // TODO исправить после реализации справочников
  getListForSelect(type) {
    switch (type) {
      case 'gosmonitoring_research_status_id':
      case 'status_id':
        this.items = [
          { id: 1, label: 'Создан' },
          { id: 2, label: 'Подписан' },
          { id: 3, label: 'Аннулирован' },
        ];
        break;
    }
  }
}
</script>
