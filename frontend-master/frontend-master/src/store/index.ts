import Vue from 'vue';
import Vuex from 'vuex';
import auth from './auth';
import approvalTask from './approvalTask';
import elevator from './elevator';
import lot from './lot';
import gpbo from './gpbo';
import gosmonitoring from './gosmonitoring';
import organization from './organization';
import sdiz from './sdiz';
import templateApproval from './templateApproval';
import user from './user';
import fias from './fias';
import nsi from './nsi';
import manufacturers from './manufacturers';
import laboratories from './laboratories';
import agents from './agents';
import errors from './errors';
import contracts from './contracts';
import stateAuthority from './stateAuthority';
import agreementDocument from './agreementDocument';
import activityLog from './activityLog';
import staff from './staff';
import country from './nsi/address';
import userRoles from './userRoles';
import contragents from './contragents';
import priorityAddress from './priorityAddress';
import notifications from './notifications';
import rshn from './rshn';
import printDocument from '@/store/printDocument';
import registryFilters from '@/store/registryFilters';

Vue.use(Vuex);

const modules = {
  agents,
  auth,
  approvalTask,
  organization,
  sdiz,
  lot,
  gpbo,
  user,
  elevator,
  templateApproval,
  nsi,
  gosmonitoring,
  errors,
  notifications,
  manufacturers,
  laboratories,
  fias,
  contracts,
  stateAuthority,
  agreementDocument,
  activityLog,
  staff,
  country,
  userRoles,
  contragents,
  priorityAddress,
  rshn,
  printDocument,
  registryFilters,
};

export default new Vuex.Store({ modules });
