import {ColReportSettingModel, HeaderReportSettingModel, ReportModel, ReportSettingType} from '@korvet/ui-elements';

export class VetReproductionCowsTotalModel extends ReportModel {
  public readonly title = 'Всего коров';
  public readonly showTotalRow = false;
  public readonly showFooter: boolean = false;
  public readonly fxFlex = '100%';
  public readonly formName: string = 'reproduction-cows-total';
  public readonly headers: Array<HeaderReportSettingModel> = [
    {
      title: '',
      name: 'name',
    },
    {
      title: 'Всего коров',
      name: 'totalCows',
    },
    {
      title: 'Выбыло коров',
      name: 'totalOut',
    },
    {
      title: 'Новотельных коров',
      name: 'totalNewCows',
    },
    {
      title: 'Осеменено новотельных коров',
      name: 'inseminatedCows',
    },
    {
      title: 'Яловые коровы на конец отчетного периода',
      name: 'totalYaleCows',
    },
    {
      title: 'Нетели на отчетную дату',
      name: 'totalNeteli',
    },
  ];
  public readonly columns: Array<ColReportSettingModel> = [
    {
      name: 'name',
      type: ReportSettingType.TITLE,
    },
    {
      name: 'totalCows',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'totalOut',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'totalNewCows',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'inseminatedCows',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'totalYaleCows',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'totalNeteli',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
  ];

  public readonly useStubCols: boolean = true;

  public readonly stubCols: { countCols: number, stub: Array<{ nameCol: string, value: string, disabled?: boolean }> } = {
    countCols: 1,
    stub: [
      {
        nameCol: 'name',
        value: 'Всего'
      },
    ]
  };
}
