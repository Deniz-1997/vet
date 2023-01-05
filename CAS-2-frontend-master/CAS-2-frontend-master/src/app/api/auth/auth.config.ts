export class AuthConfig {
  tokenName: string;
  credentials: {client_id: string, client_secret: string};
  urls: {
    api?: string| Array<string>,
    apiOAuthToken?: string,
    apiAccountUser?: string,
    apiAccountLogout?: string,
    apiAccountPassword?: string,
    apiAccountPasswordRecovery?: string,
    apiRestoreCheckCode?: string,
  } = {};
  authUrl: string;
  userKey: string;
}
