import { SdizVueModel } from '@/models/Sdiz/Data/Sdiz.vue';

export class SdizOgvVueModel extends SdizVueModel {
  name_route_list: string = 'ogv_sdizs_list';
  is_elevator: boolean | null = null;
  name_route_detail: string = 'ogv_sdizs_detail';
  available_filters: any[] = [
    ...this.getAvailableFilters(),
    { name: 'sdiz_number', type: 'text', operator: '%%' },
    { name: 'eisz_number', type: 'text', operator: '=' },
    {
      name: 'is_elevator',
      key: 'elevator_creator',
      operator: '=',
      value: (v) => {
        return !!v;
      },
    },
    { name: 'shipper_location_id', type: 'number', operator: '=' },
    { name: 'consignee_location_id', type: 'number', operator: '=' },
    {
      name: 'eisz_number_checkbox_init',
      key: 'eisz_number',
      type: 'text',
      operator: '!=',
      value: (v) => {
        return null;
      },
    },
    {
      name: 'objects',
      type: 'objects',
      child: {
        lot: [
          { name: 'purposeCode', key: 'purpose.code', type: 'text' },
          { name: 'target_id', type: 'number' },
          {
            name: 'okpd2Code',
            key: 'okpd2.code',
          },
          { name: 'lot_number', type: 'text', operator: '%%' },
          { name: 'current_location_id', type: 'number' },
          {
            name: 'amount_kg_from',
            operator: '>=',
            key: 'amount_kg',
            type: 'number',
          },
          {
            name: 'amount_kg_to',
            operator: '<=',
            key: 'amount_kg',
            type: 'number',
          },
        ],
      },
    },
  ];
}
