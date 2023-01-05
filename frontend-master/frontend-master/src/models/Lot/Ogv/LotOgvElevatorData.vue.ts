import { LotElevatorDataVueModel } from '@/models/Lot/Data/LotElevatorData.vue';

export class LotOgvElevatorDataVueModel extends LotElevatorDataVueModel {
  name_route_list: string = 'ogv_list_elevator';
  name_route_detail: string = 'ogv_lot_detail_elevator';
  name_route_create: string = 'ogv_lot_detail_elevator';

  available_filters: any[] = [
    ...this.getAvailableFilters(),
    { name: 'owner_id', type: 'number' },
    { name: 'repository_id', type: 'number' },
    { name: 'okpd2_id', type: 'number' },
    { name: 'lot_number', operator: '%%' },
  ];
}
