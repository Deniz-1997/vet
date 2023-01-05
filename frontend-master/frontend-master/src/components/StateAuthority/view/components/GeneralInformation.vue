<template>
  <div class="general">
    <v-row>
      <v-col v-if="form.subjectType !== 'IP'" cols="6" md="6">
        <InputComponent v-model="form.name" label="Наименование" disabled />
      </v-col>

      <v-col v-if="form.subjectType === 'IP'" cols="6" md="6" lg="6" xl="6">
        <InputComponent v-model="fieldName" label="ФИО" disabled />
      </v-col>
    </v-row>

    <v-row>
      <v-col v-if="form.subjectType === 'IP'" cols="12" md="12" lg="6" xl="6">
        <InputComponent v-model="form.name" label="Наименование ИП" disabled />
      </v-col>
    </v-row>

    <v-row>
      <v-col v-if="form.subjectType === 'UL'" cols="12" md="12" lg="6" xl="6">
        <InputComponent v-model="form.shortName" label="Краткое наименование" disabled />
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12" md="12" :lg="form.subjectType === 'IP' ? '3' : '2'" :xl="form.subjectType === 'IP' ? '3' : '2'">
        <InputComponent v-model="form.inn" label="ИНН" disabled />
      </v-col>
      <v-col v-if="form.subjectType === 'UL' || form.subjectType === 'IF'" cols="12" md="12" lg="2" xl="2">
        <InputComponent v-model="form.kpp" label="КПП" disabled />
      </v-col>

      <v-col
        v-if="form.subjectType !== 'IF'"
        cols="12"
        md="12"
        :lg="form.subjectType === 'IP' ? '3' : '2'"
        :xl="form.subjectType === 'IP' ? '3' : '2'"
      >
        <InputComponent v-model="form.ogrn" :label="form.subjectType === 'IP' ? 'ОГРНИП' : 'ОГРН'" disabled />
      </v-col>
    </v-row>

    <v-row>
      <v-col v-if="form.subjectType === 'UL'" cols="12">
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
import { ESubjectVerificationStatus } from '@/services/enums/subject';

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

  get fieldName() {
    return [this.form.lastName, this.form.firstName, this.form.secondName].join(' ').trim();
  }

  get subjectCountry() {
    return this.form?.address?.country?.name_full ?? '-';
  }

  get subjectOpf() {
    return this.form?.opf?.name ?? '-';
  }

  get verificationStatus() {
    return this.form.status.name;
  }

  get hasVerificationErrors() {
    const { subject_verification_status } = this.form.subject || {};
    return subject_verification_status?.code === ESubjectVerificationStatus.WRONG_DATA;
  }
}
</script>
