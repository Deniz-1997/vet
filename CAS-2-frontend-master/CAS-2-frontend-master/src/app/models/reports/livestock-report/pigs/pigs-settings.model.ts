import {ColReportSettingModel, HeaderReportSettingModel, ReportModel, ReportSettingType} from '@korvet/ui-elements';

export class VetLivestockPigsSettingsModel extends ReportModel {
  public readonly title = 'Свиньи';
  public readonly showTotalRow = true;
  public readonly fxFlexMd = '100%';
  public readonly formName: string = 'livestock-pig';
  public readonly showFooter: boolean = true;
  public readonly headers: Array<HeaderReportSettingModel> = [
    {
      title: '',
      name: 'name',
    },
    {
      title: 'Свиньи (всего)',
      name: 'total',
    },
    {
      title: 'Хряки',
      name: 'male',
    },
    {
      title: 'Основные свиноматки',
      name: 'female',
    },
    {
      title: 'Разовые и проверяемые свиноматки',
      name: 'inspectedFemale',
    },
    {
      title: 'Поросята до 4 месяцев',
      name: 'underYear',
    },
    {
      title: 'Поросята откормочники',
      name: 'pigFeeders',
    },
    {
      title: 'Ожидаемый приплод',
      name: 'expectedOffspring',
    },
  ];
  public readonly columns: Array<ColReportSettingModel> = [
    {
      name: 'name',
      type: ReportSettingType.TITLE,
      size: {fxFlex: '20'}
    },
    {
      name: 'total',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
      size: {fxFlex: ''}
    },
    {
      name: 'male',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
      size: {fxFlex: ''}
    },
    {
      name: 'female',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
      size: {fxFlex: ''}
    },
    {
      name: 'inspectedFemale',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
      size: {fxFlex: ''}
    },
    {
      name: 'underYear',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
      size: {fxFlex: ''}
    },
    {
      name: 'pigFeeders',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
      size: {fxFlex: ''}
    },
    {
      name: 'expectedOffspring',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
      size: {fxFlex: ''}
    },
  ];

  public readonly useStubCols: boolean = true;

  public readonly stubCols: { countCols: number, stub: Array<{ nameCol: string, value: string, disabled?: boolean }> } = {
    countCols: 2,
    stub: [
      {
        nameCol: 'name',
        value: 'ХС'
      },
      {
        nameCol: 'name',
        value: 'Частные'
      },
    ]
  };
}
