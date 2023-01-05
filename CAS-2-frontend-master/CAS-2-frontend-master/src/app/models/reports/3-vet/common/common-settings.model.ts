import {ColReportSettingModel, HeaderReportSettingModel, ReportModel, ReportSettingType} from '@korvet/ui-elements';

export class Vet3CommonSettingsModel extends ReportModel {

  public readonly title: string = 'Сведения о неблагополучных пунктах и ветеринарных мероприятиях';
  public readonly type = 'table';
  public readonly formName: string = 'vet3_common';
  public readonly useFxFlex: boolean = false;

  public readonly headers: Array<HeaderReportSettingModel> = [
    {
      class: 'off-border-col',
      name: 'name',
      rowspan: 2
    },
    {
      title: 'Выявлено неблагополучных пунктов, единиц',
      colspan: 2,
    },
    {
      title: 'Оздоровлено неблагополучных пунктов, единиц',
      colspan: 2,
    },
    {
      title: 'Осталось неблагополучных пунктов, единиц',
      colspan: 2,
    },
    {
      title: 'Обработано рыб, тыс. штук',
      name: 'fishProcessed',
      class: 'fish-cls',
      rowspan: 2
    },
    {
      title: 'Обработано икры, тыс. штук',
      name: 'caviarProcessed',
      class: 'fish-cls',
      rowspan: 2
    },
    {
      title: 'Летование прудов',
      colspan: 2,
    },
    {
      title: 'Дезинфекция прудов',
      colspan: 2,
    },
    {
      title: 'Дезинфекция басейнов',
      colspan: 2,
    },
   /*  {
      class: 'off-top-left-border',
      name: 'emptyFirstTb',
      level: 1
    }, */
    {
      title: 'Рыбоводных хозяйств',
      level: 1
    },
    {
      title: 'Рыбопромысловых водоемов',
      level: 1
    },
    {
      title: 'Рыбоводных хозяйств',
      level: 1
    },
    {
      title: 'Рыбопромысловых водоемов',
      level: 1
    },
    {
      title: 'Рыбоводных хозяйств',
      level: 1
    },
    {
      title: 'Рыбопромысловых водоемов',
      level: 1
    },
    {
      title: 'Кол-во прудов, единиц',
      level: 1
    },
    {
      title: 'га',
      level: 1
    },
    {
      title: 'Кол-во прудов, единиц',
      level: 1
    },
    {
      title: 'га',
      level: 1
    },
    {
      title: 'Кол-во прудов, единиц',
      level: 1
    },
    {
      title: 'га',
      level: 1
    },
  ];

  public readonly columns: Array<ColReportSettingModel> = [
    {
      name: 'name',
      type: ReportSettingType.TITLE
    },
    {
      name: 'unsuccessfulFishFarm',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
        max: 10
      },
    },
    {
      name: 'unsuccessfulFishingReservoirs',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true
      },
    },
    {
      name: 'improvedUnsuccessfulFishFarm',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true
      }
    },
    {
      name: 'improvedUnsuccessfulFishingReservoirs',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true
      },
    },
    {
      name: 'leftUnsuccessfulFishFarm',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true
      },
    },
    {
      name: 'leftUnsuccessfulFishingReservoirs',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true
      },
    },
    {
      name: 'fishProcessed',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true
      },
    },
    {
      name: 'caviarProcessed',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true
      }
    },
    {
      name: 'clearPondsCount',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true
      }
    },
    {
      name: 'clearPondsHectare',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true
      }
    },
    {
      name: 'disinfectionPondsCount',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true
      }
    },
    {
      name: 'disinfectionPondsHectare',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true
      },
    },
    {
      name: 'disinfectionPoolsCount',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true
      }
    },
    {
      name: 'disinfectionPoolsHectare',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true
      }
    }
  ];

  public indexRowOfColumns = 1;

  public readonly useStubCols: boolean = true;

  public readonly stubCols: { countCols: number, stub: Array<{ nameCol: string, value: string }> } = {
    countCols: 16,
    stub: [
      {
        nameCol: 'name',
        value: 'Аэромоноз карпов'
      },
      {
        nameCol: 'name',
        value: 'Аэромоноз (фурункулез) лососевых'
      },
      {
        nameCol: 'name',
        value: 'Бранхионекроз карпов'
      },
      {
        nameCol: 'name',
        value: 'Воспаление плавательного пузыря карпов'
      },
      {
        nameCol: 'name',
        value: 'Вибриоз лососевых'
      },
      {
        nameCol: 'name',
        value: 'Псевдомоноз карповых рыб'
      },
      {
        nameCol: 'name',
        value: 'Ботриоцефалез'
      },
      {
        nameCol: 'name',
        value: 'Дифиллоботриоз'
      },
      {
        nameCol: 'name',
        value: 'Ихтиофтириоз'
      },
      {
        nameCol: 'name',
        value: 'Описторхоз'
      },
      {
        nameCol: 'name',
        value: 'Филометроидоз'
      },
      {
        nameCol: 'name',
        value: 'Весенняя виремия карпов'
      },
      {
        nameCol: 'name',
        value: 'Некроз поджелудочной железы лососевых'
      },
      {
        nameCol: 'name',
        value: 'Некроз гемопоэтической ткани'
      },
      {
        nameCol: 'name',
        value: 'Вирусная геморрагическая септицемия'
      },
      {
        nameCol: 'name',
        value: 'Благополучно по болезням рыб'
      },
    ]
  };
}

