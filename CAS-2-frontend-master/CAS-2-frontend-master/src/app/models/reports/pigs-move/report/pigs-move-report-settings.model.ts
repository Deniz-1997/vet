import {ColReportSettingModel, HeaderReportSettingModel, ReportModel, ReportSettingType} from '@korvet/ui-elements';

export class VetPigsMoveReportSettingsModel extends ReportModel {
  public readonly showFooter: boolean = false;
  public readonly showTotalRow = true;
  public readonly type = 'table';
  public readonly formName: string = 'leukemia-report';
  public readonly title = 'Движение и отход  свиней';
  public readonly headers: Array<HeaderReportSettingModel> = [
    {
      title: 'Наименование хозяйств',
      name: 'farmName',
      rowspan: 2
    },
    {
      title: 'Наличие свиней на начало периода',
      name: 'countStartPeriod',
      rowspan: 2
    },
    {
      title: 'Наличие свиней на отчетную дату',
      name: 'countEndPeriod',
      rowspan: 2
    },
    {
      title: 'В обороте',
      name: 'countTotal',
      rowspan: 2,
    },
    {
      title: 'Приход свиней',
      name: 'countNew',
      rowspan: 2,
    },
    {
      title: 'Получено поросят',
      name: 'countPigglet',
      rowspan: 2,
    },
    {
      title: 'Падеж свиней',
      colspan: 4,
    },
    {
      title: 'Вынужденный забой',
      colspan: 2
    },
    {
      title: 'Продажа живьем',
      name: 'sellingAlive',
      rowspan: 2,
    },
    {
      title: 'Убой в хозяйстве',
      name: 'slaughterToSelf',
      rowspan: 2,
    },
    {
      title: 'всего',
      level: 1,
      name: 'deathTotal',
    },
    {
      title: '% к обороту',
      level: 1,
      name: 'deathPercentTotal',
    },
    {
      title: 'в т.ч. молодня-ка',
      level: 1,
      name: 'deathPiggletTotal',
    },
    {
      title: '% к народившимся',
      level: 1,
      name: 'deathPiggletPercentTotal',
    },
    {
      title: 'всего',
      level: 1,
      name: 'slaughterTotal',
    },
    {
      title: 'молодняка в т.ч',
      level: 1,
      name: 'slaughterPigglet',
    },
  ];

  public readonly columns: Array<ColReportSettingModel> = [
    {
      name: 'farmName',
      type: ReportSettingType.INPUT_TEXT,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'countStartPeriod',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'countEndPeriod',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'countTotal',
      type: ReportSettingType.INPUT_NUMBER,
      readonly: true,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'countNew',
      type: ReportSettingType.INPUT_NUMBER,
    },
    {
      name: 'countPigglet',
      type: ReportSettingType.INPUT_NUMBER,
    },
    {
      name: 'deathTotal',
      type: ReportSettingType.INPUT_NUMBER,
    },
    {
      name: 'deathPercentTotal',
      type: ReportSettingType.INPUT_NUMBER,
      readonly: true,
    },
    {
      name: 'deathPiggletTotal',
      type: ReportSettingType.INPUT_NUMBER,
    },
    {
      name: 'deathPiggletPercentTotal',
      type: ReportSettingType.INPUT_NUMBER,
    },
    {
      name: 'slaughterTotal',
      type: ReportSettingType.INPUT_NUMBER,
    },
    {
      name: 'slaughterPigglet',
      type: ReportSettingType.INPUT_NUMBER,
    },
    {
      name: 'sellingAlive',
      type: ReportSettingType.INPUT_NUMBER,
    },
    {
      name: 'slaughterToSelf',
      type: ReportSettingType.INPUT_NUMBER,
    }
  ];
  public readonly useStubCols: boolean = true;

  public readonly stubCols: { countCols: number, stub: Array<{ nameCol: string, value: string, disabled?: boolean }> } = {
    countCols: 1,
    stub: [
      {
        nameCol: 'name',
        value: ''
      },
    ]
  };
}
