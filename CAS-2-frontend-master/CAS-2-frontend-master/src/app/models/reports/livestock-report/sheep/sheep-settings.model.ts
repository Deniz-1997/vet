import {ColReportSettingModel, HeaderReportSettingModel, ReportModel, ReportSettingType} from '@korvet/ui-elements';

export class VetLivestockSheepSettingsModel extends ReportModel {
  public readonly title = 'МРС (Овцы)';
  public readonly showTotalRow = true;
  public readonly fxFlexMd = '100%';
  public readonly showFooter: boolean = true;
  public readonly formName: string = 'livestock-sheep';
  public readonly headers: Array<HeaderReportSettingModel> = [
    {
      title: '',
      name: 'name',
    },
    {
      title: 'Овцы (всего)',
      name: 'total',
    },
    {
      title: 'Бараны',
      name: 'male',
    },
    {
      title: 'Овцематки',
      name: 'female',
    },
    {
      title: 'Переярки',
      name: 'pereyarki',
    },
    {
      title: 'Молодняк до года',
      name: 'underYear',
    },
    {
      title: 'Ожидаемый приплод',
      name: 'expectedOffspring',
    },
  ];
  public readonly columns: Array<ColReportSettingModel> = [
    {
      name: 'name',
      type: ReportSettingType.TITLE,
      size: {
        cols: 1,
        fxFlex: '20'
      }
    },
    {
      name: 'total',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
      size: {
        cols: 2,
        fxFlex: ''
      }
    },
    {
      name: 'male',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
      size: {
        cols: 1,
        fxFlex: ''
      }
    },
    {
      name: 'female',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
      size: {
        cols: 2,
        fxFlex: ''
      }
    },
    {
      name: 'pereyarki',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
      size: {
        cols: 2,
        fxFlex: ''
      }
    },
    {
      name: 'underYear',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
      size: {
        cols: 2,
        fxFlex: ''
      }
    },
    {
      name: 'expectedOffspring',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
      size: {
        cols: 2,
        fxFlex: ''
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
