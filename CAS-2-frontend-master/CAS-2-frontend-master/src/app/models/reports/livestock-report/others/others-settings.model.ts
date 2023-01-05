import {ColReportSettingModel, HeaderReportSettingModel, ReportModel, ReportSettingType} from '@korvet/ui-elements';

export class VetLivestockOthersSettingsModel extends ReportModel {
  public readonly title = 'МРС (прочие)';
  public readonly showTotalRow = true;
  public readonly fxFlex = '100%';
  public readonly showFooter: boolean = true;
  public readonly formName: string = 'livestock-other';
  public readonly headers: Array<HeaderReportSettingModel> = [
    {
      title: '',
      name: 'name',
    },
    {
      title: 'Всего',
      name: 'total',
    },
    {
      title: 'Самцы',
      name: 'male',
    },
    {
      title: 'Самки',
      name: 'female',
    },
    {
      title: 'Молодняк до года',
      name: 'underYear',
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
      name: 'underYear',
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
