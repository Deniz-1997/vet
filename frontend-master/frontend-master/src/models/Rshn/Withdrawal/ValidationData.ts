export enum DocumentStorageType {
  ELECTRONIC = 'ELECTRONIC',
  PAPER = 'PAPER',
}

export type SdizNumberValidationData = {
  enter_date: string;
  status: string;
  type: DocumentStorageType;
  lot_number: string;
  sdiz_number: string;
  okpd2_id: number;
};

export type LotNumberValidationData = {
  enter_date: string;
  status: string;
  type: DocumentStorageType;
  lot_number: string;
  okpd2_id: number;
};
