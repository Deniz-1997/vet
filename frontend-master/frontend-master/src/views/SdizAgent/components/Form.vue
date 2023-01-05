<template>
  <v-row>
    <v-col cols="12">
      <text-component class="title d-flex align-center" variant="span">
        <template v-if="isCreate">Формирование сведений</template>
        <template v-if="isDetail && !isEdit"> Просмотр сведения №{{ model.id }} </template>
        <template v-if="isDetail && isEdit"> Редактирование сведения №{{ model.id }} </template>
        <v-chip v-show="!isCreate" :color="isEdit ? 'warning' : ''" class="ml-3" label>
          {{ isEdit ? 'Редактирование' : model.status_translate }}
        </v-chip>
      </text-component>
    </v-col>

    <v-col cols="12" style="z-index: 1">
      <v-row v-if="model.sdiz_id !== null">
        <v-col cols="12">
          <v-row>
            <v-col cols="12" lg="4" md="4" xl="4">
              <UiDateInput
                v-model="model.date"
                :format="'DD.MM.YYYY'"
                :disabled="true"
                class="pr-2"
                label="Дата формирования"
              />
            </v-col>

            <v-col cols="12" lg="8" md="6" xl="6">
              <InputComponent
                :value="repositoryName"
                :tooltip="repositoryName"
                label="Организация, осуществляющая хранение зерна"
                disabled
              />
            </v-col>
          </v-row>
        </v-col>

        <v-col cols="12">
          <v-row>
            <v-col cols="12" lg="4" md="4" xl="3">
              <UiDateInput
                v-model="model.date_contract"
                :disabled="!isCreate && !isEdit"
                :limit-to="today"
                :format="'DD.MM.YYYY'"
                label="Дата государственного контракта"
                placeholder="Выберите дату контракта"
              />
            </v-col>
            <v-col cols="12" lg="4" md="4" xl="3">
              <input-component
                v-model="model.number_contract"
                :disabled="!isCreate && !isEdit"
                :maxlength="25"
                label="Номер государственного контракта"
                placeholder="Введите номер"
              />
            </v-col>
          </v-row>
        </v-col>

        <v-col cols="12">
          <v-row>
            <v-col cols="12" lg="4" md="4" xl="3">
              <UiDateInput
                v-model="model.date_resolution"
                :disabled="!isCreate && !isEdit"
                :limit-to="today"
                :format="'DD.MM.YYYY'"
                label="Дата решения"
                placeholder="Выберите дату решения"
              />
            </v-col>
            <v-col cols="12" lg="4" md="4" xl="3">
              <input-component
                v-model="model.number_resolution"
                :disabled="!isCreate && !isEdit"
                :maxlength="25"
                label="Номер решения"
                placeholder="Введите номер"
              />
            </v-col>
          </v-row>
        </v-col>
      </v-row>

      <v-row v-if="isCreate && model.sdiz_id">
        <v-col v-show="error !== null" class="right-align mr-3" cols="12">
          <TextComponent class="text-caption text-center orange--text" variant="span">
            {{ error }}
          </TextComponent>
        </v-col>
      </v-row>
    </v-col>

    <template>
      <v-expansion-panels v-model="tabs" class="mt-5 exp-panel" multiple>
        <v-expansion-panel v-for="(item, i) in viewPanel ? viewPanels : createPanels" :key="i">
          <v-expansion-panel-header class="">
            <text-component variant="h6" class="text-decoration-underline text-hover">
              {{ titles[i] }}
            </text-component>
            <template #actions>
              <i
                ><img
                  alt=""
                  :class="['icon', 'arrow', openTab(i, tabs) ? 'active' : 'deactive']"
                  src="/icons/arrow.svg"
              /></i>
            </template>
          </v-expansion-panel-header>
          <v-expansion-panel-content>
            <v-row v-if="i === 0">
              <v-col v-if="model.sdiz_id === null" cols="12">
                <v-row v-show="isCreate">
                  <v-col cols="8" lg="4" xl="2">
                    <!--          :hint="isEnterSdizNumber? 'Введите номер СДИЗ' : ''"-->
                    <input-component
                      v-model="model.sdiz_number_to_attach"
                      :maxlength="25"
                      label="Номер СДИЗ"
                      placeholder="Введите номер: 234, 2445623523/200/0000"
                    />
                  </v-col>
                  <v-col cols="4">
                    <button-component
                      :disabled="isEnterSdizNumber"
                      :loading="isLoading"
                      class="mt-8"
                      size="micro"
                      title="Поиск"
                      variant="primary"
                      @click="onLoadRows"
                    />
                  </v-col>

                  <v-col cols="12">
                    <sdiz-list-tables
                      :value-rows="rows"
                      :is-hide-action-show="true"
                      :headers="sdizModel.getHeaders()"
                      :model="sdizModel"
                      :pageable="pageable"
                      :total="total"
                      @onOptionsChange="onOptionsChange"
                    >
                      <template #action-select-row="{ item }">
                        <button-component class="btn-small" title="Выбрать" @click="onSelectSdiz(item)" />
                      </template>
                    </sdiz-list-tables>
                  </v-col>
                </v-row>
              </v-col>

              <v-col v-else cols="12">
                <v-row>
                  <v-col cols="5" lg="4" xl="2">
                    <input-component
                      v-model="model.sdiz.sdiz_number"
                      disabled
                      label="Номер СДИЗ"
                      placeholder="Введите номер"
                    />
                  </v-col>
                  <v-col v-show="isCreate" cols="3">
                    <button-component class="mt-8" size="micro" title="Выбрать другой СДИЗ" @click="onClearSdiz" />
                  </v-col>
                  <v-col v-show="isCreate" cols="3">
                    <button-component
                      class="mt-8"
                      size="micro"
                      title="Сохранить"
                      variant="primary"
                      :disabled="isCreateDisabled"
                      @click="handleCreate"
                    />
                  </v-col>

                  <v-col cols="12">
                    <v-row>
                      <v-col cols="12">
                        <sdiz-block-realization v-model="model.sdiz" :is-create="false" :is-edit="false" />
                      </v-col>
                    </v-row>
                  </v-col>
                </v-row>
              </v-col>
            </v-row>

            <v-row v-if="i === 1 && model.sdiz_id !== null">
              <v-col cols="12">
                <v-col cols="12">
                  <lot-form v-model="model.sdiz.objects.lot" :is-not-agent="false" :is-detail="true" :is-edit="false" />
                </v-col>
              </v-col>
            </v-row>
          </v-expansion-panel-content>
        </v-expansion-panel>
      </v-expansion-panels>
    </template>

    <v-col cols="12">
      <v-row v-if="isEdit">
        <v-col v-show="error !== null" class="right-align mr-3" cols="12">
          <TextComponent class="text-caption text-center orange--text" variant="span">
            {{ error }}
          </TextComponent>
        </v-col>
      </v-row>

      <v-row v-if="model.sdiz_id !== null">
        <v-col v-show="isDetail" class="mt-5" cols="12">
          <v-row class="ma-0" justify="end">
            <button-component
              v-show="!isEdit"
              class="mr-7"
              size="micro"
              title="Вернуться в реестр"
              @click="$router.push({ name: model.link_registry })"
            />
            <template v-if="isShow">
              <button-component
                v-show="model.status === 'CREATE' && isEdit"
                title="Отмена"
                size="micro"
                @click="cancel"
              />

              <modal-button
                v-if="model.status === 'CREATE' && !isEdit && accessGrantedAuthorities(model.delete_privileges)"
                button-text="Удалить"
                modal-text="Вы действительно хотите удалить СДИЗ ?"
                btn-class="red lighten-2 white--text"
                primary
                variant-text="h5"
                @onResumeClick="deleteActionMix(model.id)"
              />

              <button-component
                v-show="model.status === 'CREATE' && accessGrantedAuthorities(model.update_privileges)"
                :title="isEdit ? 'Сохранить' : 'Редактировать'"
                size="micro"
                variant="primary"
                :disabled="isEditDisabled"
                @click="handleEdit"
              />

              <button-component
                v-show="model.status === 'CREATE' && !isEdit && accessGrantedAuthorities(model.sign_privileges)"
                size="micro"
                title="Подписать"
                variant="primary"
                @click="initiateSignAction(signAction.SUBSCRIBE)"
              />

              <button-component
                v-show="model.status === 'SUBSCRIBED' && accessGrantedAuthorities(model.cancel_privileges)"
                size="micro"
                title="Аннулировать"
                variant="primary"
                @click="initiateSignAction(signAction.CANCEL)"
              />
            </template>
          </v-row>
        </v-col>
      </v-row>
    </v-col>

    <v-overlay :value="isLoading">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>

    <SignatureModal v-model="isSignatureModalOpened" @approve="handleSignApprove" @close="handleSignCancel" />
  </v-row>
</template>

<script lang="ts">
import { Component, Model, Prop, Mixins } from 'vue-property-decorator';
import DataTable from '@/components/common/DataTable/DataTable.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import ButtonComponent from '@/components/common/buttons/DefaultButton.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import ConfirmModalDelete from '@/views/Authorities/components/Modal/ConfirmModalDelete.vue';
import ModalButton from '@/components/common/buttons/ModalButton.vue';
import { AdditionalMix } from '@/utils/mixins/additional';
import PageComponent from '@/components/Forms/PageComponent.vue';
import { RequestMix } from '@/utils/mixins/request';
import LabelComponent from '@/components/common/Label/Label.vue';
import UiDateInput from '@/components/global/UiForm/components/UiDateInput.vue';
import { AgentVueModel } from '@/models/Sdiz/Agent.vue';
import moment from 'moment';
import TextComponent from '@/components/common/TextComponent.vue';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import SdizListTables from '@/views/Sdiz/components/Subcomponents/Table/SdiztListTables.vue';
import { fetchRowsFromTable, getElementById } from '@/utils/methodsForViews';
import { SdizVueModel } from '@/models/Sdiz/Data/Sdiz.vue';
import LotForm from '@/views/Lot/components/Form.vue';
import SdizBlockRealization from '@/views/Sdiz/components/Subcomponents/Blocks/SdizBlockRealization.vue';
import { SdizAgentVue } from '@/models/Sdiz/Data/SdizAgent.vue';
import { ActionsMix } from '@/utils/mixins/actions';
import { PermissionMix } from '@/utils/mixins/permission';
import { GetDocumentForSignMix } from '@/utils/mixins/getDocumentForSign';
import SignatureModal from '@/components/SignatureModal/SignatureModal.vue';
import { LotDataVueModel } from '@/models/Lot/Data/LotData.vue';

enum SignAction {
  SUBSCRIBE,
  CANCEL,
  NONE,
}

@Component({
  name: 'sdiz-agent-form',
  components: {
    SignatureModal,
    SdizBlockRealization,
    LotForm,
    SdizListTables,
    ButtonComponent,
    TextComponent,
    LabelComponent,
    UiDateInput,
    PageComponent,
    ConfirmModalDelete,
    ModalButton,
    DefaultButton,
    DataTable,
    DialogComponent,
    InputComponent,
    SelectRequestComponent,
    AutocompleteComponent,
  },
})
export default class SdizAgentForm extends Mixins(
  AdditionalMix,
  RequestMix,
  ActionsMix,
  PermissionMix,
  GetDocumentForSignMix
) {
  @Model('change', { type: AgentVueModel, required: true }) readonly model!: AgentVueModel;

  @Prop({ type: Boolean, default: false }) readonly isCreate!: boolean;

  @Prop({ type: Boolean, default: false }) readonly isDetail!: boolean;

  @Prop({ type: Boolean, default: true }) readonly isShow!: boolean;

  today = new Date();

  sdizModel: SdizAgentVue = new SdizAgentVue();

  isLoading = false;

  isEdit = false;

  signAction = SignAction;
  currentSignAction = SignAction.NONE;

  isSignatureModalOpened = false;

  date: string = moment().format('DD.MM.YYYY');
  getList = 'sdiz/findForAgent';
  showItem = 'sdiz/showAgent';
  panels: number[] = [];
  createPanels = 1;
  viewPanels = 2;

  openTab(item: number, array: number[]): boolean {
    if (this.isCreate && !this.viewPanel && array.length === 0) {
      array[0] = 0;
    }
    return array.find((value) => value === item) !== undefined;
  }

  get titles(): string[] {
    let title = 'Партия зерна';
    let additional_info =
      this.model.sdiz?.objects?.lot.id !== null ? `${title} ${this.model.sdiz?.objects?.lot.getLotNumber()}` : title;
    return !this.viewPanel ? ['Поиск по номеру СДИЗ'] : ['Сведения о реализации', additional_info];
  }

  get viewPanel(): boolean {
    return this.model.sdiz_id !== null;
  }

  get tabs(): Array<number> {
    return this.panels;
  }
  set tabs(val) {
    this.panels = val;
  }

  get isEnterSdizNumber() {
    return this.model.sdiz_number_to_attach === null || this.model.sdiz_number_to_attach === '';
  }

  get payload() {
    return this.request;
  }

  get value() {
    return this.model;
  }

  set value(value) {
    this.$emit('change', value);
  }

  async created() {
    this.deleteLink = this.model.delete_apiendpoit;
    this.afterDeletePush = this.model.name_route_list;
  }

  cancel() {
    this.isEdit = !this.isEdit;
  }

  async handleEdit() {
    this.isLoading = true;

    if (!this.isEdit) {
      this.isEdit = !this.isEdit;
    } else {
      const { status, response } = await this.$store.dispatch('sdiz/updateAgent', {
        data: this.model.getDataForUpdate(),
        id: this.model.id,
      });

      if (status) {
        this.$notify({ group: 'lot', type: 'success', title: 'Операция успешно выполнена', text: '' });
        this.isEdit = false;
        await this.setModelData(response);
      } else {
        this.$notify({ group: 'lot', type: 'error', title: 'Ошибка при обновлении партии зерна', text: '' });
      }
    }

    this.isLoading = false;
  }

  async initiateSignAction(action: SignAction) {
    try {
      this.isLoading = true;

      switch (action) {
        case SignAction.SUBSCRIBE:
          await this.getNewOrStoredDocument(this.model.id, this.model.export_pdf_for_subscription_service);
          break;
        case SignAction.CANCEL:
          await this.getNewOrStoredDocument(this.model.id, this.model.export_pdf_for_cancel_service);
          break;
      }

      this.currentSignAction = action;
      this.isSignatureModalOpened = true;
    } catch (_e) {
      this.$notify({ group: 'sdiz_agent', type: 'error', title: 'Ошибка при подготовке документа для подписания' });
    } finally {
      this.isLoading = false;
    }
  }

  async handleSignApprove() {
    switch (this.currentSignAction) {
      case SignAction.SUBSCRIBE:
        await this.handleSubscribed();
        break;
      case SignAction.CANCEL:
        await this.handleCanceled();
        break;
    }

    this.isSignatureModalOpened = false;
    this.currentSignAction = SignAction.NONE;
  }

  handleSignCancel() {
    this.currentSignAction = SignAction.NONE;
  }

  async handleSubscribed(): Promise<void> {
    await this.handleSignAction(this.model.subscribe_service);
  }

  async handleCanceled(): Promise<void> {
    await this.handleSignAction(this.model.cancel_service);
  }

  async handleSignAction(service: string) {
    try {
      this.isLoading = true;

      await this.$store.dispatch('agreementDocument/signDocumentFromDescription', {
        id: this.model.id,
        service,
      });

      const error = this.$store.state.agreementDocument.agreementDocumentSign.error;

      if (error) {
        throw new Error();
      }

      this.$notify({ group: 'sdiz_agent', type: 'success', title: 'Операция успешно выполнена' });

      const response = await getElementById(this, this.model.id as number);
      await this.setModelData(response);
    } catch (_err) {
      this.$notify({ group: 'sdiz_agent', type: 'error', title: 'Ошибка при выполнении операции' });
    } finally {
      this.isLoading = false;
    }
  }

  async onLoadRows() {
    this.isLoading = true;

    try {
      await this.searchSdiz();
      this.isLoading = false;
    } catch (e) {
      this.isLoading = false;
    }
  }

  async onOptionsChange(array) {
    if (this.pageable.pageNumber !== array.page + 2 || this.pageable.pageSize !== array.size) {
      this.pageable.pageNumber = array.page + 2;
      this.pageable.pageSize = array.size;
      await this.searchSdiz();
    }
  }

  async onSuccessResponse(response: any, pagination: any) {
    this.pagination = pagination;
    this.total = pagination.totalResults;
    this.rows = response.map((v) => new SdizVueModel(v));
  }

  async onSelectSdiz(item: SdizVueModel) {
    this.model.sdiz_id = item.id;
    this.model.sdiz = item;
    this.model.repository_id = item.objects?.lot?.repository_id || null;

    if (item.id) {
      const { response } = await this.$store.dispatch((this.model.sdiz as SdizVueModel).show_lot_apiendpoit, item.id);

      this.model.sdiz.objects.lot = new LotDataVueModel(response);
    }
  }

  async handleCreate(): Promise<void> {
    try {
      this.isLoading = true;
      let data: any = this.model.getDataForCreate();

      const { response, status } = await this.$store.dispatch('sdiz/createAgent', data);

      if (!status) {
        this.$store.commit('errors/setErrorsList', '500: Ошибка загрузки данных, попробуйте позже.');
      }

      this.$router.push({ name: 'sdiz_agent_detail', params: { id: response.id } });
    } catch (e) {
      this.$notify({ group: 'sdiz', type: 'warning', title: 'Ошибка при формировании запроса', text: '' });
      this.isLoading = false;
    }
  }

  async onClearSdiz() {
    this.model.sdiz_id = null;
    this.model.sdiz = null;
    this.model.repository_id = null;
    this.model.repository_name = '-';
  }

  async setModelData(payload) {
    this.value = new AgentVueModel(payload);

    if (payload.sdiz?.id) {
      const { response } = await this.$store.dispatch(
        (this.model.sdiz as SdizVueModel).show_lot_apiendpoit,
        payload.sdiz?.id
      );

      (this.value.sdiz as SdizVueModel).objects.lot = new LotDataVueModel(response);
    }
  }

  get error(): string | null {
    return this.model.getErrors().length ? this.model.getErrors()[0] : null;
  }

  get isCreateDisabled(): boolean {
    return !!this.error || !this.model.sdiz_id;
  }

  get isEditDisabled(): boolean {
    return (!!this.error || !this.model.sdiz_id) && this.isEdit;
  }

  async searchSdiz() {
    await fetchRowsFromTable(this);

    if (!this.rows.length) {
      this.$service.notify.push('error', { text: 'СДИЗ с указанным номером не найден' });
    }
  }

  get repositoryName() {
    return this.model.sdiz?.objects.lot.objects.repository?.name || '-';
  }
}
</script>

<style lang="scss" scoped>
@import './src/assets/styles/_variables';
@import './src/assets/styles/_mixins';

.text-hover:hover {
  color: orange;
  transition: color 0.2s;
}

.exp-panel {
  .v-expansion-panel-header {
    padding-left: 0;
  }

  .v-application--is-ltr .v-expansion-panel-header {
    border-bottom: 1px solid #c0c0c040;
  }

  .v-expansion-panels {
    border-top: 1px solid #c0c0c040;
    border-bottom: 1px solid #c0c0c040;
  }

  .v-expansion-panel-header {
    height: 100px !important;
  }

  .v-expansion-panel::before {
    box-shadow: none;
  }
}

.btn-small {
  height: 23px !important;
  font-size: 12px !important;
  padding-left: 8px !important;
  padding-right: 8px !important;
}
</style>
