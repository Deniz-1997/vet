<template>
  <DialogComponent v-model="isModalOpen" width="800" hide-actions>
    <template #title>
      <span data-qa="role-card__title" class="title">{{ title }}</span>
    </template>

    <template #content>
      <v-row>
        <v-col cols="12">
          <ul>
            <li v-for="(item, i) in list" :key="i">{{ item }}</li>
          </ul>
        </v-col>
      </v-row>

      <v-row justify="end">
        <v-col cols="12" class="col-exclude">
          <DefaultButton title="Закрыть" :disabled="isLoading" @click="isModalOpen = false" />
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

/** Карточка просмотра роли. */
@Component({
  name: 'ImportFileCardModal',
  components: { DialogComponent, DefaultButton },
})
export default class ImportFileCardModal extends Mixins(Modal) {
  @Prop({ type: String, default: 'Детали загрузки' }) readonly title!: string;
  @Prop({ type: [String, Array], default: '' }) readonly details!: string | string[];
  @Prop({ type: Boolean, default: false }) readonly error!: boolean;

  get list() {
    return Array.isArray(this.details) ? this.details : [this.details];
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
