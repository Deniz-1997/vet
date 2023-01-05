import {ColReportSettingModel, HeaderReportSettingModel, ReportModel, ReportSettingType} from '@korvet/ui-elements';

export class VetLivestockRabbitsModel extends ReportModel {
  public readonly title = 'Кролики';
  public readonly showTotalRow = true;
  public readonly fxFlex = '50%';
  public readonly showFooter: boolean = true;
  public readonly formName: string = 'livestock-rabbit';
  public readonly useFxFlex: boolean = true;
  public readonly headers: Array<HeaderReportSettingModel> = [
    {
      title: '',
      name: 'name',
    },
    {
      title: 'Кролики',
      name: 'rabbits',
    },
    {
      title: 'Маточное стадо',
      name: 'female',
    },
    {
      title: 'Молодняк',
      name: 'underYear',
    },
  ];
  public readonly columns: Array<ColReportSettingModel> = [
    {
      name: 'name',
      type: ReportSettingType.TITLE,
      size: {fxFlex: '20'}
    },
    {
      name: 'rabbits',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
      size: {fxFlex: ''}
    },
    {
      name: 'female',
      type: ReportSettingType.INPUT_TEXT,
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
