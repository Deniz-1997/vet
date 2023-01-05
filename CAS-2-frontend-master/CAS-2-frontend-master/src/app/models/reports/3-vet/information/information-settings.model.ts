import {ColReportSettingModel, HeaderReportSettingModel, ReportModel, ReportSettingType} from '@korvet/ui-elements';

export class Vet3InformationSettingsModel extends ReportModel {

  public readonly formName: string = 'vet3_information';

  public readonly title = 'Общие положения';

  public readonly headers: Array<HeaderReportSettingModel> = [
    {
      title: 'Наименование',
      name: 'name',
    },
    {
      title: 'Количество',
      name: 'quantity',
    },
    {
      title: 'Неблагополучные по заразным болезням',
      name: 'dysfunctional',
    },
    {
      title: 'Обследовано эпизоотологически',
      name: 'examined',
    }
  ];

  public readonly columns: Array<ColReportSettingModel> = [
    {
      name: 'name',
      type: ReportSettingType.TITLE
    },
    {
      name: 'quantity',
      type: ReportSettingType.INPUT_NUMBER,
      placeholder: 'Введите значение',
      validateRules: {
        required: true,
        max: 10
      }
    },
    {
      name: 'dysfunctional',
      type: ReportSettingType.INPUT_NUMBER,
      placeholder: 'Введите значение',
      validateRules: {
        required: true
      }
    },
    {
      name: 'examined',
      type: ReportSettingType.INPUT_NUMBER,
      placeholder: 'Введите значение',
      validateRules: {
        required: true
      }
    }
  ];

  public readonly useStubCols: boolean = true;

  public readonly stubCols: { countCols: number, stub: Array<{ nameCol: string, value: string }> } = {
    countCols: 2,
    stub: [
      {
        nameCol: 'name',
        value: 'Рыбоводные хозяйства - всего'
      },
      {
        nameCol: 'name',
        value: 'Рыбопромысловые водоемы - всего'
      }
    ]
  };
}
