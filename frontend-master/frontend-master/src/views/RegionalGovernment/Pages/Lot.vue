<template>
  <div v-if="isAccess">
    <RegionalGovernmentFilters v-model="form" :mode="mode" :is-loading="isLoading" @search="onSearch" />

    <LotDetail
      v-if="model.id"
      :key="model.id"
      v-model="model"
      is-external-value
      is-for-rou-apk
      :is-show="false"
      :hide-sdiz-tab="true"
    >
      <template #[`product-type-field`]>
        <InputComponent :value="okpd2Name" label="Вид с/х культуры" disabled />
      </template>

      <template #gosmonitoring-number-field>
        <InputComponent
          v-show="model.research_numbers_government_monitoring_id"
          :value="gosmonitoringNumber"
          disabled
          label="Документ о результатах госмониторинга"
        />
      </template>

      <template #goBackButton>
        <span v-show="false" />
      </template>
    </LotDetail>
  </div>
</template>

<script lang="ts">
import { Component, Mixins } from 'vue-property-decorator';
import RegionalGovernmentFilters from '@/views/RegionalGovernment/components/SearchForm.vue';
import { RegionalGovernmentMix, RegionalGovernmentMode } from '@/utils/mixins/regionalGovernment';
import LotDetail from '@/views/Lot/components/Detail.vue';
import { LotDataVueModel } from '@/models/Lot/Data/LotData.vue';
import ButtonComponent from '@/components/common/buttons/DefaultButton.vue';
import { EAction } from '@/utils';
import InputComponent from '@/components/common/inputs/InputComponent.vue';

@Component({
  name: 'LotRouApk',
  components: { InputComponent, ButtonComponent, LotDetail, RegionalGovernmentFilters },
})
export default class LotRouApk extends Mixins(RegionalGovernmentMix) {
  mode = RegionalGovernmentMode.LOT;
  model = new LotDataVueModel();
  viewPrivileges = EAction.READ_LOT_ROU_APK;

  get okpd2Name() {
    return this.model.objects?.okpd2?.product_name_convert || '-';
  }

  get gosmonitoringNumber() {
    return this.model.objects?.laboratory_monitor?.laboratory_monitor_number || '';
  }
}
</script>
