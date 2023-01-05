export enum StatusEnum {
  CREATE = 1,
  SUBSCRIBED,
  CANCELED,
  TAKEOFF,
}

export enum WithdrawalTypeEnum {
  STORAGE = 1,
  SHIPPING,
  PRODUCT,
}
export enum ExpertiseEnum {
  WITHDRAWAL = 1,
  EXPORT,
  IMPORT,
}

export enum ExpertiseSdizType {
  SDIZ,
  GPB_SDIZ,
  NONE,
}

export enum ConformityEnum {
  INCONSISTENT,
  RELATED,
}

export enum BatchTypeEnum {
  LOT = 1,
  GBP,
}

export enum RestrictionsEnum {
  PARTIAL = 1,
  FULL,
}

export enum LotRestrictionDataEnum {
  PROCESSING_REQUIREMENT = 1,
  BAN_FOOD_USE,
  BAN_FEED_USE,
}

export enum ActivitiesWithdrawalEnum {
  PRESCRIPTIONS = 1,
  EXPERTISES,
  RESTRICTIONS,
}

export enum ActivitiesPrescriptionEnum {
  DOCKS = 1,
  EXCERPT,
}

export enum DocksEnum {
  TYPE_1 = 1,
  TYPE_2,
}

export enum RestrictionsBin {
  NO,
  YES,
}
