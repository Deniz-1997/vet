import moment from 'moment';
import { EAction } from '@/models/roles';
import { ImplementationVueModel } from '@/models/Gosmonitoring/Implementation.vue';

export class SubmitedByManufacturerModel extends ImplementationVueModel {
  component_name = 'gosSubmitedByManufacturers';
  cancel_link = 'gosmonitoring_register_submitted_by_manufacturers';
  update_apiendpoit = 'gosmonitoring_register_submitted-by-manufacturers_detail';
  create_apiendpoit = 'gosmonitoring_register_implementation_create';
  url = 'register/implementation/submitted-by-manufacturers';
  title = 'Реестр поданных сведений товаропроизводителями';
  name_route_detail = 'gosmonitoring_register_submitted-by-manufacturers_detail';
  name_route_list = 'gosmonitoring_register_submitted_by_manufacturers';

  register_reads_privileges = EAction.READ_MANUFACTURER_DATA_REGISTER;
  view_data_privileges = EAction.READ_MANUFACTURER_DATA;

  available_filters: any[] = [
    {
      name: 'date_from',
      operator: '>=',
      key: 'date_enter',
      value: (v) => moment(v, 'DD.MM.YYYY').startOf('day').format('DD.MM.YYYY HH:mm:ss'),
    },
    {
      name: 'date_to',
      operator: '<=',
      key: 'date_enter',
      value: (v) => moment(v, 'DD.MM.YYYY').endOf('day').format('DD.MM.YYYY HH:mm:ss'),
    },
    { name: 'status_id', type: 'number' },
    { name: 'okpd2Code', key: 'okpd2.code' },
    { name: 'current_location_id', type: 'number' },
    { name: 'prodact_monitor_id', type: 'number' },
    { name: 'owner_id', type: 'number' },
    { name: 'place_of_cultivation_id', type: 'number' },
    { name: 'prodact_monitor_number', operator: '%%' },
  ];

  getHeaders() {
    return [
      { text: 'Действия', value: 'actions' },
      { text: 'Номер', value: 'id', notExclude: true },
      { text: 'Номер документа', value: 'prodact_monitor_number', notExclude: true },
      { text: 'Дата сбора урожая', value: 'date_enter' },
      { text: 'Место выращивания партии зерна', value: 'place_of_cultivation.address' },
      {
        text: 'Площадь земельного участка или его части (поля), с которого собран урожай зерна (га)',
        value: 'area_mask',
      },
      {
        text: 'Сведения о виде вещного права на земельный участок или его часть (поле), с которого собран урожай зерна',
        value: 'ownership_details.name',
      },
      { text: 'Вид сельскохозяйственной культуры зерна', value: 'okpd2.product_name_convert' },
      {
        text: 'Масса зерна (нетто в килограммах), произведенного в день уборки урожая',
        value: 'weight_mask',
        notExclude: true,
      },
      { text: 'Место хранения зерна', value: 'current_location.address' },
      { text: 'Место формирования партии зерна', value: 'numbersFromSubjectFormatted' },
      { text: 'Товаропроизводитель', value: 'owner.name' },
      { text: 'Статус', value: 'status_name' },
    ];
  }
}
