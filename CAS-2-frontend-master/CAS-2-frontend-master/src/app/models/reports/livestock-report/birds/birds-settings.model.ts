import {ColReportSettingModel, HeaderReportSettingModel, ReportModel, ReportSettingType} from '@korvet/ui-elements';

export class VetLivestockBirdsModel extends ReportModel {
  public readonly title = 'Поголовье птицы';
  public readonly showTotalRow = true;
  public readonly showFooter: boolean = true;
  public readonly fxFlex = '50%';
  public readonly useFxFlex: boolean = true;
  public readonly formName: string = 'livestock-birds';
  public readonly headers: Array<HeaderReportSettingModel> = [
    {
      title: '',
      name: 'name'
    },
    {
      title: 'Птицы',
      name: 'birds'
    },
    {
      title: 'Голуби',
      name: 'doves'
    },
    {
      title: 'Страусы',
      name: 'ostriches'
    },
  ];
  public readonly columns: Array<ColReportSettingModel> = [
    {
      name: 'name',
      type: ReportSettingType.TITLE,
      size: {
        fxFlex: '20',
      }
    },
    {
      name: 'birds',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
      size: {
        fxFlex: '',
      }
    },
    {
      name: 'doves',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
      size: {
        fxFlex: '',
      }
    },
    {
      name: 'ostriches',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
      size: {
        fxFlex: '',
      }
    },
  ];

  public readonly useStubCols: boolean = true;

  public readonly stubCols: { countCols: number, stub: Array<{ nameCol: string, value: string, disabled?: boolean }> } = {
    countCols: 2,
    stub: [
      {
        nameCol: 'name',
        value: 'ХС'
      },
      {
        nameCol: 'name',
        value: 'Частные'
      },
    ]
  };
}
