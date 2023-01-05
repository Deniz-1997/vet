<template>
  <div>
    <UiForm
      id="staff-card"
      :rules="rules"
      :messages="messages"
      @submit="saveUser"
      @validate="(v) => (isValid = v.isValid)"
    >
      <v-row>
        <v-col cols="12">
          <UiControl name="last_name" :value="form.last_name">
            <InputComponent id="lastName" v-model="form.last_name" label="Фамилия *" :disabled="isShow" />
          </UiControl>
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12">
          <UiControl name="first_name" :value="form.first_name">
            <InputComponent id="first_name" v-model="form.first_name" label="Имя *" :disabled="isShow" />
          </UiControl>
        </v-col>
        <v-col cols="12">
          <InputComponent
            id="secondName"
            v-model="form.second_name"
            label="Отчество (при наличии)"
            :disabled="isShow"
          />
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12" md="6">
          <UiControl name="snils" :value="form.snils">
            <InputComponent
              id="snils"
              v-model="form.snils"
              :maxlength="15"
              label="СНИЛС"
              :disabled="isShow"
              mask="###-###-### ##"
            />
          </UiControl>
        </v-col>
        <v-col cols="12" md="6">
          <UiControl name="email" :value="form.email">
            <InputComponent
              id="mail"
              v-model="form.email"
              label="Электронная почта *"
              :disabled="isShow"
              :rules="rulesEmail"
            />
          </UiControl>
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12" md="6">
          <UiControl name="login" :value="form.login">
            <InputComponent
              id="login"
              v-model="form.login"
              label="Логин *"
              :disabled="isShow || isEdit"
              autocomplete="new-login"
            />
          </UiControl>
        </v-col>
        <v-col v-if="isEdit" cols="12" md="6" class="buttonChangePassword mb-5">
          <DefaultButton variant="primary" title="Изменить пароль" @click="openModalPassword" />
        </v-col>
      </v-row>
      <v-row v-if="!isEdit && !isShow">
        <v-col cols="12" md="6">
          <UiControl name="password" :value="password">
            <InputComponent
              id="password"
              v-model="password"
              type="password"
              label="Пароль *"
              autocomplete="new-password"
            />
          </UiControl>
        </v-col>
        <v-col cols="12" md="6">
          <UiControl name="confirmPassword" :value="confirmPassword">
            <InputComponent
              id="confirm_password"
              v-model="confirmPassword"
              type="password"
              label="Подтвердите пароль *"
            />
          </UiControl>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12">
          <div class="inputContainer" name="subjectContainer">
            <OrganizationPicker
              v-model="form.subject"
              name="user_subjects"
              label="Организация"
              multi
              :readonly="isShow"
              id-element="subject"
            />
          </div>
        </v-col>
      </v-row>
      <v-row>
        <v-col v-for="subject in form.subject" :key="subject.subject_id" cols="12">
          <div class="inputContainer" name="roleContainer">
            <AutocompleteComponent
              v-model="subject.role"
              return-object
              :items="options.roles"
              item-value="role_id"
              item-text="description"
              :is-disabled="isShow"
              chips
              is-multiple
              hide-disabled-items
              :deletable-chips="!isShow"
              name="role"
            >
              <template #label>
                <span class="d-flex align-center">
                  <label-component
                    :label="
                      'Роли (организация: ' +
                      ('subject_data' in subject
                        ? subject.subject_data.short_name || subject.subject_data.name
                        : subject.short_name || subject.name) +
                      ')'
                    "
                  />
                  <v-tooltip bottom>
                    <template #activator="{ on, attrs }">
                      <span v-bind="attrs" v-on="on">
                        <v-icon
                          class="ml-1 mb-2"
                          small
                          @click="
                            showRolesModal(
                              subject.role,
                              'Роли (организация: ' +
                                ('subject_data' in subject
                                  ? subject.subject_data.short_name || subject.subject_data.name
                                  : subject.short_name || subject.name) +
                                ')'
                            )
                          "
                          >mdi-progress-question</v-icon
                        >
                      </span>
                    </template>
                    <span>Просмотреть информацию о полномочиях</span>
                  </v-tooltip>
                </span>
              </template>
            </AutocompleteComponent>
          </div>
        </v-col>
      </v-row>
      <v-row v-if="isEdit && form.status.code !== '3'" justify="start">
        <v-col cols="12" md="6">
          <DefaultButton
            v-if="form.status.code === '1' && canDeactivated"
            title="Заблокировать"
            @click="deactivation"
          />
          <DefaultButton v-else-if="canActivated" title="Разблокировать" @click="activation" />
        </v-col>
        <v-col cols="12" md="6">
          <DefaultButton v-if="canDelete" title="Удалить" :disabled="pending" :loading="pending" @click="deleteUser" />
        </v-col>
      </v-row>

      <v-row justify="end">
        <v-col cols="12" class="col-exclude">
          <DefaultButton title="Закрыть" @click="$emit('close')" />
          <DefaultButton
            v-if="!isShow"
            variant="primary"
            type="submit"
            title="Сохранить"
            :disabled="!isValid || isLoading"
          />
        </v-col>
      </v-row>
    </UiForm>

    <UserPasswordChangeModal v-if="form.user_id" v-model="onOpenModalPassword" :user-id="form.user_id" />
    <RoleCardModal :id="roleIds" v-model="isModalShow.roles" :title="roleCardTitle" />
  </div>
</template>

<script lang="ts">
/* eslint-disable no-useless-escape */
import cloneDeep from 'lodash/cloneDeep';
import sortBy from 'lodash/sortBy';
import { Component, Prop, Vue } from 'vue-property-decorator';
import RadioGroupComponent from '@/components/common/inputs/RadioGroupComponent.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import SelectComponent from '@/components/common/inputs/SelectComponent.vue';
import LabelComponent from '@/components/common/Label/Label.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import EditableTable from '@/components/common/Table/index.vue';
import Datepicker from '@/components/common/Datepicker/Datepicker.vue';
import { emailMask } from '@/components/common/inputs/mask/email';
import UserPasswordChangeModal from '@/components/User/UserPasswordChangeModal.vue';
import PasswordForm from '@/views/Login/PasswordRecovery.vue';
import RoleCardModal from '@/components/Administration/Roles/RoleCardModal.vue';
import OrganizationPicker from '@/components/OrganizationPicker.vue';
import { mapAccessFlags, EAction } from '@/utils';
import { createdBy } from '@/services/enums/createdBy';

type Props = {
  card: any;
  isShow: boolean;
};

@Component({
  name: 'StaffCard',
  components: {
    PasswordForm,
    UserPasswordChangeModal,
    RadioGroupComponent,
    InputComponent,
    DefaultButton,
    SelectComponent,
    LabelComponent,
    DialogComponent,
    AutocompleteComponent,
    OrganizationPicker,
    EditableTable,
    RoleCardModal,
    Datepicker,
  },
  computed: {
    ...mapAccessFlags({
      canDeactivated: EAction.DEACTIVATE_USER_ACCOUNT,
      canActivated: EAction.ACTIVATE_USER_ACCOUNT,
      canDelete: EAction.DELETE_USER_ACCOUNT,
    }),
  },
})
export default class StaffCard extends Vue {
  @Prop({ type: Object, default: () => ({}) }) readonly card: Props['card'] | undefined;
  @Prop({ type: [Boolean], default: false }) readonly isShow: Props['isShow'] | undefined;
  @Prop({ type: [Boolean], default: false }) readonly isLoading?: boolean;
  @Prop({ type: Object }) readonly options!: { roles: any[]; organizations: any[] };
  readonly canDeactivated!: boolean;
  readonly canActivated!: boolean;
  readonly canDelete!: boolean;

  pending = false;
  isModalShow = {
    roles: false,
  };
  roleCardTitle = '';
  roleIds: number[] = [];
  form: any = {
    subject: [],
    login: '',
    esia_id: null,
  };
  password = '';
  confirmPassword = '';
  isValid = true;
  subjectList: any = [];
  isShowModal = false;
  isEdit = false;
  isShowcase = false;
  showCard = false;
  mask = emailMask;
  maskEmail =
    /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,4}\.[0-9]{1,4}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  rulesEmail = [
    (email) => !email || this.maskEmail.test(String(email).toLowerCase()) || 'Должен соблюдаться формат: x@o.t',
  ];
  rolesList: any = [];
  onOpenModalPassword = false;
  initSubjects: any;

  get rules() {
    return {
      login: ['required', { between: [4, 20], regex: '/^[a-zA-Z][\\w\\d-_\\.]+[^\\.]$/' }],
      password: [!this.isShow && !this.isEdit && 'required', { between: [8, 24], regex: '/^\\S+$/' }],
      confirmPassword: [!this.isShow && !this.isEdit && this.password && 'required', 'same:password'],
      last_name: 'required',
      first_name: 'required',
      email: [!this.form.esia_id && !this.isCreatedESIA && 'required'],
    };
  }

  get messages() {
    return {
      'between.password': 'Пароль должен содержать от 8 до 24 символов',
      'between.login': 'Логин должен содержать от 4 до 20 символов',
      'regex.password': 'Пароль может содержать любые символы, кроме пробела',
      'regex.login':
        'Логин может содержать только латинские буквы, цифры, символы тире ( - ), подчеркивания (_) и точки (.), начинаться с буквы и не должен заканчиваться точкой',
      'same.confirmPassword': 'Пароли не совпадают',
    };
  }

  get isCreatedESIA() {
    return this.form.created_by === createdBy.ESIA;
  }

  mounted() {
    if (this.card) {
      this.isEdit = !this.isShow;
      this.getCardInfoById();
    }
  }

  showRolesModal(roles, title) {
    this.roleCardTitle = title;
    this.roleIds = roles.map(({ role_id }) => role_id);
    this.isModalShow.roles = true;
  }

  async getCardInfoById() {
    this.form = cloneDeep(this.card);
    this.form.subject = this.form.subject.map(({ role, subject }) => ({
      ...subject,
      role: sortBy(
        role.map(({ automatic, ...elem }) => ({
          ...elem,
          disabled: automatic,
        })),
        ['disabled']
      ),
    }));
    this.initSubjects = cloneDeep(this.form.subject);
  }

  async saveUser() {
    if (this.isValid && this.password) {
      this.form.password = this.password;
    }

    if (!this.form.subject.length) {
      this.form.subject = [];
    }

    this.$emit('saveUser', this.form, this.initSubjects);
  }

  openModalPassword() {
    this.onOpenModalPassword = true;
  }

  closeModal() {
    this.onOpenModalPassword = false;
  }

  async deactivation() {
    await this.$store.dispatch('staff/deactivation', {
      id: this.form.user_id,
    });
    this.$emit('activeUser');
  }

  async activation() {
    await this.$store.dispatch('staff/activation', {
      id: this.form.user_id,
    });
    this.$emit('activeUser');
  }

  async deleteUser() {
    this.pending = true;

    try {
      const { data } = await this.$axios.post('/api/security/user/delete', {
        id: this.form.user_id,
      });
      this.$emit('close', data);
      this.pending = false;
    } catch (_) {
      this.pending = false;
    }
  }
}
</script>

<style lang="scss" scoped>
.name-orgnz {
  display: flex;
  flex-wrap: nowrap;
}

.flex-end {
  display: flex;
  margin-top: 10px;
  justify-content: flex-end;
}

.settingsSpan {
  padding-left: 16px;
  cursor: pointer;
}

.buttonChangePassword {
  display: flex;
  align-items: flex-end;
}
</style>
