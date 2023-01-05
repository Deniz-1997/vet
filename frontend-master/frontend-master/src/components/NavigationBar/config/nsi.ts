import { EAction } from '@/models/roles';
import { TMenuItem } from "../models";

export default {
  label: 'Справочники',
  pages: [
    {
      label: 'Общие справочники',
      path: '/nsi',
      enable: EAction.READ_DICTIONARY_REGISTER,
    },
    {
      label: 'Реестр лабораторий',
      path: '/laboratories',
      enable: EAction.READ_LABORATORY_REGISTER,
    },
    {
      label: 'Государственные контракты с агентами',
      path: '/contracts',
      enable: EAction.READ_AGENT_CONTRACT_REGISTER,
    },
    {
      label: 'Органы государственной власти',
      path: '/stateAuthority',
      enable: EAction.READ_GOV_ORG_REGISTER,
    },
  ]
} as TMenuItem;
