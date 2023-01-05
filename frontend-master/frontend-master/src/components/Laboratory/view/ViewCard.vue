<template>
  <OrganizationViewCard :form="form" :loading="isLoading" :tabs="tabs">
    <template #actions>
      <v-col cols="7" class="justify--end">
        <DefaultButton
          v-if="canExcludeLaboratory"
          prepend-icon="mdi-minus-circle-outline"
          title="Исключить из реестра"
          @click="isShowActionModal = true"
        />
        <DefaultButton
          v-if="canEditLaboratory"
          type="button"
          class="ml-2"
          size="micro"
          prepend-icon="mdi-pencil"
          title="Редактировать"
          variant="primary"
          @click="$router.push(`/laboratories/edit/${form.subjectId}`)"
        />
        <DefaultButton title="Закрыть" @click="$router.go(-1)" />
      </v-col>
    </template>

    <ExcludeLaboratories
      :id="form.id"
      v-model="isShowActionModal"
      :name="form.name"
      @close="isShowActionModal = false"
      @excludeAction="isShowActionModal = false"
    />
  </OrganizationViewCard>
</template>

<script lang="ts">
import { Component, Vue, Prop } from 'vue-property-decorator';
import { ESubjectType, ESubjectVerificationStatus } from '@/services/enums/subject';
import { EAction, mapAccessFlags } from '@/utils';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import ExcludeLaboratories from '@/views/Laboratories/components/ExcludeLaboratories.vue';
import GeneralInformation from './components/GeneralInformation.vue';
import CertificateTable from './components/Certificates.vue';
import LocationsTable from './components/Locations.vue';
import AddressBlock from './components/Address.vue';
import OrganizationViewCard from '@/components/OrganizationViewCard/OrganizationViewCard.vue';
import RegisterBlock from '@/components/Subjects/view/components/Register.vue';

@Component({
  name: 'LaboratoryViewCard',
  components: {
    DefaultButton,
    GeneralInformation,
    AddressBlock,
    ExcludeLaboratories,
    CertificateTable,
    LocationsTable,
    OrganizationViewCard,
  },
  computed: {
    ...mapAccessFlags({
      canExcludeLaboratory: EAction.DELETE_LABORATORY,
      canEditLaboratory: EAction.UPDATE_LABORATORY,
    }),
  },
})
export default class ViewCard extends Vue {
  @Prop({ type: Number, default: null }) readonly id!: number;
  @Prop({ type: Boolean, default: false }) readonly withRegisters!: boolean;

  static tabs = [
    {
      value: 'certificate',
      name: 'Аттестаты аккредитации',
      class: 'certificate',
      component: CertificateTable,
      mapper: ({ certificates }) => certificates,
    },
    {
      value: 'locations',
      name: 'Адреса осуществления деятельности',
      class: 'locations',
      component: LocationsTable,
      mapper: ({ locations }) => locations,
    },
  ];

  readonly canExcludeLaboratory!: boolean;
  readonly canEditLaboratory!: boolean;

  activeId: number | null = null;
  form: any = {};
  divisions: any = [];
  isNameDifferent = false;
  opfList: any[] = [];
  isLoading = false;
  isShowActionModal = false;
  isShowActionModalReject = false;
  tabs = [
    {
      value: 'general',
      name: 'Сведения об организации',
      class: 'general',
      component: GeneralInformation,
    },
    {
      value: 'address',
      name: 'Адрес',
      class: 'address',
      component: AddressBlock,
      enable: ({ subjectType }) => ![ESubjectType.IR, ESubjectType.IF].includes(subjectType),
    },
    ...ViewCard.tabs,
    {
      value: 'register',
      class: 'register',
      name: 'Реестры',
      component: RegisterBlock,
    },
  ];

  get fieldName() {
    return [this.form.lastName, this.form.firstName, this.form.secondName].join(' ').trim();
  }

  get hasVerificationErrors() {
    const { subject_verification_status } = this.form.subject || {};

    return subject_verification_status?.code === ESubjectVerificationStatus.WRONG_DATA;
  }

  created() {
    this.activeId = Number(this.id || this.$route.params.id);
    this.getItem(this.activeId);
  }

  async getItem(id) {
    this.isLoading = true;
    const { data } = await this.$service.laboratory.findOne(id);
    this.form = data;
    this.isLoading = false;
  }

  closeModal() {
    this.isShowActionModal = false;
    this.isShowActionModalReject = false;
  }
}
</script>
