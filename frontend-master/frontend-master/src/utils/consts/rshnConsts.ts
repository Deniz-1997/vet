import {
  ActivitiesPrescriptionEnum,
  ActivitiesWithdrawalEnum,
  DocksEnum,
  ExpertiseEnum,
  WithdrawalTypeEnum,
  RestrictionsBin,
  RestrictionsEnum,
  ExpertiseSdizType,
  StatusEnum,
  LotRestrictionDataEnum,
} from '@/utils/enums/RshnEnums';

export const rshnConsts = {
  statusList: [
    { name: 'Проект', id: StatusEnum.CREATE },
    { name: 'Подписан', id: StatusEnum.SUBSCRIBED },
    { name: 'Аннулирован', id: StatusEnum.CANCELED },
  ],
  typeWithdrawalList: [
    { name: 'При производстве', id: WithdrawalTypeEnum.PRODUCT },
    { name: 'При перевозке', id: WithdrawalTypeEnum.SHIPPING },
    { name: 'При хранении', id: WithdrawalTypeEnum.STORAGE },
  ],
  typeExpertiseList: [
    { name: 'При изяъятии', id: ExpertiseEnum.WITHDRAWAL },
    { name: 'При вывозе', id: ExpertiseEnum.EXPORT },
    { name: 'При ввозе', id: ExpertiseEnum.IMPORT },
  ],

  tabList: [
    { name: 'ПРИ ХРАНЕНИИ', type: WithdrawalTypeEnum.STORAGE },
    { name: 'ПРИ ПЕРЕВОЗКЕ', type: WithdrawalTypeEnum.SHIPPING },
    { name: 'ПРИ ПРОИЗВОДСТВЕ', type: WithdrawalTypeEnum.PRODUCT },
  ],
  tabListExpertise: [
    { name: 'ПРИ ИЗЪЯТИИ', type: ExpertiseEnum.WITHDRAWAL },
    { name: 'ПРИ ВЫВОЗЕ', type: ExpertiseEnum.EXPORT },
    { name: 'ПРИ ВВОЗЕ', type: ExpertiseEnum.IMPORT },
  ],
  tabListSdiz: [
    { name: 'СДИЗ партий зерна', type: ExpertiseSdizType.SDIZ },
    { name: 'СДИЗ партий переработки зерна', type: ExpertiseSdizType.GPB_SDIZ },
  ],
  tabListWithdrawalActivities: [
    { name: 'ПРЕДПИСАНИЯ', type: ActivitiesWithdrawalEnum.PRESCRIPTIONS },
    { name: 'ЭКСПЕРТИЗЫ', type: ActivitiesWithdrawalEnum.EXPERTISES },
    { name: 'ЗАПРЕТЫ ФОРМИРОВАНИЯ ПАРТИИИ', type: ActivitiesWithdrawalEnum.RESTRICTIONS },
  ],
  tabListPrescriptionActivities: [
    { name: 'ПОТВЕРЖДАЮЩИЕ ДОКУМЕНТЫ', type: ActivitiesPrescriptionEnum.DOCKS },
    // { name: 'ЭКСПЕРТИЗА', type: ActivitiesPrescriptionEnum.EXCERPT },
  ],

  typeRestrictions: [
    { name: 'Частичный запрет', id: RestrictionsEnum.PARTIAL },
    { name: 'Полный запрет', id: RestrictionsEnum.FULL },
  ],
  typePrescriptionRestrictions: [
    { name: 'Требование переработки', id: LotRestrictionDataEnum.PROCESSING_REQUIREMENT },
    { name: 'Запрет на использование в пищевых целях', id: LotRestrictionDataEnum.BAN_FOOD_USE },
    { name: 'Запрет на использование в кормовых целях', id: LotRestrictionDataEnum.BAN_FEED_USE },
  ],
  restrictionsBinList: [
    { name: 'Помещено', id: RestrictionsBin.NO },
    { name: 'Снято', id: RestrictionsBin.YES },
  ],

  //TODO в дальнейшем обращаться к  справочнику
  typePrescription: [
    { name: 'О возврате', id: 1 },
    { name: 'Об утилизации', id: 2 },
  ],
  //TODO в дальнейшем обращаться к  справочнику
  typeDocks: [
    { name: 'Акт утилизации', id: DocksEnum.TYPE_1 },
    { name: 'Акт подработки', id: DocksEnum.TYPE_2 },
  ],
};
