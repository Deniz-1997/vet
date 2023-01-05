import {ColReportSettingModel, HeaderReportSettingModel, ReportModel, ReportSettingType} from '@korvet/ui-elements';

export class Vet1GReportSettingsModel extends ReportModel {
  public readonly showFooter: boolean = false;
  public readonly showTotalRow = false;
  public readonly type = 'table';
  public readonly useFxFlex = true;
  public readonly formName: string = '1-vet-g-report';
  public readonly headers: Array<HeaderReportSettingModel> = [
    {
      title: 'Крупный рогатый скот и северные олени',
      name: 'name',
      rowspan: 3
    },
    {
      title: 'Наличие поголовья на начало года',
      name: 'livestockAvailble',
      rowspan: 3
    },
    {
      title: 'За отчетный период',
      colspan: 13,
    },
    {
      title: 'Осталось на конец отчетного периода',
      colspan: 3,
    },
    {
      title: 'Исследовано животных, голов',
      colspan: 4,
      level: 1
    },
    {
      title: 'Выявлено больных животных, голов',
      colspan: 4,
      level: 1
    },
    {
      title: 'Выявлено неблагополучных пунктов (единиц)',
      rowspan: 2,
      level: 1
    },
    {
      title: 'Обработано с профилактической целью (голов)',
      rowspan: 2,
      level: 1
    },
    {
      title: 'Обработано с лечебной целью (голов)',
      rowspan: 2,
      level: 1
    },
    {
      title: 'Оздоровлено неблагополучных пунктов',
      colspan: 2,
      level: 1
    },
    {
      title: 'Неблагополучных пунктов (единиц)',
      rowspan: 2,
      level: 1
    },
    {
      title: 'Больных животных (голов)',
      rowspan: 2,
      level: 1
    },
    {
      title: '% от общего поголовья животных',
      rowspan: 2,
      level: 1
    },
    {
      title: 'Клинически',
      level: 2
    },
    {
      title: '% от общего поголовья животных',
      level: 2
    },
    {
      title: 'Методом ранней диагностики (КРС)',
      level: 2
    },
    {
      title: '% от общего поголовья животных',
      level: 2
    },
    {
      title: 'Клинически',
      level: 2
    },
    {
      title: ' % ',
      level: 2
    },
    {
      title: 'Методом ранней диагностики (КРС)',
      level: 2
    },
    {
      title: ' % ',
      level: 2,
    },
    {
      title: 'Всего (единиц)',
      level: 2
    },
    {
      title: '% от общего количества неблагополучных пунктов',
      level: 2
    },
  ];

  public readonly columns: Array<ColReportSettingModel> = [
    {
      name: 'name',
      type: ReportSettingType.TITLE,
    },
    {
      name: 'livestockAvailble',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'investigatedClinically',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'investigatedPercentClinically',
      type: ReportSettingType.INPUT_NUMBER,
      readonly: true,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'investigatedDiagnostic',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'investigatedPercentDiagnostic',
      type: ReportSettingType.INPUT_NUMBER,
      readonly: true,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'researchedClinically',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'researchedPercentClinically',
      type: ReportSettingType.INPUT_NUMBER,
      readonly: true,
      validateRules: {
        required: true,
      },
      style: {
        'min-width': '80px',
      },
    },
    {
      name: 'researchedDiagnostic',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'researchedPercentDiagnostic',
      type: ReportSettingType.INPUT_NUMBER,
      readonly: true,
      validateRules: {
        required: true,
      },
      style: {
        'min-width': '80px',
      },
    },
    {
      name: 'researchedDisadvantagePoints',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'processedForProfilactic',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'processedForMedical',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'healedTotal',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'healedPercent',
      type: ReportSettingType.INPUT_NUMBER,
      readonly: true,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'leftBadPoints',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'leftSickAnimals',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'leftPercentAnimals',
      type: ReportSettingType.INPUT_NUMBER,
      readonly: true,
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
        value: 'КРС всего'
      },
      {
        nameCol: 'name',
        value: 'в том числе коровы'
      },
      {
        nameCol: 'name',
        value: 'Северные олени'
      },
    ]
  };
}
