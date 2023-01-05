<template>
  <UiForm
    :rules="rules"
    :messages="messages"
    class="nsi-form"
    @validation="(item) => (isValid = item.isValid)"
    @submit="onSubmit"
  >
    <v-row>
      <v-col cols="12">
        <UiControl v-if="modal.includes('okpd2')" name="okpd2code" :value="form.okpd2_id" class="reason">
          <SelectComponent
            v-model="form.okpd2_id"
            :items="additionalData"
            item-value="id"
            item-text="name"
            label="Код ОКПД 2"
            :is-disabled="isEdit"
          />
        </UiControl>
        <UiControl v-if="isOKPD2" name="okpd2code" :value="form.code" class="reason">
          <InputComponent
            v-model="form.code"
            placeholder="Введите код"
            label="Код ОКПД 2"
            :disabled="!!(item && item.code)"
          />
        </UiControl>
        <UiControl v-if="modal.includes('tnved')" name="tnved_id" :value="form.tnved_id" class="reason">
          <SelectComponent
            v-model="form.tnved_id"
            :items="catalogTnved"
            item-value="id"
            item-text="name"
            label="Код ТН ВЭД"
            :is-disabled="isEdit"
          />
        </UiControl>
        <UiControl v-if="modal.includes('tnved_number')" name="code" :value="form.code" class="reason">
          <InputComponent
            v-model="form.code"
            placeholder="Введите код"
            label="Код ТН ВЭД"
            :disabled="!!(item && item.code)"
          />
        </UiControl>
        <UiControl v-if="modal.includes('name')" name="name" :value="form.name" class="reason">
          <InputComponent v-model="form.name" placeholder="Введите наименование" label="Наименование" />
        </UiControl>
        <UiControl v-if="modal.includes('symbol')" name="symbol" :value="form.symbol" class="reason">
          <InputComponent v-model="form.symbol" label="Условное обозначение" placeholder="Введите обозначение" />
        </UiControl>
        <UiControl v-if="modal.includes('code')" name="code" :value="form.code" class="reason">
          <InputComponent v-model="form.code" placeholder="Введите обозначение" label="Кодовое обозначение" />
        </UiControl>
        <template v-if="activeNsi === 'nsi-quality-indicators-limit'">
          <v-col cols="12">
            <UiControl name="quality_indicators_id" :value="form.quality_indicators_id" class="reason">
              <quallity-dictionary
                v-model="form.quality_indicators_id"
                return-object
                item-value="id"
                item-text="name"
                label="Наименование потребительского свойства"
                small-chips
                clereables
              />
            </UiControl>
          </v-col>
          <v-col cols="12">
            <UiControl name="purpose" :value="form.purpose" class="reason">
              <autocomplete-tooltip-component
                v-model="form.purpose"
                :items="indicatorPurposeList"
                item-value="id"
                item-text="name"
                label="Назначение потребительского свойства"
                placeholder="Выберите назначение потребительского свойства"
                clereables
              />
            </UiControl>
          </v-col>
          <v-col cols="12">
            <UiControl name="okpd2" :value="form.okpd2" class="reason">
              <okpd-autocomplete
                v-model="form.okpd2"
                return-object
                item-value="id"
                item-text="name"
                label="Вид с/х культуры"
                small-chips
                clereables
              />
            </UiControl>
          </v-col>
          <v-col cols="12">
            <UiControl name="country" :value="form.country" class="reason">
              <country-dictionary
                v-model="form.country"
                return-object
                label="Страна"
                small-chips
                placeholder="Выберите страну"
                clereables
              />
            </UiControl>
          </v-col>
        </template>
      </v-col>
    </v-row>

    <v-row v-if="isOKPD2">
      <v-col cols="12" class="d-flex flex-wrap">
        <UiCheckbox
          id="is_grain"
          v-model="form.is_grain"
          name="is_grain"
          label="Зерно"
          class="mr-4"
          @input="selectGrain"
        />
        <UiCheckbox
          id="is_product"
          v-model="form.is_product"
          name="is_product"
          label="Продукт переработки зерна"
          @input="selectProduct"
        />
      </v-col>
    </v-row>

    <!-- <div class="reason" v-if="modal.includes('measureName')">
      <div class="label">Код единицы измерения</div>
      <SelectComponent :items="additionalData" v-model="form.measureId" />
    </div> -->

    <UiControl v-if="modal.includes('valueFrom')" name="valueFrom" :value="form.valueFrom" class="reason">
      <InputComponent v-model="form.valueFrom" placeholder="Введите значение" label="Диапазон допустимых значений с" />
    </UiControl>
    <UiControl v-if="modal.includes('valueTo')" name="valueTo" :value="form.valueTo" class="reason">
      <InputComponent v-model="form.valueTo" label="Диапазон допустимых значений по" placeholder="Введите значение" />
    </UiControl>

    <v-row>
      <v-col cols="12" md="6" class="pl-0">
        <UiControl name="startDate" :value="form.start_date" class="period">
          <UiDateInput
            v-model="form.start_date"
            class="datePicker"
            format="dd.MM.yyyy HH:mm"
            output-format="dd.MM.yyyy HH:mm"
            :limit-from="$moment().add(-1, 'd').toDate()"
            :disabled="isEdit"
            label="Дата начала"
          />
        </UiControl>
      </v-col>
      <v-col cols="12" md="6" class="pr-0">
        <UiControl name="endDate" :value="form.end_date" class="period">
          <UiDateInput
            v-model="form.end_date"
            class="datePicker"
            format="dd.MM.yyyy HH:mm"
            output-format="dd.MM.yyyy HH:mm"
            label="Дата окончания"
          />
        </UiControl>
      </v-col>
    </v-row>

    <v-row class="buttons mt-6">
      <v-col cols="12" class="d-flex justify-end pb-0">
        <DefaultButton title="Отменить" @click="$emit('close')" />
        <DefaultButton variant="primary" title="Сохранить" :disabled="!isValid || isLoading" type="submit" />
      </v-col>
    </v-row>
  </UiForm>
</template>

<script lang="ts">
import { AxiosError } from 'axios';
import moment from 'moment';
import cloneDeep from 'lodash/cloneDeep';
import isEqual from 'lodash/isEqual';
import { Component, Prop, Vue } from 'vue-property-decorator';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import SelectComponent from '@/components/common/inputs/SelectComponent.vue';
import { timeMask } from '@/components/common/inputs/mask/time';
import AutocompleteTooltipComponent from '@/components/common/inputs/AutocompleteTooltipComponent.vue';
import LabelComponent from '@/components/common/Label/Label.vue';
import CountryDictionary from '@/components/common/Dictionary/Country/Country.vue';
import QuallityDictionary from '@/components/common/Dictionary/QuallityIndicators/QuallityIndicators.vue';
import OkpdAutocomplete from '@/components/common/Dictionary/OkpdAutocomplete/OkpdAutocomplete.vue';
import { TAdditionalDataItem } from '../NsiCard/NsiCard.types';
import { IAddressCountryItem, IDictionaryNode } from '@/services/models/common';

type NsiQualityIndicatorsLimitFilter = {
  okpd2?: IDictionaryNode;
  country?: IAddressCountryItem;
  quality_indicators_id?: IDictionaryNode;
  purpose?: IDictionaryNode;
};

const mappers = {
  'nsi-tnved': ({code, name, start_date, end_date,}) => ({
    code,
    name,
    start_date,
    end_date,
  }),
  'nsi-type-product': ({ tnved_id, name, start_date, end_date, okpd2_id }) => ({
    name,
    start_date,
    end_date,
    tnved_id,
    okpd2_id,
  }),
  'nsi-quality-indicators-limit': ({
    start_date,
    end_date,
    valueTo,
    valueFrom,
    okpd2,
    country,
    quality_indicators_id,
    purpose,
    id,
  }) => ({
    start_date,
    end_date,
    min_value: valueFrom,
    max_value: valueTo,
    okpd2,
    country,
    quality_indicators_id: quality_indicators_id?.id,
    purpose,
    id,
  }),
  default: (data) => {
    const commonData = {
      ...data,
    };

    if (data.parent) {
      return {
        ...commonData,
        parent_code: data.parent.code,
      };
    }

    if (data.okpd2) {
      return {
        ...commonData,
        okpd2: {
          id: data.okpd2.id ? data.okpd2.id : Number(data.okpd2),
        },
      };
    }

    if (data.measureId) {
      return {
        ...commonData,
        measureId: data.measureId.value,
        purpose: data.purposeId.value,
      };
    }

    return commonData;
  },
};

export const mapForm = (data, type) => {
  return (mappers[type] || mappers.default)(data);
};

@Component({
  name: 'modal-action-nsi',
  components: {
    DefaultButton,
    InputComponent,
    SelectComponent,
    AutocompleteTooltipComponent,
    LabelComponent,
    CountryDictionary,
    QuallityDictionary,
    OkpdAutocomplete,
  },
})
export default class ActionsNsi extends Vue {
  form: any = {
    start_date: '',
    end_date: '',
    is_grain: false,
    is_product: false,
  };
  additionalData: any[] = [];
  additionalDataSecond: any[] = [];
  catalogTnved: any[] = [];
  selectTime = '';
  mask: any = {};
  isEdit = false;
  isValid = true;
  isLoading = false;
  delete_old = false;
  okpdData: any[] = [];
  originCode = '';
  indicatorPurposeList: TAdditionalDataItem[] = [];

  @Prop(String) readonly action!: string;
  @Prop(Object) readonly item!: any;
  @Prop(Array) readonly modal!: any;
  @Prop(String) readonly activeUrl!: string;
  @Prop(String) readonly createUrl?: string;
  @Prop(String) readonly activeNsi!: string;
  @Prop(String) readonly additionalApiUrl!: string;
  @Prop(String) readonly additionalApiUrlSecond!: string;
  @Prop(String) readonly catalogTnvedApiUrl!: string;
  @Prop(String) readonly grainUrl!: string;
  @Prop(String) readonly indicatorPurposeUrl!: string;
  @Prop(String) readonly quallityIndicatorApi!: string;
  @Prop({ type: Object, required: false, default: {} }) readonly filterValues!: NsiQualityIndicatorsLimitFilter;

  private get actions() {
    return {
      add: this.add,
      edit: this.edit,
    };
  }

  get rules() {
    const common: any = {
      quality_indicators_id: this.activeNsi === 'nsi-quality-indicators-limit' && 'required',
      purpose: this.activeNsi === 'nsi-quality-indicators-limit' && 'required',
      okpd2: this.activeNsi === 'nsi-quality-indicators-limit' && 'required',
      country: this.activeNsi === 'nsi-quality-indicators-limit' && 'required',
      name: this.activeNsi !== 'nsi-quality-indicators-limit' && this.modal.includes('name') && 'required',
      startDate: 'required',
      tnved_id: [this.modal.includes('tnved') && 'required'],
      symbol: [this.modal.includes('symbol') && 'required'],
      code: [this.modal.includes('code') && 'required'],
      valueFrom: [this.modal.includes('valueFrom') && 'required'],
      valueTo: [this.modal.includes('valueTo') && 'required'],
    };

    if (this.modal.includes('tnved_number')) {
      common.code = ['required', {regex: '/^\\d+$/'}];
    }

    if (this.modal.includes('okpd2')) {
      common.okpd2code = 'required';
    }

    if (this.isOKPD2) {
      common.okpd2code = ['required', 'regex:/^(\\d{1,3}\\.?){1,4}$/'];
    }

    return common;
  }

  get messages() {
    return {
      'after_or_equal.startDate': 'Дата начала не может быть раньше даты окончания действия предыдущей версии',
    };
  }

  get isOKPD2() {
    return this.activeUrl === '/api/nci/okpd2' && this.activeNsi !== 'nsi-type-product';
  }

  async beforeMount() {
    if (this.additionalApiUrl || this.activeNsi === 'nsi-quality-indicators-limit') {
      this.getNsiList();
    }

    if (this.item) {
      this.form = cloneDeep(this.item);
      if (this.activeNsi === 'nsi-quality-indicators-limit') {
        this.form = {
          ...this.form,
          valueFrom: this.form.min_value,
          valueTo: this.form.max_value,
          quality_indicators_id: {
            id: this.form.quality_indicators_id,
            name: this.form.quality_indicator_name,
          },
        };
        const { data } = await this.$axios.post('/api/nci/indicatorPurpose', { actual: true });
        this.indicatorPurposeList = data.content;
      }
      if (this.isOKPD2 && this.form.code && this.form.end_date) {
        this.originCode = this.form.code;
      }
    }
    this.isEdit = this.action === 'edit';
    this.mask = timeMask;

    // значения фильтров автоподставляются в форме создания
    if (this.activeNsi === 'nsi-quality-indicators-limit' && this.action === 'add') {
      this.form = {
        ...this.form,
        ...this.filterValues,
      };
    }
  }

  // eslint-disable-next-line max-lines-per-function
  async getNsiList(value?: string) {
    if (this.additionalApiUrl) {
      const { content } = await this.$store.dispatch('nsi/getList', {
        url: this.additionalApiUrl,
        params: {
          actual: true,
          filter: value ? value : '',
          pageable: {
            sort: [{ direction: 'ASC', property: 'name' }],
          },
        },
      });

      if (this.modal.includes('okpd2') || this.modal.includes('okpd2-text')) {
        this.additionalData = content.map((item) => ({
          ...item,
          id: item.okpd2.id,
          name: `${item.okpd2.name} - ${item.code}`,
        }));
      }
    }
    if (this.catalogTnvedApiUrl) {
      const { content } = await this.$store.dispatch('nsi/getList', {
        url: this.catalogTnvedApiUrl,
        params: {
          actual: true,
          filter: value ? value : '',
          pageable: {
            sort: [{ direction: 'ASC', property: 'name' }],
          },
        },
      });

      this.catalogTnved = content.map((item) => ({
        id: item.id,
        name: `${item.name} - ${item.code}`,
      }));
    }
    if (this.additionalApiUrlSecond) {
      const res = await this.$store.dispatch('nsi/getList', {
        url: this.additionalApiUrlSecond,
        params: { actual: true },
      });

      return res.data.filter((item) => {
        return !item.end_date || moment(item.end_date, 'DD.MM.YYYY hh:mm') > moment();
      });
    }
    if (this.activeNsi === 'nsi-quality-indicators-limit') {
      const { data } = await this.$axios.post('/api/nci/indicatorPurpose', { actual: true });
      this.indicatorPurposeList = data.content.filter((item) => {
        return !this.form.purpose?.some((item2) => item2.id === item.id);
      });
      this.indicatorPurposeList.push(...(this.form.purpose || []));
    }
  }

  selectGrain() {
    this.form.is_product = false;
  }

  selectProduct() {
    this.form.is_grain = false;
  }

  async add() {
    if (this.isEdit && !this.item.end_date) {
      await this.edit({ ...this.item, end_date: this.$moment().format('DD.MM.YY hh:mm') });
    }
    this.form = await this.$store.dispatch('nsi/create', {
      url: this.createUrl || this.activeUrl,
      data: mapForm(this.form, this.activeNsi),
    });
  }

  async edit(form?: any) {
    this.form = await this.$store.dispatch('nsi/update', {
      url: this.createUrl || this.activeUrl,
      data: mapForm(form || this.form, this.activeNsi),
    });
  }

  async onSubmit() {
    if (isEqual(this.form, this.item)) {
      this.$emit('close');
      return this.$emit('save');
    }

    if (this.$moment().isSame(this.$moment(this.form.start_date, 'DD.MM.YYYY hh:mm'), 'd')) {
      this.form.start_date = this.$moment().format('DD.MM.YYYY hh:mm');
    }

    if (this.$moment().isSame(this.$moment(this.form.end_date, 'DD.MM.YYYY hh:mm'), 'd')) {
      this.form.end_date = this.$moment().format('DD.MM.YYYY hh:mm');
    }

    this.isLoading = true;
    const handler = this.actions[this.action];

    if (handler) {
      try {
        await handler();
        this.$emit('close');
        this.$emit('save');
      } catch (err) {
        if ((err as unknown as AxiosError)?.response?.status !== 400) {
          throw err;
        }
      }
    }

    this.isLoading = false;
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';

.nsi-form {
  .row,
  .col {
    margin: 0;
    padding-left: 0;
    padding-right: 0;
  }
}

.v-card__actions {
  padding: 0;
}

.label {
  color: $input-border-color;
  margin-bottom: 5px;
  font-size: 13px;
  line-height: 16px;
}

.lineChoose {
  margin-top: 20px;
}

.spanChoose {
  text-decoration: underline;
  margin-right: 15px;
  font-size: 16px;
  color: $medium-grey-color;
  cursor: pointer;
}

.baseInformation {
  margin-top: 25px;

  @include respond-to('medium') {
    margin-top: 15px;
  }

  @include respond-to('small') {
    margin-top: 10px;
  }
}

.description {
  color: $black-color;
  padding: 20px 0;
  text-align: center;
  font-size: 16px;
  font-weight: 700;
}

.reason,
.period {
  margin-bottom: 18px;
}

.form-input {
  border: 1px solid $input-border-color;
  border-radius: 3px;
  outline: none;
  height: 40px;
  color: $black-color;
  font-size: 14px;
  line-height: 16px;
  margin: 0;
  padding: 0 10px;
  width: 100%;
}

.buttons {
  display: flex;
  justify-content: flex-end;
}

.checkbox {
  cursor: pointer;
  height: 16px;
  position: relative;
  display: flex;
  align-items: center;
  width: 100%;

  [type='checkbox'] {
    position: absolute;
    opacity: 0;
    width: 100%;
    cursor: pointer;
    height: 100%;
    z-index: 1;
  }

  &__icon {
    align-items: center;
    justify-content: center;
    background: $check-bg;
    display: flex;
    height: 16px;
    width: 16px;
    border: 1px solid $input-border-color;
    border-radius: 4px;

    img {
      width: 9px;
      display: block;
      opacity: 0;
    }
  }

  [type='checkbox']:checked {
    & + .checkbox__icon {
      background: $gold-light-color;
      border-color: $gold-light-color;

      img {
        opacity: 1;
      }
    }
  }
}
</style>
