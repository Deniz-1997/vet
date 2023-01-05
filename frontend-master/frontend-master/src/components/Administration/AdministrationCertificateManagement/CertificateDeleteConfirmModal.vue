<template>
  <DialogComponent v-model="isModalOpen" :persistent="isLoading" hide-actions>
    <template #title>
      <span data-qa="role-card__title" class="title">{{ title }}</span>
    </template>

    <template #content>
      <UiForm
          ref="form"
          @submit="$emit('submit')"
      >
        <v-row justify="end">
          <v-col cols="12" class="col-exclude">
            <DefaultButton title="Закрыть" :disabled="isLoading" @click="isModalOpen = false" />
            <DefaultButton
                variant="primary"
                title="Удалить"
                :loading="isLoading"
                type="submit"
            />
          </v-col>
        </v-row>
      </UiForm>
    </template>
  </DialogComponent>
</template>

<script lang="ts">
import { Component, Prop, Mixins } from 'vue-property-decorator';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import Modal from '@/utils/global/mixins/modal';

@Component({
  name: 'CertificateDeleteConfirmModal',
  components: { DialogComponent, DefaultButton },
})
export default class CertificateDeleteConfirmModal extends Mixins(Modal) {
  @Prop({ type: [Number, Array] }) readonly id!: number | number[];
  @Prop({ type: String, default: 'Удаление сертификата повлияет на статус проверки других сертификатов' }) readonly title!: string;

  isLoading = false;
  error = '';

  resetForm() {
    const form = (this.$refs.form as any)?.$el as any;

    if (form) {
      form.reset();
    }
  }

  /** Обработчик на закрытие. */
  onModalClose() {
    this.isLoading = false;
    this.error = '';

    this.resetForm();
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
</style>
