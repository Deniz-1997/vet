<template>
  <v-container class="cardOrganization">
    <v-row>
      <v-col cols="12">
        <div class="title">
          <span>Карточка организации</span>
        </div>
      </v-col>
    </v-row>
    <div class="baseInformation">
      <v-row>
        <v-col cols="12" md="12" lg="6" xl="6">
          <div class="inputContainer">
            <InputComponent label="Наименование" disabled :value="information.subject.subject_data.name" />
          </div>
        </v-col>
        <v-col cols="12" md="12" lg="6" xl="6">
          <div class="inputContainer">
            <InputComponent label="Краткое наименовение" disabled :value="information.subject.subject_data.short_name" />
          </div>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12" md="12" lg="6" xl="6">
          <div class="inputContainer">
            <InputComponent label="Организационно-правовая форма" disabled :value="opfForm" />
          </div>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12" md="6" lg="4" xl="2">
          <div class="inputContainer">
            <InputComponent label="Статус проверки" disabled :value="verificationStatus">
              <template v-if="hasVerificationErrors" #append>
                <v-icon>mdi-exclamation</v-icon>
              </template>
            </InputComponent>
          </div>
        </v-col>
      </v-row>
    </div>
    <v-row>
      <v-col cols="12">
        <div class="containerTabs">
          <div class="tabs">
            <div :class="[{ active: tab === 'general' }, 'tab']" @click="clickTab('general')">Общие сведения</div>
            <div :class="[{ active: tab === 'location' }, 'tab']" @click="clickTab('location')">Места хранения</div>
            <div
              v-if="hasVerificationErrors"
              :class="[{ active: tab === 'verification' }, 'tab']"
              @click="clickTab('verification')"
            >
              Результаты проверки
            </div>
            <!-- <div
              v-bind:class="[{ active: tab === 'requests' }, 'tab']"
              v-on:click="clickTab('requests')"
            >
              Заявления
            </div> -->
          </div>
        </div>
      </v-col>
    </v-row>
    <div class="containerTab">
      <SubjectVerificationInfo v-if="tab === 'verification'" :id="information.subject.subject_id" />
      <CardGeneralInformation
        v-if="tab === 'general'"
        :form="information"
        :organization-info="organizationInfo"
        type-card="organization"
        full-info
        is-showcase
      />
      <CardLocationInformation
        v-else-if="tab === 'location'"
        :form="information"
        :max-capacity="totalCapacity"
        is-showcase
        :changes-data="changesData"
      />
      <CardRequestInformation
        v-else-if="tab === 'requests'"
        :information="information"
        is-showcase
        :changes-data="changesData"
      />
    </div>

    <v-row>
      <v-col cols="12">
        <div class="buttons">
          <DefaultButton title="Закрыть" @click="clickClose" />
        </div>
      </v-col>
    </v-row>

    <v-overlay :value="isLoading">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
  </v-container>
</template>

<script lang="ts">
import { Component, Vue, Prop } from 'vue-property-decorator';
import CardGeneralInformation from './components/GeneralInformation.vue';
import CardLocationInformation from './components/LocationInformation.vue';
import CardRequestInformation from './components/RequestInformation.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import SubjectVerificationInfo from '@/components/SubjectVerification/SubjectVerificationInfo.vue';
import { getVerificationStatusName } from '@/components/SubjectVerification/utils';
import { ISubjectItem } from '@/services/models/common';
import { ESubjectVerificationStatus } from '@/services/enums/subject';

type Props = {
  information: Record<string, any> & { subject: ISubjectItem };
};

@Component({
  name: 'authorities-card-organization',
  components: {
    CardLocationInformation,
    CardGeneralInformation,
    DefaultButton,
    InputComponent,
    CardRequestInformation,
    SubjectVerificationInfo,
  },
})
export default class AuthoritiesCardOrganization extends Vue {
  @Prop({
    type: Object,
    default: () => ({}),
  })
  readonly information!: Props['information'];

  tab = 'general';
  changesData: any = {};
  organizationInfo: any = {};
  isLoading = false;

  get verificationStatus() {
    return getVerificationStatusName(this.information.subject);
  }

  get mothballedCapacity() {
    return this.information?.elevator_info?.capacity_mothballed || 0;
  }

  get commonCapacity() {
    return (this.information.elevator_site || []).reduce((sum, row) => sum + Number(row.capacity_tons || 0), 0);
  }

  get totalCapacity() {
    this.information.elevator_info.capacity = Number((this.commonCapacity - this.mothballedCapacity).toFixed(3));
    return this.information.elevator_info.capacity;
  }

  get hasVerificationErrors() {
    const { subject_verification_status } = this.information.subject || {};

    return subject_verification_status?.code === ESubjectVerificationStatus.WRONG_DATA;
  }

  subject_type(type) {
    switch (type) {
      case 'UL':
        return 'Российское юридическое лицо';
      case 'IP':
        return 'Индивидуальный предприниматель';
      case 'IR':
        return 'Ид. Юридическое лицо, являющееся иностранным лицом без регистрации в РФ';
      case 'IF':
        return 'Филиал, представительство иностранного юридического лица';
      default:
        return '';
    }
  }

  mounted() {
    this.isLoading = true;
    this.getInfoOrganization(this.information.subject_id);
    this.isLoading = false;
  }

  get opfForm() {
    if (this.information.subject?.subject_data?.opf) {
      return this.information.subject.subject_data.opf.name;
    } else if (this.information.subject.subject_type === 'IP') {
      return 'Индивидуальный предприниматель';
    } else {
      return '-';
    }
  }

  clickClose() {
    this.$emit('click-close');
  }

  clickTab(tab: string) {
    this.tab = tab;
  }

  async getInfoOrganization(id) {
    const data = await this.$store.dispatch('organization/getInfoOrganization', id);
    return (this.organizationInfo = data);
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';

.title {
  font-style: normal;
  font-weight: 500;
  font-size: 24px;
  line-height: 24px;
  color: $black-color;

  @include respond-to('medium') {
    font-size: 22px;
  }

  @include respond-to('small') {
    font-size: 18px;
  }
}

.inputContainer {
  display: flex;
  flex-direction: column;
}

.baseInformation {
  margin-top: 25px;

  @include respond-to('medium') {
    margin-top: 15px;
  }

  @include respond-to('small') {
    margin-top: 10px;
  }
}

.containerTabs {
  margin-top: 30px;
}

.label {
  color: $input-border-color;
  font-size: 16px;
  line-height: 16px;
  margin-bottom: 5px;

  @include respond-to('medium') {
    font-size: 16px;
  }

  @include respond-to('small') {
    font-size: 14px;
  }
}

.input {
  border: 1px solid $input-border-color;
  border-radius: 3px;
  background: $white-color;
  outline: none;
  width: 480px;
  height: 40px;
  color: $black-color;
  margin-right: 15px;
  font-size: 14px;
  line-height: 16px;
  padding: 0 10px;
  z-index: 9;

  &--disabled {
    background-color: $input-disable-background;
    color: $input-disabled-color;
  }

  &--small {
    width: 120px;
  }
}

.tabs {
  border-bottom: 1px solid $light-grey-color;
  width: 100%;
  display: flex;
  flex-direction: row;
  text-transform: uppercase;
}

.tab {
  display: flex;
  justify-content: center;
  font-weight: 700;
  line-height: 16px;
  font-size: 13px;
  color: $footer-color;
  cursor: pointer;
  padding-bottom: 8px;
  margin-right: 18px;

  &.active {
    color: $gold-light-color;
    border-bottom: 1px solid $gold-light-color;
  }
}

.buttons {
  display: flex;
  margin-top: 20px;
  justify-content: flex-end;
}

.cancel {
  background-color: $white-color;
  border: 1px solid #c1c1c1;
  border-radius: 4px;
  color: $medium-grey-color;
  padding: 15px 35px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  cursor: pointer;
  outline: none;

  @include respond-to('medium') {
    padding: 9px 25px;
  }

  @include respond-to('small') {
    padding: 5px 20px;
  }

  &:hover {
    box-shadow: 0 0 5px rgb(0 0 0 / 50%);
  }
}
</style>
