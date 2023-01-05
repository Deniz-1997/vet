import {
  getVerificationStatusName,
  getVerificationRegisterBySubjectType,
} from '@/components/SubjectVerification/utils';
import { ESubjectType, ESubjectVerificationStatus } from '@/services/enums/subject';

describe('SubjectVerification utils', () => {
  test('getVerificationRegisterBySubjectType', () => {
    const actual1 = getVerificationRegisterBySubjectType(ESubjectType.IF);
    expect(actual1).toBe('РАФП');
    const actual2 = getVerificationRegisterBySubjectType(ESubjectType.IP);
    expect(actual2).toBe('ЕГРИП');
    const actual3 = getVerificationRegisterBySubjectType(ESubjectType.IR);
    expect(actual3).toBe('РАФП');
    const actual4 = getVerificationRegisterBySubjectType(ESubjectType.UL);
    expect(actual4).toBe('ЕГРЮЛ');
    const actual5 = getVerificationRegisterBySubjectType(ESubjectType.UNKNOWN);
    expect(actual5).not.toBeDefined();
    const actual6 = getVerificationRegisterBySubjectType('' as ESubjectType);
    expect(actual6).not.toBeDefined();
  });

  test('getVerificationStatusName', () => {
    const actual1 = getVerificationStatusName({
      subject_type: ESubjectType.IF,
      subject_verification_status: { code: ESubjectVerificationStatus.NOT_VERIFIED },
    } as any);
    expect(actual1).toBe('Проверка не проводилась');
    const actual2 = getVerificationStatusName({
      subject_type: ESubjectType.IP,
      subject_verification_status: { code: ESubjectVerificationStatus.PROCESSING },
    } as any);
    expect(actual2).toBe('Отправлено на проверку (ЕГРИП)');
    const actual3 = getVerificationStatusName({
      subject_type: ESubjectType.IR,
      subject_verification_status: { code: ESubjectVerificationStatus.SUCCESS_VERIFICATION },
    } as any);
    expect(actual3).toBe('Данные подтверждены (РАФП)');
    const actual4 = getVerificationStatusName({
      subject_type: ESubjectType.UL,
      subject_verification_status: { code: ESubjectVerificationStatus.WRONG_DATA },
    } as any);
    expect(actual4).toBe('Есть расхождения (ЕГРЮЛ)');
    const actual5 = getVerificationStatusName({
      subject_type: ESubjectType.UNKNOWN,
      subject_verification_status: { code: ESubjectVerificationStatus.PROCESSING },
    } as any);
    expect(actual5).toBe('Отправлено на проверку (undefined)');
    const actual6 = getVerificationStatusName({
      subject_type: '' as ESubjectType,
      subject_verification_status: { code: ESubjectVerificationStatus.SUCCESS_VERIFICATION },
    } as any);
    expect(actual6).toBe('Данные подтверждены (undefined)');
  });
});
