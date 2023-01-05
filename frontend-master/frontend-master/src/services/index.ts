import auth from './auth';
import approvalRequestLog from './approvalRequestLog';
import certificate from './certificate';
import complaint from './complaint';
import declaration from './declaration';
import exportService from './export';
import importService from './import';
import interaction from './interaction';
import notification from './notification';
import notify from './notify';
import password from './password';
import roles from './roles';
import versions from './versions';
import manufacturer from './manufacturer';
import user from './user';
import laboratory from './laboratory';
import stateAuthority from './stateAuthority';
import subject from './subject';
import divisions from './divisions';
import requests from './requests';
import signature from './signature';
import catalogs from './catalogs';

const services = {
  auth,
  approvalRequestLog,
  certificate,
  complaint,
  declaration,
  export: exportService,
  import: importService,
  interaction,
  notification,
  notify,
  password,
  roles,
  versions,
  manufacturer,
  user,
  laboratory,
  stateAuthority,
  subject,
  divisions,
  requests,
  signature,
  catalogs,
};

export default services;

export type TService = {
  [K in keyof typeof services]: InstanceType<typeof services[K]>;
};
