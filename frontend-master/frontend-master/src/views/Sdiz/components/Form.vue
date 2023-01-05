<template>
  <v-row>
    <!---- START BLOCK TITLE ---->
    <template>
      <v-col cols="12">
        <text-component class="title d-flex align-center" variant="span">
          <template v-if="isCreate">{{ isElevator ? 'Оформление СДИЗ (Элеватор)' : titlePageCreate }}</template>
          <template v-if="!isCreate">
            {{ documentTypeName }} {{ model.getSdizNumber() }}
            <v-chip :color="isEdit ? 'warning' : ''" class="ml-3" label>
              {{ isEdit ? 'Редактирование' : model.status_translate }}
            </v-chip>
            <tooltip-button
              v-show="model.status_id !== 1 && accessGrantedAuthorities(model.view_print_sdiz_privileges)"
              class="float-left ml-1 mt-1"
              tooltip-text="Печать"
              type="print"
              @click="isPrintModal = true"
            />

            <tooltip-button
              v-if="model.status_id === 1 && !isEdit && isOwnerSdiz"
              class="float-left ml-1 mt-1"
              tooltip-text="Редактировать"
              type="edit"
              @click="$emit('handleEdit', 'edit')"
            />
          </template>
        </text-component>
      </v-col>
    </template>

    <!---- END BLOCK TITLE ---->

    <!---- START BLOCK INFO ---->
    <template>
      <v-col cols="12" v-bind="enterDateColumnAttrs" style="z-index: 1">
        <UiDateInput v-model="model.enter_date" :format="'DD.MM.YYYY'" :disabled="true" label="Дата оформления" />
      </v-col>
      <v-col cols="12" v-bind="ownerColumnAttrs">
        <input-component
          v-if="isCreate"
          :disabled="true"
          :value="subjectNameFormatted"
          :tooltip="subjectNameFormatted"
          label="Уполномоченное лицо"
        />

        <ManufacturerAutocomplete
          v-else
          :key="model.owner_id"
          :value="model.owner_id"
          label="Уполномоченное лицо"
          show-name-in-tooltip
          is-disabled
        />
      </v-col>
    </template>
    <!---- END BLOCK INFO ---->

    <!---- START BLOCK OPERATIONS ---->
    <template>
      <v-col cols="12">
        <text-component>Операция:</text-component>
      </v-col>
      <v-col cols="12">
        <v-row justify-md="start" justify-sm="space-between">
          <button-component
            v-for="item in model.objects.operations.prototypes"
            :key="item.name"
            :class="{ 'select-product-sdiz': model.objects.operations.prototype_sdiz === item.id }"
            :disabled="isDisabledOperationsPrototypes()"
            :title="item.name"
            class="btn-operation ma-2"
            @click="onSelectedPrototype(item.id)"
          />
        </v-row>
      </v-col>
      <v-col cols="12">
        <v-row justify-md="start" justify-sm="space-between">
          <v-col v-for="operation in operationsSdiz" :key="operation.name" cols="12" md="2" sm="3">
            <checkbox-component
              v-model="model.objects.operations.detail[operation.operation_name]"
              :disabled="isDisabledOperations(parseInt(operation.code))"
              :label="operation.name"
              class="text-capitalize"
            />
          </v-col>
        </v-row>
      </v-col>
    </template>
    <!---- END BLOCK OPERATIONS ---->

    <v-expansion-panels v-model="tabs" accordion class="mt-5 exp-panel" multiple>
      <v-expansion-panel v-for="(item, i) in countTabs" :key="i" :readonly="onItemExistsInArray(i, readonlys)">
        <v-expansion-panel-header :class="[onItemExistsInArray(i, disableds) ? 'text--disabled' : '']">
          <v-col cols="12">
            <v-row>
              <v-col cols="8" md="7" sm="12">
                <text-component variant="h6" class="text-decoration-underline text-hover">{{
                  titles[i]
                }}</text-component>
                <button-component
                  v-show="isChangeElementsInFormAndSetHowShowForm && isSelectedLot && i === 0"
                  :variant="!isSelectedLot ? 'primary' : 'default'"
                  class="ml-0 mt-3 mb-3"
                  :title="titleButtonChangeLot"
                  @click="isOpenModalForFindLot = !isOpenModalForFindLot"
                />
              </v-col>

              <v-col
                v-show="isChangeElementsInFormAndSetHowShowForm"
                class="text--disabled pt-3 text-md-left text-sm-center"
                cols="4"
                md="5"
                sm="12"
              >
                <v-fade-transition leave-absolute>
                  <text-component :class="['text-subtitle-2', subtitles[i] === '' ? 'orange--text' : '']">
                    <template v-if="onItemExistsInArray(i, disableds)">{{ subtitles[i] }}</template>
                    <template v-if="i === 0 && !isSelectedLot">
                      <v-col v-show="onItemExistsInArray(i, tabs)"> Выберите партию </v-col>
                    </template>
                  </text-component>
                </v-fade-transition>
              </v-col>
            </v-row>
          </v-col>
          <template #actions>
            <i
              ><img
                :class="['icon', 'arrow', onItemExistsInArray(i, tabs) ? 'active' : 'deactive']"
                alt=""
                src="/icons/arrow.svg"
            /></i>
          </template>
        </v-expansion-panel-header>

        <!---------------------------------------------->
        <!---------------------ФОРМЫ-------------------->
        <!---------------------------------------------->
        <v-expansion-panel-content>
          <!---------------------------------------------->
          <!-------------ФОРМА ПАРТИИ ЗЕРНА--------------->
          <!---------------------------------------------->
          <v-row v-if="i === 0" class="pt-5">
            <v-col v-show="isChangeElementsInFormAndSetHowShowForm && !isSelectedLot" cols="3">
              <button-component
                class="ml-0 mt-3 mb-3 btn-custom"
                size="micro"
                :title="titleButtonAddLot"
                variant="primary"
                @click="isOpenModalForFindLot = !isOpenModalForFindLot"
              />
            </v-col>
            <v-col v-show="isSelectedLot" cols="12">
              <lot-form
                v-model="modelGetObjectLot"
                :is-detail="true"
                :is-edit="false"
                :hide-sdiz-tab="true"
                :hide-ammends-menu="hideAmmendsMenu"
              >
                <template #[`manufacture-field`]="{ onSearchUpdateMix, items, model, disabled }">
                  <slot
                    :items="items"
                    :model="model"
                    :disabled="disabled"
                    :on-search-update-mix="onSearchUpdateMix"
                    name="manufacture-field"
                  />
                </template>

                <template #[`date-create`]="{ model, disabled }">
                  <slot :model="model" :disabled="disabled" name="date-create" />
                </template>

                <template #[`product-type-field`]="{ model }">
                  <slot :model="model" :disabled="true" name="product-type-field" />
                </template>

                <template #gosmonitoring-number-field>
                  <slot :model="model" name="gosmonitoring-number-field" />
                </template>
              </lot-form>
            </v-col>
          </v-row>

          <!---------------------------------------------->
          <!------------ФОРМА ДЛЯ ЭЛЕВАТОРА--------------->
          <!---------------------------------------------->
          <v-row v-show="isShowFormForElevator && i === 1 && model.objects.operations.detail.acceptance" class="mt-5">
            <v-row class="flex-wrap mb-3" no-gutters>
              <v-col class="pa-2" cols="4">
                <UiDateInput
                  v-model="model.objects.storage_agreement.date"
                  :disabled="!isChangeElementsInFormAndSetHowShowForm"
                  label="Дата"
                  :format="'DD.MM.YYYY'"
                  placeholder="Выберите дату"
                />
              </v-col>
              <v-col class="pa-2" cols="4">
                <input-component
                  v-model="model.objects.storage_agreement.number"
                  :disabled="!isChangeElementsInFormAndSetHowShowForm"
                  label="Номер договора"
                  placeholder="Ведите номер договора"
                />
              </v-col>
              <v-col class="pa-2" cols="4">
                <select-request-component
                  v-model="model.objects.storage_agreement.type"
                  :disabled="!isChangeElementsInFormAndSetHowShowForm"
                  label="Тип хранения"
                  placeholder="Выберите тип хранения"
                  type="storage-type"
                  item-text-to-display="name"
                  is-return-object
                  preserve-data
                />
              </v-col>
              <v-col class="pa-2" cols="4">
                <input-component
                  v-model="model.objects.storage_agreement.area"
                  :disabled="!isSelectedTypeStore || !isChangeElementsInFormAndSetHowShowForm || isImpersonalStorage"
                  :placeholder="storageAreaPlaceholder"
                  label="Закрепленная площадь, м2"
                />
              </v-col>
              <v-col class="pa-2" cols="4">
                <UiDateInput
                  v-model="model.objects.storage_agreement.time_store"
                  :disabled="!isChangeElementsInFormAndSetHowShowForm"
                  label="Срок хранения"
                  :format="'DD.MM.YYYY'"
                  :limit-to="$moment().add(10, 'y').toDate()"
                  placeholder="Выберите срок хранения"
                />
              </v-col>
              <v-col class="pa-2" cols="4">
                <input-component
                  v-model="model.objects.storage_agreement.conditions"
                  :disabled="!isChangeElementsInFormAndSetHowShowForm"
                  label="Условия хранения"
                  placeholder="Введите условия хранения"
                />
              </v-col>
              <v-col class="pa-2" cols="6">
                <select-request-component
                  v-model="model.objects.storage_agreement.service"
                  :disabled="!isChangeElementsInFormAndSetHowShowForm"
                  label="Наименование вида услуг"
                  multiple
                  is-return-object
                  preserve-data
                  placeholder="Выберите наименование вида услуг"
                  type="elevator-service-type"
                />
              </v-col>
              <v-col class="pa-2" cols="6">
                <autocomplete-priority-address
                  v-model="model.objects.storage_agreement.place_id"
                  :is-disabled="!isChangeElementsInFormAndSetHowShowForm"
                  label="Место хранения партии зерна"
                  placeholder="Выберите хранения партии зерна"
                />
              </v-col>
              <v-col cols="12">
                <sdiz-element-moving-lot
                  v-model="model"
                  :is-create="isCreate"
                  :is-edit="isEdit"
                  exl="3"
                  label="Перемещение партии зерна"
                />
              </v-col>
            </v-row>
          </v-row>

          <!---------------------------------------------->
          <!------ФОРМА ДЛЯ ТОВАПРОПРОИЗВОДИТЕЛЯ---------->
          <!---------------------------------------------->
          <template v-if="!isShowFormForElevator">
            <sdiz-block-realization
              v-if="i === 1"
              v-model="model"
              :is-create="isEdit ? false : isCreate"
              :is-edit="isEdit"
            />

            <sdiz-block-additional-info
              v-if="i === 2"
              v-model="model"
              :is-create="isEdit ? false : isCreate"
              :is-edit="isEdit"
              class="pt-5"
            />
          </template>
        </v-expansion-panel-content>
      </v-expansion-panel>
    </v-expansion-panels>
    <!---- END BLOCK PANELS ---->

    <slot name="buttons" />

    <dialog-component
      v-model="isOpenAddModal"
      :prompt="false"
      cancel-title=""
      confirm-title=""
      controls-justify="justify-end"
      width="800"
      with-close-icon
    >
      <template #title> Сведения о добавляемой организации</template>
      <template #content>
        <sdiz-organization v-if="isOpenAddModal" @close="isOpenAddModal = false" />
      </template>
    </dialog-component>
    <lot-modal-find-lots
      :key="isOpenModalForFindLot"
      v-model="modelGetObjectLot"
      :list-api-endpoint="model.link_find_items_in_modal"
      :filter-by-first-selected-lots="filtersFromFirstSelectedLot"
      :is-open-find-lot="isOpenModalForFindLot"
      :selected-lots="selectedLots"
      :sdiz-type-lot="sdizTypeLot"
      is-for-sdiz
      @isOpenFindLot="onShowModalFindLot"
      @onSelectLot="onSelectLot"
    />
    <PrintModal v-model="isPrintModal" :measure-id="model.id" :service="model.export_pdf_service" />
  </v-row>
</template>

<script lang="ts">
import { Component, Mixins, Model, Prop, Watch } from 'vue-property-decorator';
import TextComponent from '@/components/common/TextComponent.vue';
import ButtonComponent from '@/components/common/buttons/DefaultButton.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import { SdizVueModel } from '@/models/Sdiz/Data/Sdiz.vue';
import CheckboxComponent from '@/components/common/inputs/CheckboxComponent.vue';
import UiDateInput from '@/components/global/UiForm/components/UiDateInput.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import SdizBlockRealization from '@/views/Sdiz/components/Subcomponents/Blocks/SdizBlockRealization.vue';
import SdizBlockAdditionalInfo from '@/views/Sdiz/components/Subcomponents/Blocks/SdizBlockAdditionalInfo.vue';
import AutocompletePriorityAddress from '@/components/PriorityAddress/PriorityAddress.vue';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import LotForm from '@/views/Lot/components/Form.vue';
import LotModalFindLots from '@/views/Lot/components/Subcomponents/LotModalFindLots.vue';
import isUndefined from 'lodash/isUndefined';
import pull from 'lodash/pull';
import omit from 'lodash/omit';
import { LotDataVueModel } from '@/models/Lot/Data/LotData.vue';
import { SdizGpbVueModel } from '@/models/Sdiz/Data/SdizGpb.vue';
import SdizElementMovingLot from '@/views/Sdiz/components/Subcomponents/Elements/SdizElementMovingLot.vue';
import SdizOrganization from '@/views/Sdiz/components/Organization.vue';
import { PermissionMix } from '@/utils/mixins/permission';
import { SdizElevatorModel } from '@/models/Sdiz/Data/SdizElevator.vue';
import { LotElevatorDataVueModel } from '@/models/Lot/Data/LotElevatorData.vue';
import { LotGpbDataVueModel } from '@/models/Lot/Data/LotGpbData.vue';
import { LotsMovedVueModel } from '@/models/Lot/LotsMoved.vue';
import { SdizCarrierModel } from '@/models/Sdiz/SdizCarrier';
import { addSelectedLot } from '@/utils/addSelectedLot';
import SelectComponent from '@/components/common/inputs/SelectComponent.vue';
import ManufacturerAutocomplete from '@/components/ManufacturerAutocomplete/ManufacturerAutocomplete.vue';
import { formatContragent } from '@/utils/formatContragent';
import PrintModal from '@/components/PrintModal/PrintModal.vue';
import TooltipButton from '@/components/common/buttons/TooltipButton.vue';
import { PrototypeSdizEnum, SdizTypeLot } from '@/models/Sdiz/Operations.vue';
import { DictionariesMix } from '@/utils/mixins/dictionaries';
import { StorageTypeEnum } from '@/utils/enums/StorageTypeEnum';

export type DataSelectLot = {
  lot: LotDataVueModel | LotElevatorDataVueModel | LotGpbDataVueModel;
};

@Component({
  name: 'sdiz-form',
  components: {
    TooltipButton,
    PrintModal,
    ManufacturerAutocomplete,
    SdizOrganization,
    SdizElementMovingLot,
    LotModalFindLots,
    LotForm,
    SdizBlockAdditionalInfo,
    SdizBlockRealization,
    AutocompletePriorityAddress,
    UiDateInput,
    SelectRequestComponent,
    InputComponent,
    CheckboxComponent,
    DialogComponent,
    ButtonComponent,
    TextComponent,
    SelectComponent,
  },
  mixins: [PermissionMix],
})
export default class SdizForm extends Mixins(DictionariesMix) {
  @Model('change', { type: Object, required: true }) model!: SdizGpbVueModel | SdizVueModel | SdizElevatorModel;

  @Prop({ default: false }) isDetail!: boolean;

  @Prop({ default: false }) isCreate!: boolean;

  @Prop({ default: false }) isEdit!: boolean;

  @Prop({ default: false }) isElevator!: boolean; // если юзер элеватор

  @Prop({ default: false }) isOwnerSdiz!: boolean;

  @Prop({ default: 'Оформление СДИЗ' }) titlePageCreate!: string;

  @Prop({ type: String, default: 'СДИЗ' }) documentTypeName!: string;

  @Prop({ default: 'Изменить партию' }) titleButtonChangeLot!: string;

  @Prop({ default: 'Выбрать партию' }) titleButtonAddLot!: string;

  @Prop({ required: true }) subjectId!: number;

  @Prop({ type: Boolean, default: false }) readonly isForRegionalGovernment!: boolean;

  @Prop({ type: Boolean, default: false }) hideAmmendsMenu!: boolean;

  filtersFromFirstSelectedLot = {}; // Храним первый выбранный лот (для блокировки фильтров в модальном окне по выбранному лоту)

  selectedLotsTmp: LotsMovedVueModel[] = []; // Временное хранение ИД выбранных лотов

  isLoading = false;

  isOpenAddModal = false;

  isPrintModal = false;

  isOpenModalForFindLot = false; // открыто ли модально окно для поиска партий

  isShowFormForElevator = false; // показываем-ли мы форму СДИЗ для элеваторов или для товаропроизво-ей

  panels: number[] = []; // открытые табы

  readonlys: number[] = [1, 2, 3]; // открытые табы

  disableds: number[] = [1, 2, 3]; // открытые табы

  get subjectNameFormatted(): string {
    const subject = this.$store.state.auth.user.subject;
    return typeof subject === 'undefined' ? '-' : formatContragent(subject).name;
  }

  get sdizTypeLot(): SdizTypeLot | null {
    const prototypeSdiz = this.model.objects.operations.prototype_sdiz;

    if (this.isElevatorComponent) return SdizTypeLot.ELEVATOR;
    switch (prototypeSdiz) {
      case PrototypeSdizEnum.IN_RUSSIA:
        return SdizTypeLot.IN_RUSSIA;
      case PrototypeSdizEnum.IMPORT_TO_RUSSIA:
        return SdizTypeLot.IMPORT_TO_RUSSIA;
      case PrototypeSdizEnum.EXPORT_FROM_RUSSIA:
        return SdizTypeLot.EXPORT_FROM_RUSSIA;
      default:
        return null;
    }
  }

  get isElevatorComponent() {
    return this.model.component_name === 'sdiz_elevator';
  }

  get isFormElevator() {
    if (this.isElevator) {
      return false;
    }

    return this.isCreate || this.isDetail;
  }
  get modelGetObjectLot() {
    return this.model.getObjectLot();
  }

  /**
   * Возвращаем доступные операци для СДИЗА
   * @return object[]
   */
  get operationsSdiz() {
    return this.$store.getters['sdiz/getTypes']
      .filter((operation) => {
        let code = parseInt(operation.code);

        if (this.isElevator) {
          return code == 1 || code == 100;
        }

        switch (this.model.objects.operations.prototype_sdiz) {
          case 1:
            this.filtersFromFirstSelectedLot = {};
            break;
          case 2:
            this.filtersFromFirstSelectedLot = {};
            return code == 100 || code == 10;
          case 3:
            this.filtersFromFirstSelectedLot = {};
            return code == 1 || code == 10;
        }
        return true;
      })
      .map((value) => {
        return { ...value, operation_name: this.model.objects.operations.getType(parseInt(value.code)) };
      });
  }

  /**
   * Возвращает список ИД выбранных лотов
   * @return number[]
   */
  get selectedLots(): any[] {
    return this.selectedLotsTmp;
  }

  /**
   * Возвращает открытые табы
   * @return number[]
   */
  get tabs(): number[] {
    return this.panels;
  }

  /**
   * Добавляем индексы тех табов, которые должны быть открытым
   * @param val
   */
  set tabs(val) {
    this.panels = val;
  }

  /**
   * Закрытие/открытие табов
   * @param operations
   */
  @Watch('model.objects.operations.detail', { deep: true })
  watchChangeStateOperation(operations) {
    this.onChangeStateOperation(operations);
    this.onSetSubjectInByOperations();
    if (!(operations.shipping || operations.shipment)) {
      this.model.shipper_location_id = null;
    } else {
      this.setShipperLocation(this.model.getObjectLot());
    }
  }

  /**
   * Доступно-ли редактрование элементов в форме, редактирование или создание СДИЗа
   * Так же изменяем флаг, который отвечает за то какую форму показать юзеру, для элеваторов или товаро-ей
   *
   * @return boolean
   */
  get isChangeElementsInFormAndSetHowShowForm(): boolean {
    if (this.isCreate) {
      this.isShowFormForElevator = this.isElevator;
    }

    if (this.isEdit || this.isDetail) {
      this.isShowFormForElevator = this.model.elevator_creator; // берем данные из модели созданного СДИЗА
    }

    return this.isCreate || this.isEdit;
  }

  /**
   * Возвращаем количество табов, количество табов завсит от того под кем авторзован юзер или какой СДИЗ смотрит
   * Если юзер под ELEVATOR то мы показываем 1 форму
   * Если юзер открыл СДИЗ который создавал элеватор, то тоже показываем 1-ю форму
   * Иначе показываем 2 форму
   *
   * @return number
   */
  get countTabs(): number {
    return this.isShowFormForElevator ? 2 : 3;
  }

  /**
   * Возвращаем заголовок для табов
   *
   * @return string[]
   */
  get titles(): string[] {
    let title = this.model.getObjectLot().titleForFirstBlockSdiz;

    let additional_info =
      this.model.getObjectLot().id !== null ? `${title} ${this.model.getObjectLot().getLotNumber()}` : title;

    return this.isShowFormForElevator
      ? [additional_info, 'Информация из договора хранения партии зерна']
      : [additional_info, 'Сведения о реализации', 'Сведения о перевозке и (или) приемке и (или) отгрузке'];
  }

  /**
   * Возвращаем подзаголовок для табов
   *
   * @return string[]
   */
  get subtitles(): string[] {
    return this.isShowFormForElevator
      ? ['', 'Доступно при "Приемке"']
      : ['', 'Доступно только при "Реализации"', 'Доступно при "Отгрузке" и(или) "Перевозке" и(или) "Приемке"'];
  }

  /**
   * Если выбрана партия зерна
   *
   * @return boolean
   */
  get isSelectedLot(): boolean {
    return this.model.getObjectLot().id !== null;
  }

  get isImpersonalStorage() {
    const isImpersonalStorage = this.typeCode === StorageTypeEnum.IMPERSONAL;
    if (isImpersonalStorage) this.model.objects.storage_agreement.area = null;

    return isImpersonalStorage;
  }

  get isSelectedTypeStore() {
    return typeof this.typeCode === 'string';
  }

  get storageAreaPlaceholder() {
    switch (this.typeCode) {
      case StorageTypeEnum.ISOLATED:
        return 'Выберите площадь';
      case StorageTypeEnum.IMPERSONAL:
        return '-';
      default:
        return 'Выберите тип хранения';
    }
  }

  get movingTypeCode() {
    return (this.model.objects.storage_agreement?.moving_type as any)?.code;
  }

  get typeCode() {
    return (this.model.objects.storage_agreement?.type as any)?.code;
  }

  /**
   * Меняем состояние табов
   * @param operations
   */
  onChangeStateOperation(operations) {
    const { realization, acceptance, shipment, shipping } = operations;

    if (shipping && !this.model.carriers?.length) {
      this.model.carriers.push(new SdizCarrierModel());
    }

    if (realization) {
      pull(this.readonlys, 1);
      pull(this.disableds, 1);
    } else {
      if (this.readonlys.indexOf(1) === -1) {
        const index = this.tabs.indexOf(1);
        if (index > -1) {
          this.tabs.splice(index, 1);
          this.clearRealization();
        }
        this.readonlys.push(1);
        this.disableds.push(1);
      }
    }

    if (acceptance || shipment || shipping) {
      this.changeSupplyState(acceptance);
    } else {
      this.changeRealizationState();
    }
  }

  changeSupplyState(acceptance) {
    if (this.isShowFormForElevator && acceptance) {
      pull(this.readonlys, this.isShowFormForElevator ? 1 : 2);
      pull(this.disableds, this.isShowFormForElevator ? 1 : 2);
    } else {
      pull(this.readonlys, 2);
      pull(this.disableds, 2);
    }
  }

  changeRealizationState() {
    if (this.readonlys.indexOf(2) === -1) {
      this.readonlys.push(2);
      this.disableds.push(2);
      this.clearOtherBlock();
    }
  }

  clearOtherBlock() {
    this.model.consignee_id = null;
    this.model.consignee_location_id = null;
    this.model.seller_id = null;
    this.model.shipper_id = null;
    this.model.shipper_location_id = null;
    this.model.carriers = [];
    this.model.objects.docs_transports_other = [];
  }
  clearRealization() {
    this.model.eisz_contract_date = null;
    this.model.eisz_contract_number = null;
    this.model.eisz_number = null;
    this.model.eisz_number_checkbox_init = false;
    this.model.contract_date = null;
    this.model.contract_number = null;
    this.model.buyer_id = null;
    this.model.objects.docs_akt = [];
    this.model.objects.docs_other = [];
  }

  async created() {
    this.onChangeStateOperation(this.model.objects.operations.detail);
  }
  /**
   * Проверяем нужно-ли заблокировать тип операций.
   * Блокируем если передан параметр на реализацию в строке запроса, если СДИЗ на хранении, или если просмотр,
   */
  isDisabledOperationsPrototypes() {
    if (this.$route.query.realization !== undefined) return true;
    if ((this.isCreate || this.isEdit) && this.model.getObjectLot().id) return true;
    if (!this.isCreate && !this.isEdit) return true;
    if (this.model.component_name === 'sdiz_elevator') return true;
  }

  /**
   * Проверяем нужно-ли заблокировать чекбокс операций.
   * И далее блокируем чекбоксы исходя из выбранной операции
   * @param item
   */
  isDisabledOperations(item: number) {
    const realization = this.$route.query.realization;
    if (realization !== undefined && item !== 1000) return true;

    if (!this.isCreate && !this.isEdit) return true; // если не редактирование или создане, то блокируем всё
    if (this.$route.name === 'sdiz_elevator_detail') return true;

    const detail = this.model.objects.operations.detail;

    if (this.isElevator) {
      if (!detail.shipment && detail.acceptance && item === 1) {
        return true;
      }

      if (!detail.acceptance && detail.shipment && item === 100) {
        return true;
      }
    }

    switch (this.model.objects.operations.prototype_sdiz) {
      case 3:
      case 2:
        return item == 10; // если клк по чекбоксу перевозки был
    }

    return false;
  }

  /**
   * Подставляем компанию того юзера который создает СДИЗ в поля для заполенния
   *
   * @return void
   */
  onSetSubjectInByOperations(): void {
    const detail = this.model.objects.operations.detail;
    const operationsQuantity = Object.values(detail).filter((e) => !!e).length;
    const isSingleOperation = operationsQuantity === 1;

    if (!this.model.seller_id) {
      // если выбрана реализация, добавляем текущую компанию в продавца
      this.model.seller_id = detail.realization ? this.subjectId : null;
    }

    if (this.model.shipper_id === null) {
      if (detail.shipping) {
        // если перевозка, добавляем текущую компанию в грузоотправителя
        this.model.shipper_id = this.subjectId;
      } else if (detail.shipment && isSingleOperation) {
        // если только отгрузка, добавляем текущую компанию в грузоотправителя
        this.model.shipper_id =
          detail.shipment && !(detail.shipping && detail.realization && detail.acceptance) ? this.subjectId : null;
      }
    }

    // если только приемка, добавляем текущую компанию в грузополучателя
    if (this.model.consignee_id === null && isSingleOperation) {
      this.model.consignee_id =
        detail.acceptance && !(detail.shipping && detail.realization && detail.shipment) ? this.subjectId : null;
    }
  }

  /**
   * Проверяем наличие элемента в массиве (в нашем случае мы проверяем disableds, tabs, readonlys)
   *
   * @param item
   * @param array
   * @return boolean
   */
  onItemExistsInArray(item: number, array: number[]): boolean {
    return array.find((value) => value === item) !== undefined;
  }

  /**
   * Событие срабатывает при выборе операций (На территории РФ, ввоз и вывоз).
   * Закрываем табы, ставим false на все операции (перевозка, приемка и т.п.), далее проставляем новые данные в операции
   *
   * @param id number
   */
  onSelectedPrototype(id: number): void {
    this.tabs = [];
    this.model.objects.operations.prototype_sdiz = id;
    Object.keys(this.model.objects.operations.detail).forEach(
      (key) => (this.model.objects.operations.detail[key] = false)
    );

    switch (id) {
      case 3:
      case 2:
        this.model.objects.operations.detail.shipping = true;
        break;
    }

    this.onSetSubjectInByOperations();
  }

  updateRealizationQuery(data) {
    const currentRoute = this.$route;

    if (currentRoute.name === 'sdiz_create' && (data.lot as any).repository_id) {
      this.$router.push({ path: currentRoute.path, query: { ...currentRoute.query, realization: 'true' } });
    } else if (currentRoute.name === 'sdiz_create' && currentRoute.query.realization) {
      this.$router.push({ path: currentRoute.path, query: { ...omit(currentRoute.query, 'realization') } });
    }
  }

  setShipperLocation(lot) {
    // При формировании СДИЗ на ВВОЗ, в "пункт отправления" подставлять не "местоположение партии", а "происхождение"
    const location =
      this.model.objects.operations.prototype_sdiz === 2
        ? Number(lot.origin_location_id)
        : Number(lot.current_location_id);

    this.model.shipper_location_id = location;
  }

  async loadLot(data: DataSelectLot) {
    try {
      const { response } = await this.$store.dispatch(data.lot.show_apiendpoit, data.lot.id);

      const lotFullData = data.lot.getLotModel(response);
      if (!lotFullData?.id) throw new Error();
      return lotFullData;
    } catch (_e) {
      this.$notify({ group: 'sdiz', title: 'Ошибка при загрузке полной информации о партии', type: 'error' });
    }
  }

  /**
   * При закрытии модального окна, если выбрали партию зерна
   * Отключаем флаг открытие модального окна, записываем лот в модель СДИЗа
   *
   * @param data DataSelectLot
   */
  // eslint-disable-next-line max-lines-per-function
  async onSelectLot(data: DataSelectLot): Promise<void> {
    const lotFullData = await this.loadLot(data);
    const lotData = lotFullData ?? data.lot;

    this.isOpenModalForFindLot = false;
    this.model.setObjectLot(lotData);
    this.updateRealizationQuery(data);
    const tabs = this.tabs;
    tabs.splice(
      tabs.findIndex((v) => v === 1),
      1
    );
    tabs.splice(
      tabs.findIndex((v) => v === 0),
      1
    );

    if (this.isShowFormForElevator && this.model.objects.operations.detail.acceptance) {
      this.tabs.push(3);
    }
    this.selectedLotsTmp = [addSelectedLot(lotData)];
    const selectedOperationLength = Object.values(this.model.objects.operations.detail).filter((val) => !!val).length;

    if (!selectedOperationLength) {
      this.setShipperLocation(lotData);
    }
    if (
      selectedOperationLength &&
      (this.model.objects.operations.detail.shipping || this.model.objects.operations.detail.shipment)
    ) {
      this.setShipperLocation(lotData);
    }

    // при выборе партии на хранении, все галки кроме "Реализация", должны сниматься, и потом блокироваться.
    // upd: ZERNO-546, на странице элеватора слетающие чекбоксы - нежелательное поведение.
    if ((lotData as any).repository_id && !this.isElevator) {
      this.model.objects.operations.detail.shipment = false;
      this.model.objects.operations.detail.shipping = false;
      this.model.objects.operations.detail.acceptance = false;
    }
  }

  /**
   * Событие срабатывает при открытии модального окна по поиску зерна
   * @param value
   */
  onShowModalFindLot(value: boolean | undefined) {
    this.isOpenModalForFindLot = isUndefined(value) ? !this.isOpenModalForFindLot : value;
  }

  async actualizeStorageServices() {
    if (
      !Array.isArray(this.model.objects.storage_agreement.service) ||
      !this.model.objects.storage_agreement.service.length
    ) {
      return;
    }

    const storageAgreementServices = await Promise.all(
      this.model.objects.storage_agreement.service.map(async (item) => {
        item = await this.dictionaryRecordByCode('elevator-service-type', item.code || '');

        return item ? { ...item, label: item.name } : null;
      })
    );

    this.model.objects.storage_agreement.service = storageAgreementServices.filter((e) => !!e);
  }

  async actualizeStorageAgreementType() {
    if (typeof this.typeCode === 'string') {
      this.model.objects.storage_agreement.moving_type = await this.dictionaryRecordByCode(
        'storage-type',
        this.typeCode
      );
    }
  }

  async actualizeStorageAgreementMovingType() {
    if (typeof this.movingTypeCode === 'string') {
      this.model.objects.storage_agreement.moving_type = await this.dictionaryRecordByCode(
        'storage-type',
        this.movingTypeCode
      );
    }
  }

  @Watch('isEdit')
  async onIsEditChange(v) {
    if (v) {
      await Promise.all([
        this.actualizeStorageServices(),
        this.actualizeStorageAgreementType(),
        this.actualizeStorageAgreementMovingType(),
      ]);
    }
  }

  get ownerColumnAttrs() {
    return this.isForRegionalGovernment ? { md: '9', sm: '8', xl: '10' } : { md: '3', sm: '6', xl: '2' };
  }

  get enterDateColumnAttrs() {
    return this.isForRegionalGovernment ? { md: '3', sm: '4', xl: '2' } : { md: '3', sm: '6', xl: '2' };
  }
}
</script>

<style lang="scss">
@import './src/assets/styles/_variables';
@import './src/assets/styles/_mixins';
@import './src/assets/styles/_container';

img.icon {
  max-width: 100%;
  width: 15px;
  transition: all 0.25s;
}

img.icon.active {
  transform: rotate(90deg);
}

img.icon.deactive {
  transform: rotate(-90deg);
}

.img.icon {
  fill: $medium-grey-color;
}

.text-hover:hover {
  color: $gold-light-color;
  transition: color 0.2s;
}

.exp-panel {
  .v-expansion-panel-header {
    padding-left: 0;
  }

  .v-application--is-ltr .v-expansion-panel-header {
    border-bottom: 1px solid $darkened-grey-color;
  }

  .v-expansion-panels {
    border-top: 1px solid $darkened-grey-color;
    border-bottom: 1px solid $darkened-grey-color;
  }

  .v-expansion-panel-header {
    height: 100px !important;
  }

  .v-expansion-panel::before {
    box-shadow: none;
  }
}

.settingsSpan {
  background: none;
  border: none;
  margin-left: 14px;
  display: flex;
  align-items: center;
  font-size: 14px;
  line-height: 16px;
  color: $medium-grey-color !important;
  cursor: pointer;
  text-align: left;
}

button.btn.select-product-sdiz {
  background-color: $gold-light-color !important;
  border-color: $gold-light-color !important;
  color: $white-color !important;

  &:disabled {
    opacity: 0.5;
  }
}

.btn-operation {
  align-items: center;
  cursor: pointer;
  display: inline-flex;
  line-height: 20px;
  max-width: 100%;
  outline: none;
  overflow: hidden;
  padding: 0 12px !important;
  position: relative;
  text-decoration: none;
  transition-duration: 0.28s;
  transition-property: box-shadow, opacity;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  vertical-align: middle;
  white-space: nowrap;
  font-size: 14px;
  border-radius: 4px !important;
  height: 32px !important;
  font-weight: normal !important;
}

.btn-operation .btn-container span {
  align-items: center;
  display: inline-flex;
  height: 100%;
  max-width: 100%;
}
</style>
