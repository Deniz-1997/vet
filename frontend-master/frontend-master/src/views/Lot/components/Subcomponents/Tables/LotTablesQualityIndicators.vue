<template>
  <v-col cols="12">
    <lot-table
      v-model="innerValue"
      :empty-text="getCenterTextOfTable"
      :hide-action="false"
      :is-create="isCreate"
      :is-edit="isEdit"
      :is-edit-action="isChangeData"
      :is-show-card-button="false"
      :is-show-delete-button="false"
      :options-for-table="optionsForTable"
      show-case
      is-can-edit
      class_="edit-table-quality"
      title="Потребительские свойства"
      @onShowHintForTable="onShowHintForTable"
    />
    <v-overlay :value="isLoading">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
  </v-col>
</template>

<script lang="ts">
import { Component, Model, Prop, Vue, Watch } from 'vue-property-decorator';
import { QualityIndicatorsVueModel } from '@/models/Lot/QualityIndicators.vue';
import { mixins } from 'vue-class-component';
import { AdditionalMix } from '@/utils/mixins/additional';
import { allFloatNumberMask } from '@/components/common/inputs/mask/number';
import LotTable from '@/views/Lot/components/Subcomponents/Tables/LotTable.vue';
import { loadQualityIndicatorsByParams, mergeQualityIndicators } from '@/utils/qualityIndicators';
import { IndicatorPurposeEnum } from '@/utils/enums/indicatorPurpose.enum';
import { PropType } from 'vue';
import { HistoryEntryModel } from '@/models/Lot/HistoryEntry';
const Icon = require('vue-awesome/components/Icon').default;

@Component({
  name: 'lot-tables-quality-indicators',
  components: { LotTable },
})
export default class LotTablesQualityIndicators extends mixins(AdditionalMix) {
  @Model('change', { type: Array, required: true }) value!: QualityIndicatorsVueModel[];

  @Prop({ type: Array as PropType<HistoryEntryModel[]>, default: () => [] }) versions!: HistoryEntryModel[];

  @Prop({ required: true }) okpd2Code!: string;

  @Prop({ required: true }) purposes!: Array<IndicatorPurposeEnum>;

  @Prop({ type: String, default: null }) countryAlpha!: string | null;

  @Prop({ type: Boolean, default: false }) isEdit!: boolean;

  @Prop({ type: Boolean, default: false }) isCreate!: boolean;

  @Prop({ type: Boolean, default: true }) showValidationOnDetail!: boolean;

  @Prop({ type: Boolean, default: false }) isRestriction!: boolean;

  @Prop({ type: Boolean, default: false }) isDestinationCountryNeeded!: boolean;

  @Prop({ type: Boolean, default: true }) loadPropertiesOnDetail!: boolean;

  isShowHintForTable = false;
  disabled = false;
  editIcon: any;
  ammendsIcon: any;
  isLoading = false;

  get innerCountryAlpha(): string {
    return this.countryAlpha || 'RU';
  }

  @Watch('isEdit', { immediate: true })
  async qualityIndicators(v) {
    if (this.okpd2Code && !this.isCreate && this.loadPropertiesOnDetail && !v) {
      await this.loadIndicatorsProperties();
    }
  }

  async loadIndicatorsProperties() {
    try {
      this.isLoading = true;
      const quality_indicators = await loadQualityIndicatorsByParams({
        okpd2: this.okpd2Code,
        purposes: this.purposes,
        country_alpha2: this.innerCountryAlpha,
      });

      this.innerValue = mergeQualityIndicators(this.innerValue, quality_indicators, [
        'valueTo',
        'valueFrom',
        'type',
        'measure',
      ]);
    } catch (_e) {
      this.$service.notify.push('error', {
        text: 'Ошибка загрузки потребительских свойств',
      });
    } finally {
      this.isLoading = false;
    }
  }

  get isChangeData(): boolean {
    return this.isEdit || this.isCreate;
  }

  get innerValue(): QualityIndicatorsVueModel[] {
    return this.value.sort((a, b) => (a.name || '').localeCompare(b.name || ''));
  }

  set innerValue(value: QualityIndicatorsVueModel[]) {
    this.$emit('change', value);
  }

  get getCenterTextOfTable(): string | undefined {
    this.disabled = !this.isEdit;
    if (this.isEdit) {
      if (!this.okpd2Code) return 'Выберите вид с/х культуры';

      if (this.isDestinationCountryNeeded && !this.countryAlpha) return 'Выберите страну назначения';

      if (this.value.length === 0) return 'Для выбранной культуры нет потребительских свойств';
    } else {
      if (this.value.length === 0) return 'Не добавлены потребительские свойства';
    }
    return '';
  }

  optionValuePrefix(): object {
    return {
      prefix: (model: QualityIndicatorsVueModel | any | undefined): string | undefined => {
        if (model instanceof QualityIndicatorsVueModel || typeof model === 'object') {
          if (model.type === 'D') {
            if (this.checkValueQuality(parseFloat(model.value as string))) return model.measure ?? undefined;
          }
          return undefined;
        }
      },
    };
  }
  optionValuePlaceholder(): object {
    return {
      placeholder: (model: QualityIndicatorsVueModel | any | undefined): string => {
        if (model.type === 'D') {
          if (typeof model !== 'undefined' && (model.value === null || model.value === undefined) && !this.isEdit)
            return 'Не указано';

          if (model.valueFrom === 0 && model.valueTo === 0) return 'Нет ограничений';

          return this.getText(model, 'от').replace(/[^а-я0-9\s]/gi, '');
        }
        return 'Выберите значение';
      },
    };
  }
  getText(model: any, textFrom: string): string {
    if (model.type === 'D') {
      if (model.valueFrom && !model.valueTo) return `${textFrom} <b>${model.valueFrom}</b>`;
      if (!model.valueFrom && model.valueTo) return `${textFrom} <b>0</b> до <b>${model.valueTo}</b>.`;
      if (model.valueFrom && model.valueTo) return `${textFrom} <b>${model.valueFrom}</b> до <b>${model.valueTo}</b>.`;
      return 'Добавить значение. Нет ограничений.';
    }
    return 'Выберите значение';
  }

  optionCustomRenderValue(): object {
    return {
      customRenderValue: (model: QualityIndicatorsVueModel | any | undefined): string | number => {
        const textFrom = 'Добавить значение. От ';
        let text = this.getText(model, textFrom);
        const textReturn = `<p style="cursor: pointer" class=" text--caption red--text text--lighten-1  ma-0">${text}</p>`;
        let val = model.value as string;
        const icon = this.isEdit ? this.editIcon.$el.outerHTML : '';
        const validValueRender = `<span>${model.value} ${model.measure ?? ''} ${icon}</span>`;
        const invalidValueRender = `<span class=" text--caption red--text text--lighten-1  ma-0">${model.value} ${
          model.measure ?? ''
        } ${this.isLoading ? '' : `(${this.getText(model, 'от').replace(/[^а-я0-9\s]/gi, '')})`} ${icon}</span>`;

        if (this.checkFromTo(parseFloat(val), model)) {
          if (this.isRestriction) {
            model.value = parseFloat(model.value);
            return this.checkForAmmends(model) && !this.isChangeData
              ? `<div class="tooltip">${
                  invalidValueRender + this.ammendsIcon.$el.outerHTML
                }<span class="tooltip-text">Внесены изменения</span></div>`
              : invalidValueRender;
          } else {
            model.value = undefined;
            this.$store.commit(
              'errors/setErrorsList',
              this.getText(model, 'Значение должно быть от').replace(/[^а-я0-9\s]/gi, '')
            );
            return textReturn;
          }
        }
        if (!model.value) {
          if (this.isEdit) return textReturn;
          return 'Не выбрано';
        }

        return this.checkForAmmends(model) && !this.isChangeData
          ? `<div class="tooltip">${
              validValueRender + this.ammendsIcon.$el.outerHTML
            }<span class="tooltip-text">Внесены изменения</span></div>`
          : validValueRender;
      },
    };
  }

  titleCustomRenderValue() {
    return {
      customRenderValue: (model: QualityIndicatorsVueModel | any | undefined): string | number => {
        let val = model.value as string;
        if (this.checkFromTo(parseFloat(val), model)) {
          return `<span class=" text--caption red--text text--lighten-1  ma-0"> ${model.name}</span>`;
        } else {
          return `<span> ${model.name}</span>`;
        }
      },
    };
  }

  get optionsForTable(): Array<object> {
    return [
      {
        label: 'Наименование',
        name: 'name',
        controlType: 'text',
        disabled: true,
        style: { width: '70%' },
        ...this.titleCustomRenderValue(),
      },
      {
        label: 'Значение',
        name: 'value',
        mask: allFloatNumberMask,
        autofocus: true,
        disabled: this.disabled,
        restrictions: 'values',
        style: { maxWidth: '250px', textAlign: 'center' },
        ...this.optionValuePrefix(),
        ...this.optionValuePlaceholder(),
        ...this.optionCustomRenderValue(),
        controlType: ({ type }) => (type === 'E' ? 'select' : 'input'),
      },
    ];
  }

  checkFromTo(value, options: any): boolean {
    if (this.isValidation) {
      if (options.valueFrom === 0 && options.valueTo === 0) return false;
      return value < options.valueFrom || value > options.valueTo;
    }
    return false;
  }
  onShowHintForTable() {
    this.isShowHintForTable = !this.isShowHintForTable;
  }

  get isValidation() {
    return this.isEdit || this.isCreate || this.showValidationOnDetail;
  }

  checkValueQuality(value: string | null | number): boolean {
    return value !== null && value !== '' && typeof value !== 'undefined';
  }

  prepareIcon(icon) {
    icon.$mount();
    icon.$el.style.position = 'relative';
    icon.$el.style.top = '2px';
    icon.$el.style.left = '20px';
    icon.$el.style.fill = '#D19B3F';
  }

  created() {
    const ComponentClass = Vue.extend(Icon);
    const VIcon = Vue.extend(Icon);
    this.ammendsIcon = new VIcon({ propsData: { name: 'exclamation-circle' } });

    this.editIcon = new ComponentClass({ propsData: { name: 'pen' } });

    this.prepareIcon(this.ammendsIcon);
    this.ammendsIcon.$el.style.left = '24px';
    this.prepareIcon(this.editIcon);
  }

  get isVersions(): boolean {
    return !!this.versions.length;
  }

  get initialVersion(): HistoryEntryModel | null {
    return this.isVersions ? this.versions[this.versions.length - 1] : null;
  }

  checkForAmmends(data: QualityIndicatorsVueModel): boolean {
    if (!this.isVersions) return false;

    return !!this.initialVersion?.quality_indicators.find(
      (e: QualityIndicatorsVueModel) =>
        e.quality_indicator_id === data.quality_indicator_id && parseInt(e.value as any) !== parseInt(data.value as any)
    );
  }
}
</script>
<style scoped lang="scss">
.edit-table-quality::v-deep {
  .tableHeader {
    .spanHeader:first-child {
      width: 0;
    }
  }

  .tableListRow {
    .spanList {
      line-height: 40px;
      height: 40px;
    }

    .spanList:first-child {
      width: 0;
    }

    .spanList:nth-child(3n) {
      > span {
        display: block;
        position: relative;
      }
    }

    .spanList {
      cursor: pointer;
    }
  }

  .tooltip .tooltip-text {
    opacity: 0;
    transition: all 0.2s ease-in-out;
    background-color: #211f1f9e;
    color: white;
    text-align: center;
    padding: 0 16px;
    border-radius: 6px;
    position: absolute;
    z-index: 1;
    transform: translate(-34%, -40px) scale(0.5, 0.5);
  }

  .tooltip:hover .tooltip-text {
    opacity: 1;
    transform: translate(-34%, -40px) scale(1, 1);
  }
}
</style>
