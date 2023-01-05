<template>
  <div class="nsi-card container">
    <v-row>
      <v-col cols="12">
        <div class="title">{{ NsiTitle }}</div>
      </v-col>
    </v-row>

    <div class="nsi-card-header">
      <v-row>
        <v-col cols="12" lg="10" class="d-flex align-lg-center flex-column flex-lg-row">
          <SearchComponent class="mb-4 mb-lg-0 mr-6" placeholder="Введите значение поиска" @search="handleSearch" />

          <div class="d-flex">
            <div v-if="NsiApi === '/api/nci/okpd2' && activeNsi !== 'nsi-type-product'" class="d-flex mb-xl-0">
              <div class="elementsInput checkbox-block mr-6">
                <label class="checkbox">
                  <input v-model="is_product" type="checkbox" name="check" @click="showProduct" />
                  <span class="checkbox__icon">
                    <img src="/icons/checkbox.svg" alt="" />
                  </span>
                </label>
                <span class="label">Продукт переработки зерна</span>
              </div>

              <div class="elementsInput checkbox-block mr-6">
                <label class="checkbox">
                  <input v-model="is_grain" type="checkbox" name="check" @click="showGrain" />
                  <span class="checkbox__icon">
                    <img src="/icons/checkbox.svg" alt="" />
                  </span>
                </label>
                <span class="label">Зерно</span>
              </div>
            </div>

            <div class="elementsInput checkbox-block">
              <label class="checkbox">
                <input type="checkbox" name="check" @click="showDeleted" />
                <span class="checkbox__icon">
                  <img src="/icons/checkbox.svg" alt="" />
                </span>
              </label>
              <span class="label">Отображать все записи</span>
            </div>
          </div>
        </v-col>
      </v-row>
      <v-row v-if="activeNsi === 'nsi-quality-indicators-limit'">
        <v-col>
          <nsi-filter @filter="handleFilter" />
        </v-col>
      </v-row>
      <v-row>
        <v-col class="d-flex justify-space-between">
          <div class="d-flex">
            <div v-if="canCreateDictionaryRecord" class="link-btn" @click="addNewItem">
              <img class="link-btn__icon" src="/icons/add.svg" alt="Добавить запись" />
              <span class="link-btn__text">Добавить запись</span>
            </div>
            <div v-if="canReadDictionaryRecord && canExport" class="link-btn ml-2" @click="exportRegister">
              <img class="link-btn__icon" src="/icons/export.svg" alt="Экспорт списка" />
              <span class="link-btn__text">Экспорт списка</span>
            </div>
          </div>

          <div class="total-note mr-6">Записей: {{ totalRows }}</div>
        </v-col>
      </v-row>
    </div>

    <v-row>
      <v-col cols="12" lg="12">
        <DataTable
          :headers="headers"
          :items="computedRows"
          :item-class="paintDeletedRow"
          :items-length="totalRows"
          :page="pageable.pageNumber"
          :per-page="pageable.pageSize"
          :search="searchResult"
          @onOptionsChange="onOptionsChange"
        >
          <template #[`item.actions`]="{ item }">
            <div class="d-flex align-center">
              <v-tooltip v-if="canReadDictionaryRecord" bottom>
                <template #activator="{ on, attrs }">
                  <span v-bind="attrs" class="d-flex align-center justify-center" v-on="on">
                    <div
                      v-if="isShowView"
                      class="d-flex align-center justify-center nsi-list__item"
                      @click="showCard(item)"
                    >
                      <img src="/icons/show.svg" alt="Просмотреть запись" class="iconTable" />
                    </div>
                  </span>
                </template>

                <span>Просмотреть запись</span>
              </v-tooltip>

              <v-tooltip v-if="canUpdateDictionaryRecord && item.is_actual" bottom>
                <template #activator="{ on, attrs }">
                  <span v-bind="attrs" v-on="on" @click="editItem(item, 'edit')">
                    <v-icon small class="iconTable">mdi-plus-box-multiple</v-icon>
                  </span>
                </template>
                <span>Создать новую версию</span>
              </v-tooltip>

              <v-tooltip
                v-if="(canUpdateDictionaryRecord || canDeleteDictionaryRecord) && item.is_actual && !item.endDate"
                bottom
              >
                <template #activator="{ on, attrs }">
                  <span v-bind="attrs" v-on="on" @click="waitForDelete = item">
                    <v-icon small class="iconTable">mdi-calendar</v-icon>
                  </span>
                </template>
                <span>Указать дату окончания</span>
              </v-tooltip>
            </div>
          </template>

          <template #[`item.grain_sign`]="{ item }">
            <label class="checkbox">
              <input v-model="item.grain_sign" type="checkbox" :checked="item.grain_sign" disabled />
              <span class="checkbox__icon">
                <img src="/icons/checkbox.svg" alt="" />
              </span>
            </label>
          </template>
          <template #[`item.grain_product_sign`]="{ item }">
            <label class="checkbox">
              <input v-model="item.grain_product_sign" type="checkbox" :checked="item.grain_product_sign" disabled />
              <span class="checkbox__icon">
                <img src="/icons/checkbox.svg" alt="" />
              </span>
            </label>
          </template>
          <template #[`item.min_value`]="{ item }">
            <span v-if="item.hasOwnProperty('min_value')">
              {{ item.min_value }}
            </span>
            <span v-else-if="item.values">
              {{ item.values.join('; ') }}
            </span>
          </template>
          <template #[`item.start_date`]="{ item }">
            <span v-if="item.start_date">
              {{ $moment(item.start_date, 'DD.MM.YYYY hh:mm').format('DD.MM.YYYY') }}
            </span>
          </template>
          <template #[`item.end_date`]="{ item }">
            <span v-if="item.end_date">
              {{ $moment(item.end_date, 'DD.MM.YYYY hh:mm').format('DD.MM.YYYY') }}
            </span>
          </template>
        </DataTable>
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12" class="d-flex justify-end">
        <DefaultButton title="Закрыть" @click="goBack"> </DefaultButton>
      </v-col>
    </v-row>

    <Dialog-component
      v-model="onOpenAddModal"
      :prompt="false"
      cancel-title=""
      confirm-title=""
      width="458px"
      with-close-icon
      controls-justify="justify-end"
      @close="closeModal"
    >
      <template #title>
        {{ actionTitle }}
      </template>
      <template #content>
        <ActionsNsi
          :active-nsi="activeNsi"
          :action="actionType"
          :item="editElement"
          :active-url="NsiApi"
          :create-url="createUrl"
          :additional-api-url="additionalApiUrl"
          :additional-api-url-second="additionalApiUrlSecond"
          :catalog-tnved-api-url="catalogTnvedApiUrl"
          :modal="modal"
          :filter-values="activeNsi === 'nsi-quality-indicators-limit' ? valuesFilter : {}"
          @close="closeModal"
          @save="getNsiList"
        />
      </template>
    </Dialog-component>

    <Dialog-component
      v-model="isDeleteModalShow"
      :prompt="false"
      cancel-title=""
      confirm-title=""
      width="420"
      with-close-icon
      controls-justify="justify-end"
    >
      <template #title>
        <span>Дата окончания</span>
      </template>

      <template #content>
        <v-row>
          <v-col cols="12">
            <p class="mb-0">Укажите дату окончания действия</p>
          </v-col>
          <v-col cols="12" class="d-flex align-end pb-0">
            <UiDateInput
              v-model="endDate"
              class="datePicker mr-4"
              :format="'DD.MM.YYYY hh:mm'"
              :limit-from="$moment().add(-1, 'd').toDate()"
            />
            <DefaultButton
              variant="primary"
              title="Подтвердить"
              :disabled="!endDate || isLoading"
              @click="cancelOKPD2(endDate)"
            />
          </v-col>
        </v-row>
      </template>
    </Dialog-component>

    <v-overlay :value="isLoading" :absolute="true">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
  </div>
</template>

<script lang="ts">
// TODO: очень плохо
import { Component, Vue } from 'vue-property-decorator';
import ActionsNsi, { mapForm } from '@/views/NSI/components/Modal/ActionsNsi.vue';
import nsiList from '@/views/NSI/config';
import DataTable from '@/components/common/DataTable/DataTable.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import moment from 'moment';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import SearchComponent from '@/components/common/Search/Search.vue';
import { EAction, mapAccessFlags } from '@/utils';
import { showReport } from '@/utils/file';
import NsiFilter from '../Filter/Filter.vue';

type HeadersItem = {
  text: string;
  value: string;
};
type modalItem = {
  text: string;
};

type rowsItem = {
  code: string;
  actions: string;
  okpd2: string;
  tnved: string;
  name: string;
  symbol: string;
  startDate: string;
  endDate: string;
  end_date: string;
  qualityIndicators: string;
};

@Component({
  name: 'nsi-list',
  components: {
    ActionsNsi,
    DataTable,
    DialogComponent,
    DefaultButton,
    SearchComponent,
    NsiFilter,
  },
  computed: {
    ...mapAccessFlags({
      canReadDictionaryRecord: EAction.READ_DICTIONARY_RECORD,
      canCreateDictionaryRecord: EAction.CREATE_DICTIONARY_RECORD,
      canUpdateDictionaryRecord: EAction.UPDATE_DICTIONARY_RECORD,
      canDeleteDictionaryRecord: EAction.DELETE_DICTIONARY_RECORD,
    }),
  },
  metaInfo(this: NsiList) {
    return { title: this.NsiTitle };
  },
})
export default class NsiList extends Vue {
  readonly canReadDictionaryRecord!: boolean;
  readonly canCreateDictionaryRecord!: boolean;
  readonly canUpdateDictionaryRecord!: boolean;
  readonly canDeleteDictionaryRecord!: boolean;

  activeNsi = '';
  additionalApiUrl: any[] = [];
  additionalApiUrlSecond: any[] = [];
  catalogTnvedApiUrl: any[] = [];
  createUrl = '';
  NsiTitle = '';
  onOpenAddModal = false;
  modal: modalItem[] = [];
  rows: rowsItem[] = [];
  editElement: rowsItem | undefined = {} as rowsItem;
  actionTitle = '';
  actionType = '';
  editId = '';
  NsiApi = '';
  showAll = false;
  deletedItemCode = 0;
  currentDate = '';
  complexNsi = false;
  searchResult = '';
  textDeleteModal = '';
  pageable: any = {
    pageSize: 5,
    pageNumber: 0,
  };
  totalRows = 0;
  is_product = false;
  is_grain = false;
  waitForDelete: any = false;
  endDate = '';
  isLoading = true;
  settingFilter = {};
  valuesFilter = {};

  mounted() {
    this.activeNsi = this.$route.params.mask;
    this.NsiApi = nsiList[this.activeNsi].apiUrl;
    this.createUrl = nsiList[this.activeNsi].createUrl;
    this.NsiTitle = nsiList[this.activeNsi].title;
    this.modal = nsiList[this.activeNsi].modal;
    this.additionalApiUrl = nsiList[this.activeNsi].additionalApiUrl;
    this.additionalApiUrlSecond = nsiList[this.activeNsi].additionalApiUrlSecond;
    this.catalogTnvedApiUrl = nsiList[this.activeNsi].catalogTnvedApiUrl;
    this.currentDate = moment().format('DD.MM.YYYY');

    if (this.NsiApi === '/api/nci/nsi-okpd2') {
      this.complexNsi = true;
    }

    // для справочника qualityIndicatorLimit придет событие filter для стартовой фильтрации
    if (this.activeNsi !== 'nsi-quality-indicators-limit') {
      this.getNsiList();
    }
  }

  get hasActions() {
    return (
      (this.canReadDictionaryRecord && this.isShowView) ||
      this.canUpdateDictionaryRecord ||
      this.canDeleteDictionaryRecord
    );
  }

  get headers(): HeadersItem[] {
    let list = nsiList[this.activeNsi]?.headers || [];

    if (!this.hasActions) {
      list = list.slice(1);
    }

    return list;
  }

  get canExport() {
    return ['/api/nci/tnved', '/api/nci/okpd2', '/api/nci/qualityIndicators'].includes(this.NsiApi);
  }

  get isDeleteModalShow() {
    return !!this.waitForDelete;
  }

  set isDeleteModalShow(_) {
    this.waitForDelete = null;
  }

  get isShowView() {
    if (this.NsiApi === '/api/nci/qualityIndicators') {
      return true;
    } else {
      return false;
    }
  }

  get computedRows() {
    if (!this.showAll) {
      return this.rows.filter((row) => !row.end_date || moment(row.end_date, 'DD.MM.YYYY') > moment());
    } else {
      return this.rows;
    }
  }

  showCard(elem) {
    this.$router.push({
      name: 'nsi.card.view',
      params: { mask: this.activeNsi, id: elem.id },
    });
  }

  async handleSearch(value) {
    this.searchResult = value;
    const pageNumber = 0;
    this.pageable.pageNumber = 0;
    this.getNsiList(value, pageNumber);
  }

  async handleFilter(value) {
    const pageNumber = 0;
    const settings = {
      okpd2_code: value?.okpd2?.code,
      country_code: value?.country?.code,
      quality_indicator_id: value?.quality_indicators_id?.id,
      indicator_purpose_id: value?.purpose?.id,
    };

    this.valuesFilter = { ...value };
    this.settingFilter = { ...settings };
    this.pageable.pageNumber = 0;

    this.getNsiList(this.searchResult, pageNumber, settings);
  }

  addNewItem() {
    if (this.NsiApi === '/api/nci/qualityIndicators') {
      this.$router.push({
        name: 'nsi.card.create',
        params: { mask: this.activeNsi },
      });
      return;
    } else {
      this.addAction();
    }
  }

  async cancelOKPD2(end_date: string) {
    this.isLoading = true;
    if (this.waitForDelete) {
      try {
        const url = this.createUrl || this.NsiApi;
        await this.$axios.post(`${url}/update`, {
          ...mapForm(this.waitForDelete, this.activeNsi),
          end_date,
        });
      } catch (err) {
        this.isLoading = false;
        throw err;
      }
    }
    this.isLoading = false;
    this.waitForDelete = null;
    this.getNsiList();
  }

  editItem(item) {
    if (this.NsiApi === '/api/nci/qualityIndicators') {
      this.$router.push({
        name: 'nsi.card.edit',
        params: { mask: this.activeNsi, id: item.id },
      });
      return;
    } else {
      this.onOpenAddModal = true;
      this.actionTitle = 'Создать новую версию';
      this.actionType = this.NsiApi === '/api/nci/okpd2' ? 'add' : 'edit';
      if (item.id) {
        this.editElement = this.rows.find((elem: any) => elem.id === item.id);
      }
      this.pageable.pageNumber = 0;
    }
  }

  async exportRegister() {
    this.isLoading = true;
    await showReport({
      path: `${this.NsiApi}/exportReport`,
      filter: {
        filter: this.searchResult,
        actual: !this.showAll,
        pageable: this.pageable,
      },
    });
    this.isLoading = false;
  }

  // eslint-disable-next-line max-lines-per-function
  async getNsiList(value?, pageNumber?, customFilter?) {
    this.isLoading = true;
    let config;
    this.settingFilter = customFilter ? { ...customFilter } : { ...this.settingFilter };
    if (this.NsiApi === '/api/nci/okpd2') {
      config = {
        is_product: this.is_product ? this.is_product : false,
        is_grain: this.is_grain ? this.is_grain : false,
      };
    }
    if (this.NsiApi) {
      const { content, totalElements } = await this.$store.dispatch('nsi/getList', {
        url: this.NsiApi,
        params: {
          only_with_limits: this.NsiApi === '/api/nci/qualityIndicators' ? false : undefined,
          actual: !this.showAll,
          pageable: {
            ...this.pageable,
            pageNumber: pageNumber ? pageNumber : this.pageable.pageNumber,
            sort: [
              {
                property: 'name',
                direction: 'ASC',
              },
            ],
          },
          ...this.settingFilter,
          filter: value || this.searchResult,
          ...config,
        },
      });

      this.totalRows = totalElements;
      this.rows = content;
      this.isLoading = false;
    }
  }

  showProduct() {
    this.is_product = !this.is_product;
    this.getNsiList();
  }

  showGrain() {
    this.is_grain = !this.is_grain;
    this.getNsiList();
  }

  onOptionsChange(data) {
    if (data.page !== undefined) {
      this.pageable.pageNumber = data.page + 1;
      this.pageable.pageSize = data.size;
    }
    this.getNsiList();
  }

  paintDeletedRow(item) {
    if (!item.is_actual) {
      return 'delete-item';
    }
  }

  showDeleted() {
    this.showAll = !this.showAll;
    this.pageable.pageNumber = 0;
    this.getNsiList(this.searchResult);
  }

  addAction() {
    this.onOpenAddModal = true;
    this.actionTitle = 'Добавить запись';
    this.actionType = 'add';
    this.pageable.pageNumber = 0;
  }

  closeModal() {
    this.onOpenAddModal = false;
    this.editElement = undefined;
  }

  goBack() {
    this.$router.push({ name: 'nsi' });
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';

.title {
  color: $black-color;
  font-style: normal;
  font-weight: 500;
  font-size: 24px;
  line-height: 24px;
  margin-bottom: 28px;

  @include respond-to('medium') {
    font-size: 22px;
  }

  @include respond-to('small') {
    font-size: 18px;
  }
}

.nsi-card-header {
  font-size: 14px;
  margin-bottom: 30px;
}

.total-note {
  color: $footer-color;
}

.link-btn {
  align-items: center;
  display: flex;
  color: $medium-grey-color;
  cursor: pointer;
  text-decoration-line: underline;

  img {
    margin-right: 6px;
  }
}

.elementsInput {
  color: $medium-grey-color;
}

.checkbox-block {
  align-items: center;
  display: flex;
  margin-bottom: 0;

  .label {
    margin-bottom: 0;
    margin-left: 5px;
  }
}

.checkbox {
  cursor: pointer;
  height: 16px;
  position: relative;

  [type='checkbox'] {
    position: absolute;
    opacity: 0;
    height: 100%;
    width: 100%;
    z-index: 1;
    cursor: pointer;
  }

  &__icon {
    align-items: center;
    justify-content: center;
    background: $check-bg;
    display: flex;
    height: 16px;
    width: 16px;
    border: 1px solid $input-border-color;
    border-radius: 4px;

    img {
      width: 9px;
      display: block;
      opacity: 0;
    }
  }

  [type='checkbox']:checked {
    & + .checkbox__icon {
      background: $gold-light-color;
      border-color: $gold-light-color;

      img {
        opacity: 1;
      }
    }
  }
}

.iconTable {
  width: 16px;
  height: 16px;
  margin-left: 3px;
  cursor: pointer;
}

.disabled {
  opacity: 0.8;

  .checkbox {
    cursor: text;
  }
}
</style>
