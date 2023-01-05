import { AxiosPromise } from 'axios';

/** Список названий микросервисов */
export type TServiceName =
  | 'giszp-ui'
  | 'elevator-service'
  | 'approval-template-service'
  | 'approval_task_service'
  | 'workflow-service'
  | 'nci-service'
  | 'lot-service'
  | 'authorization-service'
  | 'sgiz-service'
  | 'gosmonitoring-service'
  | 'subject-service'
  | 'fias-service'
  | 'laboratory-service'
  | 'contract-service'
  | 'ogv-service'
  | 'esia-service'
  | 'directory-service'
  | 'manufacturer-service'
  | 'division-service'
  | 'gpb-sdiz-service'
  | 'gpb-service'
  | 'opendata-service'
  | 'smev-service'
  | 'notification-service'
  | 'violation-service'
  | 'security-service'
  | 'email-service'
  | 'db'
  | 'gpbo-service'
  | 'tarantool'
  | 'opendata-service'
  | 'cache-service'
  | 'smev-send-service'
  | 'reporting-service'
  | 'soap-service'
  | 'api-service'
  | 'fts-service'
  | 'rshn-service'
  | 'export-service';

/**
 * Метод получения версии сервиса.
 * @param ctx Контекст vue для обращения к параметрам конфигурации, стору, XHR.
 */
export type TVersionCallback<T> = (this: T) => AxiosPromise<string> | string;
export type TVersionProviderItem<T> = {
  /** Название микросервиса */
  id: TServiceName;
  /** Читаемое название, если необходимо */
  name?: string;
  callback: TVersionCallback<T>;
};
export type TVersionItem = {
  /** Название сервиса. */
  name: string;
  /** Результат выполнения метода получения версии сервиса. Содержит версию. */
  version: string;
  /** Флаг успешного получения данных. */
  active?: boolean;
  /** Идентификатор сервиса. */
  id: string;
};
