import {ColReportSettingModel, HeaderReportSettingModel, ReportModel, ReportSettingType} from '@korvet/ui-elements';

export class VetReproductionCowsPeriodEndModel extends ReportModel {
  public readonly showFooter: boolean = false;
  public readonly showTotalRow = false;
  public readonly fxFlex = '50%';
  public readonly formName: string = 'reproduction-cows-period-end';
  public readonly headers: Array<HeaderReportSettingModel> = [
    {
      title: '',
      name: 'name',
    },
    {
      title: 'Коров на конец отчетного периода',
      name: 'count',
    },
  ];
  public readonly columns: Array<ColReportSettingModel> = [
    {
      name: 'name',
      type: ReportSettingType.TITLE,
    },
    {
      name: 'count',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
  ];

  public readonly useStubCols: boolean = true;

  public readonly stubCols: { countCols: number, stub: Array<{ nameCol: string, value: string, disabled?: boolean }> } = {
    countCols: 3,
    stub: [
      {
        nameCol: 'name',
        value: 'Коров'
      },
      {
        nameCol: 'name',
        value:  ` +/- к ${new Date().getFullYear()} году`
      },
      {
        nameCol: 'name',
        value: ` +/- к ${Number(new Date().getFullYear()) + 1} году`
      },
    ]
  };
}
