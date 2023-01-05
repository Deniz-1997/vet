import { ESubjectType, ESubjectVerificationStatus } from '@/services/enums/subject';
import { ISubjectItem } from '@/services/models/common';

/** Список соотношения типов организаций к веривицирующим реестрам. */
const VerificationRegister = Object.freeze({
  [ESubjectType.UL]: 'ЕГРЮЛ',
  [ESubjectType.IP]: 'ЕГРИП',
  [ESubjectType.IF]: 'РАФП',
  [ESubjectType.IR]: 'РАФП',
});

/** Список соотношения статуса проверки к тектовому описанию. */
const VerificationStatusName = Object.freeze({
  [ESubjectVerificationStatus.NOT_VERIFIED]: 'Проверка не проводилась',
  [ESubjectVerificationStatus.PROCESSING]: 'Отправлено на проверку',
  [ESubjectVerificationStatus.SUCCESS_VERIFICATION]: 'Данные подтверждены',
  [ESubjectVerificationStatus.WRONG_DATA]: 'Есть расхождения',
});

/**
 * Получить название верифицирующего реестра по типу организации.
 * @param subjectType Тип организации.
 */
export const getVerificationRegisterBySubjectType = (subjectType: ESubjectType): string =>
  VerificationRegister[subjectType];

/**
 * Получить текстовое описание статуса проверки.
 * @param subject Проверяющаяся организация.
 */
export const getVerificationStatusName = ({ subject_type, verification_status }: ISubjectItem): string => {
  const text = VerificationStatusName[verification_status?.code ?? ESubjectVerificationStatus.NOT_VERIFIED];

  // Если проверка не была запущена, не отображать название реестра.
  if (verification_status?.code === ESubjectVerificationStatus.NOT_VERIFIED) {
    return text;
  }

  const register = getVerificationRegisterBySubjectType(subject_type);

  return `${text} (${register})`;
};
