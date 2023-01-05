import {ColReportSettingModel, HeaderReportSettingModel, ReportModel, ReportSettingType} from '@korvet/ui-elements';

export class Vet3AquacultureSettingsModel extends ReportModel {
  public readonly title = 'Сведения по учёту аквакультуры';
  public readonly type = 'table';
  public readonly formName: string = 'vet3_aquaculture';
  public readonly isAddRow: boolean = true;
  public readonly showTotalRow = false;
  public readonly showFooter: boolean = false;
  public readonly fxFlex = '100%';
  public readonly headers: Array<HeaderReportSettingModel> = [
    {
      title: 'Наименование района',
      name: 'regionName',
      rowspan: 2,
    },
    {
      title: 'Наименование предприятия аквакультуры',
      name: 'companyName',
      rowspan: 2,

    },
    {
      title: 'Юридический адрес предприятия',
      name: 'juridicalAddress',
      rowspan: 2,
    },
    {
      title: 'Фактическое местонахождение предприятия',
      name: 'factAddress',
      rowspan: 2,
    },
    {
      title: 'Тип хоз-ва ( прудовое,озерное и т.д.)',
      name: 'farmType',
      rowspan: 2,
    },
    {
      title: 'Наименование используемого водоема, его месторасположение',
      name: 'poundName',
      rowspan: 2,
    },
    {
      title: 'Название объектов аквакультуры (на русском языке)',
      name: 'aquacultureNameRus',
      rowspan: 2,
    },
    {
      title: 'Название объектов аквакультуры (на латинском языке)',
      name: 'aquacultureNameLat',
      rowspan: 2,
    },
    {
      title: 'Ассортимент выпускаемой продукции (товарная, посадочный материал и т.д.)',
      name: 'products',
      rowspan: 2,
    },
    {
      title: 'Мощность пред-я (тонн)',
      name: 'enterpriseCapacity',
      rowspan: 2,
    },
    {
      title: 'Производительность пред-я (тонн)',
      name: 'enterpriseEfficiency',
      rowspan: 2,
    },
    {
      title: 'Используемые корма ( естественная кормовая база. Культивированные кормовые организмы, специализированные корма)',
      name: 'feedBase',
      rowspan: 2,
    },
    {
      title: 'При использовании специализированных кормов',
      colspan: 2,
    },
    {
      title: 'Название кормов',
      name: 'feedName',
      level: 1,
    },
    {
      title: 'Наименование страны и фирмы производителя',
      name: 'feedCountry',
      level: 1,
    },
    {
      title: 'Выявленные болезни ( за последние 4 года)',
      name: 'detectedDiseases',
      rowspan: 2,
    },
    {
      title: 'Используемые дезсредства',
      name: 'usedDisinfectants',
      rowspan: 2,
    },
  ];

  public readonly columns: Array<ColReportSettingModel> = [
    {
      name: 'regionName',
      type: ReportSettingType.INPUT_TEXT,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'companyName',
      type: ReportSettingType.INPUT_TEXT,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'juridicalAddress',
      type: ReportSettingType.INPUT_TEXT,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'factAddress',
      type: ReportSettingType.INPUT_TEXT,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'farmType',
      type: ReportSettingType.INPUT_TEXT,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'poundName',
      type: ReportSettingType.INPUT_TEXT,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'aquacultureNameRus',
      type: ReportSettingType.INPUT_TEXT,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'aquacultureNameLat',
      type: ReportSettingType.INPUT_TEXT,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'products',
      type: ReportSettingType.INPUT_TEXT,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'enterpriseCapacity',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'enterpriseEfficiency',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'feedBase',
      type: ReportSettingType.INPUT_TEXT,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'feedName',
      type: ReportSettingType.INPUT_TEXT,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'feedCountry',
      type: ReportSettingType.INPUT_TEXT,
      validateRules: {
        required: true,
      },
    },
    {
      name: 'detectedDiseases',
      type: ReportSettingType.INPUT_TEXT,
      validateRules: {
        required: true,
      },
    },
    // {
    //   name: 'usedMedicines',
    //   type: ReportSettingType.INPUT_TEXT,
    //   validateRules: {
    //     required: true,
    //   },
    // },
    {
      name: 'usedDisinfectants',
      type: ReportSettingType.INPUT_TEXT,
      validateRules: {
        required: true,
      },
    }
  ];
}
