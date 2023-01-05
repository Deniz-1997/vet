<template>
  <div class="password">
    <div class="passwordForm">
      <div>
        <div class="title">Восстановление пароля</div>
        <UiForm
          id="password-recovery"
          :rules="rules"
          :messages="messages"
          class="form"
          @submit="onSubmit"
          @validation="(v) => (isValid = v.isValid)"
        >
          <UiControl name="password" :value="form.password">
            <label class="form__item">
              <Input v-model="form.password" label="Пароль" type="password" />
            </label>
          </UiControl>
          <UiControl name="confirmPassword" :value="form.confirmPassword">
            <label class="form__item">
              <Input v-model="form.confirmPassword" label="Подтвердите пароль" type="password" />
            </label>
          </UiControl>
          <div class="buttons">
            <Button
              class="buttons__item"
              variant="primary"
              title="Отправить"
              :disabled="!isValid || isLoading"
              :loading="isLoading"
              type="submit"
            />
          </div>
        </UiForm>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import Input from '@/components/common/inputs/InputComponent.vue';
import Button from '@/components/common/buttons/DefaultButton.vue';

@Component({
  name: 'password-form',
  layout: 'login',
  components: { Input, Button },
})
export default class PasswordRecovery extends Vue {
  form = {
    password: '',
    confirmPassword: '',
  };
  isValid = true;
  isLoading = false;

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

  get body() {
    return { uuid: this.$route.query.uuid as string, ...this.form };
  }

  beforeRouteEnter(to, _, next) {
    if (!to.query.uuid) {
      next('/login?error=password-recovery');
    }
  }

  async onSubmit() {
    this.isLoading = true;
    await this.$service.password.reset(this.body);
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';

.login .error {
  background: rgba($error-color, 0.1) !important;
  color: $error-color;
  padding: 20px;
  text-align: center;
  border-radius: 10px;
  margin-bottom: 20px;
}

.passwordForm {
  display: flex;
  height: calc(100vh - 200px);
  align-items: center;
  justify-content: center;
}

.form {
  &__item {
    margin-bottom: 10px;
    color: black;
  }

  &__label {
    color: $input-border-color;
    margin-bottom: 5px;
    font-size: 13px;
    line-height: 16px;
  }
}

.title {
  text-align: center;
  font-weight: 500;
  font-size: 20px;
  line-height: 24px;
  margin-bottom: 20px;
}

.input {
  &:focus {
    border-color: $gold-light-color;
  }

  &--disabled {
    background-color: $input-disable-background;
    color: $input-disabled-color;
  }

  &--small {
    width: 120px;
  }
}

.buttons {
  display: flex;
  margin-top: 20px;
  justify-content: center;
}

.button {
  background-color: $white-color;
  color: $medium-grey-color;
  padding: 15px 35px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  border-radius: 4px;
  margin-right: 15px;
  border: 1px solid $input-border-color;
  cursor: pointer;
  outline: none;

  @include respond-to('medium') {
    padding: 9px 25px;
  }

  @include respond-to('small') {
    padding: 5px 20px;
  }

  &:hover {
    box-shadow: 0 0 5px rgba($black_color, 0.5);
  }

  &--primary {
    border-color: $button-primary-background;
    background-color: $button-primary-background;
    color: $white-color;
  }
}
</style>
