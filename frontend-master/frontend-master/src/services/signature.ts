/* eslint-disable max-lines-per-function */
import { Service } from '@/plugins/service';
import {
  CADESCOM_CADES_TYPE,
  CADESCOM_CONTENT_ENCODING_TYPE,
  CADESCOM_HASH_ALGORITHM,
  CAPICOM_CERTIFICATE_FIND_TYPE,
  CAPICOM_KEY_USAGE,
  CAPICOM_STORE_LOCATION,
  CAPICOM_STORE_NAME,
  CAPICOM_STORE_OPEN_MODE,
} from '@/services/enums/signature';

export default class Signature extends Service {
  static get PLUGIN() {
    if ('cadesplugin' in window) {
      return window['cadesplugin'];
    }
  }

  private log(payload: string | Error, ...args) {
    if (this.$config.isDev || window['enableCadesLogging'] === true) {
      const method: 'info' | 'error' = payload instanceof Error ? 'error' : 'info';
      const message: string = payload instanceof Error ? payload.message : payload;

      // eslint-disable-next-line no-console
      console[method]('[giszp@cades]:', message, ...args);
    }
  }

  private async getHash(data: string | Blob) {
    const dataToSign = data instanceof Blob ? await this.fileToBase64(data) : data;
    return new Promise((resolve, reject) => {
      // eslint-disable-next-line @typescript-eslint/no-this-alias
      const ctx = this;
      Signature.PLUGIN.async_spawn(function* () {
        try {
          const oHashedData: CAdESCOM.CPHashedDataAsync = (yield Signature.PLUGIN.CreateObjectAsync(
            'CAdESCOM.HashedData'
          )) as unknown as CAdESCOM.CPHashedDataAsync;
          yield oHashedData.propset_Algorithm(CADESCOM_HASH_ALGORITHM.CADESCOM_HASH_ALGORITHM_CP_GOST_3411_2012_256);
          yield oHashedData.propset_DataEncoding(CADESCOM_CONTENT_ENCODING_TYPE.CADESCOM_BASE64_TO_BINARY);
          yield oHashedData.Hash(dataToSign);
          const result = yield oHashedData.Value;
          ctx.log(`File hash is "${result}"`);
          resolve(result);
        } catch (err) {
          ctx.log(err as unknown as Error);
          reject(err);
        }
      });
    });
  }

  private async findCertificates(store: CAPICOM.StoreAsync, findType: CAPICOM_CERTIFICATE_FIND_TYPE, criteria: string) {
    const certificates = await (
      await (await store.Certificates).Find(CAPICOM_CERTIFICATE_FIND_TYPE.CAPICOM_CERTIFICATE_FIND_TIME_VALID)
    ).Find(
      CAPICOM_CERTIFICATE_FIND_TYPE.CAPICOM_CERTIFICATE_FIND_KEY_USAGE,
      CAPICOM_KEY_USAGE.CAPICOM_DIGITAL_SIGNATURE_KEY_USAGE
    );

    return await certificates.Find(findType, criteria);
  }

  private async openSignStore() {
    const store = await Signature.PLUGIN.CreateObjectAsync('CAdESCOM.Store');
    await store.Open(
      CAPICOM_STORE_LOCATION.CAPICOM_CURRENT_USER_STORE,
      CAPICOM_STORE_NAME.CAPICOM_MY_STORE,
      CAPICOM_STORE_OPEN_MODE.CAPICOM_STORE_OPEN_MAXIMUM_ALLOWED
    );
    return store;
  }

  private async createSigner(certificate: CAPICOM.ICertificateAsync, forHash = false) {
    try {
      const signer = await Signature.PLUGIN.CreateObjectAsync('CAdESCOM.CPSigner');
      await Promise.all([
        signer.propset_Certificate(certificate),
        signer.propset_CheckCertificate(true),
        !forHash ? signer.propset_TSAAddress('http://cryptopro.ru/tsp/') : Promise.resolve(),
      ]);
      return signer;
    } catch (err) {
      this.log(err as unknown as Error, 'Creating failed');
      throw err;
    }
  }

  private async initSign(thumbprint) {
    const oStore = await this.openSignStore();
    const oCertificates = await this.findCertificates(
      oStore,
      CAPICOM_CERTIFICATE_FIND_TYPE.CAPICOM_CERTIFICATE_FIND_SHA1_HASH,
      thumbprint
    );

    return [oStore, oCertificates];
  }

  // eslint-disable-next-line max-lines-per-function
  private signBinary(dataToSign: string, thumbprint: string): Promise<string> {
    return new Promise((resolve, reject) => {
      // eslint-disable-next-line @typescript-eslint/no-this-alias
      const ctx = this;
      Signature.PLUGIN.async_spawn(function* () {
        try {
          ctx.log('Creating signing process');
          const [oStore, oCertificates] = (yield ctx.initSign(thumbprint)) as unknown as [
            CAPICOM.StoreAsync,
            CAPICOM.ICertificatesAsync
          ];
          if (((yield oCertificates.Count) as unknown as number) <= 0) {
            const error = new Error('Certificate not found: ' + thumbprint);
            reject(error);
            throw error;
          }
          ctx.log('Creating certificate container');
          const oSigner = yield ctx.createSigner((yield oCertificates.Item(1)) as unknown as CAPICOM.ICertificateAsync);
          ctx.log(`Try sign data with certificate ${thumbprint}`);
          const oSignedData = (yield Signature.PLUGIN.CreateObjectAsync(
            'CAdESCOM.CadesSignedData'
          )) as unknown as CAdESCOM.CadesSignedDataAsync;
          yield oSignedData.propset_Content(dataToSign);
          const result = (yield oSignedData.SignCades(
            oSigner,
            CADESCOM_CADES_TYPE.CADESCOM_CADES_X_LONG_TYPE_1
          )) as unknown as string;
          resolve(result);
          yield oStore.Close();
        } catch (error) {
          ctx.log(error as unknown as Error);
          reject(error);
          throw error;
        }
      });
    });
  }

  private signFileHash(dataToSign: string, thumbprint: string): Promise<string> {
    return new Promise((resolve, reject) => {
      // eslint-disable-next-line @typescript-eslint/no-this-alias
      const ctx = this;
      Signature.PLUGIN.async_spawn(function* () {
        try {
          ctx.log('Creating signing process');
          const [_oStore, oCertificates] = (yield ctx.initSign(thumbprint)) as unknown as any;
          if (((yield oCertificates.Count) as unknown as number) <= 0) {
            const error = new Error('Certificate not found: ' + thumbprint);
            reject(error);
            throw error;
          }
          const sHashValue = (yield ctx.getHash(dataToSign)) as unknown as string;
          const hashAlg = CADESCOM_HASH_ALGORITHM.CADESCOM_HASH_ALGORITHM_CP_GOST_3411_2012_256;
          const oHashedData = (yield Signature.PLUGIN.CreateObjectAsync('CAdESCOM.HashedData')) as any;
          yield oHashedData.propset_Algorithm(hashAlg);
          yield oHashedData.SetHashValue(sHashValue);
          ctx.log('Creating certificate container');
          const oSigner = yield ctx.createSigner((yield oCertificates.Item(1)) as any, true);
          ctx.log(`Try sign data with certificate ${thumbprint}`);
          const oSignedData: any = yield Signature.PLUGIN.CreateObjectAsync('CAdESCOM.CadesSignedData');
          const sSignedMessage = (yield oSignedData.SignHash(
            oHashedData,
            oSigner,
            CADESCOM_CADES_TYPE.CADESCOM_CADES_BES
          )) as unknown as string;
          const oSignedData2: any = yield Signature.PLUGIN.CreateObjectAsync('CAdESCOM.CadesSignedData');
          yield oSignedData2.VerifyHash(oHashedData, sSignedMessage, CADESCOM_CADES_TYPE.CADESCOM_CADES_BES);
          resolve(sSignedMessage);
        } catch (error) {
          ctx.log(error as unknown as Error);
          reject(error);
          throw error;
        }
      });
    });
  }

  private verifyBinary(sSignedMessage: string) {
    // eslint-disable-next-line @typescript-eslint/no-this-alias
    const ctx = this;
    return new Promise(function (resolve, reject) {
      Signature.PLUGIN.async_spawn(function* () {
        ctx.log('Verify signing');
        const oSignedData = (yield Signature.PLUGIN.CreateObjectAsync(
          'CAdESCOM.CadesSignedData'
        )) as unknown as CAdESCOM.CadesSignedDataAsync;
        try {
          resolve(yield oSignedData.VerifyCades(sSignedMessage, CADESCOM_CADES_TYPE.CADESCOM_CADES_X_LONG_TYPE_1));
        } catch (error) {
          ctx.log(error as unknown as Error);
          reject(error);
          throw error;
        }
      });
    });
  }

  private fileToBase64(file: Blob): Promise<string> {
    const reader = new FileReader();

    if (file.size > 1024 * 1024 * 128) {
      throw new Error('Файл слишком большой');
    }

    reader.readAsDataURL(file);
    return new Promise((resolve) => {
      reader.onload = () => {
        const header = ';base64,';
        const fileData = reader.result as string;
        const fileContent = fileData.substr(fileData.indexOf(header) + header.length);
        resolve(fileContent);
      };
    });
  }

  async signData(data: string | Blob, thumbprint: string) {
    this.log(`Start signing file`);
    const dataToSign = data instanceof Blob ? await this.fileToBase64(data) : data;
    const result = await this.signBinary(dataToSign, thumbprint);

    try {
      await this.verifyBinary(result);
      this.log(`Signing finished sucessfully`);
      return result;
    } catch (error) {
      return error;
    }
  }

  async signHash(data: string | Blob, thumbprint: string) {
    this.log(`Start signing file by hash`);
    const dataToSign = data instanceof Blob ? await this.fileToBase64(data) : data;
    const result = await this.signFileHash(dataToSign, thumbprint);
    this.log(`Signing finished sucessfully`);
    return result;
  }
}
