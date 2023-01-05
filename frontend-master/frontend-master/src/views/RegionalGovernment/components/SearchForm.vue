<template>
  <div class="mt-4 mb-8">
    <v-row>
      <v-col>
        <span class="title d-flex align-center">{{ currentMode.title }}</span>
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="3" xl="3" lg="3" md="6" sm="12">
        <InputComponent
          v-model="form.number"
          :label="currentMode.numberLabel"
          :placeholder="currentMode.numberPlaceholder"
        />
      </v-col>

      <v-col cols="3" xl="3" lg="3" md="6" sm="12">
        <UiDateInput
          v-model="form.date"
          :format="'DD.MM.YYYY'"
          label="Дата оформления"
          placeholder="Укажите дату оформления документа"
          :limit-to="today"
        />
      </v-col>

      <v-col cols="3" xl="3" lg="3" md="6" sm="12">
        <WeightInput v-model="amountKgMask" label="Масса" placeholder="Введите массу, указанную в документе" />
      </v-col>

      <v-col cols="2" class="d-flex align-end">
        <ButtonComponent
          title="Поиск"
          variant="primary"
          :loading="isLoading"
          :disabled="error !== null"
          @click="$emit('search')"
        />
      </v-col>
    </v-row>

    <v-row>
      <v-col v-show="error !== null" cols="10" xl="10" lg="10" md="12" sm="12" class="right-align mr-3">
        <text-component class="text-caption text-center orange--text" variant="span">
          {{ error }}
        </text-component>
      </v-col>
    </v-row>
  </div>
</template>

<script lang="ts">
import { Model, Component, Prop, Vue } from 'vue-property-decorator';
import { PropType } from 'vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import UiDateInput from '@/components/global/UiForm/components/UiDateInput.vue';
import ButtonComponent from '@/components/common/buttons/DefaultButton.vue';
import { RegionalGovernmentMode, RegionalGovernmentForm } from '@/utils/mixins/regionalGovernment';
import TextComponent from '@/components/common/TextComponent.vue';
import { decimalNumberMask, decimalNumberUnmask } from '@/components/common/inputs/mask/decimalNumberMask';
import WeightInput from '@/views/Lot/components/Subcomponents/WeightInput.vue';

@Component({
  name: 'regional-government-filters',
  components: { WeightInput, TextComponent, ButtonComponent, InputComponent, UiDateInput },
})
export default class RegionalGovernmentFilters extends Vue {
  @Model('change', { type: Object as PropType<RegionalGovernmentForm>, required: true }) form!: RegionalGovernmentForm;
  @Prop({ required: true, type: String as PropType<RegionalGovernmentMode> }) mode!: RegionalGovernmentMode;
  @Prop({ type: Boolean, default: false }) isLoading!: boolean;

  today = new Date();
  decimalNumberMask = decimalNumberMask;
  amount_kg_mask = '';

  options = {
    sdiz: {
      title: 'Поиск СДИЗ',
      numberLabel: 'Номер СДИЗ',
      numberPlaceholder: 'Введите номер СДИЗ',
    },
    lot: {
      title: 'Поиск партий зерна',
      numberLabel: 'Номер партии зерна',
      numberPlaceholder: 'Введите номер партии зерна',
    },
    implementation: {
      title: 'Поиск сведений о собранном урожае',
      numberLabel: 'Номер документа',
      numberPlaceholder: 'Введите номер документа',
    },
  };

  get amountKgMask() {
    return this.amount_kg_mask;
  }

  set amountKgMask(v) {
    this.amount_kg_mask = v;
    this.form.amount_kg = decimalNumberUnmask(v);
  }

  getErrors(): string[] {
    const errors: string[] = [];

    if (!this.form.number) errors.push('Укажите номер документа');
    if (!this.form.date) errors.push('Укажите дату формирования документа');
    if (!this.form.amount_kg) errors.push('Введите массу, указанную в документе');

    return errors;
  }

  get error(): string | null {
    const errors = this.getErrors();
    return errors.length ? errors[0] : null;
  }

  get currentMode() {
    return this.options[this.mode];
  }
}
</script>
