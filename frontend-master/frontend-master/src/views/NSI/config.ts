// TODO: очень плохо
export const nsiList = {
  'nsi-type-product': {
    name: 'Вид сельскохозяйственной культуры',
    title: 'Вид сельскохозяйственной культуры и продукта переработки зерна, подлежащей прослеживаемости',
    apiUrl: '/api/nci/okpd2Tnved',
    createUrl: '/api/nci/okpd2Tnved',
    additionalApiUrl: '/api/nci/okpd2',
    catalogTnvedApiUrl: '/api/nci/tnved',
    storeSetter: 'nsi/setTnvedList',
    storeGetter: 'nsi/getTnvedList',
    headers: [
      {
        text: 'Действия',
        value: 'actions',
      },
      {
        text: 'Код ОКПД2',
        value: 'okpd2.code',
      },
      {
        text: 'Код ТН ВЭД',
        value: 'tnved.code',
      },
      {
        text: 'Наименование культуры зерна или продукта переработки зерна',
        value: 'okpd2.name',
      },
      {
        text: 'Наименование ТНВЭД',
        value: 'tnved.name',
      },
      {
        text: 'Действует с',
        value: 'start_date',
      },
      {
        text: 'Действует по',
        value: 'end_date',
      },
    ],
    modal: ['okpd2', 'tnved', 'start_date', 'end_date'],
    meta: {
      breadcrumb: [
        { name: 'Вид сельскохозяйственной культуры и продукта переработки зерна, подлежащей прослеживаемости' },
      ],
    },
  },
  'nsi-type-product-msh': {
    apiUrl: '/api/nci/typeProduct/msh',
    storeSetter: {
      is_grain: 'nsi/setProductTypesGrain',
      is_product: 'nsi/setProductTypesProduct',
      use_lot_period_product: 'nsi/setProductTypesPeriodProduct',
      use_lot_period_grain: 'nsi/setProductTypesPeriodGrain',
    },
    storeGetter: {
      is_grain: 'nsi/getProductTypesGrain',
      is_product: 'nsi/getProductTypesProduct',
      use_lot_period_product: 'nsi/getProductTypesPeriodProduct',
      use_lot_period_grain: 'nsi/getProductTypesPeriodGrain',
    },
  },
  'nsi-transport-type': {
    name: 'Вид транспортных средств',
    title: 'Вид транспортных средств',
    apiUrl: '/api/nci/sdizTransportType',
    storeSetter: 'nsi/setTransportTypes',
    storeGetter: 'nsi/getTransportTypes',

    headers: [
      {
        text: 'Действия',
        value: 'actions',
      },
      {
        text: 'Наименование',
        value: 'name',
      },
      {
        text: 'Действует с',
        value: 'start_date',
      },
      {
        text: 'Действует по',
        value: 'end_date',
      },
    ],
    modal: ['name', 'start_date', 'end_date'],
  },
  'nsi-lots-purpose': {
    name: 'Назначение партии зерна',
    title: 'Назначение партии зерна',
    apiUrl: '/api/nci/lotsPurpose',
    storeSetter: 'nsi/setLotsPurpose',
    storeGetter: 'nsi/getLotsPurpose',

    headers: [
      {
        text: 'Действия',
        value: 'actions',
      },
      {
        text: 'Наименование',
        value: 'name',
      },
      {
        text: 'Действует с',
        value: 'start_date',
      },
      {
        text: 'Действует по',
        value: 'end_date',
      },
    ],
    modal: ['name', 'start_date', 'end_date'],
  },
  'nsi-lots-target': {
    name: 'Цель использования партии зерна',
    title: 'Цель использования партии зерна или партии продуктов переработки зерна',
    apiUrl: '/api/nci/lotsTarget',
    storeSetter: 'nsi/setLotsTarget',
    storeGetter: 'nsi/getLotsTarget',

    headers: [
      {
        text: 'Действия',
        value: 'actions',
      },
      {
        text: 'Наименование',
        value: 'name',
      },
      {
        text: 'Действует с',
        value: 'start_date',
      },
      {
        text: 'Действует по',
        value: 'end_date',
      },
    ],
    modal: ['name', 'start_date', 'end_date'],
  },
  'nsi-sample-type': {
    name: 'Вид отбора проб',
    title: 'Вид отбора проб',
    apiUrl: '/api/nci/sampleType',
    storeSetter: 'nsi/setSampleType',
    storeGetter: 'nsi/getSampleType',

    headers: [
      {
        text: 'Действия',
        value: 'actions',
      },
      {
        text: 'Наименование',
        value: 'name',
      },
      {
        text: 'Действует с',
        value: 'start_date',
      },
      {
        text: 'Действует по',
        value: 'end_date',
      },
    ],
    modal: ['name', 'start_date', 'end_date'],
  },
  'nsi-granary-type': {
    name: 'Вид зернохранилища',
    title: 'Вид зернохранилища',
    apiUrl: '/api/nci/granaryType',
    storeSetter: 'elevator/setListGranaryType',
    storeGetter: 'elevator/getListGranaryType',

    headers: [
      {
        text: 'Действия',
        value: 'actions',
      },
      {
        text: 'Наименование',
        value: 'name',
      },
      {
        text: 'Действует с',
        value: 'start_date',
      },
      {
        text: 'Действует по',
        value: 'end_date',
      },
    ],
    modal: ['name', 'start_date', 'end_date'],
  },
  'nsi-document-type': {
    name: 'Вид документов',
    title: 'Вид документов',
    apiUrl: '/api/nci/documentType',
    storeSetter: 'elevator/setDocumentTypes',
    storeGetter: 'elevator/getDocumentTypes',

    headers: [
      {
        text: 'Действия',
        value: 'actions',
      },
      {
        text: 'Наименование',
        value: 'name',
      },
      {
        text: 'Действует с',
        value: 'start_date',
      },
      {
        text: 'Действует по',
        value: 'end_date',
      },
    ],
    modal: ['name', 'start_date', 'end_date'],
  },
  'nsi-quality-indicators': {
    name: 'Справочник потребительских свойств зерна и (или) продуктов переработки зерна',
    title: 'Справочник потребительских свойств зерна и (или) продуктов переработки зерна',
    // tableTitle: 'Наименование зерна / продуктов переработки зерна',
    apiUrl: '/api/nci/qualityIndicators',
    grainUrl: '/api/nci/grainGroup/findItems',
    additionalApiUrl: '/api/nci/unitOfMeasure',
    additionalApiUrlSecond: '/api/nci/indicatorPurpose',
    storeSetter: 'nsi/setQualityIndicators',
    storeGetter: 'nsi/getQualityIndicators',

    headers: [
      {
        text: 'Действия',
        value: 'actions',
      },
      {
        text: 'Наименование потребительского свойства',
        value: 'name',
      },
      {
        text: 'Код единицы измерения',
        value: 'unitOfMeasure.symbol',
      },
      {
        text: 'Действует с',
        value: 'start_date',
      },
      {
        text: 'Действует по',
        value: 'end_date',
      },
    ],
    modal: ['name', 'start_date', 'end_date', 'measureName', 'measureId', 'purposeName', 'valueFrom', 'valueTo'],
    card: ['name', 'start_date', 'end_date', 'measureName', 'measureId', 'purposeName', 'valueFrom', 'valueTo'],
    cardModal: ['grainGroupName', 'typeProductName'],
  },
  'nsi-quality-indicators-limit': {
    name: 'Справочник допустимых значений потребительских свойств зерна и (или) продуктов переработки зерна',
    title: 'Справочник допустимых значений потребительских свойств зерна и (или) продуктов переработки зерна',
    // tableTitle: 'Наименование зерна / продуктов переработки зерна',
    apiUrl: '/api/nci/qualityIndicatorLimit',
    grainUrl: '/api/nci/grainGroup/findItems',
    indicatorPurposeUrl: '/api/nci/indicatorPurpose',
    quallityIndicatorApi: '/api/nci/qualityIndicator',
    storeSetter: 'nsi/setQualityIndicatorsLimit',
    storeGetter: 'nsi/getQualityIndicatorsLimit',

    headers: [
      {
        text: 'Действия',
        value: 'actions',
      },
      {
        text: 'Наименование потребительского свойства',
        value: 'quality_indicator_name',
      },
      {
        text: 'Единица измерения',
        value: 'unit_of_measure_name',
      },
      {
        text: 'ОКПД2',
        value: 'okpd2.code',
        width: 100,
      },
      {
        text: 'Наименование зерна / продуктов переработки зерна',
        value: 'okpd2.name',
        width: 300,
      },
      {
        text: 'Страна',
        value: 'country.name_full',
        width: 200,
      },
      {
        text: 'Назначение потребительского свойства',
        value: 'purpose.name',
        width: 300,
      },
      {
        text: 'Диапазон допустимых значений с',
        value: 'min_value',
        width: 100,
      },
      {
        text: 'Диапазон допустимых значений по',
        value: 'max_value',
        width: 100,
      },
      {
        text: 'Действует с',
        value: 'start_date',
      },
      {
        text: 'Действует по',
        value: 'end_date',
      },
    ],
    modal: ['purpose', 'valueFrom', 'valueTo'],
  },
  'nsi-processing-method': {
    name: 'Способ переработки',
    title: 'Способ переработки',
    apiUrl: '/api/nci/processingMethod',
    storeSetter: 'nsi/setProcessingMethods',
    storeGetter: 'nsi/getProcessingMethods',

    headers: [
      {
        text: 'Действия',
        value: 'actions',
      },
      {
        text: 'Наименование',
        value: 'name',
      },
      {
        text: 'Действует с',
        value: 'start_date',
      },
      {
        text: 'Действует по',
        value: 'end_date',
      },
    ],
    modal: ['name', 'start_date', 'end_date'],
  },
  'nsi-storage-method': {
    name: 'Способ хранения',
    title: 'Способ хранения',
    apiUrl: '/api/nci/storageMethod',
    storeSetter: 'elevator/setStorageMethodList',
    storeGetter: 'elevator/getStorageMethodList',

    headers: [
      {
        text: 'Действия',
        value: 'actions',
      },
      {
        text: 'Наименование',
        value: 'name',
      },
      {
        text: 'Действует с',
        value: 'start_date',
      },
      {
        text: 'Действует по',
        value: 'end_date',
      },
    ],
    modal: ['name', 'start_date', 'end_date'],
  },
  'elevator-service-type': {
    name: 'Тип услуги элеваторов',
    title: 'Тип услуги элеваторов',
    apiUrl: '/api/nci/elevatorServiceType',
    storeSetter: 'elevator/setListServiceType',
    storeGetter: 'elevator/getListServiceType',

    headers: [
      {
        text: 'Действия',
        value: 'actions',
      },
      {
        text: 'Наименование',
        value: 'name',
      },
      {
        text: 'Действует с',
        value: 'start_date',
      },
      {
        text: 'Действует по',
        value: 'end_date',
      },
    ],
    modal: ['name', 'start_date', 'end_date'],
  },
  'unit-of-measure': {
    name: 'Справочник единиц измерения',
    title: 'Справочник единиц измерения',
    apiUrl: '/api/nci/unitOfMeasure',
    storeSetter: 'nsi/setUnitsOfMeasure',
    storeGetter: 'nsi/getUnitsOfMeasure',

    headers: [
      {
        text: 'Действия',
        value: 'actions',
      },
      {
        text: 'Наименование',
        value: 'name',
      },
      {
        text: 'Условное обозначение',
        value: 'symbol',
      },
      {
        text: 'Кодовое обозначение',
        value: 'code',
      },
      {
        text: 'Действует с',
        value: 'start_date',
      },
      {
        text: 'Действует по',
        value: 'end_date',
      },
    ],
    modal: ['name', 'symbol', 'code', 'start_date', 'end_date'],
  },
  // 'grain-group': {
  //   name: 'Наименование зерна / продуктов переработки зерна',
  //   title: 'Наименование зерна / продуктов переработки зерна',
  //   apiUrl: '/api/nci/okpd2',
  //   // okpdApiUrl: '/api/nci/okpd2',
  //   // tnvedApiUrl: '/api/nci/grainGroup/tnved',
  //   tableTitle: 'Вид с/х культуры и продукта переработки зерна',
  //   headers: [
  //     {
  //       text: 'Действия',
  //       value: 'actions'
  //     },
  //     {
  //       text: 'Наименование зерна / продуктов переработки зерна',
  //       value: 'name'
  //     },
  //     {
  //       text: 'Зерно',
  //       value: 'grain_sign'
  //     },
  //     {
  //       text: 'Продукты переработки зерна',
  //       value: 'grain_product_sign'
  //     },
  //     {
  //       text: 'Действует с',
  //       value: 'start_date'
  //     },
  //     {
  //       text: 'Действует по',
  //       value: 'end_date'
  //     }
  //   ],
  //   modal: ['name', 'grainProductSign', 'grainSign', 'start_date', 'end_date'],
  //   card: ['name', 'grainProductSign', 'grainSign', 'start_date', 'end_date'],
  //   cardModal: ['okpd2', 'typeProductCode'],
  //   extraHeaders: [
  //     {
  //       text: 'Действия',
  //       value: 'actions'
  //     },
  //     {
  //       text: '№',
  //       value: 'number'
  //     },
  //     {
  //       text: 'Код ОКПД 2',
  //       value: 'code'
  //     },
  //     {
  //       text: 'Наименование ОКПД 2',
  //       value: 'name'
  //     },
  //     {
  //       text: 'Код ТН ВЭД',
  //       value: 'tnved'
  //     }
  //   ]
  // },
  'indicator-purpose': {
    name: 'Назначение потребительского свойства партии зерна и (или) партии продуктов переработки зерна',
    title: 'Назначение потребительского свойства партии зерна и (или) партии продуктов переработки зерна',
    /*  tableTitle: 'Справочник  потребительских свойств зерна и (или) продуктов переработки зерна', */
    tableCardUrl: '/api/nci/indicatorPurpose/',
    apiUrl: '/api/nci/indicatorPurpose',
    storeSetter: 'nsi/setIndicatorPurposes',
    storeGetter: 'nsi/getIndicatorPurposes',

    headers: [
      {
        text: 'Действия',
        value: 'actions',
      },
      {
        text: 'Наименование',
        value: 'name',
      },
      {
        text: 'Действует с',
        value: 'start_date',
      },
      {
        text: 'Действует по',
        value: 'end_date',
      },
    ],
    // modal: ['name', 'start_date', 'end_date', 'measureName'],
    // card: ['name', 'start_date', 'end_date', 'measureName'],
    modal: ['name', 'start_date', 'end_date'],
    card: ['name', 'start_date', 'end_date'],
    extraHeaders: [
      {
        text: 'Действия',
        value: 'actions',
      },
      {
        text: '№',
        value: 'number',
      },
      {
        text: 'Наименование потребительского свойства',
        value: 'qualityIndicatorName',
      },
      {
        text: 'Код единицы измерения',
        value: 'measureName',
      },
    ],
  },
  'nsi-okpd2': {
    name: 'ОКПД2',
    title: 'ОКПД2',
    apiUrl: '/api/nci/okpd2',
    additionalApiUrl: '/api/nci/okpd2',
    storeSetter: 'nsi/setOkpd2List',
    storeGetter: 'nsi/getOkpd2List',

    headers: [
      {
        text: 'Действия',
        value: 'actions',
      },
      {
        text: 'Код ОКПД2',
        value: 'code',
      },
      {
        text: 'Наименование',
        value: 'name',
      },
      {
        text: 'Зерно',
        value: 'is_grain_ru',
      },
      {
        text: 'Продукты переработки зерна',
        value: 'is_product_ru',
      },
      {
        text: 'Действует с',
        value: 'start_date',
      },
      {
        text: 'Действует по',
        value: 'end_date',
      },
    ],
    modal: ['name', 'okpd2-text', 'start_date', 'end_date'],
  },
  'nsi-tnved': {
    name: 'ТН ВЭД',
    title: 'ТН ВЭД',
    apiUrl: '/api/nci/tnved',
    createUrl: '/api/nci/tnved',
    additionalApiUrl: '/api/nci/okpd2',
    catalogTnvedApiUrl: '/api/nci/tnved',
    storeSetter: 'nsi/setTnvedList',
    storeGetter: 'nsi/getTnvedList',
    headers: [
      {
        text: 'Действия',
        value: 'actions',
      },
      {
        text: 'Код ТН ВЭД',
        value: 'code',
      },
      {
        text: 'Наименование',
        value: 'name',
      },
      {
        text: 'Действует с',
        value: 'start_date',
      },
      {
        text: 'Действует по',
        value: 'end_date',
      },
    ],
    modal: ['tnved_number', 'name', 'start_date', 'end_date'],
    meta: {
      breadcrumb: [
        { name: 'ТН ВЭД' },
      ],
    },
  },
  'nsi-okpd2-msh': {
    apiUrl: '/api/nci/okpd2/msh',
    additionalApiUrl: '/api/nci/okpd2/msh',
    storeSetter: {
      is_grain: 'nsi/setOkpd2MshGrain',
      is_product: 'nsi/setOkpd2MshProduct',
      use_lot_period_product: 'nsi/setOkpd2MshPeriodProduct',
      use_lot_period_grain: 'nsi/setOkpd2MshPeriodGrain',
    },
    storeGetter: {
      is_grain: 'nsi/getOkpd2MshGrain',
      is_product: 'nsi/getOkpd2MshProduct',
      use_lot_period_product: 'getOkpd2MshPeriodProduct',
      use_lot_period_grain: 'getOkpd2MshPeriodGrain',
    },
  },
  'nsi-okpd2-codes': {
    apiUrl: '/api/nci/okpd2/codes',
  },
  'property-right': {
    name: 'Сведения о собственности',
    title: 'Сведения о собственности',
    apiUrl: '/api/nci/propertyRight',
    additionalApiUrl: '/api/nci/propertyRight',
    storeSetter: 'nsi/setPropertyRights',
    storeGetter: 'nsi/getPropertyRights',

    headers: [
      {
        text: 'Действия',
        value: 'actions',
      },
      {
        text: 'Наименование',
        value: 'name',
      },
      {
        text: 'Действует с ',
        value: 'start_date',
      },
      {
        text: 'Действует по ',
        value: 'end_date',
      },
    ],
    modal: ['name', 'start_date', 'end_date'],
  },
  'property-right-transfer-doc-type': {
    name: 'Документы, подтверждающие переход права собственности',
    title: 'Документы, подтверждающие переход права собственности',
    apiUrl: '/api/nci/propertyRightTransverDocType',
    additionalApiUrl: '/api/nci/propertyRightTransverDocType',
    storeSetter: 'nsi/setPropertyRightTransferDocTypes',
    storeGetter: 'nsi/getPropertyRightTransferDocTypes',

    headers: [
      {
        text: 'Действия',
        value: 'actions',
      },
      {
        text: 'Наименование',
        value: 'name',
      },
      {
        text: 'Действует с ',
        value: 'start_date',
      },
      {
        text: 'Действует по ',
        value: 'end_date',
      },
    ],
    modal: ['name', 'start_date', 'end_date'],
  },
  'lot-document-type': {
    name: 'Документы на партию',
    title: 'Документы на партию',
    apiUrl: '/api/nci/lotDocumentType',
    additionalApiUrl: '/api/nci/lotDocumentType',
    storeSetter: 'lot/setDocumentTypes',
    storeGetter: 'lot/getDocumentTypes',

    headers: [
      {
        text: 'Действия',
        value: 'actions',
      },
      {
        text: 'Наименование',
        value: 'name',
      },
      {
        text: 'Действует с ',
        value: 'start_date',
      },
      {
        text: 'Действует по ',
        value: 'end_date',
      },
    ],
    modal: ['name', 'start_date', 'end_date'],
  },
  'reason-write-off': {
    name: 'Причина списания',
    title: 'Причина списания',
    apiUrl: '/api/nci/reasonWriteOff',
    additionalApiUrl: '/api/nci/reasonWriteOff',
    storeSetter: 'nsi/setReasonWriteOffList',
    storeGetter: 'nsi/getReasonWriteOffList',

    headers: [
      {
        text: 'Действия',
        value: 'actions',
      },
      {
        text: 'Наименование',
        value: 'name',
      },
      {
        text: 'Действует с ',
        value: 'start_date',
      },
      {
        text: 'Действует по ',
        value: 'end_date',
      },
    ],
    modal: ['name', 'start_date', 'end_date'],
  },
  'storage-type': {
    name: 'Тип хранения',
    title: 'Тип хранения',
    apiUrl: '/api/nci/storageType',
    additionalApiUrl: '/api/nci/storageType',
    storeSetter: 'nsi/setStorageTypes',
    storeGetter: 'nsi/getStorageTypes',

    headers: [
      {
        text: 'Действия',
        value: 'actions',
      },
      {
        text: 'Наименование',
        value: 'name',
      },
      {
        text: 'Действует с ',
        value: 'start_date',
      },
      {
        text: 'Действует по ',
        value: 'end_date',
      },
    ],
    modal: ['name', 'start_date', 'end_date'],
  },
  'lot-return-reason': {
    name: 'Причины возврата партии',
    title: 'Причины возврата партии',
    apiUrl: '/api/nci/lotReturnReason',
    additionalApiUrl: '/api/nci/lotReturnReason',
    storeSetter: 'lot/setReturnReasons',
    storeGetter: 'lot/getReturnReasons',

    headers: [
      {
        text: 'Действия',
        value: 'actions',
      },
      {
        text: 'Наименование',
        value: 'name',
      },
      {
        text: 'Действует с ',
        value: 'start_date',
      },
      {
        text: 'Действует по ',
        value: 'end_date',
      },
    ],
    modal: ['name', 'start_date', 'end_date'],
  },
  'weight-disperancy-cause': {
    name: 'Причины расхождения веса',
    title: 'Причины расхождения веса',
    apiUrl: '/api/nci/weightDisperancyCause',
    additionalApiUrl: '/api/nci/weightDisperancyCause',
    storeSetter: 'nsi/setWeightDisperancyCauses',
    storeGetter: 'nsi/getWeightDisperancyCauses',

    headers: [
      {
        text: 'Действия',
        value: 'actions',
      },
      {
        text: 'Наименование',
        value: 'name',
      },
      {
        text: 'Действует с ',
        value: 'start_date',
      },
      {
        text: 'Действует по ',
        value: 'end_date',
      },
    ],
    modal: ['name', 'start_date', 'end_date'],
  },
};

export default nsiList;
