<template>
  <v-container v-if="model.original_data !== null" v-show="Object.keys(model.original_data).length">
    <v-row>
      <v-col cols="12">
        <v-row>
          <v-col cols="12">
            <text-component class="title d-flex align-center" variant="span">
              Партия {{ model.getLotNumber() }}
              <v-chip :color="isEdit ? 'warning' : ''" class="ml-3" label>
                {{ isEdit ? 'Редактирование' : model.status_translate }}
              </v-chip>

              <tooltip-button
                v-if="model.status !== 'CREATE'"
                class="float-left ml-1 mt-1"
                tooltip-text="Печать"
                type="print"
                @click="isPrintModal = true"
              />

              <v-chip v-if="model.restriction_type" :class="{ 'ml-4': model.status === 'CREATE' }" color="warning">
                {{ model.getTranslateRestriction() }}
              </v-chip>
            </text-component>
          </v-col>
        </v-row>
      </v-col>
    </v-row>

    <lot-form
      v-if="isDataSet"
      v-model="model"
      :is-detail="isDetail"
      :is-edit="isEdit"
      :type="type"
      :sdiz-model="sdizModel"
      :is-ammend="isAmmend"
      :is-for-rou-apk="isForRouApk"
      :is-actions-access="isActionsAccess"
      :hide-sdiz-tab="hideSdizTab"
      :is-elevator-lot="isElevatorLot"
      @quality-indicators-loading="setIsLoading"
      @cancel-debit="initiateCancelDebit"
      @error="setError"
    >
      <template #[`manufacture-field`]="{ disabled, model }">
        <slot :disabled="disabled" :model="model" name="manufacture-field" />
      </template>

      <template #[`date-create`]="{ disabled, model, newKey }">
        <slot :disabled="disabled" :model="model" :new-key="newKey" name="date-create" />
      </template>

      <template #[`date-lot`]="{ model, disabled }">
        <slot :model="model" :disabled="disabled" name="date-lot" />
      </template>

      <template #gosmonitoring-number-field>
        <slot name="gosmonitoring-number-field" />
      </template>

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

      <template #[`product-type-field`]="{ isDisabledElement, onSelectedTypeOfCrop, model, isActive }">
        <slot
          :is-disabled-element="isDisabledElement"
          :model="model"
          :on-selected-type-of-crop="onSelectedTypeOfCrop"
          name="product-type-field"
          :is-active="isActive"
        >
          <select-request-component
            v-model="model.okpd2_id"
            :disabled="isDisabledElement"
            label="Вид с/х культуры"
            :is-active="isActive"
            :lot-type="model.getLotType()"
            :store-lot-type="model.getStoreLotType()"
            placeholder="Выберите вид с/х культуры"
            type="nsi-okpd2-msh"
            @onChange="onSelectedTypeOfCrop"
          />
        </slot>
      </template>

      <template #buttons>
        <v-col cols="12" class="mt-10">
          <v-row>
            <v-col v-show="isEdit || isAmmend" class="right-align mb-3 mr-4" cols="12">
              <text-component class="text-caption text-center orange--text" variant="span">
                {{ errorToShow }}
              </text-component>
            </v-col>
          </v-row>
          <v-row class="ma-0" justify="end">
            <slot name="goBackButton">
              <div>
                <button-component class="mr-7" size="micro" title="Вернуться в реестр" @click="openListLot" />
              </div>
            </slot>

            <template v-if="isActionsAccess">
              <template v-if="isShow">
                <template v-if="model.isSubscribed()">
                  <div>
                    <dropdown-button off-left-mrg size="micro" title="Еще">
                      <template #list>
                        <v-list-item v-for="(item, index) in buttonItems" :key="index" ripple @click="item.call">
                          <v-list-item-content>
                            <v-list-item-title>
                              {{ item.title }}
                            </v-list-item-title>
                          </v-list-item-content>
                        </v-list-item>
                      </template>
                    </dropdown-button>
                  </div>
                  <div>
                    <button-component
                      :loading="preloader || isLoading"
                      class="ml-7"
                      size="micro"
                      title="Сформировать СДИЗ"
                      variant="primary"
                      @click="openCreateSdizFromLot"
                    />
                  </div>
                </template>

                <div v-show="!isEdit && model.isNew()">
                  <modal-button
                    button-text="Удалить"
                    :loading-btn="preloader || isLoading"
                    modal-text="Вы действительно хотите удалить партию ?"
                    variant-text="h5"
                    @onResumeClick="deleteActionMix(model.id)"
                  />

                  <button-component class="ml-5" size="micro" title="Редактировать" @click="handleEdit" />
                  <button-component
                    size="micro"
                    title="Подписать"
                    :loading="preloader || isLoading"
                    variant="primary"
                    @click="handleSignatureModalOpen(lotSignatureType.SUBSCRIBE, model.id)"
                  />
                </div>

                <div v-show="isEdit">
                  <button-component class="ml-7" size="micro" title="Отмена" @click="handleEdit" />
                  <button-component
                    :disabled="isSaveBtnDisabled"
                    :loading="preloader || isLoading"
                    class="ml-7"
                    size="micro"
                    title="Сохранить"
                    variant="primary"
                    @click="handleUpdate"
                  />
                </div>
              </template>
            </template>

            <template v-if="showAmmendButtons">
              <button-component
                v-if="!isAmmend"
                title="Внести изменения"
                class="ml-5"
                size="micro"
                @click="initiateAmmend"
              />

              <template v-if="isAmmend">
                <button-component title="Отмена" class="ml-5" size="micro" @click="cancelAmmend" />
                <button-component
                  :disabled="isAmmendButtonDisabled"
                  title="Сохранить изменения"
                  class="ml-5"
                  size="micro"
                  @click="handleAmmend"
                />
              </template>
            </template>
          </v-row>
        </v-col>
      </template>
    </lot-form>

    <dialog-component
      v-model="isShowDebitModal"
      :prompt="false"
      cancel-title=""
      confirm-title=""
      width="500"
      with-close-icon
    >
      <template #title>
        <b>Списание остатка по партии</b>
      </template>
      <template #content>
        <v-row class="ma-0">
          <v-col cols="6">
            <label-component label="Номер партии" />
            <span>{{ model.getNumber() }}</span>
          </v-col>
          <v-col cols="6">
            <label-component label="Масса текущая, кг" />
            <span>{{ model.amount_kg_mask }}</span>
          </v-col>
          <v-col cols="6">
            <label-component label="Масса доступная, кг" />
            <span>{{ model.amount_kg_available_mask }}</span>
          </v-col>
          <v-col cols="12">
            <WeightInput
              v-model="amountKgDebitMask"
              placeholder="Введите массу списания"
              :disabled="!(model.amount_kg_available > 0)"
            />
          </v-col>
          <v-col cols="12">
            <label-component label="Масса доступная после списания, кг" />
            <span>{{ getAmountDebit }}</span>
          </v-col>

          <v-col cols="12">
            <select-request-component
              v-model="reason_id"
              :required="true"
              label="Причина списания"
              placeholder="Выберите причину списания"
              type="reason-write-off"
              is-id
            />
          </v-col>

          <v-col cols="12">
            <text-area-component
              v-model="note"
              label="Примечание"
              placeholder="Введите примечание (Максимум 250 символов)"
            />
          </v-col>

          <v-col cols="12">
            <v-row>
              <v-col cols="6">
                <button-component title="Отмена" @click="changeStateDebitModal" />
              </v-col>
              <v-col class="text-right" cols="6">
                <button-component
                  :disabled="amount_kg_debit <= 0 || !reason_id || amountKgDebitError || validate(amountKgDebitMask)"
                  :loading="preloader || isLoading"
                  title="Списать"
                  variant="primary"
                  @click="handleDebit($event)"
                />
              </v-col>
            </v-row>
          </v-col>
        </v-row>
      </template>
    </dialog-component>

    <v-overlay :value="isLoading" z-index="10">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>

    <confirm-modal-delete
      :name="`№${model.getLotNumber()}`"
      :show-modal="isShowCancelConfirm"
      :text="'Вы действительно хотите аннулировать запись'"
      @apply="handleSignatureModalOpen(lotSignatureType.CANCEL, model.id)"
      @close="toggleCancelConfirmDialog"
    />

    <SignatureModal
      v-model="isSignatureModalOpen"
      :measure-id="measureId"
      @approve="handleSignApprove"
      @close="handleSignatureModalClose"
    />

    <PrintModal v-model="isPrintModal" :measure-id="model.id" :service="`${model.export_pdf_service}/pdf`" />
  </v-container>
</template>

<script lang="ts">
import { Component, Model, Prop, Mixins, Watch } from 'vue-property-decorator';
import Lot from '@/views/Lot/Lot.vue';
import ButtonComponent from '@/components/common/buttons/DefaultButton.vue';
import ModalButton from '@/components/common/buttons/ModalButton.vue';
import DropdownButton from '@/components/common/buttons/DropDownButton.vue';
import { LotDataVueModel } from '@/models/Lot/Data/LotData.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import TextComponent from '@/components/common/Text/Text.vue';
import LotForm from '@/views/Lot/components/Form.vue';
import LabelComponent from '@/components/common/Label/Label.vue';
import TextAreaComponent from '@/components/common/inputs/TextAreaComponent.vue';
import ConfirmModalDelete from '@/views/Authorities/components/Modal/ConfirmModalDelete.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import TooltipButton from '@/components/common/buttons/TooltipButton.vue';
import PrintModal from '@/components/PrintModal/PrintModal.vue';
import { getElementById } from '@/utils/methodsForViews';
import { applyMask, decimalNumberMask, decimalNumberUnmask } from '@/components/common/inputs/mask/decimalNumberMask';
import { LotGpbDataVueModel } from '@/models/Lot/Data/LotGpbData.vue';
import { LotElevatorDataVueModel } from '@/models/Lot/Data/LotElevatorData.vue';
import SignatureModal from '@/components/SignatureModal/SignatureModal.vue';
import { SdizVueModel } from '@/models/Sdiz/Data/Sdiz.vue';
import { SdizGpbVueModel } from '@/models/Sdiz/Data/SdizGpb.vue';
import { LotType } from '@/utils/enums/LotType';
import { loadQualityIndicatorsByParams, mergeQualityIndicators } from '@/utils/qualityIndicators';
import { GetDocumentForSignMix } from '@/utils/mixins/getDocumentForSign';
import { DebitVueModel } from '@/models/Lot/Debit.vue';
import { ERole } from '@/models/roles';
import { LotsPurposeEnum } from '@/utils/enums/lotsPurpose.enum';
import WeightInput from '@/views/Lot/components/Subcomponents/WeightInput.vue';
import { subtract } from '@/utils/decimals';
import { validate } from '@/components/common/inputs/mask/decimalNumberMask';

enum LotSignatureType {
  SUBSCRIBE = 1,
  CANCEL = 2,
  DEBIT = 3,
  DEBIT_CANCEL = 4,
  AMMEND = 5,
  NONE = 0,
}

@Component({
  name: 'lot-detail',
  components: {
    WeightInput,
    TooltipButton,
    PrintModal,
    SelectRequestComponent,
    InputComponent,
    ConfirmModalDelete,
    TextAreaComponent,
    LabelComponent,
    LotForm,
    TextComponent,
    DialogComponent,
    DropdownButton,
    ModalButton,
    ButtonComponent,
    SignatureModal,
  },
})
export default class LotDetail extends Mixins(Lot, GetDocumentForSignMix) {
  @Model('change', { type: Object }) value!: LotDataVueModel | LotGpbDataVueModel | LotElevatorDataVueModel;
  @Prop({ type: String, default: 'Аннулирование партии зерна' }) public titleCancel!: string;
  @Prop({ type: String, default: 'Списание остатков по партии зерна' }) public titleChangeStateDebit!: string;
  @Prop({ type: String, default: 'Сформировать партию из партии' }) public titleCreateLotFromLot!: string;
  @Prop({ type: String, default: 'lot/update' }) public linkUpdate!: string;
  @Prop({ type: String, default: 'lot_create_from_another_batch' }) public linkCreateFromAnotherBatch!: string;
  @Prop({ type: Boolean, default: true }) readonly isShow!: boolean;
  @Prop({ type: Boolean, default: false }) readonly isExternalValue!: boolean;
  @Prop({ type: Object }) sdizModel!: SdizVueModel | SdizGpbVueModel;
  @Prop({ type: Boolean, default: false }) hideSdizTab!: boolean;
  @Prop({ type: Boolean, default: false }) isForRouApk!: boolean;
  @Prop({ type: Boolean, default: false }) isElevatorLot!: boolean;

  isShowDebitModal = false;
  /** Открыт ли диалог подтверждения аннулирования. */
  isShowCancelConfirm = false;
  isDetail = true;
  isEdit = false;
  isLoading = true;
  amount_kg_debit: number | null = null;
  reason_id = 0;
  note = '';
  noticeGroup = 'lot';
  amount_kg_debit_mask = '';
  isSignatureModalOpen = false;
  isPrintModal = false;
  measureId: number | null = null;
  typeSignature: LotSignatureType = LotSignatureType.NONE;
  lotSignatureType = LotSignatureType;
  debitData: any = null;
  /** Временное хранение состояния потребсвойств */
  previousQualityIndicators: any[] = [];
  /** Флаг состояния внесения изменений */
  isAmmend = false;
  isDataSet = false;
  validate = validate;

  setError(v) {
    this.disableActions = v;
  }

  get userSubjectId() {
    const user = this.$store.getters['auth/getUserInfo'];
    return user?.subject?.subject_id || null;
  }

  get lotOwnerId() {
    return this.value.owner_id;
  }

  get lotRepositoryId() {
    return (this.value as any).repository_id;
  }

  get isActionsAccess(): boolean {
    const checkActionsAccessForLot = () => {
      if (!this.userSubjectId) return false;

      // если организация указана хранителем партии
      if (this.lotRepositoryId) return this.userSubjectId === this.lotRepositoryId;
      else {
        // если организация является владельцем партии и не указан хранитель у партии
        return this.lotOwnerId === this.userSubjectId;
      }
    };

    const checkActionsAccessForLotGpb = () => {
      if (!this.userSubjectId) return false;

      return this.lotOwnerId === this.userSubjectId;
    };

    let isAccess;

    switch (this.value.component_name) {
      case 'lot':
        isAccess = checkActionsAccessForLot();
        break;
      case 'lot_ppz':
        isAccess = checkActionsAccessForLotGpb();
        break;
      default:
        isAccess = checkActionsAccessForLot();
    }

    return isAccess;
  }

  get isOperatorRole() {
    // todo: Необходима роль оператора.
    return this.$store.getters['auth/roles'].includes(ERole.ROLE_ADMIN);
  }

  get buttonItems() {
    const buttons = [
      {
        title: this.titleChangeStateDebit,
        call: () => this.changeStateDebitModal(),
      },
    ];

    const lotFromLotButton = {
      title: this.titleCreateLotFromLot,
      call: () => this.openCreateLotFromLot(),
    };

    if (this.model.purposeCode !== LotsPurposeEnum.IMPORT_TO_RUSSIA) {
      buttons.push(lotFromLotButton);
    }

    const notCancellableLotTypes = ['EXTINGUISH', 'PART'];

    return this.value.type && notCancellableLotTypes.includes(this.value.type)
      ? buttons
      : [
          {
            title: this.titleCancel,
            call: () => this.toggleCancelConfirmDialog(),
          },
          ...buttons,
        ];
  }

  /**
   * Загрузить pdf-документ и открыть модалку подписи
   * @param type вид операции подписи
   * @param measureId PK подписываемой сущности
   */
  async handleSignatureModalOpen(type: LotSignatureType, measureId: number | null): Promise<void> {
    this.typeSignature = type;
    this.measureId = measureId;
    switch (type) {
      case LotSignatureType.SUBSCRIBE:
        await this.getNewOrStoredDocument(measureId, this.model.export_pdf_service + '/progect');
        break;
      case LotSignatureType.CANCEL:
        this.isShowCancelConfirm = false;
        await this.getNewOrStoredDocument(measureId, this.model.export_pdf_service_cancel);
        break;
      case LotSignatureType.DEBIT:
        await this.prepareDocumentFromDescription(
          this.model.export_pdf_service_debit_from_description,
          this.formatDebitData()
        );
        break;
      case LotSignatureType.DEBIT_CANCEL:
        await this.getNewOrStoredDocument(measureId, this.model.export_pdf_service_debit_cancel);
        break;
      case LotSignatureType.AMMEND:
        await this.prepareDocumentFromDescription(
          this.model.export_pdf_service_ammend_from_description,
          this.model.getDataForVersionPdf()
        );
        break;
    }
    this.isSignatureModalOpen = true;
  }

  async initiateCancelDebit(debitData: DebitVueModel) {
    await this.handleSignatureModalOpen(LotSignatureType.DEBIT_CANCEL, debitData.id);
  }

  async handleSignApprove() {
    this.isSignatureModalOpen = false;

    switch (this.typeSignature) {
      case LotSignatureType.SUBSCRIBE:
        await this.handleSubscribed();
        break;
      case LotSignatureType.CANCEL:
        await this.handleCanceled();
        break;
      case LotSignatureType.DEBIT:
        await this.createDebit();
        break;
      case LotSignatureType.DEBIT_CANCEL:
        await this.handleCancelDebit();
        break;
      case LotSignatureType.AMMEND:
        await this.createHistoryVersion();
        break;
    }

    await this.loadIndicatorsProperties();
  }

  decimalNumberUnmask = decimalNumberUnmask;

  decimalNumberMask = decimalNumberMask;

  model: any = this.innerValue;

  get innerValue(): any {
    return this.value;
  }

  set innerValue(value: any) {
    this.$emit('change', value);
  }

  get getAmountDebit() {
    if (this.amount_kg_debit !== null && this.model.amount_kg_available !== null) {
      const debit = subtract(this.model.amount_kg_available, this.amount_kg_debit);
      return debit < 0 ? 0 : applyMask(debit, true);
    }
    return 0;
  }

  get amountKgDebitMask() {
    return this.amount_kg_debit_mask;
  }

  set amountKgDebitMask(value) {
    const unmasked = decimalNumberUnmask(value);
    const isValidationError = this.onValidateInputFromDebit(unmasked);

    if (isValidationError) {
      this.amount_kg_debit = this.model.amount_kg_available;
    } else {
      this.amount_kg_debit_mask = value;
      this.amount_kg_debit = decimalNumberUnmask(value);
    }

    this.amountKgDebitError = isValidationError;
  }

  amountKgDebitError = false;

  get isSaveBtnDisabled() {
    return this.disableActions || this.getBaseValidationError !== null;
  }

  get getBaseValidationError() {
    return this.getErrors.length > 0 ? this.getErrors.shift() : null;
  }

  get ammendValidationError() {
    const errors = this.model.getVersionCreationErrors();
    return errors.length > 0 ? errors.shift() : null;
  }

  get errorToShow() {
    return this.isAmmend ? this.ammendValidationError : this.getBaseValidationError;
  }

  get isAmmendButtonDisabled() {
    return this.ammendValidationError !== null;
  }

  // TODO вернуть проверку в модель, не в компонент
  get getErrors(): Array<string> {
    return this.model.getErrors(this.type);
  }

  async created() {
    if (!this.isExternalValue) await getElementById(this, parseInt(this.$route.params.id));

    const openedByRepository = this.model.repository_id && this.userSubjectId === this.model.repository_id;
    if (openedByRepository && this.$route.name === 'lot_detail') {
      return await this.$router.push({ name: 'lot_elevator_detail' });
    }

    this.type = LotType[this.model.type] || null;

    this.isDataSet = true;

    await this.fetchOkpd2Msh(this.model.lotType, false);

    this.innerValue = this.model;

    if (
      this.model.purposeCode === LotsPurposeEnum.IMPORT_TO_RUSSIA ||
      this.model.purposeCode === LotsPurposeEnum.EXPORT_FROM_RUSSIA
    ) {
      this.buttonItems.pop();
    }
    this.isLoading = !this.isLoading;
  }

  handleSignatureModalClose() {
    if (this.typeSignature === LotSignatureType.DEBIT) this.clearDebitFields();

    this.typeSignature = LotSignatureType.NONE;
    this.measureId = null;
  }

  /**
   * Запрос на сохранение цифровой подписи
   * @param service эндпоинт для подписи сущности
   * @param id PK подписываемой сущности
   */
  async handleSubscriptionAction(service: string, id: number) {
    try {
      this.isLoading = true;

      await this.$store.dispatch('agreementDocument/signDocumentFromDescription', {
        id,
        service,
      });

      const error = this.$store.state.agreementDocument.agreementDocumentSign.error;

      if (error) {
        throw new Error(error);
      }

      this.$notify({ group: 'lot', type: 'success', title: 'Операция успешно выполнена' });
    } catch (_err) {
      this.$notify({ group: 'lot', type: 'error', title: 'Ошибка при выполнении операции' });
    } finally {
      this.measureId = null;
      await getElementById(this, parseInt(this.$route.params.id));
      this.isLoading = false;
    }
  }

  async handleCanceled(): Promise<void> {
    await this.handleSubscriptionAction(this.model.cancel_service, this.model.id);
  }

  async handleSubscribed(): Promise<void> {
    await this.handleSubscriptionAction(this.model.subscribe_service, this.model.id);
  }

  async handleDebit() {
    this.isShowDebitModal = false;
    await this.handleSignatureModalOpen(LotSignatureType.DEBIT, null);
  }

  clearDebitFields() {
    this.note = '';
    this.amount_kg_debit_mask = '';
    this.amount_kg_debit = null;
    this.reason_id = 0;
  }

  formatDebitData() {
    return { ...this.model.getPk(), amount_kg_debit: this.amount_kg_debit, reason_id: this.reason_id, note: this.note };
  }

  /**
   * Создать партию списания остатков с идентификатором подписи
   */
  async createDebit(): Promise<void> {
    try {
      this.isLoading = true;
      const esp_id = this.$store.state.agreementDocument.agreementDocumentSign?.data?.esp_id;

      if (!esp_id) throw new Error();

      const data = { ...this.formatDebitData(), esp_id };

      const { status } = await this.$store.dispatch(this.model.debit_service, data);
      if (!status) throw new Error();

      this.clearDebitFields();
      await getElementById(this, this.model.id as number);
    } catch (_e) {
      this.$notify({
        group: this.noticeGroup,
        type: 'warning',
        title: 'Ошибка при формировании запроса',
      });
    } finally {
      this.isLoading = false;
    }
  }

  async handleCancelDebit() {
    try {
      this.isLoading = true;
      await this.handleSubscriptionAction(this.model.debit_cancel_service, this.measureId as number);
      await getElementById(this, this.model.id as number);
    } catch (_e) {
      this.$notify({
        group: this.noticeGroup,
        type: 'warning',
        title: 'Ошибка при формировании запроса',
      });
    } finally {
      this.isLoading = false;
    }
  }

  async createHistoryVersion() {
    try {
      this.isLoading = true;
      const esp_id = this.$store.state.agreementDocument.agreementDocumentSign?.data?.esp_id;

      if (!esp_id) throw new Error();

      const data = { ...this.model.getDataForVersionCreation(), esp_id };

      const { status } = await this.$store.dispatch(this.model.create_ammend_api_endpoint, data);
      if (!status) throw new Error();

      this.isAmmend = false;
      await getElementById(this, this.model.id as number);
      this.model.selectLatestHistoryVersion();
    } catch (_e) {
      this.$notify({ group: this.noticeGroup, type: 'warning', title: 'Ошибка при формировании запроса' });
    } finally {
      this.isLoading = false;
    }
  }

  async handleUpdate(): Promise<void> {
    try {
      this.preloader = true;
      this.isLoading = true;

      const data = this.model.getDataForUpdate();
      if (this.model.getTypeModel() === 'LotElevator') data.is_elevator_page = true;

      const { status, response } = await this.$store.dispatch(this.linkUpdate, {
        data: data,
        id: this.model.id,
      });

      if (status) {
        this.model = new this.model.constructor(response);
        this.$notify({ group: this.noticeGroup, type: 'success', title: 'Операция успешно выполнена', text: '' });
        this.isDetail = true;
        this.isEdit = false;
      } else {
        this.$notify({ group: this.noticeGroup, type: 'error', title: 'Ошибка при обновлении партии зерна', text: '' });
      }
    } catch (_e) {
      this.$notify({ group: this.noticeGroup, type: 'warning', title: 'Ошибка при формировании запроса', text: '' });
    } finally {
      this.isLoading = false;
      this.preloader = false;
    }
  }

  onValidateInputFromDebit(value): boolean {
    if (value !== null && this.model.amount_kg_available !== null) {
      const debit = subtract(this.model.amount_kg_available, value);
      return debit < 0;
    }

    return false;
  }

  openCreateLotFromLot(): void {
    this.$router.push({ name: this.linkCreateFromAnotherBatch, query: { lot_id: this.model.id } });
  }
  openListLot(): void {
    let lot_list = '';
    if (this.model.sdiz_create === 'sdiz_elevator_create') {
      if (this.model.operator_id !== this.$store.state.auth.user.id) {
        lot_list = 'lot_list';
      } else {
        lot_list = this.model.name_route_list;
      }
    } else {
      lot_list = this.model.name_route_list;
    }
    this.$router.push({ name: lot_list });
  }

  openCreateSdizFromLot(): void {
    let sdiz_create = this.model.sdiz_create;
    let option: object = {};
    if (this.model.sdiz_create === 'sdiz_create' && this.model.repository_id) {
      option = { realization: true };
    }
    this.$router.push({
      name: sdiz_create,
      query: Object.assign(option, this.$route.query, { lot_id: this.model.id }),
    });
  }

  changeStateDebitModal(): void {
    this.isShowDebitModal = !this.isShowDebitModal;
    this.clearDebitFields();
  }

  toggleCancelConfirmDialog(): void {
    this.isShowCancelConfirm = !this.isShowCancelConfirm;
  }

  get countryAlpha() {
    return this.model.objects.country_destination.code_alpha2 ?? 'RU';
  }

  async loadIndicatorsProperties() {
    const quality_indicators = await loadQualityIndicatorsByParams({
      okpd2: this.model.objects.okpd2.code,
      purposes: this.model.qualityIndicatorPurposes,
      country_alpha2: this.countryAlpha,
    });

    this.model.objects.quality_indicators = mergeQualityIndicators(
      this.model.objects.quality_indicators,
      quality_indicators,
      ['valueTo', 'valueFrom', 'type', 'measure', 'values']
    );
  }

  async handleEdit(): Promise<void> {
    if (this.isEdit) {
      if (this.model.original_data.quality_indicators?.length) {
        this.model.original_data.quality_indicators.map((val) => {
          val.valueTo = 0;
          val.valueFrom = 0;
        });
      }
      this.modelSearch(this.model.original_data);
    }

    try {
      this.isLoading = true;

      if (!this.isEdit) {
        await this.loadIndicatorsProperties();
      }

      this.isEdit = !this.isEdit;
      if (!this.isEdit) {
        this.isDetail = true;
      }

      this.isLoading = false;
    } catch (_e) {
      this.$service.notify.push('error', {
        text: 'Ошибка загрузки потребительских свойств',
      });
      this.isLoading = false;
      this.isEdit = false;
      this.isDetail = true;
    }
  }

  modelSearch(content) {
    return (this.model = new this.model.constructor(content));
  }

  initiateAmmend() {
    this.isAmmend = true;
    this.model.ammendReason = '';
    this.model.selectedHistoryVersionId = null;
    this.previousQualityIndicators = this.model.objects.quality_indicators;
  }

  cancelAmmend() {
    this.isAmmend = false;
    this.model.ammendReason = '';
    this.model.selectLatestHistoryVersion();
    this.model.objects.quality_indicators = this.previousQualityIndicators;
  }

  async handleAmmend() {
    await this.handleSignatureModalOpen(LotSignatureType.AMMEND, null);
  }

  get showAmmendButtons() {
    const disallowedStatuses = ['CREATE', 'CANCELED'];
    return !disallowedStatuses.includes(this.model.status) && this.isOperatorRole;
  }

  @Watch('model.objects.sdiz_data.items')
  async checkPaperSdizValidNumber(newVal: any, oldVal: any): Promise<void> {
    if (this.isEdit) {
      this.isLoading = true;
      if (oldVal[0]?.lot_sp_number !== newVal[0]?.lot_sp_number && newVal[0]?.lot_sp_number) {
        const { response } = await this.$store.dispatch(this.value.reserve_number_apiendpoit, {
          data: {
            filter: {
              options: [
                {
                  field: this.value.number_type,
                  operator: '=',
                  value: this.value.objects.sdiz_data?.items[0]?.lot_sp_number,
                },
              ],
            },
          },
        });

        this.model.valid_paper_sdiz_number = response.length > 0;
      }
      this.isLoading = false;
    }
  }
}
</script>
