import {Urls} from './urls';
import {UserModels} from '../models/user/user.models';
import {RoleModel} from '../models/role.models';
import {GroupModel} from '../models/group.models';
import {ActionModel} from '../models/action/action.models';
import {ActionGroupModel} from '../models/action/action.group.models';
import {EntityModel} from '../models/entity.models';
import {SettingModel} from '../models/setting.models';
import {ReferenceIconModel} from '../models/reference/icon.model';
import {ReferenceNotificationsChannelModel} from '../models/reference/reference.notifications.channel.models';
import {ReferenceNotificationsTypeModel} from '../models/reference/reference.notifications.type.models';
import {MainNotificationModel} from '../models/notifications/notifications.models';
import {SupportMessageModel} from '../models/support/support-message.model';
import {ReferenceCountriesModel} from '../models/reference/reference.countries.models';
import {ReferenceMeasurementUnitsModel} from '../models/reference/reference.measurement.units.models';
import {ReferenceDiseaseModel} from '../models/reference/reference.disease.models';
import {EnumModel} from '../models/enum.models';
import {VaccinationModel} from '../models/vaccination/vaccination.model';
import {VaccineModel} from '../models/dictionary/vaccine.model';
import {BreedModel} from '../models/dictionary/breed.model';
import {SupervisoryAuthorityModel} from '../models/contractors/supervisory-authority.model';
import {KindModel} from '../models/dictionary/kind.model';
import {UploadedVaccinationExcelFileEntryModel} from '../models/Import/uploaded-vaccination-excel-file-entry.model';
import {ReportsListModel} from '../models/reports/report-model/reports-list.model';
import {DataReportsModel} from '../models/reports/report-model/date-reports.model';
import {ReferenceStationModel} from '../models/reference/reference.station.models';
import {UploadedFileModel} from '../models/file/uploaded-file.models';
import {ReferencePetTypeModel} from '../models/reference/reference.pet.type.models';
import {AnimalModel} from '../models/animal/animal.model';
import {ReferenceBusinessEntityModel} from '../models/reference/reference.businessEntity.models';
import {ReferenceSupervisedObjectModel} from '../models/reference/reference.supervisedObects.models';
import {VaccineSeriesModel} from '../models/dictionary/vaccine-series.model';
import {DiseaseModel} from '../models/dictionary/disease.model';
import {ManufacturerModel} from '../models/dictionary/manufacturer.model';
import {UploadedVaccinationExcelRowEntryModel} from '../models/Import/uploaded-vaccination-excel-row-entry.model';
import {AnimalLivingPlaceModel} from '../models/animal/animal-living-place.model';
import {AnimalStampModel} from '../models/animal/animal-stamp.model';
import {ColourModel} from '../models/dictionary/colour.model';
import {ReportExplanatoryNoteModel} from '../models/reference/reference.explanatory.note.models';
import {CircleModel} from '../models/location/circle.model';
import {LocationModel} from '../models/location/location.model';
import {PathModel} from '../models/location/path.model';
import {LivestockModel} from '../models/animal/livestock.model';
import {ReferenceDisinfectantsModel} from '../models/reference/reference.disinfectants.models';
import {ApiQueueModel} from '../models/vaccination/api-queue.model';
import {ApiQueueRowModel} from '../models/vaccination/api-queue-row.model';
import {ReferenceSubdivisionModel} from '../models/reference/reference.subdivision.models';
import {EntityHistoryModel} from '../models/entity-history.models';
import {UsersSyncModels} from '../models/user/users.sync.models';

export enum CrudType {
  ReferenceMeasurementUnits = 'referenceMeasurementUnits',
  ReferenceCountries = 'referenceCountries',
  ReferenceBreed = 'referenceBreed',
  ReferencePetType = 'referencePetType',
  ReferenceBusinessEntity = 'referenceBusinessEntity',
  ReferenceSupervisedObject = 'referenceSupervisedObject',
  User = 'user',
  Role = 'role',
  Group = 'group',
  File = 'file',
  UploadedFile = 'uploadedFile',
  ReferenceIcon = 'referenceIcon',
  Enum = 'enum',
  EntityHistory = 'entityHistory',
  ReferenceNotificationsType = 'referenceNotificationsType',
  ReferenceNotificationsChannel = 'referenceNotificationsChannel',
  ReportExplanatoryNote = 'reportExplanatoryNote',
  ActionGroup = 'actionGroup',
  Action = 'action',
  Entity = 'entity',
  Settings = 'settings',
  Notifications = 'notifications',
  SupportMessage = 'supportMessage',
  ReferenceDisease = 'referenceDisease',
  ReferenceDisinfectants = 'referenceDisinfectants',
  DictionaryVaccine = 'dictionaryVaccine',
  DictionaryAnimal = 'dictionaryAnimal',
  DictionaryAnimalLivingPlaces = 'dictionaryAnimalLivingPlaces',
  DictionaryLivestock = 'dictionaryLivestock',
  DictionaryTags = 'dictionaryTags',
  DictionaryColour = 'dictionaryColour',
  DictionaryAnimalStamps = 'dictionaryAnimalStamps',
  DictionaryBreed = 'dictionaryBreed',
  ReferenceCircle = 'referenceCircle',
  ReferenceLocation = 'referenceLocation',
  ReferencePath = 'referencePath',
  DictionarySupervisoryAuthority = 'dictionarySupervisoryAuthority',
  DictionaryKind = 'dictionaryKind',
  ApiQueue = 'apiQueue',
  ApiQueueRow = 'apiQueueRow',
  ReportList = 'reportList',
  ReportData = 'reportData',
  Vaccination = 'vaccination',
  ReferenceStation = 'referenceStation',
  ReferenceSubdivision = 'referenceSubdivision',
  UploadEcxelVaccination = 'uploadEcxelVaccination',
  DictionaryVaccineSeries = 'dictionaryVaccineSeries',
  DictionaryManufacturer = 'dictionaryManufacturer',
  DictionaryDisease = 'dictionaryDisease',
  UploadedVaccinationExcelRowEntry = 'uploadedVaccinationExcelRowEntry',
  UsersSycn = 'usersSync'
}

export const CrudTypes = {
  referenceDisinfectants: {
    url: Urls.api + 'reference/reference-disinfectant/:id/',
    params: ['id'],
    model: ReferenceDisinfectantsModel,
  },
  referenceMeasurementUnits: {
    url: Urls.api + 'reference/reference-measurement-units/:id/',
    params: ['id'],
    model: ReferenceMeasurementUnitsModel,
  },
  referenceCountries: {
    url: Urls.api + 'reference/reference-countries/:id/',
    params: ['id'],
    model: ReferenceCountriesModel,
  },

  referenceBusinessEntity: {
    url: Urls.api + 'reference/businessEntity/:id/',
    params: ['id'],
    model: ReferenceBusinessEntityModel,
  },
  referenceSupervisedObject: {
    url: Urls.api + 'reference/supervised-objects/:id/',
    params: ['id'],
    model: ReferenceSupervisedObjectModel,
  },
  user: {
    url: Urls.api + 'user/:id/',
    params: ['id'],
    model: UserModels,
  },
  role: {
    url: Urls.api + 'role/:id/',
    params: ['id'],
    model: RoleModel,
  },
  group: {
    url: Urls.api + 'group/:id/',
    params: ['id'],
    model: GroupModel,
  },
  action: {
    url: Urls.api + 'action/:id/',
    params: ['id'],
    model: ActionModel
  },
  actionGroup: {
    url: Urls.api + 'action-group/:id/',
    params: ['id'],
    model: ActionGroupModel
  },
  entity: {
    url: Urls.api + 'entity/:id/',
    params: ['id'],
    model: EntityModel
  },
  entityHistory: {
    url: Urls.api + 'history/history/',
    params: ['id'],
    model: EntityHistoryModel
  },
  settings: {
    url: Urls.api + 'settings/:id/',
    params: ['id'],
    model: SettingModel
  },
  referenceIcon: {
    url: Urls.api + 'icon/:id/',
    params: ['id'],
    model: ReferenceIconModel
  },
  referenceNotificationsChannel: {
    url: Urls.api + 'reference/notifications-channel/:id/',
    params: ['id'],
    model: ReferenceNotificationsChannelModel,
  },
  referenceNotificationsType: {
    url: Urls.api + 'reference/notifications-type/:id/',
    params: ['id'],
    model: ReferenceNotificationsTypeModel,
  },
  notifications: {
    url: Urls.api + 'notifications/:id/',
    params: ['id'],
    model: MainNotificationModel,
  },
  supportMessage: {
    url: Urls.api + 'support-message',
    model: SupportMessageModel,
  },
  referenceDisease: {
    url: Urls.api + 'reference/reference-disease/:id/',
    params: ['id'],
    model: ReferenceDiseaseModel,
  },
  enum: {
    url: Urls.api + 'enum/:id/',
    params: ['id'],
    model: EnumModel
  },
  vaccination: {
    url: Urls.api + 'vaccination/:id/',
    params: ['id'],
    model: VaccinationModel
  },
  dictionaryVaccine: {
    url: Urls.api + 'dictionary/vaccine/:id/',
    params: ['id'],
    model: VaccineModel
  },
  dictionaryBreed: {
    url: Urls.api + 'dictionary/breed/:id/',
    params: ['id'],
    model: BreedModel
  },
  dictionaryAnimal: {
    url: Urls.api + 'dictionary/animal/:id/',
    params: ['id'],
    model: AnimalModel
  },
  dictionarySupervisoryAuthority: {
    url: Urls.api + 'dictionary/supervisoryAuthority/:id/',
    params: ['id'],
    model: SupervisoryAuthorityModel
  },
  dictionaryKind: {
    url: Urls.api + 'dictionary/kind/:id/',
    params: ['id'],
    model: KindModel
  },
  apiQueue: {
    url: Urls.api + 'import/vaccination/:id/',
    params: ['id'],
    model: ApiQueueModel
  },
  apiQueueRow: {
    url: Urls.api + 'import/vaccination/rows/:id/',
    params: ['id'],
    model: ApiQueueRowModel
  },
  reportList: {
    url: Urls.api + 'reports/:id/',
    params: ['id'],
    model: ReportsListModel
  },
  reportData: {
    url: Urls.api + 'reports-data/:id/',
    params: ['id'],
    model: DataReportsModel
  },
  referenceStation: {
    url: Urls.api + 'reference/station/:id/',
    params: ['id'],
    model: ReferenceStationModel
  },
  referenceSubdivision: {
    url: Urls.api + 'reference/subdivision/:id/',
    params: ['id'],
    model: ReferenceSubdivisionModel
  },
  uploadedFile: {
    url: Urls.api + 'uploaded-file/:id/',
    params: ['id'],
    model: UploadedFileModel
  },
  referencePetType: {
    url: Urls.api + 'reference/pet-type/:id/',
    params: ['id'],
    model: ReferencePetTypeModel
  },
  uploadEcxelVaccination: {
    url: Urls.api + 'upload_vaccination/excel',
    params: ['id'],
    model: UploadedFileModel
  },
  dictionaryVaccineSeries: {
    url: Urls.api + 'dictionary/vaccine-series/:id/',
    params: ['id'],
    model: VaccineSeriesModel
  },
  dictionaryManufacturer: {
    url: Urls.api + 'dictionary/manufacturer/:id/',
    params: ['id'],
    model: ManufacturerModel
  },
  dictionaryDisease: {
    url: Urls.api + 'dictionary/disease/:id/',
    params: ['id'],
    model: DiseaseModel
  },
  uploadedVaccinationExcelRowEntry: {
    url: Urls.api + 'uploaded-vaccination-excel-row-entry/:id/',
    params: ['id'],
    model: UploadedVaccinationExcelRowEntryModel
  },
  dictionaryAnimalLivingPlaces: {
    url: Urls.api + 'dictionary/animal-living-places/:id/',
    params: ['id'],
    model: AnimalLivingPlaceModel
  },
  dictionaryLivestock: {
    url: Urls.api + 'dictionary/livestock/:id/',
    params: ['id'],
    model: LivestockModel
  },
  dictionaryAnimalStamps: {
    url: Urls.api + 'dictionary/animal-stamps/:id/',
    params: ['id'],
    model: AnimalStampModel
  },
  dictionaryTags: {
    url: Urls.api + 'dictionary/animal-tags/:id/',
    params: ['id'],
    model: AnimalModel
  },
  dictionaryColour: {
    url: Urls.api + 'dictionary/colour/:id/',
    params: ['id'],
    model: ColourModel
  },
  reportExplanatoryNote: {
    url: Urls.api + 'reports/explanatory_note/:id/',
    params: ['id'],
    model: ReportExplanatoryNoteModel
  },
  referenceCircle: {
    url: Urls.api + 'reference/circle/:id/',
    params: ['id'],
    model: CircleModel
  },
  referenceLocation: {
    url: Urls.api + 'reference/location/:id/',
    params: ['id'],
    model: LocationModel
  },
  referencePath: {
    url: Urls.api + 'reference/path/:id/',
    params: ['id'],
    model: PathModel
  },
  usersSync: {
    url: Urls.api + 'userSync/',
    model: UsersSyncModels
  },
};

