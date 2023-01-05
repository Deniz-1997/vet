<template>
  <div>
    <v-row>
      <v-col cols="12">
        <LabelComponent label="Введите новый пароль" />
        <InputComponent v-model="new_password" type="password" />
      </v-col>
      <v-col cols="12">
        <LabelComponent label="Повторите новый пароль" />
        <InputComponent v-model="new_password_repeat" type="password" />
      </v-col>
    </v-row>

    <v-row justify="end">
      <v-col cols="12" class="col-exclude">
        <DefaultButton title="Отмена" @click="$emit('close')" />
        <DefaultButton variant="primary" title="Сохранить" :disabled="isLoading" @click="saveAction" />
      </v-col>
    </v-row>
  </div>
</template>

<script lang="ts">
import { Component, Vue, Prop } from 'vue-property-decorator';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import LabelComponent from '@/components/common/Label/Label.vue';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';

@Component({
  name: 'change-password',
  components: {
    AutocompleteComponent,
    DefaultButton,
    InputComponent,
    LabelComponent,
  },
})
export default class ChangePassword extends Vue {
  @Prop(String) readonly userId!: string;

  isLoading = false;
  passwordInfo: any = {};
  new_password = '';
  new_password_repeat = '';

  async saveAction() {
    this.isLoading = true;
    this.passwordInfo = {
      user_id: this.userId,
      new_password: this.new_password,
      new_password_repeat: this.new_password_repeat,
    };
    await this.$store.dispatch('staff/changePassword', this.passwordInfo);
    this.isLoading = false;
    this.$emit('close');
  }
}
</script>
