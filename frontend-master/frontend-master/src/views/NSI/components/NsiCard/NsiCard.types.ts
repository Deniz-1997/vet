/** Список параметров для таблицы. */
export type TTableItem = {
  code: string;
  actions: string;
  okpd2: string;
  tnved: TTnved[];
  name: string;
  symbol: string;
  qualityIndicators: string;
  purpose?: TPuproses;
};

/** Список ограничений. */
export type TPuproses = {
  id: number;
  name: string;
  quality_indicator_purpose_id: {
    quality_indicator_id: number;
    ind_purpose: 1;
  };
  startDate: string;
  startTime: string;
  start_date: string;
};

/** Список параметров формы. */
export type TForm = {
  id?: string;
  name?: string;
  grain_sign?: boolean;
  tnved?: string;
  okpd2_array: TTableItem[];
  productAndOkpd2: TTableItem[];
  limits: TTableItem[];
  startDate?: string;
  start_date?: string;
  endDate?: string;
  end_date?: string;
  value_from?: number;
  value_to?: number;
  purposes?: TAdditionalDataItem[];
  purposes_name?: string;
  unitOfMeasure?: TTableItem;
  limit: TTableItem;
};

/** Список параметров для ТНВЭД. */
export type TTnved = {
  code: string;
  name: string;
  full_name: string;
};

export type TAdditionalDataItem = {
  code: string;
  id: number;
  is_actual?: boolean;
  is_grain?: boolean;
  is_grain_ru?: string;
  is_product_ru?: string;
  name: string;
  startDate: string;
  startTime: string;
  start_date: string;
  tnved?: string;
  okpd2?: TOkpd;
  disabled?: boolean;
};

export type TOkpd = {
  code: string;
  id: number;
  is_grain: boolean;
  name: string;
  startDate: string;
  startTime: string;
  start_date: string;
};
