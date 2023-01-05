import { SdizVueModel } from '@/models/Sdiz/Data/Sdiz.vue';
import { EAction } from '@/models/roles';
import { StorageTypeEnum } from '@/utils/enums/StorageTypeEnum';

export class SdizElevatorModel extends SdizVueModel {
  component_name = 'sdiz_elevator';
  to_lot_link = 'lot_elevator_detail';
  available_filters: any[] = [
    ...this.getAvailableFilters(),
    { name: 'sdiz_number', type: 'text', operator: '%%' },
    { name: 'eisz_number', type: 'text', operator: '=' },
    { name: 'shipper_location_id', type: 'number', operator: '=' },
    { name: 'consignee_location_id', type: 'number', operator: '=' },
    { name: 'owner_id', type: 'number', key: 'lot.owner_id' },
    {
      name: 'eisz_number_checkbox_init',
      key: 'eisz_number',
      type: 'text',
      operator: '!=',
      value: () => {
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

  name_route_list = 'sdiz_elevator_list';
  name_route_detail = 'sdiz_elevator_detail';
  name_route_create = 'sdiz_elevator_create';
  list_apiendpoit = 'sdiz/getListForElevator';
  link_find_items_in_modal = 'lot/getListForElevator';
  export_pdf_service = 'sdiz/export/pdf';

  register_sdiz_privileges = EAction.READ_SDIZ_STORAGE_REGISTER;
  filter_register_sdiz_privileges = EAction.FILTER_SDIZ_STORAGE_REGISTER;
  view_data_privileges = EAction.READ_SDIZ_STORAGE;
  view_print_sdiz_privileges = EAction.READ_SDIZ_STORAGE_PRINT_FORM;
  create_sdiz_privileges = EAction.CREATE_SDIZ_STORAGE;
  update_privileges = EAction.UPDATE_SDIZ_STORAGE;
  delete_privileges = EAction.DELETE_SDIZ_STORAGE;
  sign_privileges = EAction.SIGN_SDIZ_STORAGE;
  cancel_privileges = EAction.CANCEL_SDIZ_STORAGE;
  repayment_privileges = EAction.REPAYMENT_SDIZ_STORAGE;
  confirm_priveleges = EAction.CONFIRM_SDIZ;

  getErrorOperationAcceptace(): Array<string> {
    const errors: Array<string> = [];
    if (!this.objects.lot.id) errors.push('Выберите партию');
    if (this.objects.operations.detail.acceptance) {
      if (!this.objects.storage_agreement.date) errors.push('Заполните дату');
      if (!this.objects.storage_agreement.number) errors.push('Ведите номер договора');
      if (!this.objects.storage_agreement.type?.code) errors.push('Выберите тип хранения');
      if (
        this.objects.storage_agreement.type?.code === StorageTypeEnum.ISOLATED &&
        !this.objects.storage_agreement.area
      ) {
        errors.push('Введите площадь');
      }
      if (!this.objects.storage_agreement.time_store) errors.push('Выберите срок хранения');
      if (!this.objects.storage_agreement.conditions) errors.push('Введите условия хранения');
      if (!this.objects.storage_agreement.service.length) errors.push('Выберите наименование вида услуг');
      if (!this.objects.storage_agreement.place_id) errors.push('Выберите хранения партии зерна');
      if (this.moving_lot_checkbox_init) {
        if (!this.objects.storage_agreement.moving_date) errors.push('Введите дату хранения при перемещении');
        if (!this.objects.storage_agreement.moving_place_id) errors.push('Введите место хранения при перемещении');
        if (!this.objects.storage_agreement.moving_type?.code) errors.push('Выберите тип хранения при перемещении');
        if (!this.objects.storage_agreement.moving_conditions) errors.push('Введите условия хранения  при перемещении');
      }
    }
    return errors;
  }

  getErrors(): Array<string> {
    const errors: Array<string> = [];
    if (this.objects.operations.sdiz_type === 0) errors.push('Выберите операцию');
    errors.push(...this.getErrorOperationAcceptace());
    return errors;
  }
}
