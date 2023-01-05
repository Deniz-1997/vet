<template>
  <DialogComponent v-model="isModalOpen" width="800" hide-actions>
    <template #title>
      <span data-qa="role-card__title" class="title">{{ title }}</span>
    </template>

    <template #content>
      <v-row v-if="!isLoading" class="definition-list">
        <v-col cols="12" md="6">
          <dl>
            <dt class="mt-3"><strong>Номер декларации</strong></dt>
            <dd>{{ form.number }}</dd>

            <dt class="mt-3"><strong>Статус</strong></dt>
            <dd>{{ form.status }}</dd>

            <dt class="mt-3"><strong>Дата и время экспорта</strong></dt>
            <dd>{{ form.exportDate | date({ outputFormat: 'DD.MM.YYYY HH:mm' }) }}</dd>
          </dl>
        </v-col>
        <v-col cols="12" md="6">
          <dl>
            <dt class="mt-3"><strong>ИНН</strong></dt>
            <dd>{{ form.inn }}</dd>

            <dt class="mt-3"><strong>Тип декларации</strong></dt>
            <dd>{{ form.type }}</dd>
          </dl>
        </v-col>
      </v-row>
      <v-row v-if="!isLoading">
        <v-col cols="12">
          <v-expansion-panels v-model="panel" multiple>
            <v-expansion-panel v-for="item in form.info" :key="item.id">
              <v-expansion-panel-header>{{ item.product.name }} — {{ item.product.quantity }}</v-expansion-panel-header>
              <v-expansion-panel-content>
                <dt v-if="item.sender" class="mt-3"><strong>Отправитель</strong></dt>
                <dd v-if="item.sender">
                  {{ item.sender.name }}, ИНН: {{ item.sender.inn }}, КПП: {{ item.sender.kpp }}
                </dd>

                <dt v-if="item.recipient" class="mt-3"><strong>Получатель</strong></dt>
                <dd v-if="item.recipient">
                  {{ item.recipient.name }}, ИНН: {{ item.recipient.inn }}, КПП: {{ item.recipient.kpp }}
                </dd>

                <dt class="mt-3"><strong>Продукт</strong></dt>
                <dd>{{ item.product.name }} — {{ item.product.quantity }}</dd>

                <dt class="mt-3"><strong>Код ТНВЭД</strong></dt>
                <dd>{{ item.tnved }}</dd>
              </v-expansion-panel-content>
            </v-expansion-panel>
            <v-expansion-panel v-if="form.content">
              <v-expansion-panel-header> XML декларации </v-expansion-panel-header>
              <v-expansion-panel-content>
                <TextareaComponent :value="form.content" readonly :rows="5" />
                <DefaultButton title="Скопировать" @click="onCopy(form.content)" />
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

      <v-overlay :value="isLoading" absolute>
        <v-progress-circular indeterminate size="64"></v-progress-circular>
      </v-overlay>
    </template>
  </DialogComponent>
</template>

<script lang="ts">
import merge from 'lodash/merge';
import { Component, Prop, Mixins } from 'vue-property-decorator';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import TextareaComponent from '@/components/common/inputs/TextAreaComponent.vue';
import Modal from '@/utils/global/mixins/modal';
import { TMapperPlain } from '@/services/models/common';
import { DeclarationItem } from '@/services/mappers/declaration';
import { date } from '@/utils/global/filters';

/** Карточка просмотра роли. */
@Component({
  name: 'DeclarationCardModal',
  components: { DialogComponent, DefaultButton, TextareaComponent },
  filters: { date },
})
export default class DeclarationCardModal extends Mixins(Modal) {
  @Prop({ type: String, default: 'Просмотр записи' }) readonly title!: string;
  @Prop({ type: [String, Array], default: '' }) readonly details!: TMapperPlain<DeclarationItem>;

  panel = null;
  form: any = null;

  get isLoading() {
    return !this.form;
  }

  onCopy(content: string) {
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
  }

  async onModalOpen() {
    const { data } = await this.$service.declaration.findOne(this.details.id);
    this.form = merge({ ...this.details }, data);
  }

  onModalClose() {
    this.form = null;
  }
}
</script>

<style lang="scss">
.definition-list strong {
  font-weight: bolder !important;
}
</style>
