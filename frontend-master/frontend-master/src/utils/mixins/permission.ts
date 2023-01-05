import Vue from 'vue';
import Component from 'vue-class-component';
import { EAction } from '@/models/roles';
import { LotDataVueModel } from '@/models/Lot/Data/LotData.vue';
import { LotGpbDataVueModel } from '@/models/Lot/Data/LotGpbData.vue';
import { LotElevatorDataVueModel } from '@/models/Lot/Data/LotElevatorData.vue';

@Component
export class PermissionMix extends Vue {
  actionType = '';

  checkGrantedCreatedLot(model: LotDataVueModel | LotGpbDataVueModel | LotElevatorDataVueModel): boolean {
    if (this.$route.name === model.create_from_another_batch)
      return this.$store.getters['auth/check'](EAction[model.create_other_grain_lot_privileges]);
    if (this.$route.name === model.create_from_residues)
      return this.$store.getters['auth/check'](EAction[model.create_surples_grain_lot_privileges]);
    if (this.$route.name === model.create_from_field)
      return this.$store.getters['auth/check'](EAction[model.create_gosmonitoring_grain_lot_privileges]);
    if (this.$route.name === model.create_from_imported)
      return this.$store.getters['auth/check'](EAction[model.create_import_grain_lot_privileges]);
    if (this.$route.name === model.create_from_sdiz)
      return this.$store.getters['auth/check'](EAction[model.create_sdiz_grain_lot_privileges]);
    if (this.$route.name === 'lots_gpb_create_from_in_product')
      return this.$store.getters['auth/check'](EAction[model.create_product_grain_lot_privileges]);
    return false;
  }

  accessGrantedAuthorities(actionType: string): boolean {
    if (actionType) return this.$store.getters['auth/check'](EAction[actionType]);
    return false;
  }
}
