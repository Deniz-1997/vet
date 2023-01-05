<template>
  <sdiz-create v-if="accessGrantedAuthorities(model.create_sdiz_privileges)" v-model="model" :is-elevator-page="false">
    <template #[`product-type-field`]="{ disabled, model }">
      <select-request-component
        v-model="model.okpd2_id"
        :is-active="!disabled"
        :disabled="disabled"
        label="Вид с/х культуры"
        :lot-type="model.lotType"
        :store-lot-type="model.storeLotType"
        placeholder="Выберите вид с/х культуры"
        type="nsi-okpd2-msh"
      />
    </template>
  </sdiz-create>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import SdizCreate from '@/views/Sdiz/components/Create.vue';
import { SdizVueModel } from '@/models/Sdiz/Data/Sdiz.vue';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import { PermissionMix } from '@/utils/mixins/permission';

@Component({
  name: 'sdiz-default-create',
  components: { SdizCreate, SelectRequestComponent },
  mixins: [PermissionMix],
})
export default class SdizDefaultCreate extends Vue {
  model: SdizVueModel = new SdizVueModel();
}
</script>
