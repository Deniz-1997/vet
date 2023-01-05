<template>
  <div v-if="isAccess">
    <RegionalGovernmentFilters v-model="form" :mode="mode" :is-loading="isLoading" @search="onSearch" />

    <SdizDetail v-if="model.id" v-model="model" is-external-value is-for-regional-government :is-show="false">
      <template #[`product-type-field`]>
        <InputComponent :value="okpd2Name" label="Вид с/х культуры" disabled />
      </template>

      <template #gosmonitoring-number-field>
        <InputComponent
          v-show="model.objects.lot.research_numbers_government_monitoring_id"
          :value="gosmonitoringNumber"
          disabled
          label="Документ о результатах госмониторинга"
        />
      </template>

      <template #goBackButton>
        <span v-show="false" />
      </template>
    </SdizDetail>
  </div>
</template>

<script lang="ts">
import { Component, Mixins } from 'vue-property-decorator';
import RegionalGovernmentFilters from '@/views/RegionalGovernment/components/SearchForm.vue';
import { RegionalGovernmentMix, RegionalGovernmentMode } from '@/utils/mixins/regionalGovernment';
import SdizDetail from '@/views/Sdiz/components/Detail.vue';
import { SdizVueModel } from '@/models/Sdiz/Data/Sdiz.vue';
import { LotDataVueModel } from '@/models/Lot/Data/LotData.vue';
import ButtonComponent from '@/components/common/buttons/DefaultButton.vue';
import { EAction } from '@/utils';
import InputComponent from '@/components/common/inputs/InputComponent.vue';

@Component({
  name: 'SdizRouApk',
  components: { InputComponent, ButtonComponent, RegionalGovernmentFilters, SdizDetail },
})
export default class SdizRouApk extends Mixins(RegionalGovernmentMix) {
  model: SdizVueModel = new SdizVueModel();
  mode = RegionalGovernmentMode.SDIZ;
  viewPrivileges = EAction.READ_SDIZ_ROU_APK;

  get okpd2Name() {
    return this.model.getObjectLot().objects?.okpd2?.product_name_convert || '-';
  }

  async onSearch() {
    try {
      this.isLoading = true;
      const { data } = await this.$axios.post(this.endpoint, this.getDataForRequest());

      if (!data.status) throw new Error();

      this.model = new SdizVueModel(data.response.sdiz);
      this.model.objects.lot = new LotDataVueModel(data.response.lot);
    } catch (_e) {
      this.$notify({
        group: 'regionalGovernment',
        type: 'warning',
        title: 'Не удалось загрузить документ с указанными данными',
      });

      this.model = new SdizVueModel();
    } finally {
      this.isLoading = false;
    }
  }

  get gosmonitoringNumber() {
    return this.model.objects.lot.objects.laboratory_monitor.laboratory_monitor_number ?? '';
  }
}
</script>
