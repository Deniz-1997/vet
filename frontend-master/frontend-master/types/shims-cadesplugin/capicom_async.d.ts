declare namespace CAPICOM {
    import { CAPICOM_CERTIFICATE_FIND_TYPE, CAPICOM_STORE_LOCATION, CAPICOM_STORE_NAME, CAPICOM_STORE_OPEN_MODE } from "@/services/enums/signature";

    interface ICertificateAsync {
        readonly Version: Promise<number>;
        readonly Thumbprint: Promise<string>;
        readonly SubjectName: Promise<string>;
        readonly IssuerName: Promise<string>;
        readonly PrivateKey: Promise<string>;
        readonly SerialNumber: Promise<string>;
        readonly ValidFromDate: Promise<string>;
        readonly ValidToDate: Promise<string>;
    }

    interface ICertificatesAsync {
        readonly Count: Promise<number>;
        Item(index: number): Promise<ICertificateAsync>;
        Find(findType: CAPICOM_CERTIFICATE_FIND_TYPE, varCriteria?: any, bFindValidOnly?: boolean): Promise<ICertificatesAsync>;
    }

    interface StoreAsync {
        Open(location?: CAPICOM_STORE_LOCATION, name?: CAPICOM_STORE_NAME, openMode?: CAPICOM_STORE_OPEN_MODE): Promise<void>;
        Close(): Promise<void>;
        Delete(): Promise<boolean>;
        readonly Certificates: Promise<ICertificatesAsync>;
    }
}
