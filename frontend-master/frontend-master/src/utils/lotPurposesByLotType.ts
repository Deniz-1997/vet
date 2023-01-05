import { LotsPurposeEnum } from '@/utils/enums/lotsPurpose.enum';
import { SdizTypeLot } from '@/models/Sdiz/Operations.vue';

// eslint-disable-next-line max-lines-per-function
export function lotPurposesByLotType(content: any[], type: string) {
  const filteredByCodes = (codes: any[]) => {
    return content.filter((e) => codes.includes(e.code));
  };

  switch (type) {
    case SdizTypeLot.IN_RUSSIA.toString():
      return filteredByCodes([LotsPurposeEnum.PROCESSING, LotsPurposeEnum.STORAGE_AND_PROCESSING]);
    case SdizTypeLot.IMPORT_TO_RUSSIA.toString():
      return filteredByCodes([LotsPurposeEnum.IMPORT_TO_RUSSIA]);
    case SdizTypeLot.EXPORT_FROM_RUSSIA.toString():
      return filteredByCodes([LotsPurposeEnum.EXPORT_FROM_RUSSIA]);
    case SdizTypeLot.ELEVATOR.toString():
      return filteredByCodes([
        LotsPurposeEnum.STORAGE_AND_PROCESSING,
        LotsPurposeEnum.PROCESSING,
        LotsPurposeEnum.IMPORT_TO_RUSSIA,
      ]);
    case 'imported':
      return filteredByCodes([LotsPurposeEnum.IMPORT_TO_RUSSIA]);
    case 'sdiz':
      return filteredByCodes([
        LotsPurposeEnum.EXPORT_FROM_RUSSIA,
        LotsPurposeEnum.PROCESSING,
        LotsPurposeEnum.STORAGE_AND_PROCESSING,
      ]);
    case 'residues':
      return filteredByCodes([
        LotsPurposeEnum.EXPORT_FROM_RUSSIA,
        LotsPurposeEnum.PROCESSING,
        LotsPurposeEnum.STORAGE_AND_PROCESSING,
      ]);
    case 'field':
      return filteredByCodes([
        LotsPurposeEnum.EXPORT_FROM_RUSSIA,
        LotsPurposeEnum.PROCESSING,
        LotsPurposeEnum.STORAGE_AND_PROCESSING,
      ]);
    case 'another-batch-grain':
      return filteredByCodes([
        LotsPurposeEnum.EXPORT_FROM_RUSSIA,
        LotsPurposeEnum.PROCESSING,
        LotsPurposeEnum.STORAGE_AND_PROCESSING,
      ]);
    case 'elevator':
      return filteredByCodes([LotsPurposeEnum.PROCESSING, LotsPurposeEnum.STORAGE_AND_PROCESSING]);
    case 'in-product':
      return filteredByCodes([
        LotsPurposeEnum.EXPORT_FROM_RUSSIA,
        LotsPurposeEnum.PROCESSING,
        LotsPurposeEnum.STORAGE_AND_PROCESSING,
      ]);
    default:
      return filteredByCodes([
        LotsPurposeEnum.IMPORT_TO_RUSSIA,
        LotsPurposeEnum.EXPORT_FROM_RUSSIA,
        LotsPurposeEnum.PROCESSING,
        LotsPurposeEnum.STORAGE_AND_PROCESSING,
      ]);
  }
}
