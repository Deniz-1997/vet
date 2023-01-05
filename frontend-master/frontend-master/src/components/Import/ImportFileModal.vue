<template>
  <DialogComponent v-model="isModalOpen" width="800" :persistent="isLoading" hide-actions>
    <template #title>
      <span data-qa="role-card__title" class="title">{{ title }}</span>
    </template>

    <template #content>
      <UiForm
        v-if="isModalOpen"
        ref="form"
        :rules="rules"
        :messages="messages"
        @validation="(v) => (isValid = v.isValid)"
        @submit="onSubmit"
      >
        <v-row>
          <v-col cols="12">
            <UiControl name="file" :value="form.file">
              <div
                :class="[
                  'input-wrapper',
                  isDragging && 'input-wrapper_dragging',
                  !isLoading && loaded === 100 && 'input-wrapper_loaded',
                  error && 'input-wrapper_error',
                ]"
                @drop.prevent="onDrop"
                @dragenter.prevent="isDragging = true"
                @dragleave.prevent="isDragging = false"
                @dragover.prevent
              >
                <div class="input-wrapper__text">
                  {{ text }}
                </div>
                <div v-if="isLoading || loaded === 100" class="input-wrapper__progress">
                  <div class="input-wrapper__progress-fill" :style="{ width: `${loaded}%` }" />
                </div>
                <input
                  id="file"
                  class="d-none"
                  type="file"
                  name="file"
                  :accept="accepted"
                  @change="({ target }) => onInput(target.files)"
                />
              </div>
            </UiControl>
          </v-col>
        </v-row>

        <v-row justify="end">
          <v-col cols="12" class="col-exclude">
            <DefaultButton title="Закрыть" :disabled="isLoading" @click="isModalOpen = false" />
            <DefaultButton
              variant="primary"
              title="Импортировать"
              :disabled="!isValid || isLoading || loaded === 100"
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
import { AxiosError } from 'axios';

/** Карточка просмотра роли. */
@Component({
  name: 'ImportFileModal',
  components: { DialogComponent, DefaultButton },
})
export default class ImportFileModal extends Mixins(Modal) {
  @Prop({ type: [Number, Array] }) readonly id!: number | number[];
  @Prop({ type: String, default: 'Импортировать пользователей и организации' }) readonly title!: string;
  @Prop({ type: [String, Array], default: '' }) readonly accept!: string | string[];

  isLoading = false;
  isDragging = false;
  isValid = true;
  loaded = 0;
  error = '';
  form: { file: File | null } = {
    file: null,
  };

  get rules() {
    const { maxSize, types, extensions } = this.$config.import;
    return {
      file: [
        'required',
        {
          maxFileSize: maxSize,
          fileType: [...types, ...extensions],
        },
      ],
    };
  }

  get text() {
    if (this.isLoading) {
      return `Загружается: ${this.form.file?.name}`;
    }

    if (this.loaded === 100) {
      return `Загружено: ${this.form.file?.name}`;
    }

    if (this.form.file) {
      return this.form.file?.name;
    }

    return 'Загрузите файл в формате .xlsx';
  }

  get messages() {
    return {};
  }

  get accepted() {
    if (!this.accept || !this.accept.length) {
      return this.$config.import.types;
    }

    if (typeof this.accept === 'string') {
      return this.accept;
    }

    return this.accept.join(',');
  }

  resetForm() {
    const form = (this.$refs.form as any)?.$el as any;

    if (form) {
      form.reset();
    }
  }

  /** Обработчик на закрытие. */
  onModalClose() {
    this.form.file = null;
    this.isLoading = false;
    this.error = '';
    this.isValid = true;
    this.loaded = 0;

    this.resetForm();
  }

  async onDrop(evt) {
    if (!this.isLoading) {
      this.loaded = 0;
      const file = evt.dataTransfer.files.item(0);

      if (file) {
        this.form.file = new File([await file.arrayBuffer()], file.name, {
          lastModified: file.lastModified,
          type: file.type,
        });
        this.isDragging = false;
      }
    }
  }

  async onInput(files: FileList) {
    if (!this.isLoading) {
      this.loaded = 0;
      const file = files.item(0);

      if (file) {
        this.form.file = new File([await file.arrayBuffer()], file.name, {
          lastModified: file.lastModified,
          type: file.type,
        });
      }
    }
  }

  onUploadProgress({ total, loaded }) {
    this.loaded = Math.round((loaded * 100) / total);
  }

  async onSubmit() {
    if (!this.form.file) {
      return;
    }

    this.isLoading = true;

    try {
      await this.$service.import.upload(this.form.file, this.onUploadProgress);
      this.loaded = 100;
      this.isLoading = false;

      this.resetForm();
    } catch (err) {
      this.error = (err as unknown as AxiosError)?.response?.data?.message ?? 'Ошибка загрузки файла';
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
</style>
