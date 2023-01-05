import {
  DocksEnum,
  ExpertiseEnum,
  LotRestrictionDataEnum,
  RestrictionsEnum,
  StatusEnum,
} from '@/utils/enums/RshnEnums';

export function setTranslateStatus(statusId) {
  switch (statusId) {
    case StatusEnum.CREATE:
      return 'Проект';
    case StatusEnum.SUBSCRIBED:
      return 'Подписано';
    case StatusEnum.CANCELED:
      return 'Аннулировано';
    default:
      return statusId;
  }
}

export function setTranslateStatusRestriction(statusId) {
  switch (statusId) {
    case StatusEnum.CREATE:
      return 'Проект';
    case StatusEnum.SUBSCRIBED:
      return 'Установлен';
    case StatusEnum.CANCELED:
      return 'Аннулирован';
    case StatusEnum.TAKEOFF:
      return 'Снят';
    default:
      return statusId;
  }
}

export function setTranslateRestrictions(restrictionType) {
  switch (restrictionType) {
    case RestrictionsEnum.FULL:
      return 'Полный запрет';
    case RestrictionsEnum.PARTIAL:
      return 'Частичный запрет';
    default:
      return restrictionType;
  }
}

export function setTranslatePrescriptionRestrictions(restrictionType) {
  switch (restrictionType) {
    case LotRestrictionDataEnum.PROCESSING_REQUIREMENT:
      return 'Требование переработки';
    case LotRestrictionDataEnum.BAN_FOOD_USE:
      return 'Запрет на использование в пищевых целях';
    case LotRestrictionDataEnum.BAN_FEED_USE:
      return 'Запрет на использование в кормовых целях';
    default:
      return restrictionType;
  }
}

export function setTranslateRestrictionsBin(restrictionBin) {
  switch (restrictionBin) {
    case 0:
      return 'Помещено';
    case 1:
      return 'Снято';
    default:
      return restrictionBin;
  }
}

export function setTranslateDocks(type_gd) {
  switch (type_gd) {
    case DocksEnum.TYPE_1:
      return 'Акт утилизации';
    case DocksEnum.TYPE_2:
      return 'Акт подработки';
    default:
      return type_gd;
  }
}

export function setTranslateExpertiseType(type) {
  switch (type) {
    case ExpertiseEnum.WITHDRAWAL:
      return 'При изъятии';
    case ExpertiseEnum.EXPORT:
      return 'При вывозе';
    case ExpertiseEnum.IMPORT:
      return 'При ввозе';
    default:
      return type;
  }
}
