export type TDeclarationInfo = {
  id: number;
  declaration_info_id: number;
  product_name: string;
  tnved_code: string;
  sender_name: string;
  sender_inn: string;
  sender_kpp: string;
  recipient_name: string;
  recipient_inn: string;
  recipient_kpp: string;
  product_count: number;
  measure_unit: string;
};

export type TDeclarationLogRequestItem = {
  id: number;
  declaration_number: string;
  export_date: string;
  start_date: string;
  end_date?: string;
  inn: string;
  is_temp?: boolean;
  status_id: number;
  status: { name: string };
  xml_content?: string;
  custom_declaration_info: TDeclarationInfo[];
};
