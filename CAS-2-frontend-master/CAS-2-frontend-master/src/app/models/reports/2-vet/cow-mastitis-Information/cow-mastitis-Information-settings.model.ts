import {ColReportSettingModel, HeaderReportSettingModel, ReportModel, ReportSettingType} from '@korvet/ui-elements';

export class Vet2MastitisInformationSettingsModel extends ReportModel {
  public readonly showTotalRow = false;
  public readonly showFooter: boolean = false;
  public readonly fxFlex = '100%';
  public readonly title = 'Сведения о маститах коров (гол.)';

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
    countCols: 5,
    stub: [
      {
        nameCol: 'name',
        value: 'Исследовано'
      },
      {
        nameCol: 'name',
        value: 'Выявлено больных'
      },
      {
        nameCol: 'name',
        value: 'Подвергнуто лечению'
      },
      {
        nameCol: 'name',
        value: 'Вылечено'
      },
      {
        nameCol: 'name',
        value: '% лечебной эффективности'
      },
    ]
  };
}
