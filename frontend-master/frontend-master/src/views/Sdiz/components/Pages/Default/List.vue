<template>
  <sdiz-filter-list
    v-if="accessGrantedAuthorities(model.register_sdiz_privileges)"
    v-model="model"
    title="Реестр СДИЗ"
    :is-filters-for-elevator="false"
    :is-request-payload="true"
  >
    <template #number-filter>
      <v-col cols="12" lg="3" md="6" xl="2">
        <input-component v-model="model.sdiz_number" label="Номер" placeholder="Введите номер" />
      </v-col>
    </template>
  </sdiz-filter-list>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import PageComponent from '@/components/Forms/PageComponent.vue';
import { SdizVueModel } from '@/models/Sdiz/Data/Sdiz.vue';
import SdizFilterList from '@/views/Sdiz/components/List.vue';
import { PermissionMix } from '@/utils/mixins/permission';

@Component({
  name: 'sdiz-list',
  components: { SdizFilterList, PageComponent, InputComponent },
  mixins: [PermissionMix],
})
export default class SdizList extends Vue {
  model: SdizVueModel = new SdizVueModel();
}
</script>
