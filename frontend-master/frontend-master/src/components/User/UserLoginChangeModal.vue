<template>
  <DialogComponent v-model="isModalOpen" :prompt="false" cancel-title="" confirm-title="" width="480">
    <template #title>
      <span class="title">Редактирование логина</span>
    </template>

    <template #content>
      <UiForm :rules="rules" @submit="onSubmit" @validation="(v) => (isValid = v.isValid)">
        <v-row>
          <v-col cols="12">
            <UiControl name="login" :value="form.login">
              <InputComponent v-model="form.login" type="text" label="Логин" />
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
import { TMapperPlain } from '@/services/models/common';
import { UserItem } from '@/services/mappers/user';

@Component({
  name: 'UserCardModal',
  components: { DialogComponent, InputComponent, DefaultButton },
})
export default class UserEmailChangeModal extends Mixins(Modal) {
  @Prop({ type: Object, required: true }) readonly info!: TMapperPlain<UserItem>;

  isLoading = false;
  isValid = true;
  form = { login: this.info.login };

  get rules() {
    return {
      login: 'required',
    };
  }

  async onSubmit() {
    try {
      this.isLoading = true;
      const data = { ...this.info, login: this.form.login };
      await this.$service.user.update(data);
      this.isLoading = false;
      this.isModalOpen = false;
      this.$emit('submit', data);
    } catch (_err) {
      this.isLoading = false;
    }
  }
}
</script>
