<template>
  <OrganizationViewCard :form="form" :loading="isLoading" :tabs="tabs">
    <template #actions>
      <v-col cols="7" class="justify--end">
        <DefaultButton
          v-if="canExcludeManufacture"
          prepend-icon="mdi-minus-circle-outline"
          title="Аннулирование"
          @click="isShowActionModal = true"
        />
        <DefaultButton
          v-if="canExcludeManufacture"
          title="Отказать в регистрации"
          prepend-icon="mdi-cancel"
          @click="isShowActionModalReject = true"
        />
        <DefaultButton
          v-if="canEditCardManufacture"
          type="button"
          class="ml-2"
          size="micro"
          prepend-icon="mdi-pencil"
          title="Редактировать"
          variant="primary"
          @click="$router.push(`/manufacturers/edit/${form.subjectId}`)"
        />
        <DefaultButton title="Закрыть" @click="$router.go(-1)" />
      </v-col>
    </template>

    <reject-manufacturers
      v-if="isShowActionModalReject"
      :id="form.id"
      v-model="isShowActionModalReject"
      :name="form.name"
      @close="closeModal"
    />

    <ExcludeManufacturers
      v-if="isShowActionModal"
      :id="form.id"
      v-model="isShowActionModal"
      :name="form.name"
      @close="closeModal"
    />
  </OrganizationViewCard>
</template>

<script lang="ts">
import { Component, Vue, Prop } from 'vue-property-decorator';
import { ESubjectType, ESubjectVerificationStatus } from '@/services/enums/subject';
import { EAction, mapAccessFlags } from '@/utils';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import ExcludeManufacturers from '@/components/Manufacturers/ManufacturersModal/ExcludeManufacturers.vue';
import RejectManufacturers from '@/components/Manufacturers/ManufacturersModal/RejectManufactures.vue';
import GeneralInformation from './components/GeneralInformation.vue';
import AddressBlock from './components/Address.vue';
import RegisterBlock from '@/components/Subjects/view/components/Register.vue';
import OrganizationViewCard from '@/components/OrganizationViewCard/OrganizationViewCard.vue';

@Component({
  name: 'ManufacturerViewCard',
  components: {
    DefaultButton,
    GeneralInformation,
    AddressBlock,
    RegisterBlock,
    ExcludeManufacturers,
    RejectManufacturers,
    OrganizationViewCard,
  },
  computed: {
    ...mapAccessFlags({
      canExcludeManufacture: EAction.DELETE_MANUFACTURER,
      canEditCardManufacture: EAction.CHANGE_MANUFACTURER,
    }),
  },
})
export default class ViewCard extends Vue {
  @Prop({ type: Number, default: null }) readonly id!: number;
  @Prop({ type: Boolean, default: true }) readonly withRegisters!: boolean;

  static tabs = [];

  readonly canExcludeManufacture!: boolean;
  readonly canEditCardManufacture!: boolean;

  activeId: number | null = null;
  form: any = {};
  divisions: any = [];
  opfList: any[] = [];
  isLoading = false;
  isShowActionModal = false;
  isShowActionModalReject = false;
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
      enable: this.withRegisters,
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
    // this.divisions = await this.$service.divisions.find(this.form.subjectId);

    const { data } = await this.$service.manufacturer.findOne(id);
    this.form = data;

    this.isLoading = false;
  }

  closeModal() {
    this.isShowActionModal = false;
    this.isShowActionModalReject = false;
  }
}
</script>
