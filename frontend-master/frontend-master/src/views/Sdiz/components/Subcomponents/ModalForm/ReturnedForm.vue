<template>
  <v-row>
    <v-col cols="6">
      <label-component label="Номер СДИЗ" />
      <text-component>{{ sdizNumber }}</text-component>
    </v-col>
    <v-col cols="6">
      <label-component label="Масса текущая, кг" />
      <text-component variant="span">{{ applyMask(currentMass, true) }}</text-component>
    </v-col>

    <v-col cols="12">
      <WeightInput
        v-model="returned_amount_kg_mask"
        label="Масса партии отказа погашения, кг"
        @input="(v) => (returned_amount_kg = v)"
      />
    </v-col>

    <v-col cols="12">
      <label-component label="Непогашенная масса, кг" />
      <text-component variant="span">{{ applyMask(availableMass, true) }}</text-component>
    </v-col>

    <v-col cols="12">
      <select-request-component
        v-model="reason"
        :required="true"
        label="Причина отказа погашения"
        placeholder="Выберите причину отказа погашения"
        type="lot-return-reason"
        is-id
      />
    </v-col>

    <v-col cols="12">
      <text-area-component
        v-model="comment"
        :required="true"
        label="Примечание"
        placeholder="Введите примечание (Максимум 250 символов)"
      />
    </v-col>

    <v-col cols="12">
      <v-row class="ma-0">
        <v-col cols="6">
          <button-component title="Отмена" @click="$emit('onClose')" />
        </v-col>
        <v-col class="text-right" cols="6">
          <button-component
            :disabled="isSignDisabled"
            :loading="isLoading"
            size="micro"
            title="Подписать"
            variant="primary"
            @click="initiateReturn"
          />
        </v-col>
      </v-row>
    </v-col>
  </v-row>
</template>

<script lang="ts">
import { Component, Model, Vue } from 'vue-property-decorator';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import ButtonComponent from '@/components/common/buttons/DefaultButton.vue';
import TextAreaComponent from '@/components/common/inputs/TextAreaComponent.vue';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import TextComponent from '@/components/common/Text/Text.vue';
import LabelComponent from '@/components/common/Label/Label.vue';
import CheckboxComponent from '@/components/common/inputs/CheckboxComponent.vue';
import { SdizGpbVueModel } from '@/models/Sdiz/Data/SdizGpb.vue';
import { SdizVueModel } from '@/models/Sdiz/Data/Sdiz.vue';
import { applyMask, validate } from '@/components/common/inputs/mask/decimalNumberMask';
import { sdizCurrentMass } from '@/utils/sdizCurrentMass';
import WeightInput from '@/views/Lot/components/Subcomponents/WeightInput.vue';
import { subtract } from '@/utils/decimals';

@Component({
  name: 'returned-form',
  components: {
    WeightInput,
    CheckboxComponent,
    DialogComponent,
    ButtonComponent,
    TextAreaComponent,
    SelectRequestComponent,
    InputComponent,
    TextComponent,
    LabelComponent,
  },
})
export default class ReturnedForm extends Vue {
  @Model('change', { type: Object, required: true }) readonly value!: SdizGpbVueModel | SdizVueModel;

  model: any = this.innerValue;
  isLoading = false;
  comment = '';

  // Введенная пользователем масса отказа погашения
  returned_amount_kg_mask = '';

  returned_amount_kg = 0;

  reason = 0;
  lot_number: string | number | null = this.innerValue.getObjectLot().getLotNumber();

  applyMask = applyMask;

  get innerValue() {
    return this.value;
  }

  set innerValue(value) {
    this.$emit('change', value);
  }

  prepareReturnLot() {
    const { returned_amount_kg, reason, comment } = this;

    const data: any = {
      amount_kg: returned_amount_kg,
      reason_id: reason,
      comment: comment,
    };

    if (this.isGpb) {
      data.gpb_sdiz_id = this.innerValue.id;
    } else {
      data.sdiz_id = this.innerValue.id;
    }

    return data;
  }

  async initiateReturn(): Promise<void> {
    const data = this.prepareReturnLot();
    this.$emit('return', data);
  }

  get availableMass(): number {
    let amk = this.returned_amount_kg ?? 0;
    return amk > this.currentMass ? 0 : subtract(this.currentMass, amk);
  }

  get isSignDisabled() {
    const { returned_amount_kg, reason, comment } = this;
    return returned_amount_kg <= 0 || reason === 0 || comment === '' || validate(this.returned_amount_kg_mask);
  }

  get currentMass(): number {
    return sdizCurrentMass(this.value);
  }

  get isGpb() {
    return this.value.component_name === 'sdiz_gpb';
  }

  get sdizNumber() {
    return this.innerValue.getSdizNumber();
  }

  created() {
    this.lot_number = this.model.getObjectLot().getLotNumber();
  }
}
</script>
