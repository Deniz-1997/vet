import Vue from 'vue';
import { Model, Prop, Component } from 'vue-property-decorator';
import { StatusEnum } from '@/utils/enums/RshnEnums';
import { RshnPrescriptionData } from '@/models/Rshn/Prescription/RshnPrescriptionData.vue';
import { RshnWithdrawalData } from '@/models/Rshn/Withdrawal/RshnWithdrawalData.vue';
import { RshnExpertiseData } from '@/models/Rshn/Expertise/RshnExpertiseData.vue';

@Component
export class RshnButtonsMix extends Vue {
  @Model('change', { type: Object, required: true }) value!:
    | RshnPrescriptionData
    | RshnWithdrawalData
    | RshnExpertiseData;
  @Prop({ type: Boolean, default: false }) detail!: boolean;
  @Prop({ type: Boolean, default: false }) edit!: boolean;
  @Prop({ type: Boolean, default: false }) create!: boolean;
  @Prop({ type: Boolean, default: true }) readonly isShow!: boolean;
  @Prop({ type: Boolean, default: false }) loading!: boolean;
  @Prop({ type: String, default: 'Вы точно хотите удалить запись?' }) deleteTitle!: string;
  status = StatusEnum;

  get model(): any {
    return this.value;
  }

  set model(value: any) {
    this.$emit('change', value);
  }

  get isStatusCreated() {
    return this.model.status_id === this.status.CREATE && !this.edit;
  }

  get isStatusCreatedAndEdit() {
    return this.model.status_id === this.status.CREATE && this.edit;
  }

  get titleSave() {
    return this.edit ? 'Сохранить' : 'Редактировать';
  }

  get error() {
    return this.model.getErrors().length ? this.model.getErrors()[0] : null;
  }

  get isStatusSubscribed() {
    return this.model.status_id === this.status.SUBSCRIBED;
  }
}
