<template>
  <v-container v-if="watchForm(model)">
    <v-row>
      <v-col cols="12">
        <text-component v-if="!detail" class="title d-flex align-center" variant="span">
          <span v-show="edit">Редактирование сведений о собранном урожае №{{ model.id }}</span>
          <span v-show="!edit">Внесение сведений о собранном урожае</span>
        </text-component>
        <text-component v-else class="title d-flex align-center" variant="span">
          Сведения о собранном урожае №{{ model.id }}
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
          <v-col cols="12" lg="6" md="6" xl="4">
            <UiDateInput
              v-model="model.date_enter"
              :disabled="isDisabledField"
              :limit-to="today"
              :format="'DD.MM.YYYY'"
              label="Дата сбора урожая"
              class="normalize-labels"
            />
          </v-col>

          <v-col cols="12" lg="6" md="6" xl="4">
            <autocomplete-priority-address
              v-if="!isDisabledField"
              v-model="model.place_of_cultivation_id"
              :is-disabled="isDisabledField"
              label="Место выращивания партии зерна"
              placeholder="Выберите место выращивания"
              class="normalize-labels"
            />

            <input-component
              v-else
              label="Место выращивания партии зерна"
              :value="model.place_of_cultivation.address"
              class="normalize-labels"
              disabled
            />
          </v-col>

          <v-col cols="12" lg="6" md="6" xl="4">
            <input-component
              v-model="model.area_mask"
              :disabled="isDisabledField"
              :mask="mask"
              :maxlength="10"
              label="Площадь земельного участка или его части (поля), с которого собран урожай зерна (га)"
              placeholder="Площадь"
              @focus="onFocusMask"
              @input="model.area = unmask(model.area_mask)"
              class="normalize-labels add-lines"
            />
          </v-col>

          <v-col cols="12" lg="6" md="6" xl="4">
            <select-request-component
              v-model="model.ownership_details_id"
              :disabled="isDisabledField"
              label="Сведения о виде вещного права на земельный участок или его часть (поле), с которого собран урожай зерна"
              placeholder="Выберите право собственности"
              :custom-items="propertyRights"
              item-id="id"
              class="normalize-labels add-lines"
            />
          </v-col>

          <v-col cols="12" lg="6" md="6" xl="4">
            <select-request-component
              v-model="model.okpd2_id"
              :disabled="true"
              label="Вид сельскохозяйственной культуры зерна"
              placeholder="Выберите вид с/х культуры"
              :lot-type="{ is_grain: true }"
              store-lot-type="is_grain"
              type="nsi-okpd2-msh"
              :is-active="false"
              class="normalize-labels"
            />
          </v-col>

          <v-col cols="12" lg="6" md="6" xl="4">
            <WeightInput
              v-model="model.weight_mask"
              :disabled="isDisabledField"
              label="Масса зерна (нетто в килограммах), произведенного в день уборки урожая"
              class="normalize-labels"
              @input="(v) => (model.weight = v)"
            />
          </v-col>

          <v-col cols="12" lg="6" md="6" xl="4">
            <autocomplete-priority-address
              v-if="!isDisabledField"
              v-model="model.current_location_id"
              :is-disabled="isDisabledField"
              label="Место хранения зерна"
              placeholder="Выберите место хранения"
            />

            <input-component v-else label="Место хранения зерна" :value="model.current_location.address" disabled />
          </v-col>

          <v-col cols="12" lg="6" md="6" xl="4">
            <LotNumbersAutocomplete
              v-model="model.lots_numbers_from_subject_id"
              :subject-id-filter="value.owner_id"
              :active-filter="!detail"
              :is-disabled="isDisabledField"
              @select="handleLotsNumbersSelect"
            />
          </v-col>

          <v-col class="mt-3" cols="12">
            <v-row v-show="edit || create" class="ma-0" justify="end">
              <v-col v-show="getError !== null" class="right-align mr-3" cols="12">
                <text-component class="text-caption text-center orange--text" variant="span">
                  {{ getError }}
                </text-component>
              </v-col>

              <button-component
                :title="getNameCancelBtn"
                class="mr-7"
                size="micro"
                @click="
                  $router.push(
                    edit ? { name: model.update_apiendpoit, params: { id: model.id } } : { name: model.cancel_link }
                  )
                "
              />
              <button-component
                :disabled="errors.length > 0"
                :loading="loading"
                :title="edit ? 'Сохранить' : 'Оформить'"
                size="micro"
                variant="primary"
                @click="$emit('event')"
              />
            </v-row>
            <v-row v-show="!edit" no-gutters>
              <v-col cols="4">
                <v-row v-show="!edit && showOtherBtn" class="ma-0" justify="start">
                  <button-component
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
                  <slot name="goBackButton">
                    <button-component
                      v-show="detail && !showOtherBtn"
                      :class="{ 'mr-3': showOtherBtn }"
                      :title="getNameCancelBtn"
                      size="micro"
                      @click="
                        $router.push(
                          edit
                            ? { name: model.update_apiendpoit, params: { id: model.id } }
                            : { name: model.cancel_link }
                        )
                      "
                    />
                  </slot>

                  <modal-button
                    v-if="showOtherBtn && accessGrantedAuthorities(model.delete_privileges)"
                    button-text="Удалить"
                    modal-text="Вы действительно хотите удалить запись ?"
                    variant-text="h5"
                    @onResumeClick="$emit('onDelete')"
                  />

                  <button-component
                    v-show="showOtherBtn"
                    :class="{ 'mr-3': showOtherBtn }"
                    title="Редактировать"
                    @click="
                      $router.push({ name: 'gosmonitoring_register_implementation_edit', params: { id: model.id } })
                    "
                  />

                  <button-component
                    v-if="showOtherBtn && accessGrantedAuthorities(model.sign_privileges)"
                    size="micro"
                    title="Подписать"
                    variant="primary"
                    @click="handleSignatureModalOpen('SUBSCRIBE', model.id)"
                  />
                  <button-component
                    v-if="showCancelBtn && !showOtherBtn && accessGrantedAuthorities(model.cancel_privileges)"
                    size="micro"
                    title="Аннулировать"
                    variant="primary"
                    @click="handleSignatureModalOpen('CANCELED', model.id)"
                  />
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
import LabelComponent from '@/components/common/Label/Label.vue';
import UiDateInput from '@/components/global/UiForm/components/UiDateInput.vue';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import { Component, Model, Prop } from 'vue-property-decorator';
import { ImplementationVueModel } from '@/models/Gosmonitoring/Implementation.vue';
import ButtonComponent from '@/components/common/buttons/DefaultButton.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import ActionsButtons from '@/components/Forms/ActionsButtons.vue';
import PrintModal from '@/components/PrintModal/PrintModal.vue';
import TooltipButton from '@/components/common/buttons/TooltipButton.vue';
import { mixins } from 'vue-class-component';
import { FiasMix } from '@/utils/mixins/fias';
import { Manufactures } from '@/utils/mixins/manufactures';
import { AdditionalMix } from '@/utils/mixins/additional';
import { ActionsMix } from '@/utils/mixins/actions';
import ModalButton from '@/components/common/buttons/ModalButton.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import TextComponent from '@/components/common/Text/Text.vue';
import AutocompletePriorityAddress from '@/components/PriorityAddress/PriorityAddress.vue';
import { numberThousandsMask, numberThousandsUnmask } from '@/components/common/inputs/mask/numberThousandsMask';
import SignatureModal from '@/components/SignatureModal/SignatureModal.vue';
import { PermissionMix } from '@/utils/mixins/permission';
import { SubmitedByManufacturerModel } from '@/models/Gosmonitoring/SubmitedByManufacturers.vue';
import { Gosmonitoring } from '@/utils/mixins/gosmonitoring';
import nsiList from '@/views/NSI/config';
import LotNumbersAutocomplete from '@/components/common/LotNumbersAutocomplete/LotNumbersAutocomplete.vue';
import { LotNumbersShortModel } from '@/models/Lot/LotNumbers.vue';
import WeightInput from '@/views/Lot/components/Subcomponents/WeightInput.vue';

@Component({
  name: 'DataFields',
  components: {
    WeightInput,
    LotNumbersAutocomplete,
    AutocompletePriorityAddress,
    SignatureModal,
    DefaultButton,
    TextComponent,
    DialogComponent,
    ModalButton,
    ActionsButtons,
    AutocompleteComponent,
    ButtonComponent,
    LabelComponent,
    UiDateInput,
    InputComponent,
    SelectRequestComponent,
    PrintModal,
    TooltipButton,
  },
})
export default class DataFields extends mixins(
  FiasMix,
  AdditionalMix,
  ActionsMix,
  Manufactures,
  PermissionMix,
  Gosmonitoring
) {
  @Model('change', { type: Object, required: true }) value!: ImplementationVueModel | SubmitedByManufacturerModel;

  @Prop({ default: false }) edit!: boolean;
  @Prop({ default: false }) detail!: boolean;
  @Prop({ default: false }) create!: boolean;
  @Prop({ type: Boolean, default: false }) readonly isExternalValue!: boolean;

  @Prop({ default: false }) loading!: boolean;
  @Prop({ default: '' }) url!: string;
  isLoadNumberLots = false;
  afterUpdatePush = '';
  updateLink = 'gosmonitoring/update';
  isPrintModal = false;

  arrayNamesForLabel = ['lots_numbers_from_subject'];
  isSignatureModalOpen = false;
  measureId: number | string = 0;
  typeSignature = 'UNKNOWN';

  get model(): any {
    return this.value;
  }

  set model(value: any) {
    this.$emit('change', value);
  }

  mask: any = '';
  unmask = numberThousandsUnmask;
  async handleSignatureModalOpen(type: string, measureId: number | string): Promise<void> {
    let service = '';
    this.measureId = measureId;
    this.typeSignature = type;
    switch (type) {
      case 'SUBSCRIBE':
        service = this.model.export_pdf_service + '/progect';
        break;
      case 'CANCELED':
        service = this.model.export_pdf_canceled_service;
        break;
      default:
        throw new Error('Unknown type service');
    }

    await this.$store.dispatch('agreementDocument/getNewOrStoredDocument', {
      measureId: this.measureId,
      service: service,
    });

    this.isSignatureModalOpen = true;
  }

  async handleSignApprove() {
    this.isSignatureModalOpen = false;

    switch (this.typeSignature) {
      case 'SUBSCRIBE':
        await this.handleSubscription(this, this.model.subscribe_service);
        break;
      case 'CANCELED':
        await this.handleSubscription(this, this.model.cancel_service);
        break;
    }
  }

  get isDisabledField() {
    if (this.create) return false;
    return !this.edit;
  }

  onFocusMask() {
    this.mask = numberThousandsMask;
  }

  get getNameCancelBtn() {
    if (this.edit || this.create) return 'Отмена';
    return 'Вернуться в реестр';
  }

  get showCancelBtn() {
    if (this.subjectOfUser === undefined) return false;
    return this.model.owner_id === this.subjectOfUser.subject_id && this.model.status_id === 2;
  }

  get showOtherBtn() {
    if (this.subjectOfUser === undefined) return false;
    return this.model.owner_id === this.subjectOfUser.subject_id && this.model.status_id === 1;
  }

  get errors() {
    return this.model.validationData();
  }

  get getError() {
    return this.errors.length > 0 ? this.errors[0] : null;
  }

  propertyRights = [];

  handleLotsNumbersSelect(data: LotNumbersShortModel | null) {
    if (!data) {
      this.model.okpd2_id = null;
    } else {
      this.model.okpd2_id = data.okpd2_id;
    }
  }

  callbackResponse(model) {
    this.model = new ImplementationVueModel(model);
    this.$notify({ group: 'sdiz', type: 'success', title: 'Операция успешно выполнена', text: '' });
  }

  async created() {
    try {
      const { content } = await this.$store.dispatch('nsi/getList', {
        url: nsiList['property-right'].apiUrl,
        params: { actual: true },
      });

      this.propertyRights = content;

      if (!this.isExternalValue)
        this.model = await this.getGosmonitoringById(this, [
          { field: 'id', operator: '=', value: parseInt(this.$route.params.id) },
        ]);
    } catch (err) {
      this.$notify({ group: 'gosmonitoring', type: 'warning', title: 'Ошибка при загрузке данных', text: err } as any);
    }
  }
}
</script>

<style lang="scss" scoped>
.normalize-labels::v-deep {
  @media (min-width: 960px) {
    .label {
      display: flex;
      min-height: 32px !important;
      margin: 0 !important;
      padding: 0 !important;
    }
  }

  &.add-lines {
    @media (max-width: 1280px) {
      .label {
        min-height: 48px !important;
      }
    }
  }
}
</style>
