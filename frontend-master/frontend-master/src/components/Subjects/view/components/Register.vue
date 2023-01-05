<template>
  <div class="address">
    <v-row v-if="form.registers && form.registers.laboratory && form.headSubject">
      <v-col cols="12">
        <InputComponent
          :value="form.headSubject.name"
          name="headSubject"
          placeholder="Выберите головную организацию"
          label="Головная организация"
          disabled
        />
      </v-col>
    </v-row>
    <v-row>
      <v-col v-if="form.registry_inclusion_date && form.subjectType !== 'UL'" cols="12" md="12" lg="6" xl="6">
        <InputComponent v-model="form.registry_inclusion_date" label="Дата и время включения в реестр" disabled />
      </v-col>

      <v-col v-if="form.exclusion_reason" cols="12" md="12" lg="6" xl="6">
        <InputComponent v-model="form.registry_exclusion_date" label="Дата и время аннулирования" disabled />
      </v-col>
    </v-row>

    <v-row>
      <v-col v-if="form.exclusion_reason" cols="12">
        <InputComponent v-model="form.exclusion_reason.name" label="Причина аннулирования" disabled />
      </v-col>
    </v-row>

    <v-row v-if="form.registers.manufacturer">
      <v-col cols="12">
        <div class="elementsInput checkbox-block mb-5 elementsInput--disabled">
          <label class="checkbox">
            <input id="auto_in" v-model="form.isProcessor" type="checkbox" name="auto_in" disabled />
            <span class="checkbox__icon">
              <img src="/icons/checkbox.svg" />
            </span>
          </label>
          <span class="label">
            Организация, осуществляющая первичную и (или) последующую (промышленную) переработку зерна
          </span>
        </div>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12">
        <div class="elementsInput checkbox-block elementsInput--disabled">
          <label class="checkbox">
            <input id="auto_in" type="checkbox" name="auto_in" :checked="form.registers.manufacturer" disabled />
            <span class="checkbox__icon">
              <img src="/icons/checkbox.svg" />
            </span>
          </label>
          <span class="label">Реестр товаропроизводителей</span>
        </div>
      </v-col>
      <v-col cols="12">
        <div class="elementsInput checkbox-block elementsInput--disabled">
          <label class="checkbox">
            <input id="auto_in" type="checkbox" name="auto_in" :checked="form.registers.laboratory" disabled />
            <span class="checkbox__icon">
              <img src="/icons/checkbox.svg" />
            </span>
          </label>
          <span class="label">Реестр лабораторий</span>
        </div>
      </v-col>
      <v-col cols="12">
        <div class="elementsInput checkbox-block elementsInput--disabled">
          <label class="checkbox">
            <input id="auto_in" type="checkbox" name="auto_in" :checked="form.registers.ogv" disabled />
            <span class="checkbox__icon">
              <img src="/icons/checkbox.svg" />
            </span>
          </label>
          <span class="label">Реестр органов государственной власти (ОГВ)</span>
        </div>
      </v-col>
    </v-row>
  </div>
</template>

<script lang="ts">
import { Component, Vue, Prop } from 'vue-property-decorator';
import InputComponent from '@/components/common/inputs/InputComponent.vue';

/**ToDo: У форм прописать полную типизацию */
type Props = {
  form: any;
};

@Component({
  name: 'card-register',
  components: { InputComponent },
})
export default class CardRegister extends Vue {
  @Prop({ type: Object, default: () => ({}) }) readonly form!: Props['form'];
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';
.elementsInput {
  &--disabled {
    color: $silver-color;

    .checkbox__icon {
      cursor: initial;
    }

    [type='checkbox']:checked + .checkbox__icon {
      background-color: $silver-color;
      border-color: $silver-color;
    }
  }
}
</style>
