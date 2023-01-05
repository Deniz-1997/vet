<template>
  <DialogComponent
    v-model="isModalOpen"
    :prompt="false"
    cancel-title=""
    confirm-title=""
    class="approval-request-log__card"
    width="640"
  >
    <template #title>
      <span class="title">Запись журнала согласований № {{ info.recordNumber }}</span>
    </template>
    <template #content>
      <v-row>
        <v-col cols="12">
          <TextareaComponent :value="info.requestType" label="Тип заявления" disabled :rows="3" />
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12" md="6">
          <InputComponent :value="info.username" label="Пользователь" disabled />
        </v-col>
        <v-col cols="12" md="6">
          <InputComponent :value="info.subjectName" label="Организация" disabled />
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12" md="6">
          <InputComponent
            :value="info.dispatchDate && $moment(info.dispatchDate).format('DD.MM.YYYY')"
            label="Начало согласования"
            disabled
          />
        </v-col>
        <v-col cols="12" md="6">
          <InputComponent :value="info.assignee" label="Ответственный" disabled />
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12" md="6">
          <InputComponent :value="info.stage" label="Этап согласования" disabled />
        </v-col>
        <v-col cols="12" md="6">
          <InputComponent :value="info.status" label="Статус" disabled />
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12">
          <TextareaComponent :value="info.division" label="Подразделение" disabled :rows="1" />
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12" md="6">
          <InputComponent
            :value="info.approvalDate && $moment(info.approvalDate).format('DD.MM.YYYY')"
            label="Дата принятия решения"
            disabled
          />
        </v-col>
        <v-col cols="12" md="6">
          <InputComponent
            :value="info.expectedDate && $moment(info.expectedDate).format('DD.MM.YYYY')"
            label="Плановая дата принятия решения"
            disabled
          />
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12">
          <TextareaComponent label="Замечания" :value="info.notes" disabled :rows="3" />
        </v-col>
      </v-row>

      <v-row justify="end">
        <v-col cols="12" class="col-exclude">
          <DefaultButton title="Закрыть" @click="isModalOpen = false" />
        </v-col>
      </v-row>
    </template>
  </DialogComponent>
</template>

<script lang="ts">
import { Component, Prop, Mixins } from 'vue-property-decorator';
import Modal from '@/utils/global/mixins/modal';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import TextareaComponent from '@/components/common/inputs/TextAreaComponent.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import { TMapperPlain } from '@/services/models/common';
import { ApprovalRequestLogItem } from '@/services/mappers/approvalRequestLog';

@Component({
  name: 'ApprovalRequestLogCardModal',
  components: {
    DialogComponent,
    InputComponent,
    TextareaComponent,
    DefaultButton,
  },
})
export default class ApprovalRequestLogCardModal extends Mixins(Modal) {
  @Prop({ type: Object, default: () => ({}) }) readonly info!: TMapperPlain<ApprovalRequestLogItem>;
}
</script>

<style lang="scss" scoped>
.approval-request-log__card {
  .v-input.v-input--is-disabled {
    pointer-events: initial !important;
  }
}
</style>
