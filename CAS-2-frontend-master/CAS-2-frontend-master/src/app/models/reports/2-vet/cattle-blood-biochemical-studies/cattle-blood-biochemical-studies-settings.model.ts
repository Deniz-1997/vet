import {ColReportSettingModel, HeaderReportSettingModel, ReportModel, ReportSettingType} from '@korvet/ui-elements';

export class Vet2CattleBloodBiochemicalStudiesSettingsModel extends ReportModel {

  public readonly showTotalRow = false;
  public readonly showFooter: boolean = false;
  public readonly fxFlex = '100%';
  public readonly title = 'Сведения о результатах биохимических исследований крови рогатого скота (количество проб с отклонениями от нормы)';

  public readonly headers: Array<HeaderReportSettingModel> = [
    {
      title: '',
      name: 'name'
    },
    {
      title: 'Количество (штук)',
      name: 'count'
    },
    {
      title: 'Количество (%)',
      name: 'percent'
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
      required: true
    },
    {
      name: 'percent',
      type: ReportSettingType.INPUT_NUMBER,
      readonly: true
    },
  ];
  public readonly useStubCols: boolean = true;

  public readonly stubCols: { countCols: number, stub: Array<{ nameCol: string, value: string, disabled?: boolean }> } = {
    countCols: 7,
    stub: [
      {
        nameCol: 'name',
        value: 'Количество исследований'
      },
      {
        nameCol: 'name',
        value: 'Каротина'
      },
      {
        nameCol: 'name',
        value: 'Общего белка'
      },
      {
        nameCol: 'name',
        value: 'Кальция'
      },
      {
        nameCol: 'name',
        value: 'Фосфора'
      },
      {
        nameCol: 'name',
        value: 'Глюкозы'
      },
      {
        nameCol: 'name',
        value: 'Щелочной резерв'
      },
    ]
  };
}
