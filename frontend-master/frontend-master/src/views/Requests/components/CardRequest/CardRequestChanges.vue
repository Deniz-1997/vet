<template>
  <v-container class="cardRequest" v-if="!showModal">
    <v-row>
      <v-col cols="12">
        <div class="title">
          <span
            >Заявление
            <span v-if="form.request_id">№{{ form.request_id }}</span></span
          >
        </div>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12">
        <div class="inputContainer">
          <span class="label">Организация</span>
          <InputComponent
            disabled
            :value="form.subject.name"
          />
        </div>
      </v-col>
    </v-row>
    <v-row v-if="!!form.subject">
      <v-col cols="12">
        <div class="inputContainer">
          <span class="label">Вид заявления</span>
          <InputComponent
            disabled
            :value="form.approval_request_type.name"
          />
        </div>
      </v-col>
    </v-row>

    <v-row v-if="isShowcase">
      <v-col cols="12" md="6" lg="6" xl="6">
        <div class="inputContainer">
          <span class="label">Дата создания</span>
          <InputComponent disabled :value="form.request_date" />
        </div>
      </v-col>

      <v-col cols="12" md="6" lg="6" xl="6" v-if="form.statusId !== 1">
        <div class="inputContainer">
          <span class="label">Дата отправки на рассмотрение</span>
          <InputComponent disabled :value="form.dispatch_date" />
        </div>
      </v-col>
    </v-row>
    <v-row>
      <v-col
        cols="12"
        md="6"
        lg="6"
        xl="6"
        v-if="isShowcase && form.statusId !== 1"
      >
        <div class="inputContainer">
          <span class="label">Дата рассмотрения</span>
          <InputComponent disabled :value="form.approval_date" />
        </div>
      </v-col>

      <v-col cols="12" md="6" lg="6" xl="6">
        <div class="inputContainer">
          <span class="label">Статус</span>
          <InputComponent
            disabled
            :value="form.request_status ? form.request_status.name : 'Черновик'"
          />
        </div>
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12">
        <div class="containerTabs">
          <div class="tabs">
            <div
              :class="[{ active: tab === 'general' }, 'tab']"
              @click="clickTab('general')"
            >
              Общие сведения
            </div>
            <div
              :class="[{ active: tab === 'location' }, 'tab']"
              @click="clickTab('location')"
            >
              Места хранения
            </div>
          </div>
        </div>
      </v-col>
    </v-row>

    <div class="generalInformation" v-if="tab === 'general'">
    
      <CardGeneralInformation
        :information="form"
        :organizationInfo="organizationInfo"
        :isShowcase="isShowcase"
        :form="changesData"
      />
    </div>

    <div class="location" v-else>
      <CardLocationInformation
        :isShowcase="isShowcase"
        :status="form.request_status ? form.request_status.name : 'Черновик'"
        :information="form && form.elevator_register_application"
        :changesData="changesData"
        :form="changesData"
      />
    </div>

    <v-row>
      <v-col cols="12">
        <div class="buttons">
          <DefaultButton
            title="Вернуться назад"
            variant="primary"
            v-if="form.approval_request_type.value === 2"
            @click="$emit('hide')"
          >
          </DefaultButton>
          <DefaultButton
            title="Вернуться к списку заявлений"
            @click="$emit('close')"
          >
          </DefaultButton>
        </div>
      </v-col>
    </v-row>
  </v-container>
</template>

<script lang="ts">
import _ from "lodash";
import { Component, Vue, Prop } from "vue-property-decorator";
import CardGeneralInformation from "@/views/RegisterOrganizations/components/CardOrganization/components/GeneralInformation.vue";
import CardLocationInformation from "@/views/RegisterOrganizations/components/CardOrganization/components/LocationInformation.vue";
import ConfirmModalDelete from "@/views/Authorities/components/Modal/ConfirmModalDelete.vue";
import DefaultButton from "@/components/common/buttons/DefaultButton.vue";
import InputComponent from "@/components/common/inputs/InputComponent.vue";

type Props = {
  form: any;
  isShowcase: boolean;
  changesData: any;
}

@Component({
  name: "authorities-card-request-changes",
  components: {
    CardGeneralInformation,
    ConfirmModalDelete,
    CardLocationInformation,
    DefaultButton,
    InputComponent,
  },
  props: { form: Object, isShowcase: Boolean, changesData: Object },
})
export default class AuthoritiesCardRequestChanges extends Vue {

  @Prop({
    type: Object,
    default: () => {}
  })
  readonly form: Props['form'] | undefined;
  @Prop({
    type: Object,
    default: () => {}
  })
  readonly changesData: Props['changesData'] | undefined;
  @Prop({
    type: Boolean,
    default: () => false
  })
  readonly isShowcase: Props['isShowcase'] | undefined;

  tab: string = "general";
  information: any = {};
  subject: any = {};
  organizationInfo: any = {};
  organizations: any[] = [];
  showModal: boolean = false;

  clickTab(tab: string) {
    this.tab = tab;
  }

  async getInfoOrganization(id: number) {
    const data = await this.$store.dispatch(
      "organization/getInfoOrganization",
      id
    );
    return (this.organizationInfo = data);
  }

  created() {
    this.getInfoOrganization(this.form.subject.subject_id);
  }
}
</script>

<style lang="scss" scoped>
@import "@/assets/styles/_variables.scss";
@import "@/assets/styles/_mixins.scss";

.containerTabs {
  margin-top: 30px;
}

.tabs {
  border-bottom: 1px solid $light-grey-color;
  width: 100%;
  height: 25px;
  display: flex;
  flex-direction: row;
  text-transform: uppercase;
  margin-bottom: 0;
}
.tab {
  display: flex;
  font-weight: bold;
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
.elementsInput {
  margin-bottom: 15px;

  .label {
    display: inline-block;
  }
}

.title {
  font-style: normal;
  font-weight: 500;
  font-size: 24px;
  line-height: 24px;
  color: $black-color;

  @include respond-to("medium") {
    font-size: 22px;
  }

  @include respond-to("small") {
    font-size: 18px;
  }
}

.baseInformation {
  margin-top: 25px;

  @include respond-to("medium") {
    margin-top: 15px;
  }

  @include respond-to("small") {
    margin-top: 10px;
  }
}
.label {
  color: $input-border-color;
  font-size: 16px;
  line-height: 16px;
  margin-bottom: 5px;
  display: block;

  &--strong {
    color: $black-color;
    font-weight: 700;
  }
}

.input {
  @include respond-to("medium") {
    height: 40px;
    font-size: 16px;
  }

  @include respond-to("small") {
    height: 40px;
    font-size: 14px;
  }

  &--disabled {
    background-color: $input-disable-background;
    color: $input-disabled-color;
  }

  &--small {
    flex: 1 1 150px;
    margin-right: 15px;
    max-width: 150px;
  }

  &--large {
    flex: 1 1 100%;
  }
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

  @include respond-to("medium") {
    padding: 9px 25px;
  }

  @include respond-to("small") {
    padding: 5px 20px;
  }

  &:hover {
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
  }

  &--primary {
    border-color: $button-primary-background;
    background-color: $button-primary-background;
    color: $white-color;
  }
}

.select {
  height: 40px;
  width: 100%;

  &--lg {
    flex: 1 1 100%;
  }
}
</style>