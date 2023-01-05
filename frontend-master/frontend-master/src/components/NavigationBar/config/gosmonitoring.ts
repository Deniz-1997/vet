import { EAction } from '@/models/roles';
import { TMenuItem } from '../models';

export default {
  label: 'Госмониторинг',
  path: '/gosmonitoring',
  pages: [
    {
      label: 'Реестр мест формирования партии зерна',
      path: '/numbers-for-manufacturers',
      enable: EAction.READ_LOT_NUMBER_REGISTER,
    },
    {
      label: 'Реестр сведений о собранном урожае',
      path: '/gosmonitoring/register/implementation',
      enable: EAction.READ_GOSMONITORING_DATA_REGISTER,
    },
    {
      label: 'Реестр проведенных исследований',
      path: '/gosmonitoring/register/conducted-research-manufacturers',
      enable: EAction.READ_MANUFACTURER_RESEARCH_REGISTER,
    },
    {
      label: 'Реестр поданных сведений товаропроизводителями',
      path: '/gosmonitoring/register/submitted-by-manufacturers',
      enable: EAction.READ_MANUFACTURER_DATA_REGISTER,
    },
    {
      label: 'Реестр проведенных исследований',
      path: '/gosmonitoring/research-register',
      enable: EAction.READ_RESEARCH_REGISTER,
    },
  ],
} as TMenuItem;
