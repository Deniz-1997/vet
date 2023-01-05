<template>
  <div>
    <Dialog-component
      v-if="form.subjectType"
      v-model="innerValue"
      :prompt="false"
      cancel-title=""
      confirm-title=""
      width="800"
      with-close-icon
      controls-justify="justify-end"
    >
      <template #title>
        <span v-if="idCard">Данные организации</span>
        <span v-if="!idCard">Добавление новой организации</span>
      </template>
      <template #content>
        <v-row>
          <v-col cols="12">
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
        <component
          :is="component"
          :form="form"
          :is-edit="isEdit"
          :is-loading="isLoading"
          @save="saveOrganization"
          @close="$emit('close')"
        />
      </template>
    </Dialog-component>

    <v-overlay :value="isLoading">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
  </div>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';
import RadioGroupComponent from '@/components/common/inputs/RadioGroupComponent.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import { addressNew } from '@/utils/global/filters';
import { ESubjectType } from '@/services/enums/subject';
import { TMapperPlain } from '@/services/models/common';
import { ManufacturerItemIn } from '@/services/mappers/manufacturer';
import LegalEntitiesForm from './LegalEntitiesForm.vue';
import LegalEntitiesPersonForm from './LegalEntitiesPersonForm.vue';
import IndividualEntrepreneurForm from './IndividualEntrepreneurForm.vue';
import AccreditedRepresentativeOfficeForm from './AccreditedRepresentativeOfficeForm.vue';

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
  name: 'manufacturers-card',
  components: {
    RadioGroupComponent,
    DialogComponent,
    LegalEntitiesForm,
    LegalEntitiesPersonForm,
    IndividualEntrepreneurForm,
    AccreditedRepresentativeOfficeForm,
  },
  methods: { addressNew },
})
export default class ManufacturersOrganization extends Vue {
  @Prop({ type: Boolean }) readonly value;
  @Prop({ type: [String, Number], default: '' }) readonly idCard;
  @Prop({ type: Boolean }) readonly autoSelect;

  isLoading = true;
  isEdit = false;
  form: Partial<TMapperPlain<ManufacturerItemIn>> = getForm();

  get innerValue() {
    return this.value;
  }

  set innerValue(value) {
    this.$emit('input', value);
  }

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
        await this.$service.manufacturer.update({
          ...this.form,
          ...data,
        });
        this.$emit('close');
        this.isEdit = false;
      } else {
        if (this.autoSelect) {
          this.$emit('autoSelect');
        }
        await this.$service.manufacturer.create({
          ...this.form,
          ...data,
        });
        this.$emit('close');
      }
      this.isLoading = false;
    } catch (_error) {
      this.isLoading = false;
    }
  }
}
</script>

<style lang="scss" scoped>
.col-exclude {
  display: flex;
  justify-content: flex-end;
}

.ul-radio {
  padding-top: 8px;
}

.span-list {
  display: flex;
  flex-wrap: nowrap;
}

.checkbox {
  padding-top: 4px;
}

.checkbox-title {
  padding-left: 4px;
}
</style>
