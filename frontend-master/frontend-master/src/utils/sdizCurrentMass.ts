import { SdizExtinguishVueModel } from '@/models/Sdiz/SdizExtinguish';
import { SdizExtinguishRefusalModel } from '@/models/Sdiz/SdizExtinguishRefusal';
import { SdizGpbVueModel } from '@/models/Sdiz/Data/SdizGpb.vue';
import { SdizVueModel } from '@/models/Sdiz/Data/Sdiz.vue';
import { subtract, add } from '@/utils/decimals';

export const sdizCurrentMass = (value: SdizVueModel | SdizGpbVueModel): number => {
  const originalMass = value.amount_kg_original || 0;

  const extinguishsMass = value.objects.extinguishs.reduce((acc: number, cur: SdizExtinguishVueModel) => {
    return cur.is_canceled ? acc : add(acc, cur.amount_kg);
  }, 0);

  const extinguishRefusalsMass = value.objects.extinguish_refusals.reduce(
    (acc: number, cur: SdizExtinguishRefusalModel) => {
      return cur.is_canceled ? acc : add(acc, cur.amount_kg);
    },
    0
  );

  const amount = subtract(originalMass, add(extinguishsMass, extinguishRefusalsMass));
  return amount < 0 ? 0 : amount;
};
