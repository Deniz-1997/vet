import {ColReportSettingModel, HeaderReportSettingModel, ReportModel, ReportSettingType} from '@korvet/ui-elements';
export class Vet1OperationsSettingsModel extends ReportModel {
  public readonly showFooter: boolean = false;
  public readonly showTotalRow = false;
  public readonly fxFlex = '35%';
  public readonly type = 'table';
  public readonly formName: string = '1-vet-a-operations';
  public readonly headers: Array<HeaderReportSettingModel> = [
    {
      title: '',
      name: 'name',
      rowspan: 2
    },
    {
      title: 'Обработано животноводческих помещений, территорий, ферм, предприятий',
      name: 'sub_header',
      colspan: 2
    },
    {
      title: 'Количество объектов, шт.',
      name: 'count',
      level: 1
    },
    {
      title: 'тыс.кв.м.',
      name: 'square',
      level: 1
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
    {
      name: 'square',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
  ];

  public readonly useStubCols: boolean = true;

  public readonly stubCols: { countCols: number, stub: Array<{ nameCol: string, value: string, disabled?: boolean }> } = {
    countCols: 7,
    stub: [
      {
        nameCol: 'name',
        value: 'Дезинфекция'
      },
      {
        nameCol: 'name',
        value: 'а) профилактическая'
      },
      {
        nameCol: 'name',
        value: 'в т.ч. аэрозольная'
      },
      {
        nameCol: 'name',
        value: 'б) вынужденная'
      },
      {
        nameCol: 'name',
        value: 'в т.ч. аэрозольная'
      },
      {
        nameCol: 'name',
        value: 'Дезинсекция'
      },
      {
        nameCol: 'name',
        value: 'Дератизация'
      },
    ]
  };
}
