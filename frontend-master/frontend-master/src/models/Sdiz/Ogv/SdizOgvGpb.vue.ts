import { SdizGpbVueModel } from '@/models/Sdiz/Data/SdizGpb.vue';
import { AvailableFilters } from '@/models/Common/Default.vue';

export class SdizOgvGpbVueModel extends SdizGpbVueModel {
  name_route_list: string = 'ogv_sdizs_list_gpb';
  name_route_detail: string = 'ogv_sdizs_detail_gpb';
  name_route_create: string = 'sdiz_gpb_create';
  available_filters: AvailableFilters[] = [
    ...this.getAvailableFilters(),
    { name: 'owner_id', type: 'number' },
    { name: 'sdiz_gpb_number', type: 'text', operator: '%%' },
    { name: 'eisz_number', type: 'text', operator: '=' },
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
        gpb: [
          { name: 'purposeCode', key: 'purpose.code', type: 'text' },
          {
            name: 'okpd2Code',
            key: 'okpd2.code',
          },
          { name: 'target_id', type: 'number' },
          { name: 'gpb_number', type: 'text', operator: '%%' },
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
