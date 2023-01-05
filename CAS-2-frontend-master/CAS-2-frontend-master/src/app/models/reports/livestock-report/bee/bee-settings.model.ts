import {ColReportSettingModel, HeaderReportSettingModel, ReportModel, ReportSettingType} from '@korvet/ui-elements';

export class VetLivestockBeeModel extends ReportModel {
  public readonly title = 'Пчелы';
  public readonly showFooter: boolean = true;
  public readonly showTotalRow = true;
  public readonly fxFlex = '50%';
  public readonly useFxFlex: boolean = true;
  public readonly formName: string = 'livestock-bee';
  public readonly headers: Array<HeaderReportSettingModel> = [
    {
      title: '',
      name: 'name',
    },
    {
      title: 'Пчелы семьи',
      name: 'beehive',
    },
    {
      title: 'Пасек',
      name: 'paseka',
    },
  ];
  public readonly columns: Array<ColReportSettingModel> = [
    {
      name: 'name',
      type: ReportSettingType.TITLE,
      size: {
        cols: 2,
        fxFlex: '20'
      }
    },
    {
      name: 'beehive',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
      size: {fxFlex: ''}
    },
    {
      name: 'paseka',
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
