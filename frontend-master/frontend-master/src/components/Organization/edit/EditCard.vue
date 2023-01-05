<template>
  <v-container>
    <div v-if="!isLoading" class="cardOrganization">
      <div class="card">
        <v-row>
          <v-col cols="12">
            <div class="title">Добавление новой организации</div>
          </v-col>
        </v-row>

        <div class="btn-block">
          <v-row justify="end" class="manufacturers-btn">
            <DefaultButton title="Закрыть" @click="$router.go(-1)" />
          </v-row>
        </div>

        <v-row>
          <v-col cols="12">
            <div class="containerTabs">
              <div class="tabs">
                <div
                  :class="[{ active: step === 'type' }, 'step', { complete: form.subjectType }]"
                  @click="clickTab('type')"
                >
                  <span class="number">1</span>
                  <span class="text">Вид организации</span>
                  <svg width="24" height="8" viewBox="0 0 24 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M19.8293 0.123719C19.6214 -0.0581225 19.3056 -0.0370638 19.1237 0.170755C18.9419 0.378573 18.9629 0.694454 19.1707 0.876295L22.1693 3.50001H0V4.50001H22.1693L19.1707 7.12372C18.9629 7.30556 18.9419 7.62144 19.1237 7.82926C19.3056 8.03708 19.6214 8.05814 19.8293 7.8763L23.8293 4.3763C23.9378 4.28135 24 4.14419 24 4.00001C24 3.85583 23.9378 3.71866 23.8293 3.62372L19.8293 0.123719Z"
                      fill="#45474B"
                    />
                  </svg>
                </div>
                <div :class="[{ active: step === 'general' }, 'step']" @click="clickTab('general')">
                  <span class="number">2</span>
                  <span class="text">Сведения об организации</span>
                  <svg width="24" height="8" viewBox="0 0 24 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M19.8293 0.123719C19.6214 -0.0581225 19.3056 -0.0370638 19.1237 0.170755C18.9419 0.378573 18.9629 0.694454 19.1707 0.876295L22.1693 3.50001H0V4.50001H22.1693L19.1707 7.12372C18.9629 7.30556 18.9419 7.62144 19.1237 7.82926C19.3056 8.03708 19.6214 8.05814 19.8293 7.8763L23.8293 4.3763C23.9378 4.28135 24 4.14419 24 4.00001C24 3.85583 23.9378 3.71866 23.8293 3.62372L19.8293 0.123719Z"
                      fill="#45474B"
                    />
                  </svg>
                </div>
                <div
                  v-if="form.subjectType !== 'IF' && form.subjectType !== 'IR'"
                  :class="[{ active: step === 'address' }, 'step']"
                  @click="clickTab('address')"
                >
                  <span class="number">3</span>
                  <span class="text">Адрес</span>
                  <svg width="24" height="8" viewBox="0 0 24 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M19.8293 0.123719C19.6214 -0.0581225 19.3056 -0.0370638 19.1237 0.170755C18.9419 0.378573 18.9629 0.694454 19.1707 0.876295L22.1693 3.50001H0V4.50001H22.1693L19.1707 7.12372C18.9629 7.30556 18.9419 7.62144 19.1237 7.82926C19.3056 8.03708 19.6214 8.05814 19.8293 7.8763L23.8293 4.3763C23.9378 4.28135 24 4.14419 24 4.00001C24 3.85583 23.9378 3.71866 23.8293 3.62372L19.8293 0.123719Z"
                      fill="#45474B"
                    />
                  </svg>
                </div>
                <div :class="[{ active: step === 'register' }, 'step']" @click="clickTab('register')">
                  <span class="number">{{ form.subjectType !== 'IF' && form.subjectType !== 'IR' ? 4 : 3 }}</span>
                  <span class="text">Реестры</span>
                </div>
              </div>
            </div>
          </v-col>
        </v-row>

        <v-row v-if="step === 'type'">
          <v-col cols="12">
            <v-row>
              <v-col cols="12">
                <div class="subtitle">Выбор вида организации</div>
                <v-radio-group v-model="form.subjectType">
                  <v-row>
                    <v-col cols="12" md="5">
                      <v-radio label="Российское юридическое лицо" value="UL" :disabled="isEdit" />
                    </v-col>
                    <v-col cols="12" md="7">
                      <v-radio label="Юридическое лицо, являющееся иностранным лицом" value="IR" :disabled="isEdit" />
                    </v-col>
                    <v-col cols="12" md="5">
                      <v-radio label="Индивидуальный предприниматель" value="IP" :disabled="isEdit" />
                    </v-col>
                    <v-col cols="12" md="7">
                      <v-radio
                        label="Аккредитованный филиал представительства иностранного юр. лица"
                        value="IF"
                        :disabled="isEdit"
                      />
                    </v-col>
                  </v-row>
                </v-radio-group>
              </v-col>
            </v-row>
          </v-col>
        </v-row>

        <component
          :is="component"
          :form="form"
          :is-edit="isEdit"
          :step="step"
          :is-loading="isLoading"
          @save="saveOrganization"
        />
      </div>
    </div>

    <v-overlay :value="isLoading" :absolute="true">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
  </v-container>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import SubjectVerificationInfo from '@/components/SubjectVerification/SubjectVerificationInfo.vue';

import { ESubjectType } from '@/services/enums/subject';
import LegalEntitiesForm from '@/components/Manufacturers/ManufacturersEdit/LegalEntitiesForm.vue';
import LegalEntitiesPersonForm from '@/components/Manufacturers/ManufacturersEdit/LegalEntitiesPersonForm.vue';
import IndividualEntrepreneurForm from '@/components/Manufacturers/ManufacturersEdit/IndividualEntrepreneurForm.vue';
import AccreditedRepresentativeOfficeForm from '@/components/Manufacturers/ManufacturersEdit/AccreditedRepresentativeOfficeForm.vue';
import { addressNew } from '@/utils/global/filters';
import { TMapperPlain } from '@/services/models/common';
import { ManufacturerItemIn } from '@/services/mappers/manufacturer';

const Components = {
  [ESubjectType.UL]: 'LegalEntitiesForm',
  [ESubjectType.IP]: 'IndividualEntrepreneurForm',
  [ESubjectType.IR]: 'LegalEntitiesPersonForm',
  [ESubjectType.IF]: 'AccreditedRepresentativeOfficeForm',
};

const getForm = (): any => ({
  subjectType: undefined,
  address: {},
});

@Component({
  name: 'OrganizationEditCard',
  components: {
    DefaultButton,
    SubjectVerificationInfo,
    LegalEntitiesForm,
    LegalEntitiesPersonForm,
    IndividualEntrepreneurForm,
    AccreditedRepresentativeOfficeForm,
  },
  methods: { addressNew },
})
export default class OrganizationEditCard extends Vue {
  @Prop({ type: [String, Number], default: '' }) readonly idCard;
  @Prop({ type: Boolean }) readonly autoSelect;

  form: Partial<TMapperPlain<ManufacturerItemIn>> = getForm();
  isEdit = false;
  isLoading = false;
  step = 'type';
  rulesSeries = [(value) => (value && value.length === 4) || 'Серия должна состоять из 4 цифр.'];
  rulesPassortNumber = [(value) => (value && value.length === 6) || 'Номер должен состоять из 6 цифр.'];

  get component() {
    return Components[this.form.subjectType || ''] || 'div';
  }

  created() {
    if (this.idCard) {
      this.isEdit = true;
      this.fetchInfo();
    } else {
      this.form.subjectType = ESubjectType.UL;
      this.isLoading = false;
    }
  }

  async fetchInfo() {
    try {
      this.isLoading = true;
      const { data } = await this.$service.manufacturer.findOne(this.idCard);
      this.form = { ...data };
      this.isLoading = false;
    } catch (err) {
      this.isLoading = false;
      throw err;
    }
  }

  async saveOrganization(data) {
    this.isLoading = true;
    //ToDo не сетается значение "identity_doc_series" в форму this.form
    try {
      if (this.isEdit) {
        await this.$service.manufacturer.update(data);
        this.$emit('close');
        this.isEdit = false;
      } else {
        if (this.autoSelect) {
          this.$emit('autoSelect');
        }
        await this.$service.manufacturer.create(data);
        this.$emit('close');
      }
      this.isLoading = false;
    } catch (_error) {
      this.isLoading = false;
    }
  }

  clickTab(value: string) {
    this.step = value;
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';


.subtitle {
  font-size: 18px;
  font-weight: 500;
  margin-bottom: 20px;
}

.containerTabs {
  margin-top: 30px;
  padding-bottom: 20px;
}
.tabs {
  width: 100%;
  display: flex;
  flex-direction: row;
}
.step {
  display: flex;
  justify-content: center;
  font-weight: 500;
  font-size: 13px;
  line-height: 16px;
  color: #c1c1c1;
  cursor: pointer;
  padding-bottom: 8px;
  margin-right: 18px;
  align-items: center;

  &.complete {
    color: $gold-light-color;
    .number {
      background: $gold-light-color;
    }
  }

  &.active {
    color: #45474b;

    .number {
      background: #45474b;
    }
  }
  .number {
    display: inline-flex;
    height: 17px;
    width: 17px;
    align-items: center;
    justify-content: center;
    background: #c1c1c1;
    border-radius: 17px;
    color: #ffffff;
  }

  .text {
    display: inline-block;
    margin: 0 10px;
  }
}

.checkbox-block {
  align-items: center;
  // height: 61px;
  display: flex;
  margin-bottom: 28px;

  .label {
    margin-bottom: 0;
    margin-left: 5px;
    color: #828286;
    font-size: 14px;
    font-weight: normal;
    line-height: 16px;
  }
}

.checkbox {
  cursor: default;
  width: 16px;
  height: 16px;
  position: relative;

  [type='checkbox'] {
    position: absolute;
    opacity: 0;
  }

  &__icon {
    align-items: center;
    justify-content: center;
    background: $check-bg;
    display: flex;
    height: 16px;
    width: 16px;
    border: 1px solid $input-border-color;
    border-radius: 4px;

    img {
      width: 9px;
      display: block;
      opacity: 0;
    }
  }

  [type='checkbox']:checked {
    & + .checkbox__icon {
      background: $gold-light-color;
      border-color: $gold-light-color;

      img {
        opacity: 1;
      }
    }
  }
}

.btn-block {
  margin-top: 24px;
}
</style>
