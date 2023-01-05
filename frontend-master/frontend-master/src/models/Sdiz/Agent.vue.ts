import { constructByInterface } from '@/utils/construct-by-interface';
import { HeaderSdizItem } from '@/models/Common/Default.vue';
import { SdizVueModel } from '@/models/Sdiz/Data/Sdiz.vue';
import moment from 'moment';
import { EAction } from '@/models/roles';
import { Okpd2VueModel } from '@/models/Sdiz/Okpd2.vue';

export interface AgentVueInterface {
  id: number | null;
  esp_id: number | null;
  canceled_esp_id: number | null;
  sdiz_id: number | null;
  owner_id: number | null;
  repository_id: number | null;
  repository_name: string;

  okpd2_id: number | null;
  okpd2_code: string | null;
  okpd2: Okpd2VueModel;

  status: string | null;
  status_translate: string | null;

  date: string | null;
  date_contract: string | null;
  date_resolution: string | null;
  gka_date: string | null;

  number_contract: string | null;
  number_resolution: string | null;
  sdiz_contract_number: string | null;
  gka_number: string | null;
  available_filters: any[];

  sdiz: SdizVueModel | null;
  sdiz_number: string | null;

  date_from: string;
  date_to: string;

  date_contract_from: string;
  date_contract_to: string;

  date_resolution_from: string;
  date_resolution_to: string;

  gka_date_from: string;
  gka_date_to: string;
}

export class AgentVueModel implements AgentVueInterface {
  component_name = 'sdiz_agent_list';
  id: number | null = null;
  esp_id: number | null = null;
  canceled_esp_id: number | null = null;
  sdiz_id: number | null = null;
  owner_id: number | null = null;
  repository_id: number | null = null;
  repository_name = '-';

  status: string | null = null;
  status_translate: string | null = null;

  date: string | null = null;
  date_contract: string | null = null;
  date_resolution: string | null = null;
  gka_date: string | null = null;

  okpd2_id: number | null = null;
  okpd2_code: string | null = null;
  okpd2: Okpd2VueModel = new Okpd2VueModel();

  number_contract: string | null = null;
  number_resolution: string | null = null;
  sdiz_contract_number: string | null = null;
  gka_number: string | null = null;
  available_filters: any[] = [...this.getAvailableFilters()];

  name_route_list = 'sdiz_agent_list';
  name_route_detail = 'sdiz_agent_detail';
  name_route_create = 'sdiz_agent_create';

  delete_apiendpoit = 'sdiz/deleteAgent';

  sdiz_number: string | null = '';
  sdiz_number_to_attach: string | null = '';
  sdiz: SdizVueModel | null = new SdizVueModel();

  date_from = '';
  date_to = '';

  date_contract_from = '';
  date_contract_to = '';

  date_resolution_from = '';
  date_resolution_to = '';

  gka_date_from = '';
  gka_date_to = '';

  get_list_link = 'sdiz/getListAgent';
  link_registry = 'sdiz_agent_list';
  router_link = 'sdiz_agent_detail';

  register_agent_privileges = EAction.READ_AGENT_DATA_REGISTER;
  filter_register_agent_privileges = EAction.FILTER_AGENT_DATA_REGISTER;
  view_data_privileges = EAction.READ_AGENT_DATA;

  create_privileges = EAction.CREATE_AGENT_DATA;
  update_privileges = EAction.UPDATE_AGENT_DATA;
  delete_privileges = EAction.DELETE_AGENT_DATA;
  sign_privileges = EAction.SIGN_AGENT_DATA;
  cancel_privileges = EAction.CANCEL_AGENT_DATA;

  export_pdf_for_subscription_service = 'sdiz/agent/export/progect';
  export_pdf_for_cancel_service = 'sdiz/agent/export/canceled';

  subscribe_service = 'sdiz/agent/subscribe';
  cancel_service = 'sdiz/agent/cancel';

  constructor(o?) {
    if (o !== undefined) {
      constructByInterface(o, this, {
        sdiz: SdizVueModel,
      });

      switch (this.status) {
        case 'CREATE':
          this.status_translate = 'Создано';
          break;
        case 'SUBSCRIBED':
          this.status_translate = 'Подписано';
          break;
        case 'CANCELED':
          this.status_translate = 'Аннулировано';
          break;
        default:
          this.status_translate = this.status;
          break;
      }
    }
  }

  getHeaders(): HeaderSdizItem[] {
    return [
      { text: 'Действие', value: 'actions' },
      { text: 'Номер СДИЗ', value: 'sdiz.sdiz_number', notExclude: true },
      { text: 'Дата', value: 'date', notExclude: true },
      { text: 'Элеватор', value: 'repository.name' },
      { text: 'Организация', value: 'owner.name' },
      { text: 'Оператор', value: 'operator.full_name' },
      { text: 'Вид с/х культуры', value: 'okpd2.product_name_convert' },
      { text: 'Дата государственного контракта с агентом', value: 'date_contract' },
      { text: 'Номер государственного контракта с агентом', value: 'number_contract' },
      { text: 'Дата решения', value: 'date_resolution' },
      { text: 'Номер решения', value: 'number_resolution' },
      { text: 'Дата гражданско-правового договора', value: 'sdiz.contract_date' },
      { text: 'Номер гражданско-правового договора', value: 'sdiz.contract_number' },
      { text: 'Статус', value: 'status_translate', notExclude: true },
    ];
  }
  dateFilter(): Array<any> {
    return [
      {
        name: 'date_resolution_from',
        operator: '>=',
        key: 'date_resolution',
        value: (v) => moment(v, 'DD.MM.YYYY').startOf('day').format('DD.MM.YYYY HH:mm:ss'),
      },
      {
        name: 'date_resolution_to',
        operator: '<=',
        key: 'date_resolution',
        value: (v) => moment(v, 'DD.MM.YYYY').endOf('day').format('DD.MM.YYYY HH:mm:ss'),
      },
      {
        name: 'gka_date_from',
        operator: '>=',
        key: 'gka_date',
        value: (v) => moment(v, 'DD.MM.YYYY').startOf('day').format('DD.MM.YYYY HH:mm:ss'),
      },
      {
        name: 'gka_date_to',
        operator: '<=',
        key: 'gka_date',
        value: (v) => moment(v, 'DD.MM.YYYY').endOf('day').format('DD.MM.YYYY HH:mm:ss'),
      },
    ];
  }
  baseDateFilter(): Array<any> {
    return [
      {
        name: 'date_from',
        operator: '>=',
        key: 'date',
        value: (v) => moment(v, 'DD.MM.YYYY').startOf('day').format('DD.MM.YYYY HH:mm:ss'),
      },
      {
        name: 'date_to',
        operator: '<=',
        key: 'date',
        value: (v) => moment(v, 'DD.MM.YYYY').endOf('day').format('DD.MM.YYYY HH:mm:ss'),
      },
      {
        name: 'date_contract_from',
        operator: '>=',
        key: 'date_contract',
        value: (v) => moment(v, 'DD.MM.YYYY').startOf('day').format('DD.MM.YYYY HH:mm:ss'),
      },
      {
        name: 'date_contract_to',
        operator: '<=',
        key: 'date_contract',
        value: (v) => moment(v, 'DD.MM.YYYY').endOf('day').format('DD.MM.YYYY HH:mm:ss'),
      },
    ];
  }

  getAvailableFilters(): Array<any> {
    return [
      ...this.dateFilter(),
      ...this.baseDateFilter(),
      { name: 'okpd2_code', key: 'sdiz.lot.okpd2.code' },
      { name: 'repository_id', type: 'number' },
      { name: 'sdiz_contract_number', type: 'text', operator: '%%', key: 'sdiz.contract_number' },
      { name: 'sdiz_number', type: 'text', operator: '%%', key: 'sdiz.sdiz_number' },
      { name: 'sdiz_number_to_attach', type: 'text', operator: '%%', key: 'sdiz_number' },
      { name: 'number_resolution', type: 'text', operator: '%%' },
      { name: 'number_contract', type: 'text', operator: '%%' },
    ];
  }

  isError(): boolean {
    return false;
  }

  getDataForCreate(): any {
    return {
      number_resolution: this.number_resolution,
      number_contract: this.number_contract,

      date_resolution: this.date_resolution,
      date_contract: this.date_contract,
      repository_id: this.repository_id,
      sdiz_id: this.sdiz_id,
    };
  }

  getDataForUpdate(): any {
    return {
      number_resolution: this.number_resolution,
      number_contract: this.number_contract,
      date_resolution: this.date_resolution,
      date_contract: this.date_contract,
      repository_id: this.repository_id,
    };
  }

  getErrors(): string[] {
    const errors: string[] = [];

    if (!this.date_contract) errors.push('Укажите дату государственного контракта');
    if (!this.number_contract) errors.push('Укажите номер государственного контракта');
    if (!this.date_resolution) errors.push('Укажите дату решения');
    if (!this.number_resolution) errors.push('Укажите номер решения');

    return errors;
  }
}
