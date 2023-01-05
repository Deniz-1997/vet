import {ColReportSettingModel, HeaderReportSettingModel, ReportModel, ReportSettingType} from '@korvet/ui-elements';

export class VetCatsDogsSettingsModels extends ReportModel {
  public readonly title = 'Собаки, Кошки';
  public readonly showTotalRow = true;
  public readonly showFooter: boolean = true;
  public readonly fxFlex = '50%';
  public readonly formName: string = 'livestock-dogs';
  public readonly headers: Array<HeaderReportSettingModel> = [
    {
      title: '',
      name: 'name'
    },
    {
      title: 'Собаки',
      name: 'dogs'
    },
    {
      title: 'Кошки',
      name: 'cats'
    },
    {
      title: 'Дикие звери',
      name: 'wildAnimal'
    },
    {
      title: 'Пушные звери',
      name: 'pusnoyZver'
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
      name: 'dogs',
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
      name: 'cats',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
      size: {
        cols: 3,
        fxFlex: ''
      }
    },
    {
      name: 'wildAnimal',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
      size: {
        cols: 3,
        fxFlex: ''
      }
    },
    {
      name: 'pusnoyZver',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
      size: {
        cols: 3,
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
