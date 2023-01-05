import { LotDataVueModel } from '@/models/Lot/Data/LotData.vue';
import { LotGpbDataVueModel } from '@/models/Lot/Data/LotGpbData.vue';
import { HeaderSdizItem } from '@/models/Common/Default.vue';
import { EAction } from '@/models/roles';
import { LotType } from '@/utils/enums/LotType';

export class LotElevatorDataVueModel extends LotDataVueModel {
  name_route_list = 'lot_elevator_list';
  name_route_detail = 'lot_elevator_detail';
  name_route_create = 'lot_elevator_detail';
  component_name = 'lot_elevator';

  create_from_another_batch = 'lot_elevator_create_from_another_batch';
  create_from_field = 'lot_elevator_create_from_field';
  create_from_imported = 'lot_elevator_create_from_imported';
  create_from_residues = 'lot_elevator_create_from_residues';
  create_from_sdiz = 'lot_elevator_create_from_sdiz';
  sdiz_create = 'sdiz_elevator_create';

  list_sdiz_apiendpoint = 'sdiz/getList';
  list_apiendpoit = 'lot/getListForElevator';
  create_apiendpoit = 'lot/create';
  show_apiendpoit = 'lot/show';
  update_apiendpoit = 'lot/update';
  delete_apiendpoit = 'lot/delete';
  export_pdf_service = 'lot/export';

  propertyNameForSdiz = 'sdiz_data';
  movedField = 'lots_moved';

  register_grain_lot_product_read_privileges = EAction.READ_GRAIN_LOT_STORAGE_REGISTER;
  filter_register_grain_lot_product_privileges = EAction.FILTER_GRAIN_LOT_STORAGE_REGISTER;

  view_data_privileges = EAction.READ_GRAIN_LOT_STORAGE;

  create_other_grain_lot_privileges = EAction.CREATE_GRAIN_PARTIES_OTHER_PARTIES_STORAGE;
  create_surples_grain_lot_privileges = EAction.CREATE_GRAIN_PARTIES_SURPLUS_STORAGE;

  update_privileges = EAction.UPDATE_GRAIN_LOT_STORAGE;
  delete_privileges = EAction.DELETE_GRAIN_LOT_STORAGE;
  cancel_privileges = EAction.CANCEL_GRAIN_LOT_STORAGE;
  cancel_lot_surples = EAction.OFF_GRAIN_LOT_STORAGE;

  available_filters: any[] = [
    ...this.getAvailableFilters(),
    { name: 'repository_id', type: 'number' },
    { name: 'lot_number', operator: '%%' },
    { name: 'owner_id', type: 'number' },
    { name: 'laboratory_monitor_number', key: 'laboratory_monitor.laboratory_monitor_number', operator: '%%' },
  ];

  getLotModel(lot): LotGpbDataVueModel | LotDataVueModel | LotElevatorDataVueModel {
    return new LotElevatorDataVueModel(lot);
  }

  getHeaders(): HeaderSdizItem[] {
    return [
      { text: 'Действия', value: 'actions' },
      { text: 'ID', value: 'id' },
      { text: 'Номер', value: 'lot_number' },
      { text: 'Дата', value: 'enter_date' },
      { text: 'Владелец партии', value: 'objects.owner.name' },
      { text: 'На хранении', value: 'objects.repository.name' },
      { text: 'Масса, кг', value: 'amount_kg_original_mask', notExclude: true, width: '100px', align: 'center' },
      { text: 'Текущая масса, кг', value: 'amount_kg_mask', notExclude: true, width: '100px', align: 'center' },
      { text: 'Вид с/х культуры', value: 'objects.okpd2.product_name_convert' },
      { text: 'Цель', value: 'objects.target.name' },
      { text: 'Местоположение', value: 'objects.current_location.address' },
      { text: 'Назначение', value: 'objects.purpose.name' },
      {
        text: 'Номер документа государственного мониторинга',
        value: 'objects.laboratory_monitor.laboratory_monitor_number',
      },
      { text: 'Статус', value: 'status_translate' },
    ];
  }

  getTypeModel(): string {
    return 'LotElevator';
  }

  getErrors(typeLot?: LotType): Array<string> {
    const errors = super.getErrors(typeLot);
    if (!this.owner_id) errors.push('Не указан владелец партии');

    return errors;
  }
}
