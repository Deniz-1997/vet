import {ColReportSettingModel, HeaderReportSettingModel, ReportModel, ReportSettingType} from '@korvet/ui-elements';


export class VetReproductionGestationLossModel extends ReportModel {
  public readonly showTotalRow = false;
  public readonly showFooter: boolean = false;
  public readonly fxFlex = '50%';
  public readonly formName: string = 'reproduction-gestation-loss';
  public readonly headers: Array<HeaderReportSettingModel> = [
    {
      title: '',
      name: 'name',
    },
    {
      title: 'Потери стельности',
      name: 'gestation',
    }
  ];
  public readonly columns: Array<ColReportSettingModel> = [
    {
      name: 'name',
      type: ReportSettingType.TITLE,
    },
    {
      name: 'gestation',
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
        value: 'Аборт'
      },
      {
        nameCol: 'name',
        value: 'Мертворожденные'
      },
      {
        nameCol: 'name',
        value: 'Вынужденно убитые стельные'
      },
    ]
  };
}
