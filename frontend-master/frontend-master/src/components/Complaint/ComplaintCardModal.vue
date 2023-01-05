<template>
  <DialogComponent v-model="isModalOpen" width="800" hide-actions>
    <template #title>
      <span data-qa="role-card__title" class="title">{{ title }} №{{ details.id }}</span>
    </template>

    <template #content>
      <v-row class="definition-list">
        <v-col cols="12" md="12">
          <InputComponent v-model="details.created" label="Дата и время получения" disabled />
        </v-col>
      </v-row>
      <v-row class="definition-list">
        <v-col cols="12" md="12">
          <InputComponent v-model="details.subject_name" label="Организация" disabled />
        </v-col>
      </v-row>
      <v-row class="definition-list">
        <v-col cols="12" md="12">
          <InputComponent v-model="details.complaint_name" label="Тип жалобы" disabled />
        </v-col>
      </v-row>
      <v-row class="definition-list">
        <v-col cols="12" md="12">
          <TextareaComponent v-model="details.message" label="Сообщение" disabled />
        </v-col>
      </v-row>
      <v-row>
        <v-col v-if="details.attachment" cols="12">
          <div class="link" @click="getFile">{{ details.attachment.name }}</div>
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
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import TextareaComponent from '@/components/common/inputs/TextAreaComponent.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import Modal from '@/utils/global/mixins/modal';
import { TMapperPlain } from '@/services/models/common';
import { ComplaintItem } from '@/services/mappers/complaint';
import { showFile } from '@/utils/file';

/** Карточка просмотра роли. */
@Component({
  name: 'ComplaintCardModal',
  components: { DialogComponent, DefaultButton, TextareaComponent, InputComponent },
})
export default class ComplaintCardModal extends Mixins(Modal) {
  @Prop({ type: String, default: 'Просмотр жалобы' }) readonly title!: string;
  @Prop({ type: Object, default: {} }) readonly details!: TMapperPlain<ComplaintItem>;

  getFile() {
    return showFile({
      method: 'get',
      path: `/api/elevator-request/file/${this.details.attachment?.id}`,
    });
  }
}
</script>

<style lang="scss">
@import '@/assets/styles/_variables';

.definition-list strong {
  font-weight: bolder !important;
}

.link {
  color: $gold-light-color !important;
  text-decoration: underline;
  cursor: pointer;
}
</style>
