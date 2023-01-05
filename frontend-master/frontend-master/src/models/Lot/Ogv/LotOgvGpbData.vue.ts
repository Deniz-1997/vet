import { LotGpbDataVueModel } from '@/models/Lot/Data/LotGpbData.vue';

export class LotOgvGpbDataVueModel extends LotGpbDataVueModel {
  name_route_list: string = 'ogv_list_gpb';
  name_route_detail: string = 'ogv_lot_detail_gpb';
  available_filters: any[] = [
    ...this.getAvailableFilters(),
    { name: 'gpb_number', operator: '%%', type: 'text' },
    { name: 'okpd2_id', type: 'number' },
    { name: 'manufacturer_id', type: 'number' },
    { name: 'create_date', type: 'text' },
    { name: 'owner_id', type: 'number' },
  ];
}
