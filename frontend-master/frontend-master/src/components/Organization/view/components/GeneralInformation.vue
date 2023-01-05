<template>
  <div class="general">
    <v-row>
      <v-col v-if="!subjectType.IP" cols="6" md="6">
        <InputComponent v-model="form.name" label="Наименование" disabled />
      </v-col>

      <v-col v-if="subjectType.IP" cols="6" md="6" lg="6" xl="6">
        <InputComponent v-model="fieldName" label="ФИО" disabled />
      </v-col>
    </v-row>

    <v-row v-if="subjectType.IP && isNameDifferent">
      <v-col cols="12">
        <span class="span-list">
          <span>
            <label class="checkbox">
              <input v-model="isNameDifferent" type="checkbox" disabled />
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
      <v-col v-if="subjectType.IP" cols="12" md="12" lg="6" xl="6">
        <InputComponent v-model="form.name" label="Наименование ИП" disabled />
      </v-col>
    </v-row>

    <v-row>
      <v-col v-if="subjectType.UL" cols="12" md="12" lg="6" xl="6">
        <InputComponent v-model="form.shortName" label="Краткое наименование" disabled />
      </v-col>
    </v-row>

    <v-row v-if="subjectType.IF">
      <v-col v-if="form.country" cols="12" md="12" :lg="3" :xl="3">
        <InputComponent v-model="form.country.name_full" label="Страна регистрации" disabled />
      </v-col>

      <v-col cols="12" md="12" :lg="3" :xl="3">
        <InputComponent v-model="form.nza" label="Регистрационный номер в РАФП" disabled />
      </v-col>
    </v-row>

    <v-row>
      <v-col v-if="!subjectType.IR" cols="12" md="12" :lg="subjectType.IP ? '3' : '2'" :xl="subjectType.IP ? '3' : '2'">
        <InputComponent v-model="form.inn" label="ИНН" disabled />
      </v-col>
      <v-col v-if="subjectType.UL || subjectType.IF" cols="12" md="12" lg="2" xl="2">
        <InputComponent v-model="form.kpp" label="КПП" disabled />
      </v-col>

      <v-col
        v-if="!subjectType.IR && !subjectType.IF"
        cols="12"
        md="12"
        :lg="subjectType.IP ? '3' : '2'"
        :xl="subjectType.IP ? '3' : '2'"
      >
        <InputComponent v-model="form.ogrn" :label="subjectType.IP ? 'ОГРНИП' : 'ОГРН'" disabled />
      </v-col>
    </v-row>

    <v-row>
      <v-col v-if="subjectType.UL" cols="12">
        <InputComponent :value="subjectOpf" label="Организационно-правовая форма" disabled />
      </v-col>
    </v-row>

    <v-row>
      <v-col v-if="form.phoneNumber" cols="12" md="6">
        <InputComponent
          id="phone_number"
          v-model="form.phoneNumber"
          label="Контактный номер телефона"
          type="tel"
          disabled
        />
      </v-col>
      <v-col v-if="form.email" cols="12" md="6">
        <InputComponent id="email" v-model="form.email" label="Адрес электронной почты" type="email" disabled />
      </v-col>
    </v-row>
  </div>
</template>

<script lang="ts">
import { Component, Vue, Prop } from 'vue-property-decorator';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import { ESubjectType, ESubjectVerificationStatus } from '@/services/enums/subject';

/**ToDo: У форм прописать полную типизацию */
type Props = {
  form: any;
};

@Component({
  name: 'card-general-information',
  components: { InputComponent },
})
export default class CardGeneralInformation extends Vue {
  @Prop({ type: Object, default: () => ({}) }) readonly form!: Props['form'];

  get subjectType() {
    return {
      [ESubjectType.IP]: this.form.subjectType === ESubjectType.IP,
      [ESubjectType.UL]: this.form.subjectType === ESubjectType.UL,
      [ESubjectType.IR]: this.form.subjectType === ESubjectType.IR,
      [ESubjectType.IF]: this.form.subjectType === ESubjectType.IF,
    };
  }

  get fieldName() {
    return [this.form.lastName, this.form.firstName, this.form.secondName].join(' ').trim();
  }

  get isNameDifferent() {
    return this.fieldName !== this.form.name;
  }

  get subjectCountry() {
    return this.form?.address?.country?.name_full ?? '-';
  }

  get subjectOpf() {
    return this.form?.opf?.name ?? '-';
  }

  get verificationStatus() {
    return this.form.status?.name;
  }

  get hasVerificationErrors() {
    const { subject_verification_status } = this.form.subject || {};
    return subject_verification_status?.code === ESubjectVerificationStatus.WRONG_DATA;
  }
}
</script>

<style scoped>
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
