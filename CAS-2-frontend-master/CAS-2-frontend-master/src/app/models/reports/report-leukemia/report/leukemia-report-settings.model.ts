import {ColReportSettingModel, HeaderReportSettingModel, ReportModel, ReportSettingType} from '@korvet/ui-elements';

export class VetLeukemiaReportSettingsModel extends ReportModel {
  public readonly showFooter: boolean = false;
  public readonly showTotalRow = false;
  public readonly type = 'table';
  public readonly formName: string = 'leukemia-report';
  public readonly title = 'Сведения о количестве крупного рогатого скота на передержке в хозяйствах, неблагополучных по лейкозу КРС, где имеются РИД+ и ГЕМ+ животные';
  public readonly headers: Array<HeaderReportSettingModel> = [
    {
      title: 'Название неблагополучного пункта (хозяйство, ферма)',
      name: 'farmName',
      rowspan: 2
    },
    {
      title: 'Муниципальный район, городской округ (г.о.)',
      name: 'municipalDistrict',
      rowspan: 2
    },
    {
      title: '№ постановления, распоряжения и дата введения или отмены ограничений (карантина)',
      name: 'resolutionNumber',
      rowspan: 2
    },
    {
      title: 'Поголовье КРС на отчетную дату',
      colspan: 2,
    },
    {
      title: 'Всего иссл, (голов)',
      name: 'totalResearch',
      rowspan: 2,
    },
    {
      title: 'Выявлено РИД+ с начала года, (голов)',
      name: 'revealedCattle',
      rowspan: 2,
    },
    {
      title: 'Сдано на убой, (голов)',
      name: 'slaughtered',
      rowspan: 2,
    },
    {
      title: 'Осталось РИД+, (голов)',
      name: 'leftCattle',
      rowspan: 2,
    },
    {
      title: 'Из них коров, (голов)',
      name: 'leftCows',
      rowspan: 2,
    },
    {
      title: 'Инфицированность ЛКРС от общего поголовья КРС',
      name: 'infectionPercent',
      rowspan: 2,
    },
    {
      title: 'Всего иссл. (голов)',
      rowspan: 2,
      name: 'totalInfectionResearch',
    },
    {
      title: 'Выявлено ГЕМ+ с начала года',
      rowspan: 2,
      name: 'revealed',
    },
    {
      title: 'Было ГЕМ+ на прошлый понедельник',
      rowspan: 2,
      name: 'onLastMonday',
    },
    {
      title: 'За неделю',
      colspan: 2,
    },
    {
      title: 'Осталось ГЕМ+ на пере-держке ',
      name: 'leftOnHold',
      rowspan: 2,
    },
    {
      title: 'Срок сдачи на убой больных (ЛКРС) коров',
      rowspan: 2,
      name: 'deadline',
    },
    {
      title: 'Всего',
      level: 1,
      name: 'cattleTotal'
    },
    {
      title: 'в т.ч. коров',
      level: 1,
      name: 'cattleCowsOnly'
    },
    {
      title: 'Выяв-лено ГЕМ+ (голов)',
      level: 1,
      name: 'perWheekRevealed'
    },
    {
      title: 'Сдано на убой (голов)',
      level: 1,
      name: 'perWheekSlaughtered'
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
      name: 'municipalDistrict',
      type: ReportSettingType.INPUT_TEXT,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'resolutionNumber',
      type: ReportSettingType.INPUT_TEXT,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'cattleTotal',
      type: ReportSettingType.INPUT_NUMBER,
      readonly: true,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'cattleCowsOnly',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'totalResearch',
      type: ReportSettingType.INPUT_NUMBER,
      readonly: true,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'revealedCattle',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'slaughtered',
      type: ReportSettingType.INPUT_NUMBER,
      readonly: true,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'leftCattle',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'leftCows',
      type: ReportSettingType.INPUT_NUMBER,
      readonly: true,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'infectionPercent',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'totalInfectionResearch',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'revealed',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'onLastMonday',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'perWheekRevealed',
      type: ReportSettingType.INPUT_NUMBER,
      readonly: true,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'perWheekSlaughtered',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'leftOnHold',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'deadline',
      type: ReportSettingType.INPUT_NUMBER,
      readonly: true,
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
        value: ''
      },
    ]
  };
}
