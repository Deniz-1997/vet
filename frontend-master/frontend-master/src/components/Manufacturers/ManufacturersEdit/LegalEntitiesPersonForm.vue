<template>
  <UiForm
    :rules="rules"
    data-qa="legal_entities_person__form"
    @validation="(v) => (isValid = v.isValid)"
    @submit="$emit('save', innerForm)"
  >
    <v-row v-if="!step || step === 'general'">
      <v-col cols="12">
        <UiControl name="name" :value="innerForm.name">
          <InputComponent
            id="organizationName"
            v-model="innerForm.name"
            placeholder="Введите текст"
            label="Наименование"
            :disabled="innerForm.isEsia"
          />
        </UiControl>
      </v-col>
    </v-row>

    <v-row v-if="!step || step === 'register'">
      <v-col cols="12">
        <span class="span-list">
          <span>
            <label class="checkbox">
              <input v-model="innerForm.isProcessor" type="checkbox" :value="innerForm.isProcessor" />
              <span class="checkbox__icon">
                <img src="/icons/checkbox.svg" />
              </span>
            </label>
          </span>
          <span class="checkbox-title">
            Организация, осуществляющая первичную и (или) последующую (промышленную) переработку зерна</span
          >
        </span>
      </v-col>
    </v-row>

    <v-row v-if="!step || step === 'register'" justify="end">
      <v-col cols="12" class="col-exclude">
        <DefaultButton variant="primary" type="submit" :disabled="isLoading" title="Сохранить" />
      </v-col>
    </v-row>
  </UiForm>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';
import cloneDeep from 'lodash/cloneDeep';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import { ManufacturerItemIn } from '@/services/mappers/manufacturer';
import { TMapperPlain } from '@/services/models/common';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';

@Component({
  name: 'legal-entities-person-form',
  components: {
    InputComponent,
    DefaultButton,
  },
})
export default class LegalEntitiesPersonForm extends Vue {
  @Prop({ type: Object }) readonly form;
  @Prop({ type: Boolean }) readonly isEdit;
  @Prop({ type: Boolean, default: false }) readonly isLoading?: boolean;
  @Prop({ type: String }) readonly step?: string; /** Шаг формы. Если не задан, то отображается вся форма */

  innerForm: Partial<TMapperPlain<ManufacturerItemIn>> = {};

  get rules() {
    return {
      name: 'required',
    };
  }

  created() {
    this.innerForm = cloneDeep(this.form);
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
