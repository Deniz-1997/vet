<template>
  <div class="cardTemplate">
    <div class="container">
      <v-row>
        <v-col cols="12">
          <div class="title">
            <span
              >Карточка шаблона <span v-if="value.id">№{{ value.id }}</span></span
            >
          </div>
        </v-col>
      </v-row>
      <div class="baseInformation">
        <v-row>
          <v-col cols="12">
            <div class="inputContainer">
              <InputComponent v-model="value.name" :disabled="isView" label="Наименование" />
            </div>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12">
            <div class="inputContainer">
              <InputComponent v-if="isView" v-model="value.type.name" :disabled="isView" label="Объект" />
              <SelectComponent
                v-else
                v-model="value.type"
                return-object
                item-value="code"
                item-text="name"
                label="Объект"
                :items="typeTemplate"
              />
            </div>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12">
            <div class="inputContainer">
              <InputComponent disabled label="Статус" :value="status" />
            </div>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12">
            <div class="inputContainer">
              <InputComponent v-model="value.created" disabled label="Дата и время создания" />
            </div>
          </v-col>
        </v-row>
        <v-row v-if="value.deletionDate">
          <v-col cols="12">
            <div class="inputContainer">
              <InputComponent v-model="value.deletionDate" :disabled="isView" label="Дата и время удаления" />
            </div>
          </v-col>
        </v-row>

        <v-row>
          <v-col cols="12">
            <div class="inputContainer">
              <EditableTable
                v-model="value.stages"
                title="Этапы"
                :options="headers"
                :max="999"
                :is-showcase="isView"
                :hide-actions="isView"
                edit-strategy="outer"
                :map-before-remove="true"
                :is-edit-action="false"
                @mappingForm="removeItem"
                @edit="showStepForm"
              />
            </div>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12">
            <div class="buttons">
              <DefaultButton title="Закрыть" @click="clickClose" />
              <DefaultButton
                v-if="!isView"
                title="Сохранить"
                variant="primary"
                :disabled="isDisabled"
                @click="() => $emit('create')"
              />
            </div>
          </v-col>
        </v-row>
      </div>

      <StepForm
        v-if="!!storage"
        :is-view-type="isView"
        :storage="storage"
        :information="value"
        @click-close="handleCloseForm"
        @submit="(v) => handleSubmit(v, storage.index)"
      />
    </div>
  </div>
</template>

<script lang="ts">
import { Component, Vue, Prop } from 'vue-property-decorator';
import DataTable from '@/components/common/DataTable/DataTable.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import SelectComponent from '@/components/common/inputs/SelectComponent.vue';
import EditableTable from '@/components/common/Table/index.vue';
import StepForm from './components/StepForm.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';

@Component({
  name: 'authorities-card-template',
  components: { InputComponent, DataTable, SelectComponent, EditableTable, StepForm, DefaultButton },
})
export default class AuthoritiesCardTemplate extends Vue {
  @Prop({ type: Object, default: {} }) public value!: any;
  @Prop({ type: Boolean, default: false }) public isView!: boolean;
  typeTemplate: any[] = [];
  storage: any = null;
  listOrganization: any[] = [];
  showForm = false;
  isDisabled = true;
  headers = [
    {
      label: 'Номер',
      name: 'number',
      width: 100,
    },
    {
      label: 'Наименование',
      name: 'name',
      width: 250,
    },
    {
      label: 'Организация',
      name: 'subject_name',
      width: 250,
    },
    {
      label: 'Подразделение',
      name: 'subject_division_name',
      width: 250,
    },
    {
      label: 'Срок, дни',
      name: 'decision_period_days',
      width: 75,
    },
  ];

  get status(): string {
    return this.value?.status?.name ?? 'Новый';
  }

  updated() {
    if (this.value) {
      if (this.value.stages && this.value.name && this.value.type) {
        this.isDisabled = false;
      }
    }
  }

  created() {
    this.fetchTypeTemplate();
  }

  checkFieldForStep(elem) {
    if (!elem.approvalPeriodDays) {
      // TODO: WTF?
    }
  }

  showStepForm(index: number) {
    this.storage = {
      data: (this.value.stageList || []).find((_, i) => i === index),
      form: this.value,
      index,
    };
  }

  handleCloseForm() {
    this.storage = null;
  }

  handleSubmit(form) {
    this.value.stages = [...(this.value.stages || []), form];
    this.value.stages = this.value.stages.map((item, index) => {
      return {
        ...item,
        number: index + 1,
        approvalPeriodDays: Number(item.approvalPeriodDays) ? Number(item.approvalPeriodDays) : 2,
      };
    });
    this.handleCloseForm();
  }

  removeItem() {
    this.$emit('input', {
      ...this.value,
      stages: this.value.stages.map((item, index) => {
        return {
          ...item,
          number: index + 1,
        };
      }),
    });
  }

  async fetchTypeTemplate() {
    const data = await this.$store.dispatch('templateApproval/getListTypes');
    this.typeTemplate = data;
  }

  clickClose() {
    this.$emit('close');
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';

.label {
  color: $medium-grey-color;
  font-size: 13px;
  line-height: 16px;
  margin-bottom: 5px;
  display: block;

  &--strong {
    color: $black-color;
    font-weight: 700;
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

  @include respond-to('medium') {
    padding: 9px 25px;
  }

  @include respond-to('small') {
    padding: 5px 20px;
  }

  &:hover {
    box-shadow: 0 0 5px rgb(0 0 0 / 50%);
  }

  &--primary {
    border-color: $button-primary-background;
    background-color: $button-primary-background;
    color: $white-color;
  }
}
</style>
