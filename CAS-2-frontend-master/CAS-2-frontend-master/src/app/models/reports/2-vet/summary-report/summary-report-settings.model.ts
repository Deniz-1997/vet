import {ColReportSettingModel, HeaderReportSettingModel, ReportModel, ReportSettingType} from '@korvet/ui-elements';

export class Vet2SummaryReportSettingsModel extends ReportModel {
  public readonly showTotalRow = false;
  public readonly showFooter: boolean = false;
  public readonly fxFlex = '100%';
  public readonly headers: Array<HeaderReportSettingModel> = [
    {
      title: '',
      name: 'name'
    },
    {
      title: 'КРС',
      name: 'cattle'
    },
    {
      title: 'Свиньи',
      name: 'pigs'
    },
    {
      title: 'Лошади',
      name: 'horse'
    },
    {
      title: 'Собаки',
      name: 'dogs'
    },
    {
      title: 'Кошки',
      name: 'cats'
    },
  ];

  public readonly columns: Array<ColReportSettingModel> = [
    {
      name: 'name',
      type: ReportSettingType.TITLE,
    },
    {
      name: 'cattle',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'pigs',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'horse',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'dogs',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'cats',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
  ];

  public readonly useStubCols: boolean = true;

  public readonly stubCols: { countCols: number, stub: Array<{ nameCol: string, value: string, disabled?: boolean }> } = {
    countCols: 20,
    stub: [
      {
        nameCol: 'name',
        value: 'Маститы',
        disabled: true
      },
      {
        nameCol: 'name',
        value: 'Подвергнуто лечению (гол.)'
      },
      {
        nameCol: 'name',
        value: 'Выздоровело (гол.)'
      },
      {
        nameCol: 'name',
        value: '% лечебной эффективности'
      },
      {
        nameCol: 'name',
        value: 'Эндометриты',
        disabled: true
      },
      {
        nameCol: 'name',
        value: 'Подвергнуто лечению (гол.)'
      },
      {
        nameCol: 'name',
        value: 'Выздоровело (гол.)'
      },
      {
        nameCol: 'name',
        value: '% лечебной эффективности'
      },
      {
        nameCol: 'name',
        value: 'Хирургические болезни',
        disabled: true
      },
      {
        nameCol: 'name',
        value: 'Подвергнуто лечению (гол.)'
      },
      {
        nameCol: 'name',
        value: 'Выздоровело (гол.)'
      },
      {
        nameCol: 'name',
        value: '% лечебной эффективности'
      },
      {
        nameCol: 'name',
        value: 'Респираторные болезни',
        disabled: true
      },
      {
        nameCol: 'name',
        value: 'Подвергнуто лечению (гол.)'
      },
      {
        nameCol: 'name',
        value: 'Выздоровело (гол.)'
      },
      {
        nameCol: 'name',
        value: '% лечебной эффективности'
      },
      {
        nameCol: 'name',
        value: 'Болезни органов пищеварения',
        disabled: true
      },
      {
        nameCol: 'name',
        value: 'Подвергнуто лечению (гол.)'
      },
      {
        nameCol: 'name',
        value: 'Выздоровело (гол.)'
      },
      {
        nameCol: 'name',
        value: '% лечебной эффективности'
      },
    ]
  };
}
