import moment from 'moment';
import { ResearchRegisterVueModel } from '@/models/Gosmonitoring/ResearchRegister.vue';
import { EAction } from '@/models/roles';

export class ConductedResearchRegisterVue extends ResearchRegisterVueModel {
  cancel_link = 'gosmonitoring_register_conducted_research_manufacturers';
  update_apiendpoit = 'gosmonitoring_register_conducted_research_manufacturers_detail';
  create_apiendpoit = '';
  list_privelegies = EAction.READ_MANUFACTURER_RESEARCH_REGISTER;
  view_data_privileges = EAction.READ_MANUFACTURER_RESEARCH_DATA;
  url = 'register/conducted-research-manufacturers';
  title = 'Реестр проведенных исследований';
  component_name = 'conductedGosResearch';
  name_route_detail = 'gosmonitoring_register_conducted_research_manufacturers_detail';
  name_route_list = 'gosmonitoring_register_conducted_research_manufacturers';
  export_pdf_service = 'gosmonitoring/register/research-register/export/conducted-research-manufacturers';

  available_filters: any[] = [
    ...this.timeAvailableFilters(),
    { name: 'id', type: 'number' },
    { name: 'status_id', type: 'number' },
    { name: 'okpd2Code', key: 'okpd2.code' },
    { name: 'current_location_id', type: 'number' },
    { name: 'prodact_monitor_id', type: 'number' },
    { name: 'target_id', type: 'number' },
    { name: 'owner_id', type: 'number' },
    { name: 'operator_id', type: 'number' },
    { name: 'place_of_checking_id', type: 'number' },
    { name: 'laboratory_monitor_number', operator: '%%' },
    { name: 'number_grain_samples', operator: '%%' },
    { name: 'number_of_protocol_check', operator: '%%' },
  ];
  timeAvailableFilters(): Array<any> {
    return [
      {
        name: 'date_check_from',
        operator: '>=',
        key: 'date_check',
        value: (v) => moment(v, 'DD.MM.YYYY').startOf('day').format('DD.MM.YYYY HH:mm:ss'),
      },
      {
        name: 'date_check_to',
        operator: '<=',
        key: 'date_check',
        value: (v) => moment(v, 'DD.MM.YYYY').endOf('day').format('DD.MM.YYYY HH:mm:ss'),
      },

      {
        name: 'date_of_protocol_check_from',
        operator: '>=',
        key: 'date_of_protocol_check',
        value: (v) => moment(v, 'DD.MM.YYYY').startOf('day').format('DD.MM.YYYY HH:mm:ss'),
      },
      {
        name: 'date_of_protocol_check_to',
        operator: '<=',
        key: 'date_of_protocol_check',
        value: (v) => moment(v, 'DD.MM.YYYY').endOf('day').format('DD.MM.YYYY HH:mm:ss'),
      },
    ];
  }
  getHeaders() {
    return [
      { text: 'Действия', value: 'actions' },
      { text: 'Номер', value: 'id' },
      { text: 'Дата проведения отбора проб', value: 'date_check' },
      { text: 'Номер акта отбора проб', value: 'number_check' },
      { text: 'Товаропроизводитель', value: 'owner.name' },
      { text: 'Место формирования партии в целях отбора проб', value: 'place_of_checking.address' },
      { text: 'Номер (шифр) пробы зерна', value: 'number_grain_samples' },
      { text: 'Дата протокола исследований зерна', value: 'date_of_protocol_check' },
      { text: 'Номер протокола исследований зерна', value: 'number_of_protocol_check' },
      { text: 'Цель использования', value: 'lot_target.name' },
      { text: 'Вид с/х культуры', value: 'okpd2.product_name_convert' },
      { text: 'Место формирования партии зерна', value: 'locationOfCreationFormatted', exclude: true },
      { text: 'Номер исследования ГМ', value: 'laboratory_monitor_number' },
      { text: 'Масса, кг', value: 'amount_kg_original_mask' },
      { text: 'Текущая масса, кг', value: 'amount_kg_available_mask' },
      { text: 'Статус', value: 'status_name' },
    ];
  }
}
