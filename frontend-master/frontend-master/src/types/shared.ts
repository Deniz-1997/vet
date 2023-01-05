export interface State<Code = string> {
  id: number;
  code: Code;
  name: string;
}

export type DocumentBytes = any;

export type SignatureIdentifier = string;
