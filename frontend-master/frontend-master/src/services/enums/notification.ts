/** Статус уведомления. */
export enum ENotificationStatus {
  /** Новое. */
  NEW = 'NEW',
  /** Прочитано. */
  READ = 'READ',
}

/** Тип уведомления. */
export enum ENotificationObjectType {
  /** Уведомление о нарушении. */
  VIOLATION = 'VIOLATION',
  /** Экспорт */
  EXPORT = 'EXPORT',
}
