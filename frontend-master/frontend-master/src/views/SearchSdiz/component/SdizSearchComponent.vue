<template>
  <v-row v-if="!show">
    <v-col cols="12">
      <text-component class="title d-flex align-center" variant="span">Поиск СДИЗ </text-component>
    </v-col>
    <v-col cols="12" lg="4" md="4" sm="4" xl="4">
      <input-component
        v-model="number"
        :disabled="isLoading"
        label="Номер СДИЗ"
        placeholder="Введите номер СДИЗ"
      />
    </v-col>
    <v-col cols="12" lg="4" md="4" sm="4" xl="4" class="mt-8">
      <button-component
        class="ml-7"
        size="micro"
        :disabled="isLoading || isDictionariesLoading || number === '' || number === null"
        title="Поиск"
        variant="primary"
        @click="getSdiz"
      />
    </v-col>

    <v-col cols="12">
      <data-table :headers="headers" :items="rows" :loading="isLoading" :no-data-text="noDataText" :hide-footer="true">
        <template #[`item.actions`]="{ item }">
          <img alt="" class="iconTable" src="/icons/show.svg" @click="onClickShow(item)" />
        </template>
      </data-table>
    </v-col>
    <v-overlay :value="isLoading">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
  </v-row>
</template>

<script lang="ts">
import { Component } from 'vue-property-decorator';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import ButtonComponent from '@/components/common/buttons/DefaultButton.vue';
import TextComponent from '@/components/common/Text/Text.vue';
import PrintForm from '@/views/Sdiz/components/PrintForm.vue';
import { SdizGpbVueModel } from '@/models/Sdiz/Data/SdizGpb.vue';
import DataTable from '@/components/common/DataTable/DataTable.vue';
import { mixins } from 'vue-class-component';
import { RequestMix } from '@/utils/mixins/request';
import { SdizVueModel } from '@/models/Sdiz/Data/Sdiz.vue';
import { PermissionMix } from '@/utils/mixins/permission';
import { EAction } from '@/models/roles';
import { AdditionalMix } from '@/utils/mixins/additional';

@Component({
  name: 'sdiz-search-component',
  components: { DataTable, InputComponent, ButtonComponent, TextComponent, PrintForm },
})
export default class SdizSearchComponent extends mixins(RequestMix, PermissionMix, AdditionalMix) {
  number = '';
  show = false;
  isLoading = false;
  noDataText = 'Ничего не найдено.';
  role = EAction.FILTER_SEARCH_SDIZ_REGISTRY;

  headers: object[] = [
    { text: 'Действие', value: 'actions' },
    { text: 'Номер', value: 'number', notExclude: true },
    { text: 'Дата', value: 'enter_date', notExclude: true },
    { text: 'Уполномоченное лицо', value: 'authorized_person', notExclude: true },
    { text: 'Вид с/х культуры', value: 'product_name_convert' },
    { text: 'Цель использования', value: 'target_name' },
    { text: 'Назначение', value: 'purpose_name' },
    { text: 'Операция', value: 'objects.operations.types' },
    { text: 'Тип', value: 'type_sdiz' },
  ];

  rows: any = [];
  model: any = {};
  isDictionariesLoading = true;

  async created() {
    await Promise.all([this.fetchLotsPurpose(), this.fetchLotsTarget()]);

    this.isDictionariesLoading = false;
  }

  getLotTargetName(id) {
    if (!id) return '-';
    const targets = this.getLotsTarget;
    const item = targets.find((e) => e.code == id);
    if (item?.name) {
      return item.name;
    }
    return '-';
  }

  getLotPurposeName(id) {
    if (!id) return '-';
    const targets = this.getLotsPurpose;
    const item = targets.find((e) => e.code == id);
    if (item?.name) {
      return item.name;
    }
    return '-';
  }

  onClickShow(item) {
    let routeData = this.$router.resolve({ name: item.name_route_detail, params: { id: item.id } });
    window.open(routeData.href, '_blank');
  }

  getProductTypeFromLot(model: any) {
    return model.getObjectLot().okpd2.product_name_convert;
  }

  async fetchSdiz(apiEndpoint, type_sdiz) {
    try {
      const { status, response } = await this.$store.dispatch(apiEndpoint, this.number);

      if (status) {
        response.forEach((item) => {
          const number = item.sdiz_number ?? item.sdiz_gpb_number;
          const model = item.sdiz_number ? new SdizVueModel(item) : new SdizGpbVueModel(item);

          this.rows.push({
            ...model,
            number: number,
            product_name_convert: this.getProductTypeFromLot(model),
            target_name: this.getLotTargetName(model.getObjectLot().target_id),
            purpose_name: this.getLotPurposeName(model.getObjectLot().purpose_id) || '-',
            type_sdiz: type_sdiz,
          });
        });
      }
    } catch (e) {
      this.isLoading = false;
    }
  }

  async getSdiz() {
    if (this.accessGrantedAuthorities(this.role)) {
      this.rows = [];

      this.isLoading = true;

      await this.fetchSdiz('sdiz/findByNumber', 'СДИЗ');
      await this.fetchSdiz('sdiz/findByNumberGpb', 'СДИЗ продуктов переработки');

      this.isLoading = false;

      if (!this.rows.length) {
        this.$notify({
          group: 'sdizList',
          type: 'warning',
          title: '',
          text: `СДИЗ с номером ${this.number} не найден.`,
        });
        this.noDataText = `СДИЗ с номером ${this.number} не найден.`;
      }
    } else {
      this.$notify({ group: 'sdizList', type: 'warning', title: '', text: 'Недостаточно полномочий' });
    }
  }
}
</script>
