<template>
  <sdiz-filter-list
    v-if="accessGrantedAuthorities(viewPrivileges)"
    v-model="model"
    title="Реестр СДИЗ"
    :is-filters-for-elevator="false"
    :is-show-additional-button="false"
    :is-request-payload="true"
  >
    <template #number-filter>
      <v-col cols="12" lg="3" md="6" xl="2">
        <input-component v-model="model.sdiz_number" label="Номер" placeholder="Введите номер" />
      </v-col>
    </template>
    <template #shiper-filter>
      <v-col cols="4">
        <autocomplete-priority-address
          v-model="model.shipper_location_id"
          item-value="id"
          label="Пункт отправления"
          placeholder="Пункт отправления"
        />
      </v-col>
    </template>
    <template #consignee-filter>
      <v-col cols="4">
        <autocomplete-priority-address
          v-model="model.consignee_location_id"
          item-value="id"
          label="Пункт назначения"
          placeholder="Пункт назначения"
        />
      </v-col>
    </template>
    <template #elevator-filter>
      <v-col cols="12" lg="4" xl="4" class="mt-10">
        <checkbox-component v-model="model.is_elevator" label="На хранении" class="float-left checkbox-v" />
      </v-col>
    </template>
  </sdiz-filter-list>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import PageComponent from '@/components/Forms/PageComponent.vue';
import SdizFilterList from '@/views/Sdiz/components/List.vue';
import { SdizOgvVueModel } from '@/models/Sdiz/Ogv/SdizOgv.vue';
import CheckboxComponent from '@/components/common/inputs/CheckboxComponent.vue';
import AutocompletePriorityAddress from '@/components/PriorityAddress/PriorityAddress.vue';
import { PermissionMix } from '@/utils/mixins/permission';
import { EAction } from '@/models/roles';

@Component({
  name: 'sdiz-ogv-list',
  components: { SdizFilterList, PageComponent, InputComponent, CheckboxComponent, AutocompletePriorityAddress },
  mixins: [PermissionMix],
})
export default class SdizOgvList extends Vue {
  model: SdizOgvVueModel = new SdizOgvVueModel();
  viewPrivileges = EAction.READ_SDIZ_REGISTER;
}
</script>
