<template>
  <div>
    <v-row>
      <v-col cols="12">
        <UiControl name="lastName" :value="form.lastName">
          <InputComponent id="lastName" v-model="form.lastName" placeholder="Введите текст" label="Фамилия" required />
        </UiControl>
      </v-col>
      <v-col cols="12">
        <UiControl name="firstName" :value="form.firstName">
          <InputComponent id="firstName" v-model="form.firstName" placeholder="Введите текст" label="Имя" required />
        </UiControl>
      </v-col>
      <v-col cols="12">
        <UiControl name="secondName" :value="form.secondName">
          <InputComponent
            id="secondName"
            v-model="form.secondName"
            placeholder="Введите текст"
            label="Отчество (при наличии)"
            :disabled="isEsia"
          />
        </UiControl>
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12">
        <UiControl name="name" :value="form.name">
          <InputComponent
            id="name"
            v-model="fullName"
            placeholder="Введите текст"
            label="Наименование ИП"
            :disabled="!isNameDifferent || isEsia"
            :required="isNameDifferent"
            @input="changeFieldName"
          />
        </UiControl>
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12">
        <span class="span-list">
          <span>
            <label class="checkbox">
              <input v-model="isNameDifferent" type="checkbox" :value="isNameDifferent" :disabled="isEsia" />
              <span class="checkbox__icon">
                <img src="/icons/checkbox.svg" alt="" />
              </span>
            </label>
          </span>
          <span class="checkbox-title"> Наименование ИП отличается от ФИО индивидуального предпринимателя</span>
        </span>
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12" md="6">
        <UiControl name="inn" :value="form.inn">
          <InputComponent
            id="inn"
            key="inn"
            v-model="form.inn"
            placeholder="Введите текст"
            mask="############"
            label="ИНН"
            :disabled="isEsia || isDisabled"
            required
          />
        </UiControl>
      </v-col>

      <v-col cols="12" md="6">
        <UiControl name="ogrn" :value="form.ogrn">
          <InputComponent
            id="ogrn"
            key="ogrn"
            v-model="form.ogrn"
            placeholder="Введите текст"
            label="ОГРНИП"
            mask="###############"
            :disabled="isEsia || isDisabled"
            required
          />
        </UiControl>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12" md="6">
        <UiControl name="phoneNumber" :value="form.phoneNumber">
          <InputComponent
            id="phone_number"
            v-model="form.phoneNumber"
            placeholder="Введите номер"
            label="Контактный номер телефона"
            type="tel"
            :disabled="isEsia"
          />
        </UiControl>
      </v-col>
      <v-col cols="12" md="6">
        <UiControl name="email" :value="form.email">
          <InputComponent
            id="email"
            v-model="form.email"
            placeholder="Введите email"
            label="Адрес электронной почты"
            type="email"
            :disabled="isEsia"
          />
        </UiControl>
      </v-col>
    </v-row>
  </div>
</template>

<script lang="ts">
import { Component, Prop, Watch, Mixins } from 'vue-property-decorator';
import AddressNewComponent from '@/components/AddressNewComponent/AddressNewComponent';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import SelectComponent from '@/components/common/inputs/SelectComponent.vue';
import Form from '@/utils/global/mixins/form';

@Component({
  name: 'individual-entrepreneur-form',
  components: {
    InputComponent,
    AddressNewComponent,
    DefaultButton,
    DialogComponent,
    SelectComponent,
  },
})
export default class IndividualEntrepreneurForm extends Mixins(Form) {
  @Prop({ type: Boolean }) readonly isEdit;
  @Prop({ type: Boolean, default: false }) readonly isLoading?: boolean;
  @Prop({ type: Boolean, default: false }) public isDisabled!: boolean;

  isNameDifferent = false;

  get rules() {
    return {
      lastName: 'required',
      firstName: 'required',
      inn: ['required', { size: 12 }],
      ogrn: ['required', { size: 15 }],
      docDate: this.form.identityType && 'required',
      email: 'email',
      name: this.isNameDifferent && 'required',
    };
  }

  get isEsia() {
    return this.form.created_by === 'ESIA';
  }

  get messages() {
    return {
      required: 'Поле обязательно для заполнения',
    };
  }

  get fullName() {
    if (!this.isNameDifferent) {
      return [this.form.lastName, this.form.firstName, this.form.secondName].join(' ').trim();
    }
    return this.form.name;
  }

  set fullName(value) {
    this.form.name = value;
  }

  get limit() {
    return this.$moment().add(1, 'd').toDate();
  }

  changeFieldName(value) {
    if (!this.isNameDifferent) this.form.name = value;
  }

  created() {
    this.isNameDifferent = !!this.form.name && this.form.name !== this.fullName;
    this.$emit('update-rules', this.rules);
  }

  @Watch('rules')
  updateRules() {
    this.$emit('update-rules', this.rules);
  }

  @Watch('form.firstName', { deep: true })
  @Watch('form.lastName', { deep: true })
  @Watch('form.secondName', { deep: true })
  @Watch('isNameDifferent', { deep: true })
  changeSecondName() {
    this.changeFieldName(this.fullName);
  }
}
</script>

<style lang="scss" scoped>
.col {
  padding-bottom: 0;
  padding-top: 0;
}

.subtitle {
  font-size: 18px;
  font-weight: 500;
  margin-bottom: 20px;
}
.col-exclude {
  display: flex;
  justify-content: flex-end;
}

.ul-radio {
  padding-top: 8px;
}

.span-list {
  display: flex;
  flex-wrap: nowrap;
}

.checkbox {
  padding-top: 4px;
}

.checkbox-title {
  padding-left: 4px;
}
</style>
