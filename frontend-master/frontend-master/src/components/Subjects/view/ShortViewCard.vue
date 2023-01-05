<template>
  <OrganizationViewCard :form="form" :loading="isLoading" :tabs="tabs">
    <template #actions>
      <v-col cols="7" class="justify--end">
        <DefaultButton
          v-if="canEditCardSubject"
          type="button"
          class="ml-2"
          size="micro"
          prepend-icon="mdi-pencil"
          title="Редактировать"
          variant="primary"
          @click="$router.push(`/subjects/edit/${form.subjectId}`)"
        />
        <DefaultButton title="Закрыть" @click="$router.go(-1)" />
      </v-col>
    </template>
  </OrganizationViewCard>
</template>

<script lang="ts">
import { Component, Vue, Prop } from 'vue-property-decorator';
import { ESubjectVerificationStatus } from '@/services/enums/subject';
import { EAction, mapAccessFlags } from '@/utils';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import ExcludeManufacturers from '@/components/Manufacturers/ManufacturersModal/ExcludeManufacturers.vue';
import RejectManufacturers from '@/components/Manufacturers/ManufacturersModal/RejectManufactures.vue';
import GeneralInformation from '@/components/Organization/view/components/GeneralInformation.vue';
import AddressBlock from '@/components/Organization/view/components/Address.vue';
import OrganizationViewCard from '@/components/OrganizationViewCard/OrganizationViewCard.vue';

@Component({
  name: 'ShortViewCard',
  components: {
    DefaultButton,
    GeneralInformation,
    AddressBlock,
    ExcludeManufacturers,
    RejectManufacturers,
    OrganizationViewCard,
  },
  computed: {
    ...mapAccessFlags({
      canEditCardSubject: EAction.CHANGE_FULL_ORGANIZATION,
    }),
  },
})
export default class ViewCard extends Vue {
  @Prop({ type: Object, default: () => ({}) }) readonly form!: any;
  @Prop({ type: Array, default: () => [] }) readonly tabs!: any[];

  readonly canEditCardSubject!: boolean;

  activeId: number | null = null;
  divisions: any = [];
  opfList: any[] = [];
  isLoading = false;

  get fieldName() {
    return [this.form.lastName, this.form.firstName, this.form.secondName].join(' ').trim();
  }

  get hasVerificationErrors() {
    const { subject_verification_status } = this.form.subject || {};

    return subject_verification_status?.code === ESubjectVerificationStatus.WRONG_DATA;
  }
}
</script>
