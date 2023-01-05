<template>
  <v-container>
    <v-row>
      <v-col cols="12">
        <text-component v-if="!detail" class="title d-flex align-center" variant="span">
          <span>Формирование производства продукции, не подлежащей учету</span>
        </text-component>
        <text-component v-else class="title d-flex align-center" variant="span">
          <span>{{ detailTitle }}</span>
          <v-chip v-show="!create" :color="edit ? 'warning' : ''" class="ml-3" label>
            {{ subTitle }}
          </v-chip>
        </text-component>
      </v-col>
      <v-col cols="12" lg="4" md="6" sm="7" xl="3">
        <UiDateInput v-model="model.enter_date" :format="'DD.MM.YYYY'" disabled label="Дата формирования" />
      </v-col>

      <v-col cols="12" lg="4" md="4" sm="5" xl="4">
        <autocomplete-priority-address
          v-model="model.current_location_id"
          :is-disabled="disabledForm"
          label="Местоположение"
          placeholder="Выберите местоположение"
        />
      </v-col>
      <v-col cols="12" lg="4" md="6" sm="3" xl="4">
        <InputComponent
          :value="model.manufacturer.name"
          :tooltip="model.manufacturer.name"
          label="Организация"
          placeholder="-"
          disabled
        />
      </v-col>
    </v-row>
    <v-row>
      <v-col v-for="(value, index) in model.options" :key="index" cols="12" lg="12" xl="6">
        <lot-tables-lots-moved
          v-model="model[value.model]"
          :is-create="create"
          :is-detail="detail"
          :is-edit="edit"
          :lot="value.lot"
          :title-table="value.tableName"
          :is-not-repository-filter="value.isRepositoryFilter"
          type-lot="in-product"
          @onChangeAmountKg="$emit('onChangeAmountKg', $event)"
          @onDeleteLotMoved="$emit('onDeleteLotMoved', $event)"
          @onEditLotsMoved="$emit('onEditLotsMoved', $event)"
          @onFirstLotGrainIsSelect="$emit('onFirstLotGrainIsSelect', $event)"
          @onOpenFindLotModal="$emit('onOpenFindLotModal', $event)"
        />
      </v-col>
    </v-row>
    <v-col class="mt-3" cols="12">
      <v-row v-show="edit || create" class="ma-0" justify="end">
        <v-col v-show="error !== null" class="right-align mr-3" cols="12">
          <text-component class="text-caption text-center orange--text" variant="span">
            {{ error }}
          </text-component>
        </v-col>
        <template v-if="create">
          <button-component
            title="Вернуться в реестр"
            class="mr-7"
            size="micro"
            @click="$router.push({ name: model.cancel_link })"
          />
          <button-component
            :disabled="error !== null"
            :loading="loading"
            title="Оформить"
            size="micro"
            variant="primary"
            @click="handleCreate"
          />
        </template>
      </v-row>
      <v-col cols="12">
        <v-row class="ma-0" justify="end">
          <v-col v-show="detail" class="mt-5" cols="12">
            <v-row class="ma-0" justify="end">
              <button-component
                v-show="!edit"
                class="mr-7"
                size="micro"
                title="Вернуться в реестр"
                @click="$router.push({ name: model.cancel_link })"
              />
              <template v-if="isShow">
                <button-component
                  v-show="model.status === status.CREATE && edit"
                  title="Отмена"
                  size="micro"
                  @click="cancel"
                />

                <modal-button
                  v-if="model.status === status.CREATE && !edit"
                  button-text="Удалить"
                  modal-text="Вы действительно хотите удалить сведения производства продукции, не подлежащей учету?"
                  btn-class="red lighten-2 white--text"
                  primary
                  variant-text="h5"
                  @onResumeClick="handleDelete"
                />

                <button-component
                  v-show="model.status === status.CREATE"
                  :disabled="edit && error !== null"
                  :title="edit ? 'Сохранить' : 'Редактировать'"
                  size="micro"
                  variant="primary"
                  @click="handleEdit"
                />

                <button-component
                  v-show="model.status === status.CREATE && !edit"
                  size="micro"
                  title="Подписать"
                  variant="primary"
                  @click="handleSignatureModalOpen(status.SUBSCRIBED, model.id)"
                />

                <button-component
                  v-show="model.status === status.SUBSCRIBED"
                  size="micro"
                  title="Аннулировать"
                  variant="primary"
                  @click="handleSignatureModalOpen(status.CANCELED, model.id)"
                />
              </template>
            </v-row>
          </v-col>
          <v-overlay :value="loading">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
          </v-overlay>
        </v-row>
      </v-col>
    </v-col>
    <SignatureModal v-model="isSignatureModalOpen" :measure-id="measureId" @approve="handleSignApprove" />
  </v-container>
</template>
<script lang="ts">
import { Component, Model, Prop } from 'vue-property-decorator';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import UiDateInput from '@/components/global/UiForm/components/UiDateInput.vue';
import AutocompletePriorityAddress from '@/components/PriorityAddress/PriorityAddress.vue';
import SignatureModal from '@/components/SignatureModal/SignatureModal.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import ButtonComponent from '@/components/common/buttons/DefaultButton.vue';
import TextComponent from '@/components/common/Text/Text.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import ModalButton from '@/components/common/buttons/ModalButton.vue';
import ActionsButtons from '@/components/Forms/ActionsButtons.vue';
import LabelComponent from '@/components/common/Label/Label.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import LotTablesLotsMoved from '@/views/Lot/components/Subcomponents/Tables/LotTablesLotsMoved.vue';
import { mixins } from 'vue-class-component';
import { ActionsMix } from '@/utils/mixins/actions';
import { currentDay } from '@/utils/date';
import { GpbOutDataVueModel } from '@/models/Lot/GpbOut/GpbOutData.vue';
import { GpbOutDataOgvVueModel } from '@/models/Lot/Ogv/GpbOutDataOgv.vue';
import { StatusEnum } from '@/models/Lot/GpbOut/Data.vue';

@Component({
  name: 'gpb-out-form',
  components: {
    LotTablesLotsMoved,
    AutocompletePriorityAddress,
    SignatureModal,
    DefaultButton,
    TextComponent,
    DialogComponent,
    ModalButton,
    ActionsButtons,
    ButtonComponent,
    LabelComponent,
    UiDateInput,
    InputComponent,
    SelectRequestComponent,
  },
})
export default class DataFieldsGpbOut extends mixins(ActionsMix) {
  @Model('change', { type: Object, required: true }) model!: GpbOutDataVueModel | GpbOutDataOgvVueModel;
  @Prop({ type: Boolean, default: false }) detail!: boolean;
  @Prop({ type: Boolean, default: false }) create!: boolean;
  @Prop({ type: String, default: '' }) updateLink!: string;
  @Prop({ type: String, default: '' }) afterUpdatePush!: string;
  @Prop({ type: Boolean, default: true }) readonly isShow!: boolean;
  today = new Date();
  loading = false;
  edit = false;
  status = StatusEnum;
  isSignatureModalOpen = false;
  measureId = 0;
  typeSignature: StatusEnum | null = null;
  showItem = '';

  get disabledForm(): boolean {
    if (this.edit) return false;
    else if (this.detail) return true;
    return false;
  }

  get subTitle() {
    return this.edit ? 'Редактирование' : this.model.status_translate;
  }

  get detailTitle() {
    return (
      (this.edit
        ? 'Редактирование сведений производства продукции, не подлежащей учету'
        : 'Просмотр сведений производства продукции, не подлежащей учету') + this.model.getGpboNumber()
    );
  }

  get error() {
    return this.model.getErrors().length ? this.model.getErrors()[0] : null;
  }

  get subjectId() {
    return this.$store.state.auth.user['subject']?.subject_id;
  }

  get userSubject() {
    return this.$store.state.auth.user['subject'];
  }

  get value(): GpbOutDataVueModel | GpbOutDataOgvVueModel {
    return this.model;
  }

  set value(value) {
    this.$emit('change', value);
  }

  async created() {
    this.showItem = this.model.show_apiendpoit;

    if (this.create) {
      this.model.manufacturer_id = this.subjectId;
      this.model.enter_date = currentDay();
      this.model.manufacturer = this.userSubject;
    }
  }

  async handleSignatureModalOpen(type: StatusEnum, measureId: number): Promise<void> {
    let service = '';
    this.measureId = measureId;
    this.typeSignature = type;
    switch (type) {
      case this.status.SUBSCRIBED:
        service = 'gpbo/export/progect';
        break;
      case this.status.CANCELED:
        service = 'gpbo/export/canceled';
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

  async handleSignApprove(): Promise<void> {
    this.isSignatureModalOpen = false;
    switch (this.typeSignature) {
      case this.status.SUBSCRIBED:
        await this.handleSubscriptionAction(this.model.subscribe_service);
        break;
      case this.status.CANCELED:
        await this.handleSubscriptionAction(this.model.cancel_service);
        break;
    }
  }

  async handleSubscriptionAction(service: string) {
    try {
      this.loading = true;

      await this.$store.dispatch('agreementDocument/signDocumentFromDescription', {
        id: this.measureId,
        service,
      });

      const error = this.$store.state.agreementDocument.agreementDocumentSign.error;

      if (error) {
        throw new Error();
      }

      const { response } = await this.$store.dispatch(this.showItem, this.model.id);
      this.value = new GpbOutDataVueModel(response);

      this.$notify({ group: 'gpb-out', type: 'success', title: 'Операция успешно выполнена' });
    } catch (_err) {
      this.$notify({ group: 'gpb-out', type: 'error', title: 'Ошибка при выполнении операции' });
    } finally {
      this.loading = false;
    }
  }

  callbackResponse(model) {
    this.value = new GpbOutDataVueModel(model);
  }

  async handleEdit(): Promise<void> {
    this.loading = true;
    if (!this.edit) {
      this.edit = true;
    } else {
      const { status, response } = await this.$store.dispatch(this.model.update_apiendpoit, {
        data: this.model.getDataForUpdate(),
        id: this.model.id,
      });
      if (status) {
        this.edit = false;
        this.value = new GpbOutDataVueModel(response);
      }
    }
    this.loading = false;
  }
  cancel(): void {
    this.edit = !this.edit;
  }

  async handleCreate(): Promise<void> {
    this.model.owner_id = this.subjectId;
    const { response, status } = await this.$store.dispatch(this.model.create_apiendpoit, {
      data: this.model.getDataForCreate(),
    });
    if (status) await this.$router.push({ name: this.model.detail_link, params: { id: response.id } });
  }

  async handleDelete(): Promise<void> {
    const { status } = await this.$store.dispatch(this.model.delete_apiendpoit, {
      id: this.model.id,
    });
    if (status) await this.$router.push({ name: this.model.cancel_link });
  }
}
</script>
