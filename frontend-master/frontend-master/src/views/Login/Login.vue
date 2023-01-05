<template>
  <div class="login">
    <div v-if="isDemo" class="attention">
      Данный сайт является тестовой версией ФГИС "Зерно". Зайти в неё можно через учётные записи, которые указаны
      <a href="https://specagro.ru/fgis-test" class="spanAnchor">здесь</a>.
      <br />
      С 1 июля 2022 года началось внесение во ФГИС «Зерно» информации на продуктивном сервере. Для переход на
      продуктивную версию перейдите по ссылке:
      <a href="https://zerno.mcx.gov.ru" class="spanAnchor">https://zerno.mcx.gov.ru</a>.
    </div>
    <div class="loginForm">
      <div class="contentForm">
        <div class="title">Пожалуйста, авторизуйтесь</div>
        <div v-if="errorMessage" class="error">{{ errorMessage }}</div>
        <UiForm id="login" :rules="rules" class="form" @submit="onSubmit" @validation="(v) => (isValid = v.isValid)">
          <UiControl name="login" :value="form.login">
            <label class="form__item">
              <Input v-model="form.login" label="Логин" />
            </label>
          </UiControl>
          <UiControl name="password" :value="form.password">
            <label class="form__item">
              <Input v-model="form.password" label="Пароль" type="password" />
            </label>
          </UiControl>
          <div class="buttons">
            <Button
              class="buttons__item"
              title="Забыли пароль?"
              :disabled="!isValid"
              @click="isModalShow.password = true"
            />
            <Button
              class="buttons__item"
              variant="primary"
              title="Войти"
              :disabled="!isValid || isLoading"
              type="submit"
            />
          </div>
        </UiForm>
        <div class="additional__block">
          <hr class="hr" />
          или
          <hr class="hr" />
        </div>
        <div class="additional__buttons" @click="onEsiaLogin">
          <div class="additional__item">
            <img src="/logo/logo_GU.svg" />
          </div>
        </div>
      </div>
    </div>
    <v-overlay :value="isLoading">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
    <LoginPasswordRecoveryModal v-model="isModalShow.password" />
    <CompanyPickerModal v-model="isModalShow.companyPicker" :companies="companies" />
  </div>
</template>

<script lang="ts">
import { AxiosError } from 'axios';
import { Component, Prop, Vue } from 'vue-property-decorator';
import Input from '@/components/common/inputs/InputComponent.vue';
import Button from '@/components/common/buttons/DefaultButton.vue';
import LoginPasswordRecoveryModal from '@/components/Login/LoginPasswordRecoveryModal.vue';
import CompanyPickerModal from '@/views/Login/components/CompanyPickerModal.vue';
import { SubjectItem } from '@/services/mappers/auth';

const errors = {
  'password-recovery': 'Не удалось восстановить пароль, проверьте корректность ссылки',
  'esia-confirm': 'Не удалось авторизоваться под учётной записью ЕСИА',
  'esia-start': 'Не удалось подключиться к ЕСИА',
  logpass: 'Неверный логин или пароль',
};

@Component({
  name: 'login-router',
  components: { Input, Button, LoginPasswordRecoveryModal, CompanyPickerModal },
  layout: 'login',
  metaInfo() {
    return {
      title: 'Вход',
    };
  },
  beforeRouteEnter(_to, _from, next) {
    next((vm) => {
      if (vm.$service.auth.isLoggedIn) {
        vm.$router.push('/home');
      }
    });
  },
})
export default class LoginRouter extends Vue {
  @Prop({ type: String, default: 'Вход' }) public title!: string;
  error = '';
  companies: ReturnType<SubjectItem['toJSON']>[] = [];
  isLoading = false;
  isValid = true;
  isModalShow = {
    password: false,
    companyPicker: false,
  };
  isDemo = false;

  form = {
    login: '',
    password: '',
  };

  get rules() {
    return {
      login: 'required',
      password: 'required',
    };
  }

  get errorMessage() {
    if (!this.error) {
      return '';
    }

    return errors[this.error];
  }

  async created() {
    this.error = String(this.$route.query.error) || '';
    this.isDemo = (await this.$axios.get('/api/auth/systemSite')).data === 'DEMO';
  }

  onEsiaLogin() {
    this.isLoading = true;
    try {
      this.$service.auth.startEsiaLogin();
    } catch (_err) {
      this.isLoading = false;
      this.error = 'esia-start';
    }
  }

  async checkOrganizations() {
    const { data } = await this.$service.auth.getOrganizations();

    if (data.length > 1) {
      this.companies = data;
      this.isModalShow.companyPicker = true;
    } else {
      this.$service.auth.restoreSession();
    }
    this.isLoading = false;
  }

  async onSubmit() {
    try {
      this.isLoading = true;
      await this.$service.auth.login(this.form);
      await this.checkOrganizations();
      this.isLoading = false;
    } catch (err: any) {
      this.isLoading = false;
      const { response } = err as unknown as AxiosError;

      if ([401, 403].includes(response?.status || -1)) {
        this.error = 'logpass';
      }
      throw err;
    }
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';

.additional__block {
  display: flex;
  flex-direction: row;
  padding-top: 8px;
  color: $button-disabled-color;
  width: 100%;

  .hr {
    overflow: visible;
    height: 0;
    margin-top: 12px;
    width: 142px;

    &:first-child {
      margin-right: 16px;
    }

    &:last-child {
      margin-left: 16px;
    }
  }
}

.login {
  display: flex;
  align-items: center;
  height: 100%;
  flex-wrap: wrap;
}

.login .error {
  background: rgba($error-color, 0.1) !important;
  color: $error-color;
  padding: 20px;
  text-align: center;
  border-radius: 10px;
  margin-bottom: 20px;
}

.loginForm {
  background: #ffffff;
  padding: 40px 20px;
  border-radius: 8px;
  display: flex;
  height: auto;
  align-items: center;
  justify-content: center;
  width: 420px;
  margin: 0 auto;

  .contentForm {
    width: 100%;
  }
}

.form {
  &__item {
    margin-bottom: 10px;
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
  justify-content: flex-end;

  .buttons__item {
    width: 100%;
  }
}

.additional__buttons {
  display: flex;
  margin-top: 20px;
  justify-content: flex-end;
  width: 100%;

  .additional__item {
    width: 100%;
    background-color: $white-color;
    color: $medium-grey-color;
    cursor: pointer;
    display: inline-block;
    font-size: 20px;
    border-radius: 4px;
    border: 1px solid $input-border-color;
    height: 40px;
    box-shadow: none;
    padding-top: 3px;
    display: flex;
    align-items: center;
    justify-content: center;

    img {
      display: block;
      width: 127px;
    }
  }

  :hover {
    border-color: #2589de;
  }
}

.button {
  background-color: $white-color;
  color: $medium-grey-color;
  padding: 15px 35px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 14px;
  border-radius: 4px;
  margin-right: 15px;
  border: 1px solid $input-border-color;
  width: 100%;
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

.attention {
  color: $error-color;

  text-align: center;

  font-style: normal;
  font-weight: 500;
  font-size: 17px;
  line-height: 20px;
  width: 100%;
  text-align: center;
  background: #ffffff;
  padding: 20px;
  margin: 50px 20px 0;
  border-radius: 8px;
  position: relative;

  @include respond-to('medium') {
    top: 15px;
    font-size: 16px;
  }
}

.spanAnchor {
  color: $error-color;
  text-decoration: underline;
}
</style>
