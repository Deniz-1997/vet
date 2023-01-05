<template>
  <div>
    <sdiz-agent-form v-if="accessGrantedAuthorities(model.create_privileges)" v-model="model" :is-create="true" />
  </div>
</template>

<script lang="ts">
import { Component } from 'vue-property-decorator';
import DataTable from '@/components/common/DataTable/DataTable.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import ConfirmModalDelete from '@/views/Authorities/components/Modal/ConfirmModalDelete.vue';
import { mixins } from 'vue-class-component';
import { AdditionalMix } from '@/utils/mixins/additional';
import PageComponent from '@/components/Forms/PageComponent.vue';
import { RequestMix } from '@/utils/mixins/request';
import LabelComponent from '@/components/common/Label/Label.vue';
import SdizAgentForm from '@/views/SdizAgent/components/Form.vue';
import { AgentVueModel } from '@/models/Sdiz/Agent.vue';
import moment from 'moment';
import { PermissionMix } from '@/utils/mixins/permission';

@Component({
  name: 'SdizAgentCreate',
  components: {
    SdizAgentForm,
    LabelComponent,
    PageComponent,
    ConfirmModalDelete,
    DefaultButton,
    DataTable,
    DialogComponent,
    InputComponent,
    SelectRequestComponent,
  },
})
export default class SdizAgentCreate extends mixins(AdditionalMix, RequestMix, PermissionMix) {
  model: AgentVueModel = new AgentVueModel();

  created() {
    this.model.date = moment().format('DD.MM.YYYY');
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';
</style>
