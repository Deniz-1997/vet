<template>
  <Dialog-component
    v-model="isModalOpen"
    :prompt="false"
    cancel-title=""
    confirm-title=""
    width="420"
    controls-justify="justify-end"
    hide-actions
  >
    <template #title>
      <span>Забыли пароль?</span>
    </template>

    <template #content>
      <template v-if="isSent">
        <v-row data-qa="password-recovery-modal__alert">
          <v-col cols="12">
            <p class="mb-0">
              Письмо со ссылкой для восстановления пароля отправлено на почту<span v-if="!email"
                >, привязанную к аккаунту.</span
              >
              <a v-else :href="`mailto:${email}`">{{ email }}</a>
              <br /><br />
              Если письмо не пришло в течение 15 минут, проверьте папку "Спам" или повторите попытку.
            </p>
          </v-col>
        </v-row>

        <v-row data-qa="password-recovery-modal__close" justify="end">
          <v-col cols="12" class="col-exclude">
            <DefaultButton title="Закрыть" @click="isModalOpen = false" />
          </v-col>
        </v-row>
      </template>
      <template v-else-if="error">
        <v-row data-qa="password-recovery-modal__alert">
          <v-col cols="12">
            <p class="mb-0">{{ error }}</p>
          </v-col>
        </v-row>

        <v-row data-qa="password-recovery-modal__close" justify="end">
          <v-col cols="12" class="col-exclude">
            <DefaultButton title="Закрыть" @click="isModalOpen = false" />
          </v-col>
        </v-row>
      </template>
      <UiForm
        v-else
        :rules="rules"
        data-qa="password-recovery-modal__form"
        @validation="(v) => (isValid = v.isValid)"
        @submit="onSubmit"
      >
        <v-row data-qa="password-recovery-modal__alert">
          <v-col cols="12">
            <p class="mb-0">
              Для восстановления пароля укажите пользовательский логин.<br /><br />
              На почту, привязанную к аккаунту, будет отправлено письмо с инструкциями по восстановлению.
            </p>
          </v-col>
        </v-row>

        <v-row data-qa="password-recovery-modal__input">
          <v-col cols="12">
            <UiControl name="login" :value="form.login">
              <InputComponent v-model="form.login" />
            </UiControl>
          </v-col>
        </v-row>

        <v-row data-qa="password-recovery-modal__submit" justify="end">
          <v-col cols="12" class="col-exclude">
            <DefaultButton title="Закрыть" @click="isModalOpen = false" />
            <DefaultButton variant="primary" title="Подтвердить" type="submit" :disabled="!isValid || isLoading" />
          </v-col>
        </v-row>
      </UiForm>
    </template>
  </Dialog-component>
</template>

<script lang="ts">
import { Component, Mixins } from 'vue-property-decorator';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import Modal from '@/utils/global/mixins/modal';
import { AxiosError } from 'axios';

@Component({
  name: 'LoginPasswordRecoveryModal',
  components: {
    DefaultButton,
    DialogComponent,
    InputComponent,
  },
})
export default class LoginPasswordRecoveryModal extends Mixins(Modal) {
  form = { login: '' };
  email = '';
  isValid = true;
  isLoading = false;
  isSent = false;
  error = '';

  get rules() {
    return {
      login: 'required',
    };
  }

  async onSubmit() {
    this.isLoading = true;

    try {
      const { data = {} } = await this.$service.password.applyReset(this.form.login);
      this.isLoading = false;
      this.isSent = true;
      this.email = data.email || '';
    } catch (err) {
      this.error = (err as unknown as AxiosError)?.response?.data?.message || '';
      this.isSent = !this.error;
      this.isLoading = false;
    }
  }

  async onModalClose() {
    this.form.login = '';
    this.email = '';
    this.isSent = false;
    this.isValid = true;
    this.isLoading = false;
  }
}
</script>
