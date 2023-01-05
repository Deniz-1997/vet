import { LotDataVueModel } from '@/models/Lot/Data/LotData.vue';

export class LotDataOgvVueModel extends LotDataVueModel {
  available_filters: any[] = [
    ...this.getAvailableFilters(),
    { name: 'owner_id', type: 'number' },
    { name: 'okpd2_id', type: 'number' },
    { name: 'lot_number', operator: '%%' },
  ];
  lot_tables_paper_store_title = 'Предшествующие партии зерна';

  name_route_list: string = 'ogv_list';
  name_route_detail: string = 'ogv_lot_detail';
  name_route_create: string = 'ogv_lot_detail';
}
