import { Certificate } from '@/libs/cadesplugin';

export type FormDataItem = {
  [key: string]: string | Blob;
}

export interface SignData {
  [key: string]: FormDataItem;
}

export interface CryptoProSignature {
  certificate: Certificate;
  signature: string;
}
