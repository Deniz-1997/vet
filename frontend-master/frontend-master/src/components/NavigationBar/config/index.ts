import { TMenuItem } from '../models';

import management from './management';
import manufacturers from './manufacturers';
import ogv from './ogv';
import organizations from './organizations';
import gpb from './gpb';
import gpbo from './gpbo';
import lots from './lots';
import reserves from './reserves';
import gosmonitoring from './gosmonitoring';
import sdizs from './sdizs';
import tasks from './tasks';
import nsi from './nsi';
import files from './files';
import myorganization from '@/components/NavigationBar/config/myorganization';
import rshn from './rshn';
import regionalGovernment from './regionalGovernment';

const config: TMenuItem[] = [
  management,
  manufacturers,
  organizations,
  regionalGovernment,
  ogv,
  ...lots,
  gpb,
  gpbo,
  reserves,
  gosmonitoring,
  ...sdizs,
  tasks,
  nsi,
  myorganization,
  files,
  rshn,
];

export default config;
