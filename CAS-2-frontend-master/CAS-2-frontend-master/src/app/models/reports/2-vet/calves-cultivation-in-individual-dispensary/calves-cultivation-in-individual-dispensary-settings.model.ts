import {ColReportSettingModel, HeaderReportSettingModel, ReportModel, ReportSettingType} from '@korvet/ui-elements';

export class Vet2CalvesCultivationInIndividualDispensarySettingsModel extends ReportModel {
  public readonly showTotalRow = false;
  public readonly showFooter: boolean = false;
  public readonly fxFlex = '100%';
  public readonly title = 'Сведения о выращивании телят в индивидуальных профилакториях на закрытых площадках';

  public readonly headers: Array<HeaderReportSettingModel> = [
    {
      title: 'Количество профилакториев (шт.)',
      name: 'numberDispensary'
    },
    {
      title: 'Выращено телят (гол.)',
      name: 'calvesRaised'
    },
    {
      title: 'Рождено телят',
      name: 'calvesBorn'
    },
    {
      title: '% сохранности',
      name: 'saftyPercent'
    },
  ];

  public readonly columns: Array<ColReportSettingModel> = [
    {
      name: 'numberDispensary',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'calvesRaised',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'calvesBorn',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'saftyPercent',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
  ];

  public readonly useStubCols: boolean = true;

  public readonly stubCols: { countCols: number, stub: Array<{ nameCol: string, value: string, disabled?: boolean }> } = {
    countCols: 1,
    stub: [
      {
        nameCol: 'numberDispensary',
        value: ''
      },
    ]
  };
}
