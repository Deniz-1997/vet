<template>
  <div>
    <CompanyPickerModal v-model="isModalShow.companyPicker" :companies="companies" />
    <v-overlay value>
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
  </div>
</template>
<script lang="ts">
import isEmpty from 'lodash/isEmpty';
import { Component, Prop, Vue } from 'vue-property-decorator';
import { SubjectItem } from '@/services/mappers/auth';
import CompanyPickerModal from '@/views/Login/components/CompanyPickerModal.vue';

@Component({
  name: 'EsiaLoginPage',
  components: { CompanyPickerModal },
  layout: 'login',
})
export default class LoginRouter extends Vue {
  @Prop({ type: String, default: 'Авторизация ЕСИА' }) public title!: string;
  companies: ReturnType<SubjectItem['toJSON']>[] = [];
  isModalShow = {
    companyPicker: false,
  };

  async checkOrganizations() {
    const { data } = await this.$service.auth.getOrganizations();

    if (data.length > 1) {
      this.companies = data;
      this.isModalShow.companyPicker = true;
    } else {
      this.$service.auth.restoreSession();
    }
  }

  async created() {
    const params = this.$route.query;

    if (params && !isEmpty(params)) {
      await this.$service.auth.confirmEsiaLogin(params);
      await this.checkOrganizations();
    } else {
      this.$router.push('/login');
    }
  }
}
</script>
