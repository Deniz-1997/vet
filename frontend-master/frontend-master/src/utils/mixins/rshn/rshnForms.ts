import Vue from 'vue';
import { Model, Prop, Component } from 'vue-property-decorator';
import { ExpertiseEnum, WithdrawalTypeEnum } from '@/utils/enums/RshnEnums';
import { RshnPrescriptionData } from '@/models/Rshn/Prescription/RshnPrescriptionData.vue';
import { RshnWithdrawalData } from '@/models/Rshn/Withdrawal/RshnWithdrawalData.vue';
import { RshnExpertiseData } from '@/models/Rshn/Expertise/RshnExpertiseData.vue';
import isUndefined from 'lodash/isUndefined';
import { rshnConsts } from '@/utils/consts/rshnConsts';

@Component
export class RshnFormsMix extends Vue {
  @Model('change', { type: Object, required: true }) value!:
    | RshnPrescriptionData
    | RshnWithdrawalData
    | RshnExpertiseData;
  @Prop({ type: Boolean, default: false }) detail!: boolean;
  @Prop({ type: Boolean, default: false }) edit!: boolean;
  @Prop({ type: Boolean, default: false }) create!: boolean;
  @Prop({ type: Number, default: null }) type!: ExpertiseEnum | WithdrawalTypeEnum;
  @Prop({ type: Boolean, default: true }) signActionProcessed!: boolean;
  typeTab = WithdrawalTypeEnum;
  isOpenModal = false;
  rshn = rshnConsts;

  today = new Date();

  get model(): any {
    return this.value;
  }

  set model(value: any) {
    this.$emit('change', value);
  }

  get disabledForm(): boolean {
    if (this.edit) return false;
    return this.detail;
  }

  onShowModal(value: boolean | undefined) {
    this.isOpenModal = isUndefined(value) ? !this.isOpenModal : value;
  }

  selectItem() {
    this.model.gw_id = this.model.withdrawal.id;
  }

  async loadWithdrawalById(id): Promise<void> {
    const { status, response } = await this.$store.dispatch(this.model.withdrawal.show_apiendpoint, id);
    if (!status || response.length === 0) {
      throw new Error('Not found element');
    }
    this.model.withdrawal = new RshnWithdrawalData(response);
  }
}
