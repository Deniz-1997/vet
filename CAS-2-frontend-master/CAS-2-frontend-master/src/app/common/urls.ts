export class Urls {
  static api = '/api/';
  static apiOAuthV2 = Urls.api + 'oauth/v2/';
  static apiOAuthToken = Urls.apiOAuthV2 + 'token/';

  static apiAccount = Urls.api + 'account/';
  static apiAccountUser = Urls.apiAccount + 'user/';
  static apiAccountLogout = Urls.apiAccount + 'logout/';
  static apiAccountPassword = Urls.apiAccount + 'password/';
  static apiRestoreCheckCode = Urls.apiAccount + 'check-confirmation-code/';
  static apiAccountPasswordRecovery = Urls.apiAccount + 'password-recovery/';

  static apiUsersBEReport = Urls.api + 'print-report/usersBusinessEntity/';
  static apiReportsCountReport = Urls.api + 'print-report/reportsCount/';
  static apiReportsCountByTypeReport = Urls.api + 'print-report/reportsCountByType/';
  static apiDaData = 'https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/fio';
  static apiEsiaUrl = Urls.api + 'esia/';
}
