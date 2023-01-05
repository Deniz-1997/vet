import {ColReportSettingModel, HeaderReportSettingModel, ReportModel, ReportSettingType} from '@korvet/ui-elements';


export class VetLivestockHorsesCamelsModel extends ReportModel {
  public readonly title = 'Поголовье лошадей, оленей, верблюдов';
  public readonly showTotalRow = true;
  public readonly fxFlex = '50%';
  public readonly showFooter: boolean = true;
  public readonly formName: string = 'livestock-camels';
  public readonly useFxFlex: boolean = true;
  public readonly headers: Array<HeaderReportSettingModel> = [
    {
      title: '',
      name: 'name',
    },
    {
      title: 'Лошади',
      name: 'horses',
    },
    {
      title: 'Олени',
      name: 'deer',
    },
    {
      title: 'Верблюды',
      name: 'camels',
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
      name: 'horses',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
      size: {
        fxFlex: '',
      }
    },
    {
      name: 'deer',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
      size: {
        fxFlex: '',
      }
    },
    {
      name: 'camels',
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
