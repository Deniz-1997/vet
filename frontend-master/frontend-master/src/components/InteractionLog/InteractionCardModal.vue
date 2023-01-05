<template>
  <DialogComponent v-model="isModalOpen" width="800" hide-actions>
    <template #title>
      <span data-qa="role-card__title" class="title">{{ title }}</span>
    </template>

    <template #content>
      <v-row class="definition-list">
        <v-col cols="12" md="6">
          <dl>
            <dt><strong>Дата и время начала</strong></dt>
            <dd>{{ details.startDate | date({ outputFormat: 'DD.MM.YYYY HH:mm' }) }}</dd>

            <dt class="mt-3"><strong>Инициатор взаимодействия</strong></dt>
            <dd>{{ details.initiator }}</dd>

            <dt class="mt-3"><strong>Тип сообщения</strong></dt>
            <dd>{{ details.messageType }}</dd>

            <dt class="mt-3"><strong>Статус</strong></dt>
            <dd>{{ details.status }}</dd>

            <dt v-if="details.error" class="mt-3"><strong>Примечание</strong></dt>
            <dd v-if="details.error">{{ details.error }}</dd>
          </dl>
        </v-col>
        <v-col cols="12" md="6">
          <dl>
            <dt><strong>Дата и время завершения</strong></dt>
            <dd>{{ details.endDate | date({ outputFormat: 'DD.MM.YYYY HH:mm' }) }}</dd>

            <dt class="mt-3"><strong>Участник взаимодействия</strong></dt>
            <dd>{{ details.participant }}</dd>

            <dt class="mt-3"><strong>Операция</strong></dt>
            <dd>{{ details.requestName }}</dd>

            <dt class="mt-3"><strong>Результат</strong></dt>
            <dd>{{ details.result }}</dd>
          </dl>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12">
          <v-expansion-panels v-model="panel" multiple>
            <v-expansion-panel v-if="details.request">
              <v-expansion-panel-header> XML запроса </v-expansion-panel-header>
              <v-expansion-panel-content>
                <TextareaComponent :value="details.request" readonly :rows="5" />
                <DefaultButton
                  title="Скопировать"
                  type="button"
                  @click="(evt) => onCopy(evt.target, details.request)"
                />
              </v-expansion-panel-content>
            </v-expansion-panel>
            <v-expansion-panel v-if="details.response">
              <v-expansion-panel-header> XML ответа </v-expansion-panel-header>
              <v-expansion-panel-content>
                <TextareaComponent :value="details.response" readonly :rows="5" />
                <DefaultButton
                  title="Скопировать"
                  type="button"
                  @click="(evt) => onCopy(evt.target, details.response)"
                />
              </v-expansion-panel-content>
            </v-expansion-panel>
          </v-expansion-panels>
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
import Modal from '@/utils/global/mixins/modal';
import { TMapperPlain } from '@/services/models/common';
import { InteractionItem } from '@/services/mappers/interaction';
import { date } from '@/utils/global/filters';

/** Карточка просмотра роли. */
@Component({
  name: 'InteractionCardModal',
  components: { DialogComponent, DefaultButton, TextareaComponent },
  filters: { date },
})
export default class InteractionCardModal extends Mixins(Modal) {
  @Prop({ type: String, default: 'Просмотр записи' }) readonly title!: string;
  @Prop({ type: [String, Array], default: '' }) readonly details!: TMapperPlain<InteractionItem>;

  panel = null;

  onCopy(element: HTMLElement, content: string) {
    if ('clipboard' in navigator) {
      if ('write' in navigator.clipboard) {
        const type = 'text/plain';
        const blob = new Blob([content], { type });
        const data = [new ClipboardItem({ [type]: blob } as any)];
        (navigator.clipboard as any).write(data as any);
      } else {
        (navigator.clipboard as any).writeText(content);
      }
    } else if ('execCommand' in document) {
      document.execCommand('copy', false, content);
    }

    const button = this.getButton(element);

    if (button) {
      button.blur();
    }
  }

  getButton(element: HTMLElement): HTMLButtonElement | null {
    if (element.tagName.toLowerCase() === 'button') {
      return element as HTMLButtonElement;
    }

    return element.parentElement && this.getButton(element.parentElement);
  }
}
</script>

<style lang="scss">
.definition-list strong {
  font-weight: bolder !important;
}
</style>
