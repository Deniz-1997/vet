/*
 * This file was generated automatically.
 * Don't change it manually.
 */

export enum EAction {
  /** Создание заявки на регистрацию организации в Реестре. */
  CREATE_REQUEST = 'CREATE_REQUEST',
  /** Редактирование заявки на регистрацию организации в Реестре. */
  UPDATE_REQUEST = 'UPDATE_REQUEST',
  /** Удаление заявки на регистрацию организации в Реестре. */
  DELETE_REQUEST = 'DELETE_REQUEST',
  /** Подписание заявки на регистрацию организации в Реестре ЭП и отправка на согласование. */
  SIGN_REQUEST = 'SIGN_REQUEST',
  /** Просмотр заявки на регистрацию организации в Реестре. */
  READ_REQUEST = 'READ_REQUEST',
  /** Просмотр списка заявок. */
  READ_REQUEST_REGISTER = 'READ_REQUEST_REGISTER',
  /** Экспорт списка заяок. */
  EXPORT_REQUEST_REGISTER = 'EXPORT_REQUEST_REGISTER',
  /** Создание заявки на изменение сведений об организации. */
  CREATE_CHANGE_REQUEST = 'CREATE_CHANGE_REQUEST',
  /** Редактирование заявки на  изменение сведений об организации. */
  UPDATE_CHANGE_REQUEST = 'UPDATE_CHANGE_REQUEST',
  /** Удаление заявки на  изменение сведений об организации. */
  DELETE_CHANGE_REQUEST = 'DELETE_CHANGE_REQUEST',
  /** Подписание заявки на  изменение сведений об организации ЭП и отправка на согласование. */
  SIGN_CHANGE_REQUEST = 'SIGN_CHANGE_REQUEST',
  /** Просмотр заявки на  изменение сведений об организации. */
  READ_CHANGE_REQUEST = 'READ_CHANGE_REQUEST',
  /** Просмотр изменений в заявке. */
  READ_CHANGE_REQUEST_DATA = 'READ_CHANGE_REQUEST_DATA',
  /** Список изменений в заявке. */
  READ_CHANGE_REQUEST_DATA_LIST = 'READ_CHANGE_REQUEST_DATA_LIST',
  /** Просмотр реестра мест первичного хранения. */
  READ_LOT_NUMBER_REGISTER = 'READ_LOT_NUMBER_REGISTER',
  /** Формирование места первичного хранения. */
  CREATE_LOT_NUMBER = 'CREATE_LOT_NUMBER',
  /** Просмотр реестра сведений о собранном урожае. */
  READ_GOSMONITORING_DATA_REGISTER = 'READ_GOSMONITORING_DATA_REGISTER',
  /** Фильтрация реестра сведений о собранном урожае. */
  FILTER_GOSMONITORING_DATA_REGISTER = 'FILTER_GOSMONITORING_DATA_REGISTER',
  /** Просмотр карточки сведений о собранном урожае. */
  READ_GOSMONITORING_DATA = 'READ_GOSMONITORING_DATA',
  /** Формирование сведений о собранном урожае. */
  CREATE_GOSMONITORING_DATA = 'CREATE_GOSMONITORING_DATA',
  /** Редактирование сведений о собранном урожае. */
  UPDATE_GOSMONITORING_DATA = 'UPDATE_GOSMONITORING_DATA',
  /** Удаление сведений сведений о собранном урожае. */
  DELETE_GOSMONITORING_DATA = 'DELETE_GOSMONITORING_DATA',
  /** Подписание сведений сведений о собранном урожае. */
  SIGN_GOSMONITORING_DATA = 'SIGN_GOSMONITORING_DATA',
  /** Аннулирование сведений сведений о собранном урожае. */
  CANCEL_GOSMONITORING_DATA = 'CANCEL_GOSMONITORING_DATA',
  /** Просмотр реестра проведенных исследований. */
  READ_MANUFACTURER_RESEARCH_REGISTER = 'READ_MANUFACTURER_RESEARCH_REGISTER',
  /** Фильтрация реестра проведенных исследований. */
  FILTER_MANUFACTURER_RESEARCH_REGISTER = 'FILTER_MANUFACTURER_RESEARCH_REGISTER',
  /** Просмотр карточки проведенного исследования. */
  READ_MANUFACTURER_RESEARCH_DATA = 'READ_MANUFACTURER_RESEARCH_DATA',
  /** Просмотр реестра партий зерна. */
  READ_GRAIN_LOT_REGISTER = 'READ_GRAIN_LOT_REGISTER',
  /** Фильтрация реестра партий зерна. */
  FILTER_GRAIN_LOT_REGISTER = 'FILTER_GRAIN_LOT_REGISTER',
  /** Просмотр карточки партии зерна. */
  READ_GRAIN_LOT_DATA = 'READ_GRAIN_LOT_DATA',
  /** Формирование партии зерна по результатам государственного мониторинга. */
  CREATE_GOSMONITORING_GRAIN_LOT = 'CREATE_GOSMONITORING_GRAIN_LOT',
  /** Формирование партии зерна из других партий. */
  CREATE_OTHER_LOTS_GRAIN_LOT = 'CREATE_OTHER_LOTS_GRAIN_LOT',
  /** Формирование партии зерна из остатков. */
  CREATE_SURPLUS_GRAIN_LOT = 'CREATE_SURPLUS_GRAIN_LOT',
  /** Формирование партии зерна при ввозе. */
  CREATE_IMPORT_GRAIN_LOT = 'CREATE_IMPORT_GRAIN_LOT',
  /** Формирование партии зерна на основании СДИЗ на бумажном носителе. */
  CREATE_SDIZ_GRAIN_LOT = 'CREATE_SDIZ_GRAIN_LOT',
  /** Редактирование партии зерна. */
  UPDATE_GRAIN_LOT = 'UPDATE_GRAIN_LOT',
  /** Удаление партии зерна. */
  DELETE_GRAIN_LOT = 'DELETE_GRAIN_LOT',
  /** Аннулирование партии зерна. */
  CANCEL_GRAIN_LOT = 'CANCEL_GRAIN_LOT',
  /** Списание остатков по партии зерна. */
  CANCEL_GRAIN_LOT_SURPLUS = 'CANCEL_GRAIN_LOT_SURPLUS',
  /** Просмотр реестра СДИЗ. */
  READ_SDIZ_REGISTER = 'READ_SDIZ_REGISTER',
  /** Фильтрация реестра СДИЗ. */
  FILTER_SDIZ_REGISTER = 'FILTER_SDIZ_REGISTER',
  /** Просмотр карточки СДИЗ (раздел "Поиск СДИЗ"). */
  READ_SDIZ = 'READ_SDIZ',
  /** Просмотр печатной формы СДИЗ. */
  READ_SDIZ_PRINT_FORM = 'READ_SDIZ_PRINT_FORM',
  /** Оформление СДИЗ. */
  CREATE_SDIZ = 'CREATE_SDIZ',
  /** Редактирование СДИЗ. */
  UPDATE_SDIZ = 'UPDATE_SDIZ',
  /** Удаление СДИЗ. */
  DELETE_SDIZ = 'DELETE_SDIZ',
  /** Подписание СДИЗ. */
  SIGN_SDIZ = 'SIGN_SDIZ',
  /** Аннулирование СДИЗ. */
  CANCEL_SDIZ = 'CANCEL_SDIZ',
  /** Погашение СДИЗ. */
  REPAYMENT_SDIZ = 'REPAYMENT_SDIZ',
  /** Просмотр реестра партий продуктов переработки зерна. */
  READ_GRAIN_PRODUCT_LOT_REGISTER = 'READ_GRAIN_PRODUCT_LOT_REGISTER',
  /** Фильтрация реестра партий продуктов переработки зерна. */
  FILTER_GRAIN_PRODUCT_LOT_REGISTER = 'FILTER_GRAIN_PRODUCT_LOT_REGISTER',
  /** Просмотр карточки партии продуктов переработки зерна. */
  READ_GRAIN_PRODUCT_LOT = 'READ_GRAIN_PRODUCT_LOT',
  /** Формирование партии продуктов переработки зерна при производстве. */
  CREATE_PRODUCTION_GRAIN_PRODUCT_LOT = 'CREATE_PRODUCTION_GRAIN_PRODUCT_LOT',
  /** Формирование партии продуктов переработки зерна из других партий. */
  CREATE_OTHER_LOT_GRAIN_PRODUCT_LOT = 'CREATE_OTHER_LOT_GRAIN_PRODUCT_LOT',
  /** Формирование партии продуктов переработки зерна из остатков. */
  CREATE_SURPLUS_GRAIN_PRODUCT_LOT = 'CREATE_SURPLUS_GRAIN_PRODUCT_LOT',
  /** Формирование партии продуктов переработки зерна при ввозе. */
  CREATE_IMPORT_GRAIN_PRODUCT_LOT = 'CREATE_IMPORT_GRAIN_PRODUCT_LOT',
  /** Формирование партии зерна на основании СДИЗ на ППЗ на бумажном носителе. */
  CREATE_SDIZ_GRAIN_PRODUCT_LOT = 'CREATE_SDIZ_GRAIN_PRODUCT_LOT',
  /** Редактирование партии продуктов переработки зерна. */
  UPDATE_GRAIN_PRODUCT_LOT = 'UPDATE_GRAIN_PRODUCT_LOT',
  /** Удаление партии продуктов переработки зерна. */
  DELETE_GRAIN_PRODUCT_LOT = 'DELETE_GRAIN_PRODUCT_LOT',
  /** Аннулирование партии продуктов переработки зерна. */
  CANCEL_GRAIN_PRODUCT_LOT = 'CANCEL_GRAIN_PRODUCT_LOT',
  /** Списание остатков по партии продуктов переработки зерна. */
  CANCEL_GRAIN_PRODUCT_LOT_SURPLUS = 'CANCEL_GRAIN_PRODUCT_LOT_SURPLUS',
  /** Просмотр реестра СДИЗ на ППЗ. */
  READ_SDIZ_ON_PPZ_REGISTER = 'READ_SDIZ_ON_PPZ_REGISTER',
  /** Фильтрация реестра СДИЗ на ППЗ. */
  FILTER_SDIZ_ON_PPZ_REGISTER = 'FILTER_SDIZ_ON_PPZ_REGISTER',
  /** Просмотр карточки СДИЗ на ППЗ. */
  READ_SDIZ_ON_PPZ = 'READ_SDIZ_ON_PPZ',
  /** Просмотр печатной формы СДИЗ на ППЗ. */
  READ_SDIZ_ON_PPZ_PRINT_FORM = 'READ_SDIZ_ON_PPZ_PRINT_FORM',
  /** Оформление СДИЗ на ППЗ. */
  CREATE_SDIZ_ON_PPZ = 'CREATE_SDIZ_ON_PPZ',
  /** Редактирование СДИЗ на ППЗ. */
  UPDATE_SDIZ_ON_PPZ = 'UPDATE_SDIZ_ON_PPZ',
  /** Удаление СДИЗ на ППЗ. */
  DELETE_SDIZ_ON_PPZ = 'DELETE_SDIZ_ON_PPZ',
  /** Подписание СДИЗ на ППЗ. */
  SEND_SDIZ_ON_PPZ = 'SEND_SDIZ_ON_PPZ',
  /** Аннулирование СДИЗ на ППЗ. */
  CANCEL_SDIZ_ON_PPZ = 'CANCEL_SDIZ_ON_PPZ',
  /** Погашение СДИЗ на ППЗ. */
  REPAYMENTS_SDIZ_ON_PPZ = 'REPAYMENTS_SDIZ_ON_PPZ',
  /** Просмотр реестра партий зерна на хранении. */
  READ_GRAIN_LOT_STORAGE_REGISTER = 'READ_GRAIN_LOT_STORAGE_REGISTER',
  /** Фильтрация реестра партий зерна на хранении. */
  FILTER_GRAIN_LOT_STORAGE_REGISTER = 'FILTER_GRAIN_LOT_STORAGE_REGISTER',
  /** Просмотр карточки партии зерна на хранении. */
  READ_GRAIN_LOT_STORAGE = 'READ_GRAIN_LOT_STORAGE',
  /** Формирование партии зерна из других партий (реестр партий зерна на хранении). */
  CREATE_GRAIN_PARTIES_OTHER_PARTIES_STORAGE = 'CREATE_GRAIN_PARTIES_OTHER_PARTIES_STORAGE',
  /** Формирование партии зерна из остатков  (реестр партий зерна на хранении). */
  CREATE_GRAIN_PARTIES_SURPLUS_STORAGE = 'CREATE_GRAIN_PARTIES_SURPLUS_STORAGE',
  /** Редактирование партии зерна на хранении. */
  UPDATE_GRAIN_LOT_STORAGE = 'UPDATE_GRAIN_LOT_STORAGE',
  /** Удаление партии зерна на хранении. */
  DELETE_GRAIN_LOT_STORAGE = 'DELETE_GRAIN_LOT_STORAGE',
  /** Подписание партии зерна на хранении. */
  SIGN_GRAIN_LOT_STORAGE = 'SIGN_GRAIN_LOT_STORAGE',
  /** Аннулирование партии зерна на хранении. */
  CANCEL_GRAIN_LOT_STORAGE = 'CANCEL_GRAIN_LOT_STORAGE',
  /** Списание партии зерна на хранении. */
  OFF_GRAIN_LOT_STORAGE = 'OFF_GRAIN_LOT_STORAGE',
  /** Просмотр реестра СДИЗ при хранении. */
  READ_SDIZ_STORAGE_REGISTER = 'READ_SDIZ_STORAGE_REGISTER',
  /** Фильтрация реестра СДИЗ при хранении. */
  FILTER_SDIZ_STORAGE_REGISTER = 'FILTER_SDIZ_STORAGE_REGISTER',
  /** Просмотр карточки СДИЗ при хранении. */
  READ_SDIZ_STORAGE = 'READ_SDIZ_STORAGE',
  /** Просмотр печатной формы СДИЗ при хранении. */
  READ_SDIZ_STORAGE_PRINT_FORM = 'READ_SDIZ_STORAGE_PRINT_FORM',
  /** Оформление СДИЗ при хранении. */
  CREATE_SDIZ_STORAGE = 'CREATE_SDIZ_STORAGE',
  /** Редактирование СДИЗ при хранении. */
  UPDATE_SDIZ_STORAGE = 'UPDATE_SDIZ_STORAGE',
  /** Удаление СДИЗ при хранении. */
  DELETE_SDIZ_STORAGE = 'DELETE_SDIZ_STORAGE',
  /** Подписание СДИЗ при хранении. */
  SIGN_SDIZ_STORAGE = 'SIGN_SDIZ_STORAGE',
  /** Аннулирование СДИЗ при хранении. */
  CANCEL_SDIZ_STORAGE = 'CANCEL_SDIZ_STORAGE',
  /** Погашение СДИЗ при хранении. */
  REPAYMENT_SDIZ_STORAGE = 'REPAYMENT_SDIZ_STORAGE',
  /** Просмотр реестра товаропроизводителей. */
  READ_MANUFACTURER_REGISTER = 'READ_MANUFACTURER_REGISTER',
  /** Фильтрация реестра товаропроизводителей. */
  FILTER_MANUFACTURER_REGISTER = 'FILTER_MANUFACTURER_REGISTER',
  /** Настройка вида реестра товаропроизводителей. */
  SETTING_MANUFACTURER_REGISTER = 'SETTING_MANUFACTURER_REGISTER',
  /** Экспорт реестра товаропроизводителей. */
  EXPORT_MANUFACTURER_REGISTER = 'EXPORT_MANUFACTURER_REGISTER',
  /** Исключение товаропроизводителя из реестра. */
  DELETE_MANUFACTURER = 'DELETE_MANUFACTURER',
  /** Просмотр карточки товаропроизводителя. */
  READ_MANUFACTURER = 'READ_MANUFACTURER',
  /** Добавление товаропроизводителя в реестр. */
  CREATE_MANUFACTURER = 'CREATE_MANUFACTURER',
  /** Изменение сведений о товаропроизводителе. */
  CHANGE_MANUFACTURER = 'CHANGE_MANUFACTURER',
  /** Просмотр реестра организаций. */
  READ_ORGANIZATION_REGISTER = 'READ_ORGANIZATION_REGISTER',
  /** Фильтрация реестра организаций. */
  FILTER_ORGANIZATION_REGISTER = 'FILTER_ORGANIZATION_REGISTER',
  /** Настройка вида реестра организаций. */
  CUSTOMIZE_ORGANIZATION_REGISTER = 'CUSTOMIZE_ORGANIZATION_REGISTER',
  /** Экспорт реестра организаций. */
  EXPORT_ORGANIZATION_REGISTER = 'EXPORT_ORGANIZATION_REGISTER',
  /** Исключение организации из реестра . */
  DELETE_ORGANIZATION = 'DELETE_ORGANIZATION',
  /** Просмотр карточки организаций. */
  READ_ORGANIZATION = 'READ_ORGANIZATION',
  /** Формирование выписки из реестра организаций. */
  GENERATE_ORGANIZATION_REGISTER_REPORT = 'GENERATE_ORGANIZATION_REGISTER_REPORT',
  /** Просмотр списка задач. */
  READ_TASK_REGISTER = 'READ_TASK_REGISTER',
  /** Просмотр списка задач подразделения. */
  READ_TASK_REGISTER_DIVISION = 'READ_TASK_REGISTER_DIVISION',
  /** Фильтрация списка задач. */
  FILTER_TASK_REGISTER = 'FILTER_TASK_REGISTER',
  /** Настройка вида списка задач. */
  CUSTOMIZE_TASK_REGISTER = 'CUSTOMIZE_TASK_REGISTER',
  /** Экспорт списка задач. */
  EXPORT_TASK_REGISTER = 'EXPORT_TASK_REGISTER',
  /** Согласование задачи. */
  APPROVE_TASK = 'APPROVE_TASK',
  /** Отклонение задачи. */
  REJECT_TASK = 'REJECT_TASK',
  /** Просмотр карточки задачи. */
  READ_TASK = 'READ_TASK',
  /** Назначить ответственного в задаче. */
  ASSIGN_TASK_EXECUTOR = 'ASSIGN_TASK_EXECUTOR',
  /** Снять ответственного в задаче. */
  UNASSIGN_TASK_EXECUTOR = 'UNASSIGN_TASK_EXECUTOR',
  /** Просмотр списка шаблонов согласования. */
  READ_APPROVAL_TEMPLATE_REGISTER = 'READ_APPROVAL_TEMPLATE_REGISTER',
  /** Фильтрация списка шаблонов согласования. */
  FILTER_APPROVAL_TEMPLATE_REGISTER = 'FILTER_APPROVAL_TEMPLATE_REGISTER',
  /** Создание шаблона согласования. */
  CREATE_APPROVAL_TEMPLATE = 'CREATE_APPROVAL_TEMPLATE',
  /** Изменение шаблона согласования. */
  UPDATE_APPROVAL_TEMPLATE = 'UPDATE_APPROVAL_TEMPLATE',
  /** Отключение шаблона согласования. */
  DISABLE_APPROVAL_TEMPLATE = 'DISABLE_APPROVAL_TEMPLATE',
  /** Включение шаблона согласования. */
  ENABLE_APPROVAL_TEMPLATE = 'ENABLE_APPROVAL_TEMPLATE',
  /** Удаление шаблона согласования. */
  DELETE_APPROVAL_TEMPLATE = 'DELETE_APPROVAL_TEMPLATE',
  /** Просмотр списка справочников. */
  READ_DICTIONARY_REGISTER = 'READ_DICTIONARY_REGISTER',
  /** Просмотр  справочника. */
  READ_DICTIONARY = 'READ_DICTIONARY',
  /** Просмотр записи справочника. */
  READ_DICTIONARY_RECORD = 'READ_DICTIONARY_RECORD',
  /** Добавление новой записи в справочник. */
  CREATE_DICTIONARY_RECORD = 'CREATE_DICTIONARY_RECORD',
  /** Изменение записи в справочнике. */
  UPDATE_DICTIONARY_RECORD = 'UPDATE_DICTIONARY_RECORD',
  /** Удаление записи справочника. */
  DELETE_DICTIONARY_RECORD = 'DELETE_DICTIONARY_RECORD',
  /** Просмотр реестра лабораторий. */
  READ_LABORATORY_REGISTER = 'READ_LABORATORY_REGISTER',
  /** Фильтрация реестра лабораторий. */
  FILTER_LABORATORY_REGISTER = 'FILTER_LABORATORY_REGISTER',
  /** Настройка вида реестра лабораторий. */
  CUSTOMIZE_LABORATORY_REGISTER = 'CUSTOMIZE_LABORATORY_REGISTER',
  /** Экспорт реестра лабораторий. */
  EXPORT_LABORATORY_REGISTER = 'EXPORT_LABORATORY_REGISTER',
  /** Исключение лаборатории из реестра. */
  DELETE_LABORATORY = 'DELETE_LABORATORY',
  /** Просмотр карточки лаборатории. */
  READ_LABORATORY = 'READ_LABORATORY',
  /** Изменение карточки лаборатории. */
  UPDATE_LABORATORY = 'UPDATE_LABORATORY',
  /** Добавление лаборатории в реестр. */
  CREATE_LABORATORY = 'CREATE_LABORATORY',
  /** Просмотр реестра номеров СДИЗ. */
  READ_SDIZ_NUMBER_REGISTER = 'READ_SDIZ_NUMBER_REGISTER',
  /** Формирование номера СДИЗ. */
  CREATE_SDIZ_NUMBER = 'CREATE_SDIZ_NUMBER',
  /** Просмотр реестра выданных номеров партий зерна. */
  READ_GRAIN_NUMBER_REGISTER = 'READ_GRAIN_NUMBER_REGISTER',
  /** Формирование номера партии зерна. */
  CREATE_GRAIN_NUMBER = 'CREATE_GRAIN_NUMBER',
  /** Просмотр реестра выданных номеров партий продуктов переработки зерна. */
  READ_GRAIN_PRODUCT_NUMBER_REGISTER = 'READ_GRAIN_PRODUCT_NUMBER_REGISTER',
  /** Формирование номера партии продуктов переработки зерна. */
  CREATE_GRAIN_PRODUCT_NUMBER = 'CREATE_GRAIN_PRODUCT_NUMBER',
  /** Просмотр реестра поданных сведений товаропроизводителями. */
  READ_MANUFACTURER_DATA_REGISTER = 'READ_MANUFACTURER_DATA_REGISTER',
  /** Фильтрация реестра поданных сведений товаропроизводителями. */
  FILTER_MANUFACTURER_DATA_REGISTER = 'FILTER_MANUFACTURER_DATA_REGISTER',
  /** Просмотр карточки поданных сведений товаропроизводителями . */
  READ_MANUFACTURER_DATA = 'READ_MANUFACTURER_DATA',
  /** Просмотр реестра проведенных исследований. */
  READ_RESEARCH_REGISTER = 'READ_RESEARCH_REGISTER',
  /** Фильтрация реестра проведенных исследований. */
  FILTER_RESEARCH_REGISTER = 'FILTER_RESEARCH_REGISTER',
  /** Просмотр карточки проведенного исследования. */
  READ_RESEARCH_DATA = 'READ_RESEARCH_DATA',
  /** Формирование сведений проведенного исследования. */
  CREATE_RESEARCH_DATA = 'CREATE_RESEARCH_DATA',
  /** Редактирование сведений проведенного исследования. */
  UPDATE_RESEARCH_DATA = 'UPDATE_RESEARCH_DATA',
  /** Удаление сведений проведенного исследования. */
  DELETE_RESEARCH_DATA = 'DELETE_RESEARCH_DATA',
  /** Подписание сведений проведенного исследования. */
  SIGN_RESEARCH_DATA = 'SIGN_RESEARCH_DATA',
  /** Аннулирование сведений проведенного исследования. */
  CANCEL_RESEARCH_DATA = 'CANCEL_RESEARCH_DATA',
  /** Просмотр реестра сведений, подаваемых агентом. */
  READ_AGENT_DATA_REGISTER = 'READ_AGENT_DATA_REGISTER',
  /** Фильтрация реестра сведений, подаваемых агентом. */
  FILTER_AGENT_DATA_REGISTER = 'FILTER_AGENT_DATA_REGISTER',
  /** Просмотр карточки сведений, подаваемых агентом. */
  READ_AGENT_DATA = 'READ_AGENT_DATA',
  /** Подача  сведений агентом. */
  CREATE_AGENT_DATA = 'CREATE_AGENT_DATA',
  /** Редактирование сведений, подаваемых агентом. */
  UPDATE_AGENT_DATA = 'UPDATE_AGENT_DATA',
  /** Удаление сведений, подаваемых агентом. */
  DELETE_AGENT_DATA = 'DELETE_AGENT_DATA',
  /** Подписание  сведений, подаваемых агентом. */
  SIGN_AGENT_DATA = 'SIGN_AGENT_DATA',
  /** Аннулирование сведений, подаваемых агентом. */
  CANCEL_AGENT_DATA = 'CANCEL_AGENT_DATA',
  /** Просмотр реестра Органов государственной власти. */
  READ_GOV_ORG_REGISTER = 'READ_GOV_ORG_REGISTER',
  /** Фильтрация реестра Органов государственной власти. */
  FILTER_GOV_ORG_REGISTER = 'FILTER_GOV_ORG_REGISTER',
  /** Настройка вида реестра Органов государственной власти. */
  CUSTOMIZE_GOV_ORG_REGISTER = 'CUSTOMIZE_GOV_ORG_REGISTER',
  /** Экспорт реестра Органов государственной власти. */
  EXPORT_GOV_ORG_REGISTER = 'EXPORT_GOV_ORG_REGISTER',
  /** Просмотр данных организации, являющейся органом гос. власти. */
  READ_GOV_ORG = 'READ_GOV_ORG',
  /** Добавление новой организации, являющейся органом гос. власти в реестр. */
  CREATE_GOV_ORG = 'CREATE_GOV_ORG',
  /** Редактирование данных органа гос. власти. */
  UPDATE_GOV_ORG = 'UPDATE_GOV_ORG',
  /** Добавление новой учетной записи. */
  CREATE_USER_ACCOUNT = 'CREATE_USER_ACCOUNT',
  /** Редактирование учетной записи. */
  UPDATE_USER_ACCOUNT = 'UPDATE_USER_ACCOUNT',
  /** Деактивация учетной записи. */
  DEACTIVATE_USER_ACCOUNT = 'DEACTIVATE_USER_ACCOUNT',
  /** Активация учетной записи. */
  ACTIVATE_USER_ACCOUNT = 'ACTIVATE_USER_ACCOUNT',
  /** Удаление учетной записи. */
  DELETE_USER_ACCOUNT = 'DELETE_USER_ACCOUNT',
  /** Управление полномочиями пользователя. */
  UPDATE_USER_PRIVILEGE = 'UPDATE_USER_PRIVILEGE',
  /** Просмотр эл. журналов учета операций. */
  READ_DIGITAL_ACCOUNTING_LOG = 'READ_DIGITAL_ACCOUNTING_LOG',
  /** Просмотр журнала взаимодействия со СМЭВ. */
  READ_SMEV_LOG = 'READ_SMEV_LOG',
  /** Просмотр журнала загрузки ФИАС. */
  READ_FIAS_UPLOAD = 'READ_FIAS_UPLOAD',
  /** Инициация загрузки ФИАС. */
  CREATE_FIAS_UPLOAD = 'CREATE_FIAS_UPLOAD',
  /** Изменение параметров задачи загрузки ФИАС. */
  UPDATE_FIAS_UPLOAD = 'UPDATE_FIAS_UPLOAD',
  /** Изменение оргструктуры организации. */
  UPDATE_ORGANIZATION_STRUCTURE = 'UPDATE_ORGANIZATION_STRUCTURE',
  /** Просмотр реестра контрактов с агентами. */
  READ_AGENT_CONTRACT_REGISTER = 'READ_AGENT_CONTRACT_REGISTER',
  /** Фильтрация реестра контрактов с агентами. */
  FILTER_AGENT_CONTRACT_REGISTER = 'FILTER_AGENT_CONTRACT_REGISTER',
  /** Удаление контракта с агентом из реестра. */
  DELETE_AGENT_CONTRACT = 'DELETE_AGENT_CONTRACT',
  /** Просмотр карточки контракта. */
  READ_AGENT_CONTRACT = 'READ_AGENT_CONTRACT',
  /** Добавление контракта с агентом в реестр. */
  UPDATE_AGENT_CONTRACT_REGISTER = 'UPDATE_AGENT_CONTRACT_REGISTER',
  /** Изменение сведений о контракте с агентом. */
  UPDATE_AGENT_CONTRACT = 'UPDATE_AGENT_CONTRACT',
  /** Поиск по товаропроизводителям и контрагентам. */
  SEARCH_MANUFACTURER = 'SEARCH_MANUFACTURER',
  /** Поиск по организациям, осуществляющим хранение зерна. */
  SEARCH_ELEVATOR = 'SEARCH_ELEVATOR',
  /** Поиск по номерам СДИЗ и партий госрезерва. */
  SEARCH_FEDERAL_NUMBERS = 'SEARCH_FEDERAL_NUMBERS',
  /** Просмотр реестра уведомлений. */
  VIEW_NOTIFICATION_REGISTRY = 'VIEW_NOTIFICATION_REGISTRY',
  /** Просмотр реестра уведомлений (только уведомления пользователя). */
  VIEW_NOTIFICATION_USER_REGISTRY = 'VIEW_NOTIFICATION_USER_REGISTRY',
  /** Фильтрация реестра уведомлений. */
  FILTER_NOTIFICATION_REGISTRY = 'FILTER_NOTIFICATION_REGISTRY',
  /** Настройка вида реестра уведомлений. */
  CUSTOMIZE_NOTIFICATION_REGISTRY = 'CUSTOMIZE_NOTIFICATION_REGISTRY',
  /** Экспорт реестра уведомлений. */
  EXPORT_NOTIFICATION_REGISTRY = 'EXPORT_NOTIFICATION_REGISTRY',
  /** Просмотр карточки уведомления. */
  VIEW_NOTIFICATION = 'VIEW_NOTIFICATION',
  /** Просмотр реестра пользователей. */
  VIEW_USER_REGISTRY = 'VIEW_USER_REGISTRY',
  /** Фильтрация реестра пользователей. */
  FILTER_USER_REGISTRY = 'FILTER_USER_REGISTRY',
  /** Настройка вида реестра пользователей. */
  CUSTOMIZE_USER_REGISTRY = 'CUSTOMIZE_USER_REGISTRY',
  /** Экспорт реестра пользователей. */
  EXPORT_USER_REGISTRY = 'EXPORT_USER_REGISTRY',
  /** Просмотр карточки пользователя. */
  VIEW_USER_ACCOUNT = 'VIEW_USER_ACCOUNT',
  /** Просмотр реестра сведений, подаваемых агентом. */
  VIEW_AGENT_REGISTER = 'VIEW_AGENT_REGISTER',
  /** Фильтрация реестра сведений, подаваемых агентом. */
  FILTER_AGENT_REGISTER = 'FILTER_AGENT_REGISTER',
  /** Просмотр карточки сведений, предоставляемых агентом. */
  VIEW_AGENT_DATA = 'VIEW_AGENT_DATA',
  /** Просмотр результатов поиска номеров СДИЗ. */
  VIEW_SDIZ_SEARCH_RESULT = 'VIEW_SDIZ_SEARCH_RESULT',
  /** Просмотр результатов поиска номеров партий зерна. */
  VIEW_NUMBERS_SEARCH_RESULT = 'VIEW_NUMBERS_SEARCH_RESULT',
  /** Просмотр результатов поиска номеров партий продуктов переработки зерна. */
  VIEW_GPB_NUMBERS_SEARCH_RESULT = 'VIEW_GPB_NUMBERS_SEARCH_RESULT',
  /** Просмотр реестра ролей. */
  VIEW_ROLE = 'VIEW_ROLE',
  /** Фильтрация реестра ролей. */
  FILTER_ROLE = 'FILTER_ROLE',
  /** Настройка вида реестра ролей. */
  CUSTOMIZE_ROLE = 'CUSTOMIZE_ROLE',
  /** Просмотр журнала согласований. */
  READ_APPROVAL_REQUEST_LOG_REGISTRY = 'READ_APPROVAL_REQUEST_LOG_REGISTRY',
  /** Фильтрация журнала согласований. */
  FILTER_APPROVAL_REQUEST_LOG_REGISTRY = 'FILTER_APPROVAL_REQUEST_LOG_REGISTRY',
  /** Кастомизация журнала согласований. */
  CUSTOMIZE_APPROVAL_REQUEST_LOG_REGISTRY = 'CUSTOMIZE_APPROVAL_REQUEST_LOG_REGISTRY',
  /** Просмотр реестра производств продукции, не подлежащей учету. */
  READ_GRAIN_PROCESSING_BATCH_OUT_REGISTRY = 'READ_GRAIN_PROCESSING_BATCH_OUT_REGISTRY',
  /** Фильтрация реестра производств продукции, не подлежащей учету. */
  FILTER_GRAIN_PROCESSING_BATCH_OUT_REGISTRY = 'FILTER_GRAIN_PROCESSING_BATCH_OUT_REGISTRY',
  /** Просмотр карточки производства продукции, не подлежащей учету. */
  READ_GRAIN_PROCESSING_BATCH_OUT = 'READ_GRAIN_PROCESSING_BATCH_OUT',
  /** Формирование партии производства продукции, не подлежащей учету. */
  CREATE_GRAIN_PROCESSING_BATCH_OUT = 'CREATE_GRAIN_PROCESSING_BATCH_OUT',
  /** Редактирование производства продукции, не подлежащей учету. */
  UPDATE_GRAIN_PROCESSING_BATCH_OUT = 'UPDATE_GRAIN_PROCESSING_BATCH_OUT',
  /** Удалениепроизводства продукции, не подлежащей учету. */
  DELETE_GRAIN_PROCESSING_BATCH_OUT = 'DELETE_GRAIN_PROCESSING_BATCH_OUT',
  /** Аннулирование производства продукции, не подлежащей учету. */
  CANCEL_GRAIN_PROCESSING_BATCH_OUT = 'CANCEL_GRAIN_PROCESSING_BATCH_OUT',
  /** Просмотр реестра "Поиск СДИЗ". */
  READ_SEARCH_SDIZ_REGISTRY = 'READ_SEARCH_SDIZ_REGISTRY',
  /** Фильтрация реестра "Поиск СДИЗ". */
  FILTER_SEARCH_SDIZ_REGISTRY = 'FILTER_SEARCH_SDIZ_REGISTRY',
  /** Чтение журнала загрузок. */
  READ_IMPORT_LOG_REGISTRY = 'READ_IMPORT_LOG_REGISTRY',
  /** Фильтрация журнала загрузок. */
  FILTER_IMPORT_LOG_REGISTRY = 'FILTER_IMPORT_LOG_REGISTRY',
  /** Кастомизация журнала загрузок. */
  CUSTOMIZE_IMPORT_LOG_REGISTRY = 'CUSTOMIZE_IMPORT_LOG_REGISTRY',
  /** Импорт. */
  CREATE_IMPORT_RECORD = 'CREATE_IMPORT_RECORD',
  /** Поиск СДИЗ по номеру. */
  FIND_SDIZ_BY_NUMBER = 'FIND_SDIZ_BY_NUMBER',
  /** Оформление изъятия. */
  CREATE_WITHDRAWAL = 'CREATE_WITHDRAWAL',
  /** Редактирование изъятия. */
  UPDATE_WITHDRAWAL = 'UPDATE_WITHDRAWAL',
  /** Удаление изъятия. */
  DELETE_WITHDRAWAL = 'DELETE_WITHDRAWAL',
  /** Подписание изъятия. */
  SIGN_WITHDRAWAL = 'SIGN_WITHDRAWAL',
  /** Аннулирование изъятия. */
  CANCEL_WITHDRAWAL = 'CANCEL_WITHDRAWAL',
  /** Оформление предписания. */
  CREATE_PRESCRIPTION = 'CREATE_PRESCRIPTION',
  /** Редактирование предписания. */
  UPDATE_PRESCRIPTION = 'UPDATE_PRESCRIPTION',
  /** Удаление предписания. */
  DELETE_PRESCRIPTION = 'DELETE_PRESCRIPTION',
  /** Подписание предписания. */
  SIGN_PRESCRIPTION = 'SIGN_PRESCRIPTION',
  /** Аннулирование предписания. */
  CANCEL_PRESCRIPTION = 'CANCEL_PRESCRIPTION',
  /** Оформление экспертизы. */
  CREATE_EXPERTISE = 'CREATE_EXPERTISE',
  /** Редактирование экспертизы. */
  UPDATE_EXPERTISE = 'UPDATE_EXPERTISE',
  /** Удаление экспертизы. */
  DELETE_EXPERTISE = 'DELETE_EXPERTISE',
  /** Подписание экспертизы. */
  SIGN_EXPERTISE = 'SIGN_EXPERTISE',
  /** Аннулирование экспертизы. */
  CANCEL_EXPERTISE = 'CANCEL_EXPERTISE',
  /** Оформление запрета. */
  SIGN_RESTRICTIONS = 'SIGN_RESTRICTIONS',
  /** Снятие запрета. */
  TAKE_OFF_RESTRICTIONS = 'TAKE_OFF_RESTRICTIONS',
  /** Аннулирование изъятия. */
  CANCEL_RESTRICTIONS = 'CANCEL_RESTRICTIONS',
  /** Подтверждение СДИЗ. */
  CONFIRM_SDIZ = 'CONFIRM_SDIZ',
  /** Просмотр реестра системных сертификатов. */
  READ_SYSTEM_CERTIFICATES_REGISTRY = 'READ_SYSTEM_CERTIFICATES_REGISTRY',
  /** Просмотр карточки системного сертификата. */
  READ_SYSTEM_CERTIFICATE = 'READ_SYSTEM_CERTIFICATE',
  /** Добавление системного сертификата. */
  CREATE_SYSTEM_CERTIFICATE = 'CREATE_SYSTEM_CERTIFICATE',
  /** Удаление системного сертификата. */
  DELETE_SYSTEM_CERTIFICATE = 'DELETE_SYSTEM_CERTIFICATE',
  /** Проверка системного сертификата. */
  CHECK_SYSTEM_CERTIFICATE = 'CHECK_SYSTEM_CERTIFICATE',
  /** Просмотр реестра сертификатов безопасности организации. */
  READ_ORGANIZATION_CERTIFICATES_REGISTRY = 'READ_ORGANIZATION_CERTIFICATES_REGISTRY',
  /** Просмотр карточки сертификата безопасности организации. */
  READ_ORGANIZATION_CERTIFICATE = 'READ_ORGANIZATION_CERTIFICATE',
  /** Добавление сертификата безопасности организации. */
  CREATE_ORGANIZATION_CERTIFICATE = 'CREATE_ORGANIZATION_CERTIFICATE',
  /** Удаление сертификата безопасности организации. */
  DELETE_ORGANIZATION_CERTIFICATE = 'DELETE_ORGANIZATION_CERTIFICATE',
  /** Проверка сертификата безопасности организации. */
  CHECK_ORGANIZATION_CERTIFICATE = 'CHECK_ORGANIZATION_CERTIFICATE',
  /** Просмотр карточки организации в пункте "Моя организация". */
  READ_ORGANIZATION_CARD = 'READ_ORGANIZATION_CARD',
  /** Экспорт реестра контрактов с агентами. */
  EXPORT_AGENT_CONTRACT_REGISTER = 'EXPORT_AGENT_CONTRACT_REGISTER',
  /** Аннулирование сведений оператором и предоставление новых на партию зерна. */
  CANCELED_LOT_BY_OPERATOR = 'CANCELED_LOT_BY_OPERATOR',
  /** Аннулирование сведений оператором и предоставление новых на партию переработки зерна. */
  CANCELED_GBP_BY_OPERATOR = 'CANCELED_GBP_BY_OPERATOR',
  /** Аннулирование сведений оператором и предоставление новых на результаты исследования партии зерна при проведение государственного мониторинга. */
  CANCELED_RESERACH_BY_OPERATOR = 'CANCELED_RESERACH_BY_OPERATOR',
  /** Просмотр реестра деклараций . */
  READ_DECLARATION_REGISTER = 'READ_DECLARATION_REGISTER',
  /** Фильтрация реестра деклараций. */
  FILTER_DECLARATION_REGISTER = 'FILTER_DECLARATION_REGISTER',
  /** Настройка вида реестра деклараций. */
  CUSTOMIZE_DECLARATION_REGISTER = 'CUSTOMIZE_DECLARATION_REGISTER',
  /** Экспорт реестра деклараций. */
  EXPORT_DECLARATION_REGISTER = 'EXPORT_DECLARATION_REGISTER',
  /** Просмотр реестра жалоб. */
  READ_COMPLAINT_REGISTER = 'READ_COMPLAINT_REGISTER',
  /** Фильтрация реестра жалоб. */
  FILTER_COMPLAINT_REGISTER = 'FILTER_COMPLAINT_REGISTER',
  /** Настройка вида реестра жалоб. */
  CUSTOMIZE_COMPLAINT_REGISTER = 'CUSTOMIZE_COMPLAINT_REGISTER',
  /** Экспорт реестра жалоб. */
  EXPORT_COMPLAINT_REGISTER = 'EXPORT_COMPLAINT_REGISTER',
  /** Просмотр карточки жалобы. */
  READ_COMPLAINT = 'READ_COMPLAINT',
  /** Просмотр реестра запросов
. */
  READ_FREE_FORM_REGISTER = 'READ_FREE_FORM_REGISTER',
  /** Просмотр карточки запроса. */
  READ_FREE_FORM = 'READ_FREE_FORM',
  /** Создание запроса. */
  CREATE_FREE_FORM = 'CREATE_FREE_FORM',
  /** Обработка запроса
. */
  ANSWER_FREE_FORM = 'ANSWER_FREE_FORM ',
  /** Просмотр журнала информационного взаимодействия. */
  READ_INTERACTION_LOG_REGISTER = 'READ_INTERACTION_LOG_REGISTER',
  /** Фильтрация журнала информационного взаимодействия. */
  FILTER_INTERACTION_LOG_REGISTER = 'FILTER_INTERACTION_LOG_REGISTER',
  /** Настройка вида журнала информационного взаимодействия. */
  CUSTOMIZE_INTERACTION_LOG_REGISTER = 'CUSTOMIZE_INTERACTION_LOG_REGISTER',
  /** Экспорт журнала информационного взаимодействия. */
  EXPORT_INTERACTION_LOG_REGISTER = 'EXPORT_INTERACTION_LOG_REGISTER',
  /** Просмотр СДИЗ по номеру, дате, массе. */
  READ_SDIZ_ROU_APK = 'READ_SDIZ_ROU_APK',
  /** Просмотр партии зерна по номеру, дате, массе. */
  READ_LOT_ROU_APK = 'READ_LOT_ROU_APK',
  /** Просмотр собранного урожая по номеру, дате, массе. */
  READ_PRODUCTMONITOR_ROU_APK = 'READ_PRODUCTMONITOR_ROU_APK',
  /** Просмотр реестра деклараций в разделе "Управление СДИЗ". */
  READ_DECLARATION_REGISTER_SDIZ = 'READ_DECLARATION_REGISTER_SDIZ',
  /** Фильтрация реестра деклараций в разделе "Управление СДИЗ". */
  FILTER_DECLARATION_REGISTER_SDIZ = 'FILTER_DECLARATION_REGISTER_SDIZ',
  /** Настройка вида реестра деклараций в разделе "Управление СДИЗ". */
  CUSTOMIZE_DECLARATION_REGISTER_SDIZ = 'CUSTOMIZE_DECLARATION_REGISTER_SDIZ',
  /** Просмотр карточки декларации в разделе "Управление СДИЗ". */
  READ_DECLARATION_SDIZ = 'READ_DECLARATION_SDIZ',
  /** Просмотр реестра организаций в разделе "Администрирование". */
  READ_FULL_ORGANIZATION_REGISTER = 'READ_FULL_ORGANIZATION_REGISTER',
  /** Фильтрация реестра организаций  в разделе "Администрирование" . */
  FILTER_FULL_ORGANIZATION_REGISTER = 'FILTER_FULL_ORGANIZATION_REGISTER',
  /** Настройка вида реестра организаций в разделе "Администрирование". */
  CUSTOMIZE_FULL_ORGANIZATION_REGISTER = 'CUSTOMIZE_FULL_ORGANIZATION_REGISTER',
  /** Исключение организации из реестра в разделе "Администрирование". */
  DELETE_FULL_ORGANIZATION = 'DELETE_FULL_ORGANIZATION',
  /** Добавление организации в реестр в разделе "Администрирование" . */
  CREATE_FULL_ORGANIZATION = 'CREATE_FULL_ORGANIZATION',
  /** Изменение сведений об организации в разделе "Администрирование". */
  CHANGE_FULL_ORGANIZATION = 'CHANGE_FULL_ORGANIZATION',
  /** Просмотр карточки организации в разделе "Администрирование". */
  READ_FULL_ORGANIZATION = 'READ_FULL_ORGANIZATION',
}

export enum ERole {
  /** Сотрудник органа государственной власти. */
  ROLE_GOVERMENT_USER = 'ROLE_GOVERMENT_USER',
  /** Сотрудник товаропроизводителя. */
  ROLE_SUBJECT_USER = 'ROLE_SUBJECT_USER',
  /** Администратор. */
  ROLE_ADMIN = 'ROLE_ADMIN',
  /** Сотрудник организации, внесённой в реестр элеваторов. */
  ROLE_ELEVATOR_USER = 'ROLE_ELEVATOR_USER',
  /** Сотрудник организации, осуществляющий государственный мониторинг зерна. */
  ROLE_GOVERMENT_MONITORING = 'ROLE_GOVERMENT_MONITORING',
  /** Сотрудник Федерального агентства по государственным резервам. */
  ROLE_FEDERAL_RESERVE = 'ROLE_FEDERAL_RESERVE',
  /** Сотрудник органа государственной власти, ответственный за ведение нормативно-справочной информации. */
  ROLE_DICTIONARY = 'ROLE_DICTIONARY',
  /** Сотрудник, ответственный за электронные административные регламенты. */
  ROLE_WORKFLOW = 'ROLE_WORKFLOW',
  /** Сотрудник товаропроизводителя, являющегося агентом. */
  ROLE_AGENT_USER = 'ROLE_AGENT_USER',
  /** Сотрудник Минсельхоза. */
  ROLE_MCX_USER = 'ROLE_MCX_USER',
  /** Администратор безопасности. */
  ROLE_SECURITY_ADMIN = 'ROLE_SECURITY_ADMIN',
  /** Сотрудник, ответственный за ведение реестра лабораторий. */
  ROLE_LABORATORY = 'ROLE_LABORATORY',
  /** Сотрудник, ответственный за согласование заявок. */
  ROLE_REQUEST = 'ROLE_REQUEST',
  /** Сотрудник, ответственный за ведение реестра товаропроизводителей и реестра организаций. */
  ROLE_SUBJECT_ADMIN = 'ROLE_SUBJECT_ADMIN',
  /** Пользователь, имеющий права только на просмотр всех форм приложения. */
  ROLE_AUDITOR = 'ROLE_AUDITOR',
  /** Сотрудник РоссельхозНадзора. */
  ROLE_RSHN = 'ROLE_RSHN',
  /** Сотрудник ФТС. */
  ROLE_FTC = 'ROLE_FTC',
  /** Сотрудник РОУ АПК. */
  ROLE_ROU_APK = 'ROLE_ROU_APK',
  /** Оператор системы. */
  ROLE_OPERATOR = 'ROLE_OPERATOR',
}

export enum EAuthority {
  /** Управление пользователями. */
  MANAGE_USER = 'MANAGE_USER',
  /** Управление подразделениями. */
  MANAGE_DIVISION = 'MANAGE_DIVISION',
  /** Управление ОГВ. */
  MANAGE_OGV = 'MANAGE_OGV',
  /** Управление шаблонами согласования. */
  MANAGE_TEMPLATE = 'MANAGE_TEMPLATE',
  /** Управление реестром контрактов с агентами. */
  MANAGE_CONTRACT = 'MANAGE_CONTRACT',
  /** Управление организациями, осуществляющими хранение зерна. */
  MANAGE_ELEVATOR = 'MANAGE_ELEVATOR',
  /** Управление реестром лабораторий. */
  MANAGE_LABORATORY = 'MANAGE_LABORATORY',
  /** Управление реестром товаропроизводителей. */
  MANAGE_MANUFACTURER = 'MANAGE_MANUFACTURER',
  /** Добавление товаропроизводителей. */
  ADD_MANUFACTURER = 'ADD_MANUFACTURER',
  /** Управление задачами согласования. */
  MANAGE_APPROVAL_TASK = 'MANAGE_APPROVAL_TASK',
  /** Управление задачами согласования подразделения. */
  MANAGE_APPROVAL_TASK_DIVISION = 'MANAGE_APPROVAL_TASK_DIVISION',
  /** Управление заявками на включение организации в реестр. */
  MANAGE_APPROVAL_REQUEST = 'MANAGE_APPROVAL_REQUEST',
  /** Просмотр всех заявок. */
  VIEW_REQUEST = 'VIEW_REQUEST',
  /** Управление справочниками. */
  MANAGE_NCI = 'MANAGE_NCI',
  /** Управление заявками на изменение сведений об организации. */
  MANAGE_CHANGE_REQUEST = 'MANAGE_CHANGE_REQUEST',
  /** Управление данными, подаваемыми товаропроизводителем при осуществлении госмониторинга. */
  MANAGE_SUBJECT_GOVMONITORING = 'MANAGE_SUBJECT_GOVMONITORING',
  /** Управление партиями зерна. */
  MANAGE_GRAIN_BATCH = 'MANAGE_GRAIN_BATCH',
  /** Управление СДИЗ. */
  MANAGE_SDIZ = 'MANAGE_SDIZ',
  /** Управление партиями зерна на хранении. */
  MANAGE_STORED_GRAIN_BATCH = 'MANAGE_STORED_GRAIN_BATCH',
  /** Управление партиями продуктов переработки зерна. */
  MANAGE_GRAIN_PRODUCTS_BATCH = 'MANAGE_GRAIN_PRODUCTS_BATCH',
  /** Управление СДИЗ на ППЗ. */
  MANAGE_GRAIN_PRODUCTS_SDIZ = 'MANAGE_GRAIN_PRODUCTS_SDIZ',
  /** Управление СДИЗ при хранении. */
  MANAGE_SDIZ_STORAGE = 'MANAGE_SDIZ_STORAGE',
  /** Получение номеров партий и номеров СДИЗ для оформления на бумажном носителе. */
  GETTING_NUMBERS = 'GETTING_NUMBERS',
  /** Управление данными, подаваемыми организациями Госмониторинга при осуществлении госмониторинга. */
  MANAGE_GOVERMENT_MONITORING = 'MANAGE_GOVERMENT_MONITORING',
  /** Просмотр  сведений, предоставляемых агентом. */
  VIEW_AGENT_DATA = 'VIEW_AGENT_DATA',
  /** Управление сведениями, предоставляемыми агентом. */
  MANAGE_AGENT_DATA = 'MANAGE_AGENT_DATA',
  /** Контроль взаимодействия со СМЭВ. */
  MANAGE_SMEV = 'MANAGE_SMEV',
  /** Контроль загрузки данных ФИАС. */
  MANAGE_FIAS = 'MANAGE_FIAS',
  /** Просмотр электронных журналов учета операций. */
  VIEW_LOG = 'VIEW_LOG',
  /** Просмотр структуры ОГВ. */
  VIEW_OGV = 'VIEW_OGV',
  /** Просмотр реестра организаций, осуществляющих хранение зерна. */
  VIEW_ELEVATOR = 'VIEW_ELEVATOR',
  /** Просмотр реестра лабораторий. */
  VIEW_LABORATORY = 'VIEW_LABORATORY',
  /** Просмотр реестра товаропроизводителей. */
  VIEW_MANUFACTURER = 'VIEW_MANUFACTURER',
  /** Просмотр справочников. */
  VIEW_NCI = 'VIEW_NCI',
  /** Поиск контрагентов и товаропроизводителей. */
  SEARCH_MANUFACTURER = 'SEARCH_MANUFACTURER',
  /** Поиск организаций, осуществляющих хранение зерна. */
  SEARCH_ELEVATOR = 'SEARCH_ELEVATOR',
  /** Поиск по номерам СДИЗ и партий, выданных Госрезерву. */
  SEARCH_FEDERAL_NUMBERS = 'SEARCH_FEDERAL_NUMBERS',
  /** Просмотр сведений, поданных товаропроизводителем. */
  VIEW_PRODACTMONITOR = 'VIEW_PRODACTMONITOR',
  /** Просмотр исследований. */
  VIEW_LABORATORYMONITOR = 'VIEW_LABORATORYMONITOR',
  /** Просмотр партий зерна. */
  VIEW_LOT = 'VIEW_LOT',
  /** Просмотр партий продуктов переработки. */
  VIEW_GPB = 'VIEW_GPB',
  /** Просмотр СДИЗ на зерно. */
  VIEW_SDIZ = 'VIEW_SDIZ',
  /** Просмотр СДИЗ ППЗ. */
  VIEW_GPBSDIZ = 'VIEW_GPBSDIZ',
  /** Просмотр сведений, предоставляемых агентом. */
  VIEW_AGENTSDIZ = 'VIEW_AGENTSDIZ',
  /** Просмотр партий зерна на хранении. */
  VIEW_LOT_ELEVATOR = 'VIEW_LOT_ELEVATOR',
  /** Просмотр СДИЗ на хранение. */
  VIEW_SDIZ_ELEVATOR = 'VIEW_SDIZ_ELEVATOR',
  /** Просмотр номеров, выданных Госрезерву. */
  VIEW_FEDERAL_NUMBERS = 'VIEW_FEDERAL_NUMBERS',
  /** Просмотр реестра уведомлений, карточек уведомлений. */
  VIEW_NOTIFICATION = 'VIEW_NOTIFICATION',
  /** Просмотр реестра пользователей. */
  VIEW_USER_REGISTRY = 'VIEW_USER_REGISTRY',
  /** Просмотр журнала согласований. */
  READ_APPROVAL_REQUEST_LOG_REGISTRY = 'READ_APPROVAL_REQUEST_LOG_REGISTRY',
  /** Фильтрация журнала согласований. */
  FILTER_APPROVAL_REQUEST_LOG_REGISTRY = 'FILTER_APPROVAL_REQUEST_LOG_REGISTRY',
  /** Кастомизация журнала согласований. */
  CUST_APPROVAL_REQUEST_LOG_REGISTRY = 'CUST_APPROVAL_REQUEST_LOG_REGISTRY',
  /** Управление производствами продукции, не подлежащей учету. */
  MANAGE_GRAIN_PROCESSING_BATCH_OUT = 'MANAGE_GRAIN_PROCESSING_BATCH_OUT',
  /** Управление импортом. */
  MANAGE_IMPORT = 'MANAGE_IMPORT',
  /** Просмотр реестра "Поиск СДИЗ". */
  VIEW_SEARCH_SDIZ = 'VIEW_SEARCH_SDIZ',
  /** Просмотр карточки товаропроизводителя. */
  VIEW_CARD_MANUFACTURER = 'VIEW_CARD_MANUFACTURER',
  /** Управление изъятиями. */
  MANAGE_WITHDRAWAL = 'MANAGE_WITHDRAWAL',
  /** Управление предписаниями. */
  MANAGE_PRESCRIPTION = 'MANAGE_PRESCRIPTION',
  /** Управление экспертизами. */
  MANAGE_EXPERTISE = 'MANAGE_EXPERTISE',
  /** Управление запретами. */
  MANAGE_RESTRICTIONS = 'MANAGE_RESTRICTIONS',
  /** Поиск СДИЗ по его номеру. */
  FIND_SDIZ_BY_NUMBER = 'FIND_SDIZ_BY_NUMBER',
  /** Управление подтверждением СДИЗ. */
  MANAGE_CONFIRM_SDIZ = 'MANAGE_CONFIRM_SDIZ',
  /** Управление сертификатами организаций. */
  MANAGE_ORGANIZATION_CERTIFICATE = 'MANAGE_ORGANIZATION_CERTIFICATE',
  /** Управление системными сертификатами. */
  MANAGE_SYSTEM_CERTIFICATE = 'MANAGE_SYSTEM_CERTIFICATE',
  /** Просмотр карточки организации в пункте "Моя организация". */
  VIEW_ORGANIZATION_CARD = 'VIEW_ORGANIZATION_CARD',
  /** Просмотр шаблонов согласования. */
  VIEW_TEMPLATE = 'VIEW_TEMPLATE',
  /** Просмотр реестров контрактов с агентами. */
  VIEW_CONTRACT = 'VIEW_CONTRACT',
  /** Просмотр задач согласования. */
  VIEW_APPROVAL_TASK = 'VIEW_APPROVAL_TASK',
  /** Просмотр сведений о собранном урожае. */
  VIEW_SUBJECT_GOVMONITORING = 'VIEW_SUBJECT_GOVMONITORING',
  /** Просмотр данных, подаваемых организациями Госмониторинга при осуществлении госмониторинга. */
  VIEW_GOVERMENT_MONITORING = 'VIEW_GOVERMENT_MONITORING',
  /** Аннулирование сведение и предоставление взамен новых оператором системы. */
  CANCELLATION_DOCUMENTS_BY_OPERATOR = 'CANCELLATION_DOCUMENTS_BY_OPERATOR',
  /** Управление реестром деклараций. */
  MANAGE_DECLARATION = 'MANAGE_DECLARATION',
  /** Просмотр реестра деклараций. */
  VIEW_DECLARATION = 'VIEW_DECLARATION',
  /** Управление реестром жалоб. */
  MANAGE_COMPLAINT = 'MANAGE_COMPLAINT',
  /** Просмотр реестра жалоб. */
  VIEW_COMPLAINT = 'VIEW_COMPLAINT',
  /** Управление журналом информацинного взаимодействия. */
  MANAGE_INTERACTION_LOG = 'MANAGE_INTERACTION_LOG',
  /** Управление погашением. */
  MANAGE_FTS_EXTINCTION = 'MANAGE_FTS_EXTINCTION',
  /** Управление справочником контрагентов и их адресами. */
  MANAGE_DIRECTORY = 'MANAGE_DIRECTORY',
  /** Управление реестром запросов. */
  MANAGE_FREE_FORM = 'MANAGE_FREE_FORM ',
  /** Просмотр реестра запросов . */
  VIEW_FREE_FORM = 'VIEW_FREE_FORM',
  /** Просмотр пункта меню "РОУ АПК". */
  VIEW_ROU_APK = 'VIEW_ROU_APK',
  /** Управление реестром организаций в разделе "Администрирование". */
  MANAGE_ORGANIZATION = 'MANAGE_ORGANIZATION ',
  /** Просмотр реестра организаций в разделе "Администрирование". */
  VIEW_ORGANIZATION = 'VIEW_ORGANIZATION',
  /** Управление первичной загрузкой сельскохозяйственных угодий. */
  MANAGE_ACRICULTURAL_LOADING_TASK = 'MANAGE_ACRICULTURAL_LOADING_TASK',
  /** Просмотр загруженных сельскохозяйственных угодий. */
  VIEW_ACRICULTURAL_LOADED = 'VIEW_ACRICULTURAL_LOADED',
  /** Формирование отчёта о балансе на элеваторе. */
  REP_ELEVATOR_BALANCE = 'REP_ELEVATOR_BALANCE',
  /** Формирование отчёта о государственном мониторинге зерна. */
  REP_GMZ = 'REP_GMZ',
  /** Формирование отчёта о продовольственном балансе. */
  REP_FOOD_BALANCE = 'REP_FOOD_BALANCE',
  /** Формирование отчёта о логистической информации. */
  REP_LOGISTIC = 'REP_LOGISTIC',
  /** Формирование отчёта об экспорте/импорте. */
  REP_EXPIMP = 'REP_EXPIMP',
  /** Формирование отчёта о товаропроизводителях. */
  REP_MANUFACTURER = 'REP_MANUFACTURER',
  /** Формирование отчёта об интервенционном фонде. */
  REP_INTERV = 'REP_INTERV',
  /** Формирование отчёта о закупках для государственных и муниципальных нужд. */
  REP_GOS = 'REP_GOS',
  /** Формирование отчёта о сопоставлении данных ЕФИС ЗСН. */
  REP_EFIS_ZSN = 'REP_EFIS_ZSN',
  /** Формирование отчёта об изъятии, возврате и утилизации зерна и продуктов переработки зерна. */
  REP_RSHN = 'REP_RSHN',
  /** Формирование отчёта о системе. */
  REP_SYSTEM = 'REP_SYSTEM',
  /** Формирование отчёта о обработке заявок и запросов. */
  REP_CLAIM = ' REP_CLAIM',
  /** Формирование отчёта о средней урожайности. */
  REP_HARAVG = 'REP_HARAVG',
  /** Формирование отчёта о государственном контроле (надзоре). */
  REP_GKN = 'REP_GKN',
  /** Ведение информации о средней урожайности и валовом сборе. */
  MANAGE_HAVG_GROSSC = 'MANAGE_HAVG_GROSSC',
  /** Просмотр карточки лаборатории. */
  VIEW_CARD_LABORATORY = 'VIEW_CARD_LABORATORY',
}
