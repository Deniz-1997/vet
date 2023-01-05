/* eslint-disable */
import './cadesplugin_api.js'

/**
 * @description Методы библиотеки cadesplugin_api
 */
const PLUGIN = cadesplugin;

/**
 * @description Константа используется при получении подписи с бэкенда
 *
 * @type {string}
 */
export const METHOD_GET = 'get';

/**
 * @description Константа используется при записи/отправке подписи на бэкенд
 *
 * @type {string}
 */
export const METHOD_SET = 'set';

/**
 * @description Создает объект плагина
 *
 * @returns {Promise<*>}
 */
async function connect() {
    return await PLUGIN.CreateObjectAsync('CAdESCOM.Store')
}

/**
 * @param {object} store Результат функции connect()
 * @returns {Promise<*>}
 */
async function open(store) {
    return store.Open(
        PLUGIN.CAPICOM_CURRENT_USER_STORE,
        PLUGIN.CAPICOM_MY_STORE
    )
}

/**
 * @param {object} store Результат функции connect()
 */
function close(store) {
    return store.Close()
}

/**
 * @param value
 * @param key
 * @returns {*|string}
 */
function extract(value, key) {
    let regexp = new RegExp(`${key}=(.+?)(?:,|$)`)
    let matches = value.match(regexp)
    return matches ? matches[1] : ''
}

/**
 * @description Поиск сертификатов
 *
 * @param {object} store Результат функции connect()
 * @param {int} findType Тип поиска
 * @param {string} criteria Критерии поиска(напр. отпечаток сертификата)
 * @returns {Promise<*>}
 */
async function findCertificates(store, findType, criteria) {
    let certificates = await store.Certificates
    let timeValid = await certificates.Find(PLUGIN.CAPICOM_CERTIFICATE_FIND_TIME_VALID)
    let keyUsage = await timeValid.Find(
        PLUGIN.CAPICOM_CERTIFICATE_FIND_KEY_USAGE,
        PLUGIN.CAPICOM_DIGITAL_SIGNATURE_KEY_USAGE
    )
    return await keyUsage.Find(findType, criteria)
}

/**
 * @description Проверяет устанвленл ли плагин КриптоПро в браузер
 *
 * @param {function} callback функция которая будет вызвана, если плагин установлен
 * @param {function} callbackError функция которая будет вызвана, если плагин не установлен
 */
export function isPluginInstalled(callback = () => {}, callbackError = (error) => {}) {
    let count = 0
    let inervalId = setInterval(async () => {
        try {
            await PLUGIN.CreateObjectAsync('CAdESCOM.About')
            clearInterval(inervalId)
            callback()
        } catch (e) {
            count++
            if (count > 5) {
                clearInterval(inervalId)
                callbackError('Плагин не установлен')
            }
        }
    }, 500);
}

/**
 * @description Возвращает список сертификатов
 * @returns {Promise<[]|*[]>|array}
 */
export async function enlistCertificates() {
let store = await connect()
    let result = []

    try {
        await open(store)
        let found = await findCertificates(store, PLUGIN.CAPICOM_CERTIFICATE_FIND_SUBJECT_NAME, '')
        let count = await found.Count
        for (let i = 1; i <= count; i++) {
            let cert = await found.Item(i)
            let issuer = await cert.IssuerName
            let subject = await cert.SubjectName
            let certificate = {
                thumbprint: await cert.Thumbprint,
                serial: await cert.SerialNumber,
                issuerName: extract(issuer, 'CN'),
                issuerOrganization: extract(issuer, 'O'),
                subjectName: extract(subject, 'CN'),
                subjectOrganization: extract(subject, 'O'),
                validFrom: new Date(await cert.ValidFromDate),
                validTo: new Date(await cert.ValidToDate)
            }
            result.push(certificate)
        }
        await close(store)
        return result
    } catch (e) {
        close(store)
        console.error(e.message)
    }

    return []
}

/**
 * @description Подписывает данные
 *
 * @param {string} thumbprint Отпечаток сертификата
 * @param {string} data Данные для подписиь
 * @returns {Promise<*>|string}
 */
export async function signData(thumbprint, data) {
    let store = await connect()
    let signedMessage = '';

    console.log(thumbprint)

    try {
        await open(store)
        let certificates = await findCertificates(
            store,
            PLUGIN.CAPICOM_CERTIFICATE_FIND_SHA1_HASH,
            thumbprint
        )
        let count = await certificates.Count
        if (count < 1)
            throw new Error('Сертификат не найден')

        let certificate = await certificates.Item(1)
        let signer = await PLUGIN.CreateObjectAsync('CAdESCOM.CPSigner')
        await signer.propset_Certificate(certificate)
        let signedData = await PLUGIN.CreateObjectAsync('CAdESCOM.CadesSignedData')
        await signedData.propset_ContentEncoding(PLUGIN.CADESCOM_BASE64_TO_BINARY)
        
        // await signedData.propset_Content(data)
        signedMessage = await signedData.SignCades(signer, PLUGIN.CADESCOM_CADES_BES, true)
        close(store)
    } catch (e) {
        close(store)
        console.error(e.message)
        throw new Error(e.message)
    }

    return signedMessage;
}

/**
 * TODO доработать, когда будет готов МС
 * Сделана для получения и отправки подписей, т.к. пока не явна работа с МС
 *
 * @description Отправка данных на бэкенд.
 *
 * @param {string} data Данные для подписи
 * @param {string} method - Получение или запись подписи
 * @returns {null|string}
 */
export function sendSignAjax(data, method = METHOD_SET) {
    let url = '/local/front/mvp_signing_document/ajaxSignDocument.php';
    let result = null;
    let ajaxResponse = {};
    let ajaxData = {
        'sessid': BX.bitrix_sessid(),
        "method": method,
        "data": {},
    };
    let hasError = false;

    try {
        if (method === METHOD_GET) {
            ajaxData.data.filename = data.file
        } else if (method === METHOD_SET) {
            ajaxData.data.sign = data.sign
        }

        $.ajax({
            url: url,
            data: ajaxData ,
            dataType: 'json' ,
            type: 'POST',
            async: false,
            success: function (data) {
                ajaxResponse = data;
                if (method === 'get') {
                    if (data.response == 'error' || !data.result.sign) {
                        hasError = true;
                        return false;
                    }

                    result = data.result.sign;
                } else if (method === 'set') {

                }
            }
        });

        if (hasError)
            throw new Error(ajaxResponse.message);

    } catch (e) {
        throw new Error(e.message);
    }

    return result;
}

/**
 * @description Получает файл в base64
 *
 * @param {object} file Загруженный пользователем файл
 * @returns {Promise<string>} Файл в base64
 */
export async function openFile (file) {
    let reader = new FileReader();
    let fileContent = '';

    if (file.size > 1024 * 1024 * 128)
        throw new Error('Файл слишком большой');

    reader.readAsDataURL(file);
    return await new Promise((resolve) => {
        reader.onload = function () {
            var header = ";base64,";
            var fileData = reader.result;
            fileContent = fileData.substr(fileData.indexOf(header) + header.length);
            resolve(fileContent);
        };
    });
}

