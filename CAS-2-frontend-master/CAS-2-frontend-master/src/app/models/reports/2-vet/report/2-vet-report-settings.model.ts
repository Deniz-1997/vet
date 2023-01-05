import {ColReportSettingModel, HeaderReportSettingModel, ReportModel, ReportSettingType} from '@korvet/ui-elements';

export class Vet2ReportSettingsModel extends ReportModel {
  public readonly showFooter: boolean = false;
  public readonly showTotalRow = false;
  public readonly type = 'table';
  public readonly formName: string = '2-vet-report';
  public readonly headers: Array<HeaderReportSettingModel> = [
    {
      title: '',
      name: 'name',
      rowspan: 3
    },
    {
      title: 'Зарегистрированно больных животных первично, голов',
      colspan: 3,
    },
    {
      title: 'Из числа зарегистрированных больных пало и вынужденно забито, голов',
      colspan: 6,
    },
    {
      title: 'Крупного рогатого скота',
      rowspan: 2,
      level: 1
    },
    {
      title: 'Свиней',
      rowspan: 2,
      level: 1
    },
    {
      title: 'Мелкого рогатого скота',
      rowspan: 2,
      level: 1
    },
    {
      title: 'Крупного рогатого скота',
      colspan: 2,
      level: 1
    },
    {
      title: 'Свиней',
      colspan: 2,
      level: 1
    },
    {
      title: 'Мелкого рогатого скота',
      colspan: 2,
      level: 1
    },
    {
      title: 'Пало',
      level: 2
    },
    {
      title: 'Вынуждено забито',
      level: 2
    },
    {
      title: 'Пало',
      level: 2
    },
    {
      title: 'Вынуждено забито',
      level: 2
    },
    {
      title: 'Пало',
      level: 2
    },
    {
      title: 'Вынуждено забито',
      level: 2
    },
  ];

  public readonly columns: Array<ColReportSettingModel> = [
    {
      name: 'name',
      type: ReportSettingType.TITLE,
    },
    {
      name: 'diseaseCattleCount',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'diseasePigCount',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'diseaseSmallCattleCount',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'cattleCountFall',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'cattleCountSlaughter',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'PigCountFall',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'PigCountSlaughter',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'smallCattleCountFall',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'smallCattleCountSlaughter',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
  ];
  public readonly useStubCols: boolean = true;

  public readonly stubCols: { countCols: number, stub: Array<{ nameCol: string, value: string, disabled?: boolean }> } = {
    countCols: 15,
    stub: [
      {
        nameCol: 'name',
        value: 'Хозяйства всех категорий - всего'
      },
      {
        nameCol: 'name',
        value: 'в том числе: сельхозорганизации'
      },
      {
        nameCol: 'name',
        value: 'хозяйства населения'
      },
      {
        nameCol: 'name',
        value: 'фермерские хозяйства'
      },
      {
        nameCol: 'name',
        value: 'Из числа заболевших:'
      },
      {
        nameCol: 'name',
        value: 'болезни органов пищеварения - всего'
      },
      {
        nameCol: 'name',
        value: 'в том числе молодняка'
      },
      {
        nameCol: 'name',
        value: 'болезни органов дыхания - всего'
      },
      {
        nameCol: 'name',
        value: 'в том числе молодняка'
      },
      {
        nameCol: 'name',
        value: 'болезни обмена веществ - всего'
      },
      {
        nameCol: 'name',
        value: 'в том числе молодняка'
      },
      {
        nameCol: 'name',
        value: 'болезни органов размножения у маток - всего'
      },
      {
        nameCol: 'name',
        value: 'в том числе маститы'
      },
      {
        nameCol: 'name',
        value: 'травмы - всего'
      },
      {
        nameCol: 'name',
        value: 'отравления'
      },
    ]
  };
}
