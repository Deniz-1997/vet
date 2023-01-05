import {ColReportSettingModel, HeaderReportSettingModel, ReportModel, ReportSettingType} from '@korvet/ui-elements';

export class Vet2AerosolTechnologyInfoSettingsModel extends ReportModel {
  public readonly showTotalRow = false;
  public readonly showFooter: boolean = false;
  public readonly fxFlex = '50%';
  public readonly title = 'Сведения о применении аэрозолей для профилактики';

  public readonly headers: Array<HeaderReportSettingModel> = [
    {
      title: '',
      name: 'name'
    },
    {
      title: 'Обработано (гол.)',
      name: 'processed'
    },
    {
      title: 'Заболело',
      name: 'gotSick'
    },
    {
      title: '% лечебной и профилактической эффективности',
      name: 'percent'
    },
  ];

  public readonly columns: Array<ColReportSettingModel> = [
    {
      name: 'name',
      type: ReportSettingType.TITLE,
    },
    {
      name: 'processed',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'gotSick',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'percent',
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
        value: 'Крупный рогатый скот'
      },
      {
        nameCol: 'name',
        value: 'Свиньи'
      },
      {
        nameCol: 'name',
        value: 'Мелкий рогатый скот'
      },
    ]
  };
}
