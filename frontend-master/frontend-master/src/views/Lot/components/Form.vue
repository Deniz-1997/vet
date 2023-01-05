<template>
  <v-row>
    <v-col v-show="!isDetail" cols="12">
      <text-component class="title d-flex align-center" variant="span">{{ name }}</text-component>
    </v-col>

    <template v-if="model.historyVersions.length > 1 && !isAmmend && !hideAmmendsMenu">
      <v-col cols="3">
        <select-request-component
          v-model="model.selectedHistoryVersionId"
          :custom-items="model.historyVersions"
          item-text="text"
          label="Версия документа"
          placeholder="Выберите версию"
          :clearable="false"
        />
      </v-col>

      <v-col cols="4">
        <input-component
          label="Основание для внесения исправлений"
          disabled
          :value="model.selectedHistoryVersion ? model.selectedHistoryVersion.reason : '-'"
        />
      </v-col>
    </template>

    <v-col v-if="isAmmend" cols="4">
      <input-component
        v-model="model.ammendReason"
        label="Основание для внесения исправлений"
        placeholder="Укажите основание для внесения исправлений"
      />
    </v-col>

    <v-col cols="12">
      <v-row>
        <v-col v-show="isElevatorOrHasOwnerIdModel" cols="6" lg="6" md="6" xl="3">
          <ManufacturerAutocomplete
            :key="model.owner_id"
            v-model="model.owner_id"
            label="Владелец партии"
            placeholder="Выберите владельца партии"
            clereables
            show-name-in-tooltip
            :is-disabled="type === LotType.ANOTHER_BATCH_GRAIN || isDisabledElement"
          />
        </v-col>

        <v-col cols="6" lg="3" md="3" xl="2">
          <v-tooltip bottom>
            <template #activator="{ on, attrs }">
              <div v-bind="attrs" v-on="on">
                <UiDateInput v-model="model.enter_date" :format="'DD.MM.YYYY'" disabled label="Дата" />
              </div>
            </template>
            <span>Дата формирования партии в системе</span>
          </v-tooltip>
        </v-col>

        <slot
          :disabled="isDisabledCreateAndEndDate || isDisabledElement"
          :model="model"
          :new-key="keyDateCreate"
          name="date-create"
        />
        <slot :disabled="isDisabledElement" :model="model" name="date-lot" />

        <v-col cols="6" lg="3" md="3" xl="2">
          <WeightInput
            v-model="model.amount_kg_mask"
            :placeholder="isDisabledAmountKg ? 'Масса формируется исходя из партий' : 'Масса, кг'"
            :disabled="isDisabledAmountKg || isDisabledElement"
            :validate-input="!isDisabledAmountKg"
            @input="(v) => (model.amount_kg = v)"
            class="mr-3"
          />
        </v-col>

        <v-col cols="6" lg="3" md="6" xl="2">
          <input-component
            v-show="isDisabledElement || isImported"
            :value="model.objects.purpose ? model.objects.purpose.name : ''"
            label="Назначение"
            placeholder="Выберите назначение"
            disabled
          />

          <select-request-component
            v-show="!(isDisabledElement || isImported)"
            :key="newKeyImported"
            v-model="model.objects.purpose"
            :disabled="isDisabledElement || isImported"
            :purpose-type="purposeType"
            is-return-object
            preserve-data
            item-text="name"
            label="Назначение"
            placeholder="Выберите назначение"
            type="nsi-lots-purpose"
          />
        </v-col>

        <v-col cols="6" lg="3" md="6" xl="2">
          <select-request-component
            v-model="model.target_id"
            :disabled="isDisabledElement || isFromGosmonitoring"
            label="Цель использования"
            placeholder="Выберите цель использования"
            select
            type="nsi-lots-target"
          />
        </v-col>
        <v-col cols="12" lg="6" md="6" xl="4">
          <autocomplete-priority-address
            v-show="!isDisabledElement"
            v-model="model.current_location_id"
            label="Местоположение"
            :is-disabled="isDisabledAddress || isDisabledElement"
            placeholder="Выберите местоположение"
          />
          <input-component
            v-show="isDisabledElement"
            v-model="model.objects.current_location.address"
            :disabled="isDisabledAmountKg || isDisabledElement"
            label="Местоположение"
            type="text"
          />
        </v-col>
        <v-col v-show="type === 'imported' || model.type === 'IMPORTED'" cols="12" lg="6" md="6" xl="4">
          <autocomplete-priority-address
            v-show="!isDisabledElement"
            v-model="model.origin_location_id"
            label="Происхождение"
            :is-disabled="isDisabledElement"
            placeholder="Выберите происхождение"
          />
          <input-component
            v-show="isDisabledElement"
            :value="originLocationAddress"
            :disabled="isDisabledAmountKg || isDisabledElement"
            label="Происхождение"
            type="text"
          />
        </v-col>
        <v-col cols="4" lg="4" md="4" xl="4">
          <slot
            :is-disabled-element="isDisabledElement || isFromGosmonitoring || (isAnotherBatch && !isAnotherBatchGrain)"
            :model="model"
            :on-selected-type-of-crop="onSelectedTypeOfCrop"
            name="product-type-field"
            :is-active="isActualOkpd2"
          />

          <text-component v-if="isAnotherBatchOkpd2Error" class="text-caption text-center orange--text" variant="span">
            {{ isAnotherBatchOkpd2Error }}
          </text-component>
        </v-col>

        <v-col v-show="showTnved" cols="4" lg="4" md="4" xl="4">
          <slot :is-disabled-element="isDisabledElement" :model="model" name="tnved-field" :is-active="isActualOkpd2">
            <select-request-component
              v-model="model[model.getTnvedFieldName()]"
              :disabled="isDisabledElement || !model.okpd2_id"
              label="ТН ВЭД"
              placeholder="Укажите ТН ВЭД"
              :custom-items="tnvedItemsFormatted"
              item-name="label"
            />
          </slot>
        </v-col>

        <v-col v-if="showDestinationCountry" cols="4" lg="4" md="4" xl="4">
          <slot :is-disabled-element="isDisabledElement" :model="model" name="destination-country-field">
            <country-dictionary
              v-model="countryDestination"
              :is-disabled="isDisabledElement"
              label="Страна назначения"
              placeholder="Укажите страну назначения"
            />
          </slot>
        </v-col>

        <v-col
          v-show="
            type === 'field' ||
            typeof model.research_numbers_government_monitoring_id === 'number' ||
            model.type === 'FIELD'
          "
          cols="12"
          lg="6"
          md="6"
          xl="6"
        >
          <slot
            :disabled="isDisabledElement || !isPermLoadMonitorings"
            :model="model"
            name="gosmonitoring-number-field"
          >
            <ConductedResearchPicker
              v-model="model.research_numbers_government_monitoring_id"
              :lot-model="model"
              :is-disabled="isDisabledElement || !isPermLoadMonitorings"
              :load-privileges="isPermLoadMonitorings"
              :stored-amount-kg="storedAmountKg"
              :stored-research-id="storedResearchId"
              :subject-id-filter="getGosmonitoringSubjectIdFilter"
              :is-change="isChange"
              :selected-number-gos="selectedNumberGos"
              @select="onSelectNumberGos"
              @lot-model-change="setModel"
            />

            <text-component
              v-if="!isPermLoadMonitorings && isChange"
              class="text-caption text-center orange--text"
              variant="span"
            >
              Укажите владельца партии
            </text-component>
          </slot>
        </v-col>
        <v-col v-if="model.repository_id" cols="6" lg="6" md="6" xl="4">
          <InputComponent
            disabled
            label="Организация, осуществляющая хранение"
            :value="repositoryName"
            :tooltip="repositoryName"
          />
        </v-col>
        <slot
          :disabled="isDisabledCreateAndEndDate || isDisabledElement || isLotEdit"
          :model="model"
          name="manufacture-field"
        />
      </v-row>
    </v-col>

    <v-col v-if="isNotAgent" :class="[{ hideTabs: isChange, 'mt-2': !isChange, 'col-12': !isChange }]">
      <v-row>
        <v-col cols="12">
          <div class="containerTabs">
            <div :class="['tabs', { disabled: isEdit }]">
              <div :class="[{ active: tab === 'lot' }, 'tab']" @click="onSelectTab('lot')">Информация</div>
              <div v-if="isSdizTab" :class="[{ active: tab === 'sdiz' }, 'tab']" @click="onSelectTab('sdiz')">СДИЗ</div>
              <div v-if="isHistoryTab" :class="[{ active: tab === 'history' }, 'tab']" @click="onSelectTab('history')">
                История
              </div>
            </div>
          </div>
        </v-col>
      </v-row>
    </v-col>

    <v-col v-if="tab === 'lot'" cols="12">
      <LotDetailTables
        v-model="value"
        :is-create="isCreate"
        :is-detail="isDetail"
        :is-edit="isEdit"
        :is-ammend="isAmmend"
        :type="type"
        :selected-version="model.selectedHistoryVersion"
        @onChangeAmountKg="onChangeAmountKg"
        @onDeleteLotMoved="onDeleteLotMoved"
        @onFirstLotGrainIsSelect="onFirstLotGrainIsSelect"
      >
        <template #[`table-lots-moved`]="{ isCreate, isDetail, isEdit, isLockFiltersFromLots, model, type }">
          <slot
            :is-create="isCreate"
            :is-detail="isDetail"
            :is-edit="isEdit"
            :is-lock-filters="isLockFiltersFromLots"
            :model="model"
            :type="type"
            name="table-lots-moved"
          />
        </template>
      </LotDetailTables>
    </v-col>

    <v-col v-if="tab === 'sdiz'" cols="12">
      <sdiz-list-tables
        :value-rows="rows"
        :headers="sdizModel.getHeaders()"
        :is-short-header="true"
        :model="sdizModel"
        :pageable="pageable"
        :total="total"
        no-data-text="СДИЗ для партии не найден."
      />
    </v-col>

    <v-col v-if="tab === 'history'" cols="12">
      <lot-tables-debits
        v-model="value.objects.debits"
        @cancel-debit="$emit('cancel-debit', $event)"
        :is-actions-access="isActionsAccess"
      />
    </v-col>

    <slot name="buttons" />
  </v-row>
</template>

<script lang="ts">
import { Component, Model, Prop, Watch, Mixins } from 'vue-property-decorator';
import { LotDataVueModel } from '@/models/Lot/Data/LotData.vue';
import { LotGpbDataVueModel } from '@/models/Lot/Data/LotGpbData.vue';
import { findElementInArray } from '@/utils/methodForArrays';
import { loadQualityIndicatorsByParams } from '@/utils/qualityIndicators';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import CountryDictionary from '@/components/common/Dictionary/Country/Country.vue';
import LotDetailTables from '@/views/Lot/components/Subcomponents/Tables/LotDetailTables.vue';
import UiDateInput from '@/components/global/UiForm/components/UiDateInput.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import TextComponent from '@/components/common/Text/Text.vue';
import ButtonComponent from '@/components/common/buttons/DefaultButton.vue';
import { FiasMix } from '@/utils/mixins/fias';
import { AdditionalMix } from '@/utils/mixins/additional';
import AutocompletePriorityAddress from '@/components/PriorityAddress/PriorityAddress.vue';
import SdizListTables from '@/views/Sdiz/components/Subcomponents/Table/SdiztListTables.vue';
import LotTablesDebits from '@/views/Lot/components/Subcomponents/Tables/LotTablesDebits.vue';
import merge from 'lodash/merge';
import pick from 'lodash/pick';
import { numberThousandsMask, numberThousandsUnmask } from '@/components/common/inputs/mask/numberThousandsMask';
import { applyMask } from '@/components/common/inputs/mask/decimalNumberMask';
import { RequestMix } from '@/utils/mixins/request';
import { LotElevatorDataVueModel } from '@/models/Lot/Data/LotElevatorData.vue';
import { SdizGpbVueModel } from '@/models/Sdiz/Data/SdizGpb.vue';
import { SdizVueModel } from '@/models/Sdiz/Data/Sdiz.vue';
import ManufacturerAutocomplete from '@/components/ManufacturerAutocomplete/ManufacturerAutocomplete.vue';
import config from '@/views/NSI/config';
import { LotsMovedVueModel } from '@/models/Lot/LotsMoved.vue';
import { LotType } from '@/utils/enums/LotType';
import { LotsPurposeEnum } from '@/utils/enums/lotsPurpose.enum';
import { DictionariesMix } from '@/utils/mixins/dictionaries';
import ConductedResearchPicker from '@/views/Lot/components/Subcomponents/ConductedResearchPicker.vue';
import { ConductedResearchShortModel } from '@/models/Gosmonitoring/ConductedResearchShort';
import WeightInput from '@/views/Lot/components/Subcomponents/WeightInput.vue';
import { add } from '@/utils/decimals';

enum PurposeType {
  'IMPORTED' = 'imported',
  'SDIZ' = 'sdiz',
  'FIELD' = 'field',
  'RESIDUES' = 'residues',
  'ANOTHER_BATCH_GRAIN' = 'another-batch-grain',
  'IN_PRODUCT' = 'in-product',
}

@Component({
  name: 'lot-form',
  components: {
    WeightInput,
    ConductedResearchPicker,
    CountryDictionary,
    ManufacturerAutocomplete,
    LotTablesDebits,
    SdizListTables,
    ButtonComponent,
    AutocompletePriorityAddress,
    TextComponent,
    DialogComponent,
    InputComponent,
    UiDateInput,
    LotDetailTables,
    AutocompleteComponent,
    SelectRequestComponent,
  },
})
export default class LotForm extends Mixins(FiasMix, AdditionalMix, RequestMix, DictionariesMix) {
  @Model('change', { type: Object }) value!: LotDataVueModel | LotGpbDataVueModel | LotElevatorDataVueModel;

  @Prop({ default: null }) name!: string | null;
  @Prop({ default: '' }) type!: string;
  @Prop({ default: false }) isDetail!: boolean;
  @Prop({ default: false }) isCreate!: boolean;
  @Prop({ default: false }) isEdit!: boolean;
  @Prop({ type: Boolean, default: false }) isAmmend!: boolean;
  @Prop({ default: true }) isNotAgent!: boolean;
  @Prop({ type: Object }) sdizModel!: SdizVueModel | SdizGpbVueModel;
  @Prop({ type: Boolean, default: false }) hideSdizTab!: boolean;
  @Prop({ type: Boolean, default: false }) hideAmmendsMenu!: boolean;
  @Prop({ type: Boolean, default: false }) isForRouApk!: boolean;
  @Prop({ type: Boolean, default: false }) isActionsAccess!: boolean;
  @Prop({ type: Boolean, default: false }) isElevatorLot!: boolean;

  amount_kg_mask = '';
  maskNumberThousands = numberThousandsMask;
  numberThousandsUnmask = numberThousandsUnmask;

  anotherLot: LotsMovedVueModel | null = null;
  lockFiletsFromLotMoved = false;
  /** Выбранный "Документ о результатах госмониторинга". */
  selectedNumberGos: any = null;
  /** Состояние массы партии из ГМ перед редактированием. */
  storedAmountKg: number | null = null;
  storedResearchId: number | null = null;
  tab = 'lot';
  LotType = LotType;
  test = null;
  testMask = '';

  get model(): any {
    return this.value;
  }
  set model(value) {
    this.$emit('change', value);
  }

  setModel(value) {
    this.model = value;
  }

  get keyDateCreate() {
    return this.model.create_date ? this.model.create_date.length + this.randNumber : 0;
  }

  get keyOwner() {
    return this.model.owner_id ? this.model.owner_id + this.randNumber : 0;
  }

  get showTnved() {
    return [LotsPurposeEnum.IMPORT_TO_RUSSIA, LotsPurposeEnum.EXPORT_FROM_RUSSIA].includes(this.model.purposeCode);
  }

  get showDestinationCountry() {
    return this.model.purposeCode === LotsPurposeEnum.EXPORT_FROM_RUSSIA;
  }

  get originLocationAddress() {
    return this.model.objects.origin_location.address ?? '-';
  }

  tnvedItems: any[] = [];
  get tnvedItemsFormatted() {
    return this.tnvedItems.map((e) => ({ ...e, label: `${e.name} (${e.code})` }));
  }

  get isDisabledAmountKg(): boolean {
    if (this.model.movedField === 'gpb_moved' && this.type === 'in-product') {
      return false;
    }

    if (this.model.objects.lots_moved.length > 0 || this.model.objects.sdiz_data.items.length > 0) {
      return true;
    }
    return this.type === 'another-batch-grain' || (this.type === 'sdiz' && this.isCreate);
  }

  get isLotEdit(): boolean {
    if (this.model.type === 'RESIDUES' || this.model.type === 'SDIZ' || this.model.type === 'IMPORTED') return false;
    return (this.type === 'in-product' && this.isCreate) || this.isEdit;
  }

  get purposeType() {
    if (this.isElevatorLot) return 'elevator';
    return this.model.type && PurposeType[this.model.type] ? PurposeType[this.model.type] : this.type;
  }

  get isChange(): boolean {
    return this.isCreate || this.isEdit;
  }

  get isDisabledCreateAndEndDate(): boolean {
    switch (this.type) {
      case 'another-batch-grain':
        return true;
      case 'residues':
        return false;
    }
    return this.type === 'another-batch-grain';
  }
  get isDisabledAddress(): boolean {
    switch (this.type) {
      case 'another-batch-grain':
      case 'in-product':
        return true;
      default:
        return false;
    }
  }

  get isDisabledElement(): boolean {
    return this.isDetail && !this.isEdit;
  }

  get newKeyImported() {
    return this.type + this.randNumber;
  }

  get isImported(): boolean {
    return this.type === 'imported';
  }

  get isFromGosmonitoring() {
    return this.type === 'field';
  }

  get isAnotherBatch() {
    return this.type === 'another-batch-grain';
  }

  get isAnotherBatchGrain(): boolean {
    return this.isAnotherBatch && !!this.model.lotType?.is_grain;
  }

  get isAnotherBatchProduct(): boolean {
    return this.isAnotherBatch && !!this.model.lotType?.is_product;
  }

  get isAnotherBatchOkpd2Error() {
    const message = 'Выбранного вида с/х культуры нет в предшествующих партиях';

    if (!this.isAnotherBatchGrain || !this.isChange) return '';

    // todo: Нужны opd2_id предшествующих партий от бэка.
    if (this.isEdit) return '';

    const selectedLots = this.model.objects.lots_moved;
    const movedFieldsOkpd2: LotsMovedVueModel[] = Array.isArray(selectedLots)
      ? selectedLots.map((e) => e.okpd2_id).filter((e) => !!e)
      : [];

    const selectedOkpd2Id = this.model.okpd2_id;
    return selectedOkpd2Id && !movedFieldsOkpd2.includes(selectedOkpd2Id) ? message : '';
  }

  get randNumber() {
    return Math.floor(Math.random() * 1000 + 1);
  }

  get isElevatorOrHasOwnerIdModel(): boolean {
    if (this.isChange) {
      return this.model.getTypeModel() === 'LotElevator';
    }

    return this.model.getTypeModel() === 'LotElevator' && this.model.owner_id !== null;
  }

  get isPermLoadMonitorings() {
    if (this.isForRouApk) return false;

    if (this.isElevatorOrHasOwnerIdModel) {
      return this.model.owner_id !== null;
    }

    return true;
  }

  get isActualOkpd2() {
    const routesForTypesToPreserveInactiveOkpd2 = ['lots_gpb_create_from_another_batch'];
    const isSdizRoute = this.$route.name?.includes('sdiz') && this.$route.name !== 'lots_gpb_create_from_sdiz';

    return routesForTypesToPreserveInactiveOkpd2.includes(this.$route.name || '') ||
      isSdizRoute ||
      this.isFromGosmonitoring
      ? false
      : this.isCreate || this.isEdit;
  }

  // Подставляем в модель данные из госмониторинга по id
  onSelectNumberGos(data: ConductedResearchShortModel | null) {
    if (!data) return;

    this.model.okpd2_id = data.okpd2_id;
    this.model.target_id = data.target_id;
    this.selectedNumberGos = data;
  }

  insertQualityIndicatorValues(quality_indicators) {
    if (quality_indicators.length > 0) {
      quality_indicators.forEach((quality_indicator) => {
        this.model.objects.quality_indicators.forEach((quality_indicator_lot, quality_indicator_lot_id) => {
          if (quality_indicator_lot.quality_indicator_id === quality_indicator.quality_indicator_id) {
            this.model.objects.quality_indicators[quality_indicator_lot_id].value = quality_indicator.value;
          }
        });
      });
    }
  }

  updateLotsData(data) {
    this.model = new this.model.constructor(data);
  }

  /**
   * @param tab
   */
  onSelectTab(tab) {
    if (!this.isEdit) {
      this.tab = tab;
    }
  }

  getOkpd2Msh(lotType: string) {
    return this.$store.getters[config['nsi-okpd2-msh'].storeGetter[lotType]];
  }

  get getGosmonitoringSubjectIdFilter(): number | null {
    return this.isElevatorOrHasOwnerIdModel ? this.model.owner_id : this.subjectOfUser.subject_id;
  }

  setModelOkpd2(item) {
    this.model.objects.okpd2.id = item.id;
    this.model.objects.okpd2.product_name_convert = item.label || '';
    this.model.objects.okpd2.code = item.code;
  }

  async loadAndSetQualityIndicators(okpd2Object) {
    if (this.model.purposeCode === LotsPurposeEnum.EXPORT_FROM_RUSSIA && !this.model.country_destination_id) return;

    const country_alpha2 = this.model.objects.country_destination?.code_alpha2 || 'RU';

    try {
      this.$emit('error', false);
      this.$emit('quality-indicators-loading', true);
      this.model.objects.quality_indicators = await loadQualityIndicatorsByParams({
        okpd2: okpd2Object.code,
        purposes: this.model.qualityIndicatorPurposes,
        country_alpha2,
      });

      if (this.isChange && this.selectedNumberGos?.quality_indicators?.length) {
        this.insertQualityIndicatorValues(this.selectedNumberGos.quality_indicators);
      }

      if (this.isChange && this.anotherLot) {
        this.insertQualityIndicatorValues(this.anotherLot?.quality_indicators);
        this.anotherLot = null;
      }
    } catch (_e) {
      this.$emit('error', true);
      this.$service.notify.push('error', {
        text: 'Ошибка загрузки потребительских свойств',
      });
    } finally {
      await this.$nextTick();
      this.$emit('quality-indicators-loading', false);
    }
  }

  async onSelectedTypeOfCrop(data: { items: Array<any>; value: any }): Promise<void> {
    if (this.isCreate || this.isEdit) {
      this.model.objects.quality_indicators = [];
      const isSelectedItem = data.value !== null && findElementInArray(data.items, data.value);

      if (isSelectedItem) {
        const item: any = findElementInArray(data.items, data.value);
        this.setModelOkpd2(item);
        await this.loadTnvedItemsByCode(item.code);
        // Если в загруженном списке ТН ВЭД нет выбранного ранее элемента, то очистить поле
        if (!this.tnvedItems.filter((e) => e.id === this.model[this.model.getTnvedFieldName()]).length) {
          this.model[this.model.getTnvedFieldName()] = null;
        }

        await this.loadAndSetQualityIndicators(item);
      } else {
        this.model[this.model.getTnvedFieldName()] = null;
      }
    }
  }

  /**
   * Произошло удалении партии от которой мы наследовались
   */
  onDeleteLotMoved(): void {
    let lots = this.model.getLots();
    if (this.isCreate) {
      if (lots.length === 0) {
        // ни какой партии более нет, сбрасываем фильтры
        this.model.owner_id = null;
        this.model.target_id = null;
        this.model.okpd2_id = null;
        this.model.current_location_id = null;
        this.model.objects.quality_indicators = [];
        this.lockFiletsFromLotMoved = false;
      } else {
        this.model.amount_kg = this.getTotalAmountKg();
      }
    }
  }

  /**
   * Меняем массу партии исходя от измененых данных в lots_moved
   * @param lot
   */
  onChangeAmountKg(lot): void {
    if (this.type !== 'sdiz') {
      const amount_kg = this.getTotalAmountKg();
      this.model.amount_kg = amount_kg === 0 ? null : amount_kg;
    } else {
      if (typeof lot.amountKg !== 'undefined') {
        this.model.amount_kg = lot.amountKg.value;
      } else {
        this.model.amount_kg = parseFloat(lot.value) > 0 ? parseFloat(lot.value) : null;
      }
    }

    this.model.amount_kg_mask = applyMask(this.model.amount_kg, true);
  }

  /**
   * Подставляем данные в фильтр из той партии от которой наследуемся
   * @param data
   */
  onFirstLotGrainIsSelect(data: {
    filters: LotDataVueModel | LotGpbDataVueModel;
    rows: LotDataVueModel[] | LotGpbDataVueModel[];
    lot: LotDataVueModel | LotGpbDataVueModel;
  }): void {
    if (this.isCreate) {
      let fieldMoved = this.model.movedField;
      if (this.model.objects[fieldMoved].length <= 1) {
        const filters = pick(data.lot, ['target_id', 'okpd2_id', 'current_location_id']);
        merge(this.model, filters);
        this.lockFiletsFromLotMoved = true;
      }
    }
  }

  getTotalAmountKg(): number {
    let amount_kg = 0;
    this.model.objects.lots_moved.map(({ value }) => (amount_kg = add(amount_kg, parseInt(value))));
    return amount_kg;
  }

  async handleLotTypes() {
    if (this.isFromField) await this.handleFromField();
    if (this.isAnotherBatch) await this.handleAnotherBatch();
  }

  async loadAttachedSdiz() {
    const { status, response, pagination } = await this.$store.dispatch(this.value.list_sdiz_apiendpoint, {
      filter: {
        options: [{ field: this.value.filter_sdiz_lot_number, operator: '=', value: this.value.getNumber() }],
      },
    });

    if (status) {
      this.total = pagination.totalResults;

      this.rows = response.map((v) => {
        const clearData = Object.fromEntries(Object.entries(v).filter(([_, v]) => v !== null));

        if (this.value.number_type === 'lot_number') {
          return new SdizVueModel(clearData);
        }
        return new SdizGpbVueModel(clearData);
      });
    }
  }

  get isHistoryTab() {
    return this.value.status !== 'CREATE';
  }

  get isSdizTab() {
    return !this.hideSdizTab && this.value.status !== 'CREATE' && this.value.getNumber();
  }

  async mounted() {
    if (this.model.tnved_id) await this.loadTnvedItemById(this.model.tnved_id);

    if (this.isSdizTab) {
      await this.loadAttachedSdiz();
    }

    await this.handleLotTypes();

    this.model.selectLatestHistoryVersion();
  }

  async handleAnotherBatch() {
    const isDirectLink = !!this.$route.query.lot_id;

    if (isDirectLink) {
      const movedLots = this.model.objects[this.model.movedField];
      const anotherLot = Array.isArray(movedLots) && movedLots.length ? movedLots[0] : null;
      this.anotherLot = anotherLot;
      if (anotherLot) await this.setOkpd2AndQualityIndicators();
    }
  }

  async setOkpd2AndQualityIndicators() {
    await this.fetchOkpd2Msh(this.model.lotType);
    const selectedOkpd2Id = this.model.okpd2_id;
    const selectedOkpd2 = this.okpd2List.find((e) => e.id === selectedOkpd2Id);
    if (selectedOkpd2) {
      this.setModelOkpd2(selectedOkpd2);
      await this.loadAndSetQualityIndicators(this.model.objects.okpd2);
    }
  }

  async handleFromField() {
    if (this.model.id) {
      this.storedAmountKg = this.model.amount_kg;
      this.storedResearchId = this.model.research_numbers_government_monitoring_id;
    }

    const isDirectCreationLink = this.isCreate && !!this.$route.params.research_numbers_government;
    if (isDirectCreationLink) {
      const gm = JSON.parse(this.$route.params.research_numbers_government);
      this.model.research_numbers_government_monitoring_id = gm.id;
      this.onSelectNumberGos(gm);
      await this.setOkpd2AndQualityIndicators();
    }
  }

  get okpd2List() {
    const lotTypeKey = Object.keys(this.model.lotType)[0];
    return this.$store.getters[config['nsi-okpd2-msh'].storeGetter[lotTypeKey]];
  }

  get isFromField() {
    return (
      typeof this.model.research_numbers_government_monitoring_id === 'number' ||
      this.$route.name === 'lot_create_from_field' ||
      this.model.type === 'FIELD'
    );
  }

  async loadTnvedItemsByCode(okpd2Code: string) {
    this.tnvedItems = await this.fetchTnved(okpd2Code);
  }

  async loadTnvedItemById(id) {
    const data = await this.fetchTnvedById(id);
    this.tnvedItems = [data];
  }

  @Watch('model.tnved_id')
  async onTnvedChange(val) {
    if (val && !this.isChange) {
      await this.loadTnvedItemById(val);
    }
  }

  @Watch('model.purposeCode')
  async onPurposeCodeChange(newVal: LotsPurposeEnum | null) {
    if (newVal !== LotsPurposeEnum.EXPORT_FROM_RUSSIA && this.isChange) {
      this.countryDestination = null;
      this.model.country_destination_id = null;
      this.model.objects.quality_indicators = [];

      const okpd2 = this.model.objects.okpd2 ?? null;
      if (okpd2.code) await this.loadAndSetQualityIndicators(okpd2);
    } else if (newVal === LotsPurposeEnum.EXPORT_FROM_RUSSIA) {
      await this.onOkpd2AndCountrySelect(this.okpd2AndCountryDestination);
    }
  }

  @Watch('model.objects.country_destination', { deep: true })
  async onChangeCountryDestination(val) {
    if (!this.isChange) return;
    this.model.country_destination_id = val?.country_id || null;
  }

  get okpd2AndCountryDestination() {
    return {
      okpd2Code: this.model.objects.okpd2?.code || null,
      countryAlpha: this.model.objects.country_destination?.code_alpha2 || null,
    };
  }

  @Watch('okpd2AndCountryDestination', { deep: true })
  async onOkpd2AndCountrySelect(val) {
    if (this.model.purposeCode !== LotsPurposeEnum.EXPORT_FROM_RUSSIA || !this.isChange) return;

    this.model.objects.quality_indicators = [];

    if (val.okpd2Code && val.countryAlpha) {
      await this.loadAndSetQualityIndicators(this.model.objects.okpd2);
    }
  }

  get countryDestination() {
    return this.model.objects.country_destination?.country_id ? this.model.objects.country_destination : null;
  }

  set countryDestination(v) {
    this.model.objects.country_destination = v;
  }

  @Watch('isEdit')
  async onIsEditChange(val, prev) {
    if (prev && !val) {
      await this.handleLotTypes();
    }

    if (val) {
      if (this.model.objects.purpose?.code) {
        try {
          this.model.objects.purpose = await this.dictionaryRecordByCode(
            'nsi-lots-purpose',
            this.model.objects.purpose?.code
          );
        } catch (_e) {
          this.$emit('error');
        }
      }
    }
  }

  get repositoryName() {
    return this.model.objects.repository?.short_name || this.model.objects.repository?.name || '-';
  }

  @Watch('model.owner_id')
  onOwnerIdChange() {
    this.model.research_numbers_government_monitoring_id = null;
    this.model.okpd2_id = null;
    this.model.target_id = null;
    this.model.objects.quality_indicators = [];
    this.selectedNumberGos = null;
  }
}
</script>
<style lang="scss" scoped>
@import './src/assets/styles/_variables';
@import './src/assets/styles/_mixins';
@import './src/assets/styles/_container';

.hideTabs {
  opacity: 0;
  height: 53px;
}

.hideTabs div {
  display: none;
}

.title {
  position: relative;
}

.hint {
  position: absolute;
  top: 55px;
  font-size: 10px !important;
}

img.icon {
  max-width: 100%;
  width: 25px;
}

.verticalLine {
  border-left: 2px solid $light-grey-color;
  height: 100%;
  margin-left: 15px;
}

.tabs {
  border-bottom: 1px solid $light-grey-color;
  width: 100%;
  display: flex;
  flex-direction: row;
  text-transform: uppercase;

  &.disabled .tab {
    cursor: not-allowed;
    opacity: 0.4;
    color: $button-disabled-color;

    &.active {
      border-bottom: 1px solid $button-disabled-color;
    }
  }
}

.tab {
  display: flex;
  font-weight: bold;
  font-size: 13px;
  color: $footer-color;
  cursor: pointer;
  padding-bottom: 8px;
  margin-right: 18px;

  &.active {
    color: $gold-light-color;
    border-bottom: 1px solid $gold-light-color;
  }
}

.input {
  &--disabled {
    background-color: $input-disable-background;
    color: $input-disabled-color;
  }

  &--small {
    flex: 1 1 150px;
    margin-right: 15px;
    max-width: 150px;
  }

  &--large {
    flex: 1 1 100%;
  }
}

.btn-small {
  font-size: 14px;
  padding: 0 10px !important;
  height: 35px !important;
  margin-top: 0;
}
</style>
