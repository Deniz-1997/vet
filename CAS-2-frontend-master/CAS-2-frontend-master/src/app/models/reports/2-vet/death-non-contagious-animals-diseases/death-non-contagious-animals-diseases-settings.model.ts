import {ColReportSettingModel, HeaderReportSettingModel, ReportModel, ReportSettingType} from '@korvet/ui-elements';

export class Vet2DeathNonContagiousAnimalsDiseasesSettingsModel extends ReportModel {

  public readonly showTotalRow = false;
  public readonly showFooter: boolean = false;
  public readonly fxFlex = '33.333%';
  public readonly title = 'Сведения о падеже молодняка от незаразных болезней (гол.)';

  public readonly headers: Array<HeaderReportSettingModel> = [
    {
      title: '',
      name: 'name'
    },
    {
      title: 'Телята',
      name: 'calves'
    },
    {
      title: 'Поросята',
      name: 'pigs'
    },
    {
      title: 'Ягнята, козлята',
      name: 'lambs'
    },
  ];

  public readonly columns: Array<ColReportSettingModel> = [
    {
      name: 'name',
      type: ReportSettingType.TITLE,
    },
    {
      name: 'calves',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'pigs',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'lambs',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    }
  ];
  public readonly useStubCols: boolean = true;

  public readonly stubCols: { countCols: number, stub: Array<{ nameCol: string, value: string, disabled?: boolean }> } = {
    countCols: 3,
    stub: [
      {
        nameCol: 'name',
        value: '1 - 10 дней'
      },
      {
        nameCol: 'name',
        value: '10 - 30 дней'
      },
      {
        nameCol: 'name',
        value: 'ст. 30 дней'
      },
    ]
  };
}
