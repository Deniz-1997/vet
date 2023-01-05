<template>
  <v-container fluid>
    <v-row>
      <dialog-component
        :key="openModal"
        v-model="openModal"
        :prompt="false"
        :no-click-animation="true"
        cancel-title="Закрыть"
        confirm-title=""
        controls-justify="justify-end"
        @onCancel="onCancel"
      >
        <template #content>
          <tab-component v-model="filters.sdiz_type" :tab-list="rshn.tabListSdiz" />

          <rshn-list
            :key="sdizKey"
            v-model="filters.sdiz"
            :is-show-additional-button="false"
            is-custom-clear-filters
            :custom-clear-filters-function="customClearFilters"
            :use-filters-store="false"
            is-request-payload
            show-select-button
            :title="titleFindTable"
            :additional-system-headers="['button', 'objects.operations.types']"
            @onSelectItem="selectSdiz"
          >
            <template #[`filter`]="{ clear }">
              <div :key="clear" class="mb-1">
                <v-row justify-md="start">
                  <v-col cols="12" md="4">
                    <input-component
                      v-model="filters.sdiz[filters.sdiz.getSdizNumberField(filters.sdiz_type)]"
                      clearable
                      label="Номер СДИЗ"
                      placeholder="Введите номер СДИЗ"
                    />
                  </v-col>
                  <v-col cols="12" md="4">
                    <label-component label="Дата" />
                    <v-row>
                      <v-col class="pr-1" cols="12" data-app sm="6">
                        <UiDateInput
                          v-model="filters.sdiz.date_from"
                          :format="'DD.MM.YYYY'"
                          :limit-to="today"
                          placeholder="от"
                        />
                      </v-col>
                      <v-col class="pr-1" cols="12" data-app sm="6">
                        <UiDateInput
                          v-model="filters.sdiz.date_to"
                          :limit-to="today"
                          :limit-from="fromDate(filters.sdiz.date_from)"
                          :format="'DD.MM.YYYY'"
                          placeholder="до"
                        />
                      </v-col>
                    </v-row>
                  </v-col>

                  <v-col cols="12" md="4">
                    <select-request-component
                      v-model="sdizLotOkpdCode"
                      type="nsi-okpd2-codes"
                      item-id="code"
                      :is-active="false"
                      :lot-type="lotType"
                      :label="titleProduct"
                      :placeholder="'Выберите ' + titleProduct"
                    />
                  </v-col>
                  <v-col cols="12" md="4">
                    <label-component label="Масса, кг" />
                    <v-row no-gutters>
                      <v-col cols="12" data-app sm="6">
                        <input-component v-model="filterAmountKgFromMask" placeholder="От" type="text" />
                      </v-col>
                      <v-col cols="12" data-app sm="6">
                        <input-component v-model="filterAmountKgToMask" class="ml-3" placeholder="До" type="text" />
                      </v-col>
                    </v-row>
                  </v-col>
                  <v-col cols="12" md="4">
                    <ManufacturerAutocomplete
                      v-model="filters.sdiz.authorized_person"
                      label="Уполномоченное лицо"
                      placeholder="Начните вводить наименование, ИНН, КПП или ОГРН"
                      show-name-in-tooltip
                      clereables
                    />
                  </v-col>

                  <v-col cols="12" md="4">
                    <ManufacturerAutocomplete
                      v-model="filters.sdiz.shipper_id"
                      label="Грузоотправитель"
                      placeholder="Начните вводить наименование, ИНН, КПП или ОГРН"
                      show-name-in-tooltip
                      clereables
                    />
                  </v-col>

                  <v-col cols="12" md="4">
                    <ManufacturerAutocomplete
                      v-model="filters.sdiz.consignee_id"
                      label="Грузополучатель"
                      placeholder="Начните вводить наименование, ИНН, КПП или ОГРН"
                      clereables
                    />
                  </v-col>

                  <v-col cols="12" md="4">
                    <autocomplete-priority-address
                      v-model="shipperOrConsigneeModel"
                      item-value="id"
                      :label="title ? 'Страна-экспортер' : 'Страна-импортер'"
                      placeholder="Выберите страну"
                    />
                  </v-col>
                </v-row>
              </div>
            </template>
          </rshn-list>

          <div class="close-btn-container">
            <IconComponent :width="16" icon-color="#828286" @click="onCancel"><CloseIcon /></IconComponent>
          </div>
        </template>
      </dialog-component>
    </v-row>
  </v-container>
</template>

<script lang="ts">
import { Component, Prop, Watch, Mixins } from 'vue-property-decorator';
import SignatureModal from '@/components/SignatureModal/SignatureModal.vue';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import UiDateInput from '@/components/global/UiForm/components/UiDateInput.vue';
import RshnList from '@/views/rshn/components/List.vue';
import WithdrawalFilterForms from '@/views/rshn/subcomponents/FilterForms/WithdrawalFilterForms.vue';
import SdizListTables from '@/views/Sdiz/components/Subcomponents/Table/SdiztListTables.vue';
import ActionsButtons from '@/components/Forms/ActionsButtons.vue';
import { RequestMix } from '@/utils/mixins/request';
import { AdditionalMix } from '@/utils/mixins/additional';
import { RshnExpertiseData } from '@/models/Rshn/Expertise/RshnExpertiseData.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import { dateFrom } from '@/utils/date';
import LabelComponent from '@/components/common/Label/Label.vue';
import { decimalNumberMask, decimalNumberUnmask } from '@/components/common/inputs/mask/decimalNumberMask';
import ManufacturerAutocomplete from '@/components/ManufacturerAutocomplete/ManufacturerAutocomplete.vue';
import { ExpertiseEnum, ExpertiseSdizType, StatusEnum } from '@/utils/enums/RshnEnums';
import AutocompletePriorityAddress from '@/components/PriorityAddress/PriorityAddress.vue';
import { rshnConsts } from '@/utils/consts/rshnConsts';
import TabComponent from '@/views/rshn/subcomponents/TabComponent.vue';
import { SdizShortVue } from '@/models/Rshn/ShortModel/SdizShort.vue';
import { DictionariesMix } from '@/utils/mixins/dictionaries';
import { LotPurposeVueModel } from '@/models/Lot/LotPurpose.vue';
import IconComponent from '@/components/common/IconComponent/IconComponent.vue';
import CloseIcon from '@/components/common/IconComponent/icons/CloseIcon.vue';

@Component({
  components: {
    CloseIcon,
    IconComponent,
    TabComponent,
    AutocompletePriorityAddress,
    ManufacturerAutocomplete,
    LabelComponent,
    InputComponent,
    ActionsButtons,
    SdizListTables,
    WithdrawalFilterForms,
    RshnList,
    DialogComponent,
    SelectRequestComponent,
    SignatureModal,
    UiDateInput,
  },
})
export default class SdizFindDialog extends Mixins(RequestMix, AdditionalMix, DictionariesMix) {
  @Prop({ type: Boolean, default: false }) isOpen!: boolean;
  @Prop({ type: Number, default: null }) type!: ExpertiseEnum;

  filters = new RshnExpertiseData();

  rshn = rshnConsts;
  titleFindTable = 'Поиск сведений СДИЗ';
  decimalNumberMask = decimalNumberMask;
  decimalNumberUnmask = decimalNumberUnmask;
  fromDate(date) {
    return dateFrom(date, -1);
  }
  created() {
    this.filters.sdiz.status_id = StatusEnum.SUBSCRIBED;
  }

  get sdizKey() {
    if (this.filters.sdiz_type === ExpertiseSdizType.SDIZ) {
      this.filters.sdiz.changeType(ExpertiseSdizType.SDIZ);
      this.titleFindTable = 'Поиск сведений СДИЗ на партии зерна';
    } else {
      this.filters.sdiz.changeType(ExpertiseSdizType.GPB_SDIZ);
      this.titleFindTable = 'Поиск сведений СДИЗ на продукты переработки';
    }
    return this.filters.sdiz_type;
  }

  get titleProduct() {
    return this.filters.sdiz_type === 0 ? 'Вид с\\х культуры' : 'Вид продуктов переработки';
  }

  get lotType() {
    return this.filters.sdiz.getLotType(this.filters.sdiz_type);
  }

  get sdizLotOkpdCode() {
    return this.filters.sdiz.getLotObject(this.filters.sdiz_type).okpd2Code;
  }

  set sdizLotOkpdCode(v: any) {
    this.filters.sdiz.getLotObject(this.filters.sdiz_type).okpd2Code = v;
  }

  get filterAmountKgFromMask() {
    return this.filters.sdiz.getLotObject(this.filters.sdiz_type).amount_kg_from_mask;
  }

  set filterAmountKgFromMask(v) {
    this.filters.sdiz.getLotObject(this.filters.sdiz_type).amount_kg_from_mask = v;
    this.filters.sdiz.getLotObject(this.filters.sdiz_type).amount_kg_from = decimalNumberUnmask(v);
  }

  get filterAmountKgToMask() {
    return this.filters.sdiz.getLotObject(this.filters.sdiz_type).amount_kg_to_mask;
  }

  set filterAmountKgToMask(v) {
    this.filters.sdiz.getLotObject(this.filters.sdiz_type).amount_kg_to_mask = v;
    this.filters.sdiz.getLotObject(this.filters.sdiz_type).amount_kg_to = decimalNumberUnmask(v);
  }

  get title() {
    return this.type === ExpertiseEnum.EXPORT;
  }
  get shipperOrConsigneeModel() {
    return this.type === ExpertiseEnum.EXPORT
      ? this.filters.sdiz.shipper_location_id
      : this.filters.sdiz.consignee_location_id;
  }

  set shipperOrConsigneeModel(v: any) {
    if (this.type === ExpertiseEnum.EXPORT) {
      this.filters.sdiz.shipper_location_id = v;
    } else {
      this.filters.sdiz.consignee_location_id = v;
    }
  }

  get openModal() {
    return this.isOpen;
  }

  set openModal(value: any) {
    this.$emit('isOpen', value);
  }

  onCancel() {
    this.openModal = !this.openModal;
  }

  selectSdiz(item) {
    this.$emit('onSelect', { sdiz: item, sdizType: this.filters.sdiz_type });
    this.onCancel();
  }

  get withdrawalAndSdizType() {
    return `${this.type} ${this.filters.sdiz_type}`;
  }

  @Watch('withdrawalAndSdizType', { immediate: true })
  onPrototypeAndTypeChange(_value) {
    this.filters.sdiz = this.sdizShortBaseFilters(this.filters.sdiz);
  }

  sdizShortBaseFilters(filters: SdizShortVue) {
    let purpose: any = null;

    switch (this.type) {
      case ExpertiseEnum.IMPORT:
        purpose = {
          code: '3',
        };
        break;
      case ExpertiseEnum.EXPORT:
        purpose = {
          code: '4',
        };
        break;
    }

    filters.status_id = StatusEnum.SUBSCRIBED;
    filters.getLotObject(this.filters.sdiz_type).objects.purpose = new LotPurposeVueModel(purpose);

    return filters;
  }

  customClearFilters() {
    return this.sdizShortBaseFilters(new SdizShortVue());
  }
}
</script>

<style lang="scss" scoped>
.close-btn-container {
  position: absolute;
  top: 20px;
  right: 20px;
}
</style>
