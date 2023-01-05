<template>
  <DialogComponent v-model="isModalOpen" :prompt="false" cancel-title="" confirm-title="" width="480">
    <template #title>
      <span class="title">Изменение пароля</span>
    </template>

    <template #content>
      <UiForm :rules="rules" :messages="messages" @submit="onSubmit" @validation="(v) => (isValid = v.isValid)">
        <v-row>
          <v-col cols="12" class="pb-0">
            <UiControl name="password" :value="form.password">
              <InputComponent v-model="form.password" label="Пароль" type="password" autocomplete="off" />
            </UiControl>
          </v-col>
          <v-col cols="12" class="pt-0">
            <UiControl name="confirmPassword" :value="form.confirmPassword">
              <InputComponent
                v-model="form.confirmPassword"
                label="Подтвердите пароль"
                type="password"
                autocomplete="off"
              />
            </UiControl>
          </v-col>
        </v-row>

        <v-row justify="end">
          <v-col cols="12" class="col-exclude">
            <DefaultButton title="Закрыть" @click="isModalOpen = false" />
            <DefaultButton title="Сохранить" variant="primary" type="submit" :disabled="!isValid || isLoading" />
          </v-col>
        </v-row>
      </UiForm>

      <v-overlay :value="isLoading" absolute>
        <v-progress-circular indeterminate size="64" />
      </v-overlay>
    </template>
  </DialogComponent>
</template>

<script lang="ts">
import { Component, Mixins, Prop } from 'vue-property-decorator';
import Modal from '@/utils/global/mixins/modal';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import { TPasswordChangeForm } from '@/services/models/password';

@Component({
  name: 'UserCardModal',
  components: { DialogComponent, InputComponent, DefaultButton },
})
export default class UserEmailChangeModal extends Mixins(Modal) {
  @Prop({ type: Number, required: true }) readonly userId!: number;

  isLoading = false;
  isValid = true;
  form: TPasswordChangeForm = { userId: this.userId, password: '', confirmPassword: '' };

  get rules() {
    return {
      password: [{ between: [8, 24], regex: '/^\\S+$/' }],
      confirmPassword: [this.form.password && 'required', 'same:password'],
    };
  }

  get messages() {
    return {
      'between.password': 'Пароль должен содержать от 8 до 24 символов',
      'regex.password': 'Пароль может содержать любые символы, кроме пробела',
      'same.confirmPassword': 'Пароли не совпадают',
    };
  }

  async onSubmit() {
    this.isLoading = true;
    await this.$service.password.change(this.form);
    return this.$service.auth.logout();
  }

  onModalClose() {
    this.form.password = '';
    this.form.confirmPassword = '';
  }
}
</script>
