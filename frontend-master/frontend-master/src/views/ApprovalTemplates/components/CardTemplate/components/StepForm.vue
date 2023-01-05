<template>
  <div>
    <div class="overlay" @click="clickClose" />
    <v-container class="storage-locations">
      <v-row>
        <v-col cols="10">
          <div class="title">
            <span>Этапы</span>
          </div>
        </v-col>
        <v-col cols="1"> </v-col>
        <v-col cols="1">
          <div class="close">
            <img src="/icons/cross.svg" class="cross" @click="clickClose" />
          </div>
        </v-col>
      </v-row>
      <UiForm
        :rules="rules"
        class="baseInformation"
        validate-on="input"
        @validation="(v) => (isValid = v.isValid)"
        @submit="save"
      >
        <v-row>
          <v-col cols="6" md="6" lg="6" xl="6">
            <div class="inputContainer">
              <InputComponent class="input--large" :value="getNumberStep()" label="Этап" disabled />
            </div>
          </v-col>
          <v-col cols="6" md="6" lg="6" xl="6">
            <UiControl name="term" :value="tempForm.decision_period_days" class="inputContainer">
              <InputComponent
                v-model="tempForm.decision_period_days"
                class="input--large"
                :disabled="isViewType"
                :mask="mask"
                label="Срок, дни"
              />
            </UiControl>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12" md="12" lg="12" xl="12">
            <UiControl name="name" :value="tempForm.name" class="inputContainer">
              <InputComponent
                v-model="tempForm.name"
                :disabled="isAutomatic"
                class="input--large"
                label="Наименование"
              />
            </UiControl>
          </v-col>

          <v-col cols="12" md="12" lg="12" xl="12">
            <UiControl name="subject" :value="tempForm.subject" class="inputContainer">
              <AutocompleteComponent
                v-model="tempForm.subject"
                return-object
                label="Организация"
                :items="subjectList"
                item-value="subject.subject_data.code"
                item-text="subject.subject_data.name"
                @searchInputUpdate="searchSubject"
              />
            </UiControl>
          </v-col>
          <v-col v-if="divisionList.length > 0" cols="12" md="12" lg="12" xl="12">
            <UiControl name="division" :value="tempForm.subject_division" class="inputContainer">
              <AutocompleteComponent
                v-model="tempForm.subject_division"
                return-object
                :items="divisionList"
                label="Подразделение"
                item-value="code"
                item-text="name"
              />
              <!-- <SelectComponent
                return-object
                v-model="tempForm.subject_division"
                :items="divisionList"
                item-value="code"
                item-text="name"
                :isDisabled="!tempForm.subject"
              /> -->
            </UiControl>
          </v-col>
          <v-col cols="12">
            <UiControl name="automatic" :value="isAutomatic" class="inputContainer">
              <checkbox-component
                id="automatic"
                :value="isAutomatic"
                label="Добавить автоматический шаг"
                @change="handleCheck"
              />
            </UiControl>
          </v-col>

          <v-col v-if="isAutomatic" cols="12">
            <UiControl name="automaticStage" :value="tempForm.automatic_stage" class="inputContainer">
              <SelectComponent
                v-model="automaticName"
                return-object
                :items="automaticStages"
                item-value="id"
                item-text="name"
              />
            </UiControl>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12">
            <div class="buttons">
              <DefaultButton title="Отмена" @click="clickClose" />
              <DefaultButton title="Сохранить" variant="primary" type="submit" :disabled="!isValid || isLoading" />
            </div>
          </v-col>
        </v-row>
      </UiForm>

      <v-overlay :value="isLoading">
        <v-progress-circular indeterminate size="64"></v-progress-circular>
      </v-overlay>
    </v-container>
  </div>
</template>

<script lang="ts">
import _ from 'lodash';
import { Component, Vue, Prop, Watch } from 'vue-property-decorator';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import SelectComponent from '@/components/common/inputs/SelectComponent.vue';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import { numberMask } from '@/components/common/inputs/mask/number';
import CheckboxComponent from '@/components/common/inputs/CheckboxComponent.vue';

@Component({
  name: 'authorities-card-request',
  components: {
    InputComponent,
    DefaultButton,
    SelectComponent,
    AutocompleteComponent,
    CheckboxComponent,
  },
})
export default class AuthoritiesCardRequest extends Vue {
  @Prop({ type: Object, default: {} }) public storage!: any;
  @Prop({ type: Object, default: {} }) public information!: any;
  @Prop({ type: Boolean, default: false }) public isViewType!: boolean;
  storageLocationsList = [];
  editTable = false;
  locationStorage = null;
  isEdit = false;
  isShowcase = false;
  tempForm: any = {};
  subjectList = [];
  divisionList = [];
  isDisabled = true;
  isLoading = false;
  isValid = false;
  isAutomatic = false;
  automaticStages: any[] = [];
  mask = numberMask;

  get rules() {
    return {
      term: 'required|integer',
      name: 'required',
      subject: 'required',
      division: [!!this.divisionList.length && 'required'].filter((v) => v),
      automatic: 'boolean',
      automaticStage: [
        {
          required_if: ['automatic', true],
        },
      ],
    };
  }

  get automaticName() {
    return this.tempForm.automatic_stage;
  }

  set automaticName(v) {
    this.tempForm = {
      ...this.tempForm,
      automatic_stage: v,
      name: v?.name,
    };
  }

  updated() {
    if (this.tempForm) {
      const { decision_period_days, subject, subject_division, name } = this.tempForm;
      if (decision_period_days && subject && subject_division && name) {
        this.isDisabled = false;
      }
    }
  }

  clickClose() {
    this.$emit('click-close');
  }

  save() {
    this.$emit('submit', {
      ...this.tempForm,
      automatic_stage: this.tempForm.automatic_stage && {
        id: this.tempForm.automatic_stage.id,
      },
      subject_division_id: this.tempForm?.subject_division?.subject_division_id,
      ...this.tempForm.subject,
      subject_name: this.tempForm.subject ? this.tempForm.subject.subject.name : '-',
      subject_division_name: this.tempForm.subject_division ? this.tempForm.subject_division.name : '-',
    });
  }

  created() {
    this.fetchListOrganizationApproval();
    if (this.storage) {
      this.tempForm = { ...this.tempForm, ...(this.storage.data || {}) };
    }
  }

  async handleCheck() {
    this.isLoading = true;
    this.isAutomatic = !this.isAutomatic;
    if (this.isAutomatic) {
      this.automaticStages = await this.$store.dispatch('templateApproval/getListAutomaticStages');
    }
    if (this.tempForm.automatic_stage) {
      this.tempForm.name = this.tempForm.automatic_stage.name;
    }
    this.isLoading = false;
  }

  getNumberStep() {
    return this.information.stages ? this.information.stages.length + 1 : 1;
  }

  async searchSubject(value) {
    if (value === null) {
      return;
    }
    const itemIndex = this.subjectList.findIndex((item: any) => item.subject.subject_data.name === value);
    if (itemIndex === -1) {
      this.fetchListOrganizationApproval();
    }
  }

  async fetchListOrganizationApproval() {
    this.isLoading = true;
    const { content } = await this.$store.dispatch('stateAuthority/getList', {
      params: {
        actual: true,
      },
    });
    // const { content } = await this.$store.dispatch(
    //   "templateApproval/getListApprovalOrganization",
    //   {actual: true}
    // );

    this.subjectList = content;
    this.isLoading = false;
  }

  @Watch('tempForm.subject')
  async fetchListResponsible() {
    this.isLoading = true;
    const { content } = await this.$store.dispatch('templateApproval/getListDivisions', {
      subject_id: this.tempForm.subject.subject_id,
    });
    this.divisionList = content;
    this.isLoading = false;
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';

.storage-locations {
  position: fixed;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
  z-index: 100;
  background: $white-color;
  border: 1px solid $input-border-color;
  box-sizing: border-box;
  box-shadow: 0 16px 32px rgba($black-color, 0.1);
  border-radius: 4px;
  max-width: 500px;
  width: 100%;
  max-height: 800px;
  overflow-y: auto;
  padding: 20px;
}

.close {
  display: flex;
  justify-content: flex-end;
  cursor: pointer;
}

.overlay {
  background-color: rgba($black-color, 0.3);
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 9;
}

.container {
  overflow-y: auto;
}

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

.baseInformation {
  margin-top: 25px;

  .row {
    width: auto !important;
  }

  @include respond-to('medium') {
    margin-top: 15px;
  }

  @include respond-to('small') {
    margin-top: 10px;
  }
}

.label {
  color: $input-border-color;
  font-size: 16px;
  line-height: 16px;
  margin-bottom: 5px;
  display: block;
}

.buttons {
  display: flex;
  margin-top: 20px;
  justify-content: flex-end;
}

.button {
  background-color: $white-color;
  color: $medium-grey-color;
  padding: 15px 35px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  border-radius: 4px;
  margin-left: 15px;
  border: 1px solid $input-border-color;
  cursor: pointer;
  outline: none;

  @include respond-to('medium') {
    padding: 9px 25px;
  }

  @include respond-to('small') {
    padding: 5px 20px;
  }

  &:hover {
    box-shadow: 0 0 5px rgba($black-color, 0.5);
  }

  &--primary {
    border-color: $button-primary-background;
    background-color: $button-primary-background;
    color: $white-color;
  }
}

.select {
  height: 40px;
  width: 320px;

  &--lg {
    width: 100%;
  }

  &--small {
    width: 100%;
  }
}

.inputContainer {
  position: relative;
}

.inputContainer:hover {
  .pop-up {
    display: block;
  }
}
</style>
