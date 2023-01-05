<template>
  <v-container>
    <v-row>
      <v-col cols="12">
        <div class="title">
          <span>Сведения об организации</span>
        </div>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12">
        <Input-component label="Наименование" disabled :value="infoOrganization.name" />
      </v-col>
    </v-row>
    <v-row v-if="infoOrganization.subject_type !== 'IR'">
      <v-col cols="4">
        <Input-component label="ИНН" disabled :value="infoOrganization.inn" />
      </v-col>
      <v-col v-if="infoOrganization.subject_type !== 'IP'" cols="4">
        <Input-component label="КПП" disabled :value="infoOrganization.kpp" />
      </v-col>
      <v-col v-if="infoOrganization.subject_type !== 'IF'" cols="4">
        <Input-component label="ОГРН/ОГРНИП" disabled :value="infoOrganization.ogrn" />
      </v-col>
      <v-col v-if="infoOrganization.subject_type !== 'IF'" cols="12">
        <Input-component label="Юридический адрес" disabled :value="infoOrganization.address" />
      </v-col>
    </v-row>
    <v-row v-if="infoOrganization.subject_type === 'IF'">
      <v-col cols="4">
        <Input-component label="Страна регистрации" disabled :value="infoOrganization.country" />
      </v-col>
      <v-col cols="4">
        <Input-component label="Регистрационный номер в РАФП" disabled :value="infoOrganization.nza" />
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="4">
        <Input-component label="Дата регистрации в системе" :value="dateCreated" disabled />
      </v-col>

      <v-col cols="8" class="xml_copy_panel">
        <XmlCopy title="Подписанный XML" :value="signature" />
      </v-col>
    </v-row>
  </v-container>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import XmlCopy from '@/components/XmlCopy/XmlCopy.vue';
import { formatDate } from '@/utils/date';

@Component({
    name: 'MyOrganization',
    components: {
      InputComponent,
      XmlCopy,
    },
})

export default class MyOrganization extends Vue {
  infoOrganization: any = {};
  dateCreated = '';
  signature = '';

  async created() {
    this.infoOrganization = this.userSubject;
    this.fetchSmevVerification();
  }

  get userSubject() {
    return this.$store.state.auth.user.subject;
  }

  async fetchSmevVerification() {
    try {
      const { data } = await this.$service.auth.getSmevVerification(this.infoOrganization.subject_id);

      this.dateCreated = formatDate(data.created);
      this.signature = data.smev_result_message;
    } catch (err) {
      throw err;
    }
  }
}
</script>

<style lang="scss">
  .xml_copy_panel {
    .v-expansion-panels {
      margin-top: 29px;
    }
  }
</style>
