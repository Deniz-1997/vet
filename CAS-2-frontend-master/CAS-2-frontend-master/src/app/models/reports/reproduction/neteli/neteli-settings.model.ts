import {ColReportSettingModel, HeaderReportSettingModel, ReportModel, ReportSettingType} from '@korvet/ui-elements';

export class VetReproductionNeteliModel extends ReportModel {
  public readonly showTotalRow = true;
  public readonly showFooter: boolean = false;
  public readonly fxFlex = '50%';
  public readonly formName: string = 'reproduction-neteli';
  public readonly headers: Array<HeaderReportSettingModel> = [
    {
      title: '',
      name: 'name',
    },
    {
      title: 'Нетели',
      name: 'total',
    }
  ];
  public readonly columns: Array<ColReportSettingModel> = [
    {
      name: 'name',
      type: ReportSettingType.TITLE,
    },
    {
      name: 'total',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    }
  ];

  public readonly useStubCols: boolean = true;

  public readonly stubCols: { countCols: number, stub: Array<{ nameCol: string, value: string, disabled?: boolean }> } = {
    countCols: 5,
    stub: [
      {
        nameCol: 'name',
        value: `Всего по хозяйству нетелей на 01.01. ${new Date().getFullYear()}`
      },
      {
        nameCol: 'name',
        value: 'Растелилось'
      },
      {
        nameCol: 'name',
        value: 'Живых телят'
      },
      {
        nameCol: 'name',
        value: 'Аборты'
      },
      {
        nameCol: 'name',
        value: 'Мертворожденные'
      },
    ]
  };
}
