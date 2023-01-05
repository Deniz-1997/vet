<template>
  <UiForm
    ref="form"
    :rules="rules"
    :messages="messages"
    :step="!manuallyActiveStep"
    @validation="(v) => (isValid = v.isValid)"
    @submit="saveAction"
  >
    <v-container>
      <v-row>
        <v-col cols="5">
          <h1 class="title">{{ titleForm }}</h1>
        </v-col>
        <v-col cols="7">
          <div class="btn-block justify-end">
            <DefaultButton title="Отмена" @click="cancel" />
            <DefaultButton
              variant="primary"
              title="Назад"
              :disabled="activeStep === 0"
              :loading="isLoading"
              @click="prevStepGo"
            >
              <template #prependCustomIcon>
                <icon-component icon-color="currentColor" width="16" height="16">
                  <prev-icon />
                </icon-component>
              </template>
            </DefaultButton>
            <DefaultButton
              variant="primary"
              :title="textForSave()"
              :loading="isLoading"
              type="submit"
              :disabled="isDisabledSubmit"
            >
              <template v-if="statusButton !== 'create'" #appendCustomIcon>
                <icon-component icon-color="currentColor" width="16" height="16">
                  <next-icon />
                </icon-component>
              </template>
            </DefaultButton>
            <DefaultButton
              v-if="isEdit"
              variant="primary"
              title="Сохранить"
              :disabled="isLoading || isDisabledSubmit"
              :loading="isLoading"
              @click="saveForm"
            />
            <DefaultButton
              :class="{ 'btn--active': isShowHelp }"
              is-svg
              custom-icon="mdi-information-outline"
              @click="isShowHelp = !isShowHelp"
            />
          </div>
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12">
          <Steps :steps="steps" @selectStep="selectStep" />
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="7">
          <OrganizationType
            v-if="steps[activeStep].code === 'type'"
            v-model="form"
            :is-active="notVerification || successVerification"
            @first-change="changeManuallyStep"
          />
          <component
            :is="component"
            v-if="steps[activeStep].code === 'info'"
            v-model="form"
            :is-loading="isLoading"
            :is-disabled="notVerification || successVerification"
            @update-rules="updateRules"
          />
          <Address
            v-if="steps[activeStep].code === 'address'"
            v-model="form"
            @change="updateForm"
            @update-rules="updateRules"
          />
          <Register v-if="steps[activeStep].code === 'register'" v-model="form" @first-change="changeManuallyStep" />
          <LocationsTable
            v-if="steps[activeStep].code === 'locations'"
            v-model="form.locations"
            :is-edit-table="typeRegistry.lab || typeRegistry.registry"
          />
          <CertificatesTable
            v-if="steps[activeStep].code === 'certificates'"
            v-model="form"
            :is-edit-table="typeRegistry.lab || typeRegistry.registry"
          />
          <Divisions
            v-if="steps[activeStep].code === 'ogv'"
            v-model="form.divisions"
            :is-edit-table="typeRegistry.ogv || typeRegistry.registry"
          />
        </v-col>
        <v-col cols="5">
          <HelpComponent v-show="isShowHelp" />
        </v-col>
      </v-row>
    </v-container>
    <v-overlay :value="isLoading" :absolute="true">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
  </UiForm>
</template>

<script lang="ts">
import { Component, Vue, Watch } from 'vue-property-decorator';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import SubjectVerificationInfo from '@/components/SubjectVerification/SubjectVerificationInfo.vue';

import { ESubjectType } from '@/services/enums/subject';
import LegalEntitiesForm from './OrganizationInfo/components/LegalEntitiesForm.vue';
import LegalEntitiesPersonForm from './OrganizationInfo/components/LegalEntitiesPersonForm.vue';
import IndividualEntrepreneurForm from './OrganizationInfo/components/IndividualEntrepreneurForm.vue';
import AccreditedRepresentativeOfficeForm from './OrganizationInfo/components/AccreditedRepresentativeOfficeForm.vue';
import OrganizationType from './OrganizationType/OrganizationType.vue';
import { addressNew } from '@/utils/global/filters';
import Steps from '@/components/common/Steps/Steps.vue';
import Address from './Address/Address.vue';
import Register from './Register/Register.vue';
import LocationsTable from './Locations/LocationsTable.vue';
import CertificatesTable from './Certificates/CertificatesTable.vue';
import Divisions from './Divisions/Divisions.vue';

import HelpComponent from './Help/Help.vue';
import IconComponent from '@/components/common/IconComponent/IconComponent.vue';
import NextIcon from '@/components/common/IconComponent/icons/NextIcon.vue';
import PrevIcon from '@/components/common/IconComponent/icons/PrevIcon.vue';
import { ESubjectVerificationStatus } from '@/services/enums/subject';

const defaultStep = () => {
  return [
    { code: 'type', name: 'Вид организации', isActive: true, isComplete: false },
    { code: 'info', name: 'Сведения об организации', isActive: false, isComplete: false },
    { code: 'register', name: 'Реестры', isActive: false, isComplete: false },
  ];
};

const Components = {
  [ESubjectType.UL]: 'LegalEntitiesForm',
  [ESubjectType.IP]: 'IndividualEntrepreneurForm',
  [ESubjectType.IR]: 'LegalEntitiesPersonForm',
  [ESubjectType.IF]: 'AccreditedRepresentativeOfficeForm',
};

@Component({
  name: 'SubjectForm',
  components: {
    DefaultButton,
    IconComponent,
    NextIcon,
    PrevIcon,
    SubjectVerificationInfo,
    LegalEntitiesForm,
    LegalEntitiesPersonForm,
    IndividualEntrepreneurForm,
    AccreditedRepresentativeOfficeForm,
    HelpComponent,
    OrganizationType,
    Steps,
    Address,
    Register,
    LocationsTable,
    CertificatesTable,
    Divisions,
  },
  methods: { addressNew },
})
export default class OrganizationEditCard extends Vue {
  isValid = true;
  isLoading = false;
  isShowHelp = false;
  isActive = false;
  rules = {};
  /** Флаг: текущий шаг был выбран пользователем вручную */
  manuallyActiveStep = false;

  steps = defaultStep();

  activeStep = 0;
  isEdit = false;

  form: any = {
    subjectType: ESubjectType.UL,
    locations: [],
    certificates: [],
    divisions: [],
    registers: {
      manufacturer: false,
      laboratory: false,
      ogv: false,
    },

    address: {
      country: {},
      additionalInfo: null,
      address: null,
      addressText: '',
    },
  };

  addressStep = {
    code: 'address',
    name: 'Адрес',
    isActive: false,
    isComplete: false,
  };

  laboratoryStep = [
    {
      code: 'locations',
      name: 'Адреса осуществления деятельности',
      isActive: false,
      isComplete: true,
    },
    { code: 'certificates', name: 'Аттестаты аккредитации', isActive: false, isComplete: true },
  ];

  ogvStep = [{ code: 'ogv', name: 'Организационная структура', isActive: false, isComplete: true }];

  @Watch('form.subjectType', { deep: true })
  checkSubjectType() {
    if (this.form.subjectType === ESubjectType.IR || this.form.subjectType === ESubjectType.IF) {
      if (this.steps.find((item) => item.code === 'address')) {
        const indexStep = this.steps.findIndex((item) => item.code === 'address');
        this.steps.splice(indexStep, 1);
      }
    } else {
      if (this.steps.find((item) => item.code === 'address')) {
        return;
      }
      this.steps.splice(2, 0, this.addressStep);
    }
  }

  @Watch('form.registers', { deep: true })
  checkRegisterType() {
    if (this.form.registers.laboratory) {
      if (this.steps.findIndex((item) => item.code === 'locations') < 0) {
        this.steps.push(...this.laboratoryStep);
      }
    } else if (!this.form.registers.laboratory) {
      const indexStep = this.steps.findIndex((item) => item.code === 'locations');
      if (indexStep >= 0) {
        this.steps.splice(indexStep, 2);
      }
    }
    if (this.form.registers.ogv) {
      if (this.steps.findIndex((item) => item.code === 'ogv') < 0) {
        this.steps.push(...this.ogvStep);
      }
    } else if (!this.form.registers.ogv) {
      const indexStep = this.steps.findIndex((item) => item.code === 'ogv');
      if (indexStep >= 0) {
        this.steps.splice(indexStep, 1);
      }
    }
  }

  get component() {
    return Components[this.form.subjectType || ''] || 'div';
  }

  get isCreate() {
    return !!this.form.subjectId;
  }

  get titleForm() {
    const typeForm = this.$route.params.id;
    return typeForm ? 'Редактирование организации' : 'Добавление новой организации';
  }

  get messages() {
    return {};
  }

  get isDisabledSubmit() {
    if (this.steps[this.activeStep].code === 'locations') {
      return !this.form.locations?.length;
    }

    if (this.steps[this.activeStep].code === 'certificates') {
      return !this.form.certificates?.length;
    }

    return false;
  }

  get statusButton() {
    if (this.activeStep === this.steps.length - 1 && !this.isEdit) {
      return 'create';
    }
    return 'edit';
  }

  get type() {
    if (this.$route.path.includes('create')) {
      return 'create';
    }
    return 'edit';
  }

  get notVerification() {
    const { status } = this.form || {};

    return status?.code === ESubjectVerificationStatus.NOT_VERIFIED;
  }

  get successVerification() {
    const { status } = this.form || {};

    return status?.code === ESubjectVerificationStatus.SUCCESS_VERIFICATION;
  }

  get typeRegistry() {
    const { path } = this.$route;

    const result: Record<string, boolean> = {
      manufacturer: path.includes('/manufacturers'),
      lab: path.includes('/laboratories'),
      ogv: path.includes('/stateAuthority'),
      registry: path.includes('/subjects'),
    };

    if (!result.manufacturer && !result.lab && !result.ogv && !result.registry) {
      result.subject = path.includes('/stateAuthority');
    }

    return result;
  }

  // eslint-disable-next-line max-lines-per-function
  async created() {
    if (this.$route.path.includes('manufacturers')) {
      this.steps = [...this.steps];
    } else if (this.$route.path.includes('laboratories')) {
      this.steps = [...this.steps, ...this.laboratoryStep];
    } else if (this.$route.path.includes('stateAuthority')) {
      this.steps = [...this.steps, ...this.ogvStep];
    }
    this.steps.splice(2, 0, this.addressStep);
    this.activeStep = this.steps.findIndex((item) => item.isActive);
    this.isLoading = true;
    if (this.$route.params?.id) {
      const { data } = await this.$service.subject.findOne(this.$route.params.id);
      this.form = { ...this.form, ...data };
      // this.steps = [...this.ogvStep];

      if (this.$route.path.includes('subjects')) {
        if (this.form.registers.laboratory) {
          this.steps = [...this.steps, ...this.laboratoryStep];
        }

        if (this.form.registers.ogv) {
          this.steps = [...this.steps, ...this.ogvStep];
        }
      }

      this.steps.forEach((step) => {
        step.isComplete = true;
      });
      this.isEdit = true;
      this.isLoading = false;
      return;
    }
    this.isLoading = false;
    this.form.subjectType = ESubjectType.UL;
  }

  cancel() {
    if (this.$route.path.includes('manufacturers')) {
      return this.$router.push('/manufacturers');
    }
    if (this.$route.path.includes('laboratories')) {
      return this.$router.push('/laboratories');
    }
    if (this.$route.path.includes('stateAuthority')) {
      return this.$router.push('/stateAuthority');
    }
    if (this.$route.path.includes('subjects')) {
      return this.$router.push('/subjects');
    }
  }

  textForSave() {
    if (this.statusButton === 'create') {
      return 'Сохранить';
    }
    return 'Далее';
  }

  updateForm(value) {
    this.form = { ...this.form, ...value };
  }

  updateRules(rules) {
    this.rules = { ...rules };
  }

  prevStepGo() {
    this.rules = {};
    this.activeStep = this.steps.findIndex((item) => item.isActive);
    this.steps = this.steps.map((item) => ({
      ...item,
      isActive: false,
    }));
    this.steps[this.activeStep].isActive = false;
    this.steps[this.activeStep].isComplete = false;
    this.steps[this.activeStep - 1].isActive = true;
    this.steps[this.activeStep - 1].isComplete = true;
    this.activeStep = this.activeStep - 1;
    if (this.activeStep === 0) {
      this.steps[0].isActive = true;
      this.steps = this.steps.map((item) => ({
        ...item,
        isComplete: false,
      }));
    }
  }

  changeActiveStep(step) {
    this.rules = {};
    this.steps[this.activeStep].isActive = false;
    this.steps[step].isActive = true;
    this.activeStep = step;
  }

  selectStep(step) {
    this.activeStep = this.steps.findIndex((item) => item.isActive);

    if (step === this.activeStep) {
      return;
    }

    if (this.manuallyActiveStep && !this.isValid) {
      return;
    }

    if (this.steps[step].isComplete) {
      this.changeActiveStep(step);
      this.manuallyActiveStep = true;
    } else if (this.steps[step - 1].isComplete) {
      this.changeActiveStep(step);
      this.manuallyActiveStep = false;
    }
  }

  changeManuallyStep() {
    if (!this.isEdit && this.manuallyActiveStep) {
      if (this.steps[this.activeStep].code === 'type') {
        this.steps = this.steps.map((step) => ({
          ...step,
          isComplete: false,
        }));
        return;
      }

      if (this.steps[this.activeStep].code === 'register') {
        this.steps = this.steps.map((step, index) => ({
          ...step,
          isComplete: index > this.activeStep ? false : step.isComplete,
        }));
      }
    }
  }

  async saveAction() {
    this.rules = {};
    this.activeStep = this.steps.findIndex((item) => item.isActive);
    if (this.activeStep < this.steps.length - 1) {
      if (!this.steps[this.activeStep + 1].isComplete) {
        this.manuallyActiveStep = false;
      }
      this.steps[this.activeStep].isActive = false;
      this.steps[this.activeStep].isComplete = true;
      this.steps[this.activeStep + 1].isActive = true;
      this.activeStep = this.activeStep + 1;
    } else {
      this.saveForm();
    }
  }

  async saveForm() {
    if (this.isEdit) {
      this.isLoading = true;
      try {
        await this.$service.subject.update(this.form);
        this.isLoading = false;
        this.cancel();
        return;
      } catch (e) {
        this.isLoading = false;
        return;
      }
    } else {
      try {
        this.isLoading = true;
        await this.$service.subject.create(this.form);
        this.isLoading = false;
        if (this.activeStep == this.steps.length - 1) {
          this.cancel();
        }
      } catch (e) {
        this.isLoading = false;
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.title {
  font-size: 16px;
  margin: 0;
}

.justify-end {
  display: flex;
  align-items: center;
  justify-content: flex-end;
}
</style>
