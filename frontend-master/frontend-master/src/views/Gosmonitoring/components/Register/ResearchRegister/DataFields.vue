<template>
  <v-container v-if="watchForm(model)">
    <v-row>
      <v-col cols="12">
        <text-component v-if="!detail" class="title d-flex align-center" variant="span">
          <span v-show="edit">Редактирование данных об исследовании №{{ model.id }}</span>
          <span v-show="!edit">Внесение данных об исследовании</span>
        </text-component>
        <text-component v-else class="title d-flex align-center" variant="span">
          Результат исследования «{{ resultTitle }}»
          <v-chip class="ml-3" label>{{ model.status_name }}</v-chip>

          <tooltip-button
            v-show="model.status_id !== 1"
            class="float-left ml-1 mt-1"
            tooltip-text="Печать"
            type="print"
            @click="isPrintModal = true"
          />
        </text-component>
      </v-col>

      <v-col cols="12">
        <v-row>
          <template v-if="model.historyVersions.length > 1 && !isAmmend">
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

          <v-col cols="4" v-if="isAmmend">
            <input-component
              v-model="model.ammendReason"
              label="Основание для внесения исправлений"
              placeholder="Укажите основание для внесения исправлений"
            />
          </v-col>

          <v-col cols="4" xl="3" lg="4" md="6" sm="12">
            <UiDateInput
              v-model="model.date_check"
              :disabled="isDisabledField"
              :limit-to="today"
              :format="'DD.MM.YYYY'"
              label="Дата проведения отбора проб"
              placeholder="Выберите дату"
            />
          </v-col>

          <v-col cols="4" xl="3" lg="4" md="6" sm="12">
            <input-component
              v-model="model.number_check"
              :disabled="isDisabledField"
              label="Номер акта отбора проб"
              placeholder="Введите номер акта отбора проб"
            />
          </v-col>

          <v-col cols="4" xl="3" lg="4" md="6" sm="12">
            <ManufacturerAutocomplete
              v-model="model.owner_id"
              label="Товаропроизводитель"
              placeholder="Выберите товаропроизводителя"
              show-name-in-tooltip
              :is-disabled="isDisabledField"
              @change="handleOwnerIdChange"
            />
          </v-col>

          <v-col cols="4" xl="3" lg="4" md="6" sm="12">
            <autocomplete-priority-address
              v-show="!isDisabledField"
              v-model="model.place_of_checking_id"
              :is-disabled="isDisabledField"
              label="Место формирования партии в целях отбора проб"
              placeholder="Выберите местоположение"
            />
            <input-component
              v-show="isDisabledField"
              v-model="model.place_of_checking.address"
              :disabled="isDisabledField"
              label="Место формирования партии в целях отбора проб"
              type="text"
            />
          </v-col>

          <v-col cols="4" xl="3" lg="4" md="6" sm="12">
            <input-component
              v-model="model.number_grain_samples"
              :disabled="isDisabledField"
              label="Номер (шифр) пробы зерна"
              placeholder="Введите номер"
            />
          </v-col>

          <v-col cols="4" xl="3" lg="4" md="6" sm="12">
            <UiDateInput
              v-model="model.date_of_protocol_check"
              :disabled="isDisabledField"
              :limit-to="today"
              :limit-from="fromDate(model.date_check)"
              :format="'DD.MM.YYYY'"
              class="UiDateInput"
              label="Дата протокола исследований зерна"
              placeholder="Выберите дату"
            />
          </v-col>

          <v-col cols="4" xl="3" lg="4" md="6" sm="12">
            <input-component
              v-model="model.number_of_protocol_check"
              :disabled="isDisabledField"
              label="Номер протокола исследований зерна"
              placeholder="Введите номер протокола исследований зерна"
            />
          </v-col>

          <v-col cols="4" xl="3" lg="4" md="6" sm="12">
            <select-request-component
              v-model="model.target_id"
              :disabled="isDisabledField"
              label="Цель использования зерна"
              placeholder="Выберите цель использования"
              type="nsi-lots-target"
            />
          </v-col>

          <v-col cols="4" xl="3" lg="4" md="6" sm="12">
            <select-request-component
              v-model="model.okpd2_id"
              disabled
              label="Вид с/х культуры"
              placeholder="Выберите вид с/х культуры"
              type="nsi-okpd2-msh"
              :lot-type="{ is_grain: true }"
              store-lot-type="is_grain"
              :is-active="false"
              @onChange="onSelectTypeProduct"
            />
          </v-col>

          <v-col cols="4" xl="3" lg="4" md="6" sm="12">
            <LotNumbersAutocomplete
              v-model="model.lots_numbers_from_subject_id"
              :subject-id-filter="model.owner_id"
              :active-filter="!detail"
              :is-disabled="isDisabledField || model.owner_id === null"
              @select="handleLotsNumbersSelect"
            />
            <div
              v-show="model.owner_id === null && model.lots_numbers_from_subject_id === null"
              class="mt-2 text-center"
            >
              <small class="orange--text mt-10">Выберите товаропроизводителя</small>
            </div>
          </v-col>
          <v-col v-if="[2, 3].includes(model.status_id)" cols="4" xl="3" lg="4" md="6" sm="12">
            <input-component v-model="model.laboratory_monitor_number" disabled label="Номер исследования ГМ" />
          </v-col>

          <v-col v-if="[2, 3].includes(model.status_id)" cols="4" xl="3" lg="4" md="6" sm="12">
            <input-component v-model="model.amount_kg_original" disabled label="Масса, кг" />
          </v-col>

          <v-col cols="12">
            <v-row>
              <v-col cols="12" md="6" sm="12" xl="6">
                <lot-tables-quality-indicators
                  v-model="qualityIndicators"
                  :versions="model.historyVersions"
                  :is-edit="edit || create || isAmmend"
                  :okpd2-code="model.okpd2.code"
                  :purposes="purposes"
                  :is-restriction="true"
                />
              </v-col>
            </v-row>
          </v-col>

          <v-col class="mt-3" cols="12">
            <v-row v-show="edit || create || isAmmend" class="ma-0" justify="end">
              <v-col v-show="errorToShow !== null" class="right-align mr-3" cols="12">
                <text-component class="text-caption text-center orange--text" variant="span">
                  {{ errorToShow }}
                </text-component>
              </v-col>

              <default-button
                v-if="!isAmmend"
                :title="getNameCancelBtn"
                class="mr-7"
                size="micro"
                @click="
                  $router.push(
                    edit ? { name: model.update_apiendpoit, params: { id: model.id } } : { name: model.cancel_link }
                  )
                "
              />

              <default-button
                v-if="!isAmmend"
                :disabled="errors.length > 0 || isError"
                :loading="loading"
                :title="edit ? 'Сохранить' : 'Оформить'"
                size="micro"
                variant="primary"
                @click="$emit('event')"
              />
            </v-row>
            <v-row v-show="!edit">
              <v-col cols="4">
                <v-row v-show="!edit && showOtherBtn" class="ma-0" justify="start">
                  <default-button
                    v-if="!isAmmend"
                    :title="getNameCancelBtn"
                    class="ml-0"
                    size="micro"
                    @click="
                      $router.push(
                        edit ? { name: model.update_apiendpoit, params: { id: model.id } } : { name: model.cancel_link }
                      )
                    "
                  />
                </v-row>
              </v-col>
              <v-col cols="8">
                <v-row v-show="!edit" class="ma-0" justify="end">
                  <default-button
                    v-show="detail && !showOtherBtn && !isAmmend"
                    :class="{ 'mr-3': showOtherBtn }"
                    :title="getNameCancelBtn"
                    size="micro"
                    @click="
                      $router.push(
                        edit ? { name: model.update_apiendpoit, params: { id: model.id } } : { name: model.cancel_link }
                      )
                    "
                  />

                  <modal-button
                    v-if="showOtherBtn && accessGrantedAuthorities(model.delete_privileges) && !isAmmend"
                    button-text="Удалить"
                    modal-text="Вы действительно хотите удалить запись ?"
                    variant-text="h5"
                    @onResumeClick="$emit('onDelete')"
                  />

                  <default-button
                    v-show="showOtherBtn && !isAmmend"
                    :class="{ 'mr-3': showOtherBtn }"
                    title="Редактировать"
                    @click="routerUpdatePush()"
                  />

                  <default-button
                    v-if="isCreateLot && !isAmmend"
                    :variant="!showOtherBtn ? 'primary' : ''"
                    title="Создать партию"
                    @click="routerCreateLot()"
                  />

                  <button-component
                    v-if="showOtherBtn && accessGrantedAuthorities(model.sign_privileges)"
                    size="micro"
                    title="Подписать"
                    variant="primary"
                    @click="handleSignatureModalOpen(researchSignatureType.SUBSCRIBE, model.id)"
                  />
                  <button-component
                    v-if="isCancelBtn && accessGrantedAuthorities(model.cancel_privileges) && !isAmmend"
                    size="micro"
                    title="Аннулировать"
                    variant="primary"
                    @click="handleSignatureModalOpen(researchSignatureType.CANCEL, model.id)"
                  />

                  <template v-if="isShowAmmendButtons">
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
            </v-row>
          </v-col>
        </v-row>
      </v-col>
    </v-row>
    <SignatureModal v-model="isSignatureModalOpen" :measure-id="measureId" @approve="handleSignApprove" />

    <PrintModal v-model="isPrintModal" :measure-id="model.id" :service="`${model.export_pdf_service}/pdf`" />
  </v-container>
</template>

<script lang="ts">
import { Component, Model, Prop, Watch } from 'vue-property-decorator';
import LabelComponent from '@/components/common/Label/Label.vue';
import UiDateInput from '@/components/global/UiForm/components/UiDateInput.vue';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import { ResearchRegisterVueModel } from '@/models/Gosmonitoring/ResearchRegister.vue';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import { loadQualityIndicatorsByParams } from '@/utils/qualityIndicators';
import ActionsButtons from '@/components/Forms/ActionsButtons.vue';
import LotTablesQualityIndicators from '@/views/Lot/components/Subcomponents/Tables/LotTablesQualityIndicators.vue';
import { findElementInArray } from '@/utils/methodForArrays';
import { mixins } from 'vue-class-component';
import { FiasMix } from '@/utils/mixins/fias';
import { Manufactures } from '@/utils/mixins/manufactures';
import { AdditionalMix } from '@/utils/mixins/additional';
import { ActionsMix } from '@/utils/mixins/actions';
import ModalButton from '@/components/common/buttons/ModalButton.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import TextComponent from '@/components/common/Text/Text.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import ButtonComponent from '@/components/common/buttons/DefaultButton.vue';
import ConfirmModalDelete from '@/views/Authorities/components/Modal/ConfirmModalDelete.vue';
import AutocompletePriorityAddress from '@/components/PriorityAddress/PriorityAddress.vue';
import EditableTable from '@/components/common/Table/index.vue';
import PrintModal from '@/components/PrintModal/PrintModal.vue';
import TooltipButton from '@/components/common/buttons/TooltipButton.vue';
import { dateFrom, tomorrow } from '@/utils/date';
import SignatureModal from '@/components/SignatureModal/SignatureModal.vue';
import { PermissionMix } from '@/utils/mixins/permission';
import { ConductedResearchRegisterVue } from '@/models/Gosmonitoring/ConductedResearchRegister.vue';
import ManufacturerAutocomplete from '@/components/ManufacturerAutocomplete/ManufacturerAutocomplete.vue';
import { Gosmonitoring } from '@/utils/mixins/gosmonitoring';
import { Okpd2VueModel } from '@/models/Sdiz/Okpd2.vue';
import { QualityIndicatorsVueModel } from '@/models/Lot/QualityIndicators.vue';
import { GetDocumentForSignMix } from '@/utils/mixins/getDocumentForSign';
import { ERole } from '@/models/roles';
import { IndicatorPurposeEnum } from '@/utils/enums/indicatorPurpose.enum';
import LotNumbersAutocomplete from '@/components/common/LotNumbersAutocomplete/LotNumbersAutocomplete.vue';
import { LotNumbersShortModel } from '@/models/Lot/LotNumbers.vue';

enum ResearchSignatureType {
  SUBSCRIBE = 1,
  CANCEL = 2,
  AMMEND = 3,
  NONE = 0,
}

@Component({
  name: 'data-fields',
  components: {
    LotNumbersAutocomplete,
    PrintModal,
    TooltipButton,
    EditableTable,
    ButtonComponent,
    AutocompletePriorityAddress,
    ConfirmModalDelete,
    SignatureModal,
    DefaultButton,
    TextComponent,
    DialogComponent,
    ModalButton,
    LotTablesQualityIndicators,
    ActionsButtons,
    AutocompleteComponent,
    LabelComponent,
    UiDateInput,
    InputComponent,
    SelectRequestComponent,
    ManufacturerAutocomplete,
  },
})
export default class DataFields extends mixins(
  FiasMix,
  AdditionalMix,
  ActionsMix,
  Manufactures,
  PermissionMix,
  Gosmonitoring,
  GetDocumentForSignMix
) {
  @Model('change', { type: Object, required: true }) value!: ResearchRegisterVueModel | ConductedResearchRegisterVue;

  @Prop({ default: false }) edit!: boolean;
  @Prop({ default: false }) detail!: boolean;
  @Prop({ default: false }) create!: boolean;
  @Prop({ default: false }) loading!: boolean;
  @Prop({ default: '' }) url!: string;
  tomorrow = tomorrow();
  today = new Date();
  afterUpdatePush = '';
  updateLink = 'gosmonitoring/update';
  selectedLotArray = null;
  arrayNamesForLabel = ['lots_numbers_from_subject'];
  isSignatureModalOpen = false;
  measureId: number | null = null;
  researchSignatureType = ResearchSignatureType;
  typeSignature: ResearchSignatureType = ResearchSignatureType.NONE;
  isPrintModal = false;
  isError = false;
  isAmmend = false;
  previousQualityIndicators: QualityIndicatorsVueModel[] = [];
  purposes = [IndicatorPurposeEnum.GOSMONITORING];

  get isOperatorRole() {
    // todo: Необходима роль оператора.
    return this.$store.getters['auth/roles'].includes(ERole.ROLE_ADMIN);
  }

  fromDate(date) {
    return dateFrom(date, -1);
  }

  get model(): any {
    return this.value;
  }

  set model(value: any) {
    this.$emit('change', value);
  }

  get resultTitle() {
    return this.model.lots_numbers_from_subject?.lots_numbers_from_subject;
  }

  routerUpdatePush(): void {
    if (this.accessGrantedAuthorities(this.model.update_privileges))
      this.$router.push({ name: 'gosmonitoring_research_register_edit', params: { id: String(this.model?.id) } });
  }

  routerCreateLot(): void {
    if (this.accessGrantedAuthorities(this.model.create_gosmonitoring_grain_lot_privileges)) {
      this.$router.push({
        name: 'lot_create_from_field',
        params: {
          research_numbers_government: JSON.stringify(this.model),
        },
      });
    }
  }

  async handleSignatureModalOpen(type: ResearchSignatureType, measureId: number | null): Promise<void> {
    this.measureId = measureId;
    this.typeSignature = type;
    switch (type) {
      case ResearchSignatureType.SUBSCRIBE:
        await this.getNewOrStoredDocument(measureId, this.model.export_pdf_service + '/progect');
        break;
      case ResearchSignatureType.CANCEL:
        await this.getNewOrStoredDocument(measureId, this.model.export_pdf_canceled_service);
        break;
      case ResearchSignatureType.AMMEND:
        await this.prepareDocumentFromDescription(
          this.model.export_pdf_service_ammend_from_description,
          this.model.getDataForVersionPdf()
        );
        break;
      default:
        throw new Error('Unknown type service');
    }

    this.isSignatureModalOpen = true;
  }

  async handleSignApprove() {
    this.isSignatureModalOpen = false;

    switch (this.typeSignature) {
      case ResearchSignatureType.SUBSCRIBE:
        await this.handleSubscription(this, this.model.subscribe_service);
        break;
      case ResearchSignatureType.CANCEL:
        await this.handleSubscription(this, this.model.cancel_service);
        break;
      case ResearchSignatureType.AMMEND:
        await this.createHistoryVersion();
        break;
    }
  }

  get isCreateLot() {
    if (this.user === null) return false;

    return (
      this.model.status_id === 2 &&
      this.model.operator_id !== this.user.id &&
      this.model.owner_id === this.user.subject.subject_id &&
      !!this.model.amount_kg_available
    );
  }

  get isActionAccess() {
    return this.userSubject?.subject_id === this.model.laboratory_id;
  }

  get isCancelBtn() {
    if (this.user === null) return false;

    return this.isActionAccess && this.model.status_id === 2;
  }

  get showOtherBtn() {
    if (this.user === null) return false;

    return !this.model.isSubscribed && this.isActionAccess && this.model.status_id === 1;
  }

  get getNameCancelBtn() {
    if (this.edit || this.create) return 'Отмена';

    return 'Вернуться в реестр';
  }

  get isDisabledField() {
    if (this.create) return false;
    return !this.edit;
  }

  get errors() {
    return this.model.validationData(tomorrow());
  }

  get getError() {
    return this.errors.length > 0 ? this.errors[0] : null;
  }

  get ammendValidationError() {
    const errors = this.model.getVersionCreationErrors();
    return errors.length > 0 ? errors.shift() : null;
  }

  get isAmmendButtonDisabled() {
    return this.ammendValidationError !== null;
  }

  get errorToShow() {
    return this.isAmmend ? this.ammendValidationError : this.getError;
  }

  handleOwnerIdChange() {
    if (!this.detail) {
      this.model.lots_numbers_from_subject_id = null;
      this.model.okpd2_id = null;
    }
  }

  async handleLotsNumbersRequest(filterOptions) {
    return await this.$store.dispatch('lot/numbers', {
      data: {
        page_size: 100,
        filter: {
          options: filterOptions,
        },
      },
    });
  }

  handleLotsNumbersSelect(data: LotNumbersShortModel | null) {
    if (!data) {
      this.model.okpd2_id = null;
    } else {
      this.model.okpd2_id = data.okpd2_id;
    }
  }

  async loadDataAndSetModel() {
    this.model = await this.getGosmonitoringById(this, [
      { field: 'id', operator: '=', value: parseInt(this.$route.params.id) },
    ]);

    this.model.selectLatestHistoryVersion();
  }

  async created() {
    await this.loadDataAndSetModel();
  }

  async onSelectTypeProduct(data) {
    if (this.create || this.edit) {
      if (data.value !== null) {
        const item: any = findElementInArray(data.items, data.value);
        if (item !== null) {
          this.model.okpd2.id = item.id;
          this.model.okpd2.product_name_convert = item.label;
          this.model.okpd2.code = item.code;
        }
      } else {
        this.model.okpd2 = new Okpd2VueModel();
      }
    }
  }

  callbackResponse(model) {
    this.model = new ResearchRegisterVueModel(model);
    this.$notify({ group: 'sdiz', type: 'success', title: 'Операция успешно выполнена', text: '' });
  }

  @Watch('model.okpd2', { immediate: true, deep: true })
  async handleChangeOkpd2Code(val) {
    if (!this.detail) {
      const okpd2Code = val.code;
      await this.updateQualityIndicators(okpd2Code);
    }
  }

  async initiateAmmend() {
    await this.updateQualityIndicators(this.model.okpd2.code, true);
    this.isAmmend = true;
    this.model.ammendReason = '';
    this.model.selectedHistoryVersionId = null;
    this.previousQualityIndicators = this.model.quality_indicators;
  }

  // eslint-disable-next-line max-lines-per-function
  async updateQualityIndicators(okpd2Code: string, forAmmend = false) {
    const qualityIndicatorsValues = [...this.model.quality_indicators];
    if (!forAmmend) this.model.quality_indicators = [];

    if (okpd2Code) {
      const getIndicatorValue = (indicator) => {
        const indicatorById = qualityIndicatorsValues.find(
          (indicatorValueRec) => indicator.quality_indicator_id === indicatorValueRec.quality_indicator_id
        );

        return indicatorById ? indicatorById.value : indicator.value;
      };

      try {
        this.$emit('set-is-loading', true);
        this.isError = false;
        const indicatorsByParams = await loadQualityIndicatorsByParams({
          okpd2: okpd2Code,
          purposes: this.purposes,
        });

        if (forAmmend) {
          this.model.quality_indicators = qualityIndicatorsValues.map((e) => {
            const indicator = indicatorsByParams.find((n2) => n2.quality_indicator_id === e.quality_indicator_id);

            return indicator ? { ...indicator, id: e.id, value: e.value } : e;
          });
        } else {
          this.model.quality_indicators = indicatorsByParams.map((indicator) => ({
            ...indicator,
            value: getIndicatorValue(indicator),
          }));
        }
      } catch (_e) {
        this.isError = true;
        this.$service.notify.push('error', {
          text: 'Ошибка загрузки потребительских свойств',
        });
      } finally {
        await this.$nextTick();
        this.$emit('set-is-loading', false);
      }
    }
  }

  cancelAmmend() {
    this.isAmmend = false;
    this.model.ammendReason = '';
    this.model.selectLatestHistoryVersion();
    this.model.quality_indicators = this.previousQualityIndicators;
  }

  async handleAmmend() {
    await this.handleSignatureModalOpen(ResearchSignatureType.AMMEND, null);
  }

  async createHistoryVersion() {
    try {
      this.$emit('set-is-loading', true);
      const esp_id = this.$store.state.agreementDocument.agreementDocumentSign?.data?.esp_id;

      if (!esp_id) throw new Error('Ошибка при формировании запроса');

      const data = { ...this.model.getDataForVersionCreation(), esp_id };

      const { status } = await this.$store.dispatch(this.model.create_ammend_api_endpoint, data);
      if (!status) throw new Error('Ошибка при формировании запроса');

      this.isAmmend = false;
      await this.loadDataAndSetModel();
      await this.updateQualityIndicators(this.model.okpd2.code, true);
    } catch (e) {
      this.$notify({ group: 'gosmonitoring', type: 'warning', title: 'Ошибка при формировании запроса' });
    } finally {
      this.$emit('set-is-loading', false);
    }
  }

  get qualityIndicators() {
    const { selectedHistoryVersion } = this.model;

    return selectedHistoryVersion ? selectedHistoryVersion.quality_indicators : this.model.quality_indicators;
  }

  set qualityIndicators(v) {
    this.model.quality_indicators = v;
  }

  get isShowAmmendButtons() {
    const disallowedStatuses = [1, 3];
    return !disallowedStatuses.includes(this.model.status_id) && this.isOperatorRole;
  }
}
</script>
<style lang="scss" scoped>
.table-list-numbers {
  .tableHeader,
  .tableListRow {
    display: flex;
    flex-direction: row;
    align-content: center;
    justify-content: space-between;
  }

  .tableHeader {
    .spanHeader {
      width: 33.33% !important;
      text-align: center !important;

      &:first-child {
        flex: 1 0 70px;
      }

      &:nth-child(2) {
        flex: 1 1 auto;
      }

      &:nth-child(3),
      &:last-child {
        flex: 2 1 auto;
      }
    }
  }

  .tableListRow {
    .spanList {
      line-height: 40px;
      height: 40px;
      width: 33.33% !important;
      text-align: center !important;

      &:first-child {
        flex: 1 0 70px;
      }

      &:nth-child(2) {
        flex: 2 1 auto;
      }

      &:nth-child(3),
      &:last-child {
        flex: 1 1 auto;
      }

      > div {
        max-width: 225px !important;
      }

      > span {
        display: block;
        max-width: 230px !important;
      }
    }
  }
}
</style>
