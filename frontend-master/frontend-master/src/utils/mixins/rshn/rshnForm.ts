import { Model, Prop, Component, Mixins } from 'vue-property-decorator';
import { StatusEnum } from '@/utils/enums/RshnEnums';
import { RshnPrescriptionData } from '@/models/Rshn/Prescription/RshnPrescriptionData.vue';
import { RshnWithdrawalData } from '@/models/Rshn/Withdrawal/RshnWithdrawalData.vue';
import { ActionsMix } from '@/utils/mixins/actions';
import { RshnExpertiseData } from '@/models/Rshn/Expertise/RshnExpertiseData.vue';
import { rshnConsts } from '@/utils/consts/rshnConsts';
import { getElementById } from '@/utils/methodsForViews';

@Component
export class RshnFormMix extends Mixins(ActionsMix) {
  @Model('change', { type: Object, required: true }) value!:
    | RshnPrescriptionData
    | RshnWithdrawalData
    | RshnExpertiseData;
  @Prop({ type: Boolean, default: false }) detail!: boolean;
  @Prop({ type: Boolean, default: false }) create!: boolean;
  @Prop({ type: Boolean, default: false }) edit!: boolean;
  @Prop({ type: String, default: '' }) updateLink!: string;
  @Prop({ type: Boolean, default: true }) readonly isShow!: boolean;
  @Prop({ type: String, default: '' }) titleCreate!: string;
  @Prop({ type: String, default: '' }) titleDetail!: string;
  @Prop({ type: String, default: '' }) titleEdit!: string;

  measureId = 0;
  today = new Date();
  loading = false;
  status = StatusEnum;
  isSignatureModalOpen = false;
  isPrintModal = false;
  isOpenDialog = false;
  typeSignature!: StatusEnum | null;
  rshn = rshnConsts;
  optionDialog = {};
  afterUpdatePush = '';

  loadNestedDataAfterSignActions = false;
  signActionProcessed = true;

  get model(): any {
    return this.value;
  }

  set model(value: any) {
    this.$emit('change', value);
  }

  get subTitle() {
    return this.edit ? 'Редактирование' : this.model.status_translate;
  }

  get detailTitle() {
    return this.edit ? this.titleEdit : `${this.titleDetail} ${this.model.getNumber()}`;
  }

  get subjectId() {
    return this.$store.state.auth.user['subject']?.subject_id;
  }

  get showItem() {
    return this.model.show_apiendpoint;
  }

  async cancel(): Promise<void> {
    this.loading = true;
    await getElementById(this, this.model.id);
    this.loading = false;
    this.$emit('edit', false);
  }

  /**
   * Открытие модального окна для подписания основной сущности РСХН
   * @param type Статус
   * @param measureId Идентификатор сущности
   */
  async handleSignatureModalOpen(type: StatusEnum, measureId: number): Promise<void> {
    let service = '';
    this.measureId = measureId;
    this.typeSignature = type;
    switch (type) {
      case this.status.SUBSCRIBED:
        service = this.model.export_apiendpoint + '/progect';
        break;
      case this.status.CANCELED:
        service = this.model.export_canceled_apiendpoint;
        break;
      default:
        throw new Error('Unknown type service');
    }
    await this.$store.dispatch('agreementDocument/getNewOrStoredDocument', {
      measureId: this.measureId,
      service: service,
    });
    this.isSignatureModalOpen = true;
  }

  /**
   * Подписание основных сущностей РСХН
   */
  async handleSignApprove(): Promise<void> {
    this.isSignatureModalOpen = false;
    switch (this.typeSignature) {
      case this.status.SUBSCRIBED:
        await this.handleSignFromDescription(this.model.id, this.model.subscribe_service);
        break;
      case this.status.CANCELED:
        await this.handleSignFromDescription(this.model.id, this.model.cancel_service);
        break;
    }
  }

  callbackResponse(model) {
    this.value = this.model.createNewModel(model);
  }

  async handleSignFromDescription(id, service) {
    try {
      await this.$store.dispatch('agreementDocument/signDocumentFromDescription', {
        id,
        service,
      });

      const error = this.$store.state.agreementDocument.agreementDocumentSign.error;
      if (error) {
        throw new Error(error);
      }
    } catch (e) {
      this.$service.notify.push('error', { text: e as string });
    } finally {
      await getElementById(this, this.model.id);
      if (this.loadNestedDataAfterSignActions) {
        this.signActionProcessed = false;
      }
    }
  }

  async handleEdit(): Promise<void> {
    try {
      this.loading = true;
      if (!this.edit) {
        this.$emit('edit', true);
      } else {
        const { status, response } = await this.$store.dispatch(this.model.update_apiendpoint, {
          data: this.model.getDataForCreateOrUpdate(),
          id: this.model.id,
        });
        if (status) {
          this.$emit('edit', false);
          this.model = this.model.createNewModel(response);
          this.$notify({ group: 'rshn', type: 'success', title: 'Операция выполнена успешно' });
        }
      }
    } catch (_e) {
      this.$notify({ group: 'rshn', type: 'warning', title: 'Ошибка при выполнении операции' });
    } finally {
      this.loading = false;
    }
  }

  async handleCreate(): Promise<void> {
    this.model.operator_id = this.subjectId;
    const { response, status } = await this.$store.dispatch(this.model.create_apiendpoint, {
      data: this.model.getDataForCreateOrUpdate(),
    });
    if (status) await this.$router.push({ name: this.model.detail_link, params: { id: response.id } });
  }

  async handleDelete(): Promise<void> {
    const { status } = await this.$store.dispatch(this.model.delete_apiendpoint, {
      id: this.model.id,
    });
    if (status) await this.$router.push({ name: this.model.cancel_link });
  }
}
