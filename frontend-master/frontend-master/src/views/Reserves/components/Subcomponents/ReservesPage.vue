<template>
  <v-container>
    <page-component
      :key="clear"
      v-model="innerValue"
      :headers="innerValue.getHeaders()"
      :callback-rows="callbackLoadList"
      :get-list="apiTypeStore"
      :is-clear-filters-and-reload-rows="isClearFiltersAndReloadRows"
      :is-show-additional-button="isCreatePrivileges"
      :pageable="pageable"
      :title="title"
      @onClearFilters="
        () => {
          ++clear;
          isClearFiltersAndReloadRows = false;
        }
      "
      @onOpenCreatePage="onChangeStateAddModal"
    >
      <template #filters>
        <v-row>
          <v-col cols="12" lg="3" md="3" xl="4">
            <input-component v-model="innerValue[numberName]" label="Введите номер" placeholder="Введите значение" />
          </v-col>
          <v-col cols="12" lg="4" md="5" xl="3">
            <label-component label="Дата выдачи" />
            <v-row no-gutters>
              <v-col class="pr-1" cols="12" data-app sm="6">
                <UiDateInput v-model="innerValue.date_from" :format="'DD.MM.YYYY'" :limit-to="today" placeholder="от" />
              </v-col>
              <v-col class="pr-1" cols="12" data-app sm="6">
                <UiDateInput
                  v-model="innerValue.date_to"
                  :limit-to="today"
                  :limit-from="fromDate(innerValue.date_from)"
                  :format="'DD.MM.YYYY'"
                  placeholder="до"
                />
              </v-col>
            </v-row>
          </v-col>
          <slot name="filters" />
        </v-row>
      </template>
    </page-component>

    <dialog-component
      v-model="isShowModalForRecord"
      :close-on-outside-click="true"
      :persistent="true"
      :prompt="isPromptForCreateModal"
      cancel-title=""
      confirm-title=""
      controls-justify="justify-end"
      width="800"
      with-close-icon
    >
      <template #title>
        <span>{{ titleForCreateModal }}</span>
      </template>

      <template #content>
        <slot name="create-form" />

        <v-row class="mt-10" justify="end">
          <v-col class="col-exclude" cols="12">
            <DefaultButton title="Отмена" @click="onChangeStateAddModal" />
            <DefaultButton
              :disabled="isDisabledBtnSaveInModal"
              :loading="isLoading"
              title="Сгенерировать"
              variant="primary"
              @click="prepareDocumentForSign"
            />
          </v-col>
        </v-row>
      </template>
    </dialog-component>

    <SignatureModal v-model="isSignatureModalOpen" @approve="onCreate" />

    <v-overlay :value="isLoading">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
  </v-container>
</template>

<script lang="ts">
import { Component, Model, Prop, Mixins } from 'vue-property-decorator';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import LabelComponent from '@/components/common/Label/Label.vue';
import UiDateInput from '@/components/global/UiForm/components/UiDateInput.vue';
import { AdditionalMix } from '@/utils/mixins/additional';
import PageComponent from '@/components/Forms/PageComponent.vue';
import { RequestMix } from '@/utils/mixins/request';
import _ from 'lodash';
import { SdizVueModel } from '@/models/Sdiz/Data/Sdiz.vue';
import { SdizGpbVueModel } from '@/models/Sdiz/Data/SdizGpb.vue';
import { PermissionMix } from '@/utils/mixins/permission';
import { dateFrom } from '@/utils/date';
import SignatureModal from '@/components/SignatureModal/SignatureModal.vue';
import { GetDocumentForSignMix } from '@/utils/mixins/getDocumentForSign';

@Component({
  name: 'reserves-page-component',
  components: {
    SignatureModal,
    PageComponent,
    LabelComponent,
    UiDateInput,
    DefaultButton,
    DialogComponent,
    InputComponent,
    AutocompleteComponent,
    SelectRequestComponent,
  },
})
export default class ReservesPageComponent extends Mixins(
  AdditionalMix,
  RequestMix,
  PermissionMix,
  GetDocumentForSignMix
) {
  @Model('change', { required: true }) model!: any;
  @Prop({ default: () => [], required: true }) readonly fieldsForCreateRows!: string[];
  @Prop({ type: Boolean, default: true }) readonly isPromptForCreateModal!: boolean;
  @Prop({ type: String, required: true }) readonly title!: string;
  @Prop({ type: String, required: true }) readonly numberName!: string;
  @Prop({ type: String, required: true }) readonly apiTypeStore!: string;
  @Prop({ type: String, default: 'Добавить номер партии' }) readonly titleForCreateModal!: string;

  isClearFiltersAndReloadRows = false;
  clear = 0;
  isLoading = false;
  isShowModalForRecord = false;
  isSignatureModalOpen = false;
  numberId = null;

  fromDate(date) {
    return dateFrom(date, -1);
  }

  get innerValue() {
    return this.model;
  }

  set innerValue(value) {
    this.$emit('change', value);
  }

  get getDataForCreate() {
    const model = _(this.innerValue);
    if (model.pick(this.fieldsForCreateRows).size() === 0) return {};
    return model.pick(this.fieldsForCreateRows).value();
  }

  get isDisabledBtnSaveInModal(): boolean {
    const model = _(this.innerValue);

    if (this.fieldsForCreateRows.length === 0) return false;

    if (model.pick(this.fieldsForCreateRows).size() === 0) return true;

    let error = false;
    model.pick(this.fieldsForCreateRows).forIn((value) => (error = value === null));
    return error;
  }

  async prepareDocumentForSign() {
    try {
      await this.prepareDocumentFromDescription(this.model.pdf_from_description_service, this.getDataForCreate);
      this.isSignatureModalOpen = true;
    } catch (_e) {
      this.$notify({ group: 'reserves', type: 'error', title: 'Ошибка при получении документа для подписи' });
    }
  }

  async onCreate(): Promise<void> {
    try {
      const esp_id = this.$store.state.agreementDocument.agreementDocumentSign?.data?.esp_id;
      if (!esp_id) throw new Error();

      this.isLoading = true;
      const { response } = await this.$store.dispatch(this.apiTypeStore, {
        data: { ...this.getDataForCreate, esp_id: esp_id },
        type: 'create',
      });
      this.isClearFiltersAndReloadRows = true;
      ++this.clear;
      this.$emit('onCreate', response);
      this.isSignatureModalOpen = false;
    } catch (error) {
      this.$notify({
        group: 'reserves',
        type: 'warning',
        title: 'Ошибка при выполнении операции',
        text: error,
      } as any);
    } finally {
      this.isLoading = false;
      this.onChangeStateAddModal();
    }
  }

  get isCreatePrivileges() {
    return this.accessGrantedAuthorities(this.model['create_number_privileges']);
  }

  onChangeStateAddModal(): void {
    if (this.isCreatePrivileges) {
      this.innerValue = new this.model.constructor();
      this.isShowModalForRecord = !this.isShowModalForRecord;
      this.$emit('onChangeStateAddModal', this.isShowModalForRecord);
    }
  }

  callbackLoadList(model: any, modelArray: SdizVueModel[] | SdizGpbVueModel[], response: any[]) {
    return response.map((v) => {
      if (v.owner_id !== undefined) {
        v.owner_short_name = v.owner.name;
      }
      return new model.constructor(v);
    });
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';

.title {
  display: flex;
}

.settingsSpan {
  margin-left: 1em;
}

.settings {
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
}

.settingsSpan {
  background: none;
  border: none;
  margin-right: 14px;
  display: flex;
  align-items: center;
  text-decoration-line: underline;
  font-size: 14px;
  line-height: 16px;
  color: $medium-grey-color !important;
  cursor: pointer;
  text-align: left;

  &--disabled {
    opacity: 0.5;
    cursor: default;
  }
}

.iconSettings {
  margin-right: 5px;
}

.checkbox-elem {
  display: flex;
  padding-top: 12px;
}

.processor {
  padding-left: 40px;
}

.checkbox-title {
  font-size: 14px;
  margin-left: 4px;
  color: #828286;
}

.iconTable {
  cursor: pointer;
  padding-right: 8px;
}

.checkbox-elem {
  position: relative;
  cursor: pointer;

  input {
    position: absolute;
    top: 0;
    left: 0;
    z-index: 1;
    cursor: pointer;
    width: 100%;
    height: 100%;
  }
}
</style>
