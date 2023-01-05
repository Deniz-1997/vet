<template>
  <v-container>
    <v-row>
      <v-col cols="12">
        <div class="title mb-4">
          <span>Системные сертификаты</span>
        </div>
        <div class="d-flex mb-3">
          <div class="settings">
            <span class="settingsSpan" @click="isModalShow.upload = true">
              <img src="/icons/export.svg" class="iconSettings reverse" />
              Загрузить
            </span>
          </div>
        </div>

        <DataTable
          :headers="filter.columns"
          :items="list"
          :items-length="total"
          :page="filter.pageable.pageNumber"
          :per-page="filter.pageable.pageSize"
          :search="filter.filter"
          is-disable-sort
          @onOptionsChange="({ page, size }) => onChangeFilter({ pageable: { pageNumber: page + 1, pageSize: size } })"
        >
          <template #[`item.actions`]="{ item }">
            <span class="d-flex align-items-center">
              <v-tooltip bottom>
                <template #activator="{ on, attrs }">
                  <span v-bind="attrs" v-on="on">
                    <img alt="" class="iconTable" src="/icons/show.svg" @click="showDetails(item)" />
                  </span>
                </template>
                <span>Просмотреть информацию</span>
              </v-tooltip>
              <v-tooltip bottom>
                <template #activator="{ on, attrs }">
                  <span v-bind="attrs" v-on="on">
                    <img alt="" class="iconTable" src="/icons/deleteBasket.svg" @click="openModalDelete(item.id)" />
                  </span>
                </template>
                <span>Удалить сертификат</span>
              </v-tooltip>
            </span>
          </template>
          <template #[`item.isRoot`]="{ item }">
            {{ item.isRoot ? 'да' : 'нет' }}
          </template>
          <template #[`item.isVerified`]="{ item }">
            {{ item.isVerified ? 'Пройдено' : 'Не пройдено' }}
          </template>
          <template #[`item.notValidBefore`]="{ item }">
            {{ date(item.notValidBefore, { outputFormat: 'DD.MM.YYYY' }) }}
          </template>
          <template #[`item.notValidAfter`]="{ item }">
            {{ date(item.notValidAfter, { outputFormat: 'DD.MM.YYYY' }) }}
          </template>
        </DataTable>
      </v-col>
    </v-row>

    <CertificateManagementModal v-model="isModalShow.upload" :type="type" @close="fetchList" />
    <CertificateCardModal v-model="isModalShow.card" :type="type" :details="details" @verify="onVerify" />
    <v-overlay :value="isLoading" absolute>
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
  </v-container>
</template>

<script lang="ts">
import { AxiosError } from 'axios';
import { Component, Vue, Watch } from 'vue-property-decorator';
import merge from 'lodash/merge';

import { date } from '@/utils/global/filters';
import { TInnerFilter, TMapperPlain } from '@/services/models/common';
import DataTable from '@/components/common/DataTable/DataTable.vue';
import SearchComponent from '@/components/common/Search/Search.vue';
import { EAction, mapAccessFlags } from '@/utils';
import { CertificateItem } from '@/services/mappers/certificate';
import CertificateManagementModal from '@/components/Administration/AdministrationCertificateManagement/CertificateManagementModal.vue';
import CertificateCardModal from '@/components/Administration/AdministrationCertificateManagement/CertificateCardModal.vue';

@Component({
  name: 'CertificateManagementPage',
  components: {
    DataTable,
    SearchComponent,
    CertificateManagementModal,
    CertificateCardModal,
  },
  computed: {
    ...mapAccessFlags({
      canReadRegister: EAction.READ_IMPORT_LOG_REGISTRY,
      canFilterRegister: EAction.FILTER_IMPORT_LOG_REGISTRY,
      canCustomizeRegister: EAction.CUSTOMIZE_IMPORT_LOG_REGISTRY,
    }),
  },
})
export default class ImportLogPage extends Vue {
  readonly canReadRegister!: boolean;
  readonly canFilterRegister!: boolean;
  readonly canCustomizeRegister!: boolean;

  type = '';
  card: TMapperPlain<CertificateItem> | null = null;
  details = null;
  isLoading = true;
  list: TMapperPlain<CertificateItem>[] = [];
  total = 0;
  selectCertificate = 0;
  filter: TInnerFilter = {
    filter: '',
    pageable: {
      pageSize: 10,
      pageNumber: 0,
      sort: [],
    },
    actual: true,
    columns: [
      {
        value: 'actions',
        text: 'Действия',
      },
      {
        value: 'isRoot',
        text: 'Корневой',
      },
      {
        value: 'isVerified',
        text: 'Статус проверки',
      },
      {
        value: 'subjectDN',
        text: 'Кому выдан',
      },
      {
        value: 'issuerDn',
        text: 'Кем выдан',
      },
      {
        value: 'notValidBefore',
        text: 'Действителен от',
        sortAs: 'not_valid_before',
      },
      {
        value: 'notValidAfter',
        text: 'Действителен до',
        sortAs: 'not_valid_after',
      },
    ],
  };
  temp = {
    id: 0,
  };
  isModalShow = {
    upload: false,
    card: false,
  };

  get service() {
    if (this.type === 'system') {
      return this.$service.certificate.system;
    }

    return this.$service.certificate;
  }

  onChangeFilter(filter: TInnerFilter) {
    this.filter = merge(this.filter, filter);
    this.fetchList();
  }

  date = date;

  showDetails(item) {
    this.details = item;
    this.isModalShow.card = true;
  }

  onVerify(data: CertificateItem) {
    this.list = this.list.map((item) => {
      if (item.id === data.id) {
        return data;
      }

      return item;
    });
  }

  openModalDelete(id: number) {
    this.selectCertificate = id;
    this.$service.notify.push('message', {
      text: '',
      params: {
        type: 'confirm-modal',
        title: 'Подтвердите действие',
        text: 'Вы действительно хотите удалить сертификат?',
        buttons: [{ type: 'decline' }, { callback: this.deleteItem }],
      },
    });
  }

  async deleteItem() {
    try {
      await this.service.delete(this.selectCertificate, false);
      this.fetchList();
    } catch (err) {
      const response = (err as unknown as AxiosError).response;
      if (response?.data.message.includes('violates foreign key constraint')) {
        this.temp.id = this.selectCertificate;
        this.$service.notify.push('message', {
          text: '',
          params: {
            type: 'confirm-modal',
            title: 'Предупреждение',
            text: 'Удаление сертификата повлияет на статус проверки других сертификатов',
            buttons: [{ type: 'decline' }, { callback: () => this.onSubmit() }],
          },
        });
      }
    }
  }

  async onSubmit() {
    await this.service.delete(this.temp.id, true);
    this.fetchList();
  }

  async fetchList() {
    // try {
    //   if (this.canReadRegister) {
    this.isLoading = true;
    const { data, filter } = await this.service.find(this.filter);
    this.list = data;
    this.total = filter?.total ?? this.list.length;
    // }
    this.isLoading = false;
    // } catch (err) {
    //   this.isLoading = false;
    //   throw err;
    // }
  }

  @Watch('$route.meta.type', { immediate: true })
  _handleChangeType(v) {
    this.type = v;
    this.fetchList();
  }
}
</script>

<style lang="scss" scoped>
.iconSettings {
  margin-right: 5px;

  &.reverse {
    transform: rotate(180deg);
  }
}

.settingsSpan {
  text-decoration: underline;
}
</style>
