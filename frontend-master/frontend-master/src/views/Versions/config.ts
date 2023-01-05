import { TVersionProviderItem } from './Versions.types';

const { version } = require('../../../package.json');

// Определяем список сервисов для отображения и методы получения их версий.
const config: TVersionProviderItem<Vue>[] = [];

config.push({
  id: 'giszp-ui',
  callback() {
    return process.env.npm_package_version || version;
  },
});

config.push({
  id: 'email-service',
  callback() {
    return this.$axios('/api/email/version', { timeout: 15000, ignoreStatuses: [500, 502, 503, 504] });
  },
});

config.push({
  id: 'fias-service',
  callback() {
    return this.$axios('/api/fias/version', { timeout: 15000, ignoreStatuses: [500, 502, 503, 504] });
  },
});

config.push({
  id: 'workflow-service',
  callback() {
    return this.$axios('/api/elevator-request/version', { timeout: 15000, ignoreStatuses: [500, 502, 503, 504] });
  },
});

config.push({
  id: 'nci-service',
  callback() {
    return this.$axios('/api/nci/sdizType/version', { timeout: 15000, ignoreStatuses: [500, 502, 503, 504] });
  },
});

config.push({
  id: 'authorization-service',
  callback() {
    return this.$axios('/api/auth/version', { timeout: 15000, ignoreStatuses: [500, 502, 503, 504] });
  },
});

config.push({
  id: 'subject-service',
  callback() {
    return this.$axios('/api/subject/version', { timeout: 15000, ignoreStatuses: [500, 502, 503, 504] });
  },
});

config.push({
  id: 'directory-service',
  callback() {
    return this.$axios('/api/directory/version', { timeout: 15000, ignoreStatuses: [500, 502, 503, 504] });
  },
});

config.push({
  id: 'division-service',
  callback() {
    return this.$axios('/api/subject/division/version', { timeout: 15000, ignoreStatuses: [500, 502, 503, 504] });
  },
});

config.push({
  id: 'contract-service',
  callback() {
    return this.$axios('/api/agent/contract/version', { timeout: 15000, ignoreStatuses: [500, 502, 503, 504] });
  },
});

config.push({
  id: 'smev-service',
  callback() {
    return this.$axios('/api/smev/version', { timeout: 15000, ignoreStatuses: [500, 502, 503, 504] });
  },
});

config.push({
  id: 'gosmonitoring-service',
  callback() {
    return this.$axios('/api/gosmonitoring/register/version', { timeout: 15000, ignoreStatuses: [500, 502, 503, 504] });
  },
});

config.push({
  id: 'gpb-service',
  callback() {
    return this.$axios('/api/gpb/version', { timeout: 15000, ignoreStatuses: [500, 502, 503, 504] });
  },
});

config.push({
  id: 'gpb-sdiz-service',
  callback() {
    return this.$axios('/api/sdiz/gpb/version', { timeout: 15000, ignoreStatuses: [500, 502, 503, 504] });
  },
});

config.push({
  id: 'lot-service',
  callback() {
    return this.$axios('/api/lot/version', { timeout: 15000, ignoreStatuses: [500, 502, 503, 504] });
  },
});

config.push({
  id: 'sgiz-service',
  callback() {
    return this.$axios('/api/sdiz/version', { timeout: 15000, ignoreStatuses: [500, 502, 503, 504] });
  },
});

config.push({
  id: 'notification-service',
  callback() {
    return this.$axios('/api/notification/version', { timeout: 15000, ignoreStatuses: [500, 502, 503, 504] });
  },
});

config.push({
  id: 'violation-service',
  callback() {
    return this.$axios('/api/violation/version', { timeout: 15000, ignoreStatuses: [500, 502, 503, 504] });
  },
});

config.push({
  id: 'security-service',
  callback() {
    return this.$axios('/api/security/info/version', { timeout: 15000, ignoreStatuses: [500, 502, 503, 504] });
  },
});

config.push({
  id: 'db',
  callback() {
    return this.$axios('/api/security/info/db/version', { timeout: 15000, ignoreStatuses: [500, 502, 503, 504] });
  },
});

config.push({
  id: 'gpbo-service',
  callback() {
    return this.$axios('/api/gpbo/version', { timeout: 15000, ignoreStatuses: [500, 502, 503, 504] });
  },
});

config.push({
  id: 'tarantool',
  callback() {
    return this.$axios('/api/cache/tarantool', {
      timeout: 15000,
      ignoreStatuses: [500, 502, 503, 504],
      responseType: 'text',
    });
  },
});

config.push({
  id: 'opendata-service',
  callback() {
    return this.$axios('/api/opendata/version', { timeout: 15000, ignoreStatuses: [500, 502, 503, 504] });
  },
});

config.push({
  id: 'cache-service',
  callback() {
    return this.$axios('/api/cache/version', { timeout: 15000, ignoreStatuses: [500, 502, 503, 504] });
  },
});

config.push({
  id: 'smev-send-service',
  callback() {
    return this.$axios('/api/smev/send/version', { timeout: 15000, ignoreStatuses: [500, 502, 503, 504] });
  },
});

config.push({
  id: 'reporting-service',
  callback() {
    return this.$axios('/api/reporting/version', { timeout: 10000, ignoreStatuses: [500, 502, 503, 504] });
  },
});

config.push({
  id: 'soap-service',
  callback() {
    return this.$axios('/api/soap-service/version', { timeout: 10000, ignoreStatuses: [500, 502, 503, 504] });
  },
});

config.push({
  id: 'api-service',
  callback() {
    return this.$axios('/api/api-service/version', { timeout: 10000, ignoreStatuses: [500, 502, 503, 504] });
  },
});

config.push({
  id: 'fts-service',
  callback() {
    return this.$axios('/api/declaration-info/version', { timeout: 10000, ignoreStatuses: [500, 502, 503, 504] });
  },
});

config.push({
  id: 'rshn-service',
  callback() {
    return this.$axios('/api/rshn/withdrawal/version', { timeout: 10000, ignoreStatuses: [500, 502, 503, 504] });
  },
});

config.push({
  id: 'export-service',
  callback() {
    return this.$axios('/api/export/version', { timeout: 10000, ignoreStatuses: [500, 502, 503, 504] });
  },
});

export default config;
