export interface Certificate {
  thumbprint: string;
  serial: number;
  issuerName: string;
  issuerOrganization: string;
  subjectName: string;
  subjectOrganization: string;
  validFrom: string;
  validTo: string;
}

export function enlistCertificates(): Promise<Certificate[]>;

export function isPluginInstalled(callback: () => void, callbackError: (error: string) => void): Promise<void>;

export function signData(thumbprint: string, data: string): Promise<string>;
