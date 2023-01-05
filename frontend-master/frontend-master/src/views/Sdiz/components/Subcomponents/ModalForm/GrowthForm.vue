<template>
  <v-row class="mt-3 ma-0">
    <v-col cols="6">
      <label-component label="Номер Партии" />
      <text-component variant="span">{{ growthForm.lot_number }}</text-component>
    </v-col>
    <v-col cols="6">
      <label-component label="Масса текущая, кг" />
      <text-component variant="span">{{ growthForm.amount_kg_lock }}</text-component>
    </v-col>
    <v-col cols="12">
      <label-component label="Масса доступная, кг" />
      <text-component variant="span">{{ growthForm.amount_kg_available }}</text-component>
    </v-col>
    <v-col cols="12">
      <WeightInput
        v-model="amount_kg_mask"
        label="Размер увеличения массы, кг"
        @input="(v) => (growthForm.amount_kg = v)"
      />
    </v-col>
    <v-col cols="12">
      <label-component label="Масса доступная после увеличения, кг" />
      <text-component variant="span">{{ amountKgAfter }}</text-component>
    </v-col>
    <v-col cols="12">
      <select-request-component
        v-model="growthForm.reason"
        :required="true"
        label="Причина возврата"
        placeholder="Выберите причину возврата"
        type="lot-return-reason"
      />
    </v-col>

    <v-col cols="12">
      <text-area-component
        v-model="growthForm.comment"
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
            :disabled="growthForm.amount_kg <= 0 && growthForm.amount_kg === null"
            size="micro"
            title="Подписать"
            variant="primary"
            @click="
              $emit('onSend', {
                sdiz_id: growthForm.sdiz_id,
                amount_kg: growthForm.amount_kg,
                reason_id: growthForm.reason,
                comment: growthForm.comment,
              })
            "
          />
        </v-col>
      </v-row>
    </v-col>
  </v-row>
</template>

<script lang="ts">
import { Component, Model, Watch } from 'vue-property-decorator';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import ButtonComponent from '@/components/common/buttons/DefaultButton.vue';
import TextAreaComponent from '@/components/common/inputs/TextAreaComponent.vue';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import TextComponent from '@/components/common/Text/Text.vue';
import LabelComponent from '@/components/common/Label/Label.vue';
import Sdiz from '@/views/Sdiz/Sdiz.vue';
import { SdizVueModel } from '@/models/Sdiz/Data/Sdiz.vue';
import WeightInput from '@/views/Lot/components/Subcomponents/WeightInput.vue';
import { subtract } from '@/utils/decimals';

export type GrowthFormType = {
  amount_kg_lock: number;
  amount_kg_after: number;
  amount_kg: number | null;
  comment: string;
  reason: number;
  lot_number: string | number | null;
  amount_kg_available: number;
  sdiz_id: number;
};

@Component({
  name: 'growth-form',
  components: {
    WeightInput,
    DialogComponent,
    ButtonComponent,
    TextAreaComponent,
    SelectRequestComponent,
    InputComponent,
    TextComponent,
    LabelComponent,
  },
})
export default class GrowthForm extends Sdiz {
  @Model('change', { type: SdizVueModel, required: true }) readonly value!: SdizVueModel;
  amount_kg_mask = '';

  growthForm: GrowthFormType = {
    amount_kg_lock: 0,
    amount_kg_after: 0,
    amount_kg: null,
    comment: '',
    reason: 0,
    lot_number: 0,
    amount_kg_available: 0,
    sdiz_id: 0,
  };

  get amountKgAfter() {
    if (this.growthForm.amount_kg !== null) {
      return subtract(this.growthForm.amount_kg_after, this.growthForm.amount_kg);
    } else {
      return this.growthForm.amount_kg_after;
    }
  }

  @Watch('growthForm.amount_kg')
  maxAndMinNumber(): void {
    if (this.growthForm.amount_kg !== null) {
      if (this.growthForm.amount_kg > this.growthForm.amount_kg_lock || this.growthForm.amount_kg < 0) {
        this.growthForm.amount_kg = null;
      }
    }
  }

  created() {
    this.growthForm.sdiz_id = Number(this.value.id);
    this.growthForm.amount_kg_lock = Number(this.value.objects.lot.amount_kg);
    this.growthForm.amount_kg_available = Number(this.value.objects.lot.amount_kg_available);
    this.growthForm.lot_number = this.value.objects.lot.lot_number;
    this.growthForm.amount_kg_after = Number(this.value.objects.lot.amount_kg_available);
  }
}
</script>
