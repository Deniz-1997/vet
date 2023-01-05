import get from 'lodash/get';
import { TValidationRules } from '@/components/global/UiForm/models';
import { ESubjectType } from '@/services/enums/subject';

export default {
  subject_country: {
    message: 'Введенный адрес не может использоваться этой организацией',
    handler(value, subjectType: ESubjectType) {
      const addressCode = get(value, 'country.code_alpha2') === 'RU' ? 1 : 0;
      const companyCode = !subjectType || [ESubjectType.IR, ESubjectType.IF].includes(subjectType) ? 0 : 1;

      return !companyCode || addressCode === companyCode;
    },
  },
  maxFileSize: {
    message: 'Максимально допустимый размер файла -- :maxFileSize байт',
    handler(value: File, size: number) {
      return value.size <= size;
    },
  },
  fileType: {
    message: 'Недопустимый тип файла',
    handler(value: File, types: string[]) {
      return types.some((type) => value.type === type || value.name.split('.').pop()?.includes(type));
    },
  },
} as TValidationRules;
