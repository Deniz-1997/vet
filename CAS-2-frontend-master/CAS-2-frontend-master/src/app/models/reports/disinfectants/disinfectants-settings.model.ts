import {ColReportSettingModel, HeaderReportSettingModel, ReportModel, ReportSettingType} from '@korvet/ui-elements';
import {CrudType} from '../../../common/crud-types';

export class VetDisinfectantsSettingsModel extends ReportModel {
  public readonly title = 'Дезинфицирующие средства';
  public readonly isAddRow: boolean = true;
  public readonly showTotalRow = false;
  public readonly showFooter: boolean = false;
  public readonly fxFlex = '100%';
  public readonly headers: Array<HeaderReportSettingModel> = [
    {
      title: 'Препарат',
      name: 'medication'
    },
    {
      title: 'Вид',
      name: 'type'
    },
    {
      title: 'Ед. измерения',
      name: 'measurement'
    },
    {
      title: 'Срок годности',
      name: 'expirationDate'
    },
    {
      title: 'Хозяйства по разведению КРС',
      name: 'cattleFarm'
    },
    {
      title: 'Свинводческие хозяйства',
      name: 'pigFarm'
    },
    {
      title: 'Птицеводческие хозяйства',
      name: 'chickenFarm'
    },
    {
      title: 'Иные',
      name: 'otherFarm'
    },
    {
      title: 'СББЖ',
      name: 'sbbj'
    },
  ];
  public readonly columns: Array<ColReportSettingModel> = [
    {
      name: 'medication',
      type: ReportSettingType.AUTOCOMPLETE,
      crudType: CrudType.ReferenceDisinfectants,
      autofill: {autocompleteFields: ['kind', {key: 'measurementUnits', value: 'name'}], reportFields: ['type', 'measurement']},
    },
    {
      name: 'type',
      type: ReportSettingType.INPUT_TEXT,
      disabled: true,
    },
    {
      name: 'measurement',
      type: ReportSettingType.INPUT_TEXT,
      disabled: true,
    },
    {
      name: 'expirationDate',
      type: ReportSettingType.INPUT_DATE,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'cattleFarm',
      type: ReportSettingType.INPUT_NUMBER,
      readonly: true
    },
    {
      name: 'pigFarm',
      type: ReportSettingType.INPUT_NUMBER,
      readonly: true
    },
    {
      name: 'chickenFarm',
      type: ReportSettingType.INPUT_NUMBER,
      readonly: true
    },
    {
      name: 'otherFarm',
      type: ReportSettingType.INPUT_NUMBER,
      readonly: true
    },
    {
      name: 'sbbj',
      type: ReportSettingType.INPUT_NUMBER,
      readonly: true
    },
  ];
}
