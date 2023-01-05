<template>
  <div v-if="isAccess">
    <RegionalGovernmentFilters v-model="form" :mode="mode" :is-loading="isLoading" @search="onSearch" />

    <DataFields v-if="model.id" v-model="model" :detail="true" is-external-value>
      <template #goBackButton>
        <span v-show="false" />
      </template>
    </DataFields>
  </div>
</template>

<script lang="ts">
import { Component, Mixins } from 'vue-property-decorator';
import RegionalGovernmentFilters from '@/views/RegionalGovernment/components/SearchForm.vue';
import { RegionalGovernmentMix, RegionalGovernmentMode } from '@/utils/mixins/regionalGovernment';
import DataFields from '@/views/Gosmonitoring/components/Register/Implementation/DataFields.vue';
import { ImplementationVueModel } from '@/models/Gosmonitoring/Implementation.vue';
import ButtonComponent from '@/components/common/buttons/DefaultButton.vue';
import { EAction } from '@/utils';

@Component({
  name: 'ImplementationRouApk',
  components: { ButtonComponent, DataFields, RegionalGovernmentFilters },
})
export default class ImplementationRouApk extends Mixins(RegionalGovernmentMix) {
  mode = RegionalGovernmentMode.IMPLEMENTATION;
  model = new ImplementationVueModel();
  viewPrivileges = EAction.READ_PRODUCTMONITOR_ROU_APK;
}
</script>
