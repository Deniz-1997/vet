<template>
  <OrganizationViewCard
    :form="form"
    :loading="isLoading"
    :tabs="tabs"
    :permit-edit="canEditCardStateAuthority"
    @edit="$router.push(`/stateAuthority/edit/${form.subjectId}`)"
    @close="$router.go(-1)"
  />
</template>

<script lang="ts">
import { Component, Vue, Prop } from 'vue-property-decorator';
import { ESubjectType, ESubjectVerificationStatus } from '@/services/enums/subject';
import GeneralInformation from './components/GeneralInformation.vue';
import AddressBlock from './components/Address.vue';
import StructureBlock from './components/Structure.vue';
import OrganizationViewCard from '@/components/OrganizationViewCard/OrganizationViewCard.vue';
import RegisterBlock from '@/components/Subjects/view/components/Register.vue';
import { EAction, mapAccessFlags } from '@/utils';

@Component({
  name: 'StateAuthorityViewCard',
  components: {
    GeneralInformation,
    AddressBlock,
    StructureBlock,
    OrganizationViewCard,
  },
  computed: {
    ...mapAccessFlags({
      canEditCardStateAuthority: EAction.UPDATE_GOV_ORG,
    }),
  },
})
export default class ViewCard extends Vue {
  @Prop({ type: Number, default: null }) readonly id!: number;
  @Prop({ type: Boolean, default: false }) readonly withRegisters!: boolean;

  static tabs = [
    {
      value: 'structure',
      class: 'structure',
      name: 'Организационная структура',
      component: StructureBlock,
      mapper: ({ divisions }) => divisions,
    },
  ];

  readonly canEditCardStateAuthority!: boolean;

  activeId: number | null = null;
  form: any = {};
  opfList: any[] = [];
  isLoading = false;
  tabs = [
    {
      value: 'general',
      class: 'general',
      name: 'Сведения об организации',
      component: GeneralInformation,
    },
    {
      value: 'address',
      class: 'address',
      name: 'Адрес',
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
    this.form = {};
    const { data } = await this.$service.stateAuthority.findOne(id);
    this.form = data;
    const { data: divisions } = await this.$service.divisions.find(this.form.subjectId);
    this.form.divisions = divisions;

    this.isLoading = false;
  }
}
</script>
