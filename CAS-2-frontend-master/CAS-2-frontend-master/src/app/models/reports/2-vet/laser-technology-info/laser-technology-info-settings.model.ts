import {ColReportSettingModel, HeaderReportSettingModel, ReportModel, ReportSettingType} from '@korvet/ui-elements';

export class Vet2LaserTechnologyInfoSettingsModel extends ReportModel {
  public readonly showTotalRow = false;
  public readonly showFooter: boolean = false;
  public readonly fxFlex = '50%';
  public readonly title = 'Сведения о применении лазерной техники';

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
        value: 'Мустанг - 1'
      },
      {
        nameCol: 'name',
        value: 'Муравей - 2'
      },
      {
        nameCol: 'name',
        value: 'Милта - 1'
      },
    ]
  };
}
