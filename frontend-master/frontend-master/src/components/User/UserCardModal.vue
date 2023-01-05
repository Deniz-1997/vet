<template>
  <DialogComponent v-model="isModalOpen" :prompt="false" cancel-title="" confirm-title="" width="640">
    <template #title>
      <span class="title">Личные данные</span>
    </template>

    <template #content>
      <v-row>
        <v-col cols="12" md="6">
          <InputComponent :value="form.lastName" label="Фамилия" disabled />
        </v-col>
        <v-col cols="12" md="6">
          <InputComponent :value="form.firstName" label="Имя" disabled />
        </v-col>
        <v-col v-if="form.fatherName" cols="12">
          <InputComponent :value="form.fatherName" label="Отчество" disabled />
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12">
          <UiControl name="email" :value="form.email">
            <InputComponent :value="form.email" type="email" label="Электронная почта" disabled>
              <template #[`label-icon`]
                ><img src="/icons/edit.svg" class="iconTable mb-2" @click="isEdit.email = true"
              /></template>
            </InputComponent>
          </UiControl>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="6">
          <UiControl name="login" :value="form.login">
            <InputComponent :value="form.login" label="Логин" disabled>
              <template #[`label-icon`]
                ><img src="/icons/edit.svg" class="iconTable mb-2" @click="isEdit.login = true"
              /></template>
            </InputComponent>
          </UiControl>
        </v-col>
        <v-col cols="6" class="d-flex align-end mb-5">
          <DefaultButton title="Изменить пароль" @click="isEdit.password = true" />
        </v-col>
      </v-row>

      <v-row v-if="form.subjects.length">
        <v-col cols="12">
          <h4 class="title my-0">Организации</h4>
        </v-col>
        <v-col cols="12">
          <v-expansion-panels v-model="panel" multiple>
            <v-expansion-panel v-for="subject in form.subjects" :key="subject.id">
              <v-expansion-panel-header>{{ subject.name }}</v-expansion-panel-header>
              <v-expansion-panel-content>
                <ul class="mb-6">
                  <li v-for="{ id, name, code, description } in subject.roles" :key="id">
                    <b>{{ name === description ? code : `${name} (${code})` }}</b>
                    - {{ description }}.
                  </li>
                </ul>
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
        <v-progress-circular indeterminate size="64" />
      </v-overlay>

      <UserEmailChangeModal v-if="form && form.id" v-model="isEdit.email" :info="form" @submit="onSubmit" />
      <UserLoginChangeModal v-if="form && form.id" v-model="isEdit.login" :info="form" @submit="onSubmit" />
      <UserPasswordChangeModal v-if="form && form.id" v-model="isEdit.password" :user-id="form.id" />
    </template>
  </DialogComponent>
</template>

<script lang="ts">
import { Component, Mixins } from 'vue-property-decorator';
import Modal from '@/utils/global/mixins/modal';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import UserEmailChangeModal from '@/components/User/UserEmailChangeModal.vue';
import UserLoginChangeModal from '@/components/User/UserLoginChangeModal.vue';
import UserPasswordChangeModal from '@/components/User/UserPasswordChangeModal.vue';
import { UserItem } from '@/services/mappers/user';
import { TMapperPlain } from '@/services/models/common';

@Component({
  name: 'UserCardModal',
  components: {
    DialogComponent,
    InputComponent,
    DefaultButton,
    UserEmailChangeModal,
    UserLoginChangeModal,
    UserPasswordChangeModal,
  },
})
export default class UserCardModal extends Mixins(Modal) {
  panel = [];
  form: TMapperPlain<UserItem> | null = null;
  isLoading = true;
  isEdit = {
    email: false,
    login: false,
    password: false,
  };

  created() {
    this.form = {} as any;
  }

  onModalOpen() {
    this.fetch();
  }

  async fetch() {
    this.isLoading = true;
    this.form = await this.$service.user.findOne();
    this.isLoading = false;
  }

  onSubmit(data: TMapperPlain<UserItem>) {
    this.form = data;
  }
}
</script>

<style lang="scss" scoped></style>
