/** Тип организации. */
export enum ESubjectType {
  /** Юридическое лицо */
  UL = 'UL',
  /** Индивидуальный предприниматель */
  IP = 'IP',
  /** Ид. Иностранное лицо без регистрации в РФ */
  IR = 'IR',
  /** Филиал, представительство иностранного юридического лица */
  IF = 'IF',
  /** Неизвестно что */
  UNKNOWN = 'UNKNOWN',
}

/**
 * Статус проверки организации.
 *
 * Для проверок должен быть указан реестр, по которому осуществляется валидация:
 * - ЕГРЮЛ для российских ЮЛ @see SubjectType#UL;
 * - ЕГРИП для российских ИП @see SubjectType#IP;
 * - РАФП для аккредитованных филиалов @see SubjectType#IF, представительств иностранных ЮЛ @see SubjectType#IR.
 */
export enum ESubjectVerificationStatus {
  /** Проверка не проводилась. */
  NOT_VERIFIED = 'NOT_VERIFIED',
  /** Данные подтверждены. */
  SUCCESS_VERIFICATION = 'SUCCESS_VERIFICATION',
  /** Есть расхождения. */
  WRONG_DATA = 'WRONG_DATA',
  /** Отправлено на проверку. */
  PROCESSING = 'PROCESSING',
}
