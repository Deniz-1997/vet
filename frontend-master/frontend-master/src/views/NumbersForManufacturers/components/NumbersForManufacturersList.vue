<template>
  <v-container>
    <page-component
      :key="clear"
      v-model="model"
      :get-list="getList"
      :is-clear-filters-and-reload-rows="isClearFiltersAndReloadRows"
      is-show-additional-button
      :pageable="pageable"
      :callbackRows="callbackRows"
      :settings-show="false"
      additional-button-title="Добавить"
      title="Реестр мест формирования партии зерна"
      @onClearFilters="onClearFilters"
      @onOpenCreatePage="onOpenAddModal = !onOpenAddModal"
    >
      <template #filters>
        <v-row>
          <v-col cols="12" lg="4" md="4" sm="4" xl="4">
            <input-component
              v-model="model.lots_numbers_from_subject"
              label="Место формирования партии зерна"
              placeholder="Введите место формирования партии зерна"
            />
          </v-col>
          <v-col cols="12" lg="4" md="6" sm="4" xl="4">
            <select-request-component
              v-model="model.okpd2.code"
              label="Вид с/х культуры"
              :lot-type="{ is_grain: true }"
              placeholder="Выберите вид с/х культуры"
              type="nsi-okpd2-codes"
              :is-active="false"
              item-id="code"
            />
          </v-col>
        </v-row>
      </template>

      <template #[`table`]="{ pageable, rows, total, change }">
        <data-table
          :headers="headers"
          :items="rows"
          :items-length="total"
          :page="pageable.pageNumber"
          :per-page="pageable.pageSize"
          @onOptionsChange="change"
        >
          <template #[`item.actions`]="{ item }">
            <img
              class="iconTable"
              src="/icons/deleteBasket.svg"
              @click="
                numberId = item.id;
                isShowConfirmModal = true;
              "
            />
          </template>
          <template #[`item.active_number`]="{ item }">
            <p v-if="item.active" class="success--text">Активен</p>
            <p v-else class="orange--text">Неактивен</p>
          </template>
        </data-table>
      </template>
    </page-component>

    <Dialog-component
      v-model="onOpenAddModal"
      :persistent="true"
      :prompt="true"
      cancel-title=""
      confirm-title=""
      controls-justify="justify-end"
      width="800"
      with-close-icon
    >
      <template #title>
        <span>Добавить место формирования партии зерна</span>
      </template>

      <template #content>
        <v-col cols="12">
          <input-component
            v-model="form.lots_numbers_from_subject"
            label="Место формирования партии зерна"
            placeholder="Введите место формирования партии зерна"
          />
        </v-col>
        <v-col cols="12">
          <select-request-component
            v-model="form.okpd2_id"
            label="Вид с/х культуры"
            :lot-type="{ is_grain: true }"
            store-lot-type="is_grain"
            placeholder="Выберите вид с/х культуры"
            type="nsi-okpd2-msh"
            :additional-params="{ leafs_only: true }"
          />
        </v-col>

        <v-row class="mt-10" justify="end">
          <v-col class="col-exclude" cols="12">
            <DefaultButton v-if="onOpenAddModal" title="Закрыть" @click="closeModal" />
            <DefaultButton v-if="!onOpenAddModal" title="Отмена" @click="closeModal" />
            <DefaultButton :disabled="isDisabledSaveBtn" title="Сохранить" variant="primary" @click="handleSave" />
          </v-col>
        </v-row>
      </template>
    </Dialog-component>

    <v-overlay :value="isLoading">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>

    <confirm-modal-delete
      :show-modal="isShowConfirmModal"
      :text="'Вы действительно хотите удалить место первичного хранения'"
      @apply="handleDelete"
      @close="
        numberId = null;
        isShowConfirmModal = false;
      "
    />
  </v-container>
</template>

<script lang="ts">
import { Component } from 'vue-property-decorator';
import DataTable from '@/components/common/DataTable/DataTable.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import ConfirmModalDelete from '@/views/Authorities/components/Modal/ConfirmModalDelete.vue';
import { mixins } from 'vue-class-component';
import { AdditionalMix } from '@/utils/mixins/additional';
import PageComponent from '@/components/Forms/PageComponent.vue';
import { RequestMix } from '@/utils/mixins/request';
import LabelComponent from '@/components/common/Label/Label.vue';
import { LotNumbersVueModel } from '@/models/Lot/LotNumbers.vue';
import { Okpd2VueModel } from '@/models/Sdiz/Okpd2.vue';

@Component({
  name: 'NumbersForManufacturers-list',
  components: {
    LabelComponent,
    PageComponent,
    ConfirmModalDelete,
    DefaultButton,
    DataTable,
    DialogComponent,
    InputComponent,
    SelectRequestComponent,
  },
})
export default class NumbersForManufacturersList extends mixins(AdditionalMix, RequestMix) {
  getList = 'lot/numbers';
  clear = 0;
  model: LotNumbersVueModel = new LotNumbersVueModel();
  form: LotNumbersVueModel = new LotNumbersVueModel();

  isClearFiltersAndReloadRows = false;
  isShowConfirmModal = false;
  isLoading = false;
  onOpenAddModal = false;
  numberId = null;
  headers = [
    {
      value: 'actions',
    },
    {
      text: 'Место формирования партии зерна',
      value: 'lots_numbers_from_subject',
      sortable: false,
    },
    {
      text: 'Дата заведения',
      value: 'date',
      sortable: false,
    },
    {
      text: 'Вид с/х культуры',
      value: 'okpd2.product_name_convert',
      sortable: false,
    },
    {
      text: 'Статус',
      value: 'active_number',
      sortable: false,
    },
  ];

  get isDisabledSaveBtn() {
    if (typeof this.form.lots_numbers_from_subject === 'undefined' || typeof this.form.okpd2_id === 'undefined') {
      return true;
    }
    const { lots_numbers_from_subject, okpd2_id } = this.form;
    return lots_numbers_from_subject === '' || okpd2_id === null;
  }

  async created() {
    await this.fetchOkpd2Msh();
  }

  async handleSave() {
    try {
      this.isLoading = true;
      this.onOpenAddModal = false;
      await this.$store.dispatch('lot/numbers', {
        data: {
          lots_numbers_from_subject: this.form.lots_numbers_from_subject,
          okpd2_id: this.form.okpd2_id,
        },
        type: 'create',
      });
    } catch (_err) {
      this.$notify({
        group: 'numbers-for-manufacturers',
        type: 'warning',
        title: 'Ошибка при выполнении запроса',
      });
    } finally {
      this.isLoading = false;
      this.isClearFiltersAndReloadRows = true;
      ++this.clear;
      this.form.lots_numbers_from_subject = '';
      this.form.okpd2_id = null;
    }
  }

  async handleDelete() {
    try {
      this.isLoading = true;
      this.isShowConfirmModal = false;

      await this.$store.dispatch('lot/numbers', {
        data: {},
        id: this.numberId,
        type: 'delete',
      });
    } catch (_err) {
      this.$notify({
        group: 'numbers-for-manufacturers',
        type: 'warning',
        title: 'Ошибка при выполнении запроса',
      });
    } finally {
      this.numberId = null;
      this.isLoading = false;
      this.isClearFiltersAndReloadRows = true;
      ++this.clear;
    }
  }

  onClearFilters() {
    ++this.clear;
    this.isClearFiltersAndReloadRows = false;
  }

  closeModal() {
    this.form.lots_numbers_from_subject = '';
    this.form.okpd2_id = null;
    this.onOpenAddModal = false;
  }

  callbackRows(model: LotNumbersVueModel, modelArray: LotNumbersVueModel[], response: any[]): any[] {
    return response.map((response) => ({ ...response, okpd2: new Okpd2VueModel(response.okpd2) }));
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
