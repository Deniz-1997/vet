import {ColReportSettingModel, HeaderReportSettingModel, ReportModel, ReportSettingType} from '@korvet/ui-elements';

export class Vet2AbortionsNumberSettingsModel extends ReportModel {
  public readonly showTotalRow = false;
  public readonly showFooter: boolean = false;
  public readonly fxFlex = '100%';
  public readonly title = 'Сведения о количестве абортов и мертворожденных';

  public readonly headers: Array<HeaderReportSettingModel> = [
    {
      title: '',
      name: 'name'
    },
    {
      title: 'Количество',
      name: 'count'
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
        value: 'Растелилось коров и нетелей'
      },
      {
        nameCol: 'name',
        value: 'Количество абортов'
      },
      {
        nameCol: 'name',
        value: 'Количество мертворожденных'
      },
    ]
  };
}
