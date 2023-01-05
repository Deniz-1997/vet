<template>
  <v-container v-if="!component">
    <v-overlay :value="true" :absolute="true">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
  </v-container>
  <component :is="component" v-else v-bind="payload" />
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import ManufacturerViewCard from '@/components/Organization/view/ViewCard.vue';
import LaboratoryViewCard from '@/components/Laboratory/view/ViewCard.vue';
import StateAuthorityViewCard from '@/components/StateAuthority/view/ViewCard.vue';
import ShortViewCard from '@/components/Subjects/view/ShortViewCard.vue';
import { ESubjectType } from '@/services/enums/subject';
import GeneralInformation from '@/components/Organization/view/components/GeneralInformation.vue';
import AddressBlock from '@/components/Organization/view/components/Address.vue';
import RegisterBlock from '@/components/Subjects/view/components/Register.vue';
import { merge } from '@/utils';

@Component({ name: 'OrganizationViewCard' })
export default class OrganizationViewCard extends Vue {
  component: any = null;
  payload: any = null;

  static tabs = [
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
    {
      value: 'register',
      class: 'register',
      name: 'Реестры',
      component: RegisterBlock,
    },
  ];

  async created() {
    const { data } = await this.$service.subject.findOne(this.$route.params.id);
    const { registers, attachedId } = data;

    const tabs = OrganizationViewCard.tabs.slice(0, 2);
    const promises: Promise<any>[] = [];

    if (registers.manufacturer) {
      tabs.push(...(ManufacturerViewCard.tabs as any[]));
      promises.push(this.$service.manufacturer.findOne(attachedId.manufacturer));
    }

    if (registers.ogv) {
      tabs.push(...(StateAuthorityViewCard.tabs as any[]));
      promises.push(this.$service.stateAuthority.findOne(attachedId.ogv));
    }

    if (registers.laboratory) {
      tabs.push(...(LaboratoryViewCard.tabs as any[]));
      promises.push(this.$service.laboratory.findOne(attachedId.laboratory));
    }

    const additional = await Promise.all(promises);

    tabs.push([...(OrganizationViewCard.tabs as any)].pop());

    this.payload = {
      tabs,
      form: merge(
        data,
        additional.map(({ data }) => data),
      ),
    };
    this.component = ShortViewCard;
  }
}
</script>
