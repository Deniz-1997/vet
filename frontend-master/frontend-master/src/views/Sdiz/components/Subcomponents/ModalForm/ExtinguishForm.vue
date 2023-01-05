<template>
  <v-row>
    <v-col cols="6">
      <label-component label="Дата погашения" />
      <text-component>{{ currentDate }}</text-component>
    </v-col>
    <v-col cols="6">
      <label-component label="Номер СДИЗ" />
      <text-component>{{ value.getSdizNumber() }}</text-component>
    </v-col>
    <v-col cols="6">
      <label-component label="Масса текущая, кг" />
      <text-component variant="span">{{ applyMask(currentMass, true) }}</text-component>
    </v-col>
    <v-col cols="12">
      <WeightInput
        v-model="amount_kg_extinguish_mask"
        label="Масса погашения, кг"
        @input="(v) => (amount_kg_extinguish = v)"
      />
    </v-col>
    <v-col cols="6">
      <label-component label="Непогашенная масса, кг" />
      <text-component variant="span">{{ applyMask(inextinguishedMass, true) }}</text-component>
    </v-col>
    <v-col v-show="isShipping && !isFullExtinguishWithNoMass" cols="12">
      <select-component
        v-model="amountTransportIds"
        :items="transportIds"
        is-multiple
        label="Транспорт"
        return-object
        placeholder="Выберите транспорт"
      />
    </v-col>

    <v-col class="mt-3" cols="12">
      <checkbox-component v-model="checkboxFullUse" class="float-left checkbox-v" label="Признак полного погашения" />
    </v-col>

    <v-col class="mt-3" cols="12">
      <checkbox-component
        v-model="createGpbo"
        class="float-left checkbox-v"
        label="Для производства продукции не подлежащей учету"
      />
    </v-col>

    <template v-if="showWarningFromExtinguishForm">
      <v-col cols="12">
        <select-request-component
          v-model="reason_id"
          :required="true"
          label="Причина расхождения"
          placeholder="Выберите причину списания"
          type="weight-disperancy-cause"
          is-id
        />
      </v-col>

      <v-col cols="12">
        <text-area-component
          v-model="note"
          label="Примечание"
          placeholder="Введите примечание (Максимум 250 символов)"
        />
      </v-col>
    </template>

    <v-col cols="12">
      <v-row class="ma-0">
        <v-col v-show="warning !== ''" class="text-right pt-0 pb-0" cols="12">
          <text-component class="orange--text mt-10">{{ warning }}</text-component>
        </v-col>

        <v-col cols="6">
          <button-component title="Отмена" @click="$emit('onClose')" />
        </v-col>
        <v-col class="text-right" cols="6">
          <button-component
            :loading="isLoading"
            :disabled="isDisabled"
            size="micro"
            title="Подписать"
            variant="primary"
            @click="initiateExtinguish"
          />
        </v-col>
      </v-row>
    </v-col>
  </v-row>
</template>

<script lang="ts">
import { Component, Model, Prop, Vue, Watch } from 'vue-property-decorator';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import ButtonComponent from '@/components/common/buttons/DefaultButton.vue';
import TextAreaComponent from '@/components/common/inputs/TextAreaComponent.vue';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import SelectComponent from '@/components/common/inputs/SelectComponent.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import TextComponent from '@/components/common/Text/Text.vue';
import LabelComponent from '@/components/common/Label/Label.vue';
import { SdizVueModel } from '@/models/Sdiz/Data/Sdiz.vue';
import CheckboxComponent from '@/components/common/inputs/CheckboxComponent.vue';
import { SdizGpbVueModel } from '@/models/Sdiz/Data/SdizGpb.vue';
import { applyMask, decimalNumberUnmask, validate } from '@/components/common/inputs/mask/decimalNumberMask';
import { currentDay } from '@/utils';
import { SdizExtinguishCreateVueModel } from '@/models/Sdiz/SdizExtinguishCreate';
import { sdizCurrentMass } from '@/utils/sdizCurrentMass';
import WeightInput from '@/views/Lot/components/Subcomponents/WeightInput.vue';
import { subtract } from '@/utils/decimals';

@Component({
  name: 'extinguish-form',
  components: {
    WeightInput,
    CheckboxComponent,
    DialogComponent,
    ButtonComponent,
    TextAreaComponent,
    SelectRequestComponent,
    SelectComponent,
    InputComponent,
    TextComponent,
    LabelComponent,
  },
})
export default class ExtinguishForm extends Vue {
  @Model('change', { type: Object, required: true }) readonly value!: SdizGpbVueModel | SdizVueModel;

  @Prop({ type: Boolean }) readonly is_gpb!: string;

  @Prop({ type: Number }) readonly operator_id!: number | null;

  @Prop({ type: Number }) readonly owner_id!: number | null;

  private isLoading = false;

  warning = '';

  // Масса погашения, кг. Вводится пользователем
  amount_kg_extinguish: number | null = null;

  amount_transport_id: number[] = [];

  reason_id: number | null = 0;

  note: string | null = '';

  checkboxFullUse = false;

  createGpbo = false;

  isGpbOut = false;

  showWarningFromExtinguishForm = false; // Показать дополнительные поля при расхождении веса

  decimalNumberUnmask = decimalNumberUnmask;

  applyMask = applyMask;

  amount_kg_extinguish_mask = '';

  model: any = this.innerValue;

  currentDate: string = currentDay();

  get inextinguishedMass(): number {
    let amk = this.amount_kg_extinguish ?? 0;

    return amk > this.currentMass ? 0 : subtract(this.currentMass, amk);
  }

  get innerValue() {
    return this.value;
  }

  set innerValue(value) {
    this.$emit('change', value);
  }

  get transportIds() {
    const docTransports = this.value.carriers.map((e) => e.doc_transports);
    const formatDocsTransport = (el: any) => ({
      value: el.id,
      text: el.number_tc + (el.number ? `, Контейнер: ${el.number}` : ''),
    });
    return docTransports.flat().map((e) => formatDocsTransport(e));
  }

  get isShipping() {
    return this.value.objects.operations.detail.shipping;
  }

  get isFullExtinguishWithNoMass() {
    return !this.amount_kg_extinguish && this.checkboxFullUse;
  }

  selectedTransportIds: any[] = [];

  get amountTransportIds() {
    return this.selectedTransportIds;
  }

  set amountTransportIds(val) {
    this.selectedTransportIds = val;
    this.amount_transport_id = val.map((e) => e.value);
  }

  get isDisabled(): boolean {
    this.warning = '';

    if ((this.amount_kg_extinguish === null || this.amount_kg_extinguish <= 0) && !this.checkboxFullUse) {
      this.warning = 'Укажите массу погашения';
      return true;
    }

    const amount_kg_extinguish = this.amount_kg_extinguish || 0;

    if (this.checkboxFullUse && (amount_kg_extinguish > this.currentMass || amount_kg_extinguish < this.currentMass)) {
      if (this.reason_id === null || this.reason_id === 0) {
        this.warning = 'Укажите причину';
        return true;
      }
    }
    if (!this.checkboxFullUse && amount_kg_extinguish > this.currentMass) {
      if (this.reason_id === null || this.reason_id === 0) {
        this.warning = 'Укажите причину';
        return true;
      }
    }
    if (
      this.isShipping &&
      !this.isFullExtinguishWithNoMass &&
      this.value.carriers.filter((e) => e.doc_transports?.length).length > 0 &&
      this.amount_transport_id.length === 0
    ) {
      this.warning = 'Укажите транспорт';
      return true;
    }

    return validate(this.amount_kg_extinguish_mask);
  }

  created() {
    this.amount_transport_id = [];
    this.reason_id = 0;
    this.note = '';
    this.checkboxFullUse = false;
    this.isGpbOut = false;
    this.amount_kg_extinguish = null;
  }

  get currentMass(): number {
    return sdizCurrentMass(this.value);
  }

  /** Проверяем флаг checkboxFullUse при погашении, этот флаг означает что юзер полностью гасит СДИЗ **/
  @Watch('amount_kg_extinguish')
  isShow() {
    let amount_kg_extinguish = this.amount_kg_extinguish === null ? 0 : this.amount_kg_extinguish;
    this.showWarningFromExtinguishForm = amount_kg_extinguish > this.currentMass;

    if (this.checkboxFullUse) {
      if (amount_kg_extinguish == this.currentMass) {
        return (this.showWarningFromExtinguishForm = false);
      }

      if (amount_kg_extinguish < this.currentMass) {
        return (this.showWarningFromExtinguishForm = true);
      }

      this.showWarningFromExtinguishForm = true;
    }
  }

  get weightDisperancy() {
    return (this.amount_kg_extinguish || 0) !== (this.currentMass || 0);
  }

  @Watch('checkboxFullUse')
  isShowWarn() {
    let amount_kg_extinguish = this.amount_kg_extinguish === null ? 0 : this.amount_kg_extinguish;

    if (this.checkboxFullUse && (amount_kg_extinguish > this.currentMass || amount_kg_extinguish < this.currentMass)) {
      this.showWarningFromExtinguishForm = true;
    }

    if (!this.checkboxFullUse && amount_kg_extinguish <= this.currentMass) {
      return (this.showWarningFromExtinguishForm = false);
    }

    if (amount_kg_extinguish > this.currentMass) {
      this.showWarningFromExtinguishForm = true;
    }
  }

  private initiateExtinguish() {
    const data = this.prepareExtinguishLotData();
    this.$emit('extinguish', data);
  }

  private prepareExtinguishLotData() {
    let data: SdizExtinguishCreateVueModel = new SdizExtinguishCreateVueModel({
      amount_kg: this.amount_kg_extinguish || 0,
      full_use: this.checkboxFullUse,
      create_gpbo: this.createGpbo,
    });

    if (this.showWarningFromExtinguishForm) data.full_use = true;

    if (this.isShipping && !this.isFullExtinguishWithNoMass) {
      data.transports_ids = this.amount_transport_id;
    }

    if (data.full_use && this.weightDisperancy) {
      data.reason_id = this.reason_id;
      data.note = this.note;
    }

    if (this.is_gpb) {
      data.gpb_sdiz_id = this.innerValue.id;
    } else {
      data.sdiz_id = this.innerValue.id;
    }

    return data;
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';

.checkbox::v-deep {
  .v-label {
    color: $webkit-color;
    font-size: 15px;
    font-weight: normal;
    line-height: 24px;
  }
}
</style>
