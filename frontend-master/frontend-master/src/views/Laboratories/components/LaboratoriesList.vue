<template>
  <v-container>
    <v-row>
      <v-col cols="6">
        <div class="title">
          <span>Реестр лабораторий</span>
        </div>
      </v-col>

      <v-col cols="6" class="d-flex justify-end">
        <DefaultButtonNew
          v-if="canAddNewLaboratory"
          title="Добавить"
          variant="primary"
          alt="Добавить"
          :prependIcon="'mdi-plus-circle'"
          @click="showModal(null, 'edit')"
        />
        <!-- TODO: Ожидает задачи по доработке асинхронного экспорта -->

        <!-- <span v-if="canExportRegister" class="settingsSpan" @click="exportAction">
          <img src="/icons/export.svg" class="iconSettings" />
          Экспорт списка
        </span> -->

        <div class="settings">
          <UiViewSettingsModal
            v-if="canSettingRegister"
            id="manufacture"
            type="button"
            v-model="onOpenSettings"
            :sort-map="{ 'subject.inn_kpp': ['subject.inn', 'subject.kpp'] }"
            @apply-settings="onApplySettings"
          />

          <!-- GISZP-2435 -->
<!--          <button-->
<!--            v-if="canExcludeLaboratory"-->
<!--            class="settingsSpan"-->
<!--            :disabled="!statusButton"-->
<!--            :class="{ 'settingsSpan&#45;&#45;disabled': !statusButton }"-->
<!--            @click="showActionModal"-->
<!--          >-->
<!--            <img src="/icons/delete.svg" class="iconSettings" />-->
<!--            <span>Исключить из реестра</span>-->
<!--          </button>-->
        </div>
      </v-col>

      <v-row v-if="canFilterRegister">
        <v-col
          cols="12"
          class="d-flex align-lg-center justify-end"
        >
        <span :style="{marginRight: '20px'}">
          <checkbox-component
            id="all_list"
            :value="showAll"
            name="all_list"
            label="Все записи"
            :style="{width: 'auto'}"
            @change="getLaboratories(searchResult)"
          />
        </span>
          <SearchNewComponent
            class="justify-start"
            placeholder="Введите значение поиска"
            @search="handleSearch"
          />
        </v-col>
      </v-row>

      <v-col cols="12">
        <DataTableNew
          :headers="applyHeaders"
          :items="rows"
          :items-length="total"
          :page="pageable.pageNumber"
          :per-page="pageable.pageSize"
          :search="searchResult"
          class="lastcol_fixed"
          @onOptionsChange="onOptionsChange"
        >
          <!-- GISZP-2435 -->
<!--          <template v-if="canExcludeLaboratory" #[`item.check`]="{ item }">-->
<!--            <span class="spanList">-->
<!--              <label :for="item.subjectId" class="checkbox">-->
<!--                <input-->
<!--                  :id="item.id"-->
<!--                  v-model="selectLaboratories"-->
<!--                  type="checkbox"-->
<!--                  :name="item.subjectId"-->
<!--                  :value="item.id"-->
<!--                  @change="handleCheck(item.id, item.name, item.exclusion_date)"-->
<!--                />-->
<!--                <span class="checkbox__icon">-->
<!--                  <img src="/icons/checkbox.svg" />-->
<!--                </span>-->
<!--              </label>-->
<!--            </span>-->
<!--          </template>-->

          <template #[`item.actions`]="{ item }">
            <span class="wrap_content_actions">
              <v-tooltip v-if="canViewCardLaboratory" bottom>
              <template #activator="{ on, attrs }">
                <span v-bind="attrs" v-on="on">
                  <img src="/icons/show.svg" class="iconTable" @click="showModal(item.id, 'show')" />
                </span>
              </template>
              <span>Просмотреть информацию</span>
            </v-tooltip>

            <v-tooltip v-if="canEditCardLaboratory" bottom>
              <template #activator="{ on, attrs }">
                <span v-bind="attrs" v-on="on">
                  <img
                    src="/icons/edit.svg"
                    class="iconTable"
                    :disabled="item.canEdit"
                    @click="showModal(item.id, 'edit')"
                  />
                </span>
              </template>
              <span>Редактировать информацию</span>
            </v-tooltip>
            </span>
          </template>
        </DataTableNew>
      </v-col>
    </v-row>
    <!-- GISZP-2435 -->
<!--    <ExcludeLaboratories-->
<!--      :id="selectLaboratory.id"-->
<!--      v-model="isShowActionModal"-->
<!--      :name="selectLaboratory.name"-->
<!--      @close="closeModal"-->
<!--      @excludeAction="closeModal"-->
<!--    />-->

    <LaboratoryCard
      :id="idEditCard"
      v-model="onOpenAddModal"
      :is-show="typeAction === 'show'"
      :show-actual="!showAll"
      @close="closeModal"
    />

    <!-- <DialogComponent
      v-model="onOpenAddModal"
      :prompt="false"
      cancel-title=""
      confirm-title=""
      width="800"
      with-close-icon
      controls-justify="justify-end"
    >
      <template #title>
        <span v-if="idEditCard">Данные лаборатории</span>
        <span v-if="!idEditCard">Добавление лаборатории</span>
      </template>

      <template #content>
        <LaboratoriesOrganization
          v-if="onOpenAddModal"
          :is-show="typeAction === 'show'"
          :show-actual="showActual"
          :id-card="idEditCard"
          @close="closeModal"
        />
      </template>
    </DialogComponent> -->

    <v-overlay :value="isLoading" :absolute="true">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
  </v-container>
</template>

<script lang="ts">
import { Component, Mixins } from 'vue-property-decorator';
import DataTableNew from '@/components/common/DataTableNew/DataTable.vue';
import SearchNewComponent from '@/components/common/SearchNew/Search.vue';
import DefaultButtonNew from '@/components/common/buttons/DefaultButtonNew.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import ExcludeLaboratories from '@/views/Laboratories/components/ExcludeLaboratories.vue';
import LaboratoryCard from './LaboratoryCard.vue';
import { EAction, mapAccessFlags } from '@/utils';
import ViewSettingsModal from '@/components/common/ViewSettings/ViewSettingsModal.vue';
import { TInnerFilter } from '@/services/models/common';
import Exportable from '@/utils/global/mixins/exportable';

@Component({
  name: 'laboratories-list',
  components: {
    SearchNewComponent,
    DataTableNew,
    DefaultButtonNew,
    DialogComponent,
    ExcludeLaboratories,
    ViewSettingsModal,
    LaboratoryCard,
  },
  computed: {
    ...mapAccessFlags({
      canFilterRegister: EAction.FILTER_LABORATORY_REGISTER,
      canSettingRegister: EAction.CUSTOMIZE_LABORATORY_REGISTER,
      canExportRegister: EAction.EXPORT_LABORATORY_REGISTER,
      // GISZP-2435
      //canExcludeLaboratory: EAction.DELETE_LABORATORY,
      canViewCardLaboratory: EAction.READ_LABORATORY,
      canEditCardLaboratory: EAction.UPDATE_LABORATORY,
      canAddNewLaboratory: EAction.CREATE_LABORATORY,
    }),
  },
})
export default class LadoratoriesList extends Mixins(Exportable('/api/laboratory/exportReport2')) {
  // GISZP-2435
  // statusButton = false;
  // selectLaboratories: any[] = [];
  // isShowActionModal = false;
  rows: any[] = [];
  isLoading = false;
  showAll = false;
  applyHeaders: any[] = [];
  idEditCard = '';
  onOpenAddModal = false;
  onOpenSettings = false;
  checked = false;
  searchResult = '';
  filter: TInnerFilter = {
    pageable: {
      pageSize: 5,
      pageNumber: 0,
      sort: [{ direction: 'ASC', property: 'subject.name' }],
    },
  };
  pageable = {
    pageSize: 5,
    pageNumber: 0,
  };
  typeAction = 'view';
  total = 0;
  infoAboutLaboratory: any = {};
  headers = [
    {
      text: 'Наименование',
      value: 'subject.subject_data.name',
    },
    {
      text: 'ИНН/КПП',
      value: 'subject.subject_data.inn_kpp',
    },
    {
      text: 'Адреса осуществления деятельности',
      value: 'location_concat',
    },
    {
      text: 'Аттестаты аккредитации',
      value: 'certificates_string',
    },
    {
      text: 'Дата включения в реестр',
      value: 'include_date',
    },
    {
      text: 'Дата исключения из реестра',
      value: 'exclusion_date',
    },
    {
      text: 'Причина исключения',
      value: 'reason_exclusion',
    },
    {
      text: 'Действия',
      value: 'actions',
      class: 'th_actions fixed fixed--right',
      sortable: false,
    },
  ];

  mounted() {
    this.applyHeaders = this.headers;
    this.getLaboratories();
  }
// GISZP-2435
  // get selectLaboratory() {
  //   if (this.selectLaboratories.length === 1) {
  //     // ToDo: Исправить типизацию.
  //     const organization: any = this.rows.find((item: any) => item.id === this.selectLaboratories[0]);
  //
  //     return {
  //       name: organization.subject ? organization.subject.subject_data.name : null,
  //       id: organization.id,
  //       exclusion_date: !organization.exclusion_date,
  //     };
  //   } else {
  //     return {
  //       name: '',
  //       id: 0,
  //       exclusion_date: false,
  //     };
  //   }
  // }

  async getLaboratories(value = this.searchResult) {
    this.isLoading = true;
    const { sort } = this.filter;
    const { content, totalElements } = await this.$store.dispatch('laboratories/getList', {
      filter: value ? value : '',
      actual: !this.showAll,
      pageable: { ...this.pageable, sort },
    });
    this.total = totalElements;
    this.rows = content;
    this.isLoading = false;
  }

  onOptionsChange(v) {
    // GISZP-2435
    // this.statusButton = false;
    // this.selectLaboratories = [];
    this.checked = false;
    this.pageable.pageNumber = v.page + 1;
    this.pageable.pageSize = v.size;
    this.getLaboratories(this.searchResult);
  }

  async handleSearch(value: string) {
    this.pageable.pageNumber = 0;
    this.searchResult = value;
    this.getLaboratories(value);
  }

  async showModal(id: string, typeAction: string) {
    this.isLoading = true;
    this.onOpenAddModal = true;
    this.idEditCard = id ? String(id) : '';
    this.typeAction = typeAction;
    this.isLoading = false;
  }

  onApplySettings(data) {
    this.applyHeaders = data.columns;
    this.filter.sort = data.sort;
    this.getLaboratories();
  }

  handleCheck() {
    if (this.checked) {
      this.checked = false;
      // GISZP-2435
      // this.statusButton = false;
      return;
    }
    this.checked = true;
    // GISZP-2435
    // this.statusButton = this.selectLaboratory.exclusion_date;
  }

  getExportFilter() {
    return {
      filter: this.searchResult,
      actual: !this.showAll,
      pageable: this.filter.pageable,
    };
  }

// GISZP-2435
  // showActionModal() {
  //   this.isShowActionModal = true;
  // }

  closeModal() {
    this.onOpenAddModal = false;
    this.idEditCard = '';
    this.getLaboratories();
    // GISZP-2435
    // this.selectLaboratory.id = null;
    // this.statusButton = false;
    // this.selectLaboratories = [];
    // this.isShowActionModal = false;
    this.checked = false;
  }

  closeSettingsModal() {
    this.onOpenSettings = !this.onOpenSettings;
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';

.settings {
  display: inline-block;
  vertical-align: middle;
  background-color: #fff;
  color: $medium-grey-color;
  text-align: center;
  text-decoration: none;
  font-size: 15px;
  border-radius: 4px;
  margin-right: 0;
  margin-left: 0;
  border: 1px solid $input-border-color;
  width: 40px;
  height: 40px;
  line-height: 3.1;
  cursor: pointer;
}

.settingsSpan {
  vertical-align: middle;
  background-color: #fff;
  color: $medium-grey-color;
  padding: 0 16px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 15px;
  border-radius: 4px;
  margin-right: 8px;
  margin-left: 0;
  border: 1px solid $input-border-color;
  height: 40px;
  line-height: 2.5;
  cursor: pointer;

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
  color: $medium-grey-color;
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

<style lang="scss">
.checkbox_grain_processing {
  .v-input__slot .v-icon::before {
    background-image: url("/icons/grain_processing.svg") !important;
    width: 14px !important;
    background-size: contain;
  }

  &.v-input--is-dirty {
    .v-input__slot .v-icon::before {
      background-image: url("/icons/grain_processing_check.svg") !important;
    }
  }

  .v-input--selection-controls__input {
    margin-right: 0;
  }
}
</style>
