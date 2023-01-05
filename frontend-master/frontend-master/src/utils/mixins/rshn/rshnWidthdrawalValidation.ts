import { Component, Vue } from 'vue-property-decorator';

import {
  DocumentStorageType,
  LotNumberValidationData,
  SdizNumberValidationData,
} from '@/models/Rshn/Withdrawal/ValidationData';
import isUndefined from 'lodash/isUndefined';
import { getDateObject } from '@/utils/date';

@Component
export class RshnWidthdrawalValidation extends Vue {
  getDocumentNumbersErrors(
    lotValidationData: LotNumberValidationData | null,
    sdizValidationData: SdizNumberValidationData | null,
    isSdizSet
  ): string[] {
    const errors: string[] = [];

    if (isSdizSet && isUndefined(sdizValidationData)) errors.push('СДИЗ с указанным номером не найден');
    if (isUndefined(lotValidationData)) errors.push('Партия с указанным номером не найдена');

    return errors;
  }

  getDocumentNumbersEqualityErrors(
    lotValidationData: LotNumberValidationData | null,
    sdizValidationData: SdizNumberValidationData | null,
    isSdizSet
  ): string[] {
    const errors: string[] = [];

    //Если СДИЗ на бумажном носителе, то и партия должна быть на бумажном иначе Партия должна быть в этом СДИЗ
    if (
      isSdizSet &&
      sdizValidationData?.type === DocumentStorageType.PAPER &&
      sdizValidationData?.type !== lotValidationData?.type
    ) {
      errors.push('Указан СДИЗ на бумажном носителе. Пожалуйста, укажите партию на бумажном носителе');
    } else if (isSdizSet && sdizValidationData?.lot_number !== lotValidationData?.lot_number) {
      errors.push('Указанный номер партии не совпадает с номером партии в СДИЗ');
    }

    return errors;
  }

  getOkpd2Errors(lotValidationData: LotNumberValidationData | null, thisRef: any): string[] {
    const errors: string[] = [];

    //Если указана электронная партия проверяем на соответствие корректно ли указан Вид с\х культуры
    if (
      lotValidationData?.type === DocumentStorageType.ELECTRONIC &&
      lotValidationData?.okpd2_id !== thisRef.model.okpd2.id
    ) {
      errors.push('Указанный вид с/х культуры не совпадает с видом с/х культуры, указанной в партии');
    }

    return errors;
  }

  getDatesErrors(
    lotValidationData: LotNumberValidationData | null,
    sdizValidationData: SdizNumberValidationData | null,
    thisRef: any
  ): string[] {
    const errors: string[] = [];
    const withdrawalEnterDate: Date = getDateObject(thisRef.model.enter_date);
    let sdizEnterDate: any = null;
    let lotEnterDate: any = null;
    let lastEnterDate: any = null;
    let documentName = '';

    // если указан номер СДИЗи или партии (те что в электронном виде)
    if (
      typeof sdizValidationData?.enter_date === 'string' &&
      sdizValidationData.type === DocumentStorageType.ELECTRONIC
    )
      sdizEnterDate = getDateObject(sdizValidationData.enter_date);
    if (typeof lotValidationData?.enter_date === 'string' && lotValidationData.type === DocumentStorageType.ELECTRONIC)
      lotEnterDate = getDateObject(lotValidationData.enter_date);

    if (!lotEnterDate && !sdizEnterDate) return errors;

    if (sdizEnterDate > lotEnterDate) {
      lastEnterDate = sdizEnterDate;
      documentName = 'СДИЗ';
    } else {
      lastEnterDate = lotEnterDate;
      documentName = 'партии';
    }

    // Проверять дату изъятия она должна быть больше или равна (максимальной даты партии или СДИЗ)
    if (withdrawalEnterDate < lastEnterDate)
      errors.push(`Дата формирования изъятия не может предшествовать дате формирования ${documentName}`);

    return errors;
  }

  errorMessagesShown(errors: string[], thisRef: any): boolean {
    if (errors.length) {
      errors.forEach((err) => thisRef.$notify({ group: 'rshn', type: 'warning', title: err, duration: 3500 }));
    }

    return !!errors.length;
  }

  async isWithdrawalValid(thisRef) {
    if (thisRef.model.is_not_doc || !thisRef.model.isShippingOrStorage) return true;

    const isSdizSet = !!thisRef.model.sdiz_number;
    const sdizValidation = isSdizSet
      ? await thisRef.$store.dispatch('rshn/findSdizByNumber', thisRef.model.sdiz_number)
      : null;
    const lotValidation = await thisRef.$store.dispatch('rshn/findLotByNumber', thisRef.model.lot_number);

    if (isSdizSet && !sdizValidation.status) throw new Error('Ошибка при валидации СДИЗ');
    if (!lotValidation.status) throw new Error('Ошибка при валидации партии');

    const sdizValidationData: SdizNumberValidationData = isSdizSet ? sdizValidation.response : null;
    const lotValidationData: LotNumberValidationData = lotValidation.response;

    if (
      this.errorMessagesShown(this.getDocumentNumbersErrors(lotValidationData, sdizValidationData, isSdizSet), thisRef)
    )
      return false;

    if (this.errorMessagesShown(this.getDatesErrors(lotValidationData, sdizValidationData, this), thisRef))
      return false;

    if (
      this.errorMessagesShown(
        this.getDocumentNumbersEqualityErrors(lotValidationData, sdizValidationData, isSdizSet),
        thisRef
      )
    )
      return false;

    return !this.errorMessagesShown(this.getOkpd2Errors(lotValidationData, this), thisRef);
  }
}
