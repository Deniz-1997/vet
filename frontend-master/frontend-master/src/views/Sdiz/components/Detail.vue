<template>
  <div v-if="user">
    <sdiz-form
      v-model="model"
      :is-create="false"
      :is-detail="!isEdit"
      :is-edit="isEdit"
      :subject-id="subjectId"
      :is-elevator="isElevatorCreator"
      :is-owner-sdiz="isOwnerSdiz"
      :document-type-name="documentTypeName"
      :is-for-regional-government="isForRegionalGovernment"
      @handleEdit="handle"
    >
      <template #buttons>
        <v-col v-if="isEdit" class="right-align mb-3 mr-4" cols="12">
          <text-component class="text-caption text-center orange--text" variant="span">
            {{ model.getError() }}
          </text-component>
        </v-col>

        <v-col :class="[{ hideTabs: isEdit, 'mt-2': !isEdit, 'col-12': !isEdit }]">
          <v-row>
            <v-col cols="12">
              <div class="containerTabs">
                <div :class="['tabs', { disabled: isEdit }]">
                  <div :class="[{ active: tab === 'extinguish' }, 'tab']" @click="onSelectTab('extinguish')">
                    История погашений
                  </div>
                  <div :class="[{ active: tab === 'returnHistory' }, 'tab']" @click="onSelectTab('returnHistory')">
                    История отказа погашений СДИЗ
                  </div>
                </div>
              </div>
            </v-col>
          </v-row>
        </v-col>

        <v-col v-if="tab === 'extinguish'" cols="12">
          <sdiz-extinguish-tables
            v-if="!isEdit"
            v-model="model.objects.extinguishs"
            :cancel-sign-service="model.extinguish_cancel_sign_service"
            :to-lot-link="model.to_lot_link"
            class="mt-3"
            @update="findElementById(parseInt($route.params.id))"
          />
        </v-col>

        <v-col v-if="tab === 'returnHistory'" cols="12">
          <sdiz-history-return
            v-if="!isEdit"
            v-model="model.objects.extinguish_refusals"
            :to-lot-link="model.to_lot_link"
            :cancel-sign-service="model.extinguish_refusal_cancel_sign_service"
            :is-actions-access="isOwnerSdiz"
            class="mt-3"
            @update="findElementById(parseInt($route.params.id))"
          />
        </v-col>

        <v-col cols="6">
          <v-row class="ma-0" justify="start">
            <slot name="goBackButton">
              <button-component
                v-show="!isEdit"
                size="pico"
                title="Вернуться в реестр"
                @click="$router.push({ name: model.name_route_list })"
              />
            </slot>
          </v-row>
        </v-col>
        <v-col v-show="isShow" :key="model.status_id" cols="6">
          <v-row class="ma-0" justify="end">
            <template v-if="!isEdit">
              <button-component v-if="isOwnerSdiz" title="Копировать СДИЗ" @click="handleCopySdiz" variant="primary" />

              <template v-if="model.status_id === 1 && isOwnerSdiz">
                <modal-button
                  v-if="deletePrivileges"
                  :loading-btn="preloader || isLoading"
                  button-text="Удалить"
                  modal-text="Вы действительно хотите удалить СДИЗ ?"
                  btn-class="red lighten-2 white--text"
                  primary
                  variant-text="h5"
                  @onResumeClick="deleteSdiz(model.id)"
                />

                <button-component v-if="updatePrivileges" title="Редактировать" @click="handle('edit')" />
                <button-component
                  v-if="signPrivileges"
                  size="micro"
                  :loading="preloader || isLoading"
                  title="Подписать"
                  variant="primary"
                  @click="handleSignatureModalOpen('SUBSCRIBE', model.id)"
                />
              </template>

              <button-component
                v-if="showCancelSdizButton"
                :loading="preloader || isLoading"
                size="micro"
                title="Аннулировать"
                variant="primary"
                @click="handleSignatureModalOpen('CANCELED', model.id)"
              />

              <template v-if="model.status_id === 2">
                <button-component
                  v-if="isOwnerSdiz"
                  v-show="false"
                  :loading="preloader || isLoading"
                  size="micro"
                  title="Увеличить"
                  variant="primary"
                  @click="isGrowthFunc = true"
                />
              </template>

              <template v-if="showExtinguishButton">
                <button-component
                  :loading="preloader || isLoading"
                  size="micro"
                  title="Погасить"
                  variant="primary"
                  @click="isExtinguishSdiz = true"
                />
              </template>

              <template v-if="showExtinguishRefusalButton">
                <button-component
                  :loading="preloader || isLoading"
                  size="micro"
                  title="Отказ погашения"
                  variant="primary"
                  @click="isReturnModalOpened = true"
                />
              </template>

              <template v-if="model.status_id === 2 && isRshnRole && confirmPriveleges">
                <button-component
                  :loading="preloader || isLoading"
                  size="micro"
                  title="Подтвердить"
                  variant="primary"
                  @click="isConfirmDialog = true"
                />
              </template>

              <template v-if="model.status_id === 2 && isRshnRole && confirmPriveleges">
                <button-component
                  :loading="preloader || isLoading"
                  size="micro"
                  title="Отклонить"
                  variant="primary"
                  @click="isCancelDialog = true"
                />
              </template>
            </template>

            <template v-else>
              <button-component size="micro" title="Отмена" @click="handle('edit')" />
              <button-component
                :loading="preloader || isLoading"
                size="micro"
                title="Сохранить"
                variant="primary"
                :disabled="model.getError() !== ''"
                @click="handle('update')"
              />
            </template>
          </v-row>
        </v-col>
      </template>
      <template #[`manufacture-field`]="{ onSearchUpdateMix, items, model, disabled }">
        <slot
          :items="items"
          :model="model"
          :disabled="disabled"
          :onSearchUpdateMix="onSearchUpdateMix"
          name="manufacture-field"
        />
      </template>

      <template #[`date-create`]="{ model, disabled }">
        <slot :model="model" :disabled="disabled" name="date-create" />
      </template>

      <template #[`product-type-field`]="{ model, disabled }">
        <slot :model="model" :disabled="disabled" name="product-type-field" />
      </template>

      <template #gosmonitoring-number-field>
        <slot :model="model" name="gosmonitoring-number-field" />
      </template>
    </sdiz-form>

    <v-overlay :value="isLoading" z-index="10">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>

    <dialog-component
      v-model="isExtinguishSdiz"
      :is-loading="isLoading"
      :no-click-animation="true"
      :persistent="true"
      :prompt="false"
      :with-close-icon="true"
      cancel-title=""
      confirm-title=""
      controls-justify="justify-end"
      width="500"
    >
      <template #title>Погашение СДИЗ</template>
      <template #content>
        <extinguish-form
          :key="isExtinguishSdiz"
          v-model="model"
          :operator_id="user.id"
          :owner_id="subjectOfUser.subject_id"
          :is_gpb="checkTypeSdiz()"
          @extinguish="initiateExtinguish"
          @onClose="onCloseExtinguish"
        />
      </template>
    </dialog-component>

    <dialog-component
      v-model="isReturnModalOpened"
      :is-loading="isLoading"
      :no-click-animation="true"
      :persistent="true"
      :prompt="false"
      :with-close-icon="true"
      cancel-title=""
      confirm-title=""
      controls-justify="justify-end"
      width="500"
    >
      <template #title>Отказ погашения</template>
      <template #content>
        <returned-form :key="isReturnModalOpened" v-model="model" @return="initiateReturn" @onClose="onCloseReturn" />
      </template>
    </dialog-component>

    <dialog-component
      v-model="isGrowthFunc"
      :is-loading="isLoading"
      :no-click-animation="true"
      :persistent="true"
      :prompt="false"
      :with-close-icon="true"
      cancel-title=""
      confirm-title=""
      controls-justify="justify-end"
      width="500"
    >
      <template #title>Увеличение остатка по партии</template>
      <template #content>
        <growth-form v-model="model" @onClose="isGrowthFunc = false" @onSend="handleGrowth" />
      </template>
    </dialog-component>

    <dialog-component
      v-model="isConfirmDialog"
      :is-loading="isLoading"
      :no-click-animation="true"
      :persistent="true"
      :prompt="false"
      :with-close-icon="true"
      controls-justify="justify-end"
      width="500"
      @onSuccess="handleConfirm"
    >
      <template #title>Подтверждение СДИЗ</template>

      <template #content>
        <text-component variant="h5">Вы действительно хотите подтвердить СДИЗ?</text-component>
      </template>
    </dialog-component>

    <dialog-component
      v-model="isCancelDialog"
      :is-loading="isLoading"
      :no-click-animation="true"
      :persistent="true"
      :prompt="false"
      :with-close-icon="true"
      controls-justify="justify-end"
      width="500"
      @onSuccess="handleSignatureModalOpen('CANCELED', model.id)"
    >
      <template #title>Отклонение СДИЗ</template>

      <template #content>
        <text-component variant="h5">Вы действительно хотите отклонить СДИЗ?</text-component>
      </template>
    </dialog-component>

    <SignatureModal
      v-model="isSignatureModalOpen"
      :measure-id="measureId"
      @approve="handleSignApprove"
      @close="handleSignatureModalClose"
    />
  </div>
</template>

<script lang="ts">
import { mixins } from 'vue-class-component';
import { Component, Model, Prop } from 'vue-property-decorator';
import Sdiz from '@/views/Sdiz/Sdiz.vue';
import ButtonComponent from '@/components/common/buttons/DefaultButton.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import ModalButton from '@/components/common/buttons/ModalButton.vue';
import ActionsButtons from '@/components/Forms/ActionsButtons.vue';
import TextComponent from '@/components/common/Text/Text.vue';
import SdizForm from '@/views/Sdiz/components/Form.vue';
import SdizExtinguishTables from '@/views/Sdiz/components/Subcomponents/Table/SdizExtinguishTables.vue';
import SdizHistoryReturn from '@/views/Sdiz/components/Subcomponents/Table/SdizHistoryReturn.vue';
import LabelComponent from '@/components/common/Label/Label.vue';
import CheckboxComponent from '@/components/common/inputs/CheckboxComponent.vue';
import TextAreaComponent from '@/components/common/inputs/TextAreaComponent.vue';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import { getElementById } from '@/utils/methodsForViews';
import GrowthForm from '@/views/Sdiz/components/Subcomponents/ModalForm/GrowthForm.vue';
import ExtinguishForm from '@/views/Sdiz/components/Subcomponents/ModalForm/ExtinguishForm.vue';
import ReturnedForm from '@/views/Sdiz/components/Subcomponents/ModalForm/ReturnedForm.vue';
import { SdizGpbVueModel } from '@/models/Sdiz/Data/SdizGpb.vue';
import { SdizVueModel } from '@/models/Sdiz/Data/Sdiz.vue';
import SignatureModal from '@/components/SignatureModal/SignatureModal.vue';
import { SdizElevatorModel } from '@/models/Sdiz/Data/SdizElevator.vue';
import { GetDocumentForSignMix } from '@/utils/mixins/getDocumentForSign';
import { ERole } from '@/models/roles';

@Component({
  name: 'sdiz-detail',
  components: {
    ReturnedForm,
    ExtinguishForm,
    GrowthForm,
    InputComponent,
    SelectRequestComponent,
    TextAreaComponent,
    CheckboxComponent,
    LabelComponent,
    SdizExtinguishTables,
    SdizForm,
    TextComponent,
    ActionsButtons,
    ModalButton,
    DialogComponent,
    ButtonComponent,
    SignatureModal,
    SdizHistoryReturn,
  },
})
export default class SdizDetail extends mixins(Sdiz, GetDocumentForSignMix) {
  @Model('change', { type: Object, required: true }) value!: SdizGpbVueModel | SdizVueModel | SdizElevatorModel;

  @Prop({ type: Boolean, default: true }) readonly isShow!: boolean;
  @Prop({ type: Boolean, default: false }) readonly isElevatorMenu!: boolean;
  @Prop({ type: Boolean, default: false }) readonly isExternalValue!: boolean;
  @Prop({ type: Boolean, default: false }) readonly isForRegionalGovernment!: boolean;
  @Prop({ type: String, default: 'СДИЗ' }) documentTypeName!: string;

  isEdit = false;
  measureId: any = null;
  typeSignature = 'UNKNOWN';
  showItem = this.value.show_apiendpoit;
  isSignatureModalOpen = false;
  sdizToExtinguish: any = null;
  returnData: any = null;
  tabs: string[] = ['extinguish', 'returnHistory'];
  tab: string = this.tabs[0];
  isConfirmDialog = false;
  isCancelDialog = false;

  get isSdizToExtinguish(): boolean {
    return Boolean(this.sdizToExtinguish);
  }

  get model(): SdizVueModel | SdizGpbVueModel | SdizElevatorModel {
    return this.value;
  }
  set model(value) {
    this.$emit('change', value);
  }

  get isElevator() {
    return this.user.roles.find((x) => x.authority === 'ROLE_ELEVATOR_USER') !== undefined;
  }

  get userInfo() {
    return this.$store.getters['auth/getUserInfo'];
  }

  get updatePrivileges() {
    return this.accessGrantedAuthorities(this.model.update_privileges);
  }
  get deletePrivileges() {
    return this.accessGrantedAuthorities(this.model.delete_privileges);
  }
  get signPrivileges() {
    return this.accessGrantedAuthorities(this.model.sign_privileges);
  }
  get cancelPrivileges() {
    return this.accessGrantedAuthorities(this.model.cancel_privileges);
  }
  get repaymentPrivileges() {
    return this.accessGrantedAuthorities(this.model.repayment_privileges);
  }

  get confirmPriveleges() {
    return this.accessGrantedAuthorities(this.model.confirm_priveleges);
  }

  get isRshnRole() {
    return this.$store.getters['auth/roles'].includes(ERole.ROLE_RSHN);
  }

  deleteSdiz(id) {
    if (this.accessGrantedAuthorities(this.model.delete_privileges)) {
      this.deleteActionMix(id);
    }
  }

  get isOnlyShipmentClientSdiz() {
    const { acceptance, shipping, realization, shipment } = this.model.objects.operations.detail;
    const { shipper_id } = this.model;

    return shipment && !acceptance && !shipping && !realization && shipper_id === this.subjectId;
  }

  get isClientSdiz() {
    const { acceptance, shipping, realization } = this.model.objects.operations.detail;
    const { prototype_sdiz, elevator_creator, consignee_repository_id, consignee_id, buyer_id } = this.model;

    // Если на территории РФ
    if (prototype_sdiz === 1) {
      // ЕСЛИ СДИЗ оформил элеватор (elevator_creator) показываем кнопку у оформителя СДИЗ
      if (elevator_creator) return this.isOwnerSdiz;
      // ИНАЧЕ Если (перевозка или приемка)
      else if (acceptance || shipping) {
        // И указан элеватор получателя
        if (consignee_repository_id !== null) {
          // то показывается у организации элеватора, иначе у грузополучателя, + если реализация то у покупателя
          return consignee_repository_id === this.subjectId;
        } else return consignee_id === this.subjectId || (realization && buyer_id === this.subjectId);
      }
      // иначе Если есть реализация то у покупателя
      if (realization) return buyer_id === this.subjectId;

      return this.isOnlyShipmentClientSdiz;
    }
    // Если Ввоз
    if (prototype_sdiz === 2) {
      // ЕСЛИ указан элеватор получателя
      if (consignee_repository_id !== null) {
        // Показывается у Элеватора, иначе Показывается у грузополучателя
        return consignee_repository_id === this.subjectId;
      } else return consignee_id === this.subjectId;
    }
    // Если вывоз Показывается у Оформителя СДИЗ
    if (prototype_sdiz === 3) return this.isOwnerSdiz || this.isOnlyShipmentClientSdiz;

    return false;
  }

  get isOwnerElevator(): boolean {
    return this.isElevatorMenu && this.model.owner_id === this.model.consignee_id;
  }

  get isOwnerSdiz(): boolean {
    return this.model.owner_id === this.subjectId;
  }

  get isElevatorCreator() {
    return this.model.elevator_creator;
  }

  get prototypeSdiz() {
    return this.model.prototype_sdiz;
  }

  get isImportOrExport() {
    return this.prototypeSdiz === 2 || this.prototypeSdiz === 3;
  }

  get showExtinguishButton() {
    // На территории РФ ? оформлен : подтвержден.
    const statusNeededForOptions = this.prototypeSdiz === 1 ? 2 : 5;
    return this.model.status_id === statusNeededForOptions && this.isClientSdiz && this.repaymentPrivileges;
  }

  get showExtinguishRefusalButton() {
    const { prototype_sdiz } = this.model;
    if (prototype_sdiz === 2) return false;
    let value = false;
    switch (prototype_sdiz) {
      case 2:
        //  "на ввоз" кнопки отказ погашения не должно быть
        value = false;
        break;
      case 3:
        //  "на вывоз" показываться только для статуса "Оформлен и подтвержден"
        value = (this.model.status_id === 5 || this.model.status_id === 3) && this.isOwnerSdiz;
        break;
      case 1:
        value = (this.model.status_id === 2 || this.model.status_id === 3) && this.isOwnerSdiz;
    }

    return value;
  }

  /**
   * FIXME функция роста
   *
   * @param event
   */
  async handleGrowth(event): Promise<void> {
    if (this.isGrowthFunc && event.amount_kg > 0) {
      this.isGrowthFunc = !this.isGrowthFunc;
      this.isLoading = true;
      try {
        // TODO убрать после реализации на бэке
        // const {response, status} = await this.$store.dispatch('', event);
        // if (status) {
        // TODO убрать после реализации на бэке
        //   if (!(this.model.authorized_person === this.user.subject.name && this.model.objects.operations.prototype_sdiz === 3)) {
        //     await this.$store.dispatch("lot/update", {data: {status: "SUBSCRIBED"}, id: response.newLot.id,});
        //   }
        //   this.$notify({group: "sdiz", type: "success", title: "Операция увеличение остатка успешно выполнена", text: "",});
        //
        this.isLoading = false;
        // } else {
        //   this.$notify({group: "sdiz", type: "error", title: "Ошибка при увеличении", text: "",});
        // }
      } catch (e) {
        this.isLoading = false;
      }
    } else {
      this.isGrowthFunc = !this.isGrowthFunc;
    }
  }

  async handle(action, onCloseModal?): Promise<void> {
    try {
      this.isLoading = true;
      switch (action) {
        case 'update': {
          const { status, response } = await this.$store.dispatch(this.model.update_apiendpoit, {
            data: this.model.getDataForUpdate(),
            id: this.model.id,
          });
          await this.updateCase(status, response);
          break;
        }
        case 'edit': {
          this.isEdit = !this.isEdit;
          break;
        }
        case 'subscribed': {
          await this.handleSubscribeCase();
          break;
        }
        case 'extinguish': {
          await this.extinguishCase();
          break;
        }
        case 'return': {
          await this.returnCase();
          break;
        }
        case 'canceled': {
          await this.handleCancelCase();
          break;
        }
        default: {
          await this.defaultCase(action);
          break;
        }
      }

      if (action !== 'edit' || !this.isEdit) {
        const model = await this.findElementById(this.model.id);
        this.$emit('change', model);
        this.closeModel(onCloseModal);
      }
      this.isLoading = false;
    } catch (e) {
      this.isLoading = false;
      this.closeModel(onCloseModal);
      if (this.errors !== undefined) return this.errors;
    }
  }
  async updateCase(status, response) {
    if (status) {
      this.$emit('change', this.returnNewModel(response, this.model.component_name));
      await this.fetchTypes();
      this.$notify({ group: 'lot', type: 'success', title: 'Операция успешно выполнена', text: '' });
      this.isEdit = false;
    } else {
      this.$notify({ group: 'lot', type: 'error', title: 'Ошибка при обновлении партии зерна', text: '' });
    }
  }

  /**
   * Отправить запрос на создание сущности с присвоенным sign_id
   * @param entity Объект сущности
   * @param endpoint Используемый сервис
   */
  async createEntityWithSignature(endpoint: string, entity: any): Promise<void> {
    const esp_id = this.$store.state.agreementDocument.agreementDocumentSign?.data?.esp_id;

    if (!esp_id) throw new Error('Ошибка при формировании запроса');

    const data = { ...entity, esp_id };

    const { status } = await this.$store.dispatch(endpoint, data);
    if (!status) throw new Error('Ошибка при формировании запроса');
  }

  async createExtinguishLot(): Promise<void> {
    return await this.createEntityWithSignature(this.model.extinguish_api_endpoint, this.sdizToExtinguish);
  }

  async createExtinguishRefusalLot(): Promise<void> {
    return await this.createEntityWithSignature(this.model.extinguish_refusal_api_endpoint, this.returnData);
  }

  async extinguishCase() {
    try {
      this.isLoading = true;
      await this.createExtinguishLot();

      this.model = await this.findElementById(parseInt(this.$route.params.id));

      this.$notify({ group: 'sdiz', type: 'success', title: 'Операция погашения успешно выполнена', text: '' });
    } catch (e) {
      this.$notify({ group: 'sdiz', type: 'error', title: 'Ошибка при погашении', text: '' });
    } finally {
      this.sdizToExtinguish = null;
      this.typeSignature = 'UNKNOWN';
      this.isLoading = false;
    }
  }

  async returnCase() {
    try {
      this.isLoading = true;
      await this.createExtinguishRefusalLot();

      this.model = await this.findElementById(parseInt(this.$route.params.id));

      this.$notify({
        group: 'sdiz',
        type: 'success',
        title: 'Операция отказа погашения успешно выполнена',
        text: '',
      });
    } catch (err) {
      this.$notify({ group: 'sdiz', type: 'error', title: 'Ошибка при отказе погашения', text: err || '' } as any);
    } finally {
      this.returnData = null;
      this.typeSignature = 'UNKNOWN';
      this.isLoading = false;
    }
  }

  async handleSubscriptionAction(service: string) {
    try {
      this.isLoading = true;

      await this.$store.dispatch('agreementDocument/signDocumentFromDescription', {
        id: this.measureId,
        service,
      });

      const error = this.$store.state.agreementDocument.agreementDocumentSign.error;

      if (error) {
        throw new Error(error);
      }

      this.$notify({ group: 'sdiz', type: 'success', title: 'Операция успешно выполнена' });
    } catch (_err) {
      this.$notify({ group: 'sdiz', type: 'error', title: 'Ошибка при выполнении операции' });
    } finally {
      this.model = await this.findElementById(parseInt(this.$route.params.id));
      this.isLoading = false;
    }
  }

  async handleSubscribeCase() {
    await this.handleSubscriptionAction(this.model.subscribe_service);
  }

  async handleCancelCase() {
    await this.handleSubscriptionAction(this.model.cancel_service);
  }

  async defaultCase(action) {
    this.updateLink = this.model.update_apiendpoit;
    this.afterUpdatePush = this.model.name_route_detail;
    let data = { status_id: this.model.objects.sdiz_status.getStatus(action) };
    await this.updateActionMix(this.model.id, data, (response) => {
      const data = this.returnNewModel(response, this.model.component_name);
      this.$emit('change', data);
      this.fetchTypes();
      this.$notify({ group: 'sdiz', type: 'success', title: 'Операция успешно выполнена', text: '' });
    });
  }

  closeModel(onCloseModal) {
    if (onCloseModal !== undefined) {
      onCloseModal();
    }
  }

  async created(): Promise<void> {
    await this.fetchTypes();

    this.deleteLink = this.model.delete_apiendpoit;
    this.afterDeletePush = this.model.name_route_list;
    if (!this.isExternalValue) this.model = await this.findElementById(parseInt(this.$route.params.id));

    this.isLoading = !this.isLoading;
  }

  async findElementById(id) {
    const sdizResponse = await getElementById(this, id);
    const lot_id = this.model.lot_type === 'lot' ? sdizResponse.lot_id : sdizResponse.gpb_id;
    if (lot_id) {
      const lotResponse = await this.$store.dispatch(this.model.show_lot_apiendpoit, id);

      if (lotResponse.status) {
        sdizResponse[this.model.lot_type].repository = lotResponse.response.repository;
        sdizResponse[this.model.lot_type].purpose = lotResponse.response.purpose;
        sdizResponse[this.model.lot_type].current_location = lotResponse.response.current_location;
        sdizResponse[this.model.lot_type].docs = lotResponse.response.docs;
        sdizResponse[this.model.lot_type].debits = lotResponse.response.debits;
        sdizResponse[this.model.lot_type].quality_indicators = lotResponse.response.quality_indicators;
        sdizResponse[this.model.lot_type].country_destination = lotResponse.response.country_destination;
        if (lotResponse.response.origin_location)
          sdizResponse[this.model.lot_type].origin_location = lotResponse.response.origin_location;
      }
    }
    return this.callbackLoadList(this.model, [], [sdizResponse])[0];
  }

  checkTypeSdiz(): boolean {
    return this.model.getNameNumber() === 'gpb_number';
  }

  async handleSignatureModalOpen(type: string, measureId): Promise<void> {
    if ((this.cancelPrivileges && this.signPrivileges && this.repaymentPrivileges) || type === 'CANCELED') {
      const isGpb = this.checkTypeSdiz();
      let service = '';
      this.measureId = measureId;
      this.typeSignature = type;
      switch (type) {
        case 'SUBSCRIBE':
          service = isGpb ? `sdiz/gpb/export/progect` : `sdiz/export/progect`;
          await this.getNewOrStoredDocument(measureId, service);
          break;
        case 'CANCELED':
          service = isGpb ? `sdiz/gpb/export/canceled` : `sdiz/export/canceled`;
          await this.getNewOrStoredDocument(measureId, service);
          break;
        case 'EXTINGUISH':
          service = isGpb
            ? 'sdiz/gpb/export/extinguish/pdf/from/description'
            : 'sdiz/export/extinguish/pdf/from/description';
          await this.prepareDocumentFromDescription(service, {
            ...this.sdizToExtinguish,
            amount_kg_lock: this.sdizToExtinguish.amount_kg_unlock,
          });
          break;
        case 'RETURN':
          service = isGpb
            ? 'sdiz/gpb/export/extinguish/refusal/pdf/from/description'
            : 'sdiz/export/extinguish/refusal/pdf/from/description';
          await this.prepareDocumentFromDescription(service, this.returnData);
          break;

        default:
          throw new Error('Unknown type service');
      }
      this.isSignatureModalOpen = true;
    }
  }

  handleSignatureModalClose() {
    switch (this.typeSignature) {
      case 'EXTINGUISH':
        this.sdizToExtinguish = null;
        this.typeSignature = 'UNKNOWN';
        break;
      case 'RETURN':
        this.returnData = null;
        this.typeSignature = 'UNKNOWN';
    }
  }

  async handleSignApprove() {
    this.isSignatureModalOpen = false;

    switch (this.typeSignature) {
      case 'SUBSCRIBE':
        await this.handle('subscribed');
        break;
      case 'CANCELED':
        await this.handle('canceled');
        break;
      case 'EXTINGUISH':
        await this.handle('extinguish');
        break;
      case 'RETURN':
        await this.handle('return');
        break;
    }
  }

  updateModel(data) {
    this.model = this.returnNewModel(data, this.model.component_name);
  }

  async onCloseExtinguish() {
    this.isExtinguishSdiz = false;
    this.model = await this.findElementById(parseInt(this.$route.params.id));
  }

  async initiateExtinguish(data) {
    this.sdizToExtinguish = data;
    this.isExtinguishSdiz = false;
    await this.handleSignatureModalOpen('EXTINGUISH', null);
  }

  async initiateReturn(data) {
    this.returnData = data;
    this.isReturnModalOpened = false;
    await this.handleSignatureModalOpen('RETURN', null);
  }

  onCloseReturn() {
    this.isReturnModalOpened = false;
  }

  onSelectTab(tab: string) {
    this.tab = tab;
  }

  get showCancelSdizButton(): boolean {
    const { extinguish_refusals, extinguishs } = this.value.objects;

    const isNotCancelled = (arr) => {
      return arr.find((e) => !e.is_canceled);
    };

    const isSignedOrConfirmed = this.model.status_id === 2 || this.model.status_id === 5;

    return (
      this.isOwnerSdiz &&
      isSignedOrConfirmed &&
      this.cancelPrivileges &&
      !isNotCancelled(extinguish_refusals) &&
      !isNotCancelled(extinguishs)
    );
  }

  async handleConfirm() {
    try {
      await this.$store.dispatch(this.model.confirm_endpoint, this.model.id);
      this.$notify({ group: 'sdiz', type: 'success', title: 'Операция успешно выполнена' });
    } catch (_e) {
      this.$notify({ group: 'sdiz', type: 'warning', title: 'Ошибка при выполнении операции' });
    } finally {
      this.model = await this.findElementById(parseInt(this.$route.params.id));
    }
  }

  handleCopySdiz() {
    this.$router.push({ name: this.model.name_route_create, params: { sdizToCopyId: this.model.id as any } });
  }
}
</script>

<style lang="scss" scoped>
@import './src/assets/styles/_variables';
@import './src/assets/styles/_mixins';
@import './src/assets/styles/_container';

img.icon {
  max-width: 100%;
  width: 25px;
}

.empty-table-block {
  background-color: $silver-color;
}

.table-bd td {
  border: 1px solid $black-color;
  padding: 0 0 0 5px;
  border-radius: 0;
}

td {
  padding: 5px;
}

table {
  page-break-inside: auto;
}

tr {
  page-break-inside: avoid;
  page-break-after: auto;
}

thead {
  display: table-header-group;
}

.square-b {
  width: 40px;
  height: 40px;
  border: 1px solid;
  float: left;
  margin-right: 10px;
}

.gold {
  background-color: $gold-light-color !important;
}

img.icon {
  max-width: 100%;
  width: 28px;
}

.white-icn {
  filter: invert(100%) sepia(6%) saturate(0%) hue-rotate(115deg) brightness(108%) contrast(108%);
}

.checkbox-v {
  padding: 4px 20px !important;
  margin-top: 20px;
}

.hideTabs {
  opacity: 0;
  height: 53px;
}

.hideTabs div {
  display: none;
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
</style>
