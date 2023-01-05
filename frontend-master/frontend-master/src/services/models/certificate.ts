export type TCertificateItemResponse = {
  id: number;
  cert_number: string;
  subject_id: number;
  subject_dn: string;
  issuer_dn: string;
  certificate: File;
  start_date: string;
  end_date: string;
  is_root: boolean;
  verified: boolean;
  chain_cert_id: number;
  not_valid_before: string;
  not_valid_after: string;
};
