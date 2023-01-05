import { LotsMovedVueModel } from '@/models/Lot/LotsMoved.vue';
import { LotGpbDataVueModel } from '@/models/Lot/Data/LotGpbData.vue';
import { applyMask } from '@/components/common/inputs/mask/decimalNumberMask';

export function createNewLotsMovedModel(lot): any {
  return new LotsMovedVueModel({
    value: 0,
    value_mask: applyMask(0),
    lot_number: lot.lot_number,
    gpb_number: lot.gpb_number,
    amount_kg_available: lot.amount_kg_available,
    amount_kg_original: lot.amount_kg_original,
    target_id_moved: lot.target_id,
    okpd2_id: lot.okpd2_id,
  });
}

export function addSelectedLot(lot): any {
  const selectLot = createNewLotsMovedModel(lot);
  if (lot instanceof LotGpbDataVueModel) {
    selectLot['gpb_id'] = lot.id;
  } else {
    selectLot['lot_id'] = lot.id;
  }
  return selectLot;
}
