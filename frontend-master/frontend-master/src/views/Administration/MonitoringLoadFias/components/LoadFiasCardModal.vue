<template>
  <DialogComponent v-model="isModalOpen" width="800" hide-actions>
    <template #title>
      <span data-qa="load-fias-card__title" class="title">{{ title }}</span>
    </template>

    <template #content>
      <v-row class="definition-list">
        <v-col cols="12" md="6">
          <dl>
            <dt><strong>Дата и время начала</strong></dt>
            <dd>{{ details.started_at }}</dd>

            <dt class="mt-3"><strong>Результат</strong></dt>
            <dd>{{ details.result ?? '-' }}</dd>
          </dl>
        </v-col>

        <v-col cols="12" md="6">
          <dl>
            <dt><strong>Дата и время завершения</strong></dt>
            <dd>{{ details.finished_at }}</dd>
          </dl>
        </v-col>

        <v-col cols="12">
          <dl>
            <dt><strong>Ссылка на файл</strong></dt>
            <dd>{{ details.zip_file_name ?? '-' }}</dd>
          </dl>
        </v-col>

        <v-col cols="12">
          <dl>
            <dt><strong>Загруженный файл</strong></dt>
            <dd>{{ details.file_name ?? '-' }}</dd>

            <dt v-if="details.error" class="mt-6"><strong>Текст ошибки</strong></dt>
            <dd>
              <pre class="error-text">{{ details.error }}</pre>
            </dd>
          </dl>
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
import Modal from '@/utils/global/mixins/modal';

//TODO вывод данных о файле

/** Карточка просмотра деталей загрузки ФИАС */
@Component({
  name: 'load-fias-card-modal',
  components: { DialogComponent, DefaultButton },
})
export default class LoadFiasCardModal extends Mixins(Modal) {
  @Prop({ type: String, default: 'Детали загрузки' }) readonly title!: string;
  @Prop({ type: Object, default: () => ({}) }) readonly details!: any; //TMapperPlain<InteractionItem>; //TODO типизация
}
</script>

<style lang="scss" scoped>
.definition-list strong {
  font-weight: bolder !important;
}

.error-text {
  white-space: pre-wrap;
  max-height: 250px;
  overflow: auto;
}
</style>
