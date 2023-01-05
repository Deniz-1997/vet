<template>
  <Dialog-component
    v-model="isModalOpen"
    :prompt="false"
    cancel-title=""
    confirm-title=""
    width="600"
    persistent
    controls-justify="justify-end"
    with-close-icon
    @onCancel="logOut"
  >
    <template #title>
      <span>{{ title }}</span>
    </template>

    <template #content>
      <div>
        <v-row>
          <v-col cols="12">
            <p>{{ message }}</p>

            <SelectComponent
              v-model="pickedId"
              :items="list"
              placeholder="Выберите организацию"
              item-value="id"
              item-text="name"
              data-qa="company-picker-modal__input"
            />
          </v-col>
        </v-row>

        <v-row justify="end">
          <v-col cols="12" class="col-exclude">
            <DefaultButton
              variant="primary"
              title="Подтвердить"
              :loading="isLoading"
              :disabled="isLoading || !pickedId"
              data-qa="company-picker-modal__submit"
              @click="onSubmit"
            />
          </v-col>
        </v-row>
      </div>
    </template>
  </Dialog-component>
</template>

<script lang="ts">
import { Component, Mixins, Prop } from 'vue-property-decorator';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import SelectComponent from '@/components/common/inputs/SelectComponent.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import Modal from '@/utils/global/mixins/modal';

@Component({
  name: 'CompanyPickerModal',
  components: {
    DefaultButton,
    DialogComponent,
    SelectComponent,
  },
})
export default class CompanyPickerModal extends Mixins(Modal) {
  @Prop({ type: Array, default: () => [] }) readonly companies!: [];
  @Prop({ type: Number, required: false }) readonly currentCompanyId?: number;
  isLoading = false;
  pickedId: number | null = null;

  get title() {
    return this.currentCompanyId ? 'Переключить организацию' : 'Выбор организации';
  }

  get message() {
    if (this.currentCompanyId) {
      return `Было обнаружено несколько организаций, связанных с вашим аккаунтом.\nВы можете выбрать другую организацию из выпадающего списка, чтобы переключиться на неё.`;
    }

    return `Было обнаружено несколько организаций, связанных с вашим аккаунтом.\nПожалуйста, выберите одну из них, чтобы продолжить работу.`;
  }

  get list() {
    return this.companies.filter(({ id }) => !this.currentCompanyId || id !== this.currentCompanyId);
  }

  async onSubmit() {
    if (!this.pickedId) return;
    this.isLoading = true;
    await this.$service.auth.setOrganization(this.pickedId);
    this.isModalOpen = false;
    this.isLoading = false;
  }

  async logOut() {
    if (!this.currentCompanyId) {
      this.$service.auth.logout();
    }
  }
}
</script>
