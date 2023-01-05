import { Model, Prop, Component, Mixins } from 'vue-property-decorator';
import { ActionsMix } from '@/utils/mixins/actions';
import { RshnPrescriptionData } from '@/models/Rshn/Prescription/RshnPrescriptionData.vue';
import { RshnWithdrawalData } from '@/models/Rshn/Withdrawal/RshnWithdrawalData.vue';
import { StatusEnum } from '@/utils/enums/RshnEnums';
import { RestrictionsData } from '@/models/Rshn/Withdrawal/RestrictionsData';
import { PrescriptionDocData } from '@/models/Rshn/Prescription/PrescriptionDocData';
import ActivitiesWithdrawal from '@/views/rshn/subcomponents/Tables/ActivitiesWithdrawal.vue';
import ActivitiesPrescription from '@/views/rshn/subcomponents/Tables/ActivitiesPrescription.vue';
import { getElementById } from '@/utils/methodsForViews';

type GetActionButtonsOptions = {
  isDetailButton: boolean;
  isEditButton: boolean;
};

@Component
export class RshnActivity extends Mixins(ActionsMix) {
  @Model('change', { type: Object, required: true }) value!: RshnPrescriptionData | RshnWithdrawalData;
  @Prop({ type: Boolean, default: false }) edit!: boolean;
  @Prop({ type: Boolean, default: false }) detail!: boolean;
  @Prop({ type: Array, default: [] }) tabList!: [];
  updateLink = '';
  showItem = '';
  typeSignature: StatusEnum | null = null;
  status = StatusEnum;
  isLoading = false;
  isSignatureModalOpen = false;
  measureId: number | null = null;
  /** Сущность для подписи*/
  docToSign: any = null;

  get model(): any {
    return this.value;
  }

  set model(value: any) {
    this.$emit('change', value);
  }

  getButton(params: { name: string; action: string; style?: string }) {
    return `<span class="v-chip v-chip--label theme--light v-size--default btn-canceled-chip mx-1" style="${
      params.style || 'height: 25px; cursor: pointer'
    }"><span data-action="${params.action}" class="v-chip__content">${params.name}</span></span>`;
  }

  // eslint-disable-next-line max-lines-per-function
  getActionButtons(
    thisRef: ActivitiesWithdrawal | ActivitiesPrescription,
    options: GetActionButtonsOptions = { isDetailButton: false, isEditButton: false }
  ) {
    return {
      label: '',
      name: 'action',
      onClick: (model: RestrictionsData | PrescriptionDocData) => {
        window.addEventListener(
          'click',
          async function (e: any) {
            if (e.target && model) {
              if (e.target.dataset.action === 'sign') {
                await thisRef.handleSignatureModalOpen(StatusEnum.SUBSCRIBED, model.export_apiendpoint, model);
              } else if (e.target.dataset.action === 'edit') {
                thisRef.$emit('edit', model);
              } else if (e.target.dataset.action === 'delete') {
                await thisRef.handleDelete(model);
              } else if (e.target.dataset.action === 'detail') {
                await thisRef.handleDetail(model);
              } else if (e.target.dataset.action === 'cancel') {
                await thisRef.handleSignatureModalOpen(StatusEnum.CANCELED, model.export_canceled_apiendpoint, model);
              }
            }
          },
          { once: true }
        );
      },
      customRenderValue: (model: any): string | number | void => {
        const styleForSignActions = 'background: #d19b3f; height: 25px; color: white; cursor: pointer';

        const buttonDetail = thisRef.getButton({ name: 'Просмотр', action: 'detail' });

        if (model.status_id === StatusEnum.CREATE) {
          const buttonDelete = thisRef.getButton({ name: 'Удалить', action: 'delete' });
          const buttonEdit = thisRef.getButton({ name: 'Редактировать', action: 'edit' });
          const buttonSign = thisRef.getButton({ name: 'Подписать', action: 'sign', style: styleForSignActions });
          return (
            buttonDelete +
            (options.isEditButton ? buttonEdit : '') +
            (options.isDetailButton ? buttonDetail : '') +
            buttonSign
          );
        }

        if (model.status_id === StatusEnum.SUBSCRIBED) {
          const cancelButton = thisRef.getButton({
            name: 'Аннулировать',
            action: 'cancel',
            style: styleForSignActions,
          });
          return (options.isDetailButton ? buttonDetail : '') + cancelButton;
        } else {
          return options.isDetailButton ? buttonDetail : '';
        }
      },
    };
  }

  /**
   * Открытие модального окна подписания зависимых сущностей РСХН
   * @param type Тип конечного статуса
   * @param service Сервис экспорта PDF
   * @param docToSign Сущность для подписи
   */
  async handleSignatureModalOpen(type: StatusEnum, service: string, docToSign: any): Promise<void> {
    this.typeSignature = type;
    this.docToSign = docToSign;
    await this.$store.dispatch('agreementDocument/getNewOrStoredDocument', {
      measureId: docToSign.id,
      service: service + (type === StatusEnum.SUBSCRIBED ? '/progect' : ''),
    });
    this.isSignatureModalOpen = true;
  }

  handleSignatureModalClose() {
    this.docToSign = null;
    this.typeSignature = null;
    this.isSignatureModalOpen = false;
  }

  /**
   * Подписание зависимой сущности РСХН
   */
  async handleSignApprove(): Promise<void> {
    this.isSignatureModalOpen = false;
    switch (this.typeSignature) {
      case this.status.SUBSCRIBED:
        await this.handleSignFromDescription(this.docToSign.id, this.docToSign.subscribe_service);
        break;
      case this.status.CANCELED:
        await this.handleSignFromDescription(this.docToSign.id, this.docToSign.cancel_service);
        break;
    }
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
    }
  }

  /**
   * Удаление зависимой сущности РСХН
   * @param model модель зависимой сущности
   */
  async handleDelete(model: any): Promise<void> {
    const { status } = await this.$store.dispatch(model.delete_apiendpoint, {
      id: model.id,
    });
    if (status) {
      await getElementById(this, this.model.id);
    }
  }

  async handleDetail(model: any) {
    await this.$router.push({ name: model.detail_link, params: { id: model.id } });
  }

  async handleEdit(id) {
    this.$emit('edit', id);
  }

  created() {
    this.showItem = this.model.show_apiendpoint;
  }
}
