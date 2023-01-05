<template>
  <DialogComponent v-model="isModalOpen" width="800" hide-actions>
    <template #title>
      <span data-qa="role-card__title" class="title">{{ title }}</span>
    </template>

    <template #content>
      <v-row>
        <v-col cols="12">
          <dl>
            <dt><strong>Номер сертификата</strong></dt>
            <dd>{{ details.number }}</dd>

            <dt class="mt-3"><strong>Корневой</strong></dt>
            <dd>{{ details.isRoot ? 'да' : 'нет' }}</dd>

            <dt class="mt-3"><strong>Кому выдан</strong></dt>
            <dd>{{ details.subjectDN }}</dd>

            <dt class="mt-3"><strong>Кем выдан</strong></dt>
            <dd>{{ details.issuerDn }}</dd>

            <dt class="mt-3"><strong>Действителен от</strong></dt>
            <dd>{{ details.startDate | date({ outputFormat: 'DD.MM.YYYY' }) }}</dd>

            <dt class="mt-3"><strong>Действителен до</strong></dt>
            <dd>{{ details.endDate | date({ outputFormat: 'DD.MM.YYYY' }) }}</dd>
          </dl>
        </v-col>
      </v-row>

      <v-row justify="end">
        <v-col cols="12" class="col-exclude">
          <DefaultButton title="Проверить" :disabled="isLoading" :loading="isLoading" @click="verify" />
          <DefaultButton title="Закрыть" @click="isModalOpen = false" />
        </v-col>
      </v-row>
    </template>
  </DialogComponent>
</template>

<script lang="ts">
import { Component, Prop, Mixins } from 'vue-property-decorator';
import { date } from '@/utils/global/filters';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import Modal from '@/utils/global/mixins/modal';
import { TMapperPlain } from '@/services/models/common';
import { CertificateItem } from '@/services/mappers/certificate';
import { AxiosError } from 'axios';

/** Карточка просмотра роли. */
@Component({
  name: 'CertificateCardModal',
  filters: { date },
  components: { DialogComponent, DefaultButton },
})
export default class CertificateCardModal extends Mixins(Modal) {
  @Prop({ type: String, default: 'Информация о сертификате' }) readonly title!: string;
  @Prop({ type: String, default: 'system' }) readonly type!: string;
  @Prop({ type: Object, default: '' }) readonly details!: TMapperPlain<CertificateItem>;

  isLoading = false;

  get service() {
    if (this.type === 'system') {
      return this.$service.certificate.system;
    }

    return this.$service.certificate;
  }

  async verify() {
    this.isLoading = true;

    try {
      const { data } = await this.service.verify(this.details.id);

      if (data.message) {
        this.$service.notify.push('error', { text: data.message });
      } else {
        this.$service.notify.push('message', { text: 'Проверка пройдена успешно' });
      }
      this.$emit('verify', data.certificate);

      this.isLoading = false;
    } catch (err) {
      const text = (err as unknown as AxiosError)?.response?.data?.message ?? 'Ошибка проверки сертификата';
      this.$service.notify.push('error', { text });
      this.isLoading = false;
    }
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';

.input-wrapper {
  width: 100%;
  padding: 20px 16px;
  border: 1px dashed $gold-light-color;
  display: flex;
  flex-direction: column;
  align-items: center;
  transition: 0.2s all ease-out;
  cursor: pointer;

  &_dragging {
    opacity: 0.6;
    transition: 0.2s all ease-in;
  }

  &_loaded {
    border: 1px dashed $success-color;

    .input-wrapper__text {
      color: $success-color;
    }
  }

  &_error {
    border: 1px dashed $error-color;

    .input-wrapper__text {
      color: $error-color;
    }
  }

  &__text {
    color: $gold-light-color;
  }

  &__progress {
    width: 60%;
    height: 12px;
    border: 1px solid $input-border-color;
    border-radius: 6px;
    margin-top: 12px;
  }

  &__progress-fill {
    height: 10px;
    background-color: $success-color;
    border-radius: 5px;
  }
}

strong {
  font-weight: bolder;
}

.panel {
  border-radius: 0;

  &::before {
    box-shadow: none;
  }

  button {
    min-height: 0;
    padding: 8px 0;
  }
}
</style>
